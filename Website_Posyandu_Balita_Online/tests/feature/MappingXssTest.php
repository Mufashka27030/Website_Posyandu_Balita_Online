<?php

namespace Tests\Feature;

use App\Models\BalitaModel;
use App\Models\PengukuranModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use Tests\Support\Concerns\WithLoggedInUser;

/**
 * Regresi untuk XSS tersimpan di halaman peta sebaran.
 *
 * Nama dan alamat balita dulu dikirim ke <script> dengan json_encode biasa,
 * sehingga satu nama berisi "</script><img src=x onerror=...>" menutup blok
 * script dan menjalankan JavaScript di browser admin/kader — cukup untuk
 * mencuri sesi administrator. Perbaikannya: JSON_HEX_* di controller.
 */
class MappingXssTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;
    use WithLoggedInUser;

    protected $refresh = false;
    protected $migrate = false;

    private const PAYLOAD = 'XSS</script><img src=x onerror=alert(1)>';

    public function testNamaBalitaBerbahayaTidakDieksekusiSebagaiScript()
    {
        $balitaModel = new BalitaModel();

        $idBalita = $balitaModel->insert([
            'id_user'       => 3,
            'nama_balita'   => self::PAYLOAD,
            'nama_ibu'      => 'Ibu Uji',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2024-02-02',
            'alamat'        => 'Alamat <b>Uji</b>',
            'latitude'      => '-7.9521',
            'longitude'     => '112.6545',
        ], false);

        // Halaman peta hanya menampilkan balita yang sudah punya pengukuran,
        // tanpa itu baris uji tidak akan muncul di JSON sama sekali.
        (new PengukuranModel())->insert([
            'id_balita'    => (int) $idBalita,
            'tanggal_ukur' => date('Y-m-d'),
            'usia_bulan'   => 24,
            'berat_badan'  => 11.2,
            'tinggi_badan' => 87.5,
            'z_score'      => -1.1,
            'status_gizi'  => 'Normal',
        ], false);

        $this->asUser(1, 'admin');

        $body = $this->call('get', 'mapping')->getBody();

        // Payload yang masih utuh berarti penutup </script> asli ikut terkirim
        // dan browser akan mengeksekusi onerror.
        $this->assertStringNotContainsString('</script><img src=x onerror=alert(1)>', $body);
        $this->assertStringContainsString('\u003Cimg src=x onerror=alert(1)\u003E', $body);

        (new PengukuranModel())->where('id_balita', (int) $idBalita)->delete();
        $balitaModel->delete((int) $idBalita);
    }
}
