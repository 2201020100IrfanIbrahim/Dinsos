<?php

namespace App\Models;

use CodeIgniter\Model;

class UsahaModel extends Model
{
    protected $table            = 'ref_jenis_usaha';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['nama_jenis_usaha'];
}
