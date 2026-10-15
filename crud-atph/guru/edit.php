<?php
session_start();

if (($_SESSION['role'] ?? '') !== 'admin') {
    echo "<script>alert('Akses ditolak! Halaman ini khusus untuk Admin.'); window.location='index.php';</script>";
    exit();
}

if(file_exists("../../koneksi.php")) include "../../koneksi.php";
elseif(file_exists("../koneksi.php")) include "../koneksi.php";
else include "koneksi.php";
if(isset($koneksi) && !isset($conn)) $conn = $koneksi;

$nip_url = $_GET['nip'] ?? '';
$nip_url = mysqli_real_escape_string($conn, $nip_url);
$data_q = mysqli_query($conn, "SELECT * FROM guru WHERE nip='$nip_url'");
$data = mysqli_fetch_assoc($data_q);
if(!$data) die("Data NIP $nip_url tidak ditemukan");

if(isset($_POST['update'])){
    $nip_lama = mysqli_real_escape_string($conn, $_POST['nip_lama']);
    $nip = mysqli_real_escape_string($conn, $_POST['nip']);
    $Nama = mysqli_real_escape_string($conn, $_POST['Nama']);
    $Mapel = mysqli_real_escape_string($conn, $_POST['Mapel_Utama']);
    $Wali = mysqli_real_escape_string($conn, $_POST['Wali_Kelas']);
    $foto_nama = $data['Foto'];

    // Proses upload foto ke folder uploads di dalam folder guru
    if(isset($_FILES['Foto']) && $_FILES['Foto']['error'] == 0){
        $ext = pathinfo($_FILES['Foto']['name'], PATHINFO_EXTENSION);
        $foto_nama = $nip."_".time().".".$ext;
        
        $dir = "uploads/";
        if(!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        
        // Hapus foto lama jika ada di folder uploads
        if(!empty($data['Foto']) && file_exists($dir . $data['Foto'])) {
            unlink($dir . $data['Foto']);
        }
        
        move_uploaded_file($_FILES['Foto']['tmp_name'], $dir . $foto_nama);
    }

    $update = mysqli_query($conn, "UPDATE guru SET nip='$nip', Nama='$Nama', Mapel_Utama='$Mapel', Wali_Kelas='$Wali', Foto='$foto_nama' WHERE nip='$nip_lama'");
    if($update){
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Guru</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI',Arial}
body{min-height:100vh;background:url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=1920') no-repeat center fixed;background-size:cover;display:flex;justify-content:center;align-items:center;padding:20px;position:relative}
body::before{content:'';position:absolute;inset:0;background:rgba(16,58,29,0.7);backdrop-filter:blur(2px)}
.card{position:relative;z-index:2;width:100%;max-width:480px;background:linear-gradient(180deg,rgba(40,80,50,0.85),rgba(20,50,30,0.9));backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,0.1);border-radius:16px;padding:35px;box-shadow:0 10px 30px rgba(0,0,0,0.4)}
.card h2{text-align:center;color:#a8ff78;font-size:28px;font-weight:800}.sub{text-align:center;color:rgba(255,255,255,0.8);font-size:12px;margin:8px 0 28px;font-weight:600;letter-spacing:.8px}
label{display:block;color:#d1e7c9;font-size:14px;font-weight:700;margin:18px 0 8px}
input[type=text]{width:100%;padding:14px 16px;background:rgba(0,0,0,0.35);border:1px solid rgba(255,255,255,0.15);border-radius:12px;color:white;outline:none}
input[type=text]:focus{border-color:#a8ff78;background:rgba(0,0,0,0.5)}
.file-box{width:100%;padding:10px 12px;background:rgba(0,0,0,0.35);border:1px solid rgba(255,255,255,0.15);border-radius:12px;color:#ccc}
.foto-lama{margin-top:10px;color:#a8ff78;font-size:12px}.foto-lama img{width:80px;height:80px;object-fit:cover;border-radius:10px;margin-top:5px;border:2px solid rgba(168,255,120,0.3)}
.btn{width:100%;margin-top:25px;padding:14px;background:linear-gradient(90deg,#7ed957,#5fbb3f);color:#0f2a15;border:none;border-radius:12px;font-weight:800;cursor:pointer}
.back{display:block;text-align:center;margin-top:15px;color:rgba(255,255,255,0.6);text-decoration:none;font-size:13px}
</style>
</head>
<body>
<div class="card">
<h2>Edit Data Guru</h2>
<div class="sub">AGRIBISNIS TANAMAN PANGAN & HORTIKULTURA</div>
<form method="POST" enctype="multipart/form-data">
<input type="hidden" name="nip_lama" value="<?= htmlspecialchars($data['nip']) ?>">
<label>NIP</label><input type="text" name="nip" value="<?= htmlspecialchars($data['nip']) ?>" required>
<label>Nama Guru</label><input type="text" name="Nama" value="<?= htmlspecialchars($data['Nama']) ?>" required>
<label>Mapel Utama</label><input type="text" name="Mapel_Utama" value="<?= htmlspecialchars($data['Mapel_Utama']) ?>" required>
<label>Wali Kelas</label><input type="text" name="Wali_Kelas" value="<?= htmlspecialchars($data['Wali_Kelas']) ?>">
<label>Foto Guru</label><div class="file-box"><input type="file" name="Foto" accept="image/*"></div>
<?php if(!empty($data['Foto'])): ?>
<div class="foto-lama">Foto saat ini: <?= htmlspecialchars($data['Foto']) ?><br><img src="uploads/<?= htmlspecialchars($data['Foto']) ?>" onerror="this.src='../foto/<?= htmlspecialchars($data['Foto']) ?>'" alt=""></div>
<?php endif; ?>
<button type="submit" name="update" class="btn">UPDATE DATA</button>
</form>
<a href="index.php" class="back">← Kembali ke Data Guru</a>
</div>
</body>
</html>