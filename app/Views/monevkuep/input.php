<!DOCTYPE html>
<html lang="id">
<head>
    <title>Tambah Data MONEVKUEP</title>
    <style>
        body { font-family: sans-serif; }
        .form-container { max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input, select { width: 100%; padding: 8px; box-sizing: border-box; }
        button { padding: 10px 15px; background-color: #007bff; color: white; border: none; cursor: pointer; }
        small.help { color:#666; display:block; margin-top:4px; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Formulir Input Data MONEVKUEP</h2>

        <form action="<?= site_url('admin/monevkuep/create') ?>" method="post">
            <?= csrf_field() ?>

            <!-- Identitas dasar -->
            <div class="form-group">
                <label for="nik">NIK</label>
                <input type="text" name="nik" id="nik" required>
            </div>

            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" id="nama_lengkap" required>
            </div>

            <div class="form-group">
                <label for="jenis_kelamin">Jenis Kelamin</label>
                <select name="jenis_kelamin" id="jenis_kelamin" required>
                    <option value="">-- Pilih --</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            </div>

            <div class="form-group">
                <label for="tempat_lahir">Tempat Lahir</label>
                <input type="text" name="tempat_lahir" id="tempat_lahir">
            </div>

            <div class="form-group">
                <label for="tanggal_lahir">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" id="tanggal_lahir">
                <small class="help">Usia akan dihitung otomatis dari tanggal lahir.</small>
            </div>

            <!-- Alamat & Wilayah -->
            <div class="form-group">
                <label for="id_kecamatan">Kecamatan</label>
                <select name="id_kecamatan" id="id_kecamatan" required>
                    <option value="">-- Pilih Kecamatan --</option>
                    <?php foreach ($kecamatan_list as $kecamatan): ?>
                        <option value="<?= esc($kecamatan['id']) ?>">
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
                <input type="text" name="dusun" id="dusun">
            </div>

            <div class="form-group">
                <label for="alamat_lengkap">Alamat Lengkap</label>
                <input type="text" name="alamat_lengkap" id="alamat_lengkap">
            </div>

            <!-- Kelayakan sosial -->
            <div class="form-group">
                <label for="dtks">DTKS</label>
                <select name="dtks" id="dtks">
                    <option value="">-- Pilih --</option>
                    <option value="Ya">Ya</option>
                    <option value="Tidak">Tidak</option>
                </select>
            </div>

            <div class="form-group">
                <label for="sktm">SKTM</label>
                <select name="sktm" id="sktm">
                    <option value="">-- Pilih --</option>
                    <option value="Ada">Ada</option>
                    <option value="Tidak Ada">Tidak Ada</option>
                </select>
            </div>

            <!-- Referensi karakteristik -->
            <div class="form-group">
                <label for="agama">Agama</label>
                <input type="text" name="agama" id="agama">
            </div>

            <div class="form-group">
                <label for="pendidikan">Pendidikan</label>
                <input type="text" name="pendidikan" id="pendidikan">
            </div>

            <div class="form-group">
                <label for="jenis_usaha">Jenis Usaha</label>
                <input type="text" name="jenis_usaha" id="jenis_usaha">
            </div>

            <div class="form-group">
                <label for="jenis_pekerjaan">Jenis Pekerjaan</label>
                <input type="text" name="jenis_pekerjaan" id="jenis_pekerjaan">
            </div>

            <!-- Ekonomi -->
            <div class="form-group">
                <label for="rab_nominal">RAB (Nominal)</label>
                <input type="number" name="rab_nominal" id="rab_nominal" min="0" step="1000" placeholder="contoh: 15000000">
            </div>

            <button type="submit">Simpan Data</button>
        </form>
    </div>

    <script>
    // Dependent dropdown: kecamatan -> kelurahan
    document.addEventListener('DOMContentLoaded', function() {
        const kecamatanSelect = document.getElementById('id_kecamatan');
        const kelurahanSelect = document.getElementById('id_kelurahan');

        kecamatanSelect.addEventListener('change', function() {
            const kecamatanId = this.value;
            kelurahanSelect.innerHTML = '<option value="">-- Pilih Kelurahan --</option>';
            if (!kecamatanId) return;

            const url = `<?= site_url('admin/monevkuep/get-kelurahan/') ?>${kecamatanId}`;
            fetch(url)
                .then(res => res.json())
                .then(data => {
                    data.forEach(kel => {
                        const opt = document.createElement('option');
                        opt.value = kel.id;
                        opt.textContent = kel.nama_kelurahan;
                        kelurahanSelect.appendChild(opt);
                    });
                })
                .catch(() => {
                    alert('Gagal memuat data kelurahan');
                });
        });
    });
    </script>
</body>
</html>
