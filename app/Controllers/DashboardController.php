<?php

namespace App\Controllers;

// Panggil model-model yang akan kita gunakan
use App\Models\BankelModel;
use App\Models\DifabelModel;
// (Tambahkan model untuk MONEVKUEP jika sudah ada)

class DashboardController extends BaseController
{
    public function index()
    {
        $session = session();
        $role = $session->get('role');
        $id_kabupaten_admin = $session->get('id_kabupaten');
        
        // Inisialisasi model
        $bankelModel = new BankelModel();
        $difabelModel = new DifabelModel();
        // $monevkuepModel = new MonevkuepModel();

        // Siapkan variabel untuk menampung jumlah data
        $total_bankel = 0;
        $total_difabel = 0;
        $total_monevkuep = 0; // Contoh

        // Hitung jumlah data berdasarkan peran pengguna
        if ($role === 'superadmin') {
            // Superadmin menghitung semua data
            $total_bankel = $bankelModel->countAllResults();
            $total_difabel = $difabelModel->countAllResults();
            // $total_monevkuep = $monevkuepModel->countAllResults();
        } else { // Jika role adalah admin
            // Admin hanya menghitung data di wilayahnya
            $total_bankel = $bankelModel->where('id_kabupaten', $id_kabupaten_admin)->countAllResults();
            $total_difabel = $difabelModel->where('id_kabupaten', $id_kabupaten_admin)->countAllResults();
            // $total_monevkuep = $monevkuepModel->where('id_kabupaten', $id_kabupaten_admin)->countAllResults();
        }

        // Kirim semua data yang dibutuhkan ke view
        $data = [
            'username' => $session->get('username'),
            'role'     => $role,
            'total_bankel' => $total_bankel,
            'total_difabel' => $total_difabel,
            'total_monevkuep' => $total_monevkuep
        ];
        
        // Pilih view dashboard berdasarkan role
        if ($role === 'superadmin') {
            // Anda bisa buat view terpisah jika perlu
            return view('dashboard_admin_view', $data);
        } else {
            return view('dashboard_admin_view', $data);
        }
    }
}