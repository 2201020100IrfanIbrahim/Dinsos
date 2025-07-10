<?php

namespace App\Controllers;

class MonevkuepController extends BaseController
{
       /**
     * Menampilkan halaman utama SIM-BANKEL (nantinya berisi tabel data).
     */
    public function index()
    {
        // Untuk sementara, kita buat view sederhana
        return view('monevkueb/SIM-MONEVKUEB');
    }

    /**
     * Menampilkan formulir untuk menambah data baru.
     */
    public function new()
    {
        return view('monevkueb/input');
    }

    /**
     * Menyimpan data baru dari form ke database.
     */
    public function create()
    {
        // Logika untuk menyimpan data akan kita tambahkan di sini nanti.
        // Untuk sekarang, kita kembalikan saja ke halaman utama.
        return redirect()->to('/admin/monevkueb')->with('message', 'Data berhasil ditambahkan!');
    }
}