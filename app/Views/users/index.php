<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('page_styles') ?>
<style>
    /* Menggunakan style card yang konsisten */
    .card {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border: 1px solid #e9ecef;
        overflow: hidden;
    }
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        font-weight: 600;
    }
    .card-body {
        padding: 20px;
    }

    /* Tombol Tambah Admin Baru */
    .add-button {
        display: inline-block;
        padding: 8px 15px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-size: 14px;
        font-weight: 500;
        transition: background-color 0.2s;
    }
    .add-button:hover {
        background-color: #0056b3;
    }

    /* Tabel Profesional dengan Border */
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
        border-bottom: 1px solid #dee2e6;
        vertical-align: middle;
    }
    thead th {
        background-color: #e9ecef;
        font-weight: 600;
    }
    tbody tr:hover {
        background-color: #f1f1f1;
    }
    
    /* Tombol Aksi yang Rapi */
    .action-links {
        text-align: center;
    }
    .btn {
        display: inline-block;
        padding: 8px 12px;
        border-radius: 5px;
        text-decoration: none;
        color: white;
        font-size: 1rem;
        transition: transform 0.2s;
        line-height: 1;
    }
    .btn:hover {
        transform: scale(1.1);
    }
    .btn-edit {
        background-color: #007bff;
    }

    /* Pesan Notifikasi */
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: .25rem;
    }
    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h1><?= esc($title) ?></h1>

<?php if (session()->getFlashdata('message')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <span>Daftar Pengguna Admin</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Wilayah Tugas</th>
                        <th>Terakhir Diupdate</th>
                        <th class="action-links">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $index => $user): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= esc($user['username']) ?></td>
                                <td><?= esc($user['nama_kabupaten'] ?? '-') ?></td>
                                <td><?= !empty($user['updated_at']) ? date('d M Y, H:i', strtotime($user['updated_at'])) : '-' ?></td>
                                <td class="action-links">
                                    <a href="<?= site_url('admin/users/edit/' . $user['id']) ?>" class="btn btn-edit" title="Edit / Reset Password">
                                        📝
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center;">Tidak ada pengguna dengan peran admin.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>