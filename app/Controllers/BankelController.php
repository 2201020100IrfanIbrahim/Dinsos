<?php

namespace App\Controllers;

class BankelController extends BaseController
{
    /**
     * Menampilkan halaman utama SIM-BANKEL (nantinya berisi tabel data).
     */
    public function index()
    {
        $bankelModel = new \App\Models\BankelModel();
        $kabupatenModel = new \App\Models\KabupatenModel(); // Panggil model kabupaten
        
        $role = session()->get('role');
        $id_kabupaten_admin = session()->get('id_kabupaten');
        
        $data_bantuan = [];
        $page_title = 'Manajemen Data SIM-BANKEL'; // Judul default

        if ($role === 'superadmin') {
            // Superadmin: Ambil semua data
            $data_bantuan = $bankelModel->getBankelData();
        }

        if ($role === 'admin') {
            // Admin: Ambil data dari kabupatennya saja
            $data_bantuan = $bankelModel->getBankelData($id_kabupaten_admin);
            
            // Ambil nama kabupaten untuk judul
            $kabupaten = $kabupatenModel->find($id_kabupaten_admin);
            if ($kabupaten) {
                $page_title .= ' - Wilayah ' . $kabupaten['nama_kabupaten'];
            }
        }

        $data = [
            'bantuan' => $data_bantuan,
            'message' => session()->getFlashdata('message'),
            'title'   => $page_title // Kirim judul ke view
        ];
        
        return view('bankel/SIM-BANKEL', $data); // Pastikan nama view sudah benar
    }

    public function edit($id = null)
    {
        $bankelModel = new \App\Models\BankelModel();
        $kecamatanModel = new \App\Models\KecamatanModel();
        $kelurahanModel = new \App\Models\KelurahanModel();

        // Ambil data utama yang akan diedit
        $bantuanData = $bankelModel->find($id);

        if (!$bantuanData) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data bantuan tidak ditemukan.');
        }

        // Siapkan data untuk dikirim ke view
        $data = [
            'bantuan' => $bantuanData,
            // Ambil daftar kecamatan sesuai kabupaten data tersebut
            'kecamatan_list' => $kecamatanModel->where('id_kabupaten', $bantuanData['id_kabupaten'])->findAll(),
            // Ambil daftar kelurahan sesuai kecamatan data tersebut
            'kelurahan_list' => $kelurahanModel->where('id_kecamatan', $bantuanData['id_kecamatan'])->findAll()
        ];

        return view('bankel/edit_view', $data);
    }


    public function update($id = null)
    {
        // 1. Validasi dasar: pastikan ID ada
        if (!$id) {
            return redirect()->to('/admin/bankel')->with('error', 'Permintaan tidak valid.');
        }

        $bankelModel = new \App\Models\BankelModel();

        
        // 2. Kumpulkan SEMUA data dari form yang bisa di-edit
        $data = [
            'nik'              => $this->request->getPost('nik'),
            'nama_lengkap'     => $this->request->getPost('nama_lengkap'),
            'alamat_lengkap'   => $this->request->getPost('alamat_lengkap'),
            'rt'               => $this->request->getPost('rt'),
            'rw'               => $this->request->getPost('rw'),
            'kategori_bantuan' => $this->request->getPost('kategori_bantuan'),
            'tahun_penerimaan' => $this->request->getPost('tahun_penerimaan'),
            // Tambahkan field lain dari form jika ada
        ];

        // 3. Jalankan proses update dari model
        if ($bankelModel->update($id, $data)) {
            // Jika berhasil, kembali ke halaman daftar dengan pesan sukses
            return redirect()->to('/admin/bankel')->with('message', 'Data berhasil diupdate!');
        } else {
            // Jika validasi gagal, kembali ke form edit dengan semua error
            return redirect()->back()->withInput()->with('errors', $bankelModel->errors());
        }
    }

    public function delete($id = null)
    {
        $bankelModel = new \App\Models\BankelModel();

        // Cari data berdasarkan ID, jika tidak ada maka tampilkan error
        $data = $bankelModel->find($id);
        if ($data) {
            // Hapus data dari database
            $bankelModel->delete($id);

            // Redirect kembali ke halaman utama dengan pesan sukses
            return redirect()->to('/admin/bankel')->with('message', 'Data berhasil dihapus!');
        } else {
            // Redirect kembali ke halaman utama dengan pesan error
            return redirect()->to('/admin/bankel')->with('error', 'Data tidak ditemukan.');
        }
    }
    /**
     * Menampilkan formulir untuk menambah data baru.
     */
    public function new()
    {
        // Panggil model yang baru kita buat
        $kecamatanModel = new \App\Models\KecamatanModel();

        // Ambil id_kabupaten dari admin yang sedang login
        $id_kabupaten_admin = session()->get('id_kabupaten');

        // Siapkan data untuk dikirim ke view
        $data = [
            // Ambil semua kecamatan yang id_kabupaten-nya cocok dengan milik admin
            'kecamatan_list' => $kecamatanModel->where('id_kabupaten', $id_kabupaten_admin)->findAll()
        ];

        return view('bankel/input', $data);
    }

    /**
     * Menyimpan data baru dari form ke database.
     */
    public function create()
    {
        $bankelModel = new \App\Models\BankelModel();
        $session = session();

        // Sesuaikan data yang diambil dari form
        $data = [
            'nik'              => $this->request->getPost('nik'),
            'nama_lengkap'     => $this->request->getPost('nama_lengkap'),
            'id_kecamatan'     => $this->request->getPost('id_kecamatan'),
            'id_kelurahan'     => $this->request->getPost('id_kelurahan'),
            'dusun'            => $this->request->getPost('dusun'),
            'rt'               => $this->request->getPost('rt'),
            'rw'               => $this->request->getPost('rw'),
            'alamat_lengkap'   => $this->request->getPost('alamat_lengkap'),
            'kategori_bantuan' => $this->request->getPost('kategori_bantuan'),
            'tahun_penerimaan' => $this->request->getPost('tahun_penerimaan'),
            'id_kabupaten'     => $session->get('id_kabupaten'),
            'id_admin_input'   => $session->get('user_id'),
        ];

        if ($bankelModel->save($data)) {
            return redirect()->to('/admin/bankel')->with('message', 'Data berhasil ditambahkan!');
        } else {
            return redirect()->back()->withInput()->with('errors', $bankelModel->errors());
        }
    }

    public function getKelurahanByKecamatan($id_kecamatan)
    {
        $kelurahanModel = new \App\Models\KelurahanModel();

        // Ambil data kelurahan berdasarkan id_kecamatan yang dipilih
        $kelurahanList = $kelurahanModel->where('id_kecamatan', $id_kecamatan)->findAll();

        // Kembalikan data dalam format JSON
        return $this->response->setJSON($kelurahanList);
    }
}