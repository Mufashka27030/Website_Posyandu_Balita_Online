<?php

namespace App\Controllers;

use App\Models\ClassUserModel;

class Auth extends BaseController
{
    private const MAX_LOGIN_ATTEMPTS = 5;
    private const MAX_IP_ATTEMPTS    = 20;
    private const LOCKOUT_SECONDS   = 900; // 15 menit
    private const DUMMY_HASH        = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

    // ==================== LOGIN ====================

    public function login()
    {
        if (session()->get('logged_in')) {
            return $this->redirectAfterLogin();
        }

        return view('auth/login');
    }

    public function prosesLogin()
    {
        $ip        = $this->request->getIPAddress();
        $throttler = \Config\Services::throttler();

        $model    = new ClassUserModel();
        $email    = trim((string) $this->request->getPost('email'));
        $password = (string) $this->request->getPost('password');

        // check() mengembalikan BOOL: true = boleh jalan, false = blokir.
        // Kode lama membandingkan hasilnya dengan 0 pakai ===, yang tidak pernah
        // bernilai true untuk bool — jadi rate limit sesungguhnya tidak pernah
        // aktif sama sekali dan percobaan login bisa dilakukan tanpa batas.
        $ipKey    = $this->loginThrottleKey();
        $emailKey = $email === '' ? '' : 'login_email_' . md5(strtolower($email));

        $blocked = $throttler->check($ipKey, self::MAX_IP_ATTEMPTS, self::LOCKOUT_SECONDS) === false;

        if ($emailKey !== '' && $throttler->check($emailKey, self::MAX_LOGIN_ATTEMPTS, self::LOCKOUT_SECONDS) === false) {
            $blocked = true;
        }

        if ($blocked) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terlalu banyak percobaan login. Silakan coba lagi dalam 15 menit.');
        }

        // Validasi input
        if (! $this->validate([
            'email'    => 'required|valid_email|max_length[100]',
            'password' => 'required|max_length[255]',
        ])) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Email dan password wajib diisi dengan benar');
        }

        $user = $model->where('email', $email)->first();

        // Timing attack mitigation: jalankan password_verify walau user tidak ditemukan
        $hash = $user['password'] ?? self::DUMMY_HASH;

        if (! $user || ! password_verify($password, $hash)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Email atau password salah');
        }

        // Login berhasil: lepaskan kedua bucket supaya user yang sah (dan
        // satu kantor/WiFi yang berbagi IP) tidak ikut terkunci.
        $this->resetThrottle($ipKey);
        $this->resetThrottle($emailKey);

        // Regenerate session untuk mencegah session fixation
        session()->regenerate(true);

        // Set session dengan metadata keamanan
        session()->set([
            'id'            => $user['id_user'],
            'nama'          => $user['nama'],
            'email'         => $user['email'],
            'role'          => strtolower((string) $user['role']),
            'logged_in'     => true,
            'login_time'    => time(),
            'last_activity' => time(),
            'ip_address'    => $ip,
            'user_agent'    => $this->request->getUserAgent()->getAgentString(),
        ]);

        return $this->redirectAfterLogin();
    }

    // ==================== LOGOUT ====================

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    // ==================== REGISTER ====================

    public function register()
    {
        if (! session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (! $this->canManageUsers()) {
            return redirect()
                ->to('/profil')
                ->with('error', 'Anda tidak memiliki hak akses');
        }

        return view('auth/register');
    }

    public function prosesRegister()
    {
        return $this->simpanRegister();
    }

    public function simpanRegister()
    {
        // Dulu method ini bisa diakses siapa saja tanpa login, dan field
        // 'role' diambil mentah dari POST — celah privilege escalation
        // (orang luar bisa kirim role=admin dan langsung jadi admin).
        // Sekarang disamakan dengan pengecekan di register() (GET).
        if (! session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (! $this->canManageUsers()) {
            return redirect()
                ->to('/profil')
                ->with('error', 'Anda tidak memiliki hak akses');
        }

        // Kader hanya boleh mendaftarkan akun orangtua; admin boleh ketiganya.
        // Ini dicek di server, bukan cuma disembunyikan lewat dropdown di view.
        $allowedRoles = $this->getRole() === 'admin'
            ? ['admin', 'kader', 'orangtua']
            : ['orangtua'];

        if (! $this->validate([
            'nama'     => 'required|min_length[3]|max_length[100]',
            'email'    => 'required|valid_email|is_unique[class_users.email]',
            // PASSWORD_DEFAULT memakai bcrypt dan hanya membaca 72 byte pertama,
            // jadi password lebih panjang dipotong diam-diam. Batas 72 membuat
            // form jujur: yang ditolak adalah yang benar-benar tidak bisa
            // dipakai, bukan yang tersimpan sebagian.
            'password' => 'required|min_length[8]|max_length[72]',
            'role'     => 'required|in_list[' . implode(',', $allowedRoles) . ']',
            'no_hp'    => 'permit_empty|max_length[20]',
            'alamat'   => 'permit_empty',
        ])) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $this->validator->getErrors());
        }

        $model = new ClassUserModel();

        $model->insert([
            'nama'     => trim((string) $this->request->getPost('nama')),
            'email'    => trim((string) $this->request->getPost('email')),
            'password' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => strtolower((string) $this->request->getPost('role')),
            'no_hp'    => trim((string) $this->request->getPost('no_hp')),
            'alamat'   => trim((string) $this->request->getPost('alamat')),
        ]);

        return redirect()
            ->to('/login')
            ->with('success', 'Registrasi berhasil');
    }

    // ==================== PROFILE ====================

    public function profil()
    {
        return $this->profile();
    }

    public function profile()
    {
        if (! session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        return view('auth/profile', [
            'nama'  => session()->get('nama'),
            'email' => session()->get('email'),
            'role'  => session()->get('role'),
        ]);
    }

    public function editProfile()
    {
    if (! session()->get('logged_in')) {
        return redirect()->to('/login');
        }

    $model = new ClassUserModel();
    $id    = (int) session()->get('id');
    $user  = $model->find($id);

    return view('auth/editprofile', [
        'nama'  => $user['nama'] ?? session()->get('nama'),
        'email' => $user['email'] ?? session()->get('email'),
        'no_hp' => $user['no_hp'] ?? '',
        'foto'  => $user['foto'] ?? '',
        'role'  => session()->get('role'),
        ]);
    }

    public function updateProfile()
    {
    if (! session()->get('logged_in')) {
        return redirect()->to('/login');
    }

    if (! $this->validate([
        'nama'  => 'required|min_length[3]|max_length[100]',
        'email' => 'required|valid_email',
        'foto'  => 'permit_empty|is_image[foto]|max_size[foto,2048]',
    ])) {
        return redirect()
            ->back()
            ->withInput()
            ->with('error', $this->validator->getErrors());
    }

    $model    = new ClassUserModel();
    $id       = (int) session()->get('id');
    $email    = trim((string) $this->request->getPost('email'));
    $existing = $model->where('email', $email)->first();

    if ($existing && (int) $existing['id_user'] !== $id) {
        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Email sudah digunakan user lain');
    }

    $nama  = trim((string) $this->request->getPost('nama'));
    $no_hp = trim((string) $this->request->getPost('no_hp'));

    $data = [
        'nama'  => $nama,
        'email' => $email,
        'no_hp' => $no_hp,
    ];

    $fotoBaru = $this->simpanFotoProfil('foto');
    if ($fotoBaru !== null) {
        $data['foto'] = $fotoBaru;
    }

    $model->update($id, $data);

    // Update session
    session()->set([
        'nama'  => $nama,
        'email' => $email,
    ]);

    if (isset($data['foto'])) {
        session()->set('foto', $data['foto']);
    }

    return redirect()
        ->to('/profil')
        ->with('success', 'Profil berhasil diperbarui');
    }

    // ==================== PASSWORD ====================

    public function password()
    {
        if (! session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        return view('auth/password');
    }

    public function updatePassword()
    {
        if (! session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (! $this->validate([
            'password_lama' => 'required|max_length[72]',
            'password_baru' => 'required|min_length[8]|max_length[72]',
            'konfirmasi'    => 'required|matches[password_baru]',
        ])) {
            return redirect()
                ->back()
                ->with('error', $this->validator->getErrors());
        }

        $model = new ClassUserModel();
        $id    = (int) session()->get('id');
        $user  = $model->find($id);

        if (! $user) {
            session()->destroy();
            return redirect()
                ->to('/login')
                ->with('error', 'Sesi tidak valid, silakan login kembali');
        }

        if (! password_verify((string) $this->request->getPost('password_lama'), $user['password'])) {
            return redirect()
                ->back()
                ->with('error', 'Password lama salah');
        }

        $model->update($id, [
            'password' => password_hash((string) $this->request->getPost('password_baru'), PASSWORD_DEFAULT),
        ]);

        return redirect()
            ->to('/profil')
            ->with('success', 'Password berhasil diperbarui');
    }

    // ==================== HELPERS ====================

    private function redirectAfterLogin()
    {
    $role = session()->get('role');

    if (in_array($role, ['admin', 'kader'], true)) {
        return redirect()->to('/dashboard');
    }

    // Orang tua → dashboard khusus
    return redirect()->to('/dashboard-orangtua');
    }

    private function getRole(): string
    {
        return strtolower((string) session()->get('role'));
    }

    private function canManageUsers(): bool
    {
        return in_array($this->getRole(), ['admin', 'kader'], true);
    }

    private function loginThrottleKey(): string
    {
        $ip = $this->request->getIPAddress();
        // Hapus semua reserved character yang ditolak cache handler: {}()/\@:
        $ip = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $ip);
        return 'login_' . $ip;
    }

    /**
     * Buang satu bucket Throttler. CodeIgniter menyimpannya dengan prefix
     * "throttler_" dan menyimpan timestamp-nya sebagai key "<key>Time", jadi
     * keduanya harus dihapus supaya bucket benar-benar mulai dari nol.
     */
    private function resetThrottle(string $key): void
    {
        if ($key === '') {
            return;
        }

        $cache = cache();
        $cache->delete('throttler_' . $key);
        $cache->delete('throttler_' . $key . 'Time');
    }
}