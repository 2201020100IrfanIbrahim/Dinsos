<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
Manajemen SIM-BANKEL
<?= $this->endSection() ?>

<?= $this->section('page_styles') ?>
<style>
    /* CSS yang spesifik untuk halaman ini saja */
    .card { background-color: #fff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); margin-bottom: 30px; overflow: hidden; }
    .card-header { padding: 15px 20px; background-color: #f8f9fa; border-bottom: 1px solid #dee2e6; font-weight: 600; display: flex; justify-content: space-between; align-items: center; }
    .card-body { padding: 20px; }
    .visualization-section {display: grid; grid-template-columns: 1fr; /* Hanya 1 kolom */gap: 30px;}
    .visualization-section .card-body { min-height: 200px; display:flex; justify-content:center; align-items:center; color: #999; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border-bottom: 1px solid #dee2e6; padding: 12px; text-align: left; vertical-align: middle; }
    thead th { background-color: #e9ecef; }
    tbody tr:hover { background-color: #f1f1f1; }
    .add-button, .export-button { display: inline-block; padding: 8px 12px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; transition: background-color 0.2s; font-size: 14px; }
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
    .filter-form a { background-color: #6c757d; color: white; border: 1px solid #6c757d; font-size: 14px; display:inline-flex; align-items:center; }
    
    .pagination-container { margin-top: 20px; display: flex; justify-content: center; }
    .pagination { display: inline-flex; list-style-type: none; padding: 0; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    .page-item .page-link { color: #007bff; padding: 10px 15px; text-decoration: none; transition: background-color .3s; border: 1px solid #ddd; margin: 0 -1px 0 0; display: block; }
    .page-item:first-child .page-link { border-top-left-radius: 8px; border-bottom-left-radius: 8px; }
    .page-item:last-child .page-link { border-top-right-radius: 8px; border-bottom-right-radius: 8px; }
    .page-item.active .page-link { z-index: 1; color: #fff; background-color: #007bff; border-color: #007bff; }
    .page-item .page-link:hover { background-color: #e9ecef; }
    

    /* Tambahkan di dalam <style> */
    .chart-container {
        display: flex;
        flex-wrap: wrap; /* Agar responsif di HP */
        align-items: center;
        justify-content: center;
        gap: 20px;
    }
    .chart-canvas-container {
        position: relative;
        width: 250px;
        height: 250px;
    }
    .chart-legend-container {
        list-style-type: none;
        padding: 0 10px 0 0; /* Tambah padding kanan untuk ruang scrollbar */
        margin: 0;

        /* --- TAMBAHAN UNTUK SCROLL --- */
        max-height: 200px;  /* Batasi tinggi maksimal, sesuaikan jika perlu (cukup untuk sekitar 6-7 item) */
        overflow-y: auto; /* Tampilkan scrollbar vertikal jika konten melebihi max-height */
    }
    .chart-legend-container li {
        cursor: pointer;
        display: flex;
        align-items: center;
        margin-bottom: 5px;
        padding: 4px 8px;
        border-radius: 4px;
        transition: background-color 0.2s;
    }
    .chart-legend-container li:hover {
        background-color: #f1f1f1;
    }
    .legend-color-box {
        width: 15px;
        height: 15px;
        display: inline-block;
        margin-right: 10px;
        border-radius: 3px;
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
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    
    <h1><?= esc($title) ?></h1>

    <div class="visualization-section">
        <div class="card">
            <div class="card-header">Peta Persebaran</div>
            <div class="card-body">Peta akan ditampilkan di sini.</div>
        </div>
        <div class="card">
            <div class="card-header">Grafik Analisis Bantuan per Kecamatan</div>
            <div class="card-body chart-container">
                <div class="chart-canvas-container">
                    <canvas id="kecamatanChart"></canvas>
                </div>
                <div id="chart-legend" class="chart-legend-container"></div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span>Data Bantuan Sembako</span>
            <div>
                <a href="<?= site_url('admin/bankel/export') ?>" class="export-button">Export Excel</a>
                <a href="<?= site_url('admin/bankel/input') ?>" class="add-button">+ Tambah Data</a>
            </div>
        </div>
        <div class="card-body">
            
            <?php if ($message): ?>
                <div class="flash-message"><?= esc($message) ?></div>
            <?php endif; ?>
            
            <div class="filter-form">
                <form action="<?= site_url('admin/bankel') ?>" method="get">
                    <input type="text" name="keyword" placeholder="Cari NIK, Nama, Wilayah..." value="<?= esc($filters['keyword'] ?? '') ?>">
                    <input type="number" name="tahun" placeholder="Tahun" value="<?= esc($filters['tahun'] ?? '') ?>" style="width: 100px;">
                    <button type="submit">Cari</button>
                    <a href="<?= site_url('admin/bankel') ?>">Reset</a>
                </form>
            </div>
            
            <div style="overflow-x:auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Pilihan</th>
                            <th>No</th>
                            <th>NIK</th>
                            <th>Nama Lengkap</th>
                            <th>Kecamatan</th>
                            <th>Kelurahan</th>
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
                                    <td><?= esc($item['nama_kecamatan']) ?></td>
                                    <td><?= esc($item['nama_kelurahan']) ?></td>
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
                                <td colspan="8" style="text-align: center;">Tidak ada data yang cocok dengan kriteria pencarian Anda.</td>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('kecamatanChart');
    if (!ctx) return;

    // Fungsi untuk menghasilkan warna dinamis
    function generateDynamicColors(count) {
        let colors = [];
        for (let i = 0; i < count; i++) {
            const hue = (i * (360 / count)) % 360;
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

    fetch('<?= site_url('admin/bankel/chart-data') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) return;
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
});
</script>

<?= $this->endSection() ?>