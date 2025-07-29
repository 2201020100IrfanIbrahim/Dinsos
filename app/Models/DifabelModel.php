<?php

namespace App\Models;

use CodeIgniter\Model;

class DifabelModel extends Model
{
    protected $table            = 'data_difabel';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields    = [
        'nik', 'nama_lengkap', 'jenis_kelamin', 'usia', 'id_kabupaten', 
        'id_kecamatan', 'id_kelurahan', 'dusun', 'alamat_lengkap',
        'golongan_disabilitas', 'sebab_disabilitas', 'id_admin_input'
    ];

    // Timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Aturan validasi yang sudah diperbaiki
    protected $validationRules = [
        'id'                    => 'permit_empty|is_natural_no_zero',
        'nik'                   => 'required|numeric|exact_length[16]|is_unique[data_difabel.nik,id,{id}]',
        'nama_lengkap'          => 'required|min_length[3]',
        'jenis_kelamin'         => 'required',
        'usia'                  => 'required|integer',
        'id_kecamatan'          => 'required|integer',
        'id_kelurahan'          => 'required|integer',
        'golongan_disabilitas'  => 'required'
    ];
    
    // Tambahkan pesan error kustom
    protected $validationMessages   = [
        'nik' => [
            'required'      => 'NIK wajib diisi.',
            'exact_length'  => 'NIK harus terdiri dari 16 angka.',
            'is_unique'     => 'NIK ini sudah terdaftar di sistem difabel.'
        ]
    ];
    // Tambahkan method ini di dalam class DifabelModel

    public function getDifabelData($id_kabupaten = false)
    {
        $builder = $this->db->table($this->table);
        
        // Pilih kolom yang ingin ditampilkan, termasuk nama dari tabel lain
        $builder->select('data_difabel.*, kecamatan.nama_kecamatan, kelurahan.nama_kelurahan');
        
        // Gabungkan dengan tabel wilayah
        $builder->join('kecamatan', 'kecamatan.id = data_difabel.id_kecamatan');
        $builder->join('kelurahan', 'kelurahan.id = data_difabel.id_kelurahan');

        // Filter berdasarkan kabupaten jika ini akun admin
        if ($id_kabupaten !== false) {
            $builder->where('data_difabel.id_kabupaten', $id_kabupaten);
        }
        
        $builder->orderBy('data_difabel.created_at', 'DESC');
        return $builder->get()->getResultArray();
    }
    
    public function getExportData($id_kabupaten = false, $filters = [])
    {
        $builder = $this->db->table($this->table);

        $builder->select("
            data_difabel.nik, 
            data_difabel.nama_lengkap, 
            data_difabel.jenis_kelamin, 
            data_difabel.usia, 
            kabupaten.nama_kabupaten, 
            kecamatan.nama_kecamatan, 
            kelurahan.nama_kelurahan, 
            data_difabel.alamat_lengkap, 
            data_difabel.golongan_disabilitas,
            GROUP_CONCAT(ref_jenis_disabilitas.nama_jenis SEPARATOR ', ') as jenis_disabilitas_list,
            data_difabel.sebab_disabilitas
        ");

        $builder->join('kabupaten', 'kabupaten.id = data_difabel.id_kabupaten', 'left');
        $builder->join('kecamatan', 'kecamatan.id = data_difabel.id_kecamatan', 'left');
        $builder->join('kelurahan', 'kelurahan.id = data_difabel.id_kelurahan', 'left');
        $builder->join('link_difabel_jenis', 'link_difabel_jenis.id_difabel = data_difabel.id', 'left');
        $builder->join('ref_jenis_disabilitas', 'ref_jenis_disabilitas.id = link_difabel_jenis.id_jenis_disabilitas', 'left');

        if ($id_kabupaten !== false) {
            $builder->where('data_difabel.id_kabupaten', $id_kabupaten);
        }

        if (!empty($filters['keyword'])) {
            $builder->groupStart()
                ->like('data_difabel.nik', $filters['keyword'])
                ->orLike('data_difabel.nama_lengkap', $filters['keyword'])
                ->groupEnd();
        }
        if (!empty($filters['golongan'])) {
            $builder->where('data_difabel.golongan_disabilitas', $filters['golongan']);
        }

        $builder->groupBy('data_difabel.id');
        $builder->orderBy('data_difabel.nama_lengkap', 'ASC');

        return $builder->get()->getResultArray();
    }
}