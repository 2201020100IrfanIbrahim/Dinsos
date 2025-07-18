<!DOCTYPE html>
<html lang="id">

<head>
    <title>Edit Data Bantuan</title>
    <style>
        body {
            font-family: sans-serif;
        }

        .form-container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input,
        select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        button {
            padding: 10px 15px;
            background-color: #ffc107;
            color: black;
            border: none;
            cursor: pointer;
        }

        .error-box {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div class="form-container">

        <h2>Formulir Edit Data Bantuan</h2>
        <?php if (session()->get('errors')): ?>
            <div class="error-box">
                <strong>Gagal menyimpan data:</strong>
                <ul>
                    <?php foreach (session()->get('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('admin/bankel/update/' . $bantuan['id']) ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="nik">NIK Penerima</label>
                <input type="text" name="nik" id="nik" value="<?= old('nik', $bantuan['nik']) ?>" required>
            </div>
            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap Penerima</label>
                <input type="text" name="nama_lengkap" id="nama_lengkap" value="<?= old('nama_lengkap', $bantuan['nama_lengkap']) ?>" required>
            </div>

            <div class="form-group">
                <label for="id_kecamatan">Kecamatan</label>
                <select name="id_kecamatan" id="id_kecamatan" required>
                    <option value="">-- Pilih Kecamatan --</option>
                    <?php foreach ($kecamatan_list as $kecamatan): ?>
                        <option value="<?= $kecamatan['id'] ?>" <?= (old('id_kecamatan', $bantuan['id_kecamatan']) == $kecamatan['id']) ? 'selected' : '' ?>>
                            <?= $kecamatan['nama_kecamatan'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="id_kelurahan">Kelurahan/Desa</label>
                <select name="id_kelurahan" id="id_kelurahan" required>
                    <option value="">-- Pilih Kelurahan --</option>
                    <?php foreach ($kelurahan_list as $kelurahan): ?>
                        <option value="<?= $kelurahan['id'] ?>" <?= (old('id_kelurahan', $bantuan['id_kelurahan']) == $kelurahan['id']) ? 'selected' : '' ?>>
                            <?= $kelurahan['nama_kelurahan'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="dusun">Dusun</label>
                <input type="text" name="dusun" id="dusun" value="<?= old('dusun', $bantuan['dusun']) ?>">
            </div>
            <div class="form-group">
                <label for="rt">RT</label>
                <input type="text" name="rt" id="rt" value="<?= old('rt', $bantuan['rt']) ?>" required>
            </div>
            <div class="form-group">
                <label for="rw">RW</label>
                <input type="text" name="rw" id="rw" value="<?= old('rw', $bantuan['rw']) ?>" required>
            </div>
            <div class="form-group">
                <label for="alamat_lengkap">Alamat Lengkap</label>
                <input type="text" name="alamat_lengkap" id="alamat_lengkap" value="<?= old('alamat_lengkap', $bantuan['alamat_lengkap']) ?>">
            </div>
            <div class="form-group">
                <label for="kategori_bantuan">Jenis Bantuan</label>
                <input type="text" name="kategori_bantuan" id="kategori_bantuan" value="<?= old('kategori_bantuan', $bantuan['kategori_bantuan']) ?>" required>
            </div>
            <div class="form-group">
                <label for="tahun_penerimaan">Tahun Penerimaan</label>
                <input type="number" name="tahun_penerimaan" id="tahun_penerimaan" value="<?= old('tahun_penerimaan', $bantuan['tahun_penerimaan']) ?>" required>
            </div>
            <!-- Tambahan input untuk upload gambar -->
            <div class="form-group">
                <label for="gambar">Upload Gambar Lokasi (Opsional)</label>
                <input type="file" name="gambar" id="gambar" class="form-control">
            </div>

            <button type="submit">Update Data</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const kecamatanSelect = document.getElementById('id_kecamatan');
            const kelurahanSelect = document.getElementById('id_kelurahan');

            kecamatanSelect.addEventListener('change', function() {
                const kecamatanId = this.value;
                kelurahanSelect.innerHTML = '<option value="">-- Memuat... --</option>';

                if (kecamatanId) {
                    const url = `<?= site_url('admin/bankel/get-kelurahan/') ?>${kecamatanId}`;
                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            kelurahanSelect.innerHTML = '<option value="">-- Pilih Kelurahan --</option>';
                            data.forEach(kelurahan => {
                                const option = document.createElement('option');
                                option.value = kelurahan.id;
                                option.textContent = kelurahan.nama_kelurahan;
                                kelurahanSelect.appendChild(option);
                            });
                        });
                } else {
                    kelurahanSelect.innerHTML = '<option value="">-- Pilih Kelurahan --</option>';
                }
            });
        });
    </script>
</body>

</html>