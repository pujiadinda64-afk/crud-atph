<?php
session_start();

include '../config/koneksi.php';

/** @var mysqli $koneksi */

// logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: ../index.php");
    exit;
}

$hasil = mysqli_query($koneksi, "SELECT *, id_alat AS id FROM alat ORDER BY id_alat DESC");
$daftarAlat = [];
while ($baris = mysqli_fetch_assoc($hasil)) {
    $daftarAlat[] = $baris;
}

// pesan status dari tambah.php / hapus.php
$pesan = $_GET["pesan"] ?? null;
$teksPesan = [
    "tambah_sukses" => "Alat baru berhasil ditambahkan.",
    "hapus_sukses"  => "Alat berhasil dihapus.",
    "hapus_gagal"   => "Gagal menghapus alat.",
    "edit_sukses"   => "Data alat berhasil diperbarui.",
    "edit_gagal"    => "Data alat gagal diperbarui.",
    "tidak_ditemukan" => "Data alat tidak ditemukan.",
];

// ilustrasi bawaan untuk data awal (dipakai kalau alat tidak punya foto)
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

$PALET = [
  ["#16A34A","#E8F8EE"],["#F59E0B","#FEF3C7"],["#0EA5E9","#E0F2FE"],["#EC4899","#FCE7F3"],
  ["#8B5CF6","#EDE9FE"],["#EF4444","#FEE2E2"],["#14B8A6","#CCFBF1"],["#F97316","#FFEDD5"],
  ["#84CC16","#ECFCCB"],["#6366F1","#E0E7FF"],
];

function renderIkon($alat,$ICONS) {
    if (!empty($alat["foto"]) && file_exists("uploads/" . $alat["foto"])) {
        return '<img src="uploads/' . htmlspecialchars($alat["foto"]) . '" alt="' . htmlspecialchars($alat["nama_alat"]) . '" style="width:100%;height:100%;object-fit:cover;border-radius:16px;">';
    }
    if (!empty($alat["icon_key"]) && isset($ICONS[$alat["icon_key"]])) {
        return $ICONS[$alat["icon_key"]];
    }
    $huruf = strtoupper(mb_substr($alat["nama_alat"], 0, 1));
    return '<div style="font-size:34px;font-weight:800;color:#fff;">' . $huruf . '</div>';
}

function tanggalIndonesia() {
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
<title>Kebun Alat — Daftar Alat ATPH</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600;700&family=Nunito+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
  :root{
    --green:#16A34A; --green-dark:#0F5D30; --green-soft:#E8F8EE; --white:#FFFFFF;
    --ink:#1B3A24; --ink-soft:#5C7A65; --line:#D3EEDC; --red:#DC2626; --red-soft:#FEE2E2;
    --shadow: 0 10px 26px rgba(15,93,48,.12);
  }
  *{box-sizing:border-box;}
  body{
    margin:0; font-family:'Nunito Sans',sans-serif;
    background:
      radial-gradient(1400px 620px at 88% -12%, #BFEDD2 0%, transparent 58%),
      radial-gradient(1100px 560px at -12% 8%, #D8F7E2 0%, transparent 52%),
      radial-gradient(900px 520px at 50% 105%, #CDF2DC 0%, transparent 55%),
      linear-gradient(180deg, #EFFBF3 0%, #FFFFFF 22%, #FFFFFF 78%, #EAF9EF 100%);
    color:var(--ink); min-height:100vh; position:relative;
  }
  .leaf-pattern{
    position:fixed; inset:0; z-index:-1; pointer-events:none; opacity:.5;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120'%3E%3Cg fill='none' stroke='%2316A34A' stroke-width='2' opacity='0.16'%3E%3Cpath d='M20 90 Q20 55 55 40 Q40 70 20 90Z'/%3E%3Cpath d='M20 90 Q35 65 55 40'/%3E%3C/g%3E%3C/svg%3E");
    background-size:120px 120px;
  }
  .topbar{
    background:linear-gradient(120deg, #16A34A 0%, #1E9E5A 45%, #0F5D30 100%);
    position:relative; z-index:1;
  }
  .topbar-inner{display:flex; justify-content:space-between; align-items:center; padding:20px 6vw; max-width:1200px; margin:0 auto; flex-wrap:wrap; gap:12px;}
  h1,h2,h3{font-family:'Fredoka',sans-serif; margin:0;}
  .brand{display:flex; align-items:center; gap:10px;}
  .brand .leaf{width:38px; height:38px; border-radius:12px; background:rgba(255,255,255,.18); display:flex; align-items:center; justify-content:center; color:#fff; font-size:20px; border:1px solid rgba(255,255,255,.35);}
  .brand h1{font-size:22px; color:#fff;}
  .brand span{font-size:12px; color:rgba(255,255,255,.85); font-weight:700;}
  .top-right{display:flex; align-items:center; gap:12px; flex-wrap:wrap;}
  .date-chip{background:rgba(255,255,255,.16); color:#fff; border:1px solid rgba(255,255,255,.35); border-radius:999px; padding:9px 16px; font-size:13px; font-weight:700; display:flex; align-items:center; gap:8px;}
  .date-chip .dot{width:7px; height:7px; border-radius:50%; background:#DFFCE8; animation:pulse 2s infinite;}
  @keyframes pulse{0%,100%{opacity:1} 50%{opacity:.35}}
  .toggle{display:flex; background:rgba(255,255,255,.16); border-radius:999px; padding:4px; gap:2px;}
  .toggle button{border:none; background:none; padding:9px 18px; border-radius:999px; font-weight:800; font-size:13px; color:#fff; cursor:pointer;}
  .toggle button.active{background:#fff; color:var(--green-dark); box-shadow:var(--shadow);}
  .hero{max-width:1200px; margin:0 auto; padding:26px 6vw 0; position:relative; z-index:1;}
  .hero-card{
    background:linear-gradient(120deg, #E6FAEC 0%, #F6FFF9 55%, #FFFFFF 100%);
    border:1px solid var(--line); border-radius:26px; padding:32px 34px;
    box-shadow:var(--shadow); position:relative; overflow:hidden;
  }
  .hero-card::after{
    content:"🌾"; position:absolute; right:22px; bottom:6px; font-size:96px; opacity:.12; line-height:1;
  }
  .hero h2{font-size:clamp(26px,3.6vw,38px); color:var(--green-dark);}
  .hero p{margin-top:10px; color:var(--ink-soft); font-size:15.5px; max-width:62ch;}
  .banner{max-width:1200px; margin:16px auto 0; padding:0 6vw;}
  .banner div{background:var(--green-soft); color:var(--green-dark); border:1px solid var(--line); border-radius:14px; padding:12px 18px; font-size:13.5px; font-weight:700;}
  .controls{max-width:1200px; margin:22px auto 6px; padding:0 6vw; display:flex; gap:12px; flex-wrap:wrap; align-items:center;}
  .search{flex:1 1 260px; position:relative;}
  .search input{width:100%; padding:13px 16px 13px 42px; border-radius:14px; border:2px solid var(--line); font-family:inherit; font-size:14.5px; outline:none;}
  .search::before{content:"🔍"; position:absolute; left:14px; top:50%; transform:translateY(-50%); font-size:14px;}
  .sort-select{padding:12px 16px; border-radius:14px; border:2px solid var(--line); font-family:inherit; font-size:13.5px; background:#fff; cursor:pointer;}
  .fav-filter{border:2px solid var(--line); background:#fff; border-radius:999px; padding:10px 16px; font-size:13px; font-weight:800; color:var(--ink-soft); cursor:pointer;}
  .fav-filter.active{background:#FFFBEB; border-color:#F59E0B; color:#B45309;}
  .stats{max-width:1200px; margin:16px auto 0; padding:0 6vw; display:flex; gap:14px; flex-wrap:wrap;}
  .stat{background:#fff; border:1px solid var(--line); border-radius:16px; padding:12px 18px; box-shadow:var(--shadow); font-size:13px; color:var(--ink-soft); display:flex; gap:8px; align-items:baseline;}
  .stat b{font-family:'Fredoka',sans-serif; font-size:20px; color:var(--green-dark);}
  main{max-width:1200px; margin:0 auto; padding:26px 6vw 70px;}
  .grid{display:grid; grid-template-columns:repeat(auto-fill, minmax(220px,1fr)); gap:18px; margin-top:16px;}
  .card{background:#fff; border-radius:20px; border:1px solid var(--line); padding:18px; cursor:pointer; position:relative; box-shadow:var(--shadow); transition:transform .2s ease, box-shadow .2s ease; border-top:6px solid var(--acc);}
  .card:hover{transform:translateY(-6px) scale(1.03); box-shadow:0 18px 34px rgba(15,93,48,.2);}
  .card .idbadge{position:absolute; top:14px; left:14px; font-size:10.5px; font-weight:800; color:var(--acc); background:var(--acc-bg); padding:2px 8px; border-radius:999px;}
  .card .fav{position:absolute; top:14px; right:14px; font-size:18px; background:none; border:none; cursor:pointer; z-index:2;}
  .card .fav.on{transform:scale(1.3);}
  .card .icon-wrap{height:96px; border-radius:16px; background:var(--acc-bg); display:flex; align-items:center; justify-content:center; margin:20px 0 12px; overflow:hidden;}
  .card .icon-wrap svg{height:66px; width:auto;}
  .card h3{font-size:15.5px;}
  .card p.fungsi{font-size:12.5px; color:var(--ink-soft); margin-top:5px; line-height:1.5; display:-webkit-box; -webkit-line-clamp:2; line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;}
  .card .tap{font-size:11px; color:var(--acc); font-weight:800; margin-top:10px;}
  .empty{text-align:center; padding:60px 20px; color:var(--ink-soft);}
  .overlay{position:fixed; inset:0; background:rgba(15,60,35,.45); backdrop-filter:blur(3px); display:none; align-items:center; justify-content:center; z-index:50; padding:20px;}
  .overlay.show{display:flex;}
  .modal{background:#fff; border-radius:26px; max-width:480px; width:100%; padding:32px; position:relative; transform:scale(.85); opacity:0; transition:transform .28s cubic-bezier(.2,.9,.3,1.3), opacity .2s ease; box-shadow:0 30px 70px rgba(0,0,0,.28); border-top:8px solid var(--acc);}
  .overlay.show .modal{transform:scale(1); opacity:1;}
  .modal .close{position:absolute; top:16px; right:16px; background:var(--green-soft); border:none; border-radius:50%; width:34px; height:34px; font-size:16px; cursor:pointer; color:var(--green-dark);}
  .modal .icon-big{height:170px; display:flex; align-items:center; justify-content:center; background:var(--acc-bg); border-radius:20px; margin-bottom:16px; overflow:hidden;}
  .modal .icon-big svg{height:120px; width:auto;}
  .modal .icon-big img{width:100%; height:100%; object-fit:cover;}
  .modal h2{font-size:23px;}
  .modal .idtag{font-size:12px; color:var(--ink-soft); font-weight:700;}
  .modal .sec{margin-top:16px;}
  .modal .sec h4{font-size:11.5px; text-transform:uppercase; letter-spacing:.06em; color:var(--ink-soft); font-weight:800;}
  .modal .sec p{margin:6px 0 0; font-size:14.5px; line-height:1.6;}
  #view-kelola{display:none;}
  .panel{background:#fff; border:1px solid var(--line); border-radius:22px; padding:26px; box-shadow:var(--shadow); margin-top:22px;}
  .panel h2{font-size:18px; color:var(--green-dark); margin-bottom:4px;}
  .panel .sub{color:var(--ink-soft); font-size:13px; margin-bottom:18px;}
  form.tool-form{display:grid; grid-template-columns:1fr 1fr; gap:14px;}
  form.tool-form .full{grid-column:1/-1;}
  @media (max-width:640px){ form.tool-form{grid-template-columns:1fr;} }
  label{font-size:12.5px; font-weight:800; color:var(--ink-soft); display:block; margin-bottom:6px;}
  input[type=text], textarea, input[type=file]{width:100%; padding:11px 14px; border-radius:12px; border:2px solid var(--line); font-family:inherit; font-size:14px; outline:none; background:#fbfdfb;}
  textarea{resize:vertical; min-height:64px;}
  .filepick{border:2px dashed var(--line); border-radius:14px; padding:16px; text-align:center; color:var(--ink-soft); font-size:13px; background:#fbfdfb;}
  .save-btn{grid-column:1/-1; justify-self:start; background:var(--green); color:#fff; border:none; border-radius:999px; padding:12px 26px; font-weight:800; font-size:14px; cursor:pointer; box-shadow:var(--shadow);}
  .list{display:flex; flex-direction:column; gap:10px; margin-top:8px;}
  .row{display:flex; align-items:center; gap:14px; padding:12px 16px; border:1px solid var(--line); border-radius:16px; border-left:6px solid var(--acc);}
  .row .ic{width:40px; height:40px; flex:0 0 auto; border-radius:10px; overflow:hidden; display:flex; align-items:center; justify-content:center; background:var(--acc-bg);}
  .row .ic svg{width:70%; height:70%;}
  .row .ic img{width:100%; height:100%; object-fit:cover;}
  .row .info{flex:1; min-width:0;}
  .row .info .nm{font-weight:800; font-size:14px;}
  .row .info .fg{font-size:12px; color:var(--ink-soft); overflow:hidden; text-overflow:ellipsis; white-space:nowrap;}
  .row .idnum{font-size:11px; color:var(--ink-soft); font-weight:700;}
  .del-link{background:var(--red-soft); color:var(--red); border:none; border-radius:10px; padding:8px 12px; font-size:12.5px; font-weight:800; cursor:pointer; text-decoration:none;}
  .det-link{background:var(--green-soft); color:var(--green-dark); border:none; border-radius:10px; padding:8px 12px; font-size:12.5px; font-weight:800; cursor:pointer; text-decoration:none;}
  footer{text-align:center; padding:30px; color:var(--ink-soft); font-size:12.5px; border-top:1px solid var(--line);}
</style>
</head>
<body>
<a href="?action=logout" style="position:fixed; bottom:20px; right:20px; background:#FEE2E2; color:#DC2626; border-radius:999px; padding:10px 16px; font-size:13px; font-weight:800; text-decoration:none; box-shadow:var(--shadow); z-index:100;">🚪 Logout</a>

<div class="leaf-pattern"></div>

<div class="topbar">
  <div class="topbar-inner">
    <div class="brand">
      <div class="leaf">🌿</div>
      <div><h1>Kebun Alat</h1><span>DAFTAR ALAT ATPH</span></div>
    </div>
    <div class="top-right">
      <div class="date-chip"><span class="dot"></span><span><?php echo tanggalIndonesia(); ?></span></div>
      <div class="toggle">
        <button id="btnKatalog" class="active">🌾 Katalog</button>
        <button id="btnKelola">🛠️ Kelola Data</button>
      </div>
    </div>
  </div>
</div>

<?php if ($pesan && isset($teksPesan[$pesan])): ?>
<div class="banner"><div>✅ <?php echo $teksPesan[$pesan]; ?></div></div>
<?php endif; ?>

<div id="view-katalog">
  <div class="hero">
    <div class="hero-card">
      <h2>Daftar Alat ATPH</h2>
      <p>Kumpulan alat pertanian tanaman pangan &amp; hortikultura, tersimpan langsung dari database. Ketuk kartu untuk detail, unggah foto asli lewat tab Kelola Data.</p>
    </div>
  </div>

  <div class="controls">
    <div class="search"><input id="searchInput" type="text" placeholder="Cari nama alat…"></div>
    <select class="sort-select" id="sortSelect">
      <option value="az">Urut A–Z</option>
      <option value="za">Urut Z–A</option>
    </select>
    <button class="fav-filter" id="favFilter">⭐ Hanya Favorit</button>
  </div>

  <div class="stats">
    <div class="stat"><b><?php echo count($daftarAlat); ?></b>&nbsp;alat terdaftar</div>
    <div class="stat"><b id="favCount">0</b>&nbsp;alat favorit</div>
  </div>

  <main>
    <div class="grid" id="grid">
      <?php foreach ($daftarAlat as $i => $alat):
          $acc = $PALET[$i % count($PALET)]; ?>
      <div class="card" data-nama="<?php echo htmlspecialchars(strtolower($alat['nama_alat'])) ;?>" data-id="<?php echo $alat['id']; ?>" style="--acc:<?php echo $acc[0]; ?>;--acc-bg:<?php echo $acc[1]; ?>;">
        <span class="idbadge">#<?php echo $alat['id_alat']; ?></span>
        <button class="fav" title="Tandai favorit">☆</button>
        <div class="icon-wrap"><?php echo renderIkon($alat, $ICONS); ?></div>
        <h3><?php echo htmlspecialchars($alat['nama_alat']); ?></h3>
        <p class="fungsi"><?php echo htmlspecialchars($alat['cara_pakai'] ?? ''); ?></p>
        <div class="tap">Ketuk untuk detail →</div>
        <script type="application/json" class="detail-data"><?php echo json_encode(['cara_pakai' => $alat['cara_pakai'] ?? '']); ?></script>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="empty" id="emptyState" style="display:none;">Tidak ada alat yang cocok. Coba kata kunci lain 🌱</div>
  </main>
</div>

<div id="view-kelola">
  <main style="padding-top:26px;">
    <div class="panel">
      <h2>Tambah Alat Baru</h2>
      <p class="sub">Data dikirim ke <code>tambah.php</code> dan langsung tersimpan ke database beserta id baru (primary key otomatis).</p>
      <form class="tool-form" action="tambah.php" method="POST" enctype="multipart/form-data">
        <div class="full">
          <label>Nama Alat</label>
          <input type="text" name="nama_alat" placeholder="mis. Cangkul" required>
        </div>
        <div class="full">
          <label>Jumlah Stok</label>
          <input type="number" name="jumlah_stok" placeholder="mis. 10" required>
        </div>
        <div class="full">
          <label>Kondisi</label>
          <input type="text" name="kondisi" placeholder="mis. Baik" required>
        </div>
        <div class="full">
          <label>Foto Alat (jpg/png/webp, maks 2MB)</label>
          <div class="filepick"><input type="file" name="foto" accept=".jpg,.jpeg,.png,.webp"></div>
        </div>
        <button type="submit" class="save-btn">💾 Simpan Alat</button>
      </form>
    </div>

    <div class="panel">
      <h2>Daftar Alat</h2>
      <p class="sub">Total <b><?php echo count($daftarAlat); ?></b> alat tersimpan. Hapus memanggil <code>hapus.php?id=...</code>.</p>
      <div class="list">
        <?php foreach ($daftarAlat as $i => $alat):
            $acc = $PALET[$i % count($PALET)]; ?>
        <div class="row" style="--acc:<?php echo $acc[0]; ?>;--acc-bg:<?php echo $acc[1]; ?>;">
          <div class="ic"><?php echo renderIkon($alat, $ICONS); ?></div>
          <div class="info">
            <div class="nm"><?php echo htmlspecialchars($alat['nama_alat']); ?> <span class="idnum">#<?php echo $alat['id']; ?></span></div>
            <div class="fg"><?php echo htmlspecialchars($alat['cara_pakai']); ?></div>
          </div>
          <a class="det-link" href="detail.php?id=<?php echo $alat['id_alat']; ?>">📄 Detail</a>
          <a class="del-link" 
           href="hapus.php?id=<?php echo $alat['id']; ?>" 
            onclick="return confirm('Hapus &quot;<?php echo htmlspecialchars(addslashes($alat['nama_alat'] ?? '')); ?>&quot; dari daftar?');">
            🗑️ Hapus
          </a>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </main>
</div>

<footer>Kebun Alat · Daftar Alat ATPH — data tersimpan di database (tabel <code>alat</code>, id sebagai primary key)</footer>

<div class="overlay" id="overlay"><div class="modal" id="modalBox"></div></div>

<script>
const btnKatalog = document.getElementById('btnKatalog');
const btnKelola = document.getElementById('btnKelola');
const viewKatalog = document.getElementById('view-katalog');
const viewKelola = document.getElementById('view-kelola');
btnKatalog.onclick = () => { btnKatalog.classList.add('active'); btnKelola.classList.remove('active'); viewKatalog.style.display='block'; viewKelola.style.display='none'; };
btnKelola.onclick = () => { btnKelola.classList.add('active'); btnKatalog.classList.remove('active'); viewKelola.style.display='block'; viewKatalog.style.display='none'; };

/* favorit disimpan di localStorage browser (murni tampilan) */
const FAV_KEY = 'atph_favorit';
function getFav(){ try{ return JSON.parse(localStorage.getItem(FAV_KEY) || '[]'); }catch(e){ return []; } }
function setFav(arr){ localStorage.setItem(FAV_KEY, JSON.stringify(arr)); }
let favList = getFav();
let showFavOnly = false;

function updateFavUI(){
  document.querySelectorAll('.card').forEach(card=>{
    const id = card.dataset.id;
    const on = favList.includes(id);
    const btn = card.querySelector('.fav');
    btn.textContent = on ? '⭐' : '☆';
    btn.classList.toggle('on', on);
  });
  document.getElementById('favCount').textContent = favList.length;
}
document.querySelectorAll('.card .fav').forEach(btn=>{
  btn.addEventListener('click', (e)=>{
    e.stopPropagation();
    const card = btn.closest('.card');
    const id = card.dataset.id;
    favList = favList.includes(id) ? favList.filter(x=>x!==id) : [...favList, id];
    setFav(favList);
    updateFavUI();
    applyFilters();
  });
});

function applyFilters(){
  const q = document.getElementById('searchInput').value.trim().toLowerCase();
  const sort = document.getElementById('sortSelect').value;
  const cards = [...document.querySelectorAll('.card')];
  const grid = document.getElementById('grid');
  let visible = 0;
  cards.forEach(c=>{
    const match = (!q || c.dataset.nama.includes(q)) && (!showFavOnly || favList.includes(c.dataset.id));
    c.style.display = match ? '' : 'none';
    if(match) visible++;
  });
  document.getElementById('emptyState').style.display = visible ? 'none' : 'block';
  cards.sort((a,b)=>{
    const an = a.querySelector('h3').textContent, bn = b.querySelector('h3').textContent;
    return sort === 'az' ? an.localeCompare(bn) : bn.localeCompare(an);
  }).forEach(c=>grid.appendChild(c));
}
document.getElementById('searchInput').addEventListener('input', applyFilters);
document.getElementById('sortSelect').addEventListener('change', applyFilters);
document.getElementById('favFilter').addEventListener('click', function(){
  showFavOnly = !showFavOnly;
  this.classList.toggle('active', showFavOnly);
  applyFilters();
});

document.querySelectorAll('.card').forEach(card=>{
  card.addEventListener('click', ()=>{
    const acc = getComputedStyle(card).getPropertyValue('--acc').trim();
    const accBg = getComputedStyle(card).getPropertyValue('--acc-bg').trim();
    const nama = card.querySelector('h3').textContent;
    const fungsi = card.querySelector('.fungsi').textContent;
    const id = card.dataset.id;
    const iconHtml = card.querySelector('.icon-wrap').innerHTML;
    const detail = JSON.parse(card.querySelector('.detail-data').textContent);
    const box = document.getElementById('modalBox');
    box.style.setProperty('--acc', acc);
    box.style.setProperty('--acc-bg', accBg);
    box.innerHTML = `
      <button class="close" onclick="document.getElementById('overlay').classList.remove('show')">✕</button>
      <div class="icon-big">${iconHtml}</div>
      <div class="idtag">ID #${id}</div>
      <h2>${nama}</h2>
      <div class="sec"><h4>Fungsi</h4><p>${fungsi}</p></div>
      <div class="sec"><h4>Cara Pakai</h4><p>${detail.cara_pakai || '—'}</p></div>
      <div class="modal-actions" style="margin-top:20px;">
        <a href="detail.php?id=${id}" style="display:inline-flex;align-items:center;gap:6px;background:var(--acc);color:#fff;border:none;border-radius:999px;padding:10px 20px;font-weight:800;font-size:13px;text-decoration:none;">📄 Buka Halaman Detail Lengkap</a>
      </div>
    `;
    document.getElementById('overlay').classList.add('show');
  });
});
document.getElementById('overlay').addEventListener('click', (e)=>{ if(e.target.id==='overlay') e.currentTarget.classList.remove('show'); });
document.addEventListener('keydown', (e)=>{ if(e.key==='Escape') document.getElementById('overlay').classList.remove('show'); });

updateFavUI();
</script>
</body>
</html>