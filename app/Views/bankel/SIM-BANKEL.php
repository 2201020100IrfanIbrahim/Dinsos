<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
Manajemen SIM-BANKEL
<?= $this->endSection() ?>

<?= $this->section('page_styles') ?>
<style>
    /* CSS yang spesifik untuk halaman ini saja */
    .card { 
        background-color: #fff; 
        border-radius: 8px; 
        box-shadow: 0 4px 8px rgba(0,0,0,0.05); 
        margin-bottom: 30px; 
        overflow: hidden; 
    }
    .card-header { 
        padding: 15px 0px; 
        background-color: #f8f9fa; 
        border-bottom: 1px solid #dee2e6; 
        font-weight: 600; 
        display: flex; 
        justify-content: space-between;
    }
    .card-body { 
        padding: 20px 0px; 
    }
    .visualization-section {
        display: grid; 
        grid-template-columns: 1fr; /* Hanya 1 kolom */
        gap: 30px;
    }
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
    table { 
        width: 100%; 
        border-collapse: collapse; 
    }
    th, td { 
        border-bottom: 1px solid #dee2e6; 
        padding: 12px; 
        text-align: left; 
        vertical-align: middle; 
        padding-right: 30px
    }
    thead th { 
        background-color: #e9ecef; 
    }
    tbody tr:hover { 
        background-color: #f1f1f1; 
    }
    .add-button, .export-button { 
        display: inline-block; 
        padding: 8px 12px; 
        background-color: #007bff; 
        color: white; 
        text-decoration: none; 
        border-radius: 5px; 
        transition: background-color 0.2s; 
        font-size: 14px; 
        text-align: center;
        justify-content: center;
    }
    .tahun, .button-cari{
        display: flex;
        gap: 10px;
    }
    .tombol{
        display: flex;
        text-align: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    .export-button { 
        background-color: #17a2b8;
    }
    .flash-message { 
        padding: 15px; 
        background-color: #d4edda; 
        color: #155724; 
        border: 1px solid #c3e6cb; 
        border-radius: 4px; 
        margin-bottom: 20px;
    }
    .action-links { 
        text-align: center; 
        white-space: nowrap; 
    }
    .btn { 
        display: inline-block; 
        padding: 6px 10px; 
        border-radius: 5px; 
        text-decoration: none; 
        color: white; 
        font-size: 14px; 
        margin: 0 2px; 
        transition: transform 0.2s; 
    }
    .btn:hover { 
        transform: scale(1.1); 
    }
    .btn-edit { 
        background-color: #007bff; 
    }
    .btn-delete { 
        background-color: #dc3545; 
    }
    .btn-info {
        font-family: 'Inter', sans-serif;
        color: #333333; 
        font-size:16px; 
    }

    .filter-form { 
        border-bottom: 1px solid #e9ecef; 
    }
    .filter-form form { 
        display: flex; 
        gap: 10px; 
        align-items: center; 
    }
    .filter-form input { 
        padding: 8px; 
        border: 1px solid #ccc; 
        border-radius: 4px; 
    }
    .filter-form button, .filter-form a { 
        padding: 8px 15px; 
        border-radius: 4px; 
        text-decoration: none; 
        cursor: pointer; white-space: nowrap; 
    }
    .filter-form button { 
        background-color: #007bff; 
        color: white; 
        border: 1px solid #007bff; 
    }
    .filter-form a { 
        background-color: #6c757d; 
        color: white; 
        border: 1px solid #6c757d; 
        font-size: 14px; 
        display:inline-flex; 
        align-items:center; 
        justify-content: center; 
    }    
    .pagination-container { 
        margin-top: 20px; 
        display: flex; 
        justify-content: center; 
    }
    .pagination { 
        display: inline-flex; 
        list-style-type: none; 
        padding: 0; 
        border-radius: 8px; 
        box-shadow: 0 2px 5px rgba(0,0,0,0.1); 
    }
    .page-item .page-link { 
        color: #007bff; 
        padding: 10px 15px; 
        text-decoration: none; 
        transition: background-color .3s; 
        border: 1px solid #ddd; 
        margin: 0 -1px 0 0; 
        display: block; 
    }
    .page-item:first-child .page-link { 
        border-top-left-radius: 8px; 
        border-bottom-left-radius: 8px; 
    }
    .page-item:last-child .page-link { 
        border-top-right-radius: 8px; 
        border-bottom-right-radius: 8px; 
    }
    .page-item.active .page-link { 
        z-index: 1; color: #fff; 
        background-color: #007bff; 
        border-color: #007bff; 
    }
    .page-item .page-link:hover { 
        background-color: #e9ecef; 
    }
    
    /* Ganti semua style chart yang lama dengan ini */
    .chart-section {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
        justify-content: center;
    }
    .chart-wrapper {
        flex: 1;
        min-width: 300px;
        max-width: 500px; /* Lebar maksimal setiap chart wrapper */
        text-align: center;
    }
    .chart-wrapper h4 {
        margin-bottom: 15px;
    }
    .doughnut-container {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        gap: 20px;
    }
    .chart-canvas-container {
        position: relative;
        margin: auto;
    }
    .doughnut-container .chart-canvas-container {
        width: 250px;
        height: 250px;
    }
    .bar-chart-container {
        width: 100%;
        height: 250px;
    }

    #map {
        height: 500px; /* default untuk desktop */
        width: 100%;
        background-color: #f9f9f9;
        border: 1px solid #ccc;
        z-index: 1;
    }

    .chart-legend-container ul {
        list-style-type: none;
        padding: 0;
        margin-top: 15px;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px 20px;
    }

    .chart-legend-container li {
        cursor: pointer;
        display: flex;
        align-items: center;
        font-size: 13px; /* Ukuran font lebih kecil */
        transition: opacity 0.2s;
    }

    .chart-legend-container li:hover {
        opacity: 0.8;
    }

    .legend-color-box {
        width: 12px;
        height: 12px;
        display: inline-block;
        margin-right: 8px;
        border-radius: 3px;
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
        padding-top: 15px;
    }
    .map-controls select {
        padding: 8px;
        font-size: 14px;
    }

    /* ============================================= */
    /* STYLE UNTUK TAMPILAN HP                       */
    /* ============================================= */
    @media (max-width: 768px) {
        /* 1. Mengubah layout visualisasi menjadi atas-bawah */
        #map{
            height: 300px
        }
        .visualization-section .map-card-body {
            min-height: 300px;
        }
        .card-body{
            padding: 0px;
        }
        .legend.leaflet-control{
            display: none;
        }
        .visualization-section {
            grid-template-columns: 1fr;
            margin-bottom: 50px;
        }   
        .tombol{
            margin: 10px 0px;
        }  
        .tombol, .tombol-aksi{
            display: flex;
            gap: 20px;
        }   
        .tahun{
            justify-content: space-between;
        }
        /* 2. Menyesuaikan ukuran font */
        h1 { font-size: 22px; }
        h2 { font-size: 18px; }
        table, .add-button, .export-button, .filter-form input, .filter-form button, .filter-form a {
            font-size: 14px;
        }
        /* === TAMBAHKAN ATURAN BARU DI SINI === */
        .chart-container {
            flex-direction: column; /* Mengubah arah item menjadi ke bawah */
            height: auto; /* Biarkan tinggi menyesuaikan konten */
        }
        .chart-canvas-container {
            width: 100%; /* Lebar penuh */
            height: 250px; /* Atur tinggi tetap untuk canvas */
        }
        .chart-legend-container {
            margin-top: 20px; /* Beri jarak dari chart di atasnya */
            max-height: 150px; /* Batasi tinggi legenda agar tidak terlalu panjang */
        }

        .chart-section, .doughnut-container {
            flex-direction: column; /* Mengubah susunan dari horizontal ke vertikal */
            align-items: center;    /* (Opsional) Menengahkan chart dan legenda */
        }
        /* 3. Membuat form filter menjadi responsif */
        .filter-form form {
            flex-direction: column;   /* Mengubah arah item menjadi ke bawah */
            align-items: stretch;   /* Membuat semua item selebar kontainer */
            gap: 15px;
        }

        .card-header{
            gap: 15px;
            flex-direction: column;
            text-align: left;
            padding: 20px 0px;
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
</style>

<?= $this->endSection() ?>

<?= $this->section('content') ?>
    
<h1><?= esc($title) ?></h1>

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
                            <option value="tanjungpinang">TanjungPinang</option>
                            <option value="batam">Batam</option>
                            <option value="karimun">Karimun</option>
                            <option value="lingga">Lingga</option>
                            <option value="anambas">Anambas</option>
                            <option value="natuna">Natuna</option>
                            <option value="bintan">Bintan</option>
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
            <div class="card-body chart-section">
                
                <div class="chart-wrapper">
                    <h4>Analisis per Kecamatan</h4>
                    <div class="doughnut-container">
                        <div class="chart-canvas-container">
                            <canvas id="kecamatanChart"></canvas>
                        </div>
                        <div id="chart-legend" class="chart-legend-container"></div>
                    </div>
                </div>
                
                <div class="chart-wrapper">
                    <h4>Analisis per Tahun</h4>
                    <div class="chart-canvas-container bar-chart-container">
                        <canvas id="tahunChart"></canvas>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php if ($message): ?>
    <div class="flash-message"><?= esc($message) ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <span>Data Bantuan Sembako</span>

            
            <div class="filter-form">
                <form action="<?= site_url('admin/bankel') ?>" method="get">
                    <input type="text" name="keyword" placeholder="Cari NIK, Nama..." value="<?= esc($filters['keyword'] ?? '') ?>">
                    <input type="number" name="tahun" placeholder="Tahun" value="<?= esc($filters['tahun'] ?? '') ?>">

                    <?php if ($role === 'superadmin'): ?>
                        <select name="id_kabupaten" id="filter_kabupaten" onchange="this.form.submit()">
                            <option value="">Semua Wilayah</option>
                            <?php foreach ($kabupaten_list as $kab): ?>
                                <option value="<?= $kab['id'] ?>" <?= ($filters['id_kabupaten'] ?? '') == $kab['id'] ? 'selected' : '' ?>>
                                    <?= esc($kab['nama_kabupaten']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>

                    <button type="submit">Cari</button>
                    <a href="<?= site_url('admin/bankel') ?>">Reset</a>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="tombol">
                <div class="tombol-aksi">
                    <a href="<?= site_url('admin/bankel/import') ?>" class="export-button" style="background-color: #28a745;">Import Data</a>
                    <a href="<?= site_url('admin/bankel/export') ?>" class="export-button">Export Excel</a>
                </div>
                <a href="<?= site_url('admin/bankel/input') ?>" class="add-button">Tambah Data</a>
            </div>
            

            
            <div style="overflow-x:auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Pilihan</th>
                            <th>No</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <?php if (session()->get('role') === 'superadmin'): ?>
                                <th>Kabupaten/Kota</th> <?php endif; ?>
                            <th>Kecamatan</th>
                            <th>Kelurahan</th>            
                            <th>RT</th>
                            <th>RW</th>
                            <th>Jenis Bantuan</th>
                            <th>Tahun</th>
                            <th>Diinput Pada</th>
                            <th>KTP</th>
                            <th>KK</th>
                            <th>Gambar</th>
                            <th>Koordinat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($bantuan)): ?>
                            <?php foreach ($bantuan as $index => $item): ?>
                                <tr>
                                    <td class="action-links">
                                        <a href="<?= site_url('admin/bankel/edit/' . $item['id']) ?>" class="btn btn-edit" title="Edit">📝</a>
                                        <a href="<?= site_url('admin/bankel/delete/' . $item['id']) ?>" class="btn btn-delete tombol-hapus" title="Hapus">🗑️</a>
                                    </td>
                                    <td><?= $pager->getDetails('bantuan')['currentPage'] > 1 ? ($pager->getDetails('bantuan')['perPage'] * ($pager->getDetails('bantuan')['currentPage'] - 1)) + $index + 1 : $index + 1 ?></td>
                                    <td><?= esc($item['nik']) ?></td>
                                    <td><?= esc($item['nama_lengkap']) ?></td>
                                    <?php if (session()->get('role') === 'superadmin'): ?>
                                        <td><?= esc($item['nama_kabupaten']) ?></td> <?php endif; ?>
                                    <td><?= esc($item['nama_kecamatan']) ?></td>
                                    <td><?= esc($item['nama_kelurahan']) ?></td>
                                    <td><?= esc($item['rt']) ?></td>
                                    <td><?= esc($item['rw']) ?></td>
                                    <td><?= esc($item['kategori_bantuan']) ?></td>
                                    <td><?= esc($item['tahun_penerimaan']) ?></td>
                                    <td><?= date('d M Y', strtotime($item['created_at'])) ?></td>
                                    <td>
                                        <?php if (!empty($item['file_ktp'])): ?>
                                            <a href="<?= base_url('uploads/pdf/' . $item['file_ktp']) ?>" target="_blank" class="btn btn-info">
                                                Lihat KTP
                                            </a>
                                        <?php else: ?>
                                            <span>-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($item['file_kk'])): ?>
                                            <a href="<?= base_url('uploads/pdf/' . $item['file_kk']) ?>" target="_blank" class="btn btn-info">
                                                Lihat KK
                                            </a>
                                        <?php else: ?>
                                            <span>-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($item['gambar'])): ?>
                                            <a href="<?= base_url('uploads/' . $item['gambar']) ?>" target="_blank">Lihat</a>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($item['koordinat'])): ?>
                                            <a href="https://www.google.com/maps/search/?api=1&query=<?= esc($item['koordinat']) ?>" target="_blank">Lokasi</a>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="12" style="text-align: center;">Data Kosong</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="pagination-container">
                <?= $pager->links('bantuan', 'modern_pager') ?>
            </div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
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

document.addEventListener('DOMContentLoaded', function() {
    const filteredKabupatenId = '<?= esc($filters['id_kabupaten'] ?? '') ?>';

    // 2. Buat URL dasar untuk setiap chart
    let kecamatanChartUrl = '<?= site_url('admin/bankel/chart-data') ?>';
    let tahunChartUrl = '<?= site_url('admin/bankel/chart-data-by-year') ?>';

    // 3. Jika ada filter kabupaten, tambahkan sebagai parameter query
    if (filteredKabupatenId) {
        kecamatanChartUrl += `?id_kabupaten=${filteredKabupatenId}`;
        tahunChartUrl += `?id_kabupaten=${filteredKabupatenId}`;
    }

    const ctx = document.getElementById('kecamatanChart');
    if (!ctx) return;

    // Fungsi untuk menghasilkan warna dinamis
    function generateDynamicColors(count) {
        let colors = [];
        for (let i = 0; i < count; i++) {
            const hue = (i * (360 / count) * 0.8) % 360; // 0.8 untuk warna lebih lembut
            colors.push(`hsla(${hue}, 70%, 60%, 0.8)`);
        }
        return colors;
    }

    // Plugin legenda HTML kustom yang sudah diperbaiki
    const htmlLegendPlugin = {
        id: 'htmlLegend',
        afterUpdate(chart, args, options) {
            const legendContainer = document.getElementById(options.containerID);
            if (!legendContainer) return;
            
            // Buat atau dapatkan list <ul>
            let listContainer = legendContainer.querySelector('ul');
            if (!listContainer) {
                listContainer = document.createElement('ul');
                legendContainer.appendChild(listContainer);
            }
            listContainer.innerHTML = '';

            // Ambil data langsung dari chart
            const items = chart.data.labels.map((label, i) => {
                const meta = chart.getDatasetMeta(0);
                const style = meta.controller.getStyle(i);
                return {
                    text: label,
                    fillStyle: style.backgroundColor,
                    hidden: meta.data[i].hidden,
                    index: i
                };
            });

            items.forEach(item => {
                const li = document.createElement('li');
                li.onclick = () => {
                    const { type } = chart.config;
                    if (type === 'pie' || type === 'doughnut') {
                        chart.toggleDataVisibility(item.index);
                    }
                    chart.update();
                };

                const boxSpan = document.createElement('span');
                boxSpan.className = 'legend-color-box';
                boxSpan.style.background = item.fillStyle;

                const textContainer = document.createElement('p');
                textContainer.style.textDecoration = item.hidden ? 'line-through' : '';
                textContainer.innerText = item.text;

                li.appendChild(boxSpan);
                li.appendChild(textContainer);
                listContainer.appendChild(li);
            });
        }
    };

    // Plugin kustom untuk teks di tengah
    const centerTextPlugin = {
        id: 'centerText',
        afterDraw: (chart) => {
            if (chart.config.type !== 'doughnut' || chart.data.datasets[0].data.length === 0) return;
            let ctx = chart.ctx;
            let width = chart.width;
            let height = chart.height;
            ctx.restore();
            let fontSize = (height / 200).toFixed(2);
            ctx.font = fontSize + "em Segoe UI";
            ctx.textBaseline = "middle";
            ctx.fillStyle = "#6c757d";
            let text = "Total";
            let textX = Math.round((width - ctx.measureText(text).width) / 2);
            let textY = height / 2 - (fontSize * 15);
            ctx.fillText(text, textX, textY);
            let totalValue = chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
            fontSize = (height / 100).toFixed(2);
            ctx.font = "bold " + fontSize + "em Segoe UI";
            ctx.fillStyle = "#343a40";
            let totalText = totalValue.toString();
            let totalTextX = Math.round((width - ctx.measureText(totalText).width) / 2);
            let totalTextY = height / 2 + (fontSize * 7);
            ctx.fillText(totalText, totalTextX, totalTextY);
            ctx.save();
        }
    };


        // --- SCRIPT UNTUK GRAFIK BERDASARKAN TAHUN ---
    const ctxTahun = document.getElementById('tahunChart');
    if (ctxTahun) {
        fetch(tahunChartUrl) // Gunakan URL baru
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    ctxTahun.getContext('2d').clearRect(0, 0, ctxTahun.width, ctxTahun.height);
                    return;
                };

                const labels = data.map(item => item.tahun_penerimaan);
                const values = data.map(item => Number(item.jumlah));
                
                // Gunakan fungsi yang sama untuk menghasilkan warna yang konsisten
                const dynamicColors = generateDynamicColors(values.length);

                new Chart(ctxTahun, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jumlah Penerima',
                            data: values,
                            backgroundColor: dynamicColors, // Terapkan warna dinamis
                            borderColor: dynamicColors.map(color => color.replace('0.8', '1')), // Warna border lebih solid
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false 
                            }
                        }
                    }
                });
            });
    }

    fetch(kecamatanChartUrl) // Gunakan URL baru
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                ctx.getContext('2d').clearRect(0, 0, ctx.width, ctx.height);
                document.getElementById('chart-legend').innerHTML = 'Tidak ada data untuk wilayah ini.';
                return;
            };
            const labels = data.map(item => item.nama_kecamatan);
            const values = data.map(item => Number(item.jumlah));
            const dynamicColors = generateDynamicColors(values.length);

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: dynamicColors,
                        borderColor: '#fff',
                        borderWidth: 2,
                        cutout: '60%'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        htmlLegend: { containerID: 'chart-legend' },
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                    const value = context.parsed;
                                    let percentage = '0.00%';
                                    if (total > 0) {
                                        percentage = ((value / total) * 100).toFixed(1) + '%';
                                    }
                                    return `${context.label}: ${value} (${percentage})`;
                                }
                            }
                        },
                        // 1. AKTIFKAN KEMBALI KONFIGURASI DATALABELS
                        datalabels: {
                            formatter: (value, ctx) => {
                                const total = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                let percentage = '0%';
                                if (total > 0) {
                                   percentage = ((value / total) * 100).toFixed(1) + '%';
                                }
                                return percentage;
                            },
                            color: '#fff',
                            font: {
                                weight: 'bold',
                                size: 12
                            }
                        }
                    }
                },
                // 2. DAFTARKAN KEMBALI PLUGIN ChartDataLabels
                plugins: [htmlLegendPlugin, centerTextPlugin, ChartDataLabels],
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
                const val = feature.properties.total_penerima || 0;
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
                const url = `<?= site_url('peta/geojson/') ?>${wilayah}/${tingkat}`;

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
                                const total = feature.properties.total_penerima ?? 0;
                                layer.bindPopup(`<strong>${nama}</strong><br>Total Penerima: ${total}`);
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
                div.innerHTML = '<strong>Total Penerima</strong><br>';
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
    
    
});

</script>

<?= $this->endSection() ?>
