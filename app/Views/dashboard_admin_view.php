<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .container { max-width: 800px; margin: auto; border: 1px solid #ddd; padding: 20px; border-radius: 5px; }
        a { color: #007bff; }
    </style> 
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
        <a href="<?= site_url('/logout') ?>">Logout</a>
    </div>
</body>
</html>