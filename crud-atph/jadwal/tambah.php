<?php
session_start();
include '../config/koneksi.php';
if (($_SESSION['role'] ?? '') !== 'admin') { echo "<script>alert('Akses ditolak!');location='index.php';</script>"; exit; }
if ($_SERVER['REQUEST_METHOD']==='POST') {
 $kegiatan=trim($_POST['kegiatan']??''); $lokasi=trim($_POST['lokasi']??''); $tanggal=$_POST['tanggal']??'';
 $mulai=$_POST['jam_mulai']??''; $selesai=$_POST['jam_selesai']??''; $pembimbing=trim($_POST['pembimbing']??'');
 if($kegiatan!==''&&$lokasi!==''&&$tanggal!==''&&$mulai!==''&&$selesai!==''&&$pembimbing!==''){
  $s=mysqli_prepare($koneksi,'INSERT INTO jadwal (kegiatan,lokasi,tanggal,jam_mulai,jam_selesai,pembimbing) VALUES (?,?,?,?,?,?)');
  mysqli_stmt_bind_param($s,'ssssss',$kegiatan,$lokasi,$tanggal,$mulai,$selesai,$pembimbing); mysqli_stmt_execute($s); mysqli_stmt_close($s);
  header('Location: index.php?pesan=tambah_sukses'); exit;
 }
 $error='Semua kolom wajib diisi.';
}
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Tambah Jadwal</title><style>
body{font-family:Arial;background:#f0f7f4;padding:30px}.card{max-width:600px;margin:auto;background:#fff;padding:28px;border-radius:18px}label{display:block;font-weight:700;margin:12px 0 5px}input{width:100%;box-sizing:border-box;padding:11px;border:1px solid #cbd5e1;border-radius:8px}button{margin-top:20px;padding:11px 18px;border:0;border-radius:8px;background:#2e7d32;color:#fff;font-weight:700}.error{color:#991b1b;background:#fee2e2;padding:10px;border-radius:8px}</style></head><body><div class="card"><h2>Tambah Jadwal</h2><?php if(!empty($error)): ?><div class="error"><?=htmlspecialchars($error)?></div><?php endif; ?><form method="post">
<label>Kegiatan</label><input name="kegiatan" required><label>Lokasi</label><input name="lokasi" required><label>Tanggal</label><input type="date" name="tanggal" required><label>Jam Mulai</label><input type="time" name="jam_mulai" required><label>Jam Selesai</label><input type="time" name="jam_selesai" required><label>Pembimbing</label><input name="pembimbing" required><button>Simpan</button> <a href="index.php">Batal</a></form></div></body></html>
