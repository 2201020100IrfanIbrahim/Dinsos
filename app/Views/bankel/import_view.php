<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
Import Data dari Excel
<?= $this->endSection() ?>

<?= $this->section('page_styles') ?>
<style>
    /* Menggunakan style yang sama dengan form lain */
    .card { background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); }
    .import-instructions {
        background-color: #e9ecef;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
        line-height: 1.6;
    }
    .import-instructions strong {
        color: #343a40;
    }
    .form-group {
        margin-top: 20px;
    }
    input[type="file"] {
        border: 1px solid #ced4da;
        padding: 10px;
        border-radius: 8px;
        width: 100%;
    }
    .submit-button {
        display: inline-block;
        margin-top: 20px;
        padding: 12px 30px;
        background-color: #28a745; /* Warna hijau untuk import */
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
    }
    .alert {
        padding: 15px;
        border-radius: 8px;
        margin-top: 20px;
    }
    .alert-success { background-color: #d4edda; color: #155724; }
    .alert-danger { background-color: #f8d7da; color: #721c24; }
</style>
<?= $this->endSection() ?>


<?= $this->section('content') ?>

<div class="card">
    <h3>Import Data Bantuan dari Excel</h3>

    <div class="import-instructions">
        <p>Silakan upload file Excel (.xlsx) dengan format yang telah ditentukan.</p>
        <p><strong>Penting:</strong> Pastikan urutan dan nama kolom di file Excel Anda sudah benar untuk menghindari kegagalan import.</p>
        <a href="#">Download Template Format Excel</a>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('message') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors_list')): ?>
        <div class="alert alert-danger">
            <strong>Gagal mengimpor <?= session()->getFlashdata('fail_count') ?> data.</strong>
            <p>Berikut adalah beberapa contoh error:</p>
            <ul>
                <?php foreach (session()->get('errors_list') as $errorMessage => $lineNumbers): ?>
                    <li>
                        <strong><?= esc($errorMessage) ?>:</strong>
                        pada data ke- <?= implode(', ', $lineNumbers) ?>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= site_url('admin/bankel/process-import') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="form-group">
            <label for="excel_file"><strong>Pilih File Excel (.xlsx)</strong></label>
            <input type="file" name="excel_file" id="excel_file" class="form-input" required accept=".xlsx, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
        </div>
        <button type="submit" class="submit-button">Upload dan Proses Import</button>
    </form>
    
</div>

<?= $this->endSection() ?>