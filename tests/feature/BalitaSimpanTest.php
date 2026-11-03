<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use Config\Services;

class BalitaSimpanTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $refresh = false;
    protected $migrate  = false;

    public function testIdUserYangTidakValidDitolakSaatTambahBalita()
    {
        session()->set([
            'logged_in' => true,
            'role'      => 'admin',
            'id'        => 1,
        ]);

        $security   = Services::security();
        $tokenName  = $security->getTokenName();
        $hash       = $security->generateHash();
        $cookieName = $security->getCookieName();

        $_COOKIE[$cookieName] = $hash;

        $this->call('post', 'balita/simpan', [
            $tokenName      => $hash,
            'id_user'       => 999999,
            'nama_balita'   => 'Anak Uji Coba Otomatis',
            'nama_ibu'      => 'Ibu Uji Coba',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2024-01-01',
        ]);

        $this->dontSeeInDatabase('balita', [
            'nama_balita' => 'Anak Uji Coba Otomatis',
        ]);
    }
}
