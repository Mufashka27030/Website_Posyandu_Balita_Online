<?php

namespace App\Controllers;

use App\Models\BalitaModel;
use App\Models\PuskesmasModel;

class Puskesmas extends BaseController
{
    public function index()
    {
        $model     = new PuskesmasModel();
        $puskesmas = $model->orderBy('nama_puskesmas', 'ASC')->findAll();

        return view('puskesmas/index', ['puskesmas' => $puskesmas]);
    }

    public function tambah()
    {
        return view('puskesmas/tambah');
    }

    public function simpan()
    {
        if (! $this->validate($this->rules())) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $this->validator->getErrors());
        }

        $model = new PuskesmasModel();
        $model->insert($this->payload());

        return redirect()
            ->to('/puskesmas')
            ->with('success', 'Data puskesmas berhasil ditambahkan');
    }

    public function edit($id)
    {
        $model     = new PuskesmasModel();
        $puskesmas = $model->find((int) $id);

        if (! $puskesmas) {
            return redirect()
                ->to('/puskesmas')
                ->with('error', 'Data puskesmas tidak ditemukan');
        }

        return view('puskesmas/edit', ['puskesmas' => $puskesmas]);
    }

    public function update($id)
    {
        $id    = (int) $id;
        $model = new PuskesmasModel();

        if (! $model->find($id)) {
            return redirect()
                ->to('/puskesmas')
                ->with('error', 'Data puskesmas tidak ditemukan');
        }

        if (! $this->validate($this->rules())) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $this->validator->getErrors());
        }

        $model->update($id, $this->payload());

        return redirect()
            ->to('/puskesmas')
            ->with('success', 'Data puskesmas berhasil diperbarui');
    }

    public function hapus($id)
    {
        $id    = (int) $id;
        $model = new PuskesmasModel();

        if (! $model->find($id)) {
            return redirect()
                ->to('/puskesmas')
                ->with('error', 'Data puskesmas tidak ditemukan');
        }

        // Lepas dulu balita yang masih terhubung ke puskesmas ini (id_puskesmas
        // -> NULL) supaya tidak ada referensi menggantung setelah dihapus.
        (new BalitaModel())
            ->where('id_puskesmas', $id)
            ->set(['id_puskesmas' => null])
            ->update();

        $model->delete($id);

        return redirect()
            ->to('/puskesmas')
            ->with('success', 'Data puskesmas berhasil dihapus');
    }

    private function rules(): array
    {
        return [
            'nama_puskesmas' => 'required|min_length[3]|max_length[100]',
            'kelurahan'      => 'permit_empty|max_length[100]',
            'kecamatan'      => 'permit_empty|max_length[100]',
            'rw'             => 'permit_empty|max_length[10]',
            'alamat'         => 'permit_empty',
            'latitude'       => 'permit_empty|decimal|greater_than[-90]|less_than[90]',
            'longitude'      => 'permit_empty|decimal|greater_than[-180]|less_than[180]',
        ];
    }

    private function payload(): array
    {
        return [
            'nama_puskesmas' => trim((string) $this->request->getPost('nama_puskesmas')),
            'kelurahan'      => trim((string) $this->request->getPost('kelurahan')),
            'kecamatan'      => trim((string) $this->request->getPost('kecamatan')),
            'rw'             => trim((string) $this->request->getPost('rw')),
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
