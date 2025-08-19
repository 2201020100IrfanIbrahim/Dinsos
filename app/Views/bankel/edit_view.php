<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
Edit Data Bantuan
<?= $this->endSection() ?>

<?= $this->section('page_styles') ?>
<style>
    /* (CSS sama seperti di file input.php) */
    .form-card { background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); }
    .form-section h3 { font-size: 18px; color: #343a40; margin-top: 20px; margin-bottom: 20px; border-left: 4px solid #007bff; padding-left: 10px; }
    .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
    .form-group { margin-bottom: 20px; }
    label { display: block; margin-bottom: 8px; font-weight: 500; }
    .required-star { color: #dc3545; }
    .form-input, select, input[type="file"] { width: 100%; padding: 10px 15px; box-sizing: border-box; border: 1px solid #ced4da; border-radius: 8px; font-size: 14px; }
    .form-actions { text-align: right; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e9ecef; }
    .submit-button { padding: 12px 30px; background-color: #ffc107; color: #212529; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; }
    .error-box { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; margin-bottom: 20px; border-radius: 8px; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="form-card">
    <div class="back">
        <a href="<?= site_url('admin/bankel') ?>" class="back-button" style="margin-bottom: 20px; display: inline-block;"> Kembali</a>
        <h1>Form Pengisian Data BANKEL</h1>
        <p style="color:#888888; font-size: small;">Note: (*) Wajib Isi</p>
    </div>

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

        <div class="form-section">
            <h3>Data Penerima</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="nama_lengkap">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-input" value="<?= old('nama_lengkap', $bantuan['nama_lengkap']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="nik">NIK (KTP)</label>
                    <input type="text" name="nik" id="nik" class="form-input" value="<?= old('nik', $bantuan['nik']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="file_ktp">KTP (PDF)</label>
                    <input type="file" name="file_ktp" id="file_ktp" accept="application/pdf">
                </div>
                <div class="form-group">
                    <label for="file_kk">Kartu Keluarga (PDF)</label>
                    <input type="file" name="file_kk" id="file_kk" accept="application/pdf">
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3>Wilayah & Alamat</h3>
            
            <?php if (isset($role) && $role === 'superadmin'): ?>
            <div class="form-group">
                <label for="id_kabupaten">Kabupaten/Kota <span class="required-star">*</span></label>
                <select name="id_kabupaten" id="id_kabupaten" required>
                    <option value="">-- Pilih Kabupaten/Kota --</option>
                    <?php foreach ($kabupaten_list as $kab): ?>
                        <option value="<?= esc($kab['id']) ?>" <?= (old('id_kabupaten', $bantuan['id_kabupaten']) == $kab['id']) ? 'selected' : '' ?>>
                            <?= esc($kab['nama_kabupaten']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>

            <div class="form-row">
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
            </div>
            <div class="form-row">
                 <div class="form-group">
                    <label for="dusun">Dusun</label>
                    <input type="text" name="dusun" id="dusun" class="form-input" value="<?= old('dusun', $bantuan['dusun']) ?>">
                </div>
                <div class="form-group">
                    <label for="rt">RT</label>
                    <input type="text" name="rt" id="rt" class="form-input" value="<?= old('rt', $bantuan['rt']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="rw">RW</label>
                    <input type="text" name="rw" id="rw" class="form-input" value="<?= old('rw', $bantuan['rw']) ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label for="alamat_lengkap">Alamat Lengkap</label>
                <textarea name="alamat_lengkap" id="alamat_lengkap" class="form-input" rows="3"><?= old('alamat_lengkap', $bantuan['alamat_lengkap']) ?></textarea>
            </div>
        </div>

        <div class="form-section">
            <h3>Detail Bantuan</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="kategori_bantuan">Jenis Bantuan</label>
                    <input type="text" name="kategori_bantuan" id="kategori_bantuan" class="form-input" value="<?= old('kategori_bantuan', $bantuan['kategori_bantuan']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="tahun_penerimaan">Tahun Penerimaan</label>
                    <input type="number" name="tahun_penerimaan" id="tahun_penerimaan" class="form-input" value="<?= old('tahun_penerimaan', $bantuan['tahun_penerimaan']) ?>" required>
                </div>
            </div>
             <div class="form-group">
                <label for="gambar">Upload Foto Lokasi Baru (Opsional)</label>
                <input type="file" name="gambar" id="gambar" accept="image/jpeg">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="submit-button">Update Data</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const kabupatenSelect = document.getElementById('id_kabupaten');
    const kecamatanSelect = document.getElementById('id_kecamatan');
    const kelurahanSelect = document.getElementById('id_kelurahan');
    const userRole = '<?= esc($role ?? 'admin') ?>';
    
    // Nilai awal dari controller untuk pre-select dropdown
    const initialKabId = '<?= old('id_kabupaten', $bantuan['id_kabupaten'] ?? '') ?>';
    const initialKecId = '<?= old('id_kecamatan', $bantuan['id_kecamatan'] ?? '') ?>';
    const initialKelId = '<?= old('id_kelurahan', $bantuan['id_kelurahan'] ?? '') ?>';

    // Fungsi untuk memuat kecamatan
    function loadKecamatan(kabupatenId, selectedKecId = null) {
        kecamatanSelect.innerHTML = '<option value="">-- Memuat... --</option>';
        kelurahanSelect.innerHTML = '<option value="">-- Pilih Kecamatan Dulu --</option>';
        if (!kabupatenId) {
            kecamatanSelect.innerHTML = userRole === 'superadmin' ? '<option value="">-- Pilih Kabupaten Dulu --</option>' : '';
            return;
        }
        fetch(`<?= site_url('admin/bankel/get-kecamatan/') ?>${kabupatenId}`)
            .then(res => res.json()).then(data => {
                kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
                data.forEach(kec => {
                    const opt = document.createElement('option');
                    opt.value = kec.id;
                    opt.textContent = kec.nama_kecamatan;
                    if (kec.id == selectedKecId) opt.selected = true;
                    kecamatanSelect.appendChild(opt);
                });
                if (selectedKecId) loadKelurahan(selectedKecId, initialKelId);
            });
    }

    // Fungsi untuk memuat kelurahan
    function loadKelurahan(kecamatanId, selectedKelId = null) {
        kelurahanSelect.innerHTML = '<option value="">-- Memuat... --</option>';
        if (!kecamatanId) {
            kelurahanSelect.innerHTML = '<option value="">-- Pilih Kecamatan Dulu --</option>';
            return;
        }
        fetch(`<?= site_url('admin/bankel/get-kelurahan/') ?>${kecamatanId}`)
            .then(res => res.json()).then(data => {
                kelurahanSelect.innerHTML = '<option value="">-- Pilih Kelurahan --</option>';
                data.forEach(kel => {
                    const opt = document.createElement('option');
                    opt.value = kel.id;
                    opt.textContent = kel.nama_kelurahan;
                    if (kel.id == selectedKelId) opt.selected = true;
                    kelurahanSelect.appendChild(opt);
                });
            });
    }

    // Tambahkan event listener
    if (userRole === 'superadmin' && kabupatenSelect) {
        kabupatenSelect.addEventListener('change', () => loadKecamatan(kabupatenSelect.value));
    }
    kecamatanSelect.addEventListener('change', () => loadKelurahan(kecamatanSelect.value));

    // Muat data awal saat halaman edit dibuka
    if (initialKabId) {
        // Jika admin, opsi kecamatan sudah ada dari PHP, jadi kita hanya perlu memuat kelurahan
        if (userRole === 'admin') {
            const listKecamatan = <?= json_encode($kecamatan_list) ?>;
            kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
            listKecamatan.forEach(kec => {
                const opt = document.createElement('option');
                opt.value = kec.id;
                opt.textContent = kec.nama_kecamatan;
                if (kec.id == initialKecId) opt.selected = true;
                kecamatanSelect.appendChild(opt);
            });
            loadKelurahan(initialKecId, initialKelId);
        } else {
            // Jika superadmin, kita muat kecamatan dulu, lalu kelurahan akan dimuat otomatis
            loadKecamatan(initialKabId, initialKecId);
        }
    }
});
</script>
<?= $this->endSection() ?>