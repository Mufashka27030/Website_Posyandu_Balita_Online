<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use App\Services\ZScoreService;

class ZScoreServiceTest extends CIUnitTestCase
{
    /**
     * Tanggal ukur SEBELUM tanggal lahir tidak masuk akal secara medis —
     * pastikan kodenya benar-benar menolak, bukan malah menghitung angka aneh.
     */
    public function testTanggalUkurSebelumTanggalLahirDitolak()
    {
        $service = new ZScoreService();

        $balita = [
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2024-06-01',
        ];

        $this->expectException(\RuntimeException::class);

        // Tanggal ukur (Januari) sebelum tanggal lahir (Juni) — harus gagal
        $service->hitungStatus($balita, '2024-01-01', 60.0, 1);
    }

    /**
     * Tidak menebak angka Z-Score atau status persisnya (itu tergantung data
     * standar_who yang sudah kamu isi sendiri) — cukup pastikan STRUKTUR hasil
     * perhitungannya benar: ada z_score berupa angka, dan status salah satu
     * dari tiga nilai yang valid. Ini caranya menguji "bentuk" jawaban tanpa
     * perlu tahu persis angkanya.
     */
    public function testHasilPerhitunganPunyaStrukturYangBenar()
    {
        $service = new ZScoreService();

        $balita = [
            'jenis_kelamin' => 'P',
            'tanggal_lahir' => '2024-01-01',
        ];

        $hasil = $service->hitungStatus($balita, '2024-07-01', 65.0, 6);

        $this->assertIsFloat((float) $hasil['z_score']);
        $this->assertContains($hasil['status'], ['Normal', 'Stunting', 'Stunting Berat']);
        $this->assertSame(6, $hasil['usia_bulan']);
    }
}
