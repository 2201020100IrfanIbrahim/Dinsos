<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
Tambah Data Bantuan
<?= $this->endSection() ?>

<?= $this->section('page_styles') ?>
<style>
    .form-card { background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); }
    .form-section h3 { font-size: 18px; color: #343a40; margin-top: 20px; margin-bottom: 20px; border-left: 4px solid #007bff; padding-left: 10px; }
    .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
    .form-group { margin-bottom: 20px; }
    label { display: block; margin-bottom: 8px; font-weight: 500; }
    .form-input, select, input[type="file"] { width: 100%; padding: 10px 15px; box-sizing: border-box; border: 1px solid #ced4da; border-radius: 8px; font-size: 14px; }
    .form-actions { text-align: right; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e9ecef; }
    .submit-button { padding: 12px 30px; background-color: #007bff; color: #fff; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; }
    .error-box { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; margin-bottom: 20px; border-radius: 8px; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="form-card">
    
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

        <div class="form-section">
            <h3>Data Penerima</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="nama_lengkap">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-input" placeholder="Masukkan nama" value="<?= old('nama_lengkap') ?>" required>
                </div>
                <div class="form-group">
                    <label for="nik">NIK (KTP)</label>
                    <input type="text" name="nik" id="nik" class="form-input" placeholder="Masukkan 16 digit NIK" value="<?= old('nik') ?>" required>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3>Wilayah & Alamat</h3>
            <div class="form-row">
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
                        <option value="">-- Pilih Kecamatan Dulu --</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="dusun">Dusun</label>
                    <input type="text" name="dusun" id="dusun" class="form-input" placeholder="Isikan dusun" value="<?= old('dusun') ?>">
                </div>
                <div class="form-group">
                    <label for="rt">RT</label>
                    <input type="text" name="rt" id="rt" class="form-input" placeholder="001" value="<?= old('rt') ?>" required>
                </div>
                <div class="form-group">
                    <label for="rw">RW</label>
                    <input type="text" name="rw" id="rw" class="form-input" placeholder="001" value="<?= old('rw') ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label for="alamat_lengkap">Alamat Lengkap</label>
                <textarea name="alamat_lengkap" id="alamat_lengkap" class="form-input" rows="3" placeholder="Isikan alamat lengkap jalan dan nomor rumah"><?= old('alamat_lengkap') ?></textarea>
            </div>
        </div>

        <div class="form-section">
            <h3>Detail Bantuan</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="kategori_bantuan">Jenis Bantuan</label>
                    <input type="text" name="kategori_bantuan" id="kategori_bantuan" class="form-input" placeholder="Contoh: Bantuan Pokok" value="<?= old('kategori_bantuan') ?>" required>
                </div>
                <div class="form-group">
                    <label for="tahun_penerimaan">Tahun Penerimaan</label>
                    <input type="number" name="tahun_penerimaan" id="tahun_penerimaan" class="form-input" placeholder="Contoh: 2025" value="<?= old('tahun_penerimaan', date('Y')) ?>" required>
                </div>
            </div>
             <div class="form-group">
                <label for="gambar">Upload Foto Lokasi (Opsional, JPG dengan GPS)</label>
                <input type="file" name="gambar" id="gambar" accept="image/jpeg">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="submit-button">Simpan Data</button>
        </div>
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
                kelurahanSelect.innerHTML = '<option value="">-- Pilih Kecamatan Dulu --</option>';
            }
        });
    });
</script>
<?= $this->endSection() ?>