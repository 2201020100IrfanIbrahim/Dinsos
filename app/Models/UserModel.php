<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
        protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;

    // Sesuaikan dengan kolom di tabel users Anda
    protected $allowedFields    = ['username', 'password', 'role', 'id_kabupaten'];

    // Timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Callbacks untuk hashing password otomatis
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    // Fungsi ini akan dipanggil sebelum insert atau update
    protected function hashPassword(array $data)
    {
        // Hanya hash password jika field 'password' ada di data yang dikirim
        if (!isset($data['data']['password'])) {
            return $data;
        }

        // Hash password menggunakan Bcrypt
        $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        return $data;
    }

    // Aturan validasi (penting untuk update)
    protected $validationRules = [
        'id'       => 'permit_empty|is_natural_no_zero',
        'username' => 'required|alpha_numeric_space|min_length[3]|is_unique[users.username,id,{id}]',
        'password' => 'permit_empty|min_length[8]', // Boleh kosong saat update
    ];

    protected $validationMessages = [
        'username' => [
            'required'  => 'Username wajib diisi.',
            'is_unique' => 'Username ini sudah digunakan. Silakan pilih yang lain.'
        ],
        'password' => [
            'min_length' => 'Password baru minimal harus 8 karakter.'
        ]
    ];

    public function getUserByUsername(string $username)
    {
        return $this->where('username', $username)->first();
    }
}