<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
Edit Data Difabel
<?= $this->endSection() ?>

<?= $this->section('page_styles') ?>
<style>
    /* Menggunakan style yang sama persis dengan halaman input */
    .form-card { background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); }
    .form-section h3 { font-size: 18px; color: #343a40; margin-top: 20px; margin-bottom: 20px; border-left: 4px solid #007bff; padding-left: 10px; }
    .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
    .form-group { margin-bottom: 20px; }
    label { display: block; margin-bottom: 8px; font-weight: 500; }
    .form-input, select, textarea { width: 100%; padding: 10px 15px; box-sizing: border-box; border: 1px solid #ced4da; border-radius: 8px; font-size: 14px; }
    .form-input[readonly] { background-color: #e9ecef; }
    .form-actions { text-align: right; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e9ecef; }
    .submit-button { padding: 12px 30px; background-color: #ffc107; color: #212529; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; }
    .error-box { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; margin-bottom: 20px; border-radius: 8px; }
    .required-star { color: #dc3545; }
    .checkbox-container { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; border: 1px solid #ced4da; padding: 15px; border-radius: 8px; max-height: 200px; overflow-y: auto; }
    .checkbox-item { display: flex; align-items: center; gap: 8px; }
    .checkbox-item input[type="checkbox"] { width: auto; }
    .checkbox-item label { margin-bottom: 0; font-weight: normal; }
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

    <form action="<?= site_url('admin/difabelkepri/update/' . $data_difabel['id']) ?>" method="post">
        <?= csrf_field() ?>

        <div class="form-section">
            <h3>Data Pribadi</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="nama_lengkap">Nama Lengkap <span class="required-star">*</span></label>
                    <input type="text" name="nama_lengkap" class="form-input" value="<?= old('nama_lengkap', $data_difabel['nama_lengkap']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="nik">NIK (KTP) <span class="required-star">*</span></label>
                    <input type="text" name="nik" class="form-input" value="<?= old('nik', $data_difabel['nik']) ?>" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="jenis_kelamin">Jenis Kelamin <span class="required-star">*</span></label>
                    <select name="jenis_kelamin" required>
                        <option value="Laki-laki" <?= old('jenis_kelamin', $data_difabel['jenis_kelamin']) == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                        <option value="Perempuan" <?= old('jenis_kelamin', $data_difabel['jenis_kelamin']) == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="usia">Usia <span class="required-star">*</span></label>
                    <input type="number" name="usia" class="form-input" value="<?= old('usia', $data_difabel['usia']) ?>" required>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3>Wilayah & Alamat</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="id_kecamatan">Kecamatan <span class="required-star">*</span></label>
                    <select name="id_kecamatan" id="id_kecamatan" required>
                        <option value="">-- Pilih Kecamatan --</option>
                        <?php foreach ($kecamatan_list as $kecamatan): ?>
                            <option value="<?= $kecamatan['id'] ?>" <?= (old('id_kecamatan', $data_difabel['id_kecamatan']) == $kecamatan['id']) ? 'selected' : '' ?>>
                                <?= $kecamatan['nama_kecamatan'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="id_kelurahan">Kelurahan/Desa <span class="required-star">*</span></label>
                    <select name="id_kelurahan" id="id_kelurahan" required>
                        <option value="">-- Pilih Kelurahan --</option>
                         <?php foreach ($kelurahan_list as $kelurahan): ?>
                            <option value="<?= $kelurahan['id'] ?>" <?= (old('id_kelurahan', $data_difabel['id_kelurahan']) == $kelurahan['id']) ? 'selected' : '' ?>>
                                <?= $kelurahan['nama_kelurahan'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="alamat_lengkap">Alamat Lengkap</label>
                <textarea name="alamat_lengkap" class="form-input" rows="3"><?= old('alamat_lengkap', $data_difabel['alamat_lengkap']) ?></textarea>
            </div>
        </div>

        <div class="form-section">
            <h3>Informasi Disabilitas</h3>
            <div class="form-group">
                <label for="golongan_disabilitas">Golongan Disabilitas (Otomatis)</label>
                <input type="text" id="golongan_disabilitas" name="golongan_disabilitas" class="form-input" value="<?= old('golongan_disabilitas', $data_difabel['golongan_disabilitas']) ?>" readonly>
            </div>
            <div class="form-group">
                <label for="jenis_disabilitas_ids">Jenis Disabilitas <span class="required-star">*</span> (Bisa pilih lebih dari satu)</label>
                <div class="checkbox-container">
                    <?php foreach ($jenis_disabilitas_list as $jenis): ?>
                        <div class="checkbox-item">
                            <input type="checkbox" name="jenis_disabilitas_ids[]" value="<?= esc($jenis['id']) ?>" id="jenis_<?= esc($jenis['id']) ?>"
                                data-golongan="<?= esc($jenis['golongan']) ?>"
                                <?= in_array($jenis['id'], old('jenis_disabilitas_ids', $selected_jenis_ids)) ? 'checked' : '' ?>
                            >
                            <label for="jenis_<?= esc($jenis['id']) ?>"><?= esc($jenis['nama_jenis']) ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="form-group">
                <label for="sebab_disabilitas">Sebab Disabilitas</label>
                <textarea name="sebab_disabilitas" class="form-input" rows="3"><?= old('sebab_disabilitas', $data_difabel['sebab_disabilitas']) ?></textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="submit-button">Update Data</button>
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
    // Hapus skrip lama untuk golongan otomatis

    // Ganti dengan skrip baru ini
        const checkboxContainer = document.querySelector('.checkbox-container');
        const golonganInput = document.getElementById('golongan_disabilitas');

        function updateGolongan() {
            const checkedBoxes = checkboxContainer.querySelectorAll('input[type="checkbox"]:checked');
            if (checkedBoxes.length > 1) {
                golonganInput.value = 'Disabilitas Ganda';
            } else if (checkedBoxes.length === 1) {
                golonganInput.value = checkedBoxes[0].getAttribute('data-golongan');
            } else {
                golonganInput.value = '';
            }
        }
        
        checkboxContainer.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', updateGolongan);
        });
</script>
<?= $this->endSection() ?>