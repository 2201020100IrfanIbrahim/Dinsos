<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('page_styles') ?>
<style>
    /* Menggunakan style yang konsisten dengan form input lainnya */
    .card {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border: 1px solid #e9ecef;
    }
    .card-header {
        padding: 15px 20px;
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        font-weight: 600;
    }
    .card-body {
        padding: 30px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
    }
    .form-input, select, textarea {
        width: 100%;
        padding: 10px 15px;
        box-sizing: border-box;
        border: 1px solid #ced4da;
        border-radius: 8px;
        font-size: 14px;
    }
    .help {
        display: block;
        margin-top: 5px;
        font-size: 0.85rem;
        color: #6c757d;
    }
    .form-actions {
        text-align: right;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #e9ecef;
    }
    .submit-button {
        padding: 12px 30px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    .submit-button:hover {
        background-color: #0056b3;
    }
    .back-link {
        display: inline-block;
        margin-bottom: 20px;
        color: #007bff;
        text-decoration: none;
        font-weight: 500;
    }
    .back-link:hover {
        text-decoration: underline;
    }
    .error-box {
        color: #721c24;
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 8px;
    }
    .required-star { color: #dc3545; }

    /* Tambahkan di dalam <style> */
    .password-wrapper {
        position: relative;
    }
    .password-wrapper .toggle-password {
        position: absolute;
        top: 50%;
        right: 15px;
        transform: translateY(-50%);
        cursor: pointer;
        color: #6c757d;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="back">
    <a href="<?= site_url('admin/users') ?>" class="back-button" style="margin-bottom: 20px; display: inline-block;"> Kembali</a>
</div>
<h1><?= esc($title) ?></h1>


<div class="card">
    <div class="card-header">Form Edit Pengguna</div>
    <div class="card-body">
        <?php if (session()->get('errors')): ?>
            <div class="error-box">
                <strong>Gagal menyimpan:</strong>
                <ul>
                <?php foreach (session()->get('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
                </ul>
            </div>
        <?php endif; ?>
        
<form action="<?= site_url('admin/users/update/' . $user['id']) ?>" method="post">
    <?= csrf_field() ?>
    <div class="form-group">
        <label for="username">Username <span class="required-star">*</span></label>
        <input type="text" name="username" id="username" class="form-input" value="<?= old('username', $user['username']) ?>" required>
    </div>
    <div class="form-group">
        <label for="password">Password Baru</label>
        <div class="password-wrapper">
            <input type="password" name="password" id="password" class="form-input">
            <i class="fas fa-eye toggle-password" id="togglePassword"></i>
        </div>
        <small class="help">Biarkan kosong jika tidak ingin mengubah password.</small>
    </div>

    <div id="superadmin-confirm-section" style="display: none;">
        <hr>
        <p>Untuk mengubah password, silakan masukkan password Anda sebagai konfirmasi.</p>
        <div class="form-group">
            <label for="superadmin_password">Konfirmasi Password Superadmin <span class="required-star">*</span></label>
            <div class="password-wrapper">
                <input type="password" name="superadmin_password" id="superadmin_password" class="form-input">
                <i class="fas fa-eye toggle-password" id="toggleSuperadminPassword"></i>
            </div>
        </div>
    </div>
    <div class="form-actions">
        <button type="submit" class="submit-button">Update Pengguna</button>
    </div>
</form>

<script>
// Script kecil untuk menampilkan/menyembunyikan kolom konfirmasi
document.addEventListener('DOMContentLoaded', function() {
    const newPasswordField = document.getElementById('password');
    const confirmSection = document.getElementById('superadmin-confirm-section');
    const confirmPasswordField = document.getElementById('superadmin_password');

    newPasswordField.addEventListener('keyup', function() {
        if (this.value.length > 0) {
            confirmSection.style.display = 'block';
            confirmPasswordField.required = true;
        } else {
            confirmSection.style.display = 'none';
            confirmPasswordField.required = false;
        }
    });

    function setupPasswordToggle(toggleId, passwordId) {
    const toggle = document.getElementById(toggleId);
    const password = document.getElementById(passwordId);
    if (!toggle || !password) return;

    toggle.addEventListener('click', function () {
        // ganti tipe input
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        // ganti ikon mata
        this.classList.toggle('fa-eye-slash');
    });
}

setupPasswordToggle('togglePassword', 'password');
setupPasswordToggle('toggleSuperadminPassword', 'superadmin_password');
});
</script>
<?= $this->endSection() ?>