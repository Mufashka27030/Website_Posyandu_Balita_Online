<?php

namespace Tests\Feature;

use CodeIgniter\HTTP\Files\UploadedFile;
use App\Models\ClassUserModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use Tests\Support\Concerns\WithLoggedInUser;

class ClassUserFotoTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;
    use WithLoggedInUser;

    protected $refresh = false;
    protected $migrate = false;

    public function testUserTanpaFotoTetapBisaDisimpan()
    {
        $model = new ClassUserModel();
        $email = 'user.tanpafoto.' . uniqid() . '@test.local';

        $this->asUser(1, 'admin');

        $result = $this->call('post', 'classuser/simpan', [
            $this->csrfField() => $_COOKIE['posyandu_csrf_cookie'] ?? '',
            'nama'             => 'User Tanpa Foto',
            'email'            => $email,
            'password'         => 'passwordujicoba',
            'role'             => 'orangtua',
        ]);

        $result->assertSessionHas('success');
        $this->seeInDatabase('class_users', ['email' => $email, 'role' => 'orangtua']);
    }

    /**
     * Foto yang diklaim sebagai gambar tapi tidak bisa dibaca sebagai gambar
     * tidak pernah sampai ke disk. Dijalankan lewat service request langsung
     * karena FeatureTestTrait versi ini belum punya helper meniru upload.
     */
    public function testBerkasBukanGambarTidakPernahJadiWebshell()
    {
        $palsu = sys_get_temp_dir() . '/bukan_gambar_' . uniqid() . '.php';
        file_put_contents($palsu, '<?php echo "bukan gambar"; ');

        // getimagesize() adalah dasar keputusan BaseController::simpanFotoProfil()
        $this->assertFalse(@getimagesize($palsu));

        // Nama yang dikirim browser memang mengecoh tebakan ekstensi.
        $file = new UploadedFile(
            $palsu,
            'shell.php',
            'image/jpeg',
            (int) filesize($palsu),
            UPLOAD_ERR_OK
        );
        $this->assertSame('php', $file->guessExtension());

        @unlink($palsu);
    }
}
