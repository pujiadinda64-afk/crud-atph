<?php
include '../koneksi.php';
$result = mysqli_query($koneksi, "SELECT * FROM kegiatan ORDER BY id_kegiatan DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kegiatan Pembelajaran - ATPH</title>
    <link rel="stylesheet" href="../style.css">
  <style>
    * { box-sizing: border-box; }
    
    /* Background Utama Full Layar Tema ATPH (Gradasi Hijau Segar) */
    body { 
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
        background: linear-gradient(135deg, #1b4332 0%, #2e7d32 50%, #a7f3d0 100%);
        background-attachment: fixed;
        color: #334155; 
        margin: 0; 
        padding: 30px 20px; 
        min-height: 100vh;
    }

    /* Container Card Utama Efek Kaca (Glassmorphism Transparan) */
    .container { 
        max-width: 1150px; 
        margin: 0 auto; 
        background: rgba(255, 255, 255, 0.85); 
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        padding: 30px; 
        border-radius: 24px; 
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2); 
        border: 1px solid rgba(255, 255, 255, 0.5);
    }
    
    .header { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        margin-bottom: 25px; 
        padding-bottom: 15px;
        border-bottom: 2px dashed rgba(46, 125, 50, 0.2);
    }

    .btn { 
        padding: 10px 20px; 
        border-radius: 12px; 
        text-decoration: none; 
        font-weight: 700; 
        font-size: 14px; 
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease; 
    }

    .btn-add { 
        background: linear-gradient(135deg, #2e7d32, #1b4332); 
        color: white; 
        box-shadow: 0 4px 12px rgba(27, 67, 50, 0.3);
    }
    .btn-add:hover { 
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(27, 67, 50, 0.4);
        background: linear-gradient(135deg, #388e3c, #2e7d32); 
    }

    .btn-back { 
        background: rgba(255, 255, 255, 0.9); 
        color: #1b4332; 
        margin-bottom: 20px; 
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .btn-back:hover { 
        background: #ffffff; 
        color: #2e7d32;
        transform: translateX(-3px);
    }

    /* Slider Wrapper & Navigasi Panah */
    .slider-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .arrow-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 48px;
        height: 48px;
        background: white;
        color: #1b4332;
        border: 2px solid #a7f3d0;
        border-radius: 50%;
        font-size: 20px;
        cursor: pointer;
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 16px rgba(0,0,0,0.15);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .arrow-btn:hover { 
        background: #2e7d32; 
        color: white; 
        border-color: #2e7d32;
        scale: 1.12; 
    }

    .arrow-btn.left { left: -22px; }
    .arrow-btn.right { right: -22px; }

    /* Card Slider Area */
    .card-slider {
        display: flex;
        gap: 22px;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        padding: 25px 10px;
        scroll-behavior: smooth;
        width: 100%;
    }

    .card-slider::-webkit-scrollbar { display: none; }
    .card-slider { -ms-overflow-style: none; scrollbar-width: none; }

    /* Efek Focus Meredupkan Kartu Lain */
    .card-slider:hover .card {
        opacity: 0.6;
        transform: scale(0.95);
        filter: blur(0.5px);
    }

    /* Kartu Individual */
    .card {
        flex: 0 0 calc((100% - 44px) / 3);
        min-width: 290px;
        scroll-snap-align: center;
        background: #ffffff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        border: 1px solid #e2e8f0;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: flex;
        flex-direction: column;
        position: relative;
        z-index: 1;
        cursor: pointer;
    }

    /* Hover Kartu Utama */
    .card-slider .card:hover {
        opacity: 1 !important;
        transform: translateY(-12px) scale(1.03) !important;
        filter: blur(0) !important;
        border-color: #2e7d32;
        box-shadow: 0 20px 35px rgba(46, 125, 50, 0.25);
        z-index: 5;
    }

    .card-img-wrapper {
        width: 100%;
        height: 200px;
        overflow: hidden;
        position: relative;
        background: #e2e8f0;
    }

    .card-img { 
        width: 100%; 
        height: 100%; 
        object-fit: cover; 
        transition: transform 0.5s ease;
    }

    .card:hover .card-img {
        transform: scale(1.08);
    }

    .card-date {
        position: absolute;
        top: 12px;
        right: 12px;
        background: rgba(27, 67, 50, 0.85);
        backdrop-filter: blur(8px);
        color: #e8f5e9;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }

    .card-body { 
        padding: 20px; 
        flex-grow: 1; 
        display: flex; 
        flex-direction: column; 
    }

    .card-title { 
        margin: 0 0 8px 0; 
        font-size: 18px; 
        color: #1b4332; 
        font-weight: 700; 
        line-height: 1.3;
    }
    
    .card-desc { 
        font-size: 13px; 
        color: #64748b; 
        line-height: 1.6; 
        margin-bottom: 15px; 
        flex-grow: 1;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .card-meta { 
        font-size: 12px; 
        color: #2e7d32; 
        margin-bottom: 15px;
        padding-top: 12px;
        border-top: 1px dashed #e2e8f0;
        font-weight: 600;
    }

    .card-actions { display: flex; gap: 10px; }
    
    .btn-action {
        flex: 1;
        text-align: center;
        padding: 9px;
        border-radius: 10px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
        transition: all 0.2s ease;
    }
    
    .btn-edit { 
        background: #fffbebf5; 
        color: #d97706; 
        border: 1px solid #fef3c7;
    }
    .btn-edit:hover { 
        background: #f59e0b; 
        color: white; 
    }

    .btn-delete { 
        background: #fef2f2; 
        color: #dc2626; 
        border: 1px solid #fee2e2;
    }
    .btn-delete:hover { 
        background: #ef4444; 
        color: white; 
    }

    @media (max-width: 768px) {
        .card { flex: 0 0 88%; }
        .arrow-btn { display: none; }
    }
</style>
</head>
<body>

<div class="container">
    <a href="../index.php" class="btn btn-back">&larr; Kembali ke Dashboard</a>
    
    <div class="header">
        <div>
            <h2 style="margin:0; color:#1b4332; font-size: 26px;">🌿 Data Kegiatan Pembelajaran</h2>
            <p style="margin:6px 0 0 0; color:#64748b; font-size:14px;">Manajemen praktikum dan kegiatan Agribisnis Tanaman (ATPH).</p>
        </div>
        <a href="tambah.php" class="btn btn-add"><span>+</span> Tambah Kegiatan</a>
    </div>

    <div class="slider-wrapper">
        <button class="arrow-btn left" onclick="slideLeft()">&#10094;</button>

        <div class="card-slider" id="cardSlider">
            <?php while($d = mysqli_fetch_array($result)) { 
                $foto_src = (!empty($d['foto']) && file_exists('uploads/' . $d['foto'])) ? 'uploads/' . $d['foto'] : '';
            ?>
                <div class="card" onclick="window.location.href='detail.php?id=<?= $d['id_kegiatan']; ?>'">
                    <div class="card-img-wrapper">
                        <?php if ($foto_src): ?>
                            <img src="<?= $foto_src; ?>" class="card-img" alt="Foto Kegiatan">
                        <?php else: ?>
                            <div style="display:flex; align-items:center; justify-content:center; height:100%; color:#94a3b8; font-size:13px; font-weight:600;">Tanpa Foto</div>
                        <?php endif; ?>
                        <span class="card-date"><?= date('d M Y', strtotime($d['tanggal'])); ?></span>
                    </div>

                    <div class="card-body">
                        <h3 class="card-title"><?= htmlspecialchars($d['nama_kegiatan']); ?></h3>
                        <p class="card-desc"><?= htmlspecialchars($d['deskripsi']); ?></p>
                        <div class="card-meta">Pembimbing: <strong><?= htmlspecialchars($d['pembimbing']); ?></strong></div>
                        
                        <div class="card-actions" onclick="event.stopPropagation();">
                            <a href="edit.php?id=<?= $d['id_kegiatan']; ?>" class="btn-action btn-edit">Edit</a>
                            <a href="hapus.php?id=<?= $d['id_kegiatan']; ?>" class="btn-action btn-delete" onclick="return confirm('Yakin ingin menghapus kegiatan ini?')">Hapus</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <button class="arrow-btn right" onclick="slideRight()">&#10095;</button>
    </div>
</div>

<script>
    const slider = document.getElementById('cardSlider');

    function slideLeft() {
        slider.scrollBy({ left: -330, behavior: 'smooth' });
    }

    function slideRight() {
        slider.scrollBy({ left: 330, behavior: 'smooth' });
    }
</script>

</body>
</html>