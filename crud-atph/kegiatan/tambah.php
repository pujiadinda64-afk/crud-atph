<?php
include '../koneksi.php';

if (isset($_POST['submit'])) {
    $nama_kegiatan = $_POST['nama_kegiatan'];
    $deskripsi     = $_POST['deskripsi'];
    $pembimbing    = $_POST['pembimbing'];
    $tanggal       = $_POST['tanggal'];

    // Upload Foto Utama
    $foto_utama = time() . '_' . $_FILES['foto_utama']['name'];
    $tmp_utama  = $_FILES['foto_utama']['tmp_name'];
    move_uploaded_file($tmp_utama, 'uploads/' . $foto_utama);

    // Simpan Data Utama Kegiatan
    $query = "INSERT INTO kegiatan (nama_kegiatan, deskripsi, pembimbing, tanggal, foto) 
              VALUES ('$nama_kegiatan', '$deskripsi', '$pembimbing', '$tanggal', '$foto_utama')";
    mysqli_query($koneksi, $query);
    
    // Ambil ID Kegiatan yang Baru Dibuat
    $id_kegiatan_baru = mysqli_insert_id($koneksi);

    // Upload Banyak Foto Tambahan
    if (!empty($_FILES['foto_tambahan']['name'][0])) {
        foreach ($_FILES['foto_tambahan']['name'] as $key => $val) {
            $nama_file  = time() . '_' . $_FILES['foto_tambahan']['name'][$key];
            $tmp_file   = $_FILES['foto_tambahan']['tmp_name'][$key];
            $keterangan = $_POST['keterangan_foto'][$key] ?? '';

            if (move_uploaded_file($tmp_file, 'uploads/' . $nama_file)) {
                mysqli_query($koneksi, "INSERT INTO foto_kegiatan (id_kegiatan, nama_foto, keterangan) VALUES ('$id_kegiatan_baru', '$nama_file', '$keterangan')");
            }
        }
    }

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Kegiatan - ATPH</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body { font-family: sans-serif; background: #f8fafc; padding: 20px; color: #334155; }
        .form-container { max-width: 600px; margin: 0 auto; background: white; padding: 25px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; color: #1b4332; }
        input, textarea { width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; box-sizing: border-box; }
        .btn-submit { background: #2e7d32; color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; width: 100%; }
        .btn-submit:hover { background: #1b4332; }
    </style>
</head>
<body>

<div class="form-container">
    <h2 style="color: #1b4332; margin-top: 0;">🌱 Tambah Kegiatan Pembelajaran</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Nama Kegiatan</label>
            <input type="text" name="nama_kegiatan" required>
        </div>
        <div class="form-group">
            <label>Deskripsi Kegiatan</label>
            <textarea name="deskripsi" rows="4" required></textarea>
        </div>
        <div class="form-group">
            <label>Pembimbing</label>
            <input type="text" name="pembimbing" required>
        </div>
        <div class="form-group">
            <label>Tanggal</label>
            <input type="date" name="tanggal" required>
        </div>
        <div class="form-group">
            <label>Foto Utama (Cover)</label>
            <input type="file" name="foto_utama" accept="image/*" required>
        </div>
        <div class="form-group">
    <label>Foto Dokumentasi Tambahan (Bisa Pilih Banyak)</label>
    <input type="file" name="foto_tambahan[]" accept="image/*" multiple>
</div>
        <button type="submit" name="submit" class="btn-submit">Simpan Data</button>
    </form>
</div>

</body>
</html>