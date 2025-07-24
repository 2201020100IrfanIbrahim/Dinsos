<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
Manajemen Data Difabel
<?= $this->endSection() ?>

<?= $this->section('page_styles') ?>
<style>
    /* Style ini bisa digunakan bersama karena tampilannya konsisten */
    .card { background-color: #fff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); margin-bottom: 30px; overflow: hidden; }
    .card-header { padding: 15px 20px; background-color: #f8f9fa; border-bottom: 1px solid #dee2e6; font-weight: 600; display: flex; justify-content: space-between; align-items: center; }
    .card-body { padding: 20px; }
    .visualization-section { display: grid; grid-template-columns: 1fr; gap: 30px; }
    .visualization-section .card-body { min-height: 200px; display:flex; justify-content:center; align-items:center; color: #999; }
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
    .filter-form a { background-color: #6c757d; color: white; border: 1px solid #6c757d; font-size: 14px; display:inline-flex; align-items:center; }
    .pagination-container { margin-top: 20px; display: flex; justify-content: center; }
    .pagination { display: inline-flex; list-style-type: none; padding: 0; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    .page-item .page-link { color: #007bff; padding: 10px 15px; text-decoration: none; transition: background-color .3s; border: 1px solid #ddd; margin: 0 -1px 0 0; display: block; }
    .page-item:first-child .page-link { border-top-left-radius: 8px; border-bottom-left-radius: 8px; }
    .page-item:last-child .page-link { border-top-right-radius: 8px; border-bottom-right-radius: 8px; }
    .page-item.active .page-link { z-index: 1; color: #fff; background-color: #007bff; border-color: #007bff; }
    .page-item .page-link:hover { background-color: #e9ecef; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    
    <h1><?= esc($title ?? 'Manajemen Data Difabel') ?></h1>

    <div class="visualization-section">
        <div class="card">
            <div class="card-header">Peta Persebaran</div>
            <div class="card-body">Peta akan ditampilkan di sini.</div>
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

        // (skrip lainnya seperti tombol back)
    });
</script>
<?= $this->endSection() ?>