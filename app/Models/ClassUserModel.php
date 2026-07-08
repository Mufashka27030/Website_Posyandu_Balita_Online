<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassUserModel extends Model
{
    protected $table = 'class_users';

    protected $primaryKey = 'id_user';

    protected $returnType = 'array';

    protected $allowedFields = [

        'nama',
        'email',
        'password',
        'role',
        'no_hp',
        'alamat'
    ];
}