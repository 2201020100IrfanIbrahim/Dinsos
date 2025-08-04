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
        'id_agama',
        'id_pendidikan',
        'id_jenis_usaha',
        'id_jenis_pekerjaan',
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
        'id_agama'           => 'permit_empty|integer',
        'id_pendidikan'      => 'permit_empty|integer',
        'id_jenis_usaha'     => 'permit_empty|integer',
        'id_jenis_pekerjaan' => 'permit_empty|integer',
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
            ref_agama.nama_agama,
            ref_pendidikan.nama_pendidikan,
            ref_jenis_usaha.nama_jenis_usaha,
            ref_jenis_pekerjaan.nama_jenis_pekerjaan
        ");

        // Join referensi utama (wilayah & admin)
        $builder->join('users', 'users.id = ' . $this->table . '.id_admin_input');
        $builder->join('kabupaten', 'kabupaten.id = ' . $this->table . '.id_kabupaten');
        $builder->join('kecamatan', 'kecamatan.id = ' . $this->table . '.id_kecamatan');
        $builder->join('kelurahan', 'kelurahan.id = ' . $this->table . '.id_kelurahan');

        // Join master (left join karena opsional)
        $builder->join('ref_agama', 'ref_agama.id = ' . $this->table . '.id_agama', 'left');
        $builder->join('ref_pendidikan', 'ref_pendidikan.id = ' . $this->table . '.id_pendidikan', 'left');
        $builder->join('ref_jenis_usaha', 'ref_jenis_usaha.id = ' . $this->table . '.id_jenis_usaha', 'left');
        $builder->join('ref_jenis_pekerjaan', 'ref_jenis_pekerjaan.id = ' . $this->table . '.id_jenis_pekerjaan', 'left');

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
                ->orLike('ref_jenis_usaha.nama_jenis_usaha', $kw)
                ->orLike('ref_jenis_pekerjaan.nama_jenis_pekerjaan', $kw)
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
}
