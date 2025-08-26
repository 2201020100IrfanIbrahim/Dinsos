<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class BankelController extends BaseController
{
    public function index()
    {
        $bankelModel = new \App\Models\BankelModel();
        $kabupatenModel = new \App\Models\KabupatenModel(); //new

        $role = session()->get('role');
        $id_kabupaten_admin = session()->get('id_kabupaten');

        $filters = [
            'keyword'       => $this->request->getGet('keyword'),
            'tahun'         => $this->request->getGet('tahun'),
            'id_kabupaten'  => $this->request->getGet('id_kabupaten')
        ];

        $role = session()->get('role');
        $id_kabupaten_admin = session()->get('id_kabupaten');

        // --- LOGIKA BARU UNTUK PAGINATION ---

        // Siapkan query dasar dengan join
        $query = $bankelModel
            ->select('data_bankel.*, users.username as nama_admin, kabupaten.nama_kabupaten, kecamatan.nama_kecamatan, kelurahan.nama_kelurahan')
            ->join('users', 'users.id = data_bankel.id_admin_input')
            ->join('kabupaten', 'kabupaten.id = data_bankel.id_kabupaten')
            ->join('kecamatan', 'kecamatan.id = data_bankel.id_kecamatan')
            ->join('kelurahan', 'kelurahan.id = data_bankel.id_kelurahan');

        // Terapkan filter jika ada
        if (!empty($filters['keyword'])) {
            $query->groupStart()
                ->like('data_bankel.nik', $filters['keyword'])
                ->orLike('data_bankel.nama_lengkap', $filters['keyword'])
                ->orLike('kecamatan.nama_kecamatan', $filters['keyword'])
                ->orLike('kelurahan.nama_kelurahan', $filters['keyword'])
                ->groupEnd();
        }
        if (!empty($filters['tahun'])) {
            $query->where('data_bankel.tahun_penerimaan', $filters['tahun']);
        }

        // Terapkan filter wilayah untuk admin
        // $role = session()->get('role');
        // $id_kabupaten_admin = session()->get('id_kabupaten');
        // if ($role === 'admin') {
        //     $query->where('data_bankel.id_kabupaten', $id_kabupaten_admin);
        // }


        // 2. Terapkan filter wilayah yang lebih cerdas
        if ($role === 'superadmin') {
            if (!empty($filters['id_kabupaten'])) {
                $query->where('data_bankel.id_kabupaten', $filters['id_kabupaten']);
            }
        } else {
            $query->where('data_bankel.id_kabupaten', $id_kabupaten_admin);
        }


        // Ambil data menggunakan paginate, 10 data per halaman
        $data_bantuan = $query->paginate(10, 'bantuan'); // 'bantuan' adalah nama grup pager
        $pager = $bankelModel->pager;

        // --- BATAS LOGIKA BARU ---

        $page_title = 'Data Bantuan Sembako';
        $nama_kabupaten = null;
        $nama_kabupaten_slug = null;

        // Siapkan judul halaman
        if ($role === 'admin') {
            $kabupatenModel = new \App\Models\KabupatenModel();
            $kabupaten = $kabupatenModel->find($id_kabupaten_admin);
            if ($kabupaten) {
                $nama_kabupaten = $kabupaten['nama_kabupaten'];
                $nama_kabupaten_slug = $kabupaten['slug'];
                $page_title .= ' - Wilayah ' . $nama_kabupaten;
            }
        }

        $data = [
            'bantuan' => $data_bantuan,
            'pager'   => $pager,
            'message' => session()->getFlashdata('message'),
            'title'   => $page_title,
            'filters' => $filters,
            'role'    => $role, // Kirim peran pengguna
            'kabupaten_list' => $kabupatenModel->findAll(),
            'nama_kabupaten' => $nama_kabupaten, // Kirim nama kabupaten
            'nama_kabupaten_slug' => $nama_kabupaten_slug,
            'breadcrumbs' => [
                ['title' => 'Beranda', 'url' => '/dashboard'],
                ['title' => 'SIM-BANKEL', 'url' => '/admin/bankel']
            ]
        ];


        return view('bankel/SIM-BANKEL', $data);
    }

    public function edit($id = null)
    {
        $bankelModel = new \App\Models\BankelModel();
        $kecamatanModel = new \App\Models\KecamatanModel();
        $kelurahanModel = new \App\Models\KelurahanModel();
        $kabupatenModel = new \App\Models\KabupatenModel(); // Tambahkan ini

        $bantuanData = $bankelModel->find($id);
        if (!$bantuanData) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data bantuan tidak ditemukan.');
        }

        // Siapkan data untuk dikirim ke view
        $data = [
            'bantuan'        => $bantuanData,
            'kecamatan_list' => $kecamatanModel->where('id_kabupaten', $bantuanData['id_kabupaten'])->findAll(),
            'kelurahan_list' => $kelurahanModel->where('id_kecamatan', $bantuanData['id_kecamatan'])->findAll(),
            'kabupaten_list' => $kabupatenModel->findAll(), // Tambahkan ini
            'role'           => session()->get('role'),      // Tambahkan ini
            'breadcrumbs'    => [
                ['title' => 'SIM-BANKEL', 'url' => '/admin/bankel'],
                ['title' => 'Edit Data', 'url' => '']
            ]
        ];

        return view('bankel/edit_view', $data);
    }


    public function update($id = null)
    {
        $bankelModel = new \App\Models\BankelModel();
        $dataLama = $bankelModel->find($id);
        if (!$dataLama) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data bantuan tidak ditemukan.');
        }

        $data = [
            'id'               => $id,
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
            'gambar'           => $dataLama['gambar'],
            'koordinat'        => $dataLama['koordinat'],
            'file_ktp'         => $dataLama['file_ktp'],
            'file_kk'          => $dataLama['file_kk']
        ];

        // --- MODIFIKASI: Hanya superadmin yang boleh mengubah kabupaten ---
        if (session()->get('role') === 'superadmin') {
            $data['id_kabupaten'] = $this->request->getPost('id_kabupaten');
        }
        // --- BATAS MODIFIKASI ---

        // ( ... sisa kode untuk proses upload file tidak perlu diubah ... )
        // Proses upload file KTP
        $fileKTP = $this->request->getFile('file_ktp');
        if ($fileKTP && $fileKTP->isValid() && !$fileKTP->hasMoved()) {
            $namaFileKTPBaru = $fileKTP->getRandomName();
            $fileKTP->move(FCPATH . 'uploads/pdf', $namaFileKTPBaru);
            $data['file_ktp'] = $namaFileKTPBaru;
            if (!empty($dataLama['file_ktp']) && file_exists(FCPATH . 'uploads/pdf/' . $dataLama['file_ktp'])) {
                unlink(FCPATH . 'uploads/pdf/' . $dataLama['file_ktp']);
            }
        }

        // Proses upload file KK
        $fileKK = $this->request->getFile('file_kk');
        if ($fileKK && $fileKK->isValid() && !$fileKK->hasMoved()) {
            $namaFileKKBaru = $fileKK->getRandomName();
            $fileKK->move(FCPATH . 'uploads/pdf', $namaFileKKBaru);
            $data['file_kk'] = $namaFileKKBaru;
            if (!empty($dataLama['file_kk']) && file_exists(FCPATH . 'uploads/pdf/' . $dataLama['file_kk'])) {
                unlink(FCPATH . 'uploads/pdf/' . $dataLama['file_kk']);
            }
        }

        // Proses upload gambar
        $gambarFile = $this->request->getFile('gambar');
        if ($gambarFile && $gambarFile->isValid() && !$gambarFile->hasMoved()) {
            $now = new \DateTime();
            $tahun = $now->format('Y');
            $bulan = $now->format('F');
            $folderPath = FCPATH . 'uploads/' . $tahun . '/' . $bulan;
            if (!is_dir($folderPath)) {
                mkdir($folderPath, 0777, true);
            }
            $namaGambarBaru = $gambarFile->getRandomName();
            $fullPath = $folderPath . '/' . $namaGambarBaru;
            $relativePath = $tahun . '/' . $bulan . '/' . $namaGambarBaru;
            \Config\Services::image()
                ->withFile($gambarFile)
                ->resize(800, 800, true, 'height')
                ->save($fullPath, 70);
            $data['gambar'] = $relativePath;
            $exif = @exif_read_data($gambarFile->getTempName());
            if ($exif && isset($exif['GPSLatitude'], $exif['GPSLongitude'])) {
                $lat = $this->convertExifToCoordinate($exif['GPSLatitude'], $exif['GPSLatitudeRef']);
                $lon = $this->convertExifToCoordinate($exif['GPSLongitude'], $exif['GPSLongitudeRef']);
                $data['koordinat'] = $lat . ',' . $lon;
            }
            if (!empty($dataLama['gambar']) && file_exists(FCPATH . 'uploads/' . $dataLama['gambar'])) {
                unlink(FCPATH . 'uploads/' . $dataLama['gambar']);
            }
        }

        // Simpan data ke database
        if ($bankelModel->save($data)) {
            return redirect()->to('/admin/bankel')->with('message', 'Data berhasil diupdate!');
        } else {
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
        $kecamatanModel = new \App\Models\KecamatanModel();
        $kabupatenModel = new \App\Models\KabupatenModel(); // Tambahkan ini

        $id_kabupaten_admin = session()->get('id_kabupaten');

        $data = [
            'kecamatan_list' => $kecamatanModel->where('id_kabupaten', $id_kabupaten_admin)->findAll(),
            'kabupaten_list' => $kabupatenModel->findAll(),
            'breadcrumbs' => [
                ['title' => 'SIM-BANKEL', 'url' => '/admin/bankel'],
                ['title' => 'Tambah Data', 'url' => ''] // URL kosong karena ini halaman aktif
            ]
        ];

        return view('bankel/input', $data);
    }

    public function create()
    {
        $bankelModel = new \App\Models\BankelModel();
        $session = session();


        $data = $this->request->getPost();

        // Cek peran pengguna
        if (session()->get('role') === 'superadmin') {
            // Jika superadmin, id_kabupaten diambil dari form
            $data['id_kabupaten'] = $this->request->getPost('id_kabupaten');
        } else {
            // Jika admin, id_kabupaten diambil dari sesi
            $data['id_kabupaten'] = $session->get('id_kabupaten');
        }

        // Tambahkan id admin yang menginput
        $data['id_admin_input'] = $session->get('user_id');

        // Inisialisasi variabel dengan nilai default (null)
        $namaGambar = null;
        $koordinat = null;
        $relativePath = null;
        $namaFileKTP = null;
        $namaFileKK = null;

        // Proses upload file KTP
        $fileKTP = $this->request->getFile('file_ktp');
        if ($fileKTP && $fileKTP->isValid() && !$fileKTP->hasMoved()) {
            $namaFileKTP = $fileKTP->getRandomName();
            $fileKTP->move(FCPATH . 'uploads/pdf', $namaFileKTP); // Simpan di public/uploads/pdf
        }

        // Proses upload file KK
        $fileKK = $this->request->getFile('file_kk');
        if ($fileKK && $fileKK->isValid() && !$fileKK->hasMoved()) {
            $namaFileKK = $fileKK->getRandomName();
            $fileKK->move(FCPATH . 'uploads/pdf', $namaFileKK); // Simpan di public/uploads/pdf
        }

        // Cek dan proses file gambar HANYA JIKA ada yang di-upload
        $gambarFile = $this->request->getFile('gambar');
        if ($gambarFile && $gambarFile->isValid() && !$gambarFile->hasMoved()) {
            // Ambil koordinat dari EXIF jika ada (dari file temporer asli)
            $exif = @exif_read_data($gambarFile->getTempName());
            if ($exif && isset($exif['GPSLatitude'], $exif['GPSLongitude'])) {
                $lat = $this->convertExifToCoordinate($exif['GPSLatitude'], $exif['GPSLatitudeRef']);
                $lon = $this->convertExifToCoordinate($exif['GPSLongitude'], $exif['GPSLongitudeRef']);
                $koordinat = $lat . ',' . $lon;
            }


            // ==== Generate folder berdasarkan waktu ====
            $now = new \DateTime();
            $tahun = $now->format('Y');
            $bulan = $now->format('F');
            $tanggal = $now->format('dmY');

            $folderPath = FCPATH . 'uploads/' . $tahun . '/' . $bulan;

            // Buat folder jika belum ada
            if (!is_dir($folderPath)) {
                mkdir($folderPath, 0777, true);
            }

            // Ambil ekstensi asli file
            $ext = $gambarFile->getExtension();

            // Cari nama file yang belum ada
            $index = 0;
            do {
                $namaGambar = $tanggal . '_' . $index . '.' . $ext;
                $fullPath = $folderPath . '/' . $namaGambar;
                $index++;
            } while (file_exists($fullPath));

            // Simpan path relatif untuk database
            $relativePath = $tahun . '/' . $bulan . '/' . $namaGambar;

            // Proses kompresi dan resize
            \Config\Services::image()
                ->withFile($gambarFile)
                ->resize(800, 800, true, 'height')
                ->save($fullPath, 85);
        }

        // // 3. Kumpulkan semua data untuk disimpan ke database
        // $data = [
        //     'nik'              => $this->request->getPost('nik'),
        //     'nama_lengkap'     => $this->request->getPost('nama_lengkap'),
        //     'id_kecamatan'     => $this->request->getPost('id_kecamatan'),
        //     'id_kelurahan'     => $this->request->getPost('id_kelurahan'),
        //     'dusun'            => $this->request->getPost('dusun'),
        //     'rt'               => $this->request->getPost('rt'),
        //     'rw'               => $this->request->getPost('rw'),
        //     'alamat_lengkap'   => $this->request->getPost('alamat_lengkap'),
        //     'kategori_bantuan' => $this->request->getPost('kategori_bantuan'),
        //     'tahun_penerimaan' => $this->request->getPost('tahun_penerimaan'),
        //     'id_kabupaten'     => $session->get('id_kabupaten'),
        //     'id_admin_input'   => $session->get('user_id'),

        //     'gambar'           => $relativePath,
        //     'koordinat'        => $koordinat,
        //     'file_ktp' => $namaFileKTP, // Masukkan nama file KTP
        //     'file_kk' => $namaFileKK,   // Masukkan nama file KK

        // ];

        // 4. Simpan ke database
        if ($bankelModel->save($data)) {
            return redirect()->to('/admin/bankel')->with('message', 'Data berhasil ditambahkan!');
        } else {
            return redirect()->back()->withInput()->with('errors', $bankelModel->errors());
        }
    }

    public function getKecamatanByKabupaten($id_kabupaten)
    {
        $kecamatanModel = new \App\Models\KecamatanModel();
        $kecamatanList = $kecamatanModel->where('id_kabupaten', $id_kabupaten)->findAll();
        return $this->response->setJSON($kecamatanList);
    }

    public function export()
    {
        $bankelModel = new \App\Models\BankelModel();

        $role = session()->get('role');
        $id_kabupaten_admin = session()->get('id_kabupaten');

        $data_bantuan = [];

        // Ambil data sesuai peran (sama seperti di method index)
        if ($role === 'superadmin') {
            $data_bantuan = $bankelModel->getBankelData();
        } else {
            $data_bantuan = $bankelModel->getBankelData($id_kabupaten_admin);
        }

        // Buat objek Spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Tulis header tabel
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'NIK');
        $sheet->setCellValue('C1', 'Nama Lengkap');
        $sheet->setCellValue('D1', 'Kabupaten/Kota');
        $sheet->setCellValue('E1', 'Kecamatan');
        $sheet->setCellValue('F1', 'Kelurahan'); // 🔹 Tambahkan kolom Kelurahan
        $sheet->setCellValue('G1', 'Kategori Bantuan');
        $sheet->setCellValue('H1', 'Tahun');

        // Tulis data dari database
        $rowNumber = 2;
        foreach ($data_bantuan as $index => $item) {
            $sheet->setCellValue('A' . $rowNumber, $index + 1);
            $sheet->setCellValue('B' . $rowNumber, "'" . $item['nik']); // Tambahkan ' supaya format NIK tetap teks
            $sheet->setCellValue('C' . $rowNumber, $item['nama_lengkap']);
            $sheet->setCellValue('D' . $rowNumber, $item['nama_kabupaten']);
            $sheet->setCellValue('E' . $rowNumber, $item['nama_kecamatan']);
            $sheet->setCellValue('F' . $rowNumber, $item['nama_kelurahan']); // 🔹 Isi kolom Kelurahan
            $sheet->setCellValue('G' . $rowNumber, $item['kategori_bantuan']);
            $sheet->setCellValue('H' . $rowNumber, $item['tahun_penerimaan']);
            // ❌ Hapus kolom status_penerimaan
            $rowNumber++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'rekap_data_bankel_' . date('Y-m-d') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
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

        // --- TAMBAHKAN PENGECEKAN INI ---
        // Memeriksa apakah pembaginya (denominator) adalah nol
        if ((float)$parts[1] == 0) {
            return 0; // Mengembalikan 0 untuk menghindari error
        }
        // --- BATAS PENGECEKAN ---

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

    ///////////////////////////////////////////////////////////////////////////////////////////
    //--------------------------------------- GRAFIK ----------------------------------------//
    ///////////////////////////////////////////////////////////////////////////////////////////


    ///////////////////////////////////////////////////////////////////////////////////////////
    //--------------------------------------- GRAFIK ----------------------------------------//
    ///////////////////////////////////////////////////////////////////////////////////////////

    public function getChartData()
    {
        $bankelModel = new \App\Models\BankelModel();
        $role = session()->get('role');
        $id_kabupaten = false;

        if ($role === 'superadmin') {
            $id_kabupaten = $this->request->getGet('id_kabupaten') ?: false;
        } else { // Jika role adalah 'admin'
            $id_kabupaten = session()->get('id_kabupaten');
        }

        $chartData = $bankelModel->getChartDataByKecamatan($id_kabupaten);
        return $this->response->setJSON($chartData);
    }

    public function getChartDataByYear()
    {
        $bankelModel = new \App\Models\BankelModel();
        $role = session()->get('role');
        $id_kabupaten = false;

        if ($role === 'superadmin') {
            $id_kabupaten = $this->request->getGet('id_kabupaten') ?: false;
        } else { // Jika role adalah 'admin'
            $id_kabupaten = session()->get('id_kabupaten');
        }

        $chartData = $bankelModel->getChartDataByYear($id_kabupaten);
        return $this->response->setJSON($chartData);
    }

    ///////////////////////////////////////////////////////////////////////////////////////////
    //------------------------------------ IMPORT EXCEL -------------------------------------//
    ///////////////////////////////////////////////////////////////////////////////////////////
    public function import()
    {
        $data = [
            'title' => 'Import Data Bantuan dari Excel',
            'breadcrumbs' => [
                ['title' => 'SIM-BANKEL', 'url' => '/admin/bankel'],
                ['title' => 'Import Data', 'url' => '']
            ]
        ];
        return view('bankel/import_view', $data);
    }

    // Method untuk memproses file Excel yang diupload
    public function processImport()
    {
        $file = $this->request->getFile('excel_file');

        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return redirect()->to('/admin/bankel/import')->with('error', 'Gagal mengupload file atau file tidak valid.');
        }

        $newName = $file->getRandomName();
        $file->move(WRITEPATH . 'uploads', $newName);
        $filePath = WRITEPATH . 'uploads/' . $newName;

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
        $rows = $spreadsheet->getActiveSheet()->toArray();

        $bankelModel     = new \App\Models\BankelModel();
        $kecamatanModel  = new \App\Models\KecamatanModel();
        $kelurahanModel  = new \App\Models\KelurahanModel();

        $errors = [];
        $rowCount = 0;
        $successCount = 0;

        $session = session();
        $id_kabupaten_admin = $session->get('id_kabupaten');
        $id_admin_input     = $session->get('user_id');

        $db = \Config\Database::connect();

        // Cache
        $kecamatanCache = [];
        $kelurahanCache = [];

        foreach ($rows as $index => $row) {
            if ($index == 0) continue; // skip header
            $rowCount++;
            $excelRowNumber = $index + 1;

            $db->transStart();

            $nama_kecamatan = trim((string)($row[4] ?? ''));
            $nama_kelurahan = trim((string)($row[5] ?? ''));

            // --- Cari ID Kecamatan (pakai cache)
            if (!isset($kecamatanCache[$nama_kecamatan])) {
                $kec = $kecamatanModel
                    ->where('nama_kecamatan', $nama_kecamatan)
                    ->where('id_kabupaten', $id_kabupaten_admin)
                    ->first();
                $kecamatanCache[$nama_kecamatan] = $kec['id'] ?? null;
            }
            $id_kecamatan = $kecamatanCache[$nama_kecamatan];

            if (!$id_kecamatan) {
                $errors["Kecamatan '$nama_kecamatan' tidak ditemukan"][] = $excelRowNumber;
                $db->transRollback();
                continue;
            }

            // --- Cari ID Kelurahan (pakai cache)
            if (!isset($kelurahanCache[$nama_kelurahan])) {
                $kel = $kelurahanModel
                    ->where('nama_kelurahan', $nama_kelurahan)
                    ->where('id_kecamatan', $id_kecamatan)
                    ->first();
                $kelurahanCache[$nama_kelurahan] = $kel['id'] ?? null;
            }
            $id_kelurahan = $kelurahanCache[$nama_kelurahan];

            if (!$id_kelurahan) {
                $errors["Kelurahan '$nama_kelurahan' tidak ditemukan di kec. '$nama_kecamatan'"][] = $excelRowNumber;
                $db->transRollback();
                continue;
            }

            $rowData = [
                'nik'              => preg_replace('/[^0-9]/', '', $row[1]),
                'nama_lengkap'     => $row[2],
                'id_kabupaten'     => $id_kabupaten_admin,
                'id_kecamatan'     => $id_kecamatan,
                'id_kelurahan'     => $id_kelurahan,
                'kategori_bantuan' => $row[6],
                'tahun_penerimaan' => $row[7],
                'id_admin_input'   => $id_admin_input,
            ];

            // --- Validasi baris
            if ($bankelModel->validate($rowData) === false) {
                foreach ($bankelModel->errors() as $message) {
                    $errors[$message][] = $excelRowNumber;
                }
                $db->transRollback();
                continue;
            }

            // --- Simpan
            $bankelModel->save($rowData);

            if ($db->transComplete()) {
                $successCount++;
            } else {
                $errors["Gagal menyimpan ke database"][] = $excelRowNumber;
            }
        }

        $failCount = $rowCount - $successCount;
        $message = "Proses import selesai. <strong>Berhasil: $successCount data.</strong>";
        if ($failCount > 0) {
            $session->setFlashdata('fail_count', $failCount);
            $session->setFlashdata('errors_list', $errors);
        }

        @unlink($filePath);

        return redirect()->to('/admin/bankel/import')->with('message', $message);
    }


    public function printAll()
    {
        $bankelModel = new \App\Models\BankelModel();
        $role = session()->get('role');
        $id_kabupaten_admin = session()->get('id_kabupaten');
        $data['all_data'] = $role === 'superadmin'
            ? $bankelModel->getBankelData()
            : $bankelModel->getBankelData($id_kabupaten_admin);

        return view('admin/print_bankel', $data); // Buat view khusus cetak
    }
}
