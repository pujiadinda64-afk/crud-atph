<?php
session_start();
include '../config/koneksi.php';

<<<<<<< HEAD
if (($_SESSION['role'] ?? '') !== 'admin') {
    echo "<script>alert('Akses ditolak! Hanya admin yang dapat menambah data.'); window.location='index.php';</script>";
    exit;
}

if (isset($_POST['simpan'])) {
=======
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "<script>alert('Akses ditolak! Halaman ini khusus untuk Admin.'); window.location='index.php';</script>";
    exit();
}

if (isset($_POST['submit'])) {
>>>>>>> 1715ef8ad1ef01009636891237625cefb3e14a23
    $nama_kegiatan = $_POST['nama_kegiatan'];
    $deskripsi     = $_POST['deskripsi'];
    $pembimbing    = $_POST['pembimbing'];
    $tanggal       = $_POST['tanggal'];

<<<<<<< HEAD
// 1. Ubah bagian Upload Foto Utama ini:
    $foto_utama = time() . '_' . $_FILES['foto_utama']['name'];
    $tmp_utama  = $_FILES['foto_utama']['tmp_name'];
=======
    // Upload Foto Utama
    $foto_utama = time() . '_' . $_FILES['foto']['name'];
    $tmp_utama  = $_FILES['foto']['tmp_name'];
>>>>>>> 7acdf25957c37f1cc9db6c94b50c47b7e25094a5
    move_uploaded_file($tmp_utama, 'uploads/' . $foto_utama);

    // 2. Dan pastikan bagian Query INSERT ini memakai variabel $foto_utama:
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
    
    <!-- CSS-nya ditaruh langsung di sini biar pasti terbaca -->
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: url('../../img/latar.jpg') no-repeat center center fixed; /* Ganti dengan path foto background kamu */
            background-size: cover;
            color: #334155; 
            margin: 0; 
            padding: 30px 20px; 
            min-height: 100vh; 
        }

        /* Kotak Card Utama di Tengah */
        .card-form {
            max-width: 650px;
            margin: 40px auto;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            padding: 35px;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }

        h2 {
            color: #1e3a2f;
            margin-top: 0;
            margin-bottom: 25px;
            font-size: 24px;
        }

        label {
            font-weight: 600;
            color: #1e293b;
            font-size: 14px;
            display: block;
            margin-top: 15px;
            margin-bottom: 6px;
        }

        input[type="text"],
        input[type="date"],
        input[type="file"],
        textarea,
        select {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #cbd5e1;
            border-radius: 12px;
            font-size: 14px;
            background-color: #ffffff;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        input:focus, textarea:focus {
            border-color: #2e7d32;
            box-shadow: 0 0 0 4px rgba(46, 125, 50, 0.15);
            outline: none;
        }

        textarea {
            resize: vertical;
            height: 100px;
        }

        .btn-container {
            margin-top: 25px;
        }

        button[type="submit"], input[type="submit"] {
            background-color: #2e7d32;
            color: white;
            padding: 12px 28px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            margin-right: 10px;
        }

        button[type="submit"]:hover, input[type="submit"]:hover {
            background-color: #1b4332;
        }

        button[type="reset"], input[type="reset"] {
            background-color: #ef4444;
            color: white;
            padding: 12px 28px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        button[type="reset"]:hover {
            background-color: #dc2626;
        }
    </style>
</head>
<body>

    <!-- Pembungkus Card Form -->
    <div class="card-form">
        <h2>🌱 Tambah Kegiatan Pembelajaran</h2>
        
        <form action="" method="POST" enctype="multipart/form-data">
            <label>Nama Kegiatan</label>
            <input type="text" name="nama_kegiatan" required>

            <label>Deskripsi Kegiatan</label>
            <textarea name="deskripsi" required></textarea>

            <label>Pembimbing</label>
            <input type="text" name="pembimbing" required>

            <label>Tanggal</label>
            <input type="date" name="tanggal" required>

           <label>Media Utama (Foto / Video)</label>
           <input type="file" name="foto_utama" accept="image/*,video/mp4,video/webm,video/ogg,video/mkv" required>

            <div class="btn-container">
                <button type="submit" name="simpan">Simpan</button>
                <button type="reset">Reset</button>
            </div>
        </form>
    </div>

</body>
</html>