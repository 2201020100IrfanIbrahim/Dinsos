<?php

use App\Models\KabupatenModel;

function get_nama_kabupaten()
{
    $session = session();
    if ($session->get('role') === 'admin') {
        $kabupatenModel = new \App\Models\KabupatenModel();
        $id_kabupaten = $session->get('id_kabupaten');
        $kabupaten = $kabupatenModel->find($id_kabupaten);

        return $kabupaten ? esc($kabupaten['nama_kabupaten']) : 'Wilayah Tidak Ditemukan';
    }
    return 'Semua Wilayah'; // Teks untuk superadmin
}