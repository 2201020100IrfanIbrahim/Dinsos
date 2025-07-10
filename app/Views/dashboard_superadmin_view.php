<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Super Admin</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background-color: #f4f7f6; }
        .container { max-width: 900px; margin: auto; background: #fff; border: 1px solid #ddd; padding: 20px; border-radius: 5px; }
        h1 { color: #c0392b; }
    </style> 
</head>
<body>
    <div class="container">
        <h1>Dashboard Super Admin</h1>

        <h2>Halo, <?= esc($username) ?>!</h2>

        <p>Anda memiliki akses penuh untuk memantau data dari semua wilayah.</p>

        <p>Contoh: Laporan Bantuan Seluruh Wilayah, Manajemen User, dll.</p>

        <hr>
        <a href="<?= site_url('/logout') ?>">Logout</a>
    </div>
</body>
</html>