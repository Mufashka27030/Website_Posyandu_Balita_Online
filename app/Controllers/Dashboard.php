<?php

namespace App\Controllers;

use App\Models\BalitaModel;
use App\Models\PengukuranModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $role = strtolower((string) session()->get('role'));

        // Orang tua → redirect ke dashboard khusus
        if ($role === 'orangtua') {
            return redirect()->to('/dashboard-orangtua');
        }

        return view('dashboard/index', $this->getStatistik());
    }

    /**
     * Dashboard khusus orang tua (Issue #7)
     */
    public function orangtua()
    {
        $statistik   = $this->getStatistik();
        $balitaList  = $this->getBalitaWithLatestPengukuran();

        return view('dashboard/orangtua', array_merge($statistik, [
            'balita_list' => $balitaList,
        ]));
    }

    /**
     * Statistik khusus orang tua (Issue #9)
     */
    public function statistikOrangtua()
    {
        $statistik  = $this->getStatistik();
        $balitaList = $this->getBalitaWithLatestPengukuran();

        $persentase = $statistik['total_balita'] > 0
            ? (($statistik['total_stunting'] + $statistik['stunting_berat']) / $statistik['total_balita']) * 100
            : 0;

        return view('dashboard/statistik_orangtua', array_merge($statistik, [
            'balita_list' => $balitaList,
            'persentase'  => round($persentase, 2),
        ]));
    }

    public function statistik()
    {
        $statistik = $this->getStatistik();

        $persentase = $statistik['total_balita'] > 0
            ? (($statistik['total_stunting'] + $statistik['stunting_berat']) / $statistik['total_balita']) * 100
            : 0;

        return view('dashboard/statistik', array_merge($statistik, [
            'persentase' => round($persentase, 2),
            'warning'    => $statistik['stunting_berat'],
        ]));
    }

    /**
     * Ambil statistik gizi balita dengan query optimal (tanpa N+1).
     */
    private function getStatistik(): array
    {
        $balitaModel     = new BalitaModel();
        $pengukuranModel = new PengukuranModel();

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

        $balitaIds = array_column($balita, 'id_balita');

        $allPengukuran = $pengukuranModel
            ->select('id_balita, status_gizi')
            ->whereIn('id_balita', $balitaIds)
            ->orderBy('id_balita', 'ASC')
            ->orderBy('tanggal_ukur', 'DESC')
            ->findAll();

        $latestStatus = [];
        foreach ($allPengukuran as $row) {
            $idBalita = $row['id_balita'];
            if (! isset($latestStatus[$idBalita])) {
                $latestStatus[$idBalita] = $row['status_gizi'];
            }
        }

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

    /**
     * Ambil daftar balita milik orang tua + pengukuran terbaru (tanpa N+1).
     */
    private function getBalitaWithLatestPengukuran(): array
    {
        $balitaModel     = new BalitaModel();
        $pengukuranModel = new PengukuranModel();

        $balita = $balitaModel
            ->where('id_user', (int) session()->get('id'))
            ->orderBy('nama_balita', 'ASC')
            ->findAll();

        if (empty($balita)) {
            return [];
        }

        $balitaIds = array_column($balita, 'id_balita');

        $allPengukuran = $pengukuranModel
            ->whereIn('id_balita', $balitaIds)
            ->orderBy('id_balita', 'ASC')
            ->orderBy('tanggal_ukur', 'DESC')
            ->findAll();

        $latestPerBalita = [];
        foreach ($allPengukuran as $p) {
            $idBalita = $p['id_balita'];
            if (! isset($latestPerBalita[$idBalita])) {
                $latestPerBalita[$idBalita] = $p;
            }
        }

        foreach ($balita as &$b) {
            $latest = $latestPerBalita[$b['id_balita']] ?? null;
            $b['status_gizi']  = $latest['status_gizi'] ?? null;
            $b['z_score']      = $latest['z_score'] ?? null;
            $b['tanggal_ukur'] = $latest['tanggal_ukur'] ?? null;
        }

        return $balita;
    }
}