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