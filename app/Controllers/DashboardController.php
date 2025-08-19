<?php

namespace App\Controllers;

// Panggil model-model yang akan kita gunakan
use App\Models\BankelModel;
use App\Models\DifabelModel;
use App\Models\MonevkuepModel;
use App\Models\KabupatenModel;


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
        $monevkuepModel = new MonevkuepModel();

        // Siapkan variabel untuk menampung jumlah data
        $total_bankel = 0;
        $total_difabel = 0;
        $total_kuep = 0; // Contoh

        // Hitung jumlah data berdasarkan peran pengguna
        if ($role === 'superadmin') {
            // Superadmin menghitung semua data
            $total_bankel = $bankelModel->countAllResults();
            $total_difabel = $difabelModel->countAllResults();
            $total_kuep = $monevkuepModel->countAllResults();
            // $total_monevkuep = $monevkuepModel->countAllResults();
        } else { // Jika role adalah admin
            // Admin hanya menghitung data di wilayahnya
            $total_bankel = $bankelModel->where('id_kabupaten', $id_kabupaten_admin)->countAllResults();
            $total_difabel = $difabelModel->where('id_kabupaten', $id_kabupaten_admin)->countAllResults();
            $total_kuep = $monevkuepModel->where('id_kabupaten', $id_kabupaten_admin)->countAllResults();
        }

        // Siapkan variabel untuk menampung jumlah data
        $total_bankel = 0;
        $total_difabel = 0;
        $total_kuep = 0; // Contoh

        // Hitung jumlah data berdasarkan peran pengguna
        if ($role === 'superadmin') {
            // Superadmin menghitung semua data
            $total_bankel = $bankelModel->countAllResults();
            $total_difabel = $difabelModel->countAllResults();
            $total_kuep = $monevkuepModel->countAllResults();
        } else { // Jika role adalah admin
            // Admin hanya menghitung data di wilayahnya
            $total_bankel = $bankelModel->where('id_kabupaten', $id_kabupaten_admin)->countAllResults();
            $total_difabel = $difabelModel->where('id_kabupaten', $id_kabupaten_admin)->countAllResults();
            $total_kuep = $monevkuepModel->where('id_kabupaten', $id_kabupaten_admin)->countAllResults();
        }

        // Kirim semua data yang dibutuhkan ke view
        $data = [
            'username' => $session->get('username'),
            'role'     => $role,
            'total_bankel' => $total_bankel,
            'total_difabel' => $total_difabel,
            'total_monevkuep' => $total_kuep
        ];

        // Pilih view dashboard berdasarkan role
        if ($role === 'superadmin') {
            // Anda bisa buat view terpisah jika perlu
            return view('dashboard_superadmin_view', $data);
        } else {
            return view('dashboard_admin_view', $data);
        }
    }

    // FUNGSI BARU 1: UNTUK GRAFIK DISTRIBUSI PER WILAYAH
    public function chart_wilayah()
    {
        $kabupatenModel = new KabupatenModel();
        $bankelModel = new BankelModel();
        $difabelModel = new DifabelModel();
        $monevkuepModel = new MonevkuepModel();

        // Ambil semua kabupaten sebagai dasar
        $kabupatens = $kabupatenModel->orderBy('nama_kabupaten', 'ASC')->findAll();

        // Ambil total data dari setiap model, dikelompokkan per kabupaten
        $bankel_counts = $bankelModel->select('id_kabupaten, COUNT(id) as total')->groupBy('id_kabupaten')->findAll();
        $difabel_counts = $difabelModel->select('id_kabupaten, COUNT(id) as total')->groupBy('id_kabupaten')->findAll();
        $kuep_counts = $monevkuepModel->select('id_kabupaten, COUNT(id) as total')->groupBy('id_kabupaten')->findAll();

        // Ubah hasil query menjadi format yang mudah diakses [id_kabupaten => total]
        $bankel_map = array_column($bankel_counts, 'total', 'id_kabupaten');
        $difabel_map = array_column($difabel_counts, 'total', 'id_kabupaten');
        $kuep_map = array_column($kuep_counts, 'total', 'id_kabupaten');

        $labels = [];
        $data_bankel = [];
        $data_difabel = [];
        $data_kuep = [];

        // Loop melalui setiap kabupaten untuk membangun data chart
        foreach ($kabupatens as $kab) {
            $nama_pendek = str_replace(['Kabupaten ', 'Kota '], '', $kab['nama_kabupaten']);
            $labels[] = $nama_pendek;
            $data_bankel[] = $bankel_map[$kab['id']] ?? 0;
            $data_difabel[] = $difabel_map[$kab['id']] ?? 0;
            $data_kuep[] = $kuep_map[$kab['id']] ?? 0;
        }

        return $this->response->setJSON([
            'labels' => $labels,
            'bankel' => $data_bankel,
            'difabel' => $data_difabel,
            'kuep' => $data_kuep
        ]);
    }

    // FUNGSI BARU 2: UNTUK GRAFIK BANKEL PER TAHUN
    public function chart_bankel_by_year()
    {
        $bankelModel = new BankelModel();
        $data = $bankelModel->getChartDataByYear(); // Gunakan method yang sudah ada di model

        return $this->response->setJSON([
            'labels' => array_column($data, 'tahun_penerimaan'),
            'values' => array_column($data, 'jumlah')
        ]);
    }

    // FUNGSI BARU 3: UNTUK GRAFIK DIFABEL PER GOLONGAN
    public function chart_difabel_by_golongan()
    {
        $difabelModel = new DifabelModel();
        $data = $difabelModel->getChartDataByGolongan(); // Gunakan method yang sudah ada di model

        return $this->response->setJSON([
            'labels' => array_column($data, 'golongan_disabilitas'),
            'values' => array_column($data, 'jumlah')
        ]);
    }

    // FUNGSI BARU 4: UNTUK GRAFIK MONEVKUEP PER JENIS USAHA
    public function chart_monevkuep_by_usaha()
    {
        $monevkuepModel = new MonevkuepModel();
        $data = $monevkuepModel->getChartDataByJenisUsaha(); // Gunakan method yang sudah ada di model

        // Filter out null or empty values
        $filtered_data = array_filter($data, function($item) {
            return !empty($item['jenis_usaha']);
        });

        return $this->response->setJSON([
            'labels' => array_column($filtered_data, 'jenis_usaha'),
            'values' => array_column($filtered_data, 'jumlah')
        ]);
    }
}