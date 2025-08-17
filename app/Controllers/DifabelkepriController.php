<?php

namespace App\Controllers;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Panggil semua model yang dibutuhkan
use App\Models\DifabelModel;
use App\Models\JenisDisabilitasModel;
use App\Models\LinkDifabelJenisModel;
use App\Models\KecamatanModel;
use App\Models\KelurahanModel;

class DifabelkepriController extends BaseController
{
    public function index()
    {
        $difabelModel = new \App\Models\DifabelModel();
        $kabupatenModel = new \App\Models\KabupatenModel();

        $filters = [
            'keyword'  => $this->request->getGet('keyword'),
            'golongan' => $this->request->getGet('golongan'),
            'id_kabupaten'  => $this->request->getGet('id_kabupaten')
        ];

        $role = session()->get('role');
        $id_kabupaten_admin = session()->get('id_kabupaten');
        
        // Siapkan query dasar
        $query = $difabelModel
            // UBAH BAGIAN SELECT INI
            ->select("data_difabel.*, kabupaten.nama_kabupaten, kecamatan.nama_kecamatan, kelurahan.nama_kelurahan, GROUP_CONCAT(ref_jenis_disabilitas.nama_jenis SEPARATOR ', ') as jenis_disabilitas_list")
            ->join('kabupaten', 'kabupaten.id = data_difabel.id_kabupaten', 'left')
            ->join('kecamatan', 'kecamatan.id = data_difabel.id_kecamatan', 'left')
            ->join('kelurahan', 'kelurahan.id = data_difabel.id_kelurahan', 'left')
            ->join('link_difabel_jenis', 'link_difabel_jenis.id_difabel = data_difabel.id', 'left')
            ->join('ref_jenis_disabilitas', 'ref_jenis_disabilitas.id = link_difabel_jenis.id_jenis_disabilitas', 'left');

        // Terapkan filter... (logika filter tetap sama)
        if (!empty($filters['keyword'])) {
            $query->groupStart()
                ->like('data_difabel.nik', $filters['keyword'])
                ->orLike('data_difabel.nama_lengkap', $filters['keyword'])
                ->groupEnd();
        }
        if (!empty($filters['golongan'])) {
            $query->where('data_difabel.golongan_disabilitas', $filters['golongan']);
        }

        // --- LOGIKA FILTER WILAYAH YANG DIPERBAIKI ---
        if ($role === 'superadmin') {
            if (!empty($filters['id_kabupaten'])) {
                // Jika superadmin memilih wilayah, filter berdasarkan pilihannya
                $query->where('data_difabel.id_kabupaten', $filters['id_kabupaten']);
            }
            // Jika tidak, biarkan kosong (tampilkan semua)
        } else {
            // Admin daerah tetap hanya bisa melihat wilayahnya sendiri
            $query->where('data_difabel.id_kabupaten', $id_kabupaten_admin);
        }
        
        // TAMBAHKAN GROUP BY
        $query->groupBy('data_difabel.id');

        $page_title = 'Manajemen Data Difabel';
        $nama_kabupaten = null;
        $nama_kabupaten_slug = null; // Inisialisasi
        if ($role === 'admin') {
            $kabupaten = $kabupatenModel->find($id_kabupaten_admin);
            if ($kabupaten) {
                $page_title .= ' - Wilayah ' . $kabupaten['nama_kabupaten'];
                $nama_kabupaten_slug = $kabupaten['slug'];
            }
        }
        
        $data = [
            'data_difabel'   => $query->paginate(10, 'difabel'),
            'pager'          => $difabelModel->pager,
            'title'          => $page_title,
            'message'        => session()->getFlashdata('message'),
            'filters'        => $filters,
            'role'           => $role,
            'nama_kabupaten' => $nama_kabupaten,
            'kabupaten_list' => $kabupatenModel->findAll(), // Kirim daftar kabupaten
            'nama_kabupaten_slug' => $nama_kabupaten_slug,
            'breadcrumbs'    => [
                ['title' => 'Beranda', 'url' => '/dashboard'],
                ['title' => 'SIM-DIFABELKEPRI', 'url' => '/admin/difabelkepri']
            ]
        ];
        
        return view('difabelkepri/SIM-DIFABELKEPRI', $data);
    }
    /**
     * Menampilkan formulir untuk menambah data baru.
     */
    public function new()
    {
        $kecamatanModel = new KecamatanModel();
        $jenisDisabilitasModel = new JenisDisabilitasModel();
        $id_kabupaten_admin = session()->get('id_kabupaten');

        $data = [
            // Mengambil daftar kecamatan untuk wilayah admin
            'kecamatan_list' => $kecamatanModel->where('id_kabupaten', $id_kabupaten_admin)->findAll(),
            // Mengambil semua pilihan jenis disabilitas
            'jenis_disabilitas_list' => $jenisDisabilitasModel->findAll(),
            'breadcrumbs' => [
                ['title' => 'SIM-DIFABELKEPRI', 'url' => '/admin/difabelkepri'],
                ['title' => 'Tambah Data', 'url' => '']
            ]
        ];
        
        return view('difabelkepri/input', $data);
    }

    /**
     * Menyimpan data baru dari form ke database.
     */
    public function create()
    {
        $difabelModel = new \App\Models\DifabelModel();
        $linkModel = new \App\Models\LinkDifabelJenisModel();
        $session = session();

        // 1. Siapkan data utama dari form
        $dataDifabel = [
            'nik'                   => $this->request->getPost('nik'),
            'nama_lengkap'          => $this->request->getPost('nama_lengkap'),
            'jenis_kelamin'         => $this->request->getPost('jenis_kelamin'),
            'usia'                  => $this->request->getPost('usia'),
            'id_kecamatan'          => $this->request->getPost('id_kecamatan'),
            'id_kelurahan'          => $this->request->getPost('id_kelurahan'),
            'alamat_lengkap'        => $this->request->getPost('alamat_lengkap'),
            'golongan_disabilitas'  => $this->request->getPost('golongan_disabilitas'),
            'sebab_disabilitas'     => $this->request->getPost('sebab_disabilitas'),
            'id_kabupaten'          => $session->get('id_kabupaten'),
            'id_admin_input'        => $session->get('user_id'),
        ];

        // 2. Coba simpan data utama. Metode save() akan menjalankan validasi.
        if ($difabelModel->save($dataDifabel) === false) {
            // Jika validasi GAGAL, langsung kembali ke form dengan error (yang berbentuk ARRAY)
            return redirect()->back()->withInput()->with('errors', $difabelModel->errors());
        }

        // 3. Jika BERHASIL, lanjutkan menyimpan data ke tabel penghubung
        $newDifabelId = $difabelModel->getInsertID();
        $jenisDisabilitasIds = $this->request->getPost('jenis_disabilitas_ids');

        if (!empty($jenisDisabilitasIds)) {
            // Kita gunakan transaksi di sini untuk memastikan data link tersimpan semua atau tidak sama sekali
            $db = \Config\Database::connect();
            $db->transStart();
            foreach ($jenisDisabilitasIds as $jenisId) {
                $linkModel->save([
                    'id_difabel' => $newDifabelId,
                    'id_jenis_disabilitas' => $jenisId
                ]);
            }
            $db->transComplete();
        }

        return redirect()->to('/admin/difabelkepri')->with('message', 'Data berhasil ditambahkan!');
    }

    public function edit($id = null)
    {
        $difabelModel = new DifabelModel();
        $kecamatanModel = new KecamatanModel();
        $kelurahanModel = new KelurahanModel();
        $jenisDisabilitasModel = new JenisDisabilitasModel();
        $linkModel = new LinkDifabelJenisModel();

        $dataDifabel = $difabelModel->find($id);
        if (!$dataDifabel) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data tidak ditemukan.');
        }

        $data = [
            'data_difabel' => $dataDifabel,
            // Ambil semua jenis disabilitas untuk pilihan di form
            'jenis_disabilitas_list' => $jenisDisabilitasModel->findAll(),
            // Ambil jenis disabilitas yang sudah terpilih untuk orang ini
            'selected_jenis_ids' => $linkModel->getJenisByDifabelId($id),
            // Data untuk dropdown wilayah
            'kecamatan_list' => $kecamatanModel->where('id_kabupaten', $dataDifabel['id_kabupaten'])->findAll(),
            'kelurahan_list' => $kelurahanModel->where('id_kecamatan', $dataDifabel['id_kecamatan'])->findAll(),
            'breadcrumbs' => [
                ['title' => 'SIM-DIFABELKEPRI', 'url' => '/admin/difabelkepri'],
                ['title' => 'Edit Data', 'url' => '']
            ]
        ];

        return view('difabelkepri/edit_view', $data);
    }

    public function update($id = null)
    {
        $difabelModel = new \App\Models\DifabelModel();
        $linkModel = new \App\Models\LinkDifabelJenisModel();

        // 1. Kumpulkan data utama dari form
        $dataDifabel = [
            'nik'                   => $this->request->getPost('nik'),
            'nama_lengkap'          => $this->request->getPost('nama_lengkap'),
            'jenis_kelamin'         => $this->request->getPost('jenis_kelamin'),
            'usia'                  => $this->request->getPost('usia'),
            'id_kecamatan'          => $this->request->getPost('id_kecamatan'),
            'id_kelurahan'          => $this->request->getPost('id_kelurahan'),
            'alamat_lengkap'        => $this->request->getPost('alamat_lengkap'),
            'golongan_disabilitas'  => $this->request->getPost('golongan_disabilitas'),
            'sebab_disabilitas'     => $this->request->getPost('sebab_disabilitas'),
        ];

        // --- BAGIAN KUNCI PERBAIKAN ---
        // Tambahkan ID ke dalam data untuk memberitahu model ini adalah proses UPDATE
        $dataDifabel['id'] = $id;

        // Gunakan save() yang akan otomatis menjalankan validasi dengan benar
        if ($difabelModel->save($dataDifabel) === false) {
            // Jika validasi GAGAL, langsung kembali ke form dengan pesan error
            return redirect()->back()->withInput()->with('errors', $difabelModel->errors());
        }
        // --- BATAS PERBAIKAN ---

        // Jika validasi BERHASIL, lanjutkan dengan mengelola tabel penghubung
        $db = \Config\Database::connect();
        $db->transStart();

        // Hapus dulu semua link jenis disabilitas yang lama untuk orang ini
        $linkModel->where('id_difabel', $id)->delete();

        // Masukkan kembali link jenis disabilitas yang baru dari form
        $jenisDisabilitasIds = $this->request->getPost('jenis_disabilitas_ids');
        if (!empty($jenisDisabilitasIds)) {
            foreach ($jenisDisabilitasIds as $jenisId) {
                $linkModel->save([
                    'id_difabel' => $id,
                    'id_jenis_disabilitas' => $jenisId
                ]);
            }
        }
        
        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate data jenis disabilitas.');
        }

        return redirect()->to('/admin/difabelkepri')->with('message', 'Data berhasil diupdate!');
    }

    public function delete($id = null)
    {
        $difabelModel = new DifabelModel();
        if ($difabelModel->delete($id)) {
            return redirect()->to('/admin/difabelkepri')->with('message', 'Data berhasil dihapus.');
        } else {
            return redirect()->to('/admin/difabelkepri')->with('error', 'Gagal menghapus data.');
        }
    }
    
    public function export()
    {
        $difabelModel = new \App\Models\DifabelModel();

        $filters = [
            'keyword'  => $this->request->getGet('keyword'),
            'golongan' => $this->request->getGet('golongan')
        ];

        $role = session()->get('role');
        $id_kabupaten = ($role === 'admin') ? session()->get('id_kabupaten') : false;

        // Ambil data menggunakan method baru yang sudah kita buat
        $data_difabel = $difabelModel->getExportData($id_kabupaten, $filters);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Tulis header tabel
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'NIK');
        $sheet->setCellValue('C1', 'Nama Lengkap');
        $sheet->setCellValue('D1', 'Jenis Kelamin');
        $sheet->setCellValue('E1', 'Usia');
        $sheet->setCellValue('F1', 'Kabupaten');
        $sheet->setCellValue('G1', 'Kecamatan');
        $sheet->setCellValue('H1', 'Kelurahan');
        $sheet->setCellValue('I1', 'Alamat');
        $sheet->setCellValue('J1', 'Golongan Disabilitas');
        $sheet->setCellValue('K1', 'Jenis Disabilitas');
        $sheet->setCellValue('L1', 'Sebab Disabilitas');

        // Tulis data dari database
        $rowNumber = 2;
        foreach ($data_difabel as $index => $item) {
            $sheet->setCellValue('A' . $rowNumber, $index + 1);
            $sheet->setCellValue('B' . $rowNumber, "'" . $item['nik']); // Tambahkan ' agar NIK jadi teks
            $sheet->setCellValue('C' . $rowNumber, $item['nama_lengkap']);
            $sheet->setCellValue('D' . $rowNumber, $item['jenis_kelamin']);
            $sheet->setCellValue('E' . $rowNumber, $item['usia']);
            $sheet->setCellValue('F' . $rowNumber, $item['nama_kabupaten']);
            $sheet->setCellValue('G' . $rowNumber, $item['nama_kecamatan']);
            $sheet->setCellValue('H' . $rowNumber, $item['nama_kelurahan']);
            $sheet->setCellValue('I' . $rowNumber, $item['alamat_lengkap']);
            $sheet->setCellValue('J' . $rowNumber, $item['golongan_disabilitas']);
            $sheet->setCellValue('K' . $rowNumber, $item['jenis_disabilitas_list']);
            $sheet->setCellValue('L' . $rowNumber, $item['sebab_disabilitas']);
            $rowNumber++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'rekap_data_difabel_' . date('Y-m-d') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    }

    public function geojson_difabel($wilayah, $tingkat)
    {
        // 1. Ambil data polygon GeoJSON dari file (seperti yang sudah Anda lakukan)
        $geojson_data = json_decode(file_get_contents(WRITEPATH . "geojson/{$wilayah}_{$tingkat}.geojson"), true);

        // 2. Query ke tabel difabel untuk menghitung jumlah per wilayah
        $db = \Config\Database::connect();
        $builder = $db->table('data_difabel');
        
        // Bergantung pada 'tingkat', kita group berdasarkan nama kecamatan atau kelurahan
        $group_by_field = ($tingkat == 'kecamatan') ? 'nama_kecamatan' : 'nama_kelurahan';

        $builder->select("$group_by_field, COUNT(id) as total_difabel");
        $builder->groupBy($group_by_field);
        $query = $builder->get();
        $difabel_counts = $query->getResultArray();

        // 3. Ubah hasil query menjadi format yang mudah diakses [nama_wilayah => jumlah]
        $counts_map = array_column($difabel_counts, 'total_difabel', $group_by_field);

        // 4. Gabungkan data jumlah difabel ke dalam properti GeoJSON
        foreach ($geojson_data['features'] as &$feature) {
            $nama_wilayah = $feature['properties']['NAMOBJ'];
            // Tetapkan total_difabel jika ada, jika tidak, 0
            $feature['properties']['total_difabel'] = $counts_map[$nama_wilayah] ?? 0;
        }

        // 5. Kembalikan sebagai response JSON
        return $this->response->setJSON($geojson_data);
    }
    
    public function getChartDataGolongan()
    {
        $difabelModel = new \App\Models\DifabelModel();
        $role = session()->get('role');
        $id_kabupaten = ($role === 'admin') ? session()->get('id_kabupaten') : false;
        $chartData = $difabelModel->getChartDataByGolongan($id_kabupaten);
        return $this->response->setJSON($chartData);
    }

    public function getChartDataKecamatan()
    {
        $difabelModel = new \App\Models\DifabelModel();
        $role = session()->get('role');
        $id_kabupaten = ($role === 'admin') ? session()->get('id_kabupaten') : false;
        $chartData = $difabelModel->getChartDataByKecamatan($id_kabupaten);
        return $this->response->setJSON($chartData);
    }
    // (Method index, edit, update, delete akan kita tambahkan nanti)

    ///////////////////////////////////////////////////////////////////////////////////////////
    //------------------------------------ IMPORT EXCEL -------------------------------------//
    ///////////////////////////////////////////////////////////////////////////////////////////
    public function import()
    {
        $data = [
            'title' => 'Import Data Difabel dari Excel',
            'breadcrumbs' => [
                ['title' => 'SIM-DIFABELKEPRI', 'url' => '/admin/difabelkepri'],
                ['title' => 'Import Data', 'url' => '']
            ]
        ];
        return view('difabelkepri/import_view', $data);
    }

    public function processImport()
    {
        $file = $this->request->getFile('excel_file');

        // Validasi Awal: Pastikan file benar-benar ada dan valid
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return redirect()->to('/admin/difabelkepri/import')->with('error', 'Gagal mengupload file atau file tidak valid.');
        }

        // Pindahkan file ke folder writable/uploads
        $newName = $file->getRandomName();
        $file->move(WRITEPATH . 'uploads', $newName);
        $filePath = WRITEPATH . 'uploads/' . $newName;

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
        $rows = $spreadsheet->getActiveSheet()->toArray();
        
        $difabelModel = new \App\Models\DifabelModel();
        $kecamatanModel = new \App\Models\KecamatanModel();
        $kelurahanModel = new \App\Models\KelurahanModel();
        $jenisDisabilitasModel = new \App\Models\JenisDisabilitasModel();
        $linkModel = new \App\Models\LinkDifabelJenisModel();

        $dataToInsert = []; 
        $errors = [];
        $rowCount = 0;
        $successCount = 0;
        $session = session();
        $id_kabupaten_admin = $session->get('id_kabupaten');
        $id_admin_input = $session->get('user_id');
        
        $db = \Config\Database::connect();
        
        // Cache
        $kecamatanCache = [];
        $kelurahanCache = [];
        $jenisCache = [];

        foreach ($rows as $index => $row) {
            if ($index == 0) continue; // Lewati header
            $rowCount++;
            $excelRowNumber = $index + 1;
            
            // Memulai transaksi untuk setiap baris
            $db->transStart();

            // 1. Ambil dan validasi ID Wilayah berdasarkan NAMA dari kolom yang benar
            $nama_kecamatan = trim($row[4]); // Kolom E
            $nama_kelurahan = trim($row[5]); // Kolom F
            
            if (!isset($kecamatanCache[$nama_kecamatan])) {
                $kec = $kecamatanModel->where(['nama_kecamatan' => $nama_kecamatan, 'id_kabupaten' => $id_kabupaten_admin])->first();
                $kecamatanCache[$nama_kecamatan] = $kec['id'] ?? null;
            }
            $id_kecamatan = $kecamatanCache[$nama_kecamatan];

            if (!$id_kecamatan) { $errors["Kecamatan '$nama_kecamatan' tidak ditemukan"][] = $excelRowNumber; $db->transRollback(); continue; }
            
            if (!isset($kelurahanCache[$nama_kelurahan])) {
                $kel = $kelurahanModel->where(['nama_kelurahan' => $nama_kelurahan, 'id_kecamatan' => $id_kecamatan])->first();
                $kelurahanCache[$nama_kelurahan] = $kel['id'] ?? null;
            }
            $id_kelurahan = $kelurahanCache[$nama_kelurahan];

            if (!$id_kelurahan) { $errors["Kelurahan '$nama_kelurahan' tidak ditemukan di kec. '$nama_kecamatan'"][] = $excelRowNumber; $db->transRollback(); continue; }

            // 2. Proses dan validasi Jenis Disabilitas
            $jenisDisabilitasNames = array_map('trim', explode(',', $row[8])); // Kolom I
            $jenisDisabilitasIds = [];
            $errorInJenis = false;
            foreach($jenisDisabilitasNames as $name) {
                if (empty($name)) continue;
                if (!isset($jenisCache[$name])) {
                    $jenis = $jenisDisabilitasModel->where('nama_jenis', $name)->first();
                    $jenisCache[$name] = $jenis ?? null;
                }
                if ($jenisCache[$name]) {
                    $jenisDisabilitasIds[] = $jenisCache[$name]['id'];
                } else {
                    $errors["Jenis Disabilitas '$name' tidak valid"][] = $excelRowNumber;
                    $errorInJenis = true;
                }
            }
            if ($errorInJenis) { $db->transRollback(); continue; }

            // Tentukan golongan otomatis
            $golonganOtomatis = ''; // Inisialisasi sebagai string kosong
            if (count($jenisDisabilitasIds) > 1) {
                $golonganOtomatis = 'Disabilitas Ganda';
            } elseif (count($jenisDisabilitasIds) == 1) {
                $jenisTunggal = $jenisCache[$jenisDisabilitasNames[0]];
                $golonganOtomatis = $jenisTunggal['golongan'];
            }

            // nama 1, NIK 2, alamagt lengkap 3, kecamatan 4, kelurahan 5, usia 6, jk 7, jenis difabel 8, sebab 9
            // 3. Siapkan data utama dan validasi
            $rowData = [
                'nama_lengkap'          => $row[1], // Kolom B
                'nik'                   => preg_replace('/[^0-9]/', '', $row[2]), // Kolom A
                'alamat_lengkap'        => $row[3], // Kolom G
                'id_kecamatan'          => $id_kecamatan, // Kolom E
                'id_kelurahan'          => $id_kelurahan, // Kolom F
                'usia'                  => $row[6], // Kolom D
                'jenis_kelamin'         => $row[7], // Kolom C
                'Jenis Disabilitas'     => $row[8],
                'sebab_disabilitas'     => $row[9], // Kolom J
                'golongan_disabilitas'  => $golonganOtomatis, // Kolom H
                'id_kabupaten'          => $id_kabupaten_admin,
                'id_admin_input'        => $id_admin_input,
            ];

            if ($difabelModel->validate($rowData) === false) {
                foreach ($difabelModel->errors() as $message) { $errors[$message][] = $excelRowNumber; }
                $db->transRollback();
                continue;
            }

            // 4. Jika semua valid, simpan ke database
            $difabelModel->save($rowData);
            $newDifabelId = $difabelModel->getInsertID();
            if (!empty($jenisDisabilitasIds)) {
                foreach ($jenisDisabilitasIds as $jenisId) {
                    $linkModel->save(['id_difabel' => $newDifabelId, 'id_jenis_disabilitas' => $jenisId]);
                }
            }

            if($db->transComplete()) {
                $successCount++;
            } else {
                $errors["Gagal menyimpan ke database"][] = $excelRowNumber;
            }
        }

        // Siapkan pesan feedback (sama seperti di Bankel)
        $failCount = $rowCount - $successCount;
        $message = "Proses import selesai. <strong>Berhasil: $successCount data.</strong>";
        if ($failCount > 0) {
            $session->setFlashdata('fail_count', $failCount);
            $session->setFlashdata('errors_list', $errors);
        }

        unlink($filePath);
        return redirect()->to('/admin/difabelkepri/import')->with('message', $message);
    }
}