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
        'id_admin_input'
    ];

    // Menggunakan timestamp otomatis
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Aturan Validasi
    protected $validationRules      = [
        'nik' => 'required|numeric|exact_length[16]',
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
            'required'      => 'NIK wajib diisi.',
            'exact_length'  => 'NIK harus terdiri dari 16 angka.',
            'is_unique'     => 'NIK ini sudah terdaftar.'
        ],
        'nama_lengkap' => [
            'required' => 'Nama lengkap wajib diisi.'
        ]
    ];

    public function getBankelData($id_kabupaten = false)
    {
        $builder = $this->db->table($this->table);

        // 1. UBAH BARIS INI: Tambahkan 'kecamatan.nama_kecamatan'
        $builder->select('data_bankel.*, users.username as nama_admin, kabupaten.nama_kabupaten, kecamatan.nama_kecamatan');

        $builder->join('users', 'users.id = data_bankel.id_admin_input');
        $builder->join('kabupaten', 'kabupaten.id = data_bankel.id_kabupaten');

        // 2. TAMBAHKAN BARIS INI: Join dengan tabel kecamatan
        $builder->join('kecamatan', 'kecamatan.id = data_bankel.id_kecamatan');

        if ($id_kabupaten !== false) {
            $builder->where('data_bankel.id_kabupaten', $id_kabupaten);
        }

        $builder->orderBy('data_bankel.created_at', 'DESC');

        return $builder->get()->getResultArray();
    }
}