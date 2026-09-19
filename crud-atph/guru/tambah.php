<?php
session_start(); // Wajib ada untuk membaca data login

// Cek apakah yang mengakses adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "<script>alert('Akses ditolak! Fitur tambah data khusus untuk Admin.'); window.location='index.php';</script>";
    exit();
}

include '../../koneksi.php';
if(isset($_POST['simpan'])){
  $nip = $_POST['Nip']; $nama = $_POST['Nama']; $mapel = $_POST['Mapel_Utama']; $wali = $_POST['Wali_Kelas'];
  $foto = $_FILES['Foto']['name']; $tmp = $_FILES['Foto']['tmp_name'];
  $foto_baru = "";
  if($foto){ $foto_baru = time()."_".$foto; move_uploaded_file($tmp,"uploads/".$foto_baru); }
  mysqli_query($koneksi,"INSERT INTO guru (Nip,Nama,Mapel_Utama,Wali_Kelas,Foto) VALUES ('$nip','$nama','$mapel','$wali','$foto_baru')");
  header("location:index.php"); exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tambah Guru</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Plus Jakarta Sans',sans-serif}
html{overflow-y:auto}
body{min-height:100vh;background:#0a120a;display:flex;align-items:flex-start;justify-content:center;padding:40px 20px 120px 20px;overflow-y:auto}
body::before{content:'';position:fixed;inset:0;background:radial-gradient(ellipse at center,rgba(60,120,40,0.25) 0%,rgba(10,18,10,1) 70%);z-index:-1}
.card{width:100%;max-width:420px;background:linear-gradient(180deg,rgba(25,45,25,0.9) 0%,rgba(15,30,15,0.95) 100%);border:1px solid rgba(127,255,0,0.15);border-radius:20px;padding:28px 24px 32px 24px;box-shadow:0 0 80px rgba(127,255,0,0.12);margin-top:20px}
.card h2{color:#7FFF00;text-align:center;font-size:24px;font-weight:800}
.card .sub{color:rgba(255,255,255,0.45);text-align:center;font-size:11px;font-weight:700;margin:8px 0 22px;text-transform:uppercase}
label{display:block;text-align:left;color:rgba(255,255,255,0.9);font-size:11px;font-weight:700;margin:18px 0 8px 4px;letter-spacing:0.8px;text-transform:uppercase}
input[type=text]{width:100%;padding:13px 16px;border-radius:12px;border:1px solid rgba(255,255,255,0.08);background:rgba(0,0,0,0.45);color:white;outline:none;font-size:14px}
.file-box{width:100%;padding:10px 12px;border-radius:12px;border:1px solid rgba(255,255,255,0.08);background:rgba(0,0,0,0.45);color:white}
.btn-simpan{width:100%;margin-top:24px;padding:13px;background:#7FFF00;color:#000;border:none;border-radius:12px;font-weight:800;font-size:14px;cursor:pointer;box-shadow:0 0 20px rgba(127,255,0,0.4)}
.batal{display:block;text-align:center;margin-top:16px;padding:12px;color:rgba(255,255,255,0.7);text-decoration:none;font-size:13px;font-weight:600;background:rgba(255,255,255,0.06);border-radius:12px}
</style>
</head>
<body>
<div class="card">
  <h2>Tambah Data Guru</h2>
  <div class="sub">Agribisnis Tanaman Pangan & Hortikultura</div>
  <form method="post" enctype="multipart/form-data">
    <label>NIP</label>
    <input type="text" name="Nip" required placeholder=>
    <label>Nama Guru</label>
    <input type="text" name="Nama" required placeholder=>
    <label>Mapel Utama</label>
    <input type="text" name="Mapel_Utama" required placeholder=>
    <label>Wali Kelas</label>
    <input type="text" name="Wali_Kelas" placeholder=>
    <label>Foto Guru</label>
    <div class="file-box"><input type="file" name="Foto" required></div>
    <button type="submit" name="simpan" class="btn-simpan">Simpan</button>
    <a href="index.php" class="batal">Batal</a>
  </form>
</div>
</body>
</html>