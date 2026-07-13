<?php

namespace App\Services;

use App\Models\StandarWhoModel;
use RuntimeException;

/**
 * Service untuk perhitungan Z-Score dan status gizi berdasarkan standar WHO
 * Height-for-Age (indikator stunting).
 */
class ZScoreService
{
    /**
     * Hitung status gizi berdasarkan standar WHO Height-for-Age.
     *
     * @param array  $balita       Data balita (minimal: tanggal_lahir, jenis_kelamin)
     * @param string $tanggalUkur  Tanggal pengukuran format Y-m-d
     * @param float  $tinggiBadan  Tinggi badan dalam cm
     * @return array{usia_bulan:int, z_score:float, status:string, warna:string}
     * @throws RuntimeException Jika tanggal tidak valid atau standar WHO tidak ditemukan
     */
    public function hitungStatus(array $balita, string $tanggalUkur, float $tinggiBadan): array
    {
        $tanggalLahir   = new \DateTimeImmutable((string) $balita['tanggal_lahir']);
        $tanggalUkurObj = new \DateTimeImmutable($tanggalUkur);

        if ($tanggalUkurObj < $tanggalLahir) {
            throw new RuntimeException('Tanggal pengukuran tidak boleh lebih awal dari tanggal lahir');
        }

        $selisih  = $tanggalLahir->diff($tanggalUkurObj);
        $usiaBulan = ($selisih->y * 12) + $selisih->m;

        $standar = $this->getStandarWho((string) $balita['jenis_kelamin'], $usiaBulan);

        if (! $standar) {
            throw new RuntimeException('Standar WHO tidak ditemukan untuk usia dan jenis kelamin ini');
        }

        $median   = (float) $standar['median'];
        $sdMinus2 = (float) $standar['sd_minus_2'];
        $sd       = ($median - $sdMinus2) / 2;

        if ($sd <= 0) {
            throw new RuntimeException('Data standar WHO tidak valid');
        }

        $zScore = round(($tinggiBadan - $median) / $sd, 2);

        if ($zScore >= -2) {
            $status = 'Normal';
            $warna  = 'Hijau';
        } elseif ($zScore >= -3) {
            $status = 'Stunting';
            $warna  = 'Kuning';
        } else {
            $status = 'Stunting Berat';
            $warna  = 'Merah';
        }

        return [
            'usia_bulan' => $usiaBulan,
            'z_score'    => $zScore,
            'status'     => $status,
            'warna'      => $warna,
        ];
    }

    /**
     * Ambil data standar WHO untuk jenis kelamin dan usia tertentu.
     */
    public function getStandarWho(string $jenisKelamin, int $usiaBulan): ?array
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

    /**
     * Normalisasi format jenis kelamin ke 'L' atau 'P'.
     */
    public function normalisasiJenisKelamin(string $jenisKelamin): string
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