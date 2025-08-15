<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
Tambah Data MONEVKUEP
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
    small.help { color:#666; display:block; margin-top:4px; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="back">
            <a href="<?= site_url('admin/monevkuep') ?>" class="back-button" style="margin-bottom: 20px; display: inline-block;"> Kembali ke Dashboard</a>
        </div>
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

    <form action="<?= site_url('admin/monevkuep/create') ?>" method="post">
        <?= csrf_field() ?>

        <!-- Identitas Dasar -->
        <div class="form-section">
            <h3>Identitas Dasar</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="nik">NIK <span class="required-star">*</span></label>
                    <input type="text" name="nik" id="nik" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="nama_lengkap">Nama Lengkap <span class="required-star">*</span></label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-input" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="jenis_kelamin">Jenis Kelamin <span class="required-star">*</span></label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-input" required>
                        <option value="">-- Pilih --</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tempat_lahir">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-input">
                </div>
            </div>
            <div class="form-group">
                <label for="tanggal_lahir">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-input">
                <small class="help">Usia akan dihitung otomatis dari tanggal lahir.</small>
            </div>
        </div>

        <!-- Alamat & Wilayah -->
        <div class="form-section">
            <h3>Alamat & Wilayah</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="id_kecamatan">Kecamatan <span class="required-star">*</span></label>
                    <select name="id_kecamatan" id="id_kecamatan" class="form-input" required>
                        <option value="">-- Pilih Kecamatan --</option>
                        <?php foreach ($kecamatan_list as $kecamatan): ?>
                            <option value="<?= esc($kecamatan['id']) ?>">
                                <?= esc($kecamatan['nama_kecamatan']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="id_kelurahan">Kelurahan/Desa <span class="required-star">*</span></label>
                    <select name="id_kelurahan" id="id_kelurahan" class="form-input" required>
                        <option value="">-- Pilih Kelurahan --</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="dusun">Dusun</label>
                    <input type="text" name="dusun" id="dusun" class="form-input">
                </div>
                <div class="form-group">
                    <label for="alamat_lengkap">Alamat Lengkap</label>
                    <input type="text" name="alamat_lengkap" id="alamat_lengkap" class="form-input">
                </div>
            </div>
        </div>

        <!-- Kelayakan Sosial -->
        <div class="form-section">
            <h3>Kelayakan Sosial</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="dtks">DTKS</label>
                    <select name="dtks" id="dtks" class="form-input">
                        <option value="">-- Pilih --</option>
                        <option value="Ya">Ya</option>
                        <option value="Tidak">Tidak</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="sktm">SKTM</label>
                    <select name="sktm" id="sktm" class="form-input">
                        <option value="">-- Pilih --</option>
                        <option value="Ada">Ada</option>
                        <option value="Tidak Ada">Tidak Ada</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Karakteristik -->
        <div class="form-section">
            <h3>Karakteristik</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="agama">Agama</label>
                    <input type="text" name="agama" id="agama" class="form-input">
                </div>
                <div class="form-group">
                    <label for="pendidikan">Pendidikan</label>
                    <input type="text" name="pendidikan" id="pendidikan" class="form-input">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="jenis_usaha">Jenis Usaha</label>
                    <input type="text" name="jenis_usaha" id="jenis_usaha" class="form-input">
                </div>
                <div class="form-group">
                    <label for="jenis_pekerjaan">Jenis Pekerjaan</label>
                    <input type="text" name="jenis_pekerjaan" id="jenis_pekerjaan" class="form-input">
                </div>
            </div>
        </div>

        <!-- Ekonomi -->
        <div class="form-section">
            <h3>Ekonomi</h3>
            <div class="form-group">
                <label for="rab_nominal">RAB (Nominal)</label>
                <input type="number" name="rab_nominal" id="rab_nominal" class="form-input" min="0" step="1000" placeholder="contoh: 15000000">
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
<?= $this->endSection() ?>
