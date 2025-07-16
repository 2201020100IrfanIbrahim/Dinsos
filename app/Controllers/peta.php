<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Peta extends BaseController
{
    public function index()
    {
        return view('peta/index'); // Harus ada file: app/Views/peta/index.php
    }

    public function geojson($wilayah = 'karimun')
    {
        $db = \Config\Database::connect();

        // Ambil jumlah penerima per kecamatan
        $builder = $db->table('data_bankel b');
        $builder->select('UPPER(kec.nama_kecamatan) AS nama_kecamatan, COUNT(b.id) AS total_penerima');
        $builder->join('kecamatan kec', 'b.id_kecamatan = kec.id');
        $builder->groupBy('kec.nama_kecamatan');
        $result = $builder->get()->getResult();

        // Buat peta kecamatan => total penerima
        $mapJumlah = [];
        foreach ($result as $row) {
            $mapJumlah[$row->nama_kecamatan] = $row->total_penerima;
        }

        // Ambil file GeoJSON berdasarkan nama wilayah (pakai huruf kecil)
        $namaFile = strtolower($wilayah) . '.geojson';
        $path = FCPATH . 'assets/geojson/' . $namaFile;

        if (!is_file($path)) {
            return $this->response->setStatusCode(404)->setJSON([
                'error' => "GeoJSON tidak ditemukan untuk wilayah: $wilayah"
            ]);
        }

        $geojson = json_decode(file_get_contents($path), true);

        if (!isset($geojson['features']) || !is_array($geojson['features'])) {
            return $this->response->setStatusCode(500)->setJSON([
                'error' => 'Format GeoJSON tidak valid.'
            ]);
        }

        // Tambahkan total penerima ke properti masing-masing kecamatan
        foreach ($geojson['features'] as &$feature) {
            $nama = strtoupper($feature['properties']['NAMOBJ'] ?? '');
            $feature['properties']['total_penerima'] = $mapJumlah[$nama] ?? 0;
        }

        return $this->response->setHeader('Content-Type', 'application/json')
                              ->setJSON($geojson);
    }
}
