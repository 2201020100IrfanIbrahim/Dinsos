<?php

namespace App\Models;

use CodeIgniter\Model;

class JenisDisabilitasModel extends Model
{
    protected $table            = 'ref_jenis_disabilitas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;

    // Kolom yang diizinkan untuk diisi
    protected $allowedFields    = ['nama_jenis', 'golongan'];

    // Aturan validasi
    protected $validationRules = [
        'nama_jenis' => 'required|is_unique[ref_jenis_disabilitas.nama_jenis,id,{id}]',
        'golongan'   => 'required'
    ];

    // Pesan error kustom untuk validasi
    protected $validationMessages = [
        'nama_jenis' => [
            'required'  => 'Nama jenis disabilitas wajib diisi.',
            'is_unique' => 'Nama jenis ini sudah ada di dalam sistem.'
        ]
    ];
}