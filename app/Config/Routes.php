<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

$routes->get('/', 'Auth::login');
$routes->get('/login', 'Auth::login');

$routes->post('/auth/prosesLogin', 'Auth::prosesLogin');
$routes->get('/logout', 'Auth::logout');

$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::simpanRegister');

$routes->get('/profil', 'Auth::profil');
$routes->get('/profil/edit', 'Auth::editProfile');
$routes->post('/auth/updateProfile', 'Auth::updateProfile');

$routes->get('/profil/password', 'Auth::password');
$routes->post('/auth/updatePassword', 'Auth::updatePassword');


/*
|--------------------------------------------------------------------------
| ADMIN & KADER
|--------------------------------------------------------------------------
*/

$routes->group('', ['filter' => 'role:admin,kader'], function ($routes) {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('dashboard/statistik', 'Dashboard::statistik');


    /*
    |--------------------------------------------------------------------------
    | Balita
    |--------------------------------------------------------------------------
    */

    $routes->get('balita', 'Balita::index');

    $routes->get('balita/tambah', 'Balita::tambah');
    $routes->post('balita/simpan', 'Balita::simpan');

    $routes->get('balita/detail/(:num)', 'Balita::detail/$1');

    $routes->get('balita/edit/(:num)', 'Balita::edit/$1');
    $routes->post('balita/update/(:num)', 'Balita::update/$1');

    $routes->get('balita/hapus/(:num)', 'Balita::hapus/$1');


    /*
    |--------------------------------------------------------------------------
    | Pengukuran
    |--------------------------------------------------------------------------
    */

    $routes->get('pengukuran', 'Pengukuran::index');

    $routes->match(
        ['get','post'],
        'pengukuran/(:num)',
        'Pengukuran::tambah/$1'
    );

    $routes->post(
        'pengukuran/simpan',
        'Pengukuran::simpan'
    );

    // nanti kita buat controller-nya
    $routes->get(
        'pengukuran/riwayat/(:num)',
        'Pengukuran::riwayat/$1'
    );


    /*
    |--------------------------------------------------------------------------
    | Mapping
    |--------------------------------------------------------------------------
    */

    $routes->get(
        'mapping',
        'Mapping::index'
    );


    /*
    |--------------------------------------------------------------------------
    | Laporan
    |--------------------------------------------------------------------------
    */

    $routes->get(
        'laporan',
        'Laporan::index'
    );

    $routes->get(
        'laporan/pdf',
        'Laporan::pdf'
    );


    /*
    |--------------------------------------------------------------------------
    | User
    |--------------------------------------------------------------------------
    */

    $routes->get(
        'classuser',
        'ClassUser::index'
    );

    $routes->get(
        'classuser/tambah',
        'ClassUser::tambah'
    );

    $routes->get(
        'classuser/edit/(:num)',
        'ClassUser::edit/$1'
    );

    $routes->post(
        'classuser/update/(:num)',
        'ClassUser::update/$1'
    );

    $routes->post(
        'classuser/hapus/(:num)',
        'ClassUser::hapus/$1'
    );

});


/*
|--------------------------------------------------------------------------
| ADMIN + KADER + ORANGTUA
|--------------------------------------------------------------------------
*/

$routes->group('', ['filter' => 'role:admin,kader,orangtua'], function ($routes) {

    $routes->get(
        'balita',
        'Balita::index'
    );

    $routes->get(
        'balita/detail/(:num)',
        'Balita::detail/$1'
    );

});


/*
|--------------------------------------------------------------------------
| ORANG TUA
|--------------------------------------------------------------------------
*/

$routes->group('', ['filter' => 'role:orangtua'], function ($routes) {

    $routes->get(
        'konsultasi',
        'Konsultasi::index'
    );

    $routes->get(
        'konsultasi/anak/(:num)',
        'Konsultasi::anak/$1'
    );

});