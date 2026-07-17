<?php

namespace App\Controllers;

use App\Models\BalitaModel;
use App\Models\PengukuranModel;
use App\Models\ClassUserModel;

class Konsultasi extends BaseController
{
    public function index()
    {
        $role = strtolower((string) session()->get('role'));

        // Admin & Kader: lihat daftar WhatsApp seluruh orang tua
        if (in_array($role, ['admin', 'kader'], true)) {
            $userModel = new ClassUserModel();
            $orangtua  = $userModel->where('role', 'orangtua')->findAll();

            return view('konsultasi/index', [
                'mode'         => 'admin',
                'orangtua'     => $orangtua,
                'nama_bidan'   => 'Bidan Posyandu Sartika',
                'nomor_wa'     => '6281234567890',
            ]);
        }

        // Orang Tua: lihat kontak bidan
        return view('konsultasi/index', [
            'mode'       => 'orangtua',
            'nama_bidan' => 'Bidan Posyandu Sartika',
            'nomor_wa'   => '6281234567890',
        ]);
    }

    public function anak($idBalita)
    {
        $balitaModel     = new BalitaModel();
        $pengukuranModel = new PengukuranModel();
        $balita          = $balitaModel->find((int) $idBalita);

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

    /**
     * Chat langsung ke orang tua tertentu (untuk admin/kader)
     */
    public function chatOrangTua($idUser)
    {
        $userModel = new ClassUserModel();
        $user      = $userModel->find((int) $idUser);

        if (! $user || empty($user['no_hp'])) {
            return redirect()
                ->back()
                ->with('error', 'Nomor WhatsApp tidak tersedia');
        }

        $pesan = rawurlencode(
            "Halo " . $user['nama'] . ", saya dari Posyandu ingin berkomunikasi mengenai data balita Anda."
        );

        $nomor = preg_replace('/[^0-9]/', '', $user['no_hp']);
        if (str_starts_with($nomor, '0')) {
            $nomor = '62' . substr($nomor, 1);
        }

        return redirect()->to('https://wa.me/' . $nomor . '?text=' . $pesan);
    }
}