<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
// ... kode rute lainnya

// Rute untuk menampilkan halaman login
$routes->get('/login', 'AuthController::login');

// Rute untuk memproses form login
$routes->post('/login', 'AuthController::attemptLogin'); 

// Rute untuk logout
$routes->get('/logout', 'AuthController::logout');

$routes->get('/dashboard', 'DashboardController::index', ['filter' => 'auth']);
$routes->get('/testhash', function() {
    echo password_hash('adminlingga', PASSWORD_DEFAULT);
});

$routes->group('admin', ['filter' => 'auth'], static function ($routes) {
    //============================BANKEL=============================//
    // Rute untuk menampilkan daftar data (halaman utama)
    $routes->get('bankel', 'BankelController::index');
    // Rute untuk menampilkan form tambah data baru
    $routes->get('bankel/input', 'BankelController::new');
    // Rute untuk MENYIMPAN data dari form (metode POST)
    $routes->post('bankel/create', 'BankelController::create');
    
    
    //============================MONEVKUEB=============================//
    $routes->get('monevkuep', 'MonevkuepController::index');
    // Rute untuk menampilkan form tambah data baru
    $routes->get('monevkuep/input', 'MonevkuepController::new');
    // Rute untuk MENYIMPAN data dari form (metode POST)
    $routes->post('monevkuep/create', 'MonevkuepController::create');
    
    
    //============================DIFABELKEPRI=============================//
    $routes->get('difabelkepri', 'DifabelkepriController::index');
    // Rute untuk menampilkan form tambah data baru
    $routes->get('difabelkepri/input', 'DifabelkepriController::new');
    // Rute untuk MENYIMPAN data dari form (metode POST)
    $routes->post('difabelkepri/create', 'DifabelkepriController::create');

    // Rute-rute CRUD lainnya akan ditambahkan di sini nanti
});