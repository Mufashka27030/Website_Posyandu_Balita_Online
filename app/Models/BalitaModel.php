<?php

namespace App\Models;

use CodeIgniter\Model;

class BalitaModel extends Model
{
    protected $table = 'balita';

    protected $primaryKey = 'id_balita';

    protected $allowedFields = [
        'id_posyandu',
        'id_user',
        'nama_balita',
        'nama_ibu',
        'nik',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
        'latitude',
        'longitude'
    ];

}
