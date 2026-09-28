<?php
session_start(); include '../config/koneksi.php';
if (($_SESSION['role'] ?? '') !== 'admin') { echo "<script>alert('Akses ditolak!');location='index.php';</script>"; exit; }
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT); if(!$id){header('Location:index.php');exit;}
$s=mysqli_prepare($koneksi,'SELECT * FROM jadwal WHERE id_jadwal=?');mysqli_stmt_bind_param($s,'i',$id);mysqli_stmt_execute($s);$d=mysqli_fetch_assoc(mysqli_stmt_get_result($s));mysqli_stmt_close($s);
if(!$d){header('Location:index.php');exit;}
if($_SERVER['REQUEST_METHOD']==='POST'){
 $kegiatan=trim($_POST['kegiatan']??'');$lokasi=trim($_POST['lokasi']??'');$tanggal=$_POST['tanggal']??'';$mulai=$_POST['jam_mulai']??'';$selesai=$_POST['jam_selesai']??'';$pembimbing=trim($_POST['pembimbing']??'');
 $s=mysqli_prepare($koneksi,'UPDATE jadwal SET kegiatan=?,lokasi=?,tanggal=?,jam_mulai=?,jam_selesai=?,pembimbing=? WHERE id_jadwal=?');mysqli_stmt_bind_param($s,'ssssssi',$kegiatan,$lokasi,$tanggal,$mulai,$selesai,$pembimbing,$id);mysqli_stmt_execute($s);mysqli_stmt_close($s);header('Location:index.php?pesan=edit_sukses');exit;
}
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Edit Jadwal</title><style>
body{font-family:Arial;background:#f0f7f4;padding:30px}.card{max-width:600px;margin:auto;background:#fff;padding:28px;border-radius:18px}label{display:block;font-weight:700;margin:12px 0 5px}input{width:100%;box-sizing:border-box;padding:11px;border:1px solid #cbd5e1;border-radius:8px}button{margin-top:20px;padding:11px 18px;border:0;border-radius:8px;background:#2e7d32;color:#fff;font-weight:700}
</style></head><body><div class="card"><h2>Edit Jadwal</h2><form method="post"><label>Kegiatan</label><input name="kegiatan" value="<?=htmlspecialchars($d['kegiatan'])?>" required><label>Lokasi</label><input name="lokasi" value="<?=htmlspecialchars($d['lokasi'])?>" required><label>Tanggal</label><input type="date" name="tanggal" value="<?=htmlspecialchars($d['tanggal'])?>" required><label>Jam Mulai</label><input type="time" name="jam_mulai" value="<?=htmlspecialchars($d['jam_mulai'])?>" required><label>Jam Selesai</label><input type="time" name="jam_selesai" value="<?=htmlspecialchars($d['jam_selesai'])?>" required><label>Pembimbing</label><input name="pembimbing" value="<?=htmlspecialchars($d['pembimbing'])?>" required><button>Simpan Perubahan</button> <a href="index.php">Batal</a></form></div></body></html>
