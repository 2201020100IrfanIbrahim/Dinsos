<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Bantuan Sosial Keluarga</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #343a40;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #dee2e6;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #e9ecef;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .action-links a {
            margin-right: 10px;
            color: #007bff;
            text-decoration: none;
        }

        .action-links a:hover {
            text-decoration: underline;
        }

        .add-button {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .add-button:hover {
            background-color: #218838;
        }

        .flash-message {
            padding: 15px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 4px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1><?= esc($title) ?></h1>

        <?php if ($message): ?>
            <div class="flash-message">
                <?= esc($message) ?>
            </div>
        <?php endif; ?>

        <a href="<?= site_url('admin/bankel/input') ?>" class="add-button">+ Tambah Data Baru</a>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIK</th>
                    <th>Nama Lengkap</th>
                    <th>Kabupaten/Kota</th>
                    <th>Kecamatan</th>
                    <th>Kategori</th>
                    <th>Tahun</th>
                    <th>Status</th>
                    <th>Gambar</th>
                    <th>Koordinat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($bantuan)): ?>
                    <?php foreach ($bantuan as $index => $item): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= esc($item['nik']) ?></td>
                            <td><?= esc($item['nama_lengkap']) ?></td>
                            <td><?= esc($item['nama_kabupaten']) ?></td>
                            <td><?= esc($item['nama_kecamatan']) ?></td>
                            <td><?= esc($item['kategori_bantuan']) ?></td>
                            <td><?= esc($item['tahun_penerimaan']) ?></td>
                            <td><?= esc($item['status_penerimaan']) ?></td>
                            <!-- Kolom Gambar -->
                            <td>
                                <?php if (!empty($item['gambar'])): ?>
                                    <a href="<?= base_url('uploads/' . $item['gambar']) ?>" target="_blank" class="btn btn-sm btn-info">
                                        Lihat Gambar
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>

                            <!-- Kolom Koordinat -->
                            <td>
                                <?php if (!empty($item['koordinat'])): ?>
                                    <?php
                                    $coords = explode(',', $item['koordinat']);
                                    $lat = trim($coords[0] ?? '');
                                    $lng = trim($coords[1] ?? '');
                                    ?>
                                    <div>
                                        <small><strong>Lat:</strong> <?= $lat ?> <strong>Lng:</strong> <?= $lng ?></small><br>
                                        <a href="https://www.google.com/maps/place/<?= $lat ?>,<?= $lng ?>" class="btn btn-sm btn-success mt-1" target="_blank">Gmaps</a>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>

                            <td class="action-links">
                                <a href="<?= site_url('admin/bankel/edit/' . $item['id']) ?>">Edit</a>
                                <a href="<?= site_url('admin/bankel/delete/' . $item['id']) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" style="text-align: center;">Tidak ada data.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>