<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Peta extends BaseController
{
    public function index()
    {
        return view('peta/index');
    }

    // Ubah method untuk menerima dua parameter: $wilayah dan $tingkat
    public function geojson($wilayah, $tingkat)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('data_bankel b');
        $mapJumlah = [];

        // Logika dinamis berdasarkan parameter $tingkat
        if ($tingkat === 'kecamatan') {
            $builder->select('UPPER(kec.nama_kecamatan) AS nama_wilayah, COUNT(b.id) AS total_penerima');
            $builder->join('kecamatan kec', 'b.id_kecamatan = kec.id');
            $builder->groupBy('kec.nama_kecamatan');
        } elseif ($tingkat === 'kelurahan') {
            // Asumsikan Anda punya tabel 'kelurahan' dan kolom 'id_kelurahan' di 'data_bankel'
            $builder->select('UPPER(kel.nama_kelurahan) AS nama_wilayah, COUNT(b.id) AS total_penerima');
            $builder->join('kelurahan kel', 'b.id_kelurahan = kel.id');
            $builder->groupBy('kel.nama_kelurahan');
        } else {
             return $this->response->setStatusCode(400)->setJSON(['error' => 'Tingkat wilayah tidak valid.']);
        }
        
        $result = $builder->get()->getResult();

        // Buat peta wilayah => total penerima
        foreach ($result as $row) {
            $mapJumlah[$row->nama_wilayah] = $row->total_penerima;
        }

        // Nama file GeoJSON kini juga dinamis
        $namaFile = strtolower($wilayah) . '-' . strtolower($tingkat) . '.geojson';
        $path = FCPATH . 'assets/geojson/' . $namaFile;

        
        

        if (!is_file($path)) {
            return $this->response->setStatusCode(404)->setJSON([
                'error' => "GeoJSON tidak ditemukan untuk: $namaFile"
            ]);
        }

        $geojson = json_decode(file_get_contents($path), true);

        if (!isset($geojson['features']) || !is_array($geojson['features'])) {
            return $this->response->setStatusCode(500)->setJSON([
                'error' => 'Format GeoJSON tidak valid.'
            ]);
        }

        // Tambahkan total penerima ke properti
        foreach ($geojson['features'] as &$feature) {
            $nama = strtoupper($feature['properties']['NAMOBJ'] ?? '');
            $feature['properties']['total_penerima'] = $mapJumlah[$nama] ?? 0;
        }

        return $this->response->setHeader('Content-Type', 'application/json')
                              ->setJSON($geojson);
    }

    public function geojson_kuep($wilayah, $tingkat)
    {
          $db = \Config\Database::connect();
        $builder = $db->table('monevkuep_penerima b');
        $mapJumlah = [];

        // Logika dinamis berdasarkan parameter $tingkat
        if ($tingkat === 'kecamatan') {
            $builder->select('UPPER(kec.nama_kecamatan) AS nama_wilayah, COUNT(b.id) AS total_kuep');
            $builder->join('kecamatan kec', 'b.id_kecamatan = kec.id');
            $builder->groupBy('kec.nama_kecamatan');
        } elseif ($tingkat === 'kelurahan') {
            // Asumsikan Anda punya tabel 'kelurahan' dan kolom 'id_kelurahan' di 'data_bankel'
            $builder->select('UPPER(kel.nama_kelurahan) AS nama_wilayah, COUNT(b.id) AS total_kuep');
            $builder->join('kelurahan kel', 'b.id_kelurahan = kel.id');
            $builder->groupBy('kel.nama_kelurahan');
        } else {
             return $this->response->setStatusCode(400)->setJSON(['error' => 'Tingkat wilayah tidak valid.']);
        }

        $result = $builder->get()->getResult();

        // Buat peta wilayah => total penerima
        foreach ($result as $row) {
            $mapJumlah[$row->nama_wilayah] = $row->total_kuep;
        }

        // Nama file GeoJSON kini juga dinamis
        $namaFile = strtolower($wilayah) . '-' . strtolower($tingkat) . '.geojson';
        $path = FCPATH . 'assets/geojson/' . $namaFile;

        if (!is_file($path)) {
            return $this->response->setStatusCode(404)->setJSON([
                'error' => "GeoJSON tidak ditemukan untuk: $namaFile"
            ]);
        }

        $geojson = json_decode(file_get_contents($path), true);

        if (!isset($geojson['features']) || !is_array($geojson['features'])) {
            return $this->response->setStatusCode(500)->setJSON([
                'error' => 'Format GeoJSON tidak valid.'
            ]);
        }

        // Tambahkan total penerima ke properti
        foreach ($geojson['features'] as &$feature) {
            $nama = strtoupper($feature['properties']['NAMOBJ'] ?? '');
            $feature['properties']['total_kuep'] = $mapJumlah[$nama] ?? 0;
        }

        return $this->response->setHeader('Content-Type', 'application/json')
                              ->setJSON($geojson);
    }
}