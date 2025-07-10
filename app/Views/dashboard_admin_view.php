<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="<?= base_url('css/admin.css') ?>">
    
</head>
<body>
    <div class="container">
        <h1>Selamat Datang di Dashboard</h1>
        
        <h2>Halo, <?= esc($username) ?>!</h2>

        <p>Anda login sebagai: <strong><?= esc(ucfirst($role)) ?></strong></p>

        <?php if ($role === 'admin'): ?>
            <p>Anda bertugas untuk wilayah: <strong><?= esc($nama_kabupaten) ?></strong>.</p>
            <p>Anda hanya dapat mengelola data untuk wilayah ini.</p>
        <?php else: ?>
            <p>Anda memiliki akses penuh untuk memantau data dari semua wilayah.</p>
        <?php endif; ?>

    <hr>

    <h3>Pilih Sistem untuk Dikelola:</h3>
    <div class="system-choice-container">
        <a href="<?= site_url('admin/bankel') ?>" class="system-card">
            <h4>SIM-BANKEL</h4>
            <p>Monitoring Bantuan Sosial Keluarga</p>
        </a>
        <a href="<?= site_url('admin/monevkuep') ?>" class="system-card">
            <h4>SIM-MONEVKUEP</h4>
            <p>Monitoring & Evaluasi Bantuan UEP</p>
        </a>
        <a href="<?= site_url('admin/difabelkepri') ?>" class="system-card">
            <h4>SIM-DIFABELKEPRI</h4>
            <p>Monitoring Data Penyandang Difabel</p>
        </a>
    </div>
    <hr>
    <a href="<?= site_url('logout') ?>">Logout</a>
    </div>
</body>
</html>