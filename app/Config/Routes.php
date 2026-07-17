<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

/*
|--------------------------------------------------------------------------
| AUTH (Public)
|--------------------------------------------------------------------------
*/

$routes->get('/', 'Auth::login');
$routes->get('/login', 'Auth::login');
$routes->post('/auth/prosesLogin', 'Auth::prosesLogin');
$routes->get('/logout', 'Auth::logout');
$routes->get('/seeder', 'Seeder::index');

$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::simpanRegister');

$routes->get('/profil', 'Auth::profil');
$routes->get('/profil/edit', 'Auth::editProfile');
$routes->post('/auth/updateProfile', 'Auth::updateProfile');

$routes->get('/profil/password', 'Auth::password');
$routes->post('/auth/updatePassword', 'Auth::updatePassword');

/*
|--------------------------------------------------------------------------
| ADMIN & KADER — Manajemen
|--------------------------------------------------------------------------
*/

$routes->group('', ['filter' => 'role:admin,kader'], function ($routes) {

    // Dashboard
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('dashboard/statistik', 'Dashboard::statistik');

    // Balita — CRUD (tambah, edit, hapus)
    $routes->get('balita/tambah', 'Balita::tambah');
    $routes->post('balita/simpan', 'Balita::simpan');
    $routes->get('balita/edit/(:num)', 'Balita::edit/$1');
    $routes->post('balita/update/(:num)', 'Balita::update/$1');
    $routes->post('balita/hapus/(:num)', 'Balita::hapus/$1');

    // Pengukuran — index (daftar semua pengukuran)
    $routes->get('pengukuran', 'Pengukuran::index');
    $routes->match(['get', 'post'], 'pengukuran/(:num)', 'Pengukuran::tambah/$1');
    $routes->post('pengukuran/simpan', 'Pengukuran::simpan');
    $routes->get('pengukuran/riwayat/(:num)', 'Pengukuran::riwayat/$1');
    $routes->get('pengukuran/edit/(:num)', 'Pengukuran::edit/$1');
    $routes->post('pengukuran/update/(:num)', 'Pengukuran::update/$1');
    $routes->post('pengukuran/hapus/(:num)', 'Pengukuran::hapus/$1');

    // Mapping
    $routes->get('mapping', 'Mapping::index');

    // Laporan
    $routes->get('laporan', 'Laporan::index');
    $routes->get('laporan/pdf', 'Laporan::pdf');
    $routes->get('laporan/excel', 'Laporan::excel');
    $routes->get('laporan/recap/(:num)', 'Laporan::recap/$1');

    // User Management
    $routes->get('classuser', 'ClassUser::index');
    $routes->get('classuser/tambah', 'ClassUser::tambah');
    $routes->get('classuser/edit/(:num)', 'ClassUser::edit/$1');
    $routes->post('classuser/update/(:num)', 'ClassUser::update/$1');
    $routes->post('classuser/hapus/(:num)', 'ClassUser::hapus/$1');
});

/*
|--------------------------------------------------------------------------
| SEMUA ROLE — Balita (view), Grafik, Konsultasi
|--------------------------------------------------------------------------
*/

$routes->group('', ['filter' => 'role:admin,kader,orangtua'], function ($routes) {

    // Balita — view only
    $routes->get('balita', 'Balita::index');
    $routes->get('balita/detail/(:num)', 'Balita::detail/$1');

    // Pengukuran — grafik & riwayat
    $routes->get('pengukuran/grafik/(:num)', 'Pengukuran::grafik/$1');

    // Konsultasi — semua role bisa akses
    $routes->get('konsultasi', 'Konsultasi::index');
    $routes->get('konsultasi/anak/(:num)', 'Konsultasi::anak/$1');
    $routes->get('konsultasi/chat/(:num)', 'Konsultasi::chatOrangTua/$1');
});

/*
|--------------------------------------------------------------------------
| ORANG TUA — Dashboard & Statistik Khusus
|--------------------------------------------------------------------------
*/

$routes->group('', ['filter' => 'role:orangtua'], function ($routes) {

    $routes->get('dashboard-orangtua', 'Dashboard::orangtua');
    $routes->get('dashboard/statistik-orangtua', 'Dashboard::statistikOrangtua');
});