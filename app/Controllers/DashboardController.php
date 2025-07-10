<?php

namespace App\Controllers;

use App\Models\KabupatenModel; // Panggil model yang baru dibuat

class DashboardController extends BaseController
{
   public function index()
    {
        $session = session();
        $role = $session->get('role');

        // Jika tidak ada role di sesi, lempar kembali ke login
        if (!$role) {
            return redirect()->to('/login');
        }

        // Siapkan data dasar untuk dikirim ke view
        $data = [
            'username' => $session->get('username'),
            'role'     => $role
        ];

        // Pilih view berdasarkan role
        if ($role === 'superadmin') {
            return view('dashboard_superadmin_view', $data);
        }

        if ($role === 'admin') {
            // Jika admin, ambil juga data nama kabupatennya
            $kabupatenModel = new \App\Models\KabupatenModel();
            $id_kabupaten = $session->get('id_kabupaten');

            $kabupatenData = $kabupatenModel->find($id_kabupaten);
            $data['nama_kabupaten'] = $kabupatenData['nama_kabupaten'] ?? 'Wilayah Tidak Ditemukan';

            return view('dashboard_admin_view', $data); 
        }
    }
}