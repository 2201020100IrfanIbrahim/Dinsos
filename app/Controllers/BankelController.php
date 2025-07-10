<?php

namespace App\Controllers;

class BankelController extends BaseController
{
    /**
     * Menampilkan halaman utama SIM-BANKEL (nantinya berisi tabel data).
     */
    public function index()
    {
        // Untuk sementara, kita buat view sederhana
        return view('bankel/SIM-BANKEL');
    }

    /**
     * Menampilkan formulir untuk menambah data baru.
     */
    public function new()
    {
        return view('bankel/input');
    }

    /**
     * Menyimpan data baru dari form ke database.
     */
    public function create()
    {
        // Logika untuk menyimpan data akan kita tambahkan di sini nanti.
        // Untuk sekarang, kita kembalikan saja ke halaman utama.
        return redirect()->to('/admin/bankel')->with('message', 'Data berhasil ditambahkan!');
    }
}