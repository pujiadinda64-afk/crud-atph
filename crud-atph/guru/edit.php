<?php
include '../koneksi.php';

$nip_lama = $_GET['nip'];
$query = mysqli_query($koneksi, "SELECT * FROM guru WHERE Nip='$nip_lama'");
$data = mysqli_fetch_assoc($query);

if(isset($_POST['update'])){
  $nip = $_POST['nip'];
  $nama = $_POST['nama'];
  $mapel = $_POST['mapel'];
  $wali = $_POST['wali'];
  $foto_lama = $_POST['foto_lama'];

  // kalau upload foto baru
  if($_FILES['foto']['name'] != ""){
    $foto = $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];
    $foto_baru = time().'_'.$foto;
    move_uploaded_file($tmp, 'uploads/'.$foto_baru);
    
    if(file_exists('uploads/'.$foto_lama)){
      unlink('uploads/'.$foto_lama);
    }
  } else {
    $foto_baru = $foto_lama;
  }

  mysqli_query($koneksi, "UPDATE guru SET Nip='$nip', Nama='$nama', Mapel_Utama='$mapel', Wali_Kelas='$wali', Foto='$foto_baru' WHERE Nip='$nip_lama'");
  
  header("Location: index.php");
  exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Edit Guru</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="form-wrapper">
  <div class="form-box">
    <div class="form-header">
      <h1>Edit Data Guru</h1>
      <p><?= $data['Nama']; ?> - <?= $data['Nip']; ?></p>
    </div>
    <form method="POST" enctype="multipart/form-data">
      <input type="hidden" name="foto_lama" value="<?= $data['Foto']; ?>">
      
      <div class="form-group"><label>NIP</label><input type="text" name="nip" value="<?= $data['Nip']; ?>" required></div>
      <div class="form-group"><label>Nama Guru</label><input type="text" name="nama" value="<?= $data['Nama']; ?>" required></div>
      <div class="form-group"><label>Mapel Utama</label><input type="text" name="mapel" value="<?= $data['Mapel_Utama']; ?>" required></div>
      <div class="form-group"><label>Wali Kelas</label><input type="text" name="wali" value="<?= $data['Wali_Kelas']; ?>"></div>
      
      <div class="form-group">
        <label>Foto Lama</label><br>
        <img src="uploads/<?= $data['Foto']; ?>" style="width:100px; border-radius:8px;" onerror="this.src='https://via.placeholder.com/100'">
      </div>

      <div class="form-group"><label>Ganti Foto (Kosongkan jika tidak ganti)</label><input type="file" name="foto"></div>
      
      <div class="btn-group">
        <button type="submit" name="update" class="btn btn-simpan">Update</button>
        <a href="index.php" class="btn btn-batal">Batal</a>
      </div>
    </form>
  </div>
</div>
</body>
</html>