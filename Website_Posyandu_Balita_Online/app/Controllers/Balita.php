<?php

namespace App\Controllers;

use App\Models\BalitaModel;
use App\Models\PengukuranModel;
use App\Models\ClassUserModel;
use App\Models\PuskesmasModel;

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

        $puskesmasModel = new PuskesmasModel();
        $puskesmas      = $puskesmasModel->orderBy('nama_puskesmas', 'ASC')->findAll();

        return view('balita/tambah', ['orangtua' => $orangtua, 'puskesmas' => $puskesmas]);
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
                ->with('error', $this->validator->getErrors());
        }

        if ($this->tanggalLahirMasaDepan()) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Tanggal lahir tidak boleh di masa depan');
        }

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
        $balita = $this->findBalitaOwned((int) $id);

        if (! $balita) {
            return redirect()
                ->to('/balita')
                ->with('error', 'Data balita tidak ditemukan');
        }

        $puskesmas = null;
        if (! empty($balita['id_puskesmas'])) {
            $puskesmas = (new PuskesmasModel())->find((int) $balita['id_puskesmas']);
        }

        $pengukuranModel = new PengukuranModel();
        $pengukuran = $pengukuranModel
            ->where('id_balita', (int) $id)
            ->orderBy('tanggal_ukur', 'DESC')
            ->first();

        return view('balita/detail', [
            'balita'     => $balita,
            'puskesmas'   => $puskesmas,
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

        $puskesmasModel = new PuskesmasModel();
        $puskesmas      = $puskesmasModel->orderBy('nama_puskesmas', 'ASC')->findAll();

        return view('balita/edit', ['balita' => $balita, 'puskesmas' => $puskesmas]);
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

        // id_balita ikut dikirim ke rules() supaya is_unique tidak menabuh
        // NIK miliknya sendiri sebagai duplikat.
        if (! $this->validate($this->rules($id))) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $this->validator->getErrors());
        }

        if ($this->tanggalLahirMasaDepan()) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Tanggal lahir tidak boleh di masa depan');
        }

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

    private function rules(?int $idBalita = null): array
    {
        // NIK disimpan sebagai varchar(20) dengan index UNIQUE. Dulu aturannya
        // hanya max_length[20], jadi NIK 25 karakter tertolak di form tapi
        // format yang salah (huruf/spasi) lolos dan dua balita dengan NIK sama
        // membuat query gagal di tengah jalan.
        $nikRule = 'permit_empty|regex_match[/^\d{16}$/]';
        $nikRule .= $idBalita === null
            ? '|is_unique[balita.nik]'
            : '|is_unique[balita.nik,id_balita,' . $idBalita . ']';

        return [
            'nama_balita'   => 'required|min_length[2]|max_length[100]',
            'nik'           => $nikRule,
            'nama_ibu'      => 'required|min_length[2]|max_length[100]',
            'jenis_kelamin'  => 'required|in_list[L,P]',
            'tanggal_lahir'  => 'required|valid_date[Y-m-d]',
            'alamat'         => 'permit_empty|max_length[255]',
            'latitude'       => 'permit_empty|decimal|greater_than[-90]|less_than[90]',
            'longitude'      => 'permit_empty|decimal|greater_than[-180]|less_than[180]',
            'id_puskesmas'   => 'permit_empty|integer|is_not_unique[puskesmas.id_puskesmas]',
        ];
    }

    private function payload(): array
    {
        return [
            'id_user'       => (int) $this->request->getPost('id_user'),
            'id_puskesmas'  => $this->nullablePostInt('id_puskesmas'),
            'nama_balita'   => trim((string) $this->request->getPost('nama_balita')),
            'nik'           => $this->nullablePost('nik'),
            'nama_ibu'      => trim((string) $this->request->getPost('nama_ibu')),
            'jenis_kelamin'  => (string) $this->request->getPost('jenis_kelamin'),
            'tanggal_lahir'  => (string) $this->request->getPost('tanggal_lahir'),
            'alamat'         => trim((string) $this->request->getPost('alamat')),
            'latitude'       => $this->nullablePost('latitude'),
            'longitude'      => $this->nullablePost('longitude'),
        ];
    }

    /**
     * CI4 tidak punya rule "tanggal maksimum", jadi batas tanggal lahir dicek
     * terpisah: balita dengan tanggal lahir di masa depan membuat usia dan
     * seluruh z-score di grafik pertumbuhan jadi tidak masuk akal.
     */
    private function tanggalLahirMasaDepan(): bool
    {
        return (string) $this->request->getPost('tanggal_lahir') > date('Y-m-d');
    }

    private function nullablePostInt(string $field): ?int
    {
        $value = trim((string) $this->request->getPost($field));
        return $value === '' ? null : (int) $value;
    }

    private function nullablePost(string $field): ?string
    {
        $value = trim((string) $this->request->getPost($field));
        return $value === '' ? null : $value;
    }
}
