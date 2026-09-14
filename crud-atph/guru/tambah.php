<?php 
include '../../koneksi.php';
if(isset($_POST['simpan'])){
  $nip = $_POST['nip']; $nama = $_POST['nama']; $mapel = $_POST['mapel_utama']; $wali = $_POST['wali_kelas'];
  $foto_baru = '';
  if($_FILES['foto']['error'] === 0){
    $foto_baru = time() . '_' . basename($_FILES['foto']['name']);
    move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/" . $foto_baru);
  }
  // FIX: ? nya 5
  $stmt = $koneksi->prepare("INSERT INTO guru (Nip, Nama, Mapel_Utama, Wali_Kelas, Foto) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sssss", $nip, $nama, $mapel, $wali, $foto_baru);
  if($stmt->execute()){ echo "<script>alert('Berhasil');window.location='index.php';</script>"; }
}
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title>Tambah Guru</title><link rel="stylesheet" href="style.css"></head><body>
<div class="form-wrapper"><div class="form-box">
  <div class="form-header"><h1>Tambah Data Guru</h1><p>AGRIBISNIS TANAMAN PANGAN & HORTIKULTURA</p></div>
  <form method="POST" enctype="multipart/form-data">
    <div class="form-group"><label>NIP</label><input type="text" name="nip" required></div>
    <div class="form-group"><label>Nama Guru</label><input type="text" name="nama" required></div>
    <div class="form-group"><label>Mapel Utama</label><input type="text" name="mapel_utama" required></div>
    <div class="form-group"><label>Wali Kelas</label><input type="text" name="wali_kelas"></div>
    <div class="form-group"><label>Foto Guru</label><input type="file" name="foto" accept="image/*" required></div>
    <div class="btn-group"><button type="submit" name="simpan" class="btn btn-simpan">Simpan</button><a href="index.php" class="btn btn-batal">Batal</a></div>
  </form>
</div></div></body></html>