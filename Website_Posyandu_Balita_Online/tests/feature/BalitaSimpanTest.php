<?php

namespace Tests\Feature;

use App\Models\BalitaModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use Tests\Support\Concerns\WithLoggedInUser;

class BalitaSimpanTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;
    use WithLoggedInUser;

    protected $refresh = false;
    protected $migrate = false;

    public function testIdUserYangTidakValidDitolakSaatTambahBalita()
    {
        $this->asUser(1, 'admin');

        $result = $this->call('post', 'balita/simpan', [
            $this->csrfField() => $_COOKIE['posyandu_csrf_cookie'] ?? '',
            'id_user'          => 999999,
            'nama_balita'      => 'Anak Uji Coba Otomatis',
            'nama_ibu'         => 'Ibu Uji Coba',
            'jenis_kelamin'    => 'L',
            'tanggal_lahir'    => '2024-01-01',
        ]);

        $result->assertSessionHas('error');
        $this->dontSeeInDatabase('balita', ['nama_balita' => 'Anak Uji Coba Otomatis']);
    }

    public function testBalitaSahTersimpan()
    {
        $this->asUser(1, 'admin');

        $result = $this->call('post', 'balita/simpan', [
            $this->csrfField() => $_COOKIE['posyandu_csrf_cookie'] ?? '',
            'id_user'          => 3,
            'nama_balita'      => 'Anak Terverifikasi',
            'nama_ibu'         => 'Ibu Terverifikasi',
            'jenis_kelamin'    => 'P',
            'tanggal_lahir'    => '2024-05-17',
            'alamat'           => 'RT 02 / RW 05',
            'latitude'         => '-7.9521',
            'longitude'        => '112.6545',
        ]);

        $result->assertSessionHas('success');
        $this->seeInDatabase('balita', ['nama_balita' => 'Anak Terverifikasi']);
    }

    /**
     * NIK Nasional punya 16 digit angka. Aturan lama hanya membatasi 20
     * karakter sehingga "ABC-123" pun lolos dan merusak laporan.
     */
    public function testNikBentukSalahDitolak()
    {
        $this->asUser(1, 'admin');

        $result = $this->call('post', 'balita/simpan', [
            $this->csrfField() => $_COOKIE['posyandu_csrf_cookie'] ?? '',
            'id_user'          => 3,
            'nama_balita'      => 'Anak Nik Salah',
            'nama_ibu'         => 'Ibu Salah',
            'jenis_kelamin'    => 'L',
            'tanggal_lahir'    => '2024-01-01',
            'nik'              => '3508-12345',
        ]);

        $result->assertSessionHas('error');
        $this->dontSeeInDatabase('balita', ['nama_balita' => 'Anak Nik Salah']);
    }

    public function testKoordinatDiLuarRentangBumiDitolak()
    {
        $this->asUser(1, 'admin');

        $result = $this->call('post', 'balita/simpan', [
            $this->csrfField() => $_COOKIE['posyandu_csrf_cookie'] ?? '',
            'id_user'          => 3,
            'nama_balita'      => 'Anak Koordinat Salah',
            'nama_ibu'         => 'Ibu Koordinat Salah',
            'jenis_kelamin'    => 'L',
            'tanggal_lahir'    => '2024-01-01',
            'latitude'         => '1234.5',
        ]);

        $result->assertSessionHas('error');
        $this->dontSeeInDatabase('balita', ['nama_balita' => 'Anak Koordinat Salah']);
    }

    /**
     * Orang tua hanya boleh melihat balita miliknya sendiri (IDOR).
     */
    public function testOrangtuaTidakBisaMelihatBalitaMilikOrangLain()
    {
        $punyaOrangLain = (new BalitaModel())
            ->where('id_user !=', 3)
            ->where('id_user IS NOT NULL')
            ->first();

        if (! $punyaOrangLain) {
            $this->markTestSkipped('Database test tidak punya balita milik user lain.');
        }

        $this->asUser(3, 'orangtua');

        $result = $this->call(
            'get',
            'balita/detail/' . $punyaOrangLain['id_balita']
        );

        $result->assertSessionHas('error');
        $result->assertRedirectTo('balita');
    }
}
