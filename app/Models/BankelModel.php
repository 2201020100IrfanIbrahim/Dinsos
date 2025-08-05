<?php

namespace App\Models;

use CodeIgniter\Model;

class BankelModel extends Model
{
    protected $table            = 'data_bankel';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    // Kolom-kolom yang diizinkan untuk diisi secara massal
    protected $allowedFields    = [
        'nik',
        'nama_lengkap',
        'id_kabupaten',
        'id_kecamatan',
        'id_kelurahan',
        'dusun',
        'rt',
        'rw',
        'alamat_lengkap',
        'kategori_bantuan',
        'status_penerimaan',
        'tahun_penerimaan',
        'id_admin_input',
        'gambar',
        'koordinat'
    ];

    // Menggunakan timestamp otomatis
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Aturan Validasi
    protected $validationRules      = [
        'id'            => 'permit_empty|is_natural_no_zero',
        'nik' => 'required|numeric|exact_length[16]|is_unique[data_bankel.nik,id,{id}]',
        'nama_lengkap'  => 'required|min_length[3]',
        'id_kabupaten'  => 'required|integer',
        'id_kecamatan'  => 'required|integer',
        'id_kelurahan'  => 'required|integer',
        'dusun'         => 'permit_empty|string',
        'rt'            => 'required|numeric',
        'rw'            => 'required|numeric',
        'tahun_penerimaan' => 'required|exact_length[4]|integer',
        'kategori_bantuan' => 'required'
    ];

    // Pesan Error untuk Validasi
    protected $validationMessages   = [
        'nik' => [
            'required'      => 'NIK wajib diisi',
            'exact_length'  => 'NIK harus terdiri dari 16 angka',
            'is_unique'     => 'NIK ini sudah terdaftar'
        ],
        'nama_lengkap' => [
            'required' => 'Nama lengkap wajib diisi'
        ]
    ];

    public function getBankelData($id_kabupaten = false, $filters = [])
    {
        $builder = $this->db->table($this->table);
        $builder->select('data_bankel.*, users.username as nama_admin, kabupaten.nama_kabupaten, kecamatan.nama_kecamatan, kelurahan.nama_kelurahan');
        $builder->join('users', 'users.id = data_bankel.id_admin_input');
        $builder->join('kabupaten', 'kabupaten.id = data_bankel.id_kabupaten');
        $builder->join('kecamatan', 'kecamatan.id = data_bankel.id_kecamatan');
        $builder->join('kelurahan', 'kelurahan.id = data_bankel.id_kelurahan');

        if ($id_kabupaten !== false) {
            $builder->where('data_bankel.id_kabupaten', $id_kabupaten);
        }

        // --- LOGIKA FILTER YANG DIPERBARUI ---
        if (!empty($filters['keyword'])) {
            $builder->groupStart();
            $builder->like('data_bankel.nik', $filters['keyword']);
            $builder->orLike('data_bankel.nama_lengkap', $filters['keyword']);
            $builder->orLike('kecamatan.nama_kecamatan', $filters['keyword']);   // <-- TAMBAHAN
            $builder->orLike('kelurahan.nama_kelurahan', $filters['keyword']); // <-- TAMBAHAN
            $builder->groupEnd();
        }

        if (!empty($filters['tahun'])) {
            $builder->where('data_bankel.tahun_penerimaan', $filters['tahun']);
        }
        // --- BATAS LOGIKA FILTER ---
        
        $builder->orderBy('data_bankel.created_at', 'DESC');

        return $builder->get()->getResultArray();
    }

    ///////////////////////////////////////////////////////////////////////////////////////////
    //--------------------------------------- GRAFIK ----------------------------------------//
    ///////////////////////////////////////////////////////////////////////////////////////////

    public function getChartDataByKecamatan($id_kabupaten = false)
    {
        $builder = $this->db->table($this->table);

        // Pilih nama kecamatan dan hitung jumlah data (COUNT)
        $builder->select('kecamatan.nama_kecamatan, COUNT(data_bankel.id) as jumlah');
        $builder->join('kecamatan', 'kecamatan.id = data_bankel.id_kecamatan');

        // Filter berdasarkan kabupaten jika ini akun admin
        if ($id_kabupaten !== false) {
            $builder->where('data_bankel.id_kabupaten', $id_kabupaten);
        }

        // Kelompokkan hasilnya berdasarkan nama kecamatan
        $builder->groupBy('kecamatan.nama_kecamatan');
        $builder->orderBy('jumlah', 'DESC'); // Urutkan dari yang terbanyak

        return $builder->get()->getResultArray();
    }
    public function getChartDataByYear($id_kabupaten = false)
    {
        $builder = $this->db->table($this->table);
        $builder->select('tahun_penerimaan, COUNT(id) as jumlah');

        if ($id_kabupaten !== false) {
            $builder->where('id_kabupaten', $id_kabupaten);
        }

        $builder->groupBy('tahun_penerimaan');
        $builder->orderBy('tahun_penerimaan', 'ASC');

        return $builder->get()->getResultArray();
    }
}