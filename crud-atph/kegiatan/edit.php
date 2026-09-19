<?php
session_start(); 
// 1. Cek apakah user BELUM LOGIN SAMA SEKALI
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location='../../login.php';</script>";
    exit();
}

// 2. Cek apakah role-nya bukan admin/guru (sesuaikan kebutuhan, misal hanya admin)
if ($_SESSION['role'] != 'admin') 'siswa' {
    echo "<script>alert('Akses ditolak! Anda tidak memiliki izin.'); window.location='index.php';</script>";
    exit();
}
// ... kode proses selanjutnya (tambah/edit/hapus) ...
?>
include '../../koneksi.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data kegiatan berdasarkan id_kegiatan
$query = mysqli_query($koneksi, "SELECT * FROM kegiatan WHERE id_kegiatan='$id'");
$d = mysqli_fetch_array($query);

if (!$d) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['update'])) {
    $nama_kegiatan = $_POST['nama_kegiatan'];
    $deskripsi     = $_POST['deskripsi'];
    $pembimbing    = $_POST['pembimbing'];
    $tanggal       = $_POST['tanggal'];

    // Update foto utama jika ada file baru yang diunggah
    if (!empty($_FILES['foto_utama']['name'])) {
        $foto_utama = time() . '_' . $_FILES['foto_utama']['name'];
        move_uploaded_file($_FILES['foto_utama']['tmp_name'], 'uploads/' . $foto_utama);
        
        // Hapus foto lama jika ada
        if (!empty($d['foto']) && file_exists('uploads/' . $d['foto'])) {
            unlink('uploads/' . $d['foto']);
        }

        mysqli_query($koneksi, "UPDATE kegiatan SET foto='$foto_utama' WHERE id_kegiatan='$id'");
    }

    // Update data utama
    $query_update = "UPDATE kegiatan SET 
                        nama_kegiatan='$nama_kegiatan', 
                        deskripsi='$deskripsi', 
                        pembimbing='$pembimbing', 
                        tanggal='$tanggal' 
                      WHERE id_kegiatan='$id'";

    if (mysqli_query($koneksi, $query_update)) {
        // Upload foto-foto tambahan baru jika ada
        if (!empty($_FILES['foto_tambahan']['name'][0])) {
            foreach ($_FILES['foto_tambahan']['name'] as $key => $val) {
                $nama_file = time() . '_' . $_FILES['foto_tambahan']['name'][$key];
                $tmp_file  = $_FILES['foto_tambahan']['tmp_name'][$key];

                if (move_uploaded_file($tmp_file, 'uploads/' . $nama_file)) {
                    mysqli_query($koneksi, "INSERT INTO foto_kegiatan (id_kegiatan, nama_foto) VALUES ('$id', '$nama_file')");
                }
            }
        }

        header("Location: index.php");
        exit();
    }
}

// Proses Hapus Foto Tambahan
if (isset($_GET['hapus_foto'])) {
    $id_foto = intval($_GET['hapus_foto']);
    $q_foto  = mysqli_query($koneksi, "SELECT * FROM foto_kegiatan WHERE id_foto='$id_foto'");
    $f       = mysqli_fetch_array($q_foto);

    if ($f) {
        if (file_exists('uploads/' . $f['nama_foto'])) {
            unlink('uploads/' . $f['nama_foto']);
        }
        mysqli_query($koneksi, "DELETE FROM foto_kegiatan WHERE id_foto='$id_foto'");
    }
    header("Location: edit.php?id=" . $id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kegiatan - ATPH</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body { font-family: sans-serif; background: #f8fafc; color: #334155; margin: 0; padding: 20px; }
        .form-container { max-width: 650px; margin: 0 auto; background: white; padding: 30px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        .form-group { margin-bottom: 18px; }
        label { display: block; font-weight: bold; margin-bottom: 6px; color: #1b4332; font-size: 14px; }
        input[type="text"], input[type="date"], textarea, input[type="file"] { width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; box-sizing: border-box; font-size: 14px; }
        
        .btn-submit { background: #f59e0b; color: white; padding: 12px 20px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; width: 100%; font-size: 15px; }
        .btn-submit:hover { background: #d97706; }
        .btn-back { background: #64748b; color: white; padding: 8px 14px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: bold; display: inline-block; margin-bottom: 15px; }

        /* Galeri Kelola Foto Tambahan */
        .existing-photos { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 10px; }
        .photo-item { position: relative; width: 90px; height: 90px; border-radius: 8px; overflow: hidden; border: 1px solid #e2e8f0; }
        .photo-item img { width: 100%; height: 100%; object-fit: cover; }
        .btn-delete-photo { position: absolute; top: 4px; right: 4px; background: rgba(239, 68, 68, 0.9); color: white; border: none; border-radius: 50%; width: 22px; height: 22px; font-size: 12px; cursor: pointer; display: flex; align-items: center; justify-content: center; text-decoration: none; }
    </style>
</head>
<body>

<div class="form-container">
    <a href="index.php" class="btn-back">&larr; Kembali</a>
    <h2 style="color: #1b4332; margin-top: 0;">✏️ Edit Data Kegiatan</h2>
    
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Nama Kegiatan</label>
            <input type="text" name="nama_kegiatan" value="<?= htmlspecialchars($d['nama_kegiatan']); ?>" required>
        </div>

        <div class="form-group">
            <label>Deskripsi Kegiatan</label>
            <textarea name="deskripsi" rows="4" required><?= htmlspecialchars($d['deskripsi']); ?></textarea>
        </div>

        <div class="form-group">
            <label>Pembimbing</label>
            <input type="text" name="pembimbing" value="<?= htmlspecialchars($d['pembimbing']); ?>" required>
        </div>

        <div class="form-group">
            <label>Tanggal</label>
            <input type="date" name="tanggal" value="<?= $d['tanggal']; ?>" required>
        </div>

        <div class="form-group">
            <label>Ganti Foto Utama (Cover)</label>
            <?php if (!empty($d['foto']) && file_exists('uploads/' . $d['foto'])): ?>
                <div style="margin-bottom: 8px;">
                    <img src="uploads/<?= htmlspecialchars($d['foto']); ?>" style="width: 100px; height: 70px; object-fit: cover; border-radius: 6px;">
                </div>
            <?php endif; ?>
            <input type="file" name="foto_utama" accept="image/*">
            <small style="color: #64748b;">Biarkan kosong jika tidak ingin mengganti foto utama.</small>
        </div>

        <div class="form-group">
            <label>Foto Dokumentasi Tambahan Saat Ini</label>
            <div class="existing-photos">
                <?php
                $q_galeri = mysqli_query($koneksi, "SELECT * FROM foto_kegiatan WHERE id_kegiatan='$id'");
                if (mysqli_num_rows($q_galeri) > 0) {
                    while ($g = mysqli_fetch_array($q_galeri)) {
                ?>
                    <div class="photo-item">
                        <img src="uploads/<?= htmlspecialchars($g['nama_foto']); ?>">
                        <a href="edit.php?id=<?= $id; ?>&hapus_foto=<?= $g['id_foto']; ?>" class="btn-delete-photo" onclick="return confirm('Hapus foto ini?')" title="Hapus Foto">&times;</a>
                    </div>
                <?php 
                    }
                } else {
                    echo "<small style='color: #94a3b8;'>Belum ada foto tambahan.</small>";
                }
                ?>
            </div>
        </div>

        <div class="form-group">
            <label>Tambah Foto Dokumentasi Baru (Bisa Pilih Banyak)</label>
            <input type="file" name="foto_tambahan[]" accept="image/*" multiple>
        </div>

        <button type="submit" name="update" class="btn-submit">Simpan Perubahan</button>
    </form>
</div>

</body>
</html>