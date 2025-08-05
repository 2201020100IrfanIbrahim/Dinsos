<?php

namespace App\Models;

use CodeIgniter\Model;

class PekerjaanModel extends Model
{
    protected $table            = 'ref_jenis_pekerjaan';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['nama_jenis_pekerjaan'];
}
