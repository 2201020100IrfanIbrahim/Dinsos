<?php

namespace App\Controllers;

// Panggil model-model yang akan kita gunakan
use App\Models\BankelModel;
use App\Models\DifabelModel;
use App\Models\MonevkuepModel;

class WelcomeController extends BaseController
{
    /**
     * Menampilkan halaman depan publik (welcome_message.php)
     * dengan data total dari seluruh kabupaten.
     */
    public function index()
    {
        // 1. Inisialisasi semua model yang dibutuhkan
        $bankelModel = new BankelModel();
        $difabelModel = new DifabelModel();
        $monevkuepModel = new MonevkuepModel();

        // 2. Hitung total data dari seluruh tabel TANPA filter role/wilayah
        $total_bankel = $bankelModel->countAllResults();
        $total_difabel = $difabelModel->countAllResults();
        $total_monevkuep = $monevkuepModel->countAllResults();

        // 3. Siapkan semua data untuk dikirim ke view
        $data = [
            'title'           => 'SPBS-D KEPRI - Beranda',
            'jumlah_bansos'   => $total_bankel,
            'jumlah_disabilitas' => $total_difabel,
            'jumlah_umkm'     => $total_monevkuep,
        ];

        // 4. Muat view halaman utama dan kirim datanya
        return view('welcome_message', $data);
    }
}
