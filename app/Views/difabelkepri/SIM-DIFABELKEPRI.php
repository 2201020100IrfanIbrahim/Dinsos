<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
Manajemen Data Difabel
<?= $this->endSection() ?>

<?= $this->section('page_styles') ?>
<style>
    /* Style ini bisa digunakan bersama karena tampilannya konsisten */
    .card { background-color: #fff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); margin-bottom: 30px; overflow: hidden; }
    .card-header { padding: 15px 20px; background-color: #f8f9fa; border-bottom: 1px solid #dee2e6; font-weight: 600; display: flex; justify-content: space-between;}
    .card-body { padding: 20px; }
    .visualization-section { display: grid; grid-template-columns: 1fr; gap: 30px; }
    .visualization-section .chart-container {
        min-height: 200px;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #999;
    }

    /* Aturan baru untuk card body peta (tanpa flexbox) */
    .visualization-section .map-card-body {
        padding: 0; /* Memberi ruang di sekitar kontrol dan peta */
        min-height: 450px; /* Biarkan tingginya otomatis */
    }
    table { width: 100%; border-collapse: collapse; }
    th, td { border-bottom: 1px solid #dee2e6; padding: 12px; text-align: left; vertical-align: middle; }
    thead th { background-color: #e9ecef; }
    tbody tr:hover { background-color: #f1f1f1; }
    .add-button, .export-button { display: inline-block; padding: 8px 12px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; font-size: 14px; }
    .export-button { background-color: #17a2b8; margin-left: 10px; }
    .flash-message { padding: 15px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px; margin-bottom: 20px;}
    .action-links { text-align: center; white-space: nowrap; }
    .btn { display: inline-block; padding: 6px 10px; border-radius: 5px; text-decoration: none; color: white; font-size: 14px; margin: 0 2px; transition: transform 0.2s; }
    .btn:hover { transform: scale(1.1); }
    .btn-edit { background-color: #007bff; }
    .btn-delete { background-color: #dc3545; }
    .filter-form { margin-bottom: 20px; border-bottom: 1px solid #e9ecef; padding-bottom: 20px; }
    .filter-form form { display: flex; gap: 10px; align-items: center; }
    .filter-form input { padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
    .filter-form button, .filter-form a { padding: 8px 15px; border-radius: 4px; text-decoration: none; cursor: pointer; white-space: nowrap; }
    .filter-form button { background-color: #007bff; color: white; border: 1px solid #007bff; }
    .filter-form a { background-color: #6c757d; color: white; border: 1px solid #6c757d; font-size: 14px; display:inline-flex; align-items:center; justify-content: center;}
    .pagination-container { margin-top: 20px; display: flex; justify-content: center; }
    .pagination { display: inline-flex; list-style-type: none; padding: 0; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    .page-item .page-link { color: #007bff; padding: 10px 15px; text-decoration: none; transition: background-color .3s; border: 1px solid #ddd; margin: 0 -1px 0 0; display: block; }
    .page-item:first-child .page-link { border-top-left-radius: 8px; border-bottom-left-radius: 8px; }
    .page-item:last-child .page-link { border-top-right-radius: 8px; border-bottom-right-radius: 8px; }
    .page-item.active .page-link { z-index: 1; color: #fff; background-color: #007bff; border-color: #007bff; }
    .page-item .page-link:hover { background-color: #e9ecef; }
    .table-responsive-wrapper {
        overflow-x: auto; /* Membuat hanya div ini yang bisa di-scroll ke samping */
        width: 100%;
    }

    #map {
        height: 500px; /* default untuk desktop */
        width: 100%;
        background-color: #f9f9f9;
        border: 1px solid #ccc;
        z-index: 1;
    }

    /* ============================================= */
    /* STYLE UNTUK TAMPILAN HP            */
    /* ============================================= */
    @media (max-width: 768px) {
        /* 1. Mengubah layout visualisasi menjadi atas-bawah */
        .visualization-section {
            grid-template-columns: 1fr; /* Mengubah dari 2 kolom menjadi 1 kolom */
        }

        /* 2. Menyesuaikan ukuran font */
        h1 { font-size: 22px; }
        h2 { font-size: 18px; }
        table, .add-button, .export-button, .filter-form input, .filter-form button, .filter-form a {
            font-size: 14px; /* Sedikit mengecilkan font di tabel dan tombol */
        }
        
        /* 3. Membuat form filter menjadi responsif */
        .filter-form form {
            flex-direction: column;   /* Mengubah arah item menjadi ke bawah */
            align-items: stretch;   /* Membuat semua item selebar kontainer */
            gap: 15px;
        }

        .card-header{
            flex-direction: column;
            text-align: left;
        }
        .card-header .tombol {
            flex-direction: column; /* Ubah arah item menjadi ke bawah */
            align-items: stretch; /* Buat item meregang selebar card */
            gap: 15px;
        }
        .card-header .tombol-aksi {
            display: grid;
            grid-template-columns: 1fr 1fr; /* Buat 2 kolom tombol */
            gap: 10px;
        }
        .card-header .add-button {
            grid-column: 1 / -1; /* Buat tombol "Tambah Data" mengambil lebar penuh */
        }
    }

    .legend {
        background: white;
        padding: 6px;
        line-height: 1.4em;
        border: 1px solid #ccc;
        font-size: 14px;
    }
    .legend i {
        width: 18px;
        height: 18px;
        float: left;
        margin-right: 8px;
        opacity: 0.8;
    }
    .map-controls {
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
        align-items: center;
    }
    .map-controls select {
        padding: 8px;
        font-size: 14px;
    }

</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    
    <h1><?= esc($title ?? 'Manajemen Data Difabel') ?></h1>

    <div class="visualization-section">
        <div class="card">
            <div class="card-header">Peta Persebaran</div>
            <div class="card-body map-card-body">
            <div class="map-controls">
                <div>
                    <label for="tingkat">Tingkat:</label>
                    <select id="tingkat">
                        <option value="kecamatan">Kecamatan</option>
                        <option value="kelurahan">Kelurahan</option>
                    </select>
                </div>
                    <?php if ($role === 'superadmin'): ?>
                        <div>
                            <label for="wilayah">Wilayah:</label>
                            <select id="wilayah">
                                <option value="tanjungpinang">Tanjung Pinang</option>
                                <option value="batam">Batam</option>
                                </select>
                        </div>
                    <?php else: ?>
                        <input type="hidden" id="wilayah" value="<?= esc($nama_kabupaten_slug) ?>">
                    <?php endif; ?>
                </div>

            <div id="loading">Memuat data geojson...</div>
            
            <div id="map"></div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">Grafik Analisis</div>
            <div class="card-body">Grafik akan ditampilkan di sini.</div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span>Data Penyandang Disabilitas</span>
            <div>
                <a href="<?= site_url('admin/difabelkepri/export') ?>" class="export-button">Export Excel</a>
                <a href="<?= site_url('admin/difabelkepri/input') ?>" class="add-button">+ Tambah Data</a>
            </div>
        </div>
        <div class="card-body">
            
            <?php if (session()->getFlashdata('message')): ?>
                <div class="flash-message"><?= esc(session()->getFlashdata('message')) ?></div>
            <?php endif; ?>
            
            <div style="overflow-x:auto;">
                <div class="filter-form">
                    <form action="<?= site_url('admin/difabelkepri') ?>" method="get">
                        <input type="text" name="keyword" placeholder="Cari NIK atau Nama..." value="<?= esc($filters['keyword'] ?? '') ?>">

                        <select name="golongan">
                            <option value="">Semua Golongan</option>
                            <option value="Disabilitas Fisik" <?= ($filters['golongan'] ?? '') == 'Disabilitas Fisik' ? 'selected' : '' ?>>Disabilitas Fisik</option>
                            <option value="Disabilitas Sensorik" <?= ($filters['golongan'] ?? '') == 'Disabilitas Sensorik' ? 'selected' : '' ?>>Disabilitas Sensorik</option>
                            <option value="Disabilitas Mental" <?= ($filters['golongan'] ?? '') == 'Disabilitas Mental' ? 'selected' : '' ?>>Disabilitas Mental</option>
                            <option value="Disabilitas Intelektual" <?= ($filters['golongan'] ?? '') == 'Disabilitas Intelektual' ? 'selected' : '' ?>>Disabilitas Intelektual</option>
                            <option value="Disabilitas Ganda" <?= ($filters['golongan'] ?? '') == 'Disabilitas Ganda' ? 'selected' : '' ?>>Disabilitas Ganda</option>
                        </select>

                        <button type="submit">Cari</button>
                        <a href="<?= site_url('admin/difabelkepri') ?>">Reset</a>
                    </form>
                </div>
                <div class="table-responsive-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Pilihan</th>
                                <th>No</th>
                                <th>NIK</th>
                                <th>Nama Lengkap</th>
                                <th>Usia</th>
                                <th>Kecamatan</th>
                                <th>Kelurahan</th>
                                <th>Golongan Disabilitas</th>
                                <th>Jenis Disabilitas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($data_difabel)): ?> <?php foreach ($data_difabel as $index => $item): ?> <tr>
                                        <td class="action-links">
                                            <a href="<?= site_url('admin/difabelkepri/edit/' . $item['id']) ?>" class="btn btn-edit" title="Edit">📝</a> <a href="<?= site_url('admin/difabelkepri/delete/' . $item['id']) ?>" class="btn btn-delete tombol-hapus" title="Hapus">🗑️</a> </td>
                                        <td><?= ($pager->getDetails('difabel')['currentPage'] - 1) * $pager->getDetails('difabel')['perPage'] + $index + 1 ?></td>
                                        <td><?= esc($item['nik']) ?></td>
                                        <td><?= esc($item['nama_lengkap']) ?></td>
                                        <td><?= esc($item['usia']) ?></td>
                                        <td><?= esc($item['nama_kecamatan']) ?></td>
                                        <td><?= esc($item['nama_kelurahan']) ?></td>
                                        <td><?= esc($item['golongan_disabilitas']) ?></td>
                                        <td><?= esc($item['jenis_disabilitas_list']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="10" style="text-align: center;">Tidak ada data.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php if (isset($pager)): ?>
                <div class="pagination-container">
                    <?= $pager->links('difabel', 'modern_pager') ?> </div>
            <?php endif; ?>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ambil semua tombol dengan class 'tombol-hapus'
        const tombolHapus = document.querySelectorAll('.tombol-hapus');

        tombolHapus.forEach(tombol => {
            tombol.addEventListener('click', function(event) {
                event.preventDefault(); 
                const href = this.getAttribute('href');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    }
                });
            });
        });
        
             if (document.getElementById('map')) { // Hanya jalankan jika elemen #map ada
            const map = L.map('map').setView([1.1, 104], 8); // Zoom awal sedikit diubah
            // L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            // attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            // }).addTo(map);

            let geoLayer;
            const loading = document.getElementById('loading');
            const wilayahSelect = document.getElementById('wilayah');
            const tingkatSelect = document.getElementById('tingkat');

            function getColor(val) {
                return val > 100 ? '#e31a1c' :
                    val > 50  ? '#fd8d3c' :
                    val > 9   ? '#ffff76ff' :
                    val > 0   ? '#ffffb2' :
                                '#ccc';
            }

            function styleFeature(feature) {
                const val = feature.properties.total_difabel || 0;
                return {
                    fillColor: getColor(val),
                    color: "#333",
                    weight: 1,
                    fillOpacity: 0.8
                };
            }

            function loadGeoJSON() {
                const wilayah = wilayahSelect.value;
                const tingkat = tingkatSelect.value;
                loading.style.display = 'inline';

                console.log('Nilai yang akan dikirim:', { wilayah_value: wilayah, tingkat_value: tingkat });
                const url = `<?= site_url('peta/geojson_difabel/') ?>${wilayah}/${tingkat}`;

                fetch(url)
                    .then(res => {
                        if (!res.ok) throw new Error(`Gagal memuat GeoJSON (${res.status})`);
                        return res.json();
                    })
                    .then(data => {
                        if (data.error) throw new Error(data.error);
                        if (geoLayer) map.removeLayer(geoLayer);
                        geoLayer = L.geoJSON(data, {
                            style: styleFeature,
                            onEachFeature: function (feature, layer) {
                                const nama = feature.properties.NAMOBJ ?? 'Tidak diketahui';
                                const total = feature.properties.total_difabel ?? 0;
                                layer.bindPopup(`<strong>${nama}</strong><br>Total Difabel: ${total}`);
                            }
                        }).addTo(map);
                        if (geoLayer.getBounds().isValid()) {
                            map.fitBounds(geoLayer.getBounds());
                        }

                            setTimeout(function() {
                            map.invalidateSize();
                        }, 100);
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Gagal memuat peta: ' + err.message);
                        if (geoLayer) map.removeLayer(geoLayer);
                    })
                    .finally(() => {
                        loading.style.display = 'none';
                    });
            }

            const legend = L.control({ position: 'bottomright' });
            legend.onAdd = function () {
                const div = L.DomUtil.create('div', 'legend');
                const grades = [0, 1, 10, 51, 101]; 
                const labels = [];
                div.innerHTML = '<strong>Total Difabel</strong><br>';
                for (let i = 0; i < grades.length; i++) {
                    const from = grades[i];
                    const to = grades[i + 1];
                    labels.push(
                        '<i style="background:' + getColor(from) + '"></i> ' +
                        (from === 0 ? '0' : from) + (to ? '–' + (to - 1) : '+')
                    );
                }
                div.innerHTML += labels.join('<br>');
                return div;
            };
            legend.addTo(map);

            wilayahSelect.addEventListener('change', loadGeoJSON);
            tingkatSelect.addEventListener('change', loadGeoJSON);

            // Langsung muat peta saat halaman pertama kali dibuka
            loadGeoJSON(); 
        }
        // (skrip lainnya seperti tombol back)
    });
</script>
<?= $this->endSection() ?>