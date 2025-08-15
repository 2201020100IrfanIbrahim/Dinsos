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

$routes->get('peta/geojson/(:any)/(:any)', 'Peta::geojson/$1/$2');
$routes->get('admin/bankel/chart-data', 'BankelController::getChartData');
$routes->get('peta/geojson_difabel/(:any)/(:any)', 'Peta::geojson_difabel/$1/$2');

$routes->get('peta/geojson_kuep/(:any)/(:any)', 'Peta::geojson_kuep/$1/$2');

// Print semua data input
$routes->get('admin/bankel/printAll', 'BankelController::printAll'); // Memproses file
$routes->get('admin/monevkuep/printAll', 'MonevkuepController::printAll'); // Memproses file

// CHART KUEP
    // Chart endpoints MONEVKUEP
    $routes->group('monevkuep', ['namespace' => 'App\Controllers'], function($routes) {
        $routes->get('chart-data-by-year', 'MonevkuepController::getChartDataByYear');
        $routes->get('chart-data-by-gender', 'MonevkuepController::getChartDataByGender');
        $routes->get('chart-data-by-dtks', 'MonevkuepController::getChartDataByDTKS');
        $routes->get('chart-data-by-agama', 'MonevkuepController::getChartDataByAgama');
        $routes->get('chart-data-by-pendidikan', 'MonevkuepController::getChartDataByPendidikan');
        $routes->get('chart-data-by-jenis-usaha', 'MonevkuepController::getChartDataByJenisUsaha');
    });

$routes->group('admin', ['filter' => 'auth'], static function ($routes) {
    //============================BANKEL=============================//
    // Rute untuk menampilkan daftar data (halaman utama)
    $routes->get('bankel', 'BankelController::index');
    // Rute untuk menampilkan form tambah data baru
    $routes->get('bankel/input', 'BankelController::new');
    // Rute untuk MENYIMPAN data dari form (metode POST)
    $routes->post('bankel/create', 'BankelController::create');
    // rute edit 
    $routes->get('bankel/edit/(:num)', 'BankelController::edit/$1');
    $routes->post('bankel/update/(:num)', 'BankelController::update/$1');
    // rute hapus
    $routes->get('bankel/delete/(:num)', 'BankelController::delete/$1');
    
    // Rute untuk AJAX, mengambil data kelurahan berdasarkan ID kecamatan
    $routes->get('bankel/get-kelurahan/(:num)', 'BankelController::getKelurahanByKecamatan/$1');

    // Rute untuk koordinat map
    $routes->get('bankel/leaflet_map/(:num)', 'BankelController::leaflet_map/$1');

    $routes->get('bankel/export', 'BankelController::export');
    //chart
    $routes->get('bankel/chart-data', 'BankelController::getChartData');
    // Di dalam group('admin', ...)
    $routes->get('bankel/import', 'BankelController::import'); // Menampilkan form
    $routes->post('bankel/process-import', 'BankelController::processImport'); // Memproses file
    // Rute untuk data grafik berdasarkan tahun
    $routes->get('bankel/chart-data-by-year', 'BankelController::getChartDataByYear');


    //============================MONEVKUEB=============================//
    $routes->get('monevkuep', 'MonevkuepController::index');
    // Rute untuk menampilkan form tambah data baru
    $routes->get('monevkuep/input', 'MonevkuepController::new');
    // Rute untuk MENYIMPAN data dari form (metode POST)
    $routes->post('monevkuep/create', 'MonevkuepController::create');
     // rute edit 
    $routes->get('monevkuep/edit/(:num)', 'MonevkuepController::edit/$1');
    $routes->post('monevkuep/update/(:num)', 'MonevkuepController::update/$1');
    // rute hapus
    $routes->get('monevkuep/delete/(:num)', 'MonevkuepController::delete/$1');
    
    // Rute untuk AJAX, mengambil data kelurahan berdasarkan ID kecamatan
    $routes->get('monevkuep/get-kelurahan/(:num)', 'MonevkuepController::getKelurahanByKecamatan/$1');

    // Rute untuk koordinat map
    $routes->get('monevkuep/leaflet_map/(:num)', 'MonevkuepController::leaflet_map/$1');

    $routes->get('monevkuep/export', 'MonevkuepController::export');
    //chart
    $routes->get('monevkuep/chart-data', 'MonevkuepController::getChartData');
    // Di dalam group('admin', ...)
    $routes->get('monevkuep/import', 'MonevkuepController::import'); // Menampilkan form
    $routes->post('monevkuep/process-import', 'MonevkuepController::processImport'); // Memproses file
    //============================DIFABELKEPRI=============================//
    $routes->get('difabelkepri', 'DifabelkepriController::index');
    // Rute untuk menampilkan form tambah data baru
    $routes->get('difabelkepri/input', 'DifabelkepriController::new');
    // Rute untuk MENYIMPAN data dari form (metode POST)
    $routes->post('difabelkepri/create', 'DifabelkepriController::create');

    $routes->get('difabelkepri/edit/(:num)', 'DifabelkepriController::edit/$1');
    $routes->post('difabelkepri/update/(:num)', 'DifabelkepriController::update/$1');
    $routes->get('difabelkepri/delete/(:num)', 'DifabelkepriController::delete/$1');
    $routes->get('difabelkepri/export', 'DifabelkepriController::export');

    // Rute manual untuk Manajemen Jenis Disabilitas
    $routes->get('jenis-disabilitas', 'JenisDisabilitasController::index');
    $routes->get('jenis-disabilitas/new', 'JenisDisabilitasController::new');
    $routes->post('jenis-disabilitas/create', 'JenisDisabilitasController::create');
    $routes->get('jenis-disabilitas/edit/(:num)', 'JenisDisabilitasController::edit/$1');
    $routes->post('jenis-disabilitas/update/(:num)', 'JenisDisabilitasController::update/$1');
    $routes->get('jenis-disabilitas/delete/(:num)', 'JenisDisabilitasController::delete/$1');
    // ...

    $routes->get('difabelkepri/chart-golongan', 'DifabelkepriController::getChartDataGolongan');
    $routes->get('difabelkepri/chart-kecamatan', 'DifabelkepriController::getChartDataKecamatan');

    $routes->get('difabelkepri/import', 'DifabelkepriController::import');
    $routes->post('difabelkepri/process-import', 'DifabelkepriController::processImport');
    // Rute-rute CRUD lainnya akan ditambahkan di sini nanti
});