<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
Manajemen Jenis Disabilitas
<?= $this->endSection() ?>

<?= $this->section('page_styles') ?>
<style>
    /* Menggunakan style umum dari layout, ditambah beberapa style khusus */
    .card { background-color: #fff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); margin-bottom: 20px; }
    .card-header { padding: 15px 20px; background-color: #f8f9fa; border-bottom: 1px solid #dee2e6; font-weight: 600; display: flex; justify-content: space-between; align-items: center; }
    .card-body { padding: 20px; }
    .add-button { display: inline-block; padding: 8px 12px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; font-size: 14px; }
    
    .disability-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }
    .disability-card-header {
        padding: 12px 15px;
        background-color: #e9ecef;
        font-weight: 600;
        border-bottom: 1px solid #ddd;
    }
    .disability-card-body ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }
    .disability-card-body li {
        padding: 10px 15px;
        border-bottom: 1px solid #f1f1f1;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .disability-card-body li:last-child {
        border-bottom: none;
    }
    .action-links { white-space: nowrap; }
    .btn { display: inline-block; padding: 4px 8px; border-radius: 5px; text-decoration: none; color: white; font-size: 12px; margin: 0 2px; }
    .btn-edit { background-color: #007bff; }
    .btn-delete { background-color: #dc3545; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    
    <div class="card">
        <div class="back">
            <a href="<?= site_url('admin/difabelkepri') ?>" class="back-button" style="margin-bottom: 20px; display: inline-block;"> Kembali ke Dashboard</a>
        </div>
        <div class="card-header">
            <span>Manajemen Referensi Jenis Disabilitas</span>
            <a href="<?= site_url('admin/jenis-disabilitas/new') ?>" class="add-button">+ Tambah Jenis Baru</a>
        </div>
        <div class="card-body">
            <?php if (session()->getFlashdata('message')): ?>
                <div class="flash-message"><?= esc(session()->getFlashdata('message')) ?></div>
            <?php endif; ?>
            
            <div class="disability-grid">
                <?php if (!empty($grouped_jenis)): ?>
                    <?php foreach ($grouped_jenis as $golongan => $jenis_list): ?>
                        <div class="card" style="margin:0;">
                            <div class="disability-card-header">
                                <?= esc($golongan) ?>
                            </div>
                            <div class="disability-card-body">
                                <ul>
                                    <?php foreach ($jenis_list as $item): ?>
                                        <li>
                                            <span><?= esc($item['nama_jenis']) ?></span>
                                            <span class="action-links">
                                                <a href="<?= site_url('admin/jenis-disabilitas/edit/' . $item['id']) ?>" class="btn btn-edit" title="Edit">Edit</a>
                                                <a href="<?= site_url('admin/jenis-disabilitas/delete/' . $item['id']) ?>" class="btn btn-delete tombol-hapus" title="Hapus">Hapus</a>
                                            </span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Belum ada data referensi jenis disabilitas.</p>
                <?php endif; ?>
            </div>

        </div>
    </div>

<?= $this->endSection() ?>