<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

//======================================================================
// RUTE PUBLIK & AUTENTIKASI
//======================================================================
$routes->get('/', 'WelcomeController::index'); // Halaman utama website
$routes->get('/login', 'AuthController::login'); // Halaman login
$routes->post('/login', 'AuthController::attemptLogin'); // Proses submit login
$routes->get('/logout', 'AuthController::logout'); // Proses logout

//======================================================================
// RUTE DASHBOARD UTAMA
//======================================================================
$routes->get('/dashboard', 'DashboardController::index', ['filter' => 'auth']);
// Endpoint data untuk chart di dashboard utama
$routes->get('/dashboard/chart-wilayah', 'DashboardController::chart_wilayah', ['filter' => 'auth']);
$routes->get('/dashboard/chart-bankel-by-year', 'DashboardController::chart_bankel_by_year', ['filter' => 'auth']);
$routes->get('/dashboard/chart-difabel-by-golongan', 'DashboardController::chart_difabel_by_golongan', ['filter' => 'auth']);
$routes->get('/dashboard/chart-monevkuep-by-usaha', 'DashboardController::chart_monevkuep_by_usaha', ['filter' => 'auth']);

//======================================================================
// ENDPOINT API & DATA SPESIFIK
//======================================================================
// Endpoint untuk data GeoJSON Peta
$routes->get('peta/geojson/(:any)/(:any)', 'Peta::geojson/$1/$2');
$routes->get('peta/geojson_difabel/(:any)/(:any)', 'Peta::geojson_difabel/$1/$2');
$routes->get('peta/geojson_kuep/(:any)/(:any)', 'Peta::geojson_kuep/$1/$2');

// Endpoint untuk fitur cetak (print)
$routes->get('admin/bankel/printAll', 'BankelController::printAll');
$routes->get('admin/monevkuep/printAll', 'MonevkuepController::printAll');

// Grup endpoint untuk data chart Monev KUEP
$routes->group('monevkuep', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('chart-data-by-year', 'MonevkuepController::getChartDataByYear');
    $routes->get('chart-data-by-gender', 'MonevkuepController::getChartDataByGender');
    $routes->get('chart-data-by-dtks', 'MonevkuepController::getChartDataByDTKS');
    $routes->get('chart-data-by-agama', 'MonevkuepController::getChartDataByAgama');
    $routes->get('chart-data-by-pendidikan', 'MonevkuepController::getChartDataByPendidikan');
    $routes->get('chart-data-by-jenis-usaha', 'MonevkuepController::getChartDataByJenisUsaha');
});

//======================================================================
// RUTE AREA ADMIN
//======================================================================
$routes->group('admin', ['filter' => 'auth'], static function ($routes) {
    
    //-----------------------------------------------------
    // BANKEL
    //-----------------------------------------------------
    // CRUD Operations
    $routes->get('bankel', 'BankelController::index'); // Daftar data
    $routes->get('bankel/input', 'BankelController::new'); // Form tambah
    $routes->post('bankel/create', 'BankelController::create'); // Simpan data baru
    $routes->get('bankel/edit/(:num)', 'BankelController::edit/$1'); // Form edit
    $routes->post('bankel/update/(:num)', 'BankelController::update/$1'); // Update data
    $routes->get('bankel/delete/(:num)', 'BankelController::delete/$1'); // Hapus data
    
    // Fitur Tambahan Bankel
    $routes->get('bankel/leaflet_map/(:num)', 'BankelController::leaflet_map/$1'); // Koordinat Peta
    $routes->get('bankel/export', 'BankelController::export'); // Export data ke Excel
    $routes->get('bankel/import', 'BankelController::import'); // Halaman form import
    $routes->post('bankel/process-import', 'BankelController::processImport'); // Proses file import
    
    // AJAX & Chart Data Bankel
    $routes->get('bankel/get-kecamatan/(:num)', 'BankelController::getKecamatanByKabupaten/$1');
    $routes->get('bankel/get-kelurahan/(:num)', 'BankelController::getKelurahanByKecamatan/$1');
    $routes->get('bankel/chart-data', 'BankelController::getChartData');
    $routes->get('bankel/chart-data-by-year', 'BankelController::getChartDataByYear');
    
    //-----------------------------------------------------
    // MONEVKUEP
    //-----------------------------------------------------
    // CRUD Operations
    $routes->get('monevkuep', 'MonevkuepController::index'); // Daftar data
    $routes->get('monevkuep/input', 'MonevkuepController::new'); // Form tambah
    $routes->post('monevkuep/create', 'MonevkuepController::create'); // Simpan data baru
    $routes->get('monevkuep/edit/(:num)', 'MonevkuepController::edit/$1'); // Form edit
    $routes->post('monevkuep/update/(:num)', 'MonevkuepController::update/$1'); // Update data
    $routes->get('monevkuep/delete/(:num)', 'MonevkuepController::delete/$1'); // Hapus data

    // Fitur Tambahan Monevkuep
    $routes->get('monevkuep/leaflet_map/(:num)', 'MonevkuepController::leaflet_map/$1'); // Koordinat Peta
    $routes->get('monevkuep/export', 'MonevkuepController::export'); // Export data ke Excel
    $routes->get('monevkuep/import', 'MonevkuepController::import'); // Halaman form import
    $routes->post('monevkuep/process-import', 'MonevkuepController::processImport'); // Proses file import
    
    // AJAX & Chart Data Monevkuep
    $routes->get('monevkuep/get-kecamatan/(:num)', 'MonevkuepController::getKecamatanByKabupaten/$1');
    $routes->get('monevkuep/get-kelurahan/(:num)', 'MonevkuepController::getKelurahanByKecamatan/$1');
    $routes->get('monevkuep/chart-data', 'MonevkuepController::getChartData');

    //-----------------------------------------------------
    // DIFABEL 
    //-----------------------------------------------------
    // CRUD Operations
    $routes->get('difabelkepri', 'DifabelkepriController::index'); // Daftar data
    $routes->get('difabelkepri/input', 'DifabelkepriController::new'); // Form tambah
    $routes->post('difabelkepri/create', 'DifabelkepriController::create'); // Simpan data baru
    $routes->get('difabelkepri/edit/(:num)', 'DifabelkepriController::edit/$1'); // Form edit
    $routes->post('difabelkepri/update/(:num)', 'DifabelkepriController::update/$1'); // Update data
    $routes->get('difabelkepri/delete/(:num)', 'DifabelkepriController::delete/$1'); // Hapus data
    
    // Fitur Tambahan Difabel
    $routes->get('difabelkepri/export', 'DifabelkepriController::export'); // Export data ke Excel
    $routes->get('difabelkepri/import', 'DifabelkepriController::import'); // Halaman form import
    $routes->post('difabelkepri/process-import', 'DifabelkepriController::processImport'); // Proses file import

    // AJAX & Chart Data Difabel
    $routes->get('difabelkepri/get-kecamatan/(:num)', 'DifabelkepriController::getKecamatanByKabupaten/$1');
    $routes->get('difabelkepri/get-kelurahan/(:num)', 'DifabelkepriController::getKelurahanByKecamatan/$1');
    $routes->get('difabelkepri/chart-golongan', 'DifabelkepriController::getChartDataGolongan');
    $routes->get('difabelkepri/chart-kecamatan', 'DifabelkepriController::getChartDataKecamatan');

    // Manajemen Jenis Disabilitas
    $routes->get('jenis-disabilitas', 'JenisDisabilitasController::index');
    $routes->get('jenis-disabilitas/new', 'JenisDisabilitasController::new');
    $routes->post('jenis-disabilitas/create', 'JenisDisabilitasController::create');
    $routes->get('jenis-disabilitas/edit/(:num)', 'JenisDisabilitasController::edit/$1');
    $routes->post('jenis-disabilitas/update/(:num)', 'JenisDisabilitasController::update/$1');
    $routes->get('jenis-disabilitas/delete/(:num)', 'JenisDisabilitasController::delete/$1');

    //-----------------------------------------------------
    // MANAJEMEN PENGGUNA (USERS)
    //-----------------------------------------------------
    $routes->group('users', ['namespace' => 'App\Controllers'], function($routes) {
        $routes->get('/', 'UserController::index'); // Daftar pengguna
        $routes->get('edit/(:num)', 'UserController::edit/$1'); // Form edit pengguna
        $routes->post('update/(:num)', 'UserController::update/$1'); // Update data pengguna
    });
    
    // Profil Pengguna yang sedang login
    $routes->get('profile', 'UserController::profile');
    $routes->post('profile/update', 'UserController::updateProfile');
});


//======================================================================
// RUTE UNTUK DEVELOPMENT/TESTING
//======================================================================
$routes->get('/testhash', function() {
    echo password_hash('adminlingga', PASSWORD_DEFAULT);
});