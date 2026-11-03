<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

class ClassUserFotoTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $refresh = false;
    protected $migrate  = false;

    public function testUserTanpaFotoTetapBisaDisimpan()
    {
        session()->set([
            'logged_in' => true,
            'role'      => 'admin',
            'id'        => 1,
        ]);

        $email = 'user.tanpafoto.' . uniqid() . '@test.local';

        $result = $this->call('post', 'classuser/simpan', [
            'nama'     => 'User Tanpa Foto',
            'email'    => $email,
            'password' => 'passwordujicoba',
            'role'     => 'orangtua',
        ]);

        // Kalau validasi foto (permit_empty) bekerja, controller redirect
        // ke /classuser dengan pesan sukses — bukan redirect balik dengan
        // error. Ini dicek dari respons controllernya langsung, bukan query
        // ke database, supaya tidak kena isu koneksi terpisah seperti tadi.
        $result->assertRedirectTo('classuser');
        $result->assertSessionHas('success');
    }
}