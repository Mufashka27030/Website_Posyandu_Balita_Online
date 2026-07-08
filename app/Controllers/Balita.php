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

        $role = strtolower((string) session()->get('role'));
        if ($role === 'orangtua') {
            $idUser = (int) session()->get('id');
            $balita = $model
                ->where('id_user', $idUser)
                ->orderBy('nama_balita', 'ASC')
                ->findAll();
        } else {
            $balita = $model
                ->orderBy('nama_balita', 'ASC')
                ->findAll();
        }

        return view('balita/index', [
            'balita' => $balita,
        ]);
    }


    public function tambah()
    {
        $userModel = new ClassUserModel();

        $data['orangtua'] = $userModel
            ->where('role','orangtua')
            ->findAll();

        return view('balita/tambah',$data);
    }

    public function simpan()
    {
        if (session()->get('role') == 'orangtua') {
            return redirect()->to('/balita');
        }

        if (! $this->validate($this->rules())) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $this->validator->listErrors());
        }

        $model = new BalitaModel();
        $model->save($this->payload());

        return redirect()
            ->to('/balita')
            ->with('success', 'Data balita berhasil ditambahkan');
    }

    public function detail($id)
    {
        $balitaModel = new BalitaModel();
        $pengukuranModel = new PengukuranModel();

        $role = strtolower((string) session()->get('role'));
        $balita = null;

        if ($role === 'orangtua') {
            $balita = $balitaModel
                ->where('id_balita', (int) $id)
                ->where('id_user', (int) session()->get('id'))
                ->first();
        } else {
            $balita = $balitaModel->find((int) $id);
        }

        if (! $balita) {
            return redirect()
                ->to('/balita')
                ->with('error', 'Data balita tidak ditemukan');
        }

        if (
            session()->get('role') == 'orangtua' &&
            $balita['id_user'] != session()->get('id')
        ) {
            return redirect()
                ->to('/balita')
                ->with('error', 'Anda tidak memiliki hak akses.');
        }


        $pengukuran = $pengukuranModel
            ->where('id_balita', (int) $id)
            ->orderBy('tanggal_ukur', 'DESC')
            ->first();

        return view('balita/detail', [
            'balita' => $balita,
            'pengukuran' => $pengukuran,
        ]);

    }

    public function edit($id)
{
    $model = new BalitaModel();

    $balita = $model->find((int)$id);

    if (!$balita) {

        return redirect()
            ->to('/balita')
            ->with('error', 'Data balita tidak ditemukan');
    }

    if (session()->get('role') == 'orangtua') {

        return redirect()->to('/balita');
    }

    return view('balita/edit', [

        'balita' => $balita

    ]);
    }

    public function update($id)
    {
        $model = new BalitaModel();
        $id = (int) $id;

        if (session()->get('role') == 'orangtua') {
            return redirect()->to('/balita');
        }

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
        $model = new BalitaModel();
        $id = (int) $id;

        if (session()->get('role') == 'orangtua') {
            return redirect()->to('/balita');
        }

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


    private function rules(): array
    {
        return [
            'nama_balita' => 'required|min_length[2]|max_length[100]',
            'nama_ibu' => 'required|min_length[2]|max_length[100]',
            'jenis_kelamin' => 'required|in_list[L,P]',
            'tanggal_lahir' => 'required|valid_date[Y-m-d]',
            'alamat' => 'permit_empty|max_length[255]',
            'latitude' => 'permit_empty|numeric',
            'longitude' => 'permit_empty|numeric',
        ];
    }

    private function payload(): array
{
    return [

        'id_user' =>
        (int)$this->request->getPost('id_user'),

        'nama_balita' =>
        trim((string)$this->request->getPost('nama_balita')),

        'nama_ibu' =>
        trim((string)$this->request->getPost('nama_ibu')),

        'jenis_kelamin' =>
        (string)$this->request->getPost('jenis_kelamin'),

        'tanggal_lahir' =>
        (string)$this->request->getPost('tanggal_lahir'),

        'alamat' =>
        trim((string)$this->request->getPost('alamat')),

        'latitude' =>
        $this->nullablePost('latitude'),

        'longitude' =>
        $this->nullablePost('longitude'),
        ];
    }

    private function nullablePost(string $field): ?string
    {
        $value = trim((string) $this->request->getPost($field));

        return $value === '' ? null : $value;
    }
}
