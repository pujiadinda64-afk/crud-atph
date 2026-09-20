<?php
include "../koneksi.php";

/** @var mysqli $koneksi */

if (!isset($_GET["id"]) || !ctype_digit($_GET["id"])) {
    header("Location: index.php");
    exit;
}
$id = (int) $_GET["id"];

$stmt = mysqli_prepare($koneksi, "SELECT * FROM alat WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$hasil = mysqli_stmt_get_result($stmt);
$alat = mysqli_fetch_assoc($hasil);
mysqli_stmt_close($stmt);

if (!$alat) {
    header("Location: index.php?pesan=tidak_ditemukan");
    exit;
}

// ilustrasi bawaan (sama seperti di index.php)
$ICONS = [
  "cangkul" => '<svg viewBox="0 0 100 100"><rect x="45" y="8" width="8" height="52" rx="4" fill="#A9713F"/><path d="M28 58 Q49 62 70 58 L64 78 Q49 88 34 78 Z" fill="#9CA3AF"/><path d="M28 58 Q49 62 70 58 L67 66 Q49 70 31 66 Z" fill="#CBD5E1"/></svg>',
  "garpu" => '<svg viewBox="0 0 100 100"><rect x="45" y="6" width="8" height="48" rx="4" fill="#A9713F"/><rect x="26" y="54" width="6" height="34" rx="3" fill="#94A3B8"/><rect x="47" y="54" width="6" height="34" rx="3" fill="#94A3B8"/><rect x="68" y="54" width="6" height="34" rx="3" fill="#94A3B8"/><rect x="22" y="48" width="56" height="10" rx="5" fill="#6B7280"/></svg>',
  "bajak" => '<svg viewBox="0 0 100 100"><path d="M14 78 Q40 78 46 54 Q52 32 78 24" stroke="#6B7280" stroke-width="9" fill="none" stroke-linecap="round"/><path d="M68 14 L86 30" stroke="#A9713F" stroke-width="8" stroke-linecap="round"/></svg>',
  "traktor" => '<svg viewBox="0 0 100 100"><circle cx="32" cy="70" r="15" fill="#374151"/><circle cx="32" cy="70" r="7" fill="#9CA3AF"/><circle cx="72" cy="70" r="15" fill="#374151"/><circle cx="72" cy="70" r="7" fill="#9CA3AF"/><path d="M32 55 L32 28 L74 28 L74 42" stroke="#16A34A" stroke-width="9" fill="none" stroke-linecap="round"/><rect x="56" y="16" width="14" height="16" rx="3" fill="#16A34A"/></svg>',
  "garu" => '<svg viewBox="0 0 100 100"><line x1="18" y1="22" x2="18" y2="60" stroke="#6B7280" stroke-width="6" stroke-linecap="round"/><line x1="36" y1="22" x2="36" y2="60" stroke="#6B7280" stroke-width="6" stroke-linecap="round"/><line x1="54" y1="22" x2="54" y2="60" stroke="#6B7280" stroke-width="6" stroke-linecap="round"/><line x1="72" y1="22" x2="72" y2="60" stroke="#6B7280" stroke-width="6" stroke-linecap="round"/><rect x="12" y="18" width="66" height="10" rx="5" fill="#A9713F"/></svg>',
  "kored" => '<svg viewBox="0 0 100 100"><rect x="46" y="8" width="8" height="42" rx="4" fill="#A9713F"/><path d="M30 50 Q50 68 70 52 L60 40 Q50 46 40 40 Z" fill="#9CA3AF"/></svg>',
  "tugal" => '<svg viewBox="0 0 100 100"><rect x="46" y="6" width="8" height="60" rx="4" fill="#A9713F"/><path d="M38 66 L62 66 L50 88 Z" fill="#6B7280"/></svg>',
  "tray" => '<svg viewBox="0 0 100 100"><rect x="14" y="26" width="72" height="48" rx="6" fill="#38BDF8"/><line x1="14" y1="50" x2="86" y2="50" stroke="#0284C7" stroke-width="2"/><line x1="32" y1="26" x2="32" y2="74" stroke="#0284C7" stroke-width="2"/><line x1="50" y1="26" x2="50" y2="74" stroke="#0284C7" stroke-width="2"/><line x1="68" y1="26" x2="68" y2="74" stroke="#0284C7" stroke-width="2"/><circle cx="23" cy="38" r="4" fill="#4ADE80"/><circle cx="41" cy="62" r="4" fill="#4ADE80"/><circle cx="77" cy="38" r="4" fill="#4ADE80"/></svg>',
  "ajirbaris" => '<svg viewBox="0 0 100 100"><line x1="18" y1="80" x2="18" y2="30" stroke="#A9713F" stroke-width="7" stroke-linecap="round"/><line x1="82" y1="80" x2="82" y2="30" stroke="#A9713F" stroke-width="7" stroke-linecap="round"/><line x1="18" y1="50" x2="82" y2="50" stroke="#F59E0B" stroke-width="3" stroke-dasharray="6 5"/></svg>',
  "transplanter" => '<svg viewBox="0 0 100 100"><rect x="44" y="8" width="8" height="42" rx="4" fill="#A9713F"/><path d="M30 50 L66 50 L56 84 L40 84 Z" fill="#9CA3AF"/><circle cx="48" cy="68" r="6" fill="#4ADE80"/></svg>',
  "gembor" => '<svg viewBox="0 0 100 100"><ellipse cx="40" cy="60" rx="28" ry="18" fill="#38BDF8"/><path d="M62 46 L90 24" stroke="#0284C7" stroke-width="7" stroke-linecap="round"/><ellipse cx="88" cy="20" rx="8" ry="6" fill="#0EA5E9"/><path d="M22 44 Q40 30 58 44" stroke="#0284C7" stroke-width="6" fill="none" stroke-linecap="round"/></svg>',
  "sprayer" => '<svg viewBox="0 0 100 100"><rect x="30" y="16" width="34" height="52" rx="10" fill="#4ADE80"/><rect x="40" y="8" width="14" height="10" rx="2" fill="#16A34A"/><path d="M64 56 L88 76" stroke="#6B7280" stroke-width="6" stroke-linecap="round"/><path d="M30 56 L14 70" stroke="#6B7280" stroke-width="6" stroke-linecap="round"/></svg>',
  "gunting" => '<svg viewBox="0 0 100 100"><path d="M20 16 L74 70" stroke="#EF4444" stroke-width="7" stroke-linecap="round"/><path d="M74 16 L20 70" stroke="#DC2626" stroke-width="7" stroke-linecap="round"/><circle cx="20" cy="16" r="8" fill="#9CA3AF"/><circle cx="74" cy="16" r="8" fill="#9CA3AF"/></svg>',
  "ajir" => '<svg viewBox="0 0 100 100"><line x1="34" y1="14" x2="34" y2="88" stroke="#A9713F" stroke-width="6" stroke-linecap="round"/><path d="M34 40 Q54 34 54 48 Q54 62 34 58" stroke="#4ADE80" stroke-width="5" fill="none"/></svg>',
  "phmeter" => '<svg viewBox="0 0 100 100"><rect x="34" y="10" width="32" height="30" rx="6" fill="#0EA5E9"/><text x="50" y="30" font-size="14" fill="#fff" text-anchor="middle" font-family="Arial">pH</text><line x1="50" y1="40" x2="50" y2="86" stroke="#6B7280" stroke-width="6" stroke-linecap="round"/></svg>',
  "sabit" => '<svg viewBox="0 0 100 100"><path d="M20 84 L34 30" stroke="#A9713F" stroke-width="7" stroke-linecap="round"/><path d="M34 30 Q78 16 66 56 Q56 78 26 68" fill="none" stroke="#9CA3AF" stroke-width="7" stroke-linecap="round"/></svg>',
  "aniani" => '<svg viewBox="0 0 100 100"><rect x="24" y="40" width="44" height="16" rx="4" fill="#A9713F"/><path d="M68 44 L84 32" stroke="#9CA3AF" stroke-width="5" stroke-linecap="round"/></svg>',
  "guntingpanen" => '<svg viewBox="0 0 100 100"><path d="M26 20 L70 64" stroke="#F97316" stroke-width="7" stroke-linecap="round"/><path d="M70 20 L26 64" stroke="#EA580C" stroke-width="7" stroke-linecap="round"/><circle cx="26" cy="20" r="7" fill="#9CA3AF"/><circle cx="70" cy="20" r="7" fill="#9CA3AF"/></svg>',
  "keranjang" => '<svg viewBox="0 0 100 100"><path d="M18 36 L82 36 L72 82 L28 82 Z" fill="#F59E0B"/><path d="M30 36 Q50 12 70 36" fill="none" stroke="#B45309" stroke-width="5"/></svg>',
  "garukpanen" => '<svg viewBox="0 0 100 100"><rect x="46" y="6" width="8" height="46" rx="4" fill="#A9713F"/><line x1="28" y1="52" x2="28" y2="80" stroke="#6B7280" stroke-width="6" stroke-linecap="round"/><line x1="50" y1="52" x2="50" y2="80" stroke="#6B7280" stroke-width="6" stroke-linecap="round"/><line x1="72" y1="52" x2="72" y2="80" stroke="#6B7280" stroke-width="6" stroke-linecap="round"/><rect x="22" y="48" width="56" height="9" rx="4" fill="#4B5563"/></svg>',
  "timbangan" => '<svg viewBox="0 0 100 100"><line x1="50" y1="14" x2="50" y2="66" stroke="#6B7280" stroke-width="6" stroke-linecap="round"/><line x1="22" y1="28" x2="78" y2="28" stroke="#6B7280" stroke-width="5" stroke-linecap="round"/><path d="M12 28 Q22 48 32 28" fill="none" stroke="#16A34A" stroke-width="5"/><path d="M68 28 Q78 48 88 28" fill="none" stroke="#16A34A" stroke-width="5"/><rect x="36" y="66" width="28" height="10" rx="3" fill="#A9713F"/></svg>',
  "terpal" => '<svg viewBox="0 0 100 100"><path d="M12 72 L88 72 L74 34 L26 34 Z" fill="#38BDF8"/><circle cx="40" cy="58" r="3" fill="#FDE68A"/><circle cx="56" cy="52" r="3" fill="#FDE68A"/><circle cx="66" cy="60" r="3" fill="#FDE68A"/></svg>',
  "ayakan" => '<svg viewBox="0 0 100 100"><circle cx="50" cy="50" r="34" fill="#FDE68A"/><circle cx="50" cy="50" r="34" fill="none" stroke="#B45309" stroke-width="5"/></svg>',
  "peti" => '<svg viewBox="0 0 100 100"><rect x="16" y="30" width="68" height="46" rx="6" fill="#A9713F"/><line x1="16" y1="50" x2="84" y2="50" stroke="#8B5A2B" stroke-width="2"/><line x1="34" y1="30" x2="34" y2="76" stroke="#8B5A2B" stroke-width="2"/><line x1="66" y1="30" x2="66" y2="76" stroke="#8B5A2B" stroke-width="2"/></svg>',
];

function renderIkonBesar($alat, $ICONS) {
    if (!empty($alat["foto"]) && file_exists(__DIR__ . "/uploads/" . $alat["foto"])) {
        return '<img src="uploads/' . htmlspecialchars($alat["foto"]) . '" alt="' . htmlspecialchars($alat["nama"]) . '" style="width:100%;height:100%;object-fit:cover;">';
    }
    if (!empty($alat["icon_key"]) && isset($ICONS[$alat["icon_key"]])) {
        return $ICONS[$alat["icon_key"]];
    }
    $huruf = strtoupper(mb_substr($alat["nama"], 0, 1));
    return '<div style="font-size:64px;font-weight:800;color:#fff;">' . $huruf . '</div>';
}

function tanggalIndonesia() {
    $hari = ["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"];
    $bulan = ["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
    return $hari[date("w")] . ", " . date("j") . " " . $bulan[(int)date("n")] . " " . date("Y");
}

$PALET = [
  ["#16A34A","#E8F8EE"],["#F59E0B","#FEF3C7"],["#0EA5E9","#E0F2FE"],["#EC4899","#FCE7F3"],
  ["#8B5CF6","#EDE9FE"],["#EF4444","#FEE2E2"],["#14B8A6","#CCFBF1"],["#F97316","#FFEDD5"],
  ["#84CC16","#ECFCCB"],["#6366F1","#E0E7FF"],
];
$acc = $PALET[$alat["id"] % count($PALET)];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($alat['nama']); ?> — Kebun Alat</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600;700&family=Nunito+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
  :root{
    --green:#16A34A; --green-dark:#0F5D30; --green-soft:#E8F8EE;
    --ink:#1B3A24; --ink-soft:#5C7A65; --line:#D3EEDC; --red:#DC2626; --red-soft:#FEE2E2;
    --acc:<?php echo $acc[0]; ?>; --acc-bg:<?php echo $acc[1]; ?>;
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
  .icon-big{width:170px; height:170px; border-radius:24px; background:var(--acc-bg); display:flex; align-items:center; justify-content:center; overflow:hidden; flex:0 0 auto;}
  .icon-big svg{width:66%; height:66%;}
  .title-block{flex:1; min-width:220px;}
  .idtag{font-size:12.5px; font-weight:800; color:var(--acc); background:var(--acc-bg); display:inline-block; padding:4px 12px; border-radius:999px;}
  .title-block h2{font-size:28px; margin-top:10px; color:var(--ink);}

  .sec{margin-top:26px; padding-top:22px; border-top:1px solid var(--line);}
  .sec h4{font-size:12px; text-transform:uppercase; letter-spacing:.06em; color:var(--ink-soft); font-weight:800;}
  .sec p{margin:8px 0 0; font-size:15.5px; line-height:1.7; color:var(--ink);}

  .actions{display:flex; gap:12px; margin-top:28px; flex-wrap:wrap;}
  .btn{border:none; border-radius:999px; padding:11px 22px; font-weight:800; font-size:13.5px; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:8px;}
  .btn-primary{background:var(--green); color:#fff; box-shadow:var(--shadow);}
  .btn-danger{background:var(--red-soft); color:var(--red);}
</style>
</head>
<body>
<div class="leaf-pattern"></div>

<div class="topbar">
  <div class="topbar-inner">
    <div class="brand">
      <div class="leaf">🌿</div>
      <div><h1>Kebun Alat</h1><span>HALAMAN DETAIL ALAT</span></div>
    </div>
    <div class="date-chip"><?php echo tanggalIndonesia(); ?></div>
  </div>
</div>

<main>
  <a class="backlink" href="index.php">← Kembali ke Katalog</a>

  <div class="detail-card">
    <div class="top-row">
      <div class="icon-big"><?php echo renderIkonBesar($alat, $ICONS); ?></div>
      <div class="title-block">
        <span class="idtag">ID #<?php echo $alat['id']; ?></span>
        <h2><?php echo htmlspecialchars($alat['nama']); ?></h2>
      </div>
    </div>

    <div class="sec">
      <h4>Fungsi</h4>
      <p><?php echo nl2br(htmlspecialchars($alat['fungsi'])); ?></p>
    </div>

    <div class="sec">
      <h4>Cara Pakai</h4>
      <p><?php echo $alat['cara_pakai'] ? nl2br(htmlspecialchars($alat['cara_pakai'])) : '—'; ?></p>
    </div>

    <div class="actions">
      <a class="btn btn-primary" href="index.php">🌾 Lihat Semua Alat</a>
      <a class="btn btn-danger" href="hapus.php?id=<?php echo $alat['id']; ?>" onclick="return confirm('Hapus &quot;<?php echo htmlspecialchars(addslashes($alat['nama'])); ?>&quot; dari daftar?');">🗑️ Hapus Alat Ini</a>
    </div>
  </div>
</main>
</body>
</html>