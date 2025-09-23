<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class BankelController extends BaseController
{
    public function index()
    {
        $bankelModel = new \App\Models\BankelModel();
        $kabupatenModel = new \App\Models\KabupatenModel();

        $role = session()->get('role');
        $id_kabupaten_admin = session()->get('id_kabupaten');

        // Ambil nilai filter dari URL
        $filters = [
            'keyword'      => $this->request->getGet('keyword'),
            'tahun'        => $this->request->getGet('tahun'),
            'id_kabupaten' => $this->request->getGet('id_kabupaten')
        ];

        // Query dasar dengan join ke tabel lain
        $query = $bankelModel
            ->select('data_bankel.*, users.username as nama_admin, kabupaten.nama_kabupaten, kecamatan.nama_kecamatan, kelurahan.nama_kelurahan')
            ->join('users', 'users.id = data_bankel.id_admin_input')
            ->join('kabupaten', 'kabupaten.id = data_bankel.id_kabupaten')
            ->join('kecamatan', 'kecamatan.id = data_bankel.id_kecamatan')
            ->join('kelurahan', 'kelurahan.id = data_bankel.id_kelurahan');

        // Terapkan filter keyword jika ada
        if (!empty($filters['keyword'])) {
            $query->groupStart()
                ->like('data_bankel.nik', $filters['keyword'])
                ->orLike('data_bankel.nama_lengkap', $filters['keyword'])
                ->orLike('kecamatan.nama_kecamatan', $filters['keyword'])
                ->orLike('kelurahan.nama_kelurahan', $filters['keyword'])
                ->groupEnd();
        }

        // Terapkan filter tahun jika ada
        if (!empty($filters['tahun'])) {
            $query->where('data_bankel.tahun_penerimaan', $filters['tahun']);
        }

        // Terapkan filter wilayah berdasarkan role
        if ($role === 'superadmin') {
            if (!empty($filters['id_kabupaten'])) {
                $query->where('data_bankel.id_kabupaten', $filters['id_kabupaten']);
            }
        } else {
            // Admin biasa hanya bisa melihat data di wilayahnya
            $query->where('data_bankel.id_kabupaten', $id_kabupaten_admin);
        }

        // Ambil data dengan paginasi, 10 data per halaman
        $data_bantuan = $query->paginate(10, 'bantuan');
        $pager = $bankelModel->pager;

        // Siapkan judul halaman dinamis berdasarkan role
        $page_title = 'Data Bantuan Sembako';
        $nama_kabupaten = null;
        $nama_kabupaten_slug = null;
        if ($role === 'admin') {
            $kabupaten = $kabupatenModel->find($id_kabupaten_admin);
            if ($kabupaten) {
                $nama_kabupaten = $kabupaten['nama_kabupaten'];
                $nama_kabupaten_slug = $kabupaten['slug'];
                $page_title .= ' - Wilayah ' . $nama_kabupaten;
            }
        }

        $data = [
            'bantuan'           => $data_bantuan,
            'pager'             => $pager,
            'message'           => session()->getFlashdata('message'),
            'title'             => $page_title,
            'filters'           => $filters,
            'role'              => $role,
            'kabupaten_list'    => $kabupatenModel->findAll(),
            'nama_kabupaten'    => $nama_kabupaten,
            'nama_kabupaten_slug' => $nama_kabupaten_slug,
            'breadcrumbs'       => [
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
        $kabupatenModel = new \App\Models\KabupatenModel();

        $bantuanData = $bankelModel->find($id);
        if (!$bantuanData) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data bantuan tidak ditemukan.');
        }

        // Siapkan data untuk view edit
        $data = [
            'bantuan'        => $bantuanData,
            'kecamatan_list' => $kecamatanModel->where('id_kabupaten', $bantuanData['id_kabupaten'])->findAll(),
            'kelurahan_list' => $kelurahanModel->where('id_kecamatan', $bantuanData['id_kecamatan'])->findAll(),
            'kabupaten_list' => $kabupatenModel->findAll(),
            'role'           => session()->get('role'),
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
            'gambar'           => $dataLama['gambar'], // Gunakan file lama sebagai default
            'koordinat'        => $dataLama['koordinat'],
            'file_ktp'         => $dataLama['file_ktp'],
            'file_kk'          => $dataLama['file_kk']
        ];

        // Hanya superadmin yang bisa mengubah data kabupaten
        if (session()->get('role') === 'superadmin') {
            $data['id_kabupaten'] = $this->request->getPost('id_kabupaten');
        }

        // Proses upload file KTP jika ada file baru
        $fileKTP = $this->request->getFile('file_ktp');
        if ($fileKTP && $fileKTP->isValid() && !$fileKTP->hasMoved()) {
            $namaFileKTPBaru = $fileKTP->getRandomName();
            $fileKTP->move(FCPATH . 'uploads/pdf', $namaFileKTPBaru);
            $data['file_ktp'] = $namaFileKTPBaru;
            // Hapus file lama jika ada
            if (!empty($dataLama['file_ktp']) && file_exists(FCPATH . 'uploads/pdf/' . $dataLama['file_ktp'])) {
                unlink(FCPATH . 'uploads/pdf/' . $dataLama['file_ktp']);
            }
        }

        // Proses upload file KK jika ada file baru
        $fileKK = $this->request->getFile('file_kk');
        if ($fileKK && $fileKK->isValid() && !$fileKK->hasMoved()) {
            $namaFileKKBaru = $fileKK->getRandomName();
            $fileKK->move(FCPATH . 'uploads/pdf', $namaFileKKBaru);
            $data['file_kk'] = $namaFileKKBaru;
            // Hapus file lama jika ada
            if (!empty($dataLama['file_kk']) && file_exists(FCPATH . 'uploads/pdf/' . $dataLama['file_kk'])) {
                unlink(FCPATH . 'uploads/pdf/' . $dataLama['file_kk']);
            }
        }

        // Proses upload gambar jika ada file baru
        $gambarFile = $this->request->getFile('gambar');
        if ($gambarFile && $gambarFile->isValid() && !$gambarFile->hasMoved()) {
            $now = new \DateTime();
            $tahun = $now->format('Y');
            $bulan = $now->format('F');
            $folderPath = FCPATH . 'uploads/' . $tahun . '/' . $bulan;
            
            // Buat folder jika belum ada
            if (!is_dir($folderPath)) {
                mkdir($folderPath, 0777, true);
            }
            
            $namaGambarBaru = $gambarFile->getRandomName();
            $fullPath = $folderPath . '/' . $namaGambarBaru;
            $relativePath = $tahun . '/' . $bulan . '/' . $namaGambarBaru;
            
            // Kompresi dan simpan gambar
            \Config\Services::image()
                ->withFile($gambarFile)
                ->resize(800, 800, true, 'height')
                ->save($fullPath, 70);
            
            $data['gambar'] = $relativePath;
            
            // Ambil koordinat dari EXIF
            $exif = @exif_read_data($gambarFile->getTempName());
            if ($exif && isset($exif['GPSLatitude'], $exif['GPSLongitude'])) {
                $lat = $this->convertExifToCoordinate($exif['GPSLatitude'], $exif['GPSLatitudeRef']);
                $lon = $this->convertExifToCoordinate($exif['GPSLongitude'], $exif['GPSLongitudeRef']);
                $data['koordinat'] = $lat . ',' . $lon;
            }
            
            // Hapus gambar lama jika ada
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

        // Cari data sebelum dihapus
        $data = $bankelModel->find($id);
        if ($data) {
            $bankelModel->delete($id);
            return redirect()->to('/admin/bankel')->with('message', 'Data berhasil dihapus!');
        } else {
            return redirect()->to('/admin/bankel')->with('error', 'Data tidak ditemukan.');
        }
    }

    public function new()
    {
        $kecamatanModel = new \App\Models\KecamatanModel();
        $kabupatenModel = new \App\Models\KabupatenModel();

        $id_kabupaten_admin = session()->get('id_kabupaten');

        $data = [
            'kecamatan_list' => $kecamatanModel->where('id_kabupaten', $id_kabupaten_admin)->findAll(),
            'kabupaten_list' => $kabupatenModel->findAll(),
            'breadcrumbs'    => [
                ['title' => 'SIM-BANKEL', 'url' => '/admin/bankel'],
                ['title' => 'Tambah Data', 'url' => '']
            ]
        ];

        return view('bankel/input', $data);
    }

    public function create()
    {
        $bankelModel = new \App\Models\BankelModel();
        $session = session();
        $data = $this->request->getPost();

        // Tentukan id_kabupaten berdasarkan role
        if (session()->get('role') === 'superadmin') {
            $data['id_kabupaten'] = $this->request->getPost('id_kabupaten');
        } else {
            $data['id_kabupaten'] = $session->get('id_kabupaten');
        }

        // Tambahkan id admin yang menginput data
        $data['id_admin_input'] = $session->get('user_id');

        // Inisialisasi variabel file
        $namaGambar = null;
        $koordinat = null;
        $relativePath = null;
        $namaFileKTP = null;
        $namaFileKK = null;

        // Proses upload file KTP
        $fileKTP = $this->request->getFile('file_ktp');
        if ($fileKTP && $fileKTP->isValid() && !$fileKTP->hasMoved()) {
            $namaFileKTP = $fileKTP->getRandomName();
            $fileKTP->move(FCPATH . 'uploads/pdf', $namaFileKTP);
        }

        // Proses upload file KK
        $fileKK = $this->request->getFile('file_kk');
        if ($fileKK && $fileKK->isValid() && !$fileKK->hasMoved()) {
            $namaFileKK = $fileKK->getRandomName();
            $fileKK->move(FCPATH . 'uploads/pdf', $namaFileKK);
        }

        // Proses upload gambar jika ada
        $gambarFile = $this->request->getFile('gambar');
        if ($gambarFile && $gambarFile->isValid() && !$gambarFile->hasMoved()) {
            // Ambil koordinat dari EXIF jika ada
            $exif = @exif_read_data($gambarFile->getTempName());
            if ($exif && isset($exif['GPSLatitude'], $exif['GPSLongitude'])) {
                $lat = $this->convertExifToCoordinate($exif['GPSLatitude'], $exif['GPSLatitudeRef']);
                $lon = $this->convertExifToCoordinate($exif['GPSLongitude'], $exif['GPSLongitudeRef']);
                $koordinat = $lat . ',' . $lon;
            }

            // Buat folder berdasarkan tahun dan bulan
            $now = new \DateTime();
            $tahun = $now->format('Y');
            $bulan = $now->format('F');
            $folderPath = FCPATH . 'uploads/' . $tahun . '/' . $bulan;

            if (!is_dir($folderPath)) {
                mkdir($folderPath, 0777, true);
            }

            $namaGambar = $gambarFile->getRandomName();
            $fullPath = $folderPath . '/' . $namaGambar;
            $relativePath = $tahun . '/' . $bulan . '/' . $namaGambar;

            // Proses kompresi dan simpan gambar
            \Config\Services::image()
                ->withFile($gambarFile)
                ->resize(800, 800, true, 'height')
                ->save($fullPath, 85);
        }
        
        // Tambahkan path file ke array data untuk disimpan
        $data['gambar'] = $relativePath;
        $data['koordinat'] = $koordinat;
        $data['file_ktp'] = $namaFileKTP;
        $data['file_kk'] = $namaFileKK;

        // Simpan data ke database
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
    
    public function getKelurahanByKecamatan($id_kecamatan)
    {
        $kelurahanModel = new \App\Models\KelurahanModel();
        $kelurahanList = $kelurahanModel->where('id_kecamatan', $id_kecamatan)->findAll();
        return $this->response->setJSON($kelurahanList);
    }

    public function export()
    {
        $bankelModel = new \App\Models\BankelModel();
        $role = session()->get('role');
        $id_kabupaten_admin = session()->get('id_kabupaten');
        $data_bantuan = [];

        // Ambil data berdasarkan role
        if ($role === 'superadmin') {
            $data_bantuan = $bankelModel->getBankelData();
        } else {
            $data_bantuan = $bankelModel->getBankelData($id_kabupaten_admin);
        }

        // Buat objek Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Tulis header tabel
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'NIK');
        $sheet->setCellValue('C1', 'Nama Lengkap');
        $sheet->setCellValue('D1', 'Kabupaten/Kota');
        $sheet->setCellValue('E1', 'Kecamatan');
        $sheet->setCellValue('F1', 'Kelurahan');
        $sheet->setCellValue('G1', 'Kategori Bantuan');
        $sheet->setCellValue('H1', 'Tahun');

        // Tulis data dari database ke Excel
        $rowNumber = 2;
        foreach ($data_bantuan as $index => $item) {
            $sheet->setCellValue('A' . $rowNumber, $index + 1);
            $sheet->setCellValue('B' . $rowNumber, "'" . $item['nik']); 
            $sheet->setCellValue('C' . $rowNumber, $item['nama_lengkap']);
            $sheet->setCellValue('D' . $rowNumber, $item['nama_kabupaten']);
            $sheet->setCellValue('E' . $rowNumber, $item['nama_kecamatan']);
            $sheet->setCellValue('F' . $rowNumber, $item['nama_kelurahan']);
            $sheet->setCellValue('G' . $rowNumber, $item['kategori_bantuan']);
            $sheet->setCellValue('H' . $rowNumber, $item['tahun_penerimaan']);
            $rowNumber++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'rekap_data_bankel_' . date('Y-m-d') . '.xlsx';
        
        // Set header untuk download file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    }
    
    // Fungsi bantu untuk konversi koordinat EXIF ke desimal
    private function convertExifToCoordinate($exifCoord, $ref)
    {
        $degrees = count($exifCoord) > 0 ? $this->fracToFloat($exifCoord[0]) : 0;
        $minutes = count($exifCoord) > 1 ? $this->fracToFloat($exifCoord[1]) : 0;
        $seconds = count($exifCoord) > 2 ? $this->fracToFloat($exifCoord[2]) : 0;

        $coord = $degrees + ($minutes / 60.0) + ($seconds / 3600.0);
        return ($ref == 'S' || $ref == 'W') ? -$coord : $coord;
    }

    // Fungsi bantu untuk konversi string pecahan ke float
    private function fracToFloat($fraction)
    {
        $parts = explode('/', $fraction);
        if (count($parts) <= 0) return 0;
        if (count($parts) == 1) return (float)$parts[0];

        // Mencegah error pembagian dengan nol
        if ((float)$parts[1] == 0) {
            return 0;
        }

        return (float)$parts[0] / (float)$parts[1];
    }
    
    //--------------------------------------- GRAFIK ----------------------------------------//

    public function getChartData()
    {
        $bankelModel = new \App\Models\BankelModel();
        $role = session()->get('role');
        $id_kabupaten = false;

        if ($role === 'superadmin') {
            $id_kabupaten = $this->request->getGet('id_kabupaten') ?: false;
        } else {
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
        } else {
            $id_kabupaten = session()->get('id_kabupaten');
        }

        $chartData = $bankelModel->getChartDataByYear($id_kabupaten);
        return $this->response->setJSON($chartData);
    }
    
    //------------------------------------ IMPORT EXCEL -------------------------------------//

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

        $bankelModel = new \App\Models\BankelModel();
        $kecamatanModel = new \App\Models\KecamatanModel();
        $kelurahanModel = new \App\Models\KelurahanModel();

        $errors = [];
        $rowCount = 0;
        $successCount = 0;

        $session = session();
        $id_kabupaten_admin = $session->get('id_kabupaten');
        $id_admin_input = $session->get('user_id');
        $db = \Config\Database::connect();

        // Gunakan cache untuk mengurangi query duplikat
        $kecamatanCache = [];
        $kelurahanCache = [];

        foreach ($rows as $index => $row) {
            if ($index == 0) continue; // Lewati baris header
            $rowCount++;
            $excelRowNumber = $index + 1;

            $db->transStart();

            $nama_kecamatan = trim((string)($row[4] ?? ''));
            $nama_kelurahan = trim((string)($row[5] ?? ''));

            // Cari ID Kecamatan dari cache atau database
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

            // Cari ID Kelurahan dari cache atau database
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

            // Validasi data per baris
            if ($bankelModel->validate($rowData) === false) {
                foreach ($bankelModel->errors() as $message) {
                    $errors[$message][] = $excelRowNumber;
                }
                $db->transRollback();
                continue;
            }

            // Simpan data
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
        
        // Hapus file excel yang sudah diupload
        @unlink($filePath);

        return redirect()->to('/admin/bankel/import')->with('message', $message);
    }

    public function printAll()
    {
        $bankelModel = new \App\Models\BankelModel();
        $role = session()->get('role');
        $id_kabupaten_admin = session()->get('id_kabupaten');
        
        // Ambil data berdasarkan role untuk dicetak
        $data['all_data'] = $role === 'superadmin'
            ? $bankelModel->getBankelData()
            : $bankelModel->getBankelData($id_kabupaten_admin);

        return view('admin/print_bankel', $data);
    }
}