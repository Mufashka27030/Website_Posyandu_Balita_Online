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

        return view('user/index', [
            'users'   => $model->orderBy('nama', 'ASC')->findAll(),
            'keyword' => $keyword,
        ]);
    }

    public function tambah()
    {
        return redirect()->to('/register');
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

        return view('user/edit', ['user' => $user]);
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

        $newRole = strtolower((string) $this->request->getPost('role'));
        $isSelf  = $id === (int) session()->get('id');
        $wasAdmin = strtolower((string) $user['role']) === 'admin';

        // Hardening: admin tidak boleh menurunkan role dirinya sendiri
        if ($isSelf && $wasAdmin && $newRole !== 'admin') {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Anda tidak dapat menurunkan role akun Anda sendiri');
        }

        if (! $this->validate([
            'nama'     => 'required|min_length[3]|max_length[100]',
            'email'    => 'required|valid_email',
            'role'     => 'required|in_list[admin,kader,orangtua]',
            'password' => 'permit_empty|min_length[6]',
        ])) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $this->validator->listErrors());
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
            'nama'  => trim((string) $this->request->getPost('nama')),
            'email' => $email,
            'role'  => $newRole,
            'no_hp' => $this->request->getPost('no_hp'),
        ];

        $password = (string) $this->request->getPost('password');
        if ($password !== '') {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $model->update($id, $data);

        // Sync session jika update diri sendiri
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

        // Tidak bisa hapus akun sendiri
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

        // Hardening: cegah hapus admin terakhir
        if (strtolower((string) $user['role']) === 'admin') {
            $adminCount = $model->where('role', 'admin')->countAllResults();

            if ($adminCount <= 1) {
                return redirect()
                    ->to('/classuser')
                    ->with('error', 'Tidak dapat menghapus admin terakhir yang tersisa');
            }
        }

        $model->delete($id);

        return redirect()
            ->to('/classuser')
            ->with('success', 'User berhasil dihapus');
    }
}