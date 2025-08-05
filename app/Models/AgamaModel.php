<?php

namespace App\Models;

use CodeIgniter\Model;

class AgamaModel extends Model
{
    protected $table            = 'ref_agama';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['nama_agama'];
}
