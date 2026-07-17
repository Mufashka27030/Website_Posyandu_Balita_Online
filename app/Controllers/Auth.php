<?php

namespace App\Controllers;

use App\Models\ClassUserModel;

class Auth extends BaseController
{
    private const MAX_LOGIN_ATTEMPTS = 5;
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

        // 1. Rate limiting: konsumsi token, 0 = terkunci
        if ($throttler->check($this->loginThrottleKey(), self::MAX_LOGIN_ATTEMPTS, self::LOCKOUT_SECONDS) === 0) {
            return redirect()
                ->back()
                ->with('error', 'Terlalu banyak percobaan login. Silakan coba lagi dalam 15 menit.');
        }

        // 2. Validasi input
        if (! $this->validate([
            'email'    => 'required|valid_email',
            'password' => 'required',
        ])) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Email dan password wajib diisi dengan benar');
        }

        $model    = new ClassUserModel();
        $email    = trim((string) $this->request->getPost('email'));
        $password = (string) $this->request->getPost('password');
        $user     = $model->where('email', $email)->first();

        // 3. Timing attack mitigation: jalankan password_verify walau user tidak ditemukan
        $hash = $user ? $user['password'] : self::DUMMY_HASH;

        if (! password_verify($password, $hash)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Email atau password salah');
        }

        // 4. Regenerate session untuk mencegah session fixation
        session()->regenerate(true);

        // 5. Set session dengan metadata keamanan
        session()->set([
            'id'          => $user['id_user'],
            'nama'        => $user['nama'],
            'email'       => $user['email'],
            'role'        => strtolower((string) $user['role']),
            'logged_in'   => true,
            'login_time'  => time(),
            'ip_address'  => $ip,
            'user_agent'  => $this->request->getUserAgent()->getAgentString(),
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
        if (! session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (! $this->canManageUsers()) {
            return redirect()
                ->to('/profil')
                ->with('error', 'Anda tidak memiliki hak akses');
        }

        $currentRole  = $this->getRole();
        $role         = strtolower((string) $this->request->getPost('role'));
        $allowedRoles = $currentRole === 'admin'
            ? ['admin', 'kader', 'orangtua']
            : ['orangtua'];

        if (! in_array($role, $allowedRoles, true)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Role yang dipilih tidak diizinkan');
        }

        if (! $this->validate([
            'nama_lengkap' => 'required|min_length[3]|max_length[100]',
            'email'        => 'required|valid_email|is_unique[class_users.email]',
            'password'     => 'required|min_length[6]',
            'role'         => 'required|in_list[admin,kader,orangtua]',
        ])) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $this->validator->listErrors());
        }

        $model = new ClassUserModel();
        $model->save([
            'nama'     => trim((string) $this->request->getPost('nama_lengkap')),
            'email'    => trim((string) $this->request->getPost('email')),
            'password' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => $role,
            'no_hp'    => $this->request->getPost('no_hp'),
        ]);

        return redirect()
            ->to('/classuser')
            ->with('success', 'User berhasil ditambahkan');
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
            ->with('error', $this->validator->listErrors());
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

    // Handle upload foto
    $foto = $this->request->getFile('foto');
    if ($foto && $foto->isValid() && ! $foto->hasMoved()) {
        // Buat folder jika belum ada
        $uploadPath = ROOTPATH . 'public/uploads/foto';
        if (! is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $fotoName = $foto->getRandomName();
        $foto->move($uploadPath, $fotoName);
        $data['foto'] = 'uploads/foto/' . $fotoName;
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
            'password_lama' => 'required',
            'password_baru' => 'required|min_length[6]',
            'konfirmasi'    => 'required|matches[password_baru]',
        ])) {
            return redirect()
                ->back()
                ->with('error', $this->validator->listErrors());
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
}