<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use App\Models\ClassUserModel;

class ClassUserHapusTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $refresh = false;
    protected $migrate  = false;

    public function testKaderTidakBisaMenghapusAkunAdmin()
    {
        $model          = new ClassUserModel();
        $idAdminUjiCoba = $model->insert([
            'nama'     => 'Admin Uji Coba Otomatis',
            'email'    => 'admin.ujicoba.' . uniqid() . '@test.local',
            'password' => password_hash('passwordujicoba', PASSWORD_DEFAULT),
            'role'     => 'admin',
        ]);

        session()->set([
            'logged_in' => true,
            'role'      => 'kader',
            'id'        => 999999,
        ]);

        $this->call('post', 'classuser/hapus/' . $idAdminUjiCoba);

        $this->seeInDatabase('class_users', [
            'id_user' => $idAdminUjiCoba,
        ]);
    }
}