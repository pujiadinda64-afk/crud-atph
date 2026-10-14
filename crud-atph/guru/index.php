<?php
include '../config/koneksi.php';
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED);
$q = mysqli_query($koneksi, "SELECT * FROM guru ORDER BY 1 ASC");
$all=[]; while($r=mysqli_fetch_assoc($q)){$r=array_change_key_case($r,CASE_LOWER); $all[]=$r;}
function getInitials($nama){$p=explode(" ",trim($nama));if(count($p)>=2)return strtoupper(substr($p[0],0,1).substr(end($p),0,1));return strtoupper(substr($nama,0,2));}
?>
<!DOCTYPE html><html lang="id"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>DATA GURU ATPH</title><link rel="stylesheet" href="style.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
</head><body>

<div class="bg"></div>

<div class="top-nav">
  <a href="../index.php" class="btn-nav">Dashboard Utama</a>
  <a href="../admin/index.php" class="btn-nav active">Panel Admin</a>
  <a href="tambah.php" class="btn-nav" style="background:#f1f5f9;color:#0f2a15;font-weight:800;">+ Tambah Data</a>
</div>

<div class="header">
  <h1>DATA GURU ATPH</h1>
  <p>AGRIBISNIS TANAMAN PANGAN & HORTIKULTURA</p>
</div>

<div class="carousel" id="carousel">
<?php foreach($all as $d){
  $nama=$d['nama']??$d['nama_guru']??'Guru'; $nip=$d['nip']??''; $mapel=$d['mapel_utama']??$d['mapel']??'Dasar'; $kelas=trim($d['wali_kelas']??$d['kelas']??'');
  $foto_file=$d['foto']??''; $has=file_exists("uploads/".$foto_file)&&$foto_file!=''; $inisial=getInitials($nama);
?>
<div class="card" data-nip="<?= $nip?>">
  <?php if($has){?>
    <img src="uploads/<?= $foto_file?>" class="foto-real" draggable="false">
  <?php } else {?>
    <div class="foto-fake"><span class="inisial"><?= $inisial?></span></div>
  <?php }?>
  <div class="info">
    <div class="name-kecil"><?= htmlspecialchars($nama)?></div>
    <div class="nip-kecil">NIP: <?= htmlspecialchars($nip)?></div>
    <div class="mapel-kecil">Mapel: <?= htmlspecialchars($mapel)?></div>
    <div class="bottom2"><span class="badge-dasar"><?= htmlspecialchars($mapel)?></span><?php if($kelas!==''):?><span class="kelas-label"><?= htmlspecialchars($kelas)?></span><?php endif;?></div>
    <div class="aksi"><a href="edit.php?nip=<?= urlencode($nip)?>" class="edit">Edit</a><a href="hapus.php?nip=<?= urlencode($nip)?>" class="hapus" onclick="return confirm('Hapus?')">Hapus</a></div>
  </div>
</div>
<?php }?>
</div>

<div class="controls">
  <button id="prev">‹</button>
  <div class="dots" id="dots"></div>
  <button id="next">›</button>
</div>

<script>
const cards=document.querySelectorAll('.card');let cur=0;const total=cards.length;
const dotsEl=document.getElementById('dots');
for(let i=0;i<total;i++){let d=document.createElement('div');d.className='dot';d.onclick=()=>{cur=i;update()};dotsEl.appendChild(d);}
function update(){
  cards.forEach((c,i)=>{
    let o=i-cur; if(o>total/2)o-=total; if(o<-total/2)o+=total;
    c.classList.remove('active');
    if(o===0){c.style.transform='translateX(0) scale(1.15)';c.style.zIndex=10;c.style.opacity=1;c.classList.add('active');}
    else if(o===1||o===-1){c.style.transform=`translateX(${o*175}px) scale(0.92)`;c.style.zIndex=5;c.style.opacity=.85;}
    else if(o===2||o===-2){c.style.transform=`translateX(${o*200}px) scale(0.78)`;c.style.zIndex=2;c.style.opacity=.5;}
    else{c.style.transform=`translateX(${o*220}px) scale(0.6)`;c.style.zIndex=0;c.style.opacity=0;}
  });
  document.querySelectorAll('.dot').forEach((d,i)=>d.classList.toggle('active',i===cur));
}
cards.forEach((c,i)=>{c.addEventListener('click',e=>{if(e.target.closest('.aksi'))return; if(i!==cur){cur=i;update();}});});
document.getElementById('prev').onclick=()=>{cur=(cur-1+total)%total;update();}
document.getElementById('next').onclick=()=>{cur=(cur+1)%total;update();}
let sX=0;const car=document.getElementById('carousel');
car.addEventListener('touchstart',e=>{sX=e.touches[0].clientX},{passive:true});
car.addEventListener('touchend',e=>{let d=e.changedTouches[0].clientX-sX; if(d<-50){cur=(cur+1)%total;update();} if(d>50){cur=(cur-1+total)%total;update();}});
update();
</script>
</body></html>