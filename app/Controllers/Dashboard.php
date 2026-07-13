<?php

namespace App\Controllers;

use App\Models\BalitaModel;
use App\Models\PengukuranModel;

class Dashboard extends BaseController
{
    public function index()
    {
        return view('dashboard/index', $this->getStatistik());
    }

    public function statistik()
    {
        $statistik = $this->getStatistik();

        $persentase = $statistik['total_balita'] > 0
            ? (($statistik['total_stunting'] + $statistik['stunting_berat']) / $statistik['total_balita']) * 100
            : 0;

        return view('dashboard/statistik', array_merge($statistik, [
            'persentase' => round($persentase, 2),
            'warning'     => $statistik['stunting_berat'],
        ]));
    }

    /**
     * Ambil statistik gizi balita dengan query optimal (tanpa N+1).
     * Mengganti N query pengukuran dengan 1 query tunggal + filtering di PHP.
     */
    private function getStatistik(): array
    {
        $balitaModel = new BalitaModel();
        $pengukuranModel = new PengukuranModel();

        // Single query: ambil balita sesuai role
        $role = strtolower((string) session()->get('role'));

        if ($role === 'orangtua') {
            $balita = $balitaModel
                ->where('id_user', (int) session()->get('id'))
                ->findAll();
        } else {
            $balita = $balitaModel->findAll();
        }

        $totalBalita = count($balita);

        if ($totalBalita === 0) {
            return [
                'total_balita'  => 0,
                'total_normal'  => 0,
                'total_stunting' => 0,
                'stunting_berat' => 0,
            ];
        }

        // N+1 FIX: ambil SEMUA pengukuran dalam SATU query (whereIn),
        // lalu ambil yang terbaru per balita di PHP
        $balitaIds = array_column($balita, 'id_balita');

        $allPengukuran = $pengukuranModel
            ->select('id_balita, status_gizi')
            ->whereIn('id_balita', $balitaIds)
            ->orderBy('id_balita', 'ASC')
            ->orderBy('tanggal_ukur', 'DESC')
            ->findAll();

        // Baris pertama per id_balita = pengukuran terbaru
        $latestStatus = [];
        foreach ($allPengukuran as $row) {
            $idBalita = $row['id_balita'];
            if (! isset($latestStatus[$idBalita])) {
                $latestStatus[$idBalita] = $row['status_gizi'];
            }
        }

        // Hitung jumlah per status
        $normal   = 0;
        $stunting = 0;
        $berat    = 0;

        foreach ($latestStatus as $status) {
            match ($status) {
                'Normal'         => $normal++,
                'Stunting'       => $stunting++,
                'Stunting Berat' => $berat++,
                default          => null,
            };
        }

        return [
            'total_balita'  => $totalBalita,
            'total_normal'  => $normal,
            'total_stunting' => $stunting,
            'stunting_berat' => $berat,
        ];
    }
}