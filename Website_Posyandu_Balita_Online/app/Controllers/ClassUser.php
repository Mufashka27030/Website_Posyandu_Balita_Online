<?php

namespace App\Controllers;

use App\Models\ClassUserModel;

class ClassUser extends BaseController
{
    public function index()
    {
        $model   = new ClassUserModel();
        $keyword = trim((string) $this->request->getGet('keyword'));

        if ($keyword !== '') {
            $model
                ->groupStart()
                ->like('nama', $keyword)
                ->orLike('email', $keyword)
                ->groupEnd();
        }

        return view('classuser/index', [
            'users'   => $model->orderBy('nama', 'ASC')->findAll(),
            'keyword' => $keyword,
        ]);
    }

    public function tambah()
    {
        return view('classuser/tambah');
    }

    public function simpan()
    {
        $allowedRoles = strtolower((string) session()->get('role')) === 'admin'
            ? ['admin', 'kader', 'orangtua']
            : ['orangtua'];

        if (! $this->validate([
            'nama'     => 'required|min_length[3]|max_length[100]',
            'email'    => 'required|valid_email|is_unique[class_users.email]',
            'password' => 'required|min_length[8]|max_length[72]',
            'role'     => 'required|in_list[' . implode(',', $allowedRoles) . ']',
            'no_hp'    => 'permit_empty|max_length[20]',
            'alamat'   => 'permit_empty',
            'foto'     => 'permit_empty|is_image[foto]|max_size[foto,2048]',
        ])) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $this->validator->getErrors());
        }

        $model = new ClassUserModel();

        $data = [
            'nama'     => trim((string) $this->request->getPost('nama')),
            'email'    => trim((string) $this->request->getPost('email')),
            'password' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => strtolower((string) $this->request->getPost('role')),
            'no_hp'    => trim((string) $this->request->getPost('no_hp')),
            'alamat'   => trim((string) $this->request->getPost('alamat')),
        ];

        // Handle upload foto
        $data['foto'] = $this->uploadFoto();

        $model->insert($data);

        return redirect()
            ->to('/classuser')
            ->with('success', 'User baru berhasil ditambahkan');
    }

    public function edit($id)
    {
        $model = new ClassUserModel();
        $user = $model->find((int) $id);

        if (! $user) {
            return redirect()
                ->to('/classuser')
                ->with('error', 'User tidak ditemukan');
        }

        if (
            strtolower((string) session()->get('role')) !== 'admin'
            && strtolower((string) $user['role']) === 'admin'
        ) {
            return redirect()
                ->to('/classuser')
                ->with('error', 'Kader tidak memiliki hak untuk mengubah akun admin');
        }

        return view('classuser/edit', ['user' => $user]);
    }

    public function update($id)
    {
        $id    = (int) $id;
        $model = new ClassUserModel();
        $user = $model->find($id);

        if (! $user) {
            return redirect()
                ->to('/classuser')
                ->with('error', 'User tidak ditemukan');
        }

        $actorRole = strtolower((string) session()->get('role'));
        $newRole   = strtolower((string) $this->request->getPost('role'));
        $isSelf    = $id === (int) session()->get('id');
        $wasAdmin  = strtolower((string) $user['role']) === 'admin';

        if ($isSelf && $wasAdmin && $newRole !== 'admin') {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Anda tidak dapat menurunkan role akun Anda sendiri');
        }

        if ($actorRole !== 'admin' && $wasAdmin) {
            return redirect()
                ->to('/classuser')
                ->with('error', 'Kader tidak memiliki hak untuk mengubah akun admin');
        }

        $allowedRoles = $actorRole === 'admin'
            ? ['admin', 'kader', 'orangtua']
            : array_unique(['orangtua', strtolower((string) $user['role'])]);

        if (! $this->validate([
            'nama'     => 'required|min_length[3]|max_length[100]',
            'email'    => 'required|valid_email',
            'role'     => 'required|in_list[' . implode(',', $allowedRoles) . ']',
            'password' => 'permit_empty|min_length[8]|max_length[72]',
            'no_hp'    => 'permit_empty|max_length[20]',
            'alamat'   => 'permit_empty',
            'foto'     => 'permit_empty|is_image[foto]|max_size[foto,2048]',
        ])) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $this->validator->getErrors());
        }

        $email    = trim((string) $this->request->getPost('email'));
        $existing = $model->where('email', $email)->first();

        if ($existing && (int) $existing['id_user'] !== $id) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Email sudah digunakan user lain');
        }

        $data = [
            'nama'   => trim((string) $this->request->getPost('nama')),
            'email'  => $email,
            'role'   => $newRole,
            'no_hp'  => trim((string) $this->request->getPost('no_hp')),
            'alamat' => trim((string) $this->request->getPost('alamat')),
        ];

        $password = (string) $this->request->getPost('password');
        if ($password !== '') {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        // Handle upload foto — hapus foto lama jika ada foto baru
        $fotoBaru = $this->uploadFoto();
        if ($fotoBaru !== null) {
            $data['foto'] = $fotoBaru;
            if (! empty($user['foto']) && file_exists(FCPATH . $user['foto'])) {
                @unlink(FCPATH . $user['foto']);
            }
        }

        $model->update($id, $data);

        if ($isSelf) {
            session()->set([
                'nama'  => $data['nama'],
                'email' => $data['email'],
                'role'  => $data['role'],
            ]);
        }

        return redirect()
            ->to('/classuser')
            ->with('success', 'User berhasil diperbarui');
    }

    public function hapus($id)
    {
        $id = (int) $id;

        if ($id === (int) session()->get('id')) {
            return redirect()
                ->to('/classuser')
                ->with('error', 'Akun yang sedang dipakai tidak bisa dihapus');
        }

        $model = new ClassUserModel();
        $user  = $model->find($id);

        if (! $user) {
            return redirect()
                ->to('/classuser')
                ->with('error', 'User tidak ditemukan');
        }

        // Kader tidak boleh menghapus akun admin — konsisten dengan edit()
        // dan update() yang sudah lebih dulu melarang kader menyentuh akun
        // admin. Sebelumnya pengecekan ini tidak ada di sini, jadi kader
        // masih bisa menghapus admin lain selama bukan admin terakhir.
        if (
            strtolower((string) session()->get('role')) !== 'admin'
            && strtolower((string) $user['role']) === 'admin'
        ) {
            return redirect()
                ->to('/classuser')
                ->with('error', 'Kader tidak memiliki hak untuk menghapus akun admin');
        }

        if (strtolower((string) $user['role']) === 'admin') {
            $adminCount = $model->where('role', 'admin')->countAllResults();

            if ($adminCount <= 1) {
                return redirect()
                    ->to('/classuser')
                    ->with('error', 'Tidak dapat menghapus admin terakhir yang tersisa');
            }
        }

        // Hapus file foto jika ada
        if (! empty($user['foto']) && file_exists(FCPATH . $user['foto'])) {
            @unlink(FCPATH . $user['foto']);
        }

        $model->delete($id);

        return redirect()
            ->to('/classuser')
            ->with('success', 'User berhasil dihapus');
    }

    /**
     * Upload foto profil user. Path relatif atau null jika tidak ada upload valid.
     */
    private function uploadFoto(): ?string
    {
        return $this->simpanFotoProfil('foto_user');
    }
}