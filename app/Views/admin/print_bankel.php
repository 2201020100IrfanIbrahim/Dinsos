<html>

<head>
    <title>Cetak Semua Data Bantuan</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        h2 {
            text-align: center;
        }
    </style>
</head>

<body onload="window.print()">
    <h2>Rekap Data Bantuan Sosial</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIK</th>
                <th>Nama</th>
                <th>Kabupaten</th>
                <th>Kecamatan</th>
                <th>Kategori</th>
                <th>Tahun</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($all_data as $item): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= esc($item['nik']) ?></td>
                    <td><?= esc($item['nama_lengkap']) ?></td>
                    <td><?= esc($item['nama_kabupaten']) ?></td>
                    <td><?= esc($item['nama_kecamatan']) ?></td>
                    <td><?= esc($item['kategori_bantuan']) ?></td>
                    <td><?= esc($item['tahun_penerimaan']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>