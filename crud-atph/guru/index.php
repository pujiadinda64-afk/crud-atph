<?php
include '../config/koneksi.php';
$q = mysqli_query($koneksi, "SELECT * FROM guru ORDER BY Nip DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>DATA GURU ATPH</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="glow"></div>
<h1 class="judul">DATA GURU ATPH</h1>
<p class="sub">AGRIBISNIS TANAMAN PANGAN & HORTIKULTURA</p>

<?php if (isset($_GET['pesan'])): ?>
<div style="max-width:900px;margin:0 auto 18px;padding:12px 16px;border-radius:10px;background:#e8f5e9;color:#1b5e20;font-weight:700;">
<?= htmlspecialchars([
'hapus_sukses'=>'Data guru berhasil dihapus.',
'hapus_gagal'=>'Data guru gagal dihapus.',
'tidak_ditemukan'=>'Data guru tidak ditemukan.'
][$_GET['pesan']] ?? '') ?>
</div>
<?php endif; ?>
<div class="wrap" id="wrap">
<?php
$no=0;
while($d = mysqli_fetch_assoc($q)){
  $no++;
  $foto = "uploads/".$d['Foto'];
  if(empty($d['Foto']) ||!file_exists($foto)){
    $foto = "https://ui-avatars.com/api/?name=".urlencode($d['Nama'])."&background=2e7d32&color=fff&size=300";
  }
  $center = ($no==3)? 'center' : '';
?>
<div class="card <?= $center;?>">
  <img src="<?= $foto;?>">
  <div class="info">
    <div class="name"><?= htmlspecialchars($d['Nama']);?></div>
    <div class="nip">NIP: <?= htmlspecialchars($d['Nip']);?></div>
    <div class="bottom">
      <span><?= htmlspecialchars($d['Mapel_Utama']);?></span>
      <span class="eth"><?= $d['Wali_Kelas']?: 'XI ATPH 1';?></span>
    </div>
    <div class="aksi">
      <a href="edit.php?nip=<?= $d['Nip'];?>" class="edit">Edit</a>
      <a href="hapus.php?nip=<?= $d['Nip'];?>" class="hapus" onclick="return confirm('Hapus?')">Hapus</a>
    </div>
  </div>
</div>
<?php }?>
</div>

<a href="tambah.php" class="tambah">+ Tambah Guru</a>

<script>
const wrap = document.getElementById('wrap');
const cards = document.querySelectorAll('.card');
const defaultCenter = 2; // index ke-3 (0,1,2) yang jadi gede awal

cards.forEach((card, i) => {
  card.addEventListener('mouseenter', () => {
    cards.forEach(c => c.classList.remove('center'));
    card.classList.add('center');
  });
});

// kalau mouse keluar dari area, balikin ke tengah default
wrap.addEventListener('mouseleave', () => {
  cards.forEach(c => c.classList.remove('center'));
  if(cards[defaultCenter]){
    cards[defaultCenter].classList.add('center');
  }
});
</script>

</body>
</html>