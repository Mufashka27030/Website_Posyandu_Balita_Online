<?php

namespace App\Controllers;

use App\Models\BalitaModel;
use App\Models\PengukuranModel;
use App\Services\ZScoreService;

class Pengukuran extends BaseController
{
    public function index()
    {
        $role    = strtolower((string) session()->get('role'));
        $keyword = trim((string) $this->request->getGet('keyword'));
        $status  = trim((string) $this->request->getGet('status'));

        $db      = \Config\Database::connect();
        $builder = $db->table('pengukuran');
        $builder->select('pengukuran.*, balita.nama_balita, balita.jenis_kelamin');
        $builder->join('balita', 'balita.id_balita = pengukuran.id_balita', 'left');

        if ($role === 'orangtua') {
            $builder->where('balita.id_user', (int) session()->get('id'));
        }

        if ($keyword !== '') {
            $builder->like('balita.nama_balita', $keyword);
        }

        if ($status !== '' && in_array($status, ['Normal', 'Stunting', 'Stunting Berat'], true)) {
            $builder->where('pengukuran.status_gizi', $status);
        }

        $builder->orderBy('pengukuran.tanggal_ukur', 'DESC');
        $pengukuran = $builder->get()->getResultArray();

        return view('pengukuran/index', [
            'pengukuran'    => $pengukuran,
            'keyword'       => $keyword,
            'status_filter' => $status,
        ]);
    }

    public function tambah($idBalita)
    {
        $balita = (new BalitaModel())->find((int) $idBalita);

        if (! $balita) {
            return redirect()
                ->to('/balita')
                ->with('error', 'Data balita tidak ditemukan');
        }

        return view('pengukuran/tambah', ['balita' => $balita]);
    }

    public function simpan()
    {
        if (! $this->validate($this->rules())) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $this->validator->listErrors());
        }

        $balitaModel     = new BalitaModel();
        $pengukuranModel = new PengukuranModel();
        $idBalita        = (int) $this->request->getPost('id_balita');
        $balita          = $balitaModel->find($idBalita);

        if (! $balita) {
            return redirect()
                ->to('/balita')
                ->with('error', 'Data balita tidak ditemukan');
        }

        $usiaForm = (int) $this->request->getPost('usia_bulan');

        try {
            $hasil = (new ZScoreService())->hitungStatus(
                $balita,
                (string) $this->request->getPost('tanggal_ukur'),
                (float) $this->request->getPost('tinggi_badan'),
                $usiaForm > 0 ? $usiaForm : null
            );
        } catch (\RuntimeException $exception) {
            return redirect()->back()->withInput()->with('error', $exception->getMessage());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $pengukuranModel->save([
            'id_balita'    => $idBalita,
            'tanggal_ukur' => (string) $this->request->getPost('tanggal_ukur'),
            'usia_bulan'   => $hasil['usia_bulan'],
            'berat_badan'  => (float) $this->request->getPost('berat_badan'),
            'tinggi_badan' => (float) $this->request->getPost('tinggi_badan'),
            'z_score'      => $hasil['z_score'],
            'status_gizi'  => $hasil['status'],
            'warna_kms'    => $hasil['warna'],
            'catatan'      => trim((string) $this->request->getPost('catatan')),
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data pengukuran');
        }

        return view('pengukuran/hasil', [
            'balita'       => $balita,
            'usia_bulan'   => $hasil['usia_bulan'],
            'tanggal_ukur' => (string) $this->request->getPost('tanggal_ukur'),
            'berat_badan'  => (float) $this->request->getPost('berat_badan'),
            'tinggi_badan' => (float) $this->request->getPost('tinggi_badan'),
            'zscore'       => $hasil['z_score'],
            'status'       => $hasil['status'],
            'warna'        => $hasil['warna'],
        ]);
    }

    public function riwayat($idBalita)
    {
        $role            = strtolower((string) session()->get('role'));
        $balitaModel     = new BalitaModel();
        $pengukuranModel = new PengukuranModel();
        $idBalita        = (int) $idBalita;
        $balita          = $balitaModel->find($idBalita);

        if (! $balita) {
            return redirect()->to('/balita')->with('error', 'Data balita tidak ditemukan');
        }

        if ($role === 'orangtua' && (int) $balita['id_user'] !== (int) session()->get('id')) {
            return redirect()->to('/balita')->with('error', 'Data balita tidak ditemukan');
        }

        $cariTanggal = trim((string) $this->request->getGet('cari_tanggal'));
        $status      = trim((string) $this->request->getGet('status'));

        $query = $pengukuranModel->where('id_balita', $idBalita);

        if ($cariTanggal !== '') {
            $query = $query->where('tanggal_ukur', $cariTanggal);
        }
        if ($status !== '' && in_array($status, ['Normal', 'Stunting', 'Stunting Berat'], true)) {
            $query = $query->where('status_gizi', $status);
        }

        $riwayat = $query->orderBy('tanggal_ukur', 'DESC')->findAll();

        return view('pengukuran/riwayat', [
            'balita'        => $balita,
            'riwayat'       => $riwayat,
            'cari_tanggal'  => $cariTanggal,
            'status_filter' => $status,
        ]);
    }

    public function grafik($idBalita)
    {
        $role            = strtolower((string) session()->get('role'));
        $balitaModel     = new BalitaModel();
        $pengukuranModel = new PengukuranModel();
        $idBalita        = (int) $idBalita;
        $balita          = $balitaModel->find($idBalita);

        if (! $balita) {
            return redirect()
                ->to('/balita')
                ->with('error', 'Data balita tidak ditemukan');
        }

        if ($role === 'orangtua' && (int) $balita['id_user'] !== (int) session()->get('id')) {
            return redirect()
                ->to('/balita')
                ->with('error', 'Data balita tidak ditemukan');
        }

        $riwayat = $pengukuranModel
            ->where('id_balita', $idBalita)
            ->orderBy('usia_bulan', 'ASC')
            ->findAll();

        $labels = [];
        $tinggi = [];
        $berat  = [];

        foreach ($riwayat as $row) {
            $labels[] = (int) $row['usia_bulan'];
            $tinggi[] = (float) $row['tinggi_badan'];
            $berat[]  = (float) $row['berat_badan'];
        }

        $zScoreService = new ZScoreService();
        $whoMedian = [];
        $whoMinus2 = [];
        $whoMinus3 = [];

        foreach ($labels as $usiaBulan) {
            $standar = $zScoreService->getStandarWho((string) $balita['jenis_kelamin'], $usiaBulan);

            if ($standar) {
                $median   = (float) $standar['median'];
                $sdMinus2 = (float) $standar['sd_minus_2'];
                $sd       = ($median - $sdMinus2) / 2;

                $whoMedian[] = $median;
                $whoMinus2[] = $sdMinus2;
                $whoMinus3[] = $median - ($sd * 3);
            } else {
                $whoMedian[] = null;
                $whoMinus2[] = null;
                $whoMinus3[] = null;
            }
        }

        $alert = '';

        if (count($tinggi) >= 3) {
            $last = count($tinggi) - 1;

            if ($tinggi[$last] <= $tinggi[$last - 1]) {
                $alert = 'Pertumbuhan anak stagnan';
            }
        }

        // Issue #7: Tentukan URL tombol kembali berdasarkan parameter ?from=
        $from = trim((string) $this->request->getGet('from'));
        $backUrl = $from === 'riwayat'
            ? '/pengukuran/riwayat/' . $idBalita
            : '/balita/detail/' . $idBalita;

        $jsonFlags = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP;

        return view('pengukuran/grafik', [
            'balita'    => $balita,
            'labels'    => json_encode($labels, $jsonFlags),
            'tinggi'    => json_encode($tinggi, $jsonFlags),
            'berat'     => json_encode($berat, $jsonFlags),
            'whoMedian' => json_encode($whoMedian, $jsonFlags),
            'whoMinus2' => json_encode($whoMinus2, $jsonFlags),
            'whoMinus3' => json_encode($whoMinus3, $jsonFlags),
            'alert'     => $alert,
            'back_url'  => $backUrl,
        ]);
    }

    public function edit($id)
    {
        $pengukuran = (new PengukuranModel())->find((int) $id);

        if (! $pengukuran) {
            return redirect()
                ->to('/balita')
                ->with('error', 'Data pengukuran tidak ditemukan');
        }

        return view('pengukuran/edit', ['pengukuran' => $pengukuran]);
    }

    public function update($id)
    {
        // Issue #4: tanggal_ukur & usia_bulan sekarang bisa diedit
        if (! $this->validate([
            'tanggal_ukur' => 'required|valid_date[Y-m-d]',
            'usia_bulan'   => 'required|integer|greater_than_equal_to[0]',
            'berat_badan'  => 'required|numeric|greater_than[0]',
            'tinggi_badan' => 'required|numeric|greater_than[0]',
            'catatan'      => 'permit_empty',
        ])) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $this->validator->listErrors());
        }

        $pengukuranModel = new PengukuranModel();
        $balitaModel     = new BalitaModel();
        $id              = (int) $id;
        $pengukuran      = $pengukuranModel->find($id);

        if (! $pengukuran) {
            return redirect()
                ->to('/balita')
                ->with('error', 'Data pengukuran tidak ditemukan');
        }

        $balita = $balitaModel->find((int) $pengukuran['id_balita']);

        if (! $balita) {
            return redirect()
                ->to('/balita')
                ->with('error', 'Data balita tidak ditemukan');
        }

        // Ambil nilai baru dari form (bukan lagi dari data lama)
        $tanggalUkur = (string) $this->request->getPost('tanggal_ukur');
        $usiaForm    = (int) $this->request->getPost('usia_bulan');

        try {
            $hasil = (new ZScoreService())->hitungStatus(
                $balita,
                $tanggalUkur,
                (float) $this->request->getPost('tinggi_badan'),
                $usiaForm > 0 ? $usiaForm : null
            );
        } catch (\RuntimeException $exception) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $exception->getMessage());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $pengukuranModel->update($id, [
            'tanggal_ukur' => $tanggalUkur,
            'usia_bulan'   => $hasil['usia_bulan'],
            'berat_badan'  => (float) $this->request->getPost('berat_badan'),
            'tinggi_badan' => (float) $this->request->getPost('tinggi_badan'),
            'z_score'      => $hasil['z_score'],
            'status_gizi'  => $hasil['status'],
            'warna_kms'    => $hasil['warna'],
            'catatan'      => trim((string) $this->request->getPost('catatan')),
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data pengukuran');
        }

        return redirect()
            ->to('/pengukuran/riwayat/' . $balita['id_balita'])
            ->with('success', 'Data pengukuran berhasil diperbarui');
    }

    public function hapus($id)
    {
        $model = new PengukuranModel();
        $model->delete((int) $id);

        return redirect()
            ->back()
            ->with('success', 'Data pengukuran berhasil dihapus');
    }

    private function rules(): array
    {
        return [
            'id_balita'    => 'required|integer',
            'tanggal_ukur' => 'required|valid_date[Y-m-d]',
            'berat_badan'  => 'required|numeric|greater_than[0]',
            'tinggi_badan' => 'required|numeric|greater_than[0]',
        ];
    }
}