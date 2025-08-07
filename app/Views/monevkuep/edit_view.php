<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
Edit Data MONEVKUEP
<?= $this->endSection() ?>

<?= $this->section('page_styles') ?>
<style>
    /* (CSS sama seperti di file input.php) */
    .form-card { background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); }
    .form-section h3 { font-size: 18px; color: #343a40; margin-top: 20px; margin-bottom: 20px; border-left: 4px solid #007bff; padding-left: 10px; }
    .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
    .form-group { margin-bottom: 20px; }
    label { display: block; margin-bottom: 8px; font-weight: 500; }
    .form-input, select, input[type="date"], input[type="number"] { width: 100%; padding: 10px 15px; box-sizing: border-box; border: 1px solid #ced4da; border-radius: 8px; font-size: 14px; }
    .form-actions { text-align: right; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e9ecef; }
    .submit-button { padding: 12px 30px; background-color: #ffc107; color: #212529; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; }
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

    <form action="<?= site_url('admin/monevkuep/update/' . $bantuan['id']) ?>" method="post">
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
                    <label for="jenis_kelamin">Jenis Kelamin</label>
                    <?php $jkVal = old('jenis_kelamin', $bantuan['jenis_kelamin'] ?? ''); ?>
                    <select name="jenis_kelamin" id="jenis_kelamin" required>
                        <option value="">-- Pilih --</option>
                        <option value="Laki-laki" <?= $jkVal === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                        <option value="Perempuan" <?= $jkVal === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="tempat_lahir">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-input" value="<?= old('tempat_lahir', $bantuan['tempat_lahir'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="tanggal_lahir">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-input" value="<?= old('tanggal_lahir', $bantuan['tanggal_lahir'] ?? '') ?>">
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
                    <input type="text" name="dusun" id="dusun" class="form-input" value="<?= old('dusun', $bantuan['dusun'] ?? '') ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="alamat_lengkap">Alamat Lengkap</label>
                <textarea name="alamat_lengkap" id="alamat_lengkap" class="form-input" rows="3"><?= old('alamat_lengkap', $bantuan['alamat_lengkap'] ?? '') ?></textarea>
            </div>
        </div>

        <div class="form-section">
            <h3>Data Sosial & Ekonomi</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="dtks">DTKS</label>
                    <?php $dtksVal = old('dtks', $bantuan['dtks'] ?? ''); ?>
                    <select name="dtks" id="dtks">
                        <option value="">-- Pilih --</option>
                        <option value="Ya" <?= $dtksVal === 'Ya' ? 'selected' : '' ?>>Ya</option>
                        <option value="Tidak" <?= $dtksVal === 'Tidak' ? 'selected' : '' ?>>Tidak</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="sktm">SKTM</label>
                    <?php $sktmVal = old('sktm', $bantuan['sktm'] ?? ''); ?>
                    <select name="sktm" id="sktm">
                        <option value="">-- Pilih --</option>
                        <option value="Ada" <?= $sktmVal === 'Ada' ? 'selected' : '' ?>>Ada</option>
                        <option value="Tidak Ada" <?= $sktmVal === 'Tidak Ada' ? 'selected' : '' ?>>Tidak Ada</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="rab_nominal">RAB (Nominal)</label>
                    <input type="number" name="rab_nominal" id="rab_nominal" class="form-input" min="0" step="1000" value="<?= old('rab_nominal', $bantuan['rab_nominal'] ?? '') ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="agama">Agama</label>
                    <input type="text" name="agama" id="agama" class="form-input" value="<?= old('agama', $bantuan['agama'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="pendidikan">Pendidikan</label>
                    <input type="text" name="pendidikan" id="pendidikan" class="form-input" value="<?= old('pendidikan', $bantuan['pendidikan'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="jenis_usaha">Jenis Usaha</label>
                    <input type="text" name="jenis_usaha" id="jenis_usaha" class="form-input" value="<?= old('jenis_usaha', $bantuan['jenis_usaha'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="jenis_pekerjaan">Jenis Pekerjaan</label>
                    <input type="text" name="jenis_pekerjaan" id="jenis_pekerjaan" class="form-input" value="<?= old('jenis_pekerjaan', $bantuan['jenis_pekerjaan'] ?? '') ?>">
                </div>
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
                const url = `<?= site_url('admin/monevkuep/get-kelurahan/') ?>${kecamatanId}`;
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
                    })
                    .catch(() => {
                        kelurahanSelect.innerHTML = '<option value="">-- Gagal memuat --</option>';
                    });
            } else {
                kelurahanSelect.innerHTML = '<option value="">-- Pilih Kecamatan Dulu --</option>';
            }
        });
    });
</script>
<?= $this->endSection() ?>
