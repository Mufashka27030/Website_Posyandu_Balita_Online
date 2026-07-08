<?php

namespace App\Controllers;

use App\Models\BalitaModel;
use App\Models\PengukuranModel;
use App\Models\StandarWhoModel;

class Pengukuran extends BaseController
{
    public function index()
    {
        return redirect()->to('/balita');
    }

    public function tambah($idBalita)
    {
        $balita = (new BalitaModel())->find((int) $idBalita);

        if (! $balita) {
            return redirect()
                ->to('/balita')
                ->with('error', 'Data balita tidak ditemukan');
        }

        return view('pengukuran/tambah', [
            'balita' => $balita,
        ]);
    }

    public function simpan()
    {
        if (! $this->validate($this->rules())) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $this->validator->listErrors());
        }

        $balitaModel = new BalitaModel();
        $pengukuranModel = new PengukuranModel();
        $idBalita = (int) $this->request->getPost('id_balita');
        $balita = $balitaModel->find($idBalita);

        if (! $balita) {
            return redirect()
                ->to('/balita')
                ->with('error', 'Data balita tidak ditemukan');
        }

        try {
            $hasil = $this->hitungStatus(
                $balita,
                (string) $this->request->getPost('tanggal_ukur'),
                (float) $this->request->getPost('tinggi_badan')
            );
        } catch (\RuntimeException $exception) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $exception->getMessage());
        }

        $pengukuranModel->save([
            'id_balita' => $idBalita,
            'tanggal_ukur' => (string) $this->request->getPost('tanggal_ukur'),
            'usia_bulan' => $hasil['usia_bulan'],
            'berat_badan' => (float) $this->request->getPost('berat_badan'),
            'tinggi_badan' => (float) $this->request->getPost('tinggi_badan'),
            'z_score' => $hasil['z_score'],
            'status_gizi' => $hasil['status'],
            'warna_kms' => $hasil['warna'],
        ]);

        return view('pengukuran/hasil', [
            'balita' => $balita,
            'usia_bulan' => $hasil['usia_bulan'],
            'tinggi_badan' => (float) $this->request->getPost('tinggi_badan'),
            'zscore' => $hasil['z_score'],
            'status' => $hasil['status'],
            'warna' => $hasil['warna'],
        ]);
    }

    public function riwayat($idBalita)
    {
        $balitaModel = new BalitaModel();
        $pengukuranModel = new PengukuranModel();
        $balita = $balitaModel->find((int) $idBalita);

        if (! $balita) {
            return redirect()
                ->to('/balita')
                ->with('error', 'Data balita tidak ditemukan');
        }

        return view('pengukuran/riwayat', [
            'balita' => $balita,
            'riwayat' => $pengukuranModel
                ->where('id_balita', (int) $idBalita)
                ->orderBy('tanggal_ukur', 'DESC')
                ->findAll(),
        ]);
    }

    public function grafik($idBalita)
    {
        $balitaModel = new BalitaModel();
        $pengukuranModel = new PengukuranModel();
        $balita = $balitaModel->find((int) $idBalita);

        if (! $balita) {
            return redirect()
                ->to('/balita')
                ->with('error', 'Data balita tidak ditemukan');
        }

        $riwayat = $pengukuranModel
            ->where('id_balita', (int) $idBalita)
            ->orderBy('usia_bulan', 'ASC')
            ->findAll();

        $labels = [];
        $tinggi = [];
        $berat = [];

        foreach ($riwayat as $row) {
            $labels[] = (int) $row['usia_bulan'];
            $tinggi[] = (float) $row['tinggi_badan'];
            $berat[] = (float) $row['berat_badan'];
        }

        $alert = '';

        if (count($tinggi) >= 3) {
            $last = count($tinggi) - 1;

            if ($tinggi[$last] <= $tinggi[$last - 1]) {
                $alert = 'Pertumbuhan anak stagnan';
            }
        }

        $jsonFlags = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP;

        return view('pengukuran/grafik', [
            'balita' => $balita,
            'labels' => json_encode($labels, $jsonFlags),
            'tinggi' => json_encode($tinggi, $jsonFlags),
            'berat' => json_encode($berat, $jsonFlags),
            'alert' => $alert,
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

        return view('pengukuran/edit', [
            'pengukuran' => $pengukuran,
        ]);
    }

    public function update($id)
    {
        if (! $this->validate([
            'berat_badan' => 'required|numeric|greater_than[0]',
            'tinggi_badan' => 'required|numeric|greater_than[0]',
        ])) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $this->validator->listErrors());
        }

        $pengukuranModel = new PengukuranModel();
        $balitaModel = new BalitaModel();
        $id = (int) $id;
        $pengukuran = $pengukuranModel->find($id);

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

        try {
            $hasil = $this->hitungStatus(
                $balita,
                (string) $pengukuran['tanggal_ukur'],
                (float) $this->request->getPost('tinggi_badan')
            );
        } catch (\RuntimeException $exception) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $exception->getMessage());
        }

        $pengukuranModel->update($id, [
            'usia_bulan' => $hasil['usia_bulan'],
            'berat_badan' => (float) $this->request->getPost('berat_badan'),
            'tinggi_badan' => (float) $this->request->getPost('tinggi_badan'),
            'z_score' => $hasil['z_score'],
            'status_gizi' => $hasil['status'],
            'warna_kms' => $hasil['warna'],
        ]);

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
            'id_balita' => 'required|integer',
            'tanggal_ukur' => 'required|valid_date[Y-m-d]',
            'berat_badan' => 'required|numeric|greater_than[0]',
            'tinggi_badan' => 'required|numeric|greater_than[0]',
        ];
    }

    private function hitungStatus(array $balita, string $tanggalUkur, float $tinggiBadan): array
    {
        $tanggalLahir = new \DateTimeImmutable((string) $balita['tanggal_lahir']);
        $tanggalUkurObj = new \DateTimeImmutable($tanggalUkur);

        if ($tanggalUkurObj < $tanggalLahir) {
            throw new \RuntimeException('Tanggal pengukuran tidak boleh lebih awal dari tanggal lahir');
        }

        $selisih = $tanggalLahir->diff($tanggalUkurObj);
        $usiaBulan = ($selisih->y * 12) + $selisih->m;
        $standar = $this->standarWho((string) $balita['jenis_kelamin'], $usiaBulan);

        if (! $standar) {
            throw new \RuntimeException('Standar WHO tidak ditemukan untuk usia dan jenis kelamin ini');
        }

        $median = (float) $standar['median'];
        $sdMinus2 = (float) $standar['sd_minus_2'];
        $sd = ($median - $sdMinus2) / 2;

        if ($sd <= 0) {
            throw new \RuntimeException('Data standar WHO tidak valid');
        }

        $zScore = round(($tinggiBadan - $median) / $sd, 2);

        if ($zScore >= -2) {
            $status = 'Normal';
            $warna = 'Hijau';
        } elseif ($zScore >= -3) {
            $status = 'Stunting';
            $warna = 'Kuning';
        } else {
            $status = 'Stunting Berat';
            $warna = 'Merah';
        }

        return [
            'usia_bulan' => $usiaBulan,
            'z_score' => $zScore,
            'status' => $status,
            'warna' => $warna,
        ];
    }

    private function standarWho(string $jenisKelamin, int $usiaBulan): ?array
    {
        $candidates = array_values(array_unique([
            $this->normalisasiJenisKelamin($jenisKelamin),
            $jenisKelamin,
        ]));

        foreach ($candidates as $candidate) {
            $standar = (new StandarWhoModel())
                ->where('jenis_kelamin', $candidate)
                ->where('usia_bulan', $usiaBulan)
                ->first();

            if ($standar) {
                return $standar;
            }
        }

        return null;
    }

    private function normalisasiJenisKelamin(string $jenisKelamin): string
    {
        $value = strtolower(trim($jenisKelamin));

        if (in_array($value, ['l', 'laki-laki', 'laki laki'], true)) {
            return 'L';
        }

        if (in_array($value, ['p', 'perempuan'], true)) {
            return 'P';
        }

        return $jenisKelamin;
    }
}
