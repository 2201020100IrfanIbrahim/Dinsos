<?php

namespace App\Controllers;

use App\Models\JenisDisabilitasModel;

class JenisDisabilitasController extends BaseController
{
    // Memastikan hanya user yang sudah login yang bisa akses
    public function __construct()
    {
        if (!session()->get('isLoggedIn')) {
            // Jika belum login, paksa keluar (bisa disesuaikan)
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    // Menampilkan tabel daftar jenis disabilitas
    public function index()
    {
        $model = new JenisDisabilitasModel();
    // Ambil semua data dan urutkan berdasarkan golongan
        $all_jenis = $model->orderBy('golongan', 'ASC')->orderBy('nama_jenis', 'ASC')->findAll();

        // Siapkan array kosong untuk pengelompokan
        $grouped_jenis = [];
        foreach ($all_jenis as $item) {
            // Gunakan 'golongan' sebagai kunci array
            $grouped_jenis[$item['golongan']][] = $item;
        }

        $data = [
            'title' => 'Manajemen Referensi Jenis Disabilitas',
            'grouped_jenis' => $grouped_jenis, // Kirim data yang sudah dikelompokkan
            'message' => session()->getFlashdata('message'),
            'breadcrumbs' => [
                ['title' => 'Beranda', 'url' => '/dashboard'],
                ['title' => 'Kelola Jenis Disabilitas', 'url' => '']
            ]
        ];
        return view('jenis_disabilitas/index', $data);
    }

    // Menampilkan form tambah baru
    public function new()
    {
        $data = [
            'title' => 'Tambah Jenis Disabilitas Baru',
            'breadcrumbs' => [
                ['title' => 'Kelola Jenis Disabilitas', 'url' => '/admin/jenis-disabilitas'],
                ['title' => 'Tambah Baru', 'url' => '']
            ]
        ];
        return view('jenis_disabilitas/new', $data);
    }

    // Menyimpan data baru
    public function create()
    {
        $model = new JenisDisabilitasModel();
        $data = [
            'nama_jenis' => $this->request->getPost('nama_jenis'),
            'golongan'   => $this->request->getPost('golongan')
        ];

        if ($model->save($data) === false) {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        return redirect()->to('/admin/jenis-disabilitas')->with('message', 'Jenis disabilitas baru berhasil ditambahkan.');
    }
    
    // Menampilkan form edit
    public function edit($id = null)
    {
        $model = new JenisDisabilitasModel();
        $jenis = $model->find($id);

        $data = [
            'title' => 'Edit Jenis Disabilitas',
            'jenis' => $jenis,
            'breadcrumbs' => [
                ['title' => 'Kelola Jenis Disabilitas', 'url' => '/admin/jenis-disabilitas'],
                ['title' => 'Edit', 'url' => '']
            ]
        ];
        return view('jenis_disabilitas/edit', $data);
    }

    // Memproses update data
    public function update($id = null)
    {
        $model = new JenisDisabilitasModel();
        $data = [
            'nama_jenis' => $this->request->getPost('nama_jenis'),
            'golongan'   => $this->request->getPost('golongan')
        ];
        $data['id'] = $id; // Penting untuk validasi is_unique

        if ($model->save($data) === false) {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }
        
        return redirect()->to('/admin/jenis-disabilitas')->with('message', 'Data berhasil diupdate.');
    }

    // Menghapus data
    public function delete($id = null)
    {
        $model = new JenisDisabilitasModel();
        
        if ($model->delete($id)) {
            return redirect()->to('/admin/jenis-disabilitas')->with('message', 'Data berhasil dihapus.');
        } else {
            return redirect()->to('/admin/jenis-disabilitas')->with('error', 'Gagal menghapus data.');
        }
    }
}