<?php

namespace App\Models;

use CodeIgniter\Model;

class KabupatenModel extends Model
{
    protected $table            = 'kabupaten';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['id_kabupaten', 'slug'];
}