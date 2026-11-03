<?php

namespace Tests\Support\Concerns;

use Config\Services;

/**
 * Membantu feature test yang butuh status sudah login.
 *
 * FeatureTestTrait::call() mengosongkan $_SESSION di setiap panggilan, jadi
 * session()->set() sebelum call() tidak pernah benar-benar membuat request
 * ikut terautentikasi — tes semacam itu selalu "lulus" tanpa menguji apa pun
 * karena yang diuji sebenarnya redirect ke /login. withSession() adalah jalur
 * yang benar, dan token CSRF perlu ikut disiapkan karena filter csrf global
 * menyala untuk semua request POST.
 */
trait WithLoggedInUser
{
    protected function asUser(int $idUser, string $role): void
    {
        $security = Services::security();

        $_COOKIE[$security->getCookieName()] = $security->generateHash();

        $this->withSession([
            'logged_in'   => true,
            'id'          => $idUser,
            'role'        => $role,
            'login_time'  => time(),
            'last_activity' => time(),
        ]);
    }

    /**
     * Nama field yang harus dikirim beserta POST agar lolos filter CSRF.
     */
    protected function csrfField(): string
    {
        return Services::security()->getTokenName();
    }
}
