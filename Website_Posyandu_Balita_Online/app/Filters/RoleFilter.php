<?php

namespace App\Filters;

use App\Models\ClassUserModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Session\Session;

class RoleFilter implements FilterInterface
{
    /**
     * Sesi ditutup setelah tidak ada aktivitas selama ini (detik). Disamakan
     * dengan Config\Session::$expiration supaya punya satu angka yang sama.
     */
    private const IDLE_TIMEOUT = 7200;

    public function before(
        RequestInterface $request,
        $arguments = null
    ) {
        $session = session();

        if (! $session->get('logged_in')) {
            return redirect()->to('/login');
        }

        if ($this->isIdle($session)) {
            $session->destroy();

            return redirect()
                ->to('/login')
                ->with('error', 'Sesi Anda berakhir karena tidak ada aktivitas. Silakan login kembali.');
        }

        $session->set('last_activity', time());

        $account = (new ClassUserModel())->find((int) $session->get('id'));

        if (! $account) {
            // Akun sudah dihapus/hilang tapi cookie sesinya masih berlaku:
            // jangan biarkan "sesi hantu" terus jalan sampai kedaluwarsa.
            $session->destroy();

            return redirect()
                ->to('/login')
                ->with('error', 'Akun Anda tidak lagi aktif. Silakan login kembali.');
        }

        $role = strtolower((string) $account['role']);

        // Role di database adalah sumber kebenaran. Tanpa baris ini, akun yang
        // baru di-downgrade dari admin ke kader tetap memegang hak admin sampai
        // sesinya kedaluwarsa ( maksimal 2 jam ).
        if ($role !== strtolower((string) $session->get('role'))) {
            $session->set('role', $role);
            $session->regenerate(true);
        }

        if ($arguments && ! in_array($role, $arguments, true)) {
            return redirect()
                ->to('/profil')
                ->with('error', 'Anda tidak memiliki hak akses');
        }

        return null;
    }

    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {
        return $response;
    }

    private function isIdle(Session $session): bool
    {
        $last = (int) ($session->get('last_activity') ?? $session->get('login_time') ?? 0);

        return $last === 0 || (time() - $last) > self::IDLE_TIMEOUT;
    }
}
