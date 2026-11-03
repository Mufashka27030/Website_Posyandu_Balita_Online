<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

class AksesHalamanTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    /**
     * Tidak login sama sekali, coba buka /dashboard — harus diarahkan
     * pergi (redirect ke /login), bukan berhasil masuk.
     */
    public function testDashboardMenolakYangBelumLogin()
    {
        $result = $this->call('get', 'dashboard');

        $result->assertRedirect();
    }

    /**
     * Halaman FAQ harus bisa dibuka tanpa login sama sekali — sesuai
     * yang kita rancang supaya orang yang belum bisa masuk (lupa
     * password dsb) tetap bisa baca jawabannya.
     */
    public function testFaqBisaDiaksesTanpaLogin()
    {
        $result = $this->call('get', 'faq');

        $result->assertOK();
    }
}