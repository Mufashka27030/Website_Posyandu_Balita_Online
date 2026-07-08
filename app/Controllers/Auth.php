<?php

namespace App\Controllers;

use App\Models\ClassUserModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('logged_in')) {
            return $this->redirectAfterLogin();
        }

        return view('auth/login');
    }

    public function prosesLogin()
    {
        if (! $this->validate([
            'email' => 'required|valid_email',
            'password' => 'required',
        ])) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Email dan password wajib diisi dengan benar');
        }

        $model = new ClassUserModel();
        $email = trim((string) $this->request->getPost('email'));
        $password = (string) $this->request->getPost('password');
        $user = $model->where('email', $email)->first();

        if (! $user || ! password_verify($password, $user['password'])) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Email atau password salah');
        }

        session()->regenerate(true);
        session()->set([
            'id' => $user['id_user'],
            'nama' => $user['nama'],
            'email' => $user['email'],
            'role' => strtolower((string) $user['role']),
            'logged_in' => true,
        ]);

        return $this->redirectAfterLogin();
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login');
    }

    public function register()
    {
        if (! session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (! in_array(session()->get('role'), ['admin', 'kader'], true)) {
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

        $currentRole = strtolower((string) session()->get('role'));

        if (! in_array($currentRole, ['admin', 'kader'], true)) {
            return redirect()
                ->to('/profil')
                ->with('error', 'Anda tidak memiliki hak akses');
        }

        $role = strtolower((string) $this->request->getPost('role'));
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
            'email' => 'required|valid_email|is_unique[class_users.email]',
            'password' => 'required|min_length[6]',
            'role' => 'required|in_list[admin,kader,orangtua]',
        ])) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $this->validator->listErrors());
        }

        $model = new ClassUserModel();
        $model->save([
            'nama' => trim((string) $this->request->getPost('nama_lengkap')),
            'email' => trim((string) $this->request->getPost('email')),
            'password' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => $role,
            'no_hp' => $this->request->getPost('no_hp'),
        ]);

        return redirect()
            ->to('/classuser')
            ->with('success', 'User berhasil ditambahkan');
    }

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
            'nama' => session()->get('nama'),
            'email' => session()->get('email'),
            'role' => session()->get('role'),
        ]);
    }

    public function editProfile()
    {
        if (! session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        
        return view('auth/editprofile', [
            'nama' => session()->get('nama'),
            'email' => session()->get('email'),
            'role' => session()->get('role'),
        ]);
    }

    public function updateProfile()
    {
        if (! session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (! $this->validate([
            'nama' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email',
        ])) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $this->validator->listErrors());
        }

        $model = new ClassUserModel();
        $id = (int) session()->get('id');
        $email = trim((string) $this->request->getPost('email'));
        $existing = $model->where('email', $email)->first();

        if ($existing && (int) $existing['id_user'] !== $id) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Email sudah digunakan user lain');
        }

        $nama = trim((string) $this->request->getPost('nama'));
        $model->update($id, [
            'nama' => $nama,
            'email' => $email,
        ]);

        session()->set([
            'nama' => $nama,
            'email' => $email,
        ]);

        return redirect()
            ->to('/profil')
            ->with('success', 'Profil berhasil diperbarui');
    }

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
            'konfirmasi' => 'required|matches[password_baru]',
        ])) {
            return redirect()
                ->back()
                ->with('error', $this->validator->listErrors());
        }

        $model = new ClassUserModel();
        $id = (int) session()->get('id');
        $user = $model->find($id);

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

    private function redirectAfterLogin()
    {
        if (in_array(session()->get('role'), ['admin', 'kader'], true)) {
            return redirect()->to('/dashboard');
        }

        return redirect()->to('/profil');
    }
}
