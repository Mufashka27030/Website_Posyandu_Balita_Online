<?php

namespace App\Controllers;

use App\Models\BalitaModel;
use App\Models\PengukuranModel;

class Konsultasi extends BaseController
{
    public function index()
    {
        return view('konsultasi/index', [
            'nama_bidan' => 'Bidan Posyandu Sartika',
            'nomor_wa' => '6281234567890',
        ]);
    }

    public function anak($idBalita)
    {
        $balitaModel = new BalitaModel();
        $pengukuranModel = new PengukuranModel();
        $balita = $balitaModel->find((int) $idBalita);

        if (! $balita) {
            return redirect()
                ->to('/balita')
                ->with('error', 'Data balita tidak ditemukan');
        }

        $pengukuran = $pengukuranModel
            ->where('id_balita', (int) $idBalita)
            ->orderBy('tanggal_ukur', 'DESC')
            ->first();

        if (! $pengukuran) {
            return redirect()
                ->to('/balita/detail/' . $idBalita)
                ->with('error', 'Data pengukuran belum tersedia');
        }

        $pesan = rawurlencode(
            "Halo Bidan,\n\n" .
            "Saya ingin konsultasi mengenai anak saya.\n\n" .
            'Nama Anak: ' . $balita['nama_balita'] . "\n" .
            'Status: ' . $pengukuran['status_gizi'] . "\n" .
            'Z-Score: ' . $pengukuran['z_score']
        );

        return redirect()->to('https://wa.me/6281234567890?text=' . $pesan);
    }
}
