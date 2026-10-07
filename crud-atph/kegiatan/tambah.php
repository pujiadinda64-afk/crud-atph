<?php
session_start(); // Wajib ada untuk membaca data login

// Cek apakah yang mengakses adalah admin ATAU siswa
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'siswa')) {
    echo "<script>alert('Akses ditolak! Silakan login terlebih dahulu.'); window.location='login.php';</script>";
    exit();
}

include '../config/koneksi.php';
if(isset($_POST['simpan'])){
  $nama_kegiatan = $_POST['nama_kegiatan']; 
  $deskripsi     = $_POST['deskripsi']; 
  $pembimbing    = $_POST['pembimbing']; 
  $tanggal       = $_POST['tanggal'];
  
  // Upload Foto Utama
  $foto = $_FILES['foto']['name']; 
  $tmp  = $_FILES['foto']['tmp_name'];
  $foto_baru = "";
  if($foto){ 
      $foto_baru = time()."_".$foto; 
      move_uploaded_file($tmp, "uploads/".$foto_baru); 
  }
  
  // Simpan data utama ke tabel kegiatan
  mysqli_query($koneksi, "INSERT INTO kegiatan (nama_kegiatan, deskripsi, pembimbing, tanggal, foto) VALUES ('$nama_kegiatan', '$deskripsi', '$pembimbing', '$tanggal', '$foto_baru')");
  
  // Ambil ID kegiatan yang baru saja dimasukkan
  $id_kegiatan_baru = mysqli_insert_id($koneksi);

  // Upload Foto Tambahan (Galeri) disesuaikan dengan kolom 'nama_foto' di detail.php
  if(isset($_FILES['foto_tambahan'])) {
      foreach($_FILES['foto_tambahan']['name'] as $key => $val){
          if($val){
              $nama_file_tambahan = time()."_".$val;
              $tmp_tambahan = $_FILES['foto_tambahan']['tmp_name'][$key];
              move_uploaded_file($tmp_tambahan, "uploads/".$nama_file_tambahan);
              
              // Kolom menggunakan 'nama_foto' agar cocok dengan detail.php
              mysqli_query($koneksi, "INSERT INTO foto_kegiatan (id_kegiatan, nama_foto) VALUES ('$id_kegiatan_baru', '$nama_file_tambahan')");
          }
      }
  }

  header("location:index.php"); 
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tambah Kegiatan - ATPH</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Plus Jakarta Sans',sans-serif}
body{min-height:100vh;background:linear-gradient(135deg, #f4f9f4 0%, #fcf5ee 100%);display:flex;align-items:center;justify-content:center;padding:40px 20px;position:relative;overflow-x:hidden}

/* Efek gradasi warna daun & pastel di pojok-pojok agar tidak terlalu polos */
body::before{content:'';position:absolute;top:-50px;left:-50px;width:350px;height:350px;background:radial-gradient(circle, rgba(72,187,120,0.22) 0%, transparent 70%);z-index:0}
body::after{content:'';position:absolute;bottom:-50px;right:-50px;width:400px;height:400px;background:radial-gradient(circle, rgba(221,107,32,0.12) 0%, transparent 70%);z-index:0}

.card{position:relative;z-index:1;width:100%;max-width:480px;background:rgba(255, 255, 255, 0.9);backdrop-filter:blur(10px);border:1px solid rgba(72,187,120,0.2);border-radius:24px;padding:36px 32px;box-shadow:0 20px 40px rgba(0,0,0,0.06)}

.card h2{color:#2d3748;font-size:22px;font-weight:800;margin-bottom:4px}
.card .sub{color:#48bb78;font-size:12px;font-weight:700;margin-bottom:28px;text-transform:uppercase;letter-spacing:0.8px}

label{display:block;color:#2d3748;font-size:12px;font-weight:700;margin:16px 0 8px 2px;letter-spacing:0.5px}
label i{margin-right:6px;color:#48bb78}

input[type=text], input[type=date], textarea{width:100%;padding:13px 16px;border-radius:12px;border:1px solid #cbd5e0;background:#ffffff;color:#2d3748;outline:none;font-size:14px;transition:all 0.3s}
input[type=text]:focus, input[type=date]:focus, textarea:focus{border-color:#48bb78;box-shadow:0 0 0 3px rgba(72,187,120,0.2)}

textarea{resize:vertical;height:90px}

.file-box{width:100%;padding:10px 14px;border-radius:12px;border:1px solid #cbd5e0;background:#ffffff;font-size:13px;color:#718096;margin-bottom:4px}

.btn-group{display:flex;gap:12px;margin-top:28px}
.btn-simpan{flex:1;padding:14px;background:#48bb78;color:#fff;border:none;border-radius:12px;font-weight:700;font-size:14px;cursor:pointer;box-shadow:0 4px 12px rgba(72,187,120,0.3);transition:all 0.2s}
.btn-simpan:hover{background:#38a169}

.batal{flex:1;display:flex;align-items:center;justify-content:center;padding:14px;background:#e53e3e;color:#fff;text-decoration:none;border-radius:12px;font-weight:700;font-size:14px;box-shadow:0 4px 12px rgba(229,62,62,0.3);transition:all 0.2s}
.batal:hover{background:#c53030}
</style>
</head>
<body>
<div class="card">
  <h2>Tambah Kegiatan</h2>
  <div class="sub">Agribisnis Tanaman Pangan & Hortikultura</div>
  
  <form method="post" enctype="multipart/form-data">
    <label><i class="fa-solid fa-clipboard-list"></i> Nama Kegiatan</label>
    <input type="text" name="nama_kegiatan" placeholder="Masukkan nama kegiatan..." required>
    
    <label><i class="fa-solid fa-align-left"></i> Deskripsi</label>
    <textarea name="deskripsi" placeholder="Tuliskan deskripsi kegiatan..." required></textarea>
    
    <label><i class="fa-solid fa-user-tie"></i> Pembimbing</label>
    <input type="text" name="pembimbing" placeholder="Nama guru pembimbing..." required>
    
    <label><i class="fa-solid fa-calendar-days"></i> Tanggal</label>
    <input type="date" name="tanggal" required>
    
    <label><i class="fa-solid fa-image"></i> Foto Utama Kegiatan</label>
    <div class="file-box">
        <input type="file" name="foto" required>
    </div>
    
    <label><i class="fa-solid fa-images"></i> Galeri Dokumentasi Tambahan (Bisa pilih banyak)</label>
    <div class="file-box">
        <input type="file" name="foto_tambahan[]" multiple>
    </div>
    
    <div class="btn-group">
      <button type="submit" name="simpan" class="btn-simpan">Simpan</button>
      <a href="index.php" class="batal">Batal</a>
    </div>
  </form>
</div>
</body>
</html>