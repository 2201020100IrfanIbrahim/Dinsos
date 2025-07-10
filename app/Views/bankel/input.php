<!DOCTYPE html>
<html lang="id">
<head>
    <title>Tambah Data Bantuan</title>
    <style>
        body { font-family: sans-serif; }
        .form-container { max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input, select { width: 100%; padding: 8px; box-sizing: border-box; }
        button { padding: 10px 15px; background-color: #007bff; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Formulir Input Data Bantuan</h2>

        <form action="<?= site_url('admin/bankel/create') ?>" method="post">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="nik">NIK Penerima</label>
                <input type="text" name="nik" id="nik" required>
            </div>

            <div class="form-group">
                <label for="nama">Nama Lengkap Penerima</label>
                <input type="text" name="nama" id="nama" required>
            </div>

            <div class="form-group">
                <label for="alamat">Alamat Lengkap</label>
                <input type="text" name="alamat" id="alamat" required>
            </div>

            <div class="form-group">
                <label for="jenis_bantuan">Jenis Bantuan</label>
                <select name="jenis_bantuan" id="jenis_bantuan" required>
                    <option value="">-- Pilih Jenis Bantuan --</option>
                    <option value="PKH">PKH</option>
                    <option value="BPNT">BPNT</option>
                    <option value="BLT">BLT</option>
                </select>
            </div>

            <button type="submit">Simpan Data</button>
        </form>
    </div>
</body>
</html>