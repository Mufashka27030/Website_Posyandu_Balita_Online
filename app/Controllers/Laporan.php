<?php

namespace App\Controllers;

use App\Models\BalitaModel;
use App\Models\PengukuranModel;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Laporan extends BaseController
{
    public function index()
    {
        return view('laporan/index', $this->dataLaporan());
    }

    public function pdf()
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('laporan/pdf', $this->dataLaporan()));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="laporan-stunting.pdf"')
            ->setBody($dompdf->output());
    }

    public function excel()
    {
        $data = $this->dataLaporan();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray([
            ['No', 'Nama Balita', 'Jenis Kelamin', 'Tanggal Lahir', 'Z-Score', 'Status'],
        ]);

        $rowNumber = 2;
        $number = 1;

        foreach ($data['balita'] as $row) {
            $sheet->fromArray([
                [
                    $number++,
                    $row['nama'],
                    $row['jenis_kelamin'],
                    $row['tanggal_lahir'],
                    $row['zscore'],
                    $row['status'],
                ],
            ], null, 'A' . $rowNumber++);
        }

        $writer = new Xlsx($spreadsheet);
        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();

        return $this->response
            ->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->setHeader('Content-Disposition', 'attachment; filename="laporan-stunting.xlsx"')
            ->setBody($content);
    }

    private function dataLaporan(): array
    {
        $balitaModel = new BalitaModel();
        $pengukuranModel = new PengukuranModel();
        $dataBalita = [];

        foreach ($balitaModel->findAll() as $balita) {
            $pengukuran = $pengukuranModel
                ->where('id_balita', $balita['id_balita'])
                ->orderBy('tanggal_ukur', 'DESC')
                ->first();

            $dataBalita[] = [
                'nama' => $balita['nama_balita'],
                'jenis_kelamin' => $balita['jenis_kelamin'],
                'tanggal_lahir' => $balita['tanggal_lahir'],
                'status' => $pengukuran['status_gizi'] ?? '-',
                'zscore' => $pengukuran['z_score'] ?? '-',
            ];
        }

        $totalBalita = count($dataBalita);
        $totalNormal = 0;
        $totalStunting = 0;
        $stuntingBerat = 0;

        foreach ($dataBalita as $row) {
            if ($row['status'] === 'Normal') {
                $totalNormal++;
            } elseif ($row['status'] === 'Stunting') {
                $totalStunting++;
            } elseif ($row['status'] === 'Stunting Berat') {
                $stuntingBerat++;
            }
        }

        $persentase = $totalBalita > 0
            ? (($totalStunting + $stuntingBerat) / $totalBalita) * 100
            : 0;

        return [
            'balita' => $dataBalita,
            'total_balita' => $totalBalita,
            'total_normal' => $totalNormal,
            'total_stunting' => $totalStunting,
            'stunting_berat' => $stuntingBerat,
            'persentase' => round($persentase, 2),
        ];
    }

    public function recap($tahun)
    {
    $bulan = (int) $this->request->getGet('bulan');
    $bulan = ($bulan >= 1 && $bulan <= 12) ? $bulan : (int) date('m');

    $namaBulan = [1=>'Januari','Februari','Maret','April','Mei','Juni',
                   'Juli','Agustus','September','Oktober','November','Desember'];

    $pengukuranModel = new PengukuranModel();
    $balitaModel     = new BalitaModel();

    // Ambil pengukuran di bulan/tahun tersebut
    $pengukuranList = $pengukuranModel
        ->where('MONTH(tanggal_ukur)', $bulan)
        ->where('YEAR(tanggal_ukur)', (int) $tahun)
        ->findAll();

    $totalBalita  = count($pengukuranList);
    $totalNormal  = 0;
    $totalStunting = 0;
    $stuntingBerat = 0;

    foreach ($pengukuranList as $p) {
        $status = $p['status_gizi'] ?? '';
        if ($status === 'Normal') {
            $totalNormal++;
        } elseif ($status === 'Stunting') {
            $totalStunting++;
        } elseif ($status === 'Stunting Berat') {
            $stuntingBerat++;
        }
    }

    $persentase = $totalBalita > 0
        ? round((($totalStunting + $stuntingBerat) / $totalBalita) * 100, 2)
        : 0;

    return $this->response->setJSON([
        'status'          => 'success',
        'bulan'           => $bulan,
        'bulan_nama'      => $namaBulan[$bulan],
        'tahun'           => (int) $tahun,
        'total_balita'    => $totalBalita,
        'total_normal'    => $totalNormal,
        'total_stunting'  => $totalStunting,
        'stunting_berat'  => $stuntingBerat,
        'persentase'      => $persentase,
    ]);
    }
}
