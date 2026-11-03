<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use Config\Services;

/**
 * Regresi untuk rate limit login.
 *
 * Kode lama membandingkan hasil Throttler::check() (yang mengembalikan bool)
 * dengan 0 memakai ===, sehingga percabangan tidak pernah benar dan
 * brute-force password bisa dilakukan tanpa batas.
 */
class LoginThrottleTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testPercobaanLoginBeruntunDiblokirSetelahLimaKali()
    {
        // Email unik supaya bucket penhitung mulai dari nol.
        $email = 'throttle.' . uniqid() . '@test.local';
        $pesan = '';

        for ($percobaan = 0; $percobaan < 6; $percobaan++) {
            $this->login($email, 'passwordsalah' . $percobaan);

            $pesan = $this->flashError();
        }

        $this->assertStringContainsString('Terlalu banyak percobaan login', $pesan);
    }

    public function testLoginDenganEmailSalahTidakMengungkapEmailTerdaftar()
    {
        $this->login('tidak.ada.' . uniqid() . '@test.local', 'passwordujicoba');

        // Pesan harus sama untuk email tidak dikenal dan password salah,
        // supaya penyerang tidak bisa menyaring email yang terdaftar.
        $this->assertSame('Email atau password salah', $this->flashError());
    }

    private function login(string $email, string $password): void
    {
        $security = Services::security();
        $hash     = $security->generateHash();

        $_COOKIE[$security->getCookieName()] = $hash;

        $this->call('post', 'auth/prosesLogin', [
            $security->getTokenName() => $hash,
            'email'                   => $email,
            'password'                => $password,
        ]);
    }

    private function flashError(): string
    {
        $error = session()->getFlashdata('error');

        return is_array($error) ? implode(' ', $error) : (string) $error;
    }
}
