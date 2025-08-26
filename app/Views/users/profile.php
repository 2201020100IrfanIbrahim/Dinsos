<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
Edit Profil Saya
<?= $this->endSection() ?>

<?= $this->section('page_styles') ?>
<style>
    /* Anda bisa salin style dari users/edit.php */
    .card { background-color: #fff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid #e9ecef; }
    .card-header { padding: 15px 20px; background-color: #f8f9fa; border-bottom: 1px solid #e9ecef; font-weight: 600; }
    .card-body { padding: 30px; }
    .form-group { margin-bottom: 20px; }
    label { display: block; margin-bottom: 8px; font-weight: 500; }
    .form-input { width: 100%; padding: 10px 15px; box-sizing: border-box; border: 1px solid #ced4da; border-radius: 8px; font-size: 14px; }
    .help { display: block; margin-top: 5px; font-size: 0.85rem; color: #6c757d; }
    .form-actions { text-align: right; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e9ecef; }
    .submit-button { padding: 12px 30px; background-color: #007bff; color: #fff; border: none; border-radius: 8px; cursor: pointer; }
    .password-wrapper { position: relative; }
    .password-wrapper .toggle-password { position: absolute; top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer; color: #6c757d; }
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
    .error-box { /* Style ini mungkin sudah ada, pastikan saja */
        color: #721c24;
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 8px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h1>Edit Profil Saya</h1>

<div class="card">
    <div class="card-header">Form Edit Profil</div>
    <div class="card-body">
        <?php if (session()->getFlashdata('message')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
        <?php endif; ?>
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
        
        <form action="<?= site_url('admin/profile/update') ?>" method="post">
            <?= csrf_field() ?>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-input" value="<?= old('username', $user['username']) ?>" required>
            </div>
            <hr>
            <p>Kosongkan bagian di bawah ini jika Anda tidak ingin mengubah password.</p>
            <div class="form-group">
                <label for="old_password">Password Lama (untuk konfirmasi)</label>
                <div class="password-wrapper">
                    <input type="password" name="old_password" id="old_password" class="form-input">
                    <i class="fas fa-eye toggle-password" id="toggleOldPassword"></i>
                </div>
            </div>
            <div class="form-group">
                <label for="new_password">Password Baru</label>
                <div class="password-wrapper">
                    <input type="password" name="new_password" id="new_password" class="form-input">
                    <i class="fas fa-eye toggle-password" id="toggleNewPassword"></i>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="submit-button">Update Profil</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('page_scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function setupPasswordToggle(toggleId, passwordId) {
            const toggle = document.getElementById(toggleId);
            const password = document.getElementById(passwordId);
            if (!toggle || !password) return;

            toggle.addEventListener('click', function () {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.classList.toggle('fa-eye-slash');
            });
        }
        setupPasswordToggle('toggleOldPassword', 'old_password');
        setupPasswordToggle('toggleNewPassword', 'new_password');
    });
</script>
<?= $this->endSection() ?>