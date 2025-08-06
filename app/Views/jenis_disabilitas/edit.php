<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
Tambah Jenis Disabilitas
<?= $this->endSection() ?>

<?= $this->section('page_styles') ?>
<style>
    /* Menggunakan style yang sama dengan form lain */
    .form-card { background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); max-width: 600px; margin: auto; }
    .form-group { margin-bottom: 20px; }
    label { display: block; margin-bottom: 8px; font-weight: 500; }
    .form-input, select { width: 100%; padding: 10px 15px; box-sizing: border-box; border: 1px solid #ced4da; border-radius: 8px; font-size: 14px; }
    .form-actions { text-align: right; margin-top: 30px; }
    .submit-button { padding: 12px 30px; background-color: #007bff; color: #fff; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; }
    .error-box { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; margin-bottom: 20px; border-radius: 8px; }
    .required-star { color: #dc3545; }
</style>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="form-card">
    <h2>Edit Jenis Disabilitas</h2>
    <hr style="margin: 20px 0;">

    <?php if (session()->get('errors')): ?>
        <div class="error-box">
            <ul>
            <?php foreach (session()->get('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <form action="<?= site_url('admin/jenis-disabilitas/update/' . $jenis['id']) ?>" method="post">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="nama_jenis">Nama Jenis <span class="required-star">*</span></label>
            <input type="text" name="nama_jenis" class="form-input" value="<?= old('nama_jenis', $jenis['nama_jenis']) ?>" required>
        </div>
        <div class="form-group">
            <label for="golongan">Golongan Induk <span class="required-star">*</span></label>
            <select name="golongan" class="form-input" required>
                <option value="Disabilitas Fisik" <?= old('golongan', $jenis['golongan']) == 'Disabilitas Fisik' ? 'selected' : '' ?>>Disabilitas Fisik</option>
                <option value="Disabilitas Sensorik" <?= old('golongan', $jenis['golongan']) == 'Disabilitas Sensorik' ? 'selected' : '' ?>>Disabilitas Sensorik</option>
                <option value="Disabilitas Mental" <?= old('golongan', $jenis['golongan']) == 'Disabilitas Mental' ? 'selected' : '' ?>>Disabilitas Mental</option>
                <option value="Disabilitas Intelektual" <?= old('golongan', $jenis['golongan']) == 'Disabilitas Intelektual' ? 'selected' : '' ?>>Disabilitas Intelektual</option>
            </select>
        </div>
        <div class="form-actions">
            <a href="<?= site_url('admin/jenis-disabilitas') ?>" class="back-button">Kembali</a>
            <button type="submit" class="submit-button" style="background-color: #ffc107; color: #212529;">Update</button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>