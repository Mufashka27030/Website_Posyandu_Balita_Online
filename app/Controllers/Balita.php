<?php

namespace App\Controllers;

use App\Models\BalitaModel;
use App\Models\PengukuranModel;
use App\Models\ClassUserModel;

class Balita extends BaseController
{
    public function index()
    {
        $model = new BalitaModel();

        if ($this->isOrangtua()) {
            $balita = $model
                ->where('id_user', (int) session()->get('id'))
                ->orderBy('nama_balita', 'ASC')
                ->findAll();
        } else {
            $balita = $model
                ->orderBy('nama_balita', 'ASC')
                ->findAll();
        }

        return view('balita/index', ['balita' => $balita]);
    }

    public function tambah()
    {
        if ($this->isOrangtua()) {
            return redirect()->to('/balita');
        }

        $userModel = new ClassUserModel();
        $orangtua  = $userModel
            ->where('role', 'orangtua')
            ->findAll();

        return view('balita/tambah', ['orangtua' => $orangtua]);
    }

    public function simpan()
    {
        if ($this->isOrangtua()) {
            return redirect()->to('/balita');
        }

        if (! $this->validate($this->rules())) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $this->validator->listErrors());
        }

        // Validasi: pastikan id_user adalah orangtua yang valid
        $idUser    = (int) $this->request->getPost('id_user');
        $userModel = new ClassUserModel();
        $orangtua  = $userModel
            ->where('id_user', $idUser)
            ->where('role', 'orangtua')
            ->first();

        if (! $orangtua) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Orang tua tidak valid atau tidak ditemukan');
        }

        $model = new BalitaModel();
        $model->save($this->payload());

        return redirect()
            ->to('/balita')
            ->with('success', 'Data balita berhasil ditambahkan');
    }

    public function detail($id)
    {
        // IDOR protection: orangtua hanya bisa akses balita miliknya
        $balita = $this->findBalitaOwned((int) $id);

        if (! $balita) {
            return redirect()
                ->to('/balita')
                ->with('error', 'Data balita tidak ditemukan');
        }

        $pengukuranModel = new PengukuranModel();
        $pengukuran = $pengukuranModel
            ->where('id_balita', (int) $id)
            ->orderBy('tanggal_ukur', 'DESC')
            ->first();

        return view('balita/detail', [
            'balita'     => $balita,
            'pengukuran' => $pengukuran,
        ]);
    }

    public function edit($id)
    {
        if ($this->isOrangtua()) {
            return redirect()->to('/balita');
        }

        $model  = new BalitaModel();
        $balita = $model->find((int) $id);

        if (! $balita) {
            return redirect()
                ->to('/balita')
                ->with('error', 'Data balita tidak ditemukan');
        }

        return view('balita/edit', ['balita' => $balita]);
    }

    public function update($id)
    {
        $id = (int) $id;

        if ($this->isOrangtua()) {
            return redirect()->to('/balita');
        }

        $model = new BalitaModel();

        if (! $model->find($id)) {
            return redirect()
                ->to('/balita')
                ->with('error', 'Data balita tidak ditemukan');
        }

        if (! $this->validate($this->rules())) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $this->validator->listErrors());
        }

        $model->update($id, $this->payload());

        return redirect()
            ->to('/balita')
            ->with('success', 'Data balita berhasil diperbarui');
    }

    public function hapus($id)
    {
        $id = (int) $id;

        if ($this->isOrangtua()) {
            return redirect()->to('/balita');
        }

        $model = new BalitaModel();

        if (! $model->find($id)) {
            return redirect()
                ->to('/balita')
                ->with('error', 'Data balita tidak ditemukan');
        }

        $model->delete($id);

        return redirect()
            ->to('/balita')
            ->with('success', 'Data balita berhasil dihapus');
    }

    // ==================== HELPERS ====================

    private function getRole(): string
    {
        return strtolower((string) session()->get('role'));
    }

    private function isOrangtua(): bool
    {
        return $this->getRole() === 'orangtua';
    }

    /**
     * Cari balita dengan validasi kepemilikan (IDOR protection).
     * Orang tua hanya bisa mengakses data balita miliknya.
     */
    private function findBalitaOwned(int $id): ?array
    {
        $model = new BalitaModel();

        if ($this->isOrangtua()) {
            return $model
                ->where('id_balita', $id)
                ->where('id_user', (int) session()->get('id'))
                ->first();
        }

        return $model->find($id);
    }

    private function rules(): array
    {
        return [
            'nama_balita'   => 'required|min_length[2]|max_length[100]',
            'nama_ibu'      => 'required|min_length[2]|max_length[100]',
            'jenis_kelamin'  => 'required|in_list[L,P]',
            'tanggal_lahir'  => 'required|valid_date[Y-m-d]',
            'alamat'         => 'permit_empty|max_length[255]',
            'latitude'       => 'permit_empty|numeric',
            'longitude'      => 'permit_empty|numeric',
        ];
    }

    private function payload(): array
    {
        return [
            'id_user'       => (int) $this->request->getPost('id_user'),
            'nama_balita'   => trim((string) $this->request->getPost('nama_balita')),
            'nama_ibu'      => trim((string) $this->request->getPost('nama_ibu')),
            'jenis_kelamin'  => (string) $this->request->getPost('jenis_kelamin'),
            'tanggal_lahir'  => (string) $this->request->getPost('tanggal_lahir'),
            'alamat'         => trim((string) $this->request->getPost('alamat')),
            'latitude'       => $this->nullablePost('latitude'),
            'longitude'      => $this->nullablePost('longitude'),
        ];
    }

    private function nullablePost(string $field): ?string
    {
        $value = trim((string) $this->request->getPost($field));
        return $value === '' ? null : $value;
    }
}