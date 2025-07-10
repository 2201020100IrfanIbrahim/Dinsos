<?php

namespace App\Models;

use CodeIgniter\Model;

class KelurahanModel extends Model
{
    protected $table            = 'kelurahan';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['id_kecamatan', 'nama_kelurahan'];
}