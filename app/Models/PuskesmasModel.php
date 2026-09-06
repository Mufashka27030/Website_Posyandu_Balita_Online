<?php

namespace App\Models;

use CodeIgniter\Model;

class PuskesmasModel extends Model
{
    protected $table          = 'puskesmas';
    protected $primaryKey     = 'id_puskesmas';
    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';

    protected $allowedFields = [
        'nama_puskesmas',
        'kelurahan',
        'kecamatan',
        'rw',
        'alamat',
        'latitude',
        'longitude',
    ];

    protected $validationRules = [
        'nama_puskesmas' => 'required|min_length[3]|max_length[100]',
        'kelurahan'      => 'permit_empty|max_length[100]',
        'kecamatan'      => 'permit_empty|max_length[100]',
        'rw'             => 'permit_empty|max_length[10]',
        'alamat'         => 'permit_empty',
        'latitude'       => 'permit_empty|numeric',
        'longitude'      => 'permit_empty|numeric',
    ];

    protected $validationMessages = [
        'nama_puskesmas' => [
            'required'   => 'Nama puskesmas wajib diisi',
            'min_length' => 'Nama puskesmas minimal 3 karakter',
        ],
    ];
}
