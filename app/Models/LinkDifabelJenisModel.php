<?php

namespace App\Models;

use CodeIgniter\Model;

class LinkDifabelJenisModel extends Model
{
    protected $table            = 'link_difabel_jenis';
    protected $allowedFields    = ['id_difabel', 'id_jenis_disabilitas'];
    
    public function getJenisByDifabelId($id_difabel)
    {
        // Ambil semua baris berdasarkan id_difabel
        $links = $this->where('id_difabel', $id_difabel)->findAll();

        // Ekstrak hanya kolom id_jenis_disabilitas menjadi array sederhana
        return array_column($links, 'id_jenis_disabilitas');
    }
}