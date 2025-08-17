<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
Dashboard Super Admin
<?= $this->endSection() ?>

<?= $this->section('page_styles') ?>
<style>
    /* (Anda bisa salin style dari dashboard_admin_view.php) */
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <div class="welcome-card">
        <h2>Halo, <?= esc($username) ?>!</h2>
        <p>Anda login sebagai <strong>Super Admin</strong>. Anda memiliki hak akses penuh untuk memantau dan mengelola seluruh data sistem.</p>
    </div>

    <h3>Ringkasan Data Seluruh Wilayah</h3>
    <div class="dashboard-grid">
        <div class="info-card blue">
            <h4>Total Bantuan Terdata (SIM-BANKEL)</h4>
            <p class="value"><?= $total_bankel ?></p>
        </div>
        <div class="info-card green">
            <h4>Total Penerima (SIM-DIFABELKEPRI)</h4>
            <p class="value"><?= $total_difabel ?></p>
        </div>
        <div class="info-card orange">
            <h4>Bantuan UEP (SIM-MONEVKUEP)</h4>
            <p class="value"><?= $total_monevkuep ?></p>
        </div>
    </div>
    
    <div class="card" style="margin-top: 20px;">
        <div class="card-header">
            <h4>Fitur Tambahan</h4>
        </div>
        <div class="card-body">
            <p>Di sini nanti akan ada fitur manajemen user, laporan terpusat, dan lainnya.</p>
        </div>
    </div>

<?= $this->endSection() ?>