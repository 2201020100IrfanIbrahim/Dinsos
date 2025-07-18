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
        $bankelModel = new \App\Models\BankelModel();

        // Ambil semua data dari form
        $data = [
            'nik'              => $this->request->getPost('nik'),
            'nama_lengkap'     => $this->request->getPost('nama_lengkap'),
            'alamat_lengkap'   => $this->request->getPost('alamat_lengkap'),
            'rt'               => $this->request->getPost('rt'),
            'rw'               => $this->request->getPost('rw'),
            'kategori_bantuan' => $this->request->getPost('kategori_bantuan'),
            'tahun_penerimaan' => $this->request->getPost('tahun_penerimaan'),
            // (Tambahkan field lain dari form jika ada)
        ];

        // Tangani upload gambar baru (jika ada)
        $file = $this->request->getFile('gambar');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $namaGambar = $file->getRandomName();

            // ✅ Ekstrak koordinat dari EXIF sebelum file dipindah
            $exif = @exif_read_data($file->getTempName(), 0, true);
            if (isset($exif['GPS'])) {
                $lat_ref = $exif['GPS']['GPSLatitudeRef'];
                $lon_ref = $exif['GPS']['GPSLongitudeRef'];
                $lat = $this->convertToDecimal($exif['GPS']['GPSLatitude'], $lat_ref);
                $lon = $this->convertToDecimal($exif['GPS']['GPSLongitude'], $lon_ref);
                $data['koordinat'] = "$lat,$lon";
            }

            // ✅ Hapus gambar lama (jika ada)
            $lama = $bankelModel->find($id);
            if ($lama && !empty($lama['gambar'])) {
                $pathLama = ROOTPATH . 'public/uploads/' . $lama['gambar'];
                if (file_exists($pathLama)) {
                    unlink($pathLama);
                }
            }

            // ✅ Pindahkan file gambar baru
            $file->move(ROOTPATH . 'public/uploads/', $namaGambar);
            $data['gambar'] = $namaGambar;
        }


        // =================================================================
        // BAGIAN KUNCI PERUBAHAN
        // =================================================================
        // 1. Tambahkan ID dari URL ke dalam array $data secara manual
        $data['id'] = $id;

        // 2. Gunakan metode save() yang akan otomatis mendeteksi 'id' dan melakukan UPDATE
        if ($bankelModel->save($data)) {
            // =================================================================

            return redirect()->to('/admin/bankel')->with('message', 'Data berhasil diupdate!');
        } else {
            return redirect()->back()->withInput()->with('errors', 'Terjadi kesalahan validasi.');
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

        // ==== HANDLE GAMBAR & EKSTRAK KOORDINAT ====
        $gambarFile = $this->request->getFile('gambar');
        $namaGambar = null;
        $koordinat = null;

        if ($gambarFile && $gambarFile->isValid() && !$gambarFile->hasMoved()) {
            $namaGambar = $gambarFile->getRandomName();
            $gambarFile->move(FCPATH . 'uploads', $namaGambar);

            // Ambil koordinat dari EXIF jika ada
            $exif = @exif_read_data(FCPATH . 'uploads/' . $namaGambar);
            if ($exif && isset($exif['GPSLatitude'], $exif['GPSLongitude'])) {
                $lat = $this->convertExifToCoordinate($exif['GPSLatitude'], $exif['GPSLatitudeRef']);
                $lon = $this->convertExifToCoordinate($exif['GPSLongitude'], $exif['GPSLongitudeRef']);
                $koordinat = $lat . ',' . $lon;
            }
        }

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
            'gambar'           => $namaGambar,
            'koordinat'        => $koordinat,
        ];

        if ($bankelModel->save($data)) {
            return redirect()->to('/admin/bankel')->with('message', 'Data berhasil ditambahkan!');
        } else {
            return redirect()->back()->withInput()->with('errors', $bankelModel->errors());
        }
    }

    // Fungsi bantu: konversi EXIF ke desimal
    private function convertExifToCoordinate($exifCoord, $ref)
    {
        $degrees = count($exifCoord) > 0 ? $this->fracToFloat($exifCoord[0]) : 0;
        $minutes = count($exifCoord) > 1 ? $this->fracToFloat($exifCoord[1]) : 0;
        $seconds = count($exifCoord) > 2 ? $this->fracToFloat($exifCoord[2]) : 0;

        $coord = $degrees + ($minutes / 60.0) + ($seconds / 3600.0);
        return ($ref == 'S' || $ref == 'W') ? -$coord : $coord;
    }

    private function fracToFloat($fraction)
    {
        $parts = explode('/', $fraction);
        if (count($parts) <= 0) return 0;
        if (count($parts) == 1) return (float)$parts[0];
        return (float)$parts[0] / (float)$parts[1];
    }

    private function convertToDecimal($coord, $ref)
    {
        $degrees = count($coord) > 0 ? eval('return ' . $coord[0] . ';') : 0;
        $minutes = count($coord) > 1 ? eval('return ' . $coord[1] . ';') : 0;
        $seconds = count($coord) > 2 ? eval('return ' . $coord[2] . ';') : 0;

        $decimal = $degrees + ($minutes / 60) + ($seconds / 3600);
        return ($ref == 'S' || $ref == 'W') ? -$decimal : $decimal;
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
