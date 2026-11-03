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
     * @var array<string, array>|null Index standar WHO, dimuat sekali per request.
     */
    private ?array $standarCache = null;

    /**
     * Hitung status gizi berdasarkan standar WHO Height-for-Age.
     *
     * @param array  $balita       Data balita (minimal: tanggal_lahir, jenis_kelamin)
     * @param string $tanggalUkur  Tanggal pengukuran format Y-m-d
     * @param float  $tinggiBadan  Tinggi badan dalam cm
     * @return array{usia_bulan:int, z_score:float, status:string, warna:string}
     * @throws RuntimeException Jika tanggal tidak valid atau standar WHO tidak ditemukan
     */
    public function hitungStatus(array $balita, string $tanggalUkur, float $tinggiBadan, ?int $usiaOverride = null): array
{
    $tanggalLahir   = new \DateTimeImmutable((string) $balita['tanggal_lahir']);
    $tanggalUkurObj = new \DateTimeImmutable($tanggalUkur);

    if ($tanggalUkurObj < $tanggalLahir) {
        throw new RuntimeException('Tanggal pengukuran tidak boleh lebih awal dari tanggal lahir');
    }

    // Gunakan override usia jika diberikan (dari input manual), jika tidak hitung otomatis
    if ($usiaOverride !== null && $usiaOverride >= 0 && $usiaOverride <= 60) {
        $usiaBulan = $usiaOverride;
    } else {
        $selisih   = $tanggalLahir->diff($tanggalUkurObj);
        $usiaBulan = ($selisih->y * 12) + $selisih->m;
    }

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
     *
     * Tabel standar_who hanya berisi ±122 baris (L/P x usia 0-60 bulan) dan
     * hampir selalu dipakai berulang dalam satu request — halaman grafik
     * memanggil metode ini sekali per titik pengukuran. Membaca seluruh tabel
     * sekali lalu menyimpannya di memori menghilangkan pola N+1 query.
     */
    public function getStandarWho(string $jenisKelamin, int $usiaBulan): ?array
    {
        $index = $this->standarTable();

        foreach (array_values(array_unique([
            $this->normalisasiJenisKelamin($jenisKelamin),
            $jenisKelamin,
        ])) as $candidate) {
            $standar = $index[$candidate . '-' . $usiaBulan] ?? null;

            if ($standar !== null) {
                return $standar;
            }
        }

        return null;
    }

    /**
     * @return array<string, array>
     */
    private function standarTable(): array
    {
        if ($this->standarCache === null) {
            $index = [];

            foreach ((new StandarWhoModel())->orderBy('usia_bulan', 'ASC')->findAll() as $row) {
                $index[$row['jenis_kelamin'] . '-' . (int) $row['usia_bulan']] = $row;
            }

            $this->standarCache = $index;
        }

        return $this->standarCache;
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