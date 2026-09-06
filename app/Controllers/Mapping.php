<?php

namespace App\Controllers;

use App\Models\BalitaModel;
use App\Models\PengukuranModel;

class Mapping extends BaseController
{
    public function index()
    {
        $balitaModel = new BalitaModel();
        $pengukuranModel = new PengukuranModel();

        $allBalita = $balitaModel->findAll();

        if (empty($allBalita)) {
            return view('mapping/index', [
                'balita'   => [],
                'normal'   => 0,
                'stunting' => 0,
                'berat'    => 0,
            ]);
        }

        $balitaIds = array_column($allBalita, 'id_balita');

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

        $dataBalita = [];
        $normal     = 0;
        $stunting   = 0;
        $berat      = 0;

        foreach ($allBalita as $balita) {
            $latitude  = $balita['latitude'] ?? null;
            $longitude = $balita['longitude'] ?? null;

            if (! is_numeric($latitude) || ! is_numeric($longitude)) {
                continue;
            }

            $status = $latestStatus[$balita['id_balita']] ?? null;

            // SKIP: balita yang belum pernah diukur TIDAK muncul di peta
            if ($status === null) {
                continue;
            }

            match ($status) {
                'Normal'         => $normal++,
                'Stunting'       => $stunting++,
                'Stunting Berat' => $berat++,
                default          => null,
            };

            $dataBalita[] = [
                'nama'      => $balita['nama_balita'],
                'alamat'    => $balita['alamat'],
                'latitude'  => (float) $latitude,
                'longitude' => (float) $longitude,
                'status'    => $status,
            ];
        }

        return view('mapping/index', [
            'balita'   => $dataBalita,
            'normal'   => $normal,
            'stunting' => $stunting,
            'berat'    => $berat,
        ]);
    }
}