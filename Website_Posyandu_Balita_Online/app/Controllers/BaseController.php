<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    protected $helpers = ['form'];

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Load here all helpers you want to be available in your controllers that extend BaseController.
        // Caution: Do not put the this below the parent::initController() call below.
        // $this->helpers = ['form', 'url'];

        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        // $this->session = service('session');
    }

    /**
     * Simpan foto profil yang di-upload dan kembalikan path relatifnya.
     *
     * Ekstensi file SELALU ditentukan dari isi file (getimagesize), bukan dari
     * nama yang dikirim browser. Kalau hanya mempercayai cek MIME, satu file
     * polyglot (header GIF + kode PHP) bisa lolos sebagai "gambar" lalu
     * tersimpan sebagai .php di folder yang bisa diakses web — remote code
     * execution. Mengembalikan null bila tidak ada upload yang valid.
     */
    protected function simpanFotoProfil(string $folder = 'foto'): ?string
    {
        $foto = $this->request->getFile('foto');

        if ($foto === null || ! $foto->isValid() || $foto->hasMoved()) {
            return null;
        }

        if ($foto->getSize() > 2 * 1024 * 1024) {
            return null;
        }

        $info = @getimagesize($foto->getPathname());

        if ($info === false || ! isset($info[2])) {
            return null;
        }

        $ext = match ((int) $info[2]) {
            IMAGETYPE_JPEG => 'jpg',
            IMAGETYPE_PNG  => 'png',
            IMAGETYPE_GIF  => 'gif',
            IMAGETYPE_WEBP => 'webp',
            default        => null,
        };

        if ($ext === null) {
            return null;
        }

        $uploadPath = FCPATH . 'uploads/' . $folder;

        if (! is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $namaFile = date('Ymd_His') . '_' . bin2hex(random_bytes(8)) . '.' . $ext;

        if (! $foto->move($uploadPath, $namaFile)) {
            return null;
        }

        return 'uploads/' . $folder . '/' . $namaFile;
    }
}
