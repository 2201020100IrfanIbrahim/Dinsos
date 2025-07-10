<?php

namespace App\Controllers;

class DifabelkepriController extends BaseController
{
      /**
     * Menampilkan halaman utama SIM-BANKEL (nantinya berisi tabel data).
     */
    public function index()
    {
        // Untuk sementara, kita buat view sederhana
        return view('difabelkepri/SIM-DIFABELKEPRI');
    }

    /**
     * Menampilkan formulir untuk menambah data baru.
     */
    public function new()
    {
        return view('difabelkepri/input');
    }

    /**
     * Menyimpan data baru dari form ke database.
     */
    public function create()
    {
        // Logika untuk menyimpan data akan kita tambahkan di sini nanti.
        // Untuk sekarang, kita kembalikan saja ke halaman utama.
        return redirect()->to('/admin/difabelkepri')->with('message', 'Data berhasil ditambahkan!');
    }
}