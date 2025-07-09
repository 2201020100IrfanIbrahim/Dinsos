<?php

namespace App\Controllers;

use App\Models\KabupatenModel; // Panggil model yang baru dibuat

class DashboardController extends BaseController
{
    public function index()
    {
        $session = session();
        
        // Siapkan data untuk dikirim ke view
        $data = [
            'username' => $session->get('username'),
            'role'     => $session->get('role')
        ];

        // Jika yang login adalah admin, ambil nama kabupatennya
        if ($session->get('role') === 'admin') {
            $kabupatenModel = new KabupatenModel();
            $id_kabupaten = $session->get('id_kabupaten');
            
            // Cari data kabupaten berdasarkan ID dari sesi
            $kabupatenData = $kabupatenModel->find($id_kabupaten);
            $data['nama_kabupaten'] = $kabupatenData['nama_kabupaten'] ?? 'Wilayah Tidak Ditemukan';
        }

        // Tampilkan view dashboard dan kirim datanya
        return view('dashboard_view', $data);
    }
}