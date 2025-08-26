<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
Dashboard Super Admin
<?= $this->endSection() ?>

<?= $this->section('page_styles') ?>
<style>
    /* Font & Warna Dasar */
    body {
        background-color: #f4f7f6;
    }
    .welcome-card {
        display: flex;
        flex-direction: column;
        background: linear-gradient(130deg, #e4abdfff 0%, #007BFF 100%);
        color: white;
        padding: 25px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.2);
        gap: 20px;
    }
    .welcome-card h2 {
        margin: 0;
        font-weight: 600;
    }
    .welcome-card p {
        margin: 5px 0 0;
        opacity: 0.9;
    }
    .data {
        display: flex;
        align-items: baseline;  /* Tambahkan baris ini */
        justify-content: flex-end;
        gap: 5px; /* Opsional: Memberi sedikit jarak antara angka dan tulisan */
    }
    .detail{
        color: #ffffffbd;
    }

    /* Grid & Card Styles */
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
    }
    .info-card, .card {
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border: none;
        overflow: hidden;
    }
    .info-card {
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        color: white;
    }
    .info-card h4 {
        font-size: 1rem;
        font-weight: 500;
    }
    .info-card .value {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
        text-align: right;
    }
    .info-card a {
        color: white;
        text-decoration: none;
        font-weight: 500;
        opacity: 0.8;
        transition: opacity 0.2s;
    }
    .info-card a:hover {
        opacity: 1;
    }

    /* Warna Info Card */
    .info-card.blue { background: linear-gradient(45deg, #e079ddff 0%, #007BFF 100%); box-shadow: 0 4px 12px rgba(0,0,0,0.05)}
    .info-card.green { background: linear-gradient(45deg, #cde079ff 0%, #28a745 100%); box-shadow: 0 4px 12px rgba(0,0,0,0.05)}
    .info-card.orange { background: linear-gradient(45deg, #e3bb8dff 0% , #fd7e14 100%); box-shadow: 0 4px 12px rgba(0,0,0,0.05)}

    /* Chart Card Styles */
    .card-header {
        background-color: #f8f9fa;
        padding: 15px 20px;
        border-bottom: 1px solid #dee2e6;
        font-weight: 600;
    }
    .card-body {
        padding: 20px;
    }
    .chart-container {
        height: 350px;
        position: relative;
    }
    .table-responsive {
        overflow-x: auto;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #e9ecef;
    }
    thead th {
        background-color: #f8f9fa;
    }
    tbody tr:hover {
        background-color: #f1f1f1;
    }
    .badge {
        padding: 4px 8px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        color: #fff;
    }
    .badge-bankel { background-color: #007bff; }
    .badge-difabel { background-color: #28a745; }
    .badge-kuep { background-color: #fd7e14; }

    /* ============================================= */
    /* STYLE UNTUK TAMPILAN MOBILE             */
    /* ============================================= */
    @media (max-width: 768px) {
        /* Atur ulang layout untuk welcome card */
        .welcome-card {
            flex-direction: column; /* Susun dari atas ke bawah */
        }

        /* Perkecil ukuran teks dan angka agar tidak terlalu besar */
        .welcome-card h2 {
            font-size: 1.8rem;
        }
        .info-card .value {
            font-size: 2rem;
        }

        /* Pastikan grid selalu menjadi 1 kolom di mobile */
        .dashboard-grid {
            grid-template-columns: 1fr;
        }

        /* Beri sedikit ruang pada card body */
        .card-body {
            padding: 15px;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <div class="welcome-card">
        <div class="welcome">
            <h2 style="font-size: 2.5rem; font-weight:bold;">Halo, <?= esc(session()->get('username')) ?>!</h2>
            <p>Anda login sebagai <strong>Super Admin</strong>. Anda memiliki hak akses penuh untuk memantau dan mengelola seluruh data sistem.</p>
        </div>

        <div class="dashboard-grid">
            <div class="info-card blue">
                <div>
                    <h4>Total Data SIM-BANKEL</h4>
                    <a href="<?= site_url('admin/bankel') ?>">Lihat Detail &rarr;</a>
                </div>
                <div class="data">
                    <p class="value"><?= $total_bankel ?></p>
                    <p class="detail">Data</p>
                </div>

            </div>
            <div class="info-card green">
                <div>
                    <h4>Total Data SIM-DIFABELKEPRI</h4>
                    <a href="<?= site_url('admin/difabelkepri') ?>">Lihat Detail &rarr;</a>
                </div>
                <div class="data">
                    <p class="value"><?= $total_difabel ?></p>
                    <p class="detail">Data</p>
                </div>
            </div>
            <div class="info-card orange">
                <div>
                    <h4>Total Data SIM-MONEVKUEP</h4>
                    <a href="<?= site_url('admin/monevkuep') ?>">Lihat Detail &rarr;</a>
                </div>
                <div class="data">
                    <p class="value"><?= $total_monevkuep ?></p>
                    <p class="detail">Data</p>
                </div>
            </div>
        </div>
    </div>

    <div class="distribusi" >
        <div class="card" style="margin-top: 25px;">
            <div class="card-header">Distribusi Data Bantuan per Wilayah</div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="wilayahChart"></canvas>
                </div>
            </div>
        </div>
    
        <div class="chart-data">
            <div class="dashboard-grid" style="margin-top: 25px;">
                <div class="card">
                    <div class="card-header">BANKEL: Bantuan per Tahun</div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="bankelChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">DIFABEL: per Golongan</div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="difabelChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">MONEVKUEP: per Jenis Usaha</div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="kuepChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>

<?= $this->section('page_scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data untuk chart ini perlu disiapkan dari Controller
        // Contoh: $data['chart_wilayah'] = json_encode([...]);
        
        // 1. Chart Distribusi per Wilayah
        const ctxWilayah = document.getElementById('wilayahChart');
        if (ctxWilayah) {
            // Ganti URL ini dengan endpoint yang sesuai di DashboardController Anda
            fetch('<?= site_url('dashboard/chart-wilayah') ?>') 
                .then(response => response.json())
                .then(data => {
                    new Chart(ctxWilayah, {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [
                                { label: 'Bankel', data: data.bankel, backgroundColor: '#007bff' },
                                { label: 'Difabel', data: data.difabel, backgroundColor: '#28a745' },
                                { label: 'KUEP', data: data.kuep, backgroundColor: '#fd7e14' }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: { x: { stacked: true }, y: { stacked: true, beginAtZero: true } },
                            plugins: { 
                                legend: { position: 'top' }, 
                                datalabels: {
                                    formatter: (value) => {
                                        // Tampilkan angka nilainya
                                        return value;
                                    },
                                    color: '#ffffff', // Warna font putih
                                    font: {
                                        size: 14
                                    }
                                } 
                            }
                        },
                        plugins: [ChartDataLabels]
                    });
                });
        }

        // 2. Chart Bankel per Tahun
        const ctxBankel = document.getElementById('bankelChart');
        if (ctxBankel) {
            fetch('<?= site_url('dashboard/chart-bankel-by-year') ?>')
                .then(response => response.json())
                .then(data => {
                    new Chart(ctxBankel, {
                        type: 'doughnut',
                        data: {
                            labels: data.labels,
                            datasets: [{ data: data.values, backgroundColor: ['#007bff', '#6c757d', '#17a2b8', '#ffc107', '#dc3545'] }]
                        },
                        options: {  responsive: true, 
                                    maintainAspectRatio: false, 
                                    plugins: { 
                                        legend: { position: 'top' },
                                        datalabels: {
                                            formatter: (value) => {
                                                // Tampilkan angka nilainya
                                                return value;
                                            },
                                            color: '#ffffff', // Warna font putih
                                            font: {
                                                size: 14
                                            }
                                        } 
                                    } 
                                },plugins: [ChartDataLabels]
                    });
                });
        }

        // 3. Chart Difabel per Golongan
        const ctxDifabel = document.getElementById('difabelChart');
        if (ctxDifabel) {
            fetch('<?= site_url('dashboard/chart-difabel-by-golongan') ?>')
                .then(response => response.json())
                .then(data => {
                    new Chart(ctxDifabel, {
                        type: 'pie',
                        data: {
                            labels: data.labels,
                            datasets: [{ data: data.values, backgroundColor: ['#28a745', '#bbc920ff', '#15b7f7ff', '#7946d8ff', '#e83e8c'] }]
                        },
                        options: {  responsive: true, 
                                    maintainAspectRatio: false,    
                                    plugins: { 
                                        legend: { position: 'top' },
                                        datalabels: {
                                            formatter: (value) => {
                                                // Tampilkan angka nilainya
                                                return value;
                                            },
                                            color: '#ffffff', // Warna font putih
                                            font: {
                                                size: 14
                                            }
                                        }
                                    } 
                                },plugins: [ChartDataLabels]
                    });
                });
        }

        // 4. Chart KUEP per Jenis Usaha
        const ctxKuep = document.getElementById('kuepChart');
        if (ctxKuep) {
            fetch('<?= site_url('dashboard/chart-monevkuep-by-usaha') ?>')
                .then(response => response.json())
                .then(data => {
                    new Chart(ctxKuep, {
                        type: 'doughnut',
                        data: {
                            labels: data.labels,
                            datasets: [{ data: data.values, backgroundColor: ['#fd7e14', '#ffc107', '#6610f2', '#17a2b8'] }]
                        },
                        options: { responsive: true, maintainAspectRatio: false, 
                            plugins: { 
                            legend: { 
                                position: 'top' }, 
                                datalabels: {
                                    formatter: (value) => {
                                        // Tampilkan angka nilainya
                                        return value;
                                    },
                                    color: '#ffffff', // Warna font putih
                                    font: {
                                        size: 14
                                    }
                                } 
                            } 
                        },plugins: [ChartDataLabels]
                    });
                });
        }
    });
</script>
<?= $this->endSection() ?>