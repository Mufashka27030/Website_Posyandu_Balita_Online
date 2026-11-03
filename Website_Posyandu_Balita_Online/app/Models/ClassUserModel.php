<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassUserModel extends Model
{
    protected $table          = 'class_users';
    protected $primaryKey     = 'id_user';
    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';

    protected $allowedFields = [
        'nama',
        'email',
        'password',
        'role',
        'no_hp',
        'foto',
        'alamat',
    ];

    protected $validationRules = [
        'nama'  => 'required|min_length[3]|max_length[100]',
        'email' => 'required|valid_email',
        'role'  => 'in_list[admin,kader,dinkes,orangtua]',
    ];
}