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
        $dataBalita = [];
        $normal = 0;
        $stunting = 0;
        $berat = 0;

        foreach ($balitaModel->findAll() as $balita) {
            $latitude = $balita['latitude'] ?? null;
            $longitude = $balita['longitude'] ?? null;

            if (! is_numeric($latitude) || ! is_numeric($longitude)) {
                continue;
            }

            $pengukuran = $pengukuranModel
                ->where('id_balita', $balita['id_balita'])
                ->orderBy('tanggal_ukur', 'DESC')
                ->first();

            $status = $pengukuran['status_gizi'] ?? 'Belum Ada Data';

            if ($status === 'Normal') {
                $normal++;
            } elseif ($status === 'Stunting') {
                $stunting++;
            } elseif ($status === 'Stunting Berat') {
                $berat++;
            }

            $dataBalita[] = [
                'nama' => $balita['nama_balita'],
                'alamat' => $balita['alamat'],
                'latitude' => (float) $latitude,
                'longitude' => (float) $longitude,
                'status' => $status,
            ];
        }

        return view('mapping/index', [
            'balita' => $dataBalita,
            'normal' => $normal,
            'stunting' => $stunting,
            'berat' => $berat,
        ]);
    }
}
