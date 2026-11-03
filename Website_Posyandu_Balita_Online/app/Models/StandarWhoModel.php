<?php

namespace App\Models;

use CodeIgniter\Model;

class StandarWhoModel extends Model
{
    protected $table = 'standar_who';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'jenis_kelamin',
        'usia_bulan',
        'median',
        'sd_minus_1',
        'sd_minus_2',
        'sd_minus_3'
    ];
}