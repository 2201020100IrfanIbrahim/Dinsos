<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
Tambah Data Difabel
<?= $this->endSection() ?>

<?= $this->section('page_styles') ?>
<style>
    .form-card { background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); }
    .form-section h3 { font-size: 18px; color: #343a40; margin-top: 20px; margin-bottom: 20px; border-left: 4px solid #007bff; padding-left: 10px; }
    .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
    .form-group { margin-bottom: 20px; }
    label { display: block; margin-bottom: 8px; font-weight: 500; }
    .form-input, select, textarea { width: 100%; padding: 10px 15px; box-sizing: border-box; border: 1px solid #ced4da; border-radius: 8px; font-size: 14px; }
    .form-input[readonly] { background-color: #e9ecef; cursor: not-allowed; }
    .form-actions { text-align: right; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e9ecef; }
    .submit-button { padding: 12px 30px; background-color: #007bff; color: #fff; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; }
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
    <div class="back">
        <a href="<?= site_url('admin/difabelkepri') ?>" class="back-button" style="margin-bottom: 20px; display: inline-block;"> Kembali</a>
        <h1>Form Pengisian Data Difabel</h1>
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

    <form action="<?= site_url('admin/difabelkepri/create') ?>" method="post">
        <?= csrf_field() ?>

        <div class="form-section">
            <h3>Data Pribadi</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="nama_lengkap">Nama Lengkap <span class="required-star">*</span></label>
                    <input type="text" name="nama_lengkap" class="form-input" value="<?= old('nama_lengkap') ?>" required>
                </div>
                <div class="form-group">
                    <label for="nik">NIK (KTP) <span class="required-star">*</span></label>
                    <input type="text" name="nik" class="form-input" value="<?= old('nik') ?>" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="jenis_kelamin">Jenis Kelamin <span class="required-star">*</span></label>
                    <select name="jenis_kelamin" class="form-input" required>
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="Laki-laki" <?= old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                        <option value="Perempuan" <?= old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="usia">Usia <span class="required-star">*</span></label>
                    <input type="number" name="usia" class="form-input" value="<?= old('usia') ?>" required>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3>Wilayah & Alamat</h3>

            <?php if (isset($role) && $role === 'superadmin'): ?>
            <div class="form-group">
                <label for="id_kabupaten">Kabupaten/Kota <span class="required-star">*</span></label>
                <select name="id_kabupaten" id="id_kabupaten" class="form-input" required>
                    <option value="">-- Pilih Kabupaten --</option>
                    <?php foreach ($kabupaten_list as $kabupaten): ?>
                        <option value="<?= esc($kabupaten['id']) ?>" <?= set_select('id_kabupaten', $kabupaten['id']) ?>>
                            <?= esc($kabupaten['nama_kabupaten']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>

            <div class="form-row">
                <div class="form-group">
                    <label for="id_kecamatan">Kecamatan <span class="required-star">*</span></label>
                    <select name="id_kecamatan" id="id_kecamatan" class="form-input" required>
                        <option value="">-- Pilih Kecamatan --</option>
                        <?php if (isset($kecamatan_list)): ?>
                            <?php foreach ($kecamatan_list as $kecamatan): ?>
                                <option value="<?= esc($kecamatan['id']) ?>" <?= old('id_kecamatan') == $kecamatan['id'] ? 'selected' : '' ?>>
                                    <?= esc($kecamatan['nama_kecamatan']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="id_kelurahan">Kelurahan/Desa <span class="required-star">*</span></label>
                    <select name="id_kelurahan" id="id_kelurahan" class="form-input" required>
                        <option value="">-- Pilih Kecamatan Dulu --</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="alamat_lengkap">Alamat Lengkap</label>
                <textarea name="alamat_lengkap" class="form-input" rows="3"><?= old('alamat_lengkap') ?></textarea>
            </div>
        </div>

        <div class="form-section">
            <h3>Informasi Disabilitas</h3>
            <div class="form-group">
                <label for="golongan_disabilitas">Golongan Disabilitas (Otomatis)</label>
                <input type="text" name="golongan_disabilitas" id="golongan_disabilitas" class="form-input" value="<?= old('golongan_disabilitas') ?>" readonly>
            </div>
            <div class="form-group">
                <label>Jenis Disabilitas <span class="required-star">*</span> (Bisa pilih lebih dari satu)</label>
                <div class="checkbox-container">
                    <?php if (isset($jenis_disabilitas_list)): ?>
                        <?php foreach ($jenis_disabilitas_list as $jenis): ?>
                            <div class="checkbox-item">
                                <input type="checkbox" name="jenis_disabilitas_ids[]" value="<?= esc($jenis['id']) ?>" id="jenis_<?= esc($jenis['id']) ?>"
                                    data-golongan="<?= esc($jenis['golongan']) ?>"
                                    <?= old('jenis_disabilitas_ids') && in_array($jenis['id'], old('jenis_disabilitas_ids')) ? 'checked' : '' ?>
                                >
                                <label for="jenis_<?= esc($jenis['id']) ?>"><?= esc($jenis['nama_jenis']) ?></label>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-group">
                <label for="sebab_disabilitas">Sebab Disabilitas</label>
                <textarea name="sebab_disabilitas" class="form-input" rows="3"><?= old('sebab_disabilitas') ?></textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="submit-button">Simpan Data</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const kabupatenSelect = document.getElementById('id_kabupaten');
    const kecamatanSelect = document.getElementById('id_kecamatan');
    const kelurahanSelect = document.getElementById('id_kelurahan');
    const checkboxContainer = document.querySelector('.checkbox-container');
    const golonganInput = document.getElementById('golongan_disabilitas');
    const userRole = '<?= esc($role ?? 'admin') ?>';

    // --- LOGIKA DROPDOWN WILAYAH ---
    function loadKecamatan(kabupatenId) {
        kecamatanSelect.innerHTML = '<option value="">-- Memuat... --</option>';
        kelurahanSelect.innerHTML = '<option value="">-- Pilih Kecamatan Dulu --</option>';
        if (!kabupatenId) {
            kecamatanSelect.innerHTML = userRole === 'superadmin' ? '<option value="">-- Pilih Kabupaten Dulu --</option>' : '<option value="">-- Pilih Kecamatan --</option>';
            return;
        }
        fetch(`<?= site_url('admin/difabelkepri/get-kecamatan/') ?>${kabupatenId}`)
            .then(res => res.json()).then(data => {
                kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
                data.forEach(kec => {
                    const opt = document.createElement('option');
                    opt.value = kec.id;
                    opt.textContent = kec.nama_kecamatan;
                    kecamatanSelect.appendChild(opt);
                });
            });
    }

    function loadKelurahan(kecamatanId) {
        kelurahanSelect.innerHTML = '<option value="">-- Memuat... --</option>';
        if (!kecamatanId) {
            kelurahanSelect.innerHTML = '<option value="">-- Pilih Kecamatan Dulu --</option>';
            return;
        }
        fetch(`<?= site_url('admin/difabelkepri/get-kelurahan/') ?>${kecamatanId}`)
            .then(res => res.json()).then(data => {
                kelurahanSelect.innerHTML = '<option value="">-- Pilih Kelurahan --</option>';
                data.forEach(kel => {
                    const opt = document.createElement('option');
                    opt.value = kel.id;
                    opt.textContent = kel.nama_kelurahan;
                    kelurahanSelect.appendChild(opt);
                });
            });
    }

    if (userRole === 'superadmin' && kabupatenSelect) {
        kabupatenSelect.addEventListener('change', () => loadKecamatan(kabupatenSelect.value));
    }
    kecamatanSelect.addEventListener('change', () => loadKelurahan(kecamatanSelect.value));

    // --- LOGIKA GOLONGAN DISABILITAS OTOMATIS ---
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
});
</script>
<?= $this->endSection() ?>
