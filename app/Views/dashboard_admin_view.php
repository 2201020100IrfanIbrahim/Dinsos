<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
Dashboard Admin
<?= $this->endSection() ?>

<?= $this->section('page_styles') ?>
<style>
    /* CSS Khusus untuk halaman dashboard */
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
    }
    .info-card {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        border-left: 5px solid;
    }
    .info-card.blue { border-color: #3498db; }
    .info-card.green { border-color: #2ecc71; }
    .info-card.orange { border-color: #f39c12; }
    
    .info-card h4 {
        margin: 0 0 10px 0;
        color: #6c757d;
        font-size: 16px;
    }
    .info-card .value {
        font-size: 28px;
        font-weight: 700;
        color: #343a40;
    }

    .welcome-card {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <div class="welcome-card">
        <h2>Halo, <?= esc($username) ?>!</h2>
        <p>Anda login sebagai <strong><?= esc(ucfirst($role)) ?></strong> untuk wilayah <strong><?= get_nama_kabupaten() ?></strong>. Selamat datang di dasbor Sistem Informasi Manajemen DINSOS Kepri.</p>
    </div>

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

    <?= $this->endSection() ?>