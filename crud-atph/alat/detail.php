<?php
declare(strict_types=1);

session_start();
include "../../koneksi.php";

/** @var mysqli $koneksi */

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    header('Location: index.php?pesan=tidak_ditemukan');
    exit;
}

$stmt = mysqli_prepare($koneksi, 'SELECT * FROM alat WHERE id_alat = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$alat = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$alat) {
    header('Location: index.php?pesan=tidak_ditemukan');
    exit;
}

$fotoPath = __DIR__ . '/../uploads/' . ($alat['foto'] ?? '');
$adaFoto  = !empty($alat['foto']) && is_file($fotoPath);

$PALET = [
  ["#16A34A","#E8F8EE"],["#F59E0B","#FEF3C7"],["#0EA5E9","#E0F2FE"],["#EC4899","#FCE7F3"],
  ["#8B5CF6","#EDE9FE"],["#EF4444","#FEE2E2"],["#14B8A6","#CCFBF1"],["#F97316","#FFEDD5"],
  ["#84CC16","#ECFCCB"],["#6366F1","#E0E7FF"],
];
$acc = $PALET[$alat['id_alat'] % count($PALET)];

function tanggalIndonesia(): string {
    $hari = ["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"];
    $bulan = ["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
    return $hari[date("w")] . ", " . date("j") . " " . $bulan[(int)date("n")] . " " . date("Y");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($alat['nama_alat']) ?> — Kebun Alat</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600;700&family=Nunito+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
  :root{
    --green:#16A34A; --green-dark:#0F5D30; --green-soft:#E8F8EE;
    --ink:#1B3A24; --ink-soft:#5C7A65; --line:#D3EEDC; --red:#DC2626; --red-soft:#FEE2E2;
    --acc:<?= $acc[0] ?>; --acc-bg:<?= $acc[1] ?>;
    --shadow: 0 10px 26px rgba(15,93,48,.12);
  }
  *{box-sizing:border-box;}
  body{
    margin:0; font-family:'Nunito Sans',sans-serif; color:var(--ink); min-height:100vh; position:relative;
    background:
      radial-gradient(1400px 620px at 88% -12%, #BFEDD2 0%, transparent 58%),
      radial-gradient(1100px 560px at -12% 8%, #D8F7E2 0%, transparent 52%),
      linear-gradient(180deg, #EFFBF3 0%, #FFFFFF 22%, #FFFFFF 78%, #EAF9EF 100%);
  }
  .leaf-pattern{
    position:fixed; inset:0; z-index:-1; pointer-events:none; opacity:.5;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120'%3E%3Cg fill='none' stroke='%2316A34A' stroke-width='2' opacity='0.16'%3E%3Cpath d='M20 90 Q20 55 55 40 Q40 70 20 90Z'/%3E%3Cpath d='M20 90 Q35 65 55 40'/%3E%3C/g%3E%3C/svg%3E");
    background-size:120px 120px;
  }
  h1,h2,h3{font-family:'Fredoka',sans-serif; margin:0;}
  .topbar{background:linear-gradient(120deg, #16A34A 0%, #1E9E5A 45%, #0F5D30 100%);}
  .topbar-inner{max-width:900px; margin:0 auto; padding:20px 6vw; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;}
  .brand{display:flex; align-items:center; gap:10px;}
  .brand .leaf{width:38px; height:38px; border-radius:12px; background:rgba(255,255,255,.18); display:flex; align-items:center; justify-content:center; color:#fff; font-size:20px; border:1px solid rgba(255,255,255,.35);}
  .brand h1{font-size:20px; color:#fff;}
  .brand span{font-size:12px; color:rgba(255,255,255,.85); font-weight:700;}
  .date-chip{background:rgba(255,255,255,.16); color:#fff; border:1px solid rgba(255,255,255,.35); border-radius:999px; padding:9px 16px; font-size:13px; font-weight:700;}

  main{max-width:900px; margin:0 auto; padding:34px 6vw 70px;}
  .backlink{display:inline-flex; align-items:center; gap:6px; color:var(--green-dark); font-weight:800; font-size:13.5px; text-decoration:none; margin-bottom:18px;}
  .backlink:hover{text-decoration:underline;}

  .detail-card{background:#fff; border:1px solid var(--line); border-radius:26px; padding:34px; box-shadow:var(--shadow); border-top:8px solid var(--acc);}
  .top-row{display:flex; gap:26px; flex-wrap:wrap; align-items:center;}
  .icon-big{width:170px; height:170px; border-radius:24px; background:radial-gradient(circle at 30% 25%, var(--acc-bg) 0%, var(--acc-bg) 40%, #ffffff 100%); display:flex; align-items:center; justify-content:center; overflow:hidden; flex:0 0 auto; position:relative; border:1px solid var(--line);}
  .icon-big img{width:100%; height:100%; object-fit:cover;}
  .icon-big .avatar-fallback{position:relative; width:100%; height:100%; display:flex; align-items:center; justify-content:center;}
  .icon-big .avatar-ring{position:absolute; width:118px; height:118px; border-radius:50%; border:2px dashed var(--acc); opacity:.35;}
  .icon-big .avatar-icon{position:absolute; width:100px; height:100px; opacity:.16; fill:var(--acc);}
  .icon-big .huruf{position:relative; font-size:58px; font-weight:800; color:var(--acc); text-shadow:0 2px 0 rgba(255,255,255,.6); font-family:'Fredoka',sans-serif;}
  .title-block{flex:1; min-width:220px;}
  .idtag{font-size:12.5px; font-weight:800; color:var(--acc); background:var(--acc-bg); display:inline-block; padding:4px 12px; border-radius:999px;}
  .title-block h2{font-size:28px; margin-top:10px; color:var(--ink);}

  .grid-info{display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-top:26px;}
  @media (max-width:500px){ .grid-info{grid-template-columns:1fr;} }
  .info-box{background:var(--acc-bg); border-radius:16px; padding:16px 18px;}
  .info-box h4{font-size:11.5px; text-transform:uppercase; letter-spacing:.06em; color:var(--ink-soft); font-weight:800;}
  .info-box p{margin:6px 0 0; font-size:20px; font-weight:800; color:var(--ink);}

  .sec{margin-top:26px; padding-top:22px; border-top:1px solid var(--line);}
  .sec h4{font-size:12px; text-transform:uppercase; letter-spacing:.06em; color:var(--ink-soft); font-weight:800;}
  .sec p{margin:8px 0 0; font-size:14px; color:var(--ink-soft);}

  .actions{display:flex; gap:12px; margin-top:28px; flex-wrap:wrap;}
  .btn{border:none; border-radius:999px; padding:11px 22px; font-weight:800; font-size:13.5px; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:8px;}
  .btn-primary{background:var(--green); color:#fff; box-shadow:var(--shadow);}
  .btn-edit{background:var(--green-soft); color:var(--green-dark);}
  .btn-danger{background:var(--red-soft); color:var(--red);}
</style>
</head>
<body>
<div class="leaf-pattern"></div>

<div class="topbar">
  <div class="topbar-inner">
    <div class="brand">
      <div class="leaf">🌿</div>
      <div><h1>Kebun Alat</h1><span>DETAIL ALAT</span></div>
    </div>
    <div class="date-chip"><?= tanggalIndonesia() ?></div>
  </div>
</div>

<main>
  <a class="backlink" href="index.php">← Kembali ke Katalog</a>

  <div class="detail-card">
    <div class="top-row">
      <div class="icon-big">
        <?php if ($adaFoto): ?>
          <img src="../uploads/<?= htmlspecialchars($alat['foto']) ?>" alt="<?= htmlspecialchars($alat['nama_alat']) ?>">
        <?php else: ?>
          <div class="avatar-fallback">
            <div class="avatar-ring"></div>
            <svg class="avatar-icon" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
              <path d="M45 8 L45 60" stroke-width="8" stroke="currentColor" stroke-linecap="round"/>
              <path d="M28 60 Q49 64 70 60 L64 80 Q49 90 34 80 Z"/>
            </svg>
            <span class="huruf"><?= strtoupper(mb_substr($alat['nama_alat'], 0, 1)) ?></span>
          </div>
        <?php endif; ?>
      </div>
      <div class="title-block">
        <span class="idtag">ID #<?= $alat['id_alat'] ?></span>
        <h2><?= htmlspecialchars($alat['nama_alat']) ?></h2>
      </div>
    </div>

    <div class="grid-info">
      <div class="info-box">
        <h4>Jumlah Stok</h4>
        <p><?= (int) $alat['jumlah_stok'] ?> unit</p>
      </div>
      <div class="info-box">
        <h4>Kondisi</h4>
        <p><?= htmlspecialchars($alat['kondisi']) ?></p>
      </div>
    </div>

    <?php if (!empty($alat['dibuat_pada'])): ?>
    <div class="sec">
      <h4>Ditambahkan Pada</h4>
      <p><?= htmlspecialchars($alat['dibuat_pada']) ?></p>
    </div>
    <?php endif; ?>

    <div class="actions">
      <a class="btn btn-primary" href="index.php">🌾 Lihat Semua Alat</a>
      <a class="btn btn-edit" href="edit.php?id=<?= $alat['id_alat'] ?>">✏️ Edit Alat</a>
      <a class="btn btn-danger" href="hapus.php?id=<?= $alat['id_alat'] ?>" onclick="return confirm('Hapus &quot;<?= htmlspecialchars(addslashes($alat['nama_alat'])) ?>&quot; dari daftar?');">🗑️ Hapus Alat Ini</a>
    </div>
  </div>
</main>
</body>
</html>