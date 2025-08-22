<?php

namespace App\Models;

use CodeIgniter\Model;

class MonevkuepModel extends Model
{
    protected $table            = 'monevkuep_penerima';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    // Kolom yang boleh diisi massal
    protected $allowedFields    = [
        'nik',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        // 'usia' adalah generated column di DB -> tidak dimasukkan ke allowedFields
        'id_kabupaten',
        'id_kecamatan',
        'id_kelurahan',
        'dusun',
        'alamat_lengkap',
        'dtks',
        'sktm',
        'rab_nominal',
        'agama',
        'pendidikan',
        'jenis_usaha',
        'jenis_pekerjaan',
        'id_admin_input',
    ];

    // Timestamps
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validasi
    protected $validationRules = [
        'id'             => 'permit_empty|is_natural_no_zero',
        'nik'            => 'required|numeric|exact_length[16]|is_unique[monevkuep_penerima.nik,id,{id}]',
        'nama_lengkap'   => 'required|min_length[3]',
        'jenis_kelamin'  => 'required|in_list[Laki-laki,Perempuan]',
        'tanggal_lahir'  => 'permit_empty|valid_date[Y-m-d]',
        'id_kabupaten'   => 'required|integer',
        'id_kecamatan'   => 'required|integer',
        'id_kelurahan'   => 'required|integer',
        'dtks'           => 'permit_empty|in_list[Ya,Tidak]',
        'sktm'           => 'permit_empty|in_list[Ada,Tidak Ada]',
        'rab_nominal'    => 'permit_empty|decimal',
        'id_admin_input' => 'permit_empty|integer',
        'agama'           => 'permit_empty|string|max_length[50]',
        'pendidikan'      => 'permit_empty|string|max_length[50]',
        'jenis_usaha'     => 'permit_empty|string|max_length[50]',
        'jenis_pekerjaan' => 'permit_empty|string|max_length[50]',
    ];

    protected $validationMessages = [
        'nik' => [
            'required'     => 'NIK wajib diisi',
            'numeric'      => 'NIK harus angka',
            'exact_length' => 'NIK harus 16 digit',
            'is_unique'    => 'NIK ini sudah terdaftar pada MONEVKUEP'
        ],
        'nama_lengkap' => [
            'required' => 'Nama lengkap wajib diisi'
        ],
        'jenis_kelamin' => [
            'required' => 'Jenis kelamin wajib diisi'
        ]
    ];

    /**
     * Ambil data MONEVKUEP + join wilayah & referensi untuk kebutuhan tabel/list.
     * $filters opsional:
     *   - keyword : cari di nik/nama/kecamatan/kelurahan/jenis usaha/jenis pekerjaan
     *   - dtks    : Ya/Tidak
     *   - sktm    : Ada/Tidak Ada
     *   - jk      : Laki-laki/Perempuan
     */
    public function getMonevkuepData($id_kabupaten = false, $filters = [])
    {
        $builder = $this->db->table($this->table);
        $builder->select("
            {$this->table}.*,
            users.username AS nama_admin,
            kabupaten.nama_kabupaten,
            kecamatan.nama_kecamatan,
            kelurahan.nama_kelurahan,
            
        ");

        // Join referensi utama (wilayah & admin)
        $builder->join('users', 'users.id = ' . $this->table . '.id_admin_input');
        $builder->join('kabupaten', 'kabupaten.id = ' . $this->table . '.id_kabupaten');
        $builder->join('kecamatan', 'kecamatan.id = ' . $this->table . '.id_kecamatan');
        $builder->join('kelurahan', 'kelurahan.id = ' . $this->table . '.id_kelurahan');

        if ($id_kabupaten !== false) {
            $builder->where($this->table . '.id_kabupaten', $id_kabupaten);
        }

        // Filter keyword
        if (!empty($filters['keyword'])) {
            $kw = $filters['keyword'];
            $builder->groupStart()
                ->like($this->table . '.nik', $kw)
                ->orLike($this->table . '.nama_lengkap', $kw)
                ->orLike('kecamatan.nama_kecamatan', $kw)
                ->orLike('kelurahan.nama_kelurahan', $kw)
            ->groupEnd();
        }

        // Filter DTKS/SKTM/Jenis Kelamin (opsional)
        if (!empty($filters['dtks'])) {
            $builder->where($this->table . '.dtks', $filters['dtks']);
        }
        if (!empty($filters['sktm'])) {
            $builder->where($this->table . '.sktm', $filters['sktm']);
        }
        if (!empty($filters['jk'])) {
            $builder->where($this->table . '.jenis_kelamin', $filters['jk']);
        }

        $builder->orderBy($this->table . '.created_at', 'DESC');

        return $builder->get()->getResultArray();
    }

    /**
     * Data untuk chart jumlah penerima per kecamatan (mirip BankelModel).
     */
    public function getChartDataByKecamatan($id_kabupaten = false)
    {
        $builder = $this->db->table($this->table);
        $builder->select('kecamatan.nama_kecamatan, COUNT(' . $this->table . '.id) as jumlah');
        $builder->join('kecamatan', 'kecamatan.id = ' . $this->table . '.id_kecamatan');

        if ($id_kabupaten !== false) {
            $builder->where($this->table . '.id_kabupaten', $id_kabupaten);
        }

        $builder->groupBy('kecamatan.nama_kecamatan');
        $builder->orderBy('jumlah', 'DESC');

        return $builder->get()->getResultArray();
    }

    ///////////////////////////////////////////////////////////////////////////////////////////
    //-------------------------------- VISUALIASI DATA NEW ----------------------------------//
    ///////////////////////////////////////////////////////////////////////////////////////////

    // 1. Analisis per Tahun
    public function getChartDataByYear($id_kabupaten = false)
    {
        $builder = $this->db->table($this->table);
        $builder->select('YEAR(created_at) as tahun, COUNT(id) as jumlah');
        if ($id_kabupaten !== false) {
            $builder->where('id_kabupaten', $id_kabupaten);
        }
        $builder->groupBy('tahun');
        $builder->orderBy('tahun', 'ASC');
        return $builder->get()->getResultArray();
    }

    // 3. Analisis per Jenis Kelamin
    public function getChartDataByGender($id_kabupaten = false)
    {
        $builder = $this->db->table($this->table);
        $builder->select('jenis_kelamin, COUNT(id) as jumlah');
        if ($id_kabupaten !== false) {
            $builder->where('id_kabupaten', $id_kabupaten);
        }
        $builder->groupBy('jenis_kelamin');
        return $builder->get()->getResultArray();
    }

    // 4. Analisis DTKS
    public function getChartDataByDTKS($id_kabupaten = false)
    {
        $builder = $this->db->table($this->table);
        $builder->select("
            CASE 
                WHEN dtks IS NULL THEN 'Tidak Diisi'
                WHEN dtks = '' THEN 'Diedit Kosong'
                ELSE dtks 
            END as dtks, 
            COUNT(id) as jumlah
        ");
        
        if ($id_kabupaten !== false) {
            $builder->where('id_kabupaten', $id_kabupaten);
        }
        
        $builder->groupBy('dtks');
        return $builder->get()->getResultArray();
    }


    // 5. Analisis Agama
    public function getChartDataByAgama($id_kabupaten = false)
    {
        $builder = $this->db->table($this->table);
        $builder->select('agama, COUNT(id) as jumlah');
        if ($id_kabupaten !== false) {
            $builder->where('id_kabupaten', $id_kabupaten);
        }
        $builder->groupBy('agama');
        return $builder->get()->getResultArray();
    }

    // 6. Analisis Pendidikan
    public function getChartDataByPendidikan($id_kabupaten = false)
    {
        $builder = $this->db->table($this->table);
        $builder->select('pendidikan, COUNT(id) as jumlah');
        if ($id_kabupaten !== false) {
            $builder->where('id_kabupaten', $id_kabupaten);
        }
        $builder->groupBy('pendidikan');
        return $builder->get()->getResultArray();
    }

    // 7. Analisis Jenis Usaha
    public function getChartDataByJenisUsaha($id_kabupaten = false)
    {
        $builder = $this->db->table($this->table);
        $builder->select('jenis_usaha, COUNT(id) as jumlah');
        if ($id_kabupaten !== false) {
            $builder->where('id_kabupaten', $id_kabupaten);
        }
        $builder->groupBy('jenis_usaha');
        return $builder->get()->getResultArray();
    }

}
