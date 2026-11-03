<?php

namespace Tests\Feature;

use App\Models\ClassUserModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use Tests\Support\Concerns\WithLoggedInUser;

class ClassUserHapusTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;
    use WithLoggedInUser;

    protected $refresh = false;
    protected $migrate = false;

    public function testKaderTidakBisaMenghapusAkunAdmin()
    {
        $model          = new ClassUserModel();
        $idAdminUjiCoba = $model->insert([
            'nama'     => 'Admin Uji Coba Otomatis',
            'email'    => 'admin.ujicoba.' . uniqid() . '@test.local',
            'password' => password_hash('passwordujicoba', PASSWORD_DEFAULT),
            'role'     => 'admin',
        ]);

        // id_user 2 = akun kader yang nyata ada di database test. RoleFilter
        // membaca ulang role dari database, jadi id palsu akan langsung
        // dihapus sesinya dan tesnya lulus karena alasan yang salah.
        $this->asUser(2, 'kader');

        $result = $this->call('post', 'classuser/hapus/' . $idAdminUjiCoba, [
            $this->csrfField() => $_COOKIE['posyandu_csrf_cookie'] ?? '',
        ]);

        $result->assertSessionHas('error');
        $this->seeInDatabase('class_users', ['id_user' => $idAdminUjiCoba]);
    }

    public function testKaderTidakBisaMengubahRoleSendiriMenjadiAdmin()
    {
        $this->asUser(2, 'kader');

        $result = $this->call('post', 'classuser/update/2', [
            $this->csrfField() => $_COOKIE['posyandu_csrf_cookie'] ?? '',
            'nama'             => 'Budi',
            'email'            => 'kader2@gmail.com',
            'role'             => 'admin',
            'no_hp'            => '',
            'alamat'           => '',
        ]);

        $this->assertEquals(
            'kader',
            (new ClassUserModel())->find(2)['role'],
            'Kader tidak boleh menaikkan role-nya sendiri lewat form edit user.'
        );

        $result->assertSessionHas('error');
    }
}
