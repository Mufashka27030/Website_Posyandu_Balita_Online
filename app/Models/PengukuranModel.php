<?php

namespace App\Models;

use CodeIgniter\Model;

class PengukuranModel extends Model
{
    protected $table = 'pengukuran';

    protected $primaryKey = 'id_pengukuran';

    protected $allowedFields = [
        'id_balita',
        'tanggal_ukur',
        'usia_bulan',
        'berat_badan',
        'tinggi_badan',
        'z_score',
        'status_gizi',
        'warna_kms'
    ];
}