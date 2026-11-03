<?php

namespace App\Controllers;

class Home extends BaseController
{
    /**
     * Config\App::$defaultController masih menunjuk ke kelas ini, jadi method
     * tidak boleh menghapus view 'welcome_message' dan pergi begitu saja.
     */
    public function index()
    {
        return redirect()->to('/login');
    }
}
