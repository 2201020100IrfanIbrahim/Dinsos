<!DOCTYPE html>
<html lang="id">

<head>
    <title>Tambah Data Bantuan</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: #f4f4f4;
        }

        .form-container {
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fff;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],

        select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
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
        <h2>Formulir Input Data Bantuan</h2>

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

        <form action="<?= site_url('admin/bankel/create') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="nik">NIK Penerima</label>
                <input type="text" name="nik" id="nik" value="<?= old('nik') ?>" required>
            </div>

            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap Penerima</label>
                <input type="text" name="nama_lengkap" id="nama_lengkap" value="<?= old('nama_lengkap') ?>" required>
            </div>

            <div class="form-group">
                <label for="id_kecamatan">Kecamatan</label>
                <select name="id_kecamatan" id="id_kecamatan" required>
                    <option value="">-- Pilih Kecamatan --</option>

                    <?php foreach ($kecamatan_list as $kecamatan): ?>
                        <option value="<?= esc($kecamatan['id']) ?>" <?= old('id_kecamatan') == $kecamatan['id'] ? 'selected' : '' ?>>
                            <?= esc($kecamatan['nama_kecamatan']) ?>
                        </option>
                    <?php endforeach; ?>

                </select>
            </div>

            <div class="form-group">
                <label for="id_kelurahan">Kelurahan/Desa</label>
                <select name="id_kelurahan" id="id_kelurahan" required>
                    <option value="">-- Pilih Kelurahan --</option>
                </select>
            </div>

            <div class="form-group">
                <label for="dusun">Dusun</label>
                <input type="text" name="dusun" id="dusun" value="<?= old('dusun') ?>">
            </div>

            <div class="form-group">
                <label for="rt">RT</label>
                <input type="text" name="rt" id="rt" value="<?= old('rt') ?>" required>
            </div>

            <div class="form-group">
                <label for="rw">RW</label>
                <input type="text" name="rw" id="rw" value="<?= old('rw') ?>" required>
            </div>

            <div class="form-group">
                <label for="alamat_lengkap">Alamat Lengkap</label>
                <input type="text" name="alamat_lengkap" id="alamat_lengkap" value="<?= old('alamat_lengkap') ?>">
            </div>

            <div class="form-group">
                <label for="kategori_bantuan">Jenis Bantuan</label>
                <input type="text" name="kategori_bantuan" id="kategori_bantuan" value="<?= old('kategori_bantuan') ?>" required>
            </div>

            <div class="form-group">
                <label for="tahun_penerimaan">Tahun Penerimaan</label>
                <input type="number" name="tahun_penerimaan" id="tahun_penerimaan" value="<?= old('tahun_penerimaan', date('Y')) ?>" required>
            </div>

            <div class="form-group">
                <label for="gambar">Upload Gambar Lokasi (opsional, JPG dengan GPS)</label>
                <input type="file" name="gambar" id="gambar" accept="image/jpeg">
            </div>

            <button type="submit">Simpan Data</button>
        </form>
    </div>
    <script>
        // Jalankan script setelah halaman selesai dimuat
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil elemen dropdown kecamatan dan kelurahan
            const kecamatanSelect = document.getElementById('id_kecamatan');
            const kelurahanSelect = document.getElementById('id_kelurahan');

            // Tambahkan event listener saat pilihan kecamatan berubah
            kecamatanSelect.addEventListener('change', function() {
                // Ambil ID kecamatan yang dipilih
                const kecamatanId = this.value;

                // Kosongkan dulu pilihan kelurahan yang lama
                kelurahanSelect.innerHTML = '<option value="">-- Pilih Kelurahan --</option>';

                // Jika kecamatan dipilih (bukan pilihan default)
                if (kecamatanId) {
                    // Buat URL untuk meminta data ke controller
                    const url = `<?= site_url('admin/bankel/get-kelurahan/') ?>${kecamatanId}`;

                    // Lakukan permintaan data (AJAX) menggunakan fetch API
                    fetch(url)
                        .then(response => response.json()) // Ubah response menjadi JSON
                        .then(data => {
                            // Loop setiap data kelurahan yang diterima
                            data.forEach(kelurahan => {
                                // Buat elemen option baru
                                const option = document.createElement('option');
                                option.value = kelurahan.id;
                                option.textContent = kelurahan.nama_kelurahan;

                                // Tambahkan option baru ke dropdown kelurahan
                                kelurahanSelect.appendChild(option);
                            });
                        });
                }
            });
        });
    </script>
</body>

</html>