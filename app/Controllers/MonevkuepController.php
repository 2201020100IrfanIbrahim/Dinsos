<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class MonevkuepController extends BaseController
{
    /**
     * Menampilkan halaman utama SIM-MONEVKUEP (tabel data).
     */
    public function index()
    {
        $monevModel = new \App\Models\MonevkuepModel();
        $kabupatenModel = new \App\Models\KabupatenModel();

        // Tambahkan ini di awal method
        $role = session()->get('role');
        $id_kabupaten_admin = session()->get('id_kabupaten');

        $filters = [
            'keyword' => $this->request->getGet('keyword'),
            'dtks'    => $this->request->getGet('dtks'),   // Ya/Tidak
            'sktm'    => $this->request->getGet('sktm'),   // Ada/Tidak Ada
            'jk'      => $this->request->getGet('jk'),     // Laki-laki/Perempuan
        ];

        // --- PAGINATION STYLE (mirip Bankel) ---
        $query = $monevModel
            ->select("
                monevkuep_penerima.*,
                users.username as nama_admin,
                kabupaten.nama_kabupaten,
                kecamatan.nama_kecamatan,
                kelurahan.nama_kelurahan,
            ")
            ->join('users', 'users.id = monevkuep_penerima.id_admin_input')
            ->join('kabupaten', 'kabupaten.id = monevkuep_penerima.id_kabupaten')
            ->join('kecamatan', 'kecamatan.id = monevkuep_penerima.id_kecamatan')
            ->join('kelurahan', 'kelurahan.id = monevkuep_penerima.id_kelurahan');

        // Filter keyword (nik/nama/kecamatan/kelurahan/jenis usaha/jenis pekerjaan)
        if (!empty($filters['keyword'])) {
            $query->groupStart()
                ->like('monevkuep_penerima.nik', $filters['keyword'])
                ->orLike('monevkuep_penerima.nama_lengkap', $filters['keyword'])
                ->orLike('kecamatan.nama_kecamatan', $filters['keyword'])
                ->orLike('kelurahan.nama_kelurahan', $filters['keyword'])
                ->groupEnd();
        }
        // Filter opsional
        if (!empty($filters['dtks'])) {
            $query->where('monevkuep_penerima.dtks', $filters['dtks']);
        }
        if (!empty($filters['sktm'])) {
            $query->where('monevkuep_penerima.sktm', $filters['sktm']);
        }
        if (!empty($filters['jk'])) {
            $query->where('monevkuep_penerima.jenis_kelamin', $filters['jk']);
        }

        // Filter wilayah untuk admin
        
        if ($role === 'admin') {
            $query->where('monevkuep_penerima.id_kabupaten', $id_kabupaten_admin);
        }

        // TAMBAHKAN GROUP BY
        $query->groupBy('monevkuep_penerima.id');
        $page_title = 'Manajemen Data KUEP';
        $nama_kabupaten_slug = null; // Inisialisasi
        if ($role === 'admin') {
            $kabupaten = $kabupatenModel->find($id_kabupaten_admin);
            if ($kabupaten) {
                $page_title .= ' - Wilayah ' . $kabupaten['nama_kabupaten'];
                $nama_kabupaten_slug = $kabupaten['slug'];
            }
        }

        // Paginate 10/baris
        $data_bantuan = $query->orderBy('monevkuep_penerima.created_at', 'ASC')
            ->paginate(10, 'bantuan');
        $pager = $monevModel->pager;

        // Judul halaman
        $page_title = 'Data MONEVKUEP';
        if ($role === 'admin') {
            $kabupatenModel = new \App\Models\KabupatenModel();
            $kabupaten = $kabupatenModel->find($id_kabupaten_admin);
            if ($kabupaten) {
                $page_title .= ' - Wilayah ' . $kabupaten['nama_kabupaten'];
            }
        }

        $data = [
            'bantuan' => $data_bantuan,
            'pager'   => $pager,
            'message' => session()->getFlashdata('message'),
            'title'   => $page_title,
            'filters' => $filters,
            'breadcrumbs' => [
                ['title' => 'Beranda', 'url' => '/dashboard'],
                ['title' => 'SIM-MONEVKUEP', 'url' => '/admin/monevkuep'],
            ],
            'role' => $role,
            'nama_kabupaten_slug' => $nama_kabupaten_slug
        ];

        return view('monevkuep/SIM-MONEVKUEP', $data);
    }

    public function edit($id = null)
    {
        $monevModel = new \App\Models\MonevkuepModel();
        $kecamatanModel = new \App\Models\KecamatanModel();
        $kelurahanModel = new \App\Models\KelurahanModel();

        $bantuanData = $monevModel->find($id);
        if (!$bantuanData) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data MONEVKUEP tidak ditemukan.');
        }

        $data = [
            'bantuan' => $bantuanData,
            'kecamatan_list' => $kecamatanModel->where('id_kabupaten', $bantuanData['id_kabupaten'])->findAll(),
            'kelurahan_list' => $kelurahanModel->where('id_kecamatan', $bantuanData['id_kecamatan'])->findAll(),
            'breadcrumbs' => [
                ['title' => 'SIM-MONEVKUEP', 'url' => '/admin/monevkuep'],
                ['title' => 'Edit Data', 'url' => ''],
            ],
        ];

        return view('monevkuep/edit_view', $data);
    }

    public function update($id = null)
    {
        $monevModel = new \App\Models\MonevkuepModel();

        $dataLama = $monevModel->find($id);
        if (!$dataLama) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data MONEVKUEP tidak ditemukan.');
        }

        // Data teks dari form
        $data = [
            'nik'              => $this->request->getPost('nik'),
            'nama_lengkap'     => $this->request->getPost('nama_lengkap'),
            'jenis_kelamin'    => $this->request->getPost('jenis_kelamin'),
            'tempat_lahir'     => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir'    => $this->request->getPost('tanggal_lahir'),
            'id_kecamatan'     => $this->request->getPost('id_kecamatan'),
            'id_kelurahan'     => $this->request->getPost('id_kelurahan'),
            'dusun'            => $this->request->getPost('dusun'),
            'alamat_lengkap'   => $this->request->getPost('alamat_lengkap'),
            'dtks'             => $this->request->getPost('dtks'),
            'sktm'             => $this->request->getPost('sktm'),
            'rab_nominal'      => $this->request->getPost('rab_nominal'),
            'agama'            => $this->request->getPost('agama'),
            'pendidikan'       => $this->request->getPost('pendidikan'),
            'jenis_usaha'      => $this->request->getPost('jenis_usaha'),
            'jenis_pendidikan' => $this->request->getPost('jenis_pendidikan'),
        ];

        // Tambahkan ID untuk UPDATE
        $data['id'] = $id;

        if ($monevModel->save($data)) {
            return redirect()->to('/admin/monevkuep')->with('message', 'Data berhasil diupdate!');
        } else {
            return redirect()->back()->withInput()->with('errors', $monevModel->errors());
        }
    }

    public function delete($id = null)
    {
        $monevModel = new \App\Models\MonevkuepModel();

        $data = $monevModel->find($id);
        if ($data) {
            $monevModel->delete($id);
            return redirect()->to('/admin/monevkuep')->with('message', 'Data berhasil dihapus!');
        } else {
            return redirect()->to('/admin/monevkuep')->with('error', 'Data tidak ditemukan.');
        }
    }

    /**
     * Menampilkan formulir tambah data.
     */
    public function new()
    {
        $kecamatanModel = new \App\Models\KecamatanModel();
        $id_kabupaten_admin = session()->get('id_kabupaten');

        $data = [
            'kecamatan_list' => $kecamatanModel->where('id_kabupaten', $id_kabupaten_admin)->findAll(),
            'breadcrumbs' => [
                ['title' => 'SIM-MONEVKUEP', 'url' => '/admin/monevkuep'],
                ['title' => 'Tambah Data', 'url' => ''],
            ],
        ];
        return view('monevkuep/input', $data);
    }

    /**
     * Simpan data baru dari form.
     */
    public function create()
    {
        $monevModel = new \App\Models\MonevkuepModel();
        $session = session();

        $data = [
            'nik'              => $this->request->getPost('nik'),
            'nama_lengkap'     => $this->request->getPost('nama_lengkap'),
            'jenis_kelamin'    => $this->request->getPost('jenis_kelamin'),
            'tempat_lahir'     => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir'    => $this->request->getPost('tanggal_lahir'),
            'id_kecamatan'     => $this->request->getPost('id_kecamatan'),
            'id_kelurahan'     => $this->request->getPost('id_kelurahan'),
            'dusun'            => $this->request->getPost('dusun') ?: null,
            'alamat_lengkap'   => $this->request->getPost('alamat_lengkap') ?: null,
            'dtks'             => $this->request->getPost('dtks') ?: null,
            'sktm'             => $this->request->getPost('sktm') ?: null,
            'rab_nominal'      => $this->request->getPost('rab_nominal'),
            'agama'            => $this->request->getPost('agama') ?: null,
            'pendidikan'       => $this->request->getPost('pendidikan') ?: null,
            'jenis_usaha'      => $this->request->getPost('jenis_usaha') ?: null,
            'jenis_pekerjaan'  => $this->request->getPost('jenis_pekerjaan') ?: null,
            'id_kabupaten'     => $session->get('id_kabupaten'),
            'id_admin_input'   => $session->get('user_id'),
        ];

        if ($monevModel->save($data)) {
            return redirect()->to('/admin/monevkuep')->with('message', 'Data berhasil ditambahkan!');
        } else {
            return redirect()->back()->withInput()->with('errors', $monevModel->errors());
        }
    }

    public function export()
    {
        $monevModel = new \App\Models\MonevkuepModel();

        $role = session()->get('role');
        $id_kabupaten_admin = session()->get('id_kabupaten');

        $data_bantuan = ($role === 'superadmin')
            ? $monevModel->getMonevkuepData()
            : $monevModel->getMonevkuepData($id_kabupaten_admin);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom (disesuaikan variabel MONEVKUEP)
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'NIK');
        $sheet->setCellValue('C1', 'Nama Lengkap');
        $sheet->setCellValue('D1', 'Jenis Kelamin');
        $sheet->setCellValue('E1', 'Tempat Lahir');
        $sheet->setCellValue('F1', 'Tanggal Lahir');
        $sheet->setCellValue('G1', 'Usia');
        $sheet->setCellValue('H1', 'Kabupaten/Kota');
        $sheet->setCellValue('I1', 'Kecamatan');
        $sheet->setCellValue('J1', 'Kelurahan');
        $sheet->setCellValue('K1', 'Alamat');
        $sheet->setCellValue('L1', 'DTKS');
        $sheet->setCellValue('M1', 'SKTM');
        $sheet->setCellValue('N1', 'Agama');
        $sheet->setCellValue('O1', 'Pendidikan');
        $sheet->setCellValue('P1', 'Jenis Usaha');
        $sheet->setCellValue('Q1', 'Jenis Pekerjaan');
        $sheet->setCellValue('R1', 'RAB (Nominal)');

        $rowNumber = 2;
        foreach ($data_bantuan as $index => $item) {
            $sheet->setCellValue('A' . $rowNumber, $index + 1);
            $sheet->setCellValue('B' . $rowNumber, "'" . ($item['nik'] ?? ''));
            $sheet->setCellValue('C' . $rowNumber, $item['nama_lengkap'] ?? '');
            $sheet->setCellValue('D' . $rowNumber, $item['jenis_kelamin'] ?? '');
            $sheet->setCellValue('E' . $rowNumber, $item['tempat_lahir'] ?? '');
            $sheet->setCellValue('F' . $rowNumber, $item['tanggal_lahir'] ?? '');
            $sheet->setCellValue('G' . $rowNumber, $item['usia'] ?? '');
            $sheet->setCellValue('H' . $rowNumber, $item['nama_kabupaten'] ?? '');
            $sheet->setCellValue('I' . $rowNumber, $item['nama_kecamatan'] ?? '');
            $sheet->setCellValue('J' . $rowNumber, $item['nama_kelurahan'] ?? '');
            $sheet->setCellValue('K' . $rowNumber, $item['alamat_lengkap'] ?? '');
            $sheet->setCellValue('L' . $rowNumber, $item['dtks'] ?? '');
            $sheet->setCellValue('M' . $rowNumber, $item['sktm'] ?? '');
            $sheet->setCellValue('N' . $rowNumber, $item['agama'] ?? '');
            $sheet->setCellValue('O' . $rowNumber, $item['pendidikan'] ?? '');
            $sheet->setCellValue('P' . $rowNumber, $item['jenis_usaha'] ?? '');
            $sheet->setCellValue('Q' . $rowNumber, $item['jenis_pekerjaan'] ?? '');
            $sheet->setCellValue('R' . $rowNumber, $item['rab_nominal'] ?? '');
            $rowNumber++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'rekap_monevkuep_' . date('Y-m-d') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    public function geojson_kuep($wilayah, $tingkat)
{
    // 1. Ambil data polygon GeoJSON dari file (seperti yang sudah Anda lakukan)
    $geojson_data = json_decode(file_get_contents(WRITEPATH . "geojson/{$wilayah}_{$tingkat}.geojson"), true);

    // 2. Query ke tabel difabel untuk menghitung jumlah per wilayah
    $db = \Config\Database::connect();
    $builder = $db->table('monevkuep_penerima');

    // Bergantung pada 'tingkat', kita group berdasarkan nama kecamatan atau kelurahan
    $group_by_field = ($tingkat == 'kecamatan') ? 'nama_kecamatan' : 'nama_kelurahan';

    $builder->select("$group_by_field, COUNT(id) as total_kuep");
    $builder->groupBy($group_by_field);
    $query = $builder->get();
    $kuep_counts = $query->getResultArray();

    // 3. Ubah hasil query menjadi format yang mudah diakses [nama_wilayah => jumlah]
    $counts_map = array_column($kuep_counts, 'total_kuep', $group_by_field);

    // 4. Gabungkan data jumlah difabel ke dalam properti GeoJSON
    foreach ($geojson_data['features'] as &$feature) {
        $nama_wilayah = $feature['properties']['NAMOBJ'];
        // Tetapkan total_difabel jika ada, jika tidak, 0
        $feature['properties']['total_kuep'] = $counts_map[$nama_wilayah] ?? 0;
    }

    // 5. Kembalikan sebagai response JSON
    return $this->response->setJSON($geojson_data);
}


    // --- Helper eksisting di Bankel (dibiarkan ada; tidak dipakai di MONEVKUEP) ---
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
        if ((float)$parts[1] == 0) {
            return 0;
        }
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
    // --- end helper ---

    public function getKelurahanByKecamatan($id_kecamatan)
    {
        $kelurahanModel = new \App\Models\KelurahanModel();
        $kelurahanList = $kelurahanModel->where('id_kecamatan', $id_kecamatan)->findAll();
        return $this->response->setJSON($kelurahanList);
    }

    public function getChartData()
    {
        $monevModel = new \App\Models\MonevkuepModel();
        $role = session()->get('role');
        $id_kabupaten = ($role === 'admin') ? session()->get('id_kabupaten') : false;

        $chartData = $monevModel->getChartDataByKecamatan($id_kabupaten);
        return $this->response->setJSON($chartData);
    }

    // CHART NEW
    public function getChartDataByYear()
    {
        $model = new \App\Models\MonevkuepModel();
        $role = session()->get('role');
        $id_kabupaten = ($role === 'admin') ? session()->get('id_kabupaten') : false;
        return $this->response->setJSON($model->getChartDataByYear($id_kabupaten));
    }

    public function getChartDataByGender()
    {
        $model = new \App\Models\MonevkuepModel();
        $role = session()->get('role');
        $id_kabupaten = ($role === 'admin') ? session()->get('id_kabupaten') : false;
        return $this->response->setJSON($model->getChartDataByGender($id_kabupaten));
    }

    public function getChartDataByDTKS()
    {
        $model = new \App\Models\MonevkuepModel();
        $role = session()->get('role');
        $id_kabupaten = ($role === 'admin') ? session()->get('id_kabupaten') : false;
        return $this->response->setJSON($model->getChartDataByDTKS($id_kabupaten));
    }

    public function getChartDataByAgama()
    {
        $model = new \App\Models\MonevkuepModel();
        $role = session()->get('role');
        $id_kabupaten = ($role === 'admin') ? session()->get('id_kabupaten') : false;
        return $this->response->setJSON($model->getChartDataByAgama($id_kabupaten));
    }

    public function getChartDataByPendidikan()
    {
        $model = new \App\Models\MonevkuepModel();
        $role = session()->get('role');
        $id_kabupaten = ($role === 'admin') ? session()->get('id_kabupaten') : false;
        return $this->response->setJSON($model->getChartDataByPendidikan($id_kabupaten));
    }

    public function getChartDataByJenisUsaha()
    {
        $model = new \App\Models\MonevkuepModel();
        $role = session()->get('role');
        $id_kabupaten = ($role === 'admin') ? session()->get('id_kabupaten') : false;
        return $this->response->setJSON($model->getChartDataByJenisUsaha($id_kabupaten));
    }


    ///////////////////////////////////////////////////////////////////////////////////////////
    //------------------------------------ IMPORT EXCEL -------------------------------------//
    ///////////////////////////////////////////////////////////////////////////////////////////
    public function import()
    {
        $data = [
            'title' => 'Import Data MONEVKUEP dari Excel',
            'breadcrumbs' => [
                ['title' => 'SIM-MONEVKUEP', 'url' => '/admin/monevkuep'],
                ['title' => 'Import Data', 'url' => '']
            ]
        ];
        return view('monevkuep/import_view', $data);
    }

    public function processImport()
{
    $file = $this->request->getFile('excel_file');

    // Validasi Awal: Pastikan file benar-benar ada dan valid
    if (!$file || !$file->isValid() || $file->hasMoved()) {
        return redirect()->to('/admin/monevkuep/import')->with('error', 'Gagal mengupload file atau file tidak valid.');
    }

    // Pindahkan file ke folder writable/uploads
    $newName = $file->getRandomName();
    $file->move(WRITEPATH . 'uploads', $newName);
    $filePath = WRITEPATH . 'uploads/' . $newName;

    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
    $rows = $spreadsheet->getActiveSheet()->toArray();
    
    $monevModel = new \App\Models\MonevkuepModel();
    $kecamatanModel = new \App\Models\KecamatanModel();
    $kelurahanModel = new \App\Models\KelurahanModel();

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

    foreach ($rows as $index => $row) {
        if ($index == 0) continue; // Lewati header

        // Cek apakah baris kosong (misalnya NIK dan nama kosong)
        if (
            empty(trim((string)($row[1] ?? ''))) && // NIK
            empty(trim((string)($row[2] ?? ''))) && // Nama
            empty(trim((string)($row[8] ?? ''))) && // Kecamatan
            empty(trim((string)($row[9] ?? '')))    // Kelurahan
        ) {
            continue; // Lewati baris kosong
        }

        $rowCount++;
        $excelRowNumber = $index + 1;
        
        $db->transStart();

        // Ambil dan validasi ID Wilayah
        $nama_kecamatan = trim($row[8]);
        $nama_kelurahan = trim($row[9]);
        
        if (!isset($kecamatanCache[$nama_kecamatan])) {
            $kec = $kecamatanModel->where([
                'nama_kecamatan' => $nama_kecamatan,
                'id_kabupaten'   => $id_kabupaten_admin
            ])->first();
            $kecamatanCache[$nama_kecamatan] = $kec['id'] ?? null;
        }
        $id_kecamatan = $kecamatanCache[$nama_kecamatan];

        if (!$id_kecamatan) {
            $errors["Kecamatan '$nama_kecamatan' tidak ditemukan"][] = $excelRowNumber;
            $db->transRollback();
            continue;
        }
        
        if (!isset($kelurahanCache[$nama_kelurahan])) {
            $kel = $kelurahanModel->where([
                'nama_kelurahan' => $nama_kelurahan,
                'id_kecamatan'   => $id_kecamatan
            ])->first();
            $kelurahanCache[$nama_kelurahan] = $kel['id'] ?? null;
        }
        $id_kelurahan = $kelurahanCache[$nama_kelurahan];

        if (!$id_kelurahan) {
            $errors["Kelurahan '$nama_kelurahan' tidak ditemukan di kec. '$nama_kecamatan'"][] = $excelRowNumber;
            $db->transRollback();
            continue;
        }

        // --- Normalisasi field ---
        $jkRaw = trim((string) ($row[3] ?? ''));
        $jenis_kelamin = null;
        $jkUpper = strtoupper($jkRaw);
        if (in_array($jkUpper, ['LAKI-LAKI','LAKI LAKI','LAKI', 'L'])) {
            $jenis_kelamin = 'Laki-laki';
        } elseif (in_array($jkUpper, ['PEREMPUAN','PEREM','P','PER'])) {
            $jenis_kelamin = 'Perempuan';
        } else {
            $jenis_kelamin = $row[3];
        }

        // tanggal_lahir
        $tanggalExcel = $row[5] ?? '';
        $tanggal_lahir = null;
        if (!empty($tanggalExcel) || $tanggalExcel === 0 || $tanggalExcel === '0') {
            if (is_numeric($tanggalExcel)) {
                try {
                    $tanggal_lahir = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tanggalExcel)->format('Y-m-d');
                } catch (\Exception $e) {
                    $tanggal_lahir = null;
                }
            } else {
                $formats = ['d/m/Y', 'Y-m-d', 'm/d/Y', 'd-m-Y'];
                foreach ($formats as $fmt) {
                    $d = \DateTime::createFromFormat($fmt, trim($tanggalExcel));
                    if ($d && $d->format($fmt) === trim($tanggalExcel)) {
                        $tanggal_lahir = $d->format('Y-m-d');
                        break;
                    }
                }
                if ($tanggal_lahir === null) {
                    $ts = strtotime(trim($tanggalExcel));
                    if ($ts !== false) {
                        $tanggal_lahir = date('Y-m-d', $ts);
                    }
                }
            }
        }

        // rab_nominal
        $rabRaw = trim((string) ($row[17] ?? ''));
        $rab_nominal = null;
        if ($rabRaw !== '' && $rabRaw !== null) {
            // Bersihkan semua karakter selain angka
            // Contoh: "Rp 1.000.000" → "1000000"
            $cleanRab = preg_replace('/[^0-9]/', '', $rabRaw);

            if ($cleanRab !== '') {
                // Simpan sebagai angka murni
                $rab_nominal = (float) $cleanRab;
            }
        }


        // dtks & sktm
        $dtks = null;
        $sktm = null;
        $dtksRaw = trim((string) ($row[11] ?? ''));
        $sktmRaw = trim((string) ($row[12] ?? ''));
        $dtksLower = strtolower($dtksRaw);
        if (in_array($dtksLower, ['ya','ada','iya','y'])) $dtks = 'Ya';
        elseif (in_array($dtksLower, ['tidak','tidak ada','no','n'])) $dtks = 'Tidak';
        else $dtks = $dtksRaw ?: null;

        $sktmLower = strtolower($sktmRaw);
        if (in_array($sktmLower, ['ada','ya'])) $sktm = 'Ada';
        elseif (in_array($sktmLower, ['tidak','tidak ada','no'])) $sktm = 'Tidak Ada';
        else $sktm = $sktmRaw ?: null;

        // Data untuk insert
        $rowData = [
            'nik'                   => preg_replace('/[^0-9]/', '', $row[1]),
            'nama_lengkap'          => $row[2],
            'jenis_kelamin'         => $jenis_kelamin,
            'tempat_lahir'          => $row[4],
            'tanggal_lahir'         => $tanggal_lahir,
            'usia'                  => $row[6],
            'id_kabupaten'          => $id_kabupaten_admin,
            'id_admin_input'        => $id_admin_input,
            'id_kecamatan'          => $id_kecamatan,
            'id_kelurahan'          => $id_kelurahan,
            'alamat_lengkap'        => $row[10],
            'dtks'                  => $dtks,
            'sktm'                  => $sktm,
            'agama'                 => $row[13],
            'pendidikan'            => $row[14],
            'jenis_usaha'           => $row[15],
            'jenis_pekerjaan'       => $row[16],
            'rab_nominal'           => $rab_nominal,
        ];

        if ($monevModel->validate($rowData) === false) {
            foreach ($monevModel->errors() as $message) {
                $errors[$message][] = $excelRowNumber;
            }
            $db->transRollback();
            continue;
        }

        $monevModel->save($rowData);

        if($db->transComplete()) {
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
    return redirect()->to('/admin/monevkuep/import')->with('message', $message);
}


}
