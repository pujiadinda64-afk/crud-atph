<?php
include '../koneksi.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$query = mysqli_query($koneksi, "SELECT * FROM kegiatan WHERE id_kegiatan = '$id'");
$data = mysqli_fetch_array($query);

if (!$data) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($data['nama_kegiatan']); ?> - Detail Kegiatan ATPH</title>
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

        /* Container Card Utama Efek Kaca Transparan */
        .container { 
            max-width: 1100px; 
            margin: 0 auto; 
            background: rgba(255, 255, 255, 0.88); 
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            padding: 30px; 
            border-radius: 24px; 
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2); 
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .btn-back { 
            background: rgba(255, 255, 255, 0.9); 
            color: #1b4332; 
            padding: 10px 18px; 
            border-radius: 12px; 
            text-decoration: none; 
            font-weight: 700; 
            font-size: 14px; 
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 25px; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            transition: all 0.3s ease;
        }
        .btn-back:hover { 
            background: #ffffff; 
            color: #2e7d32;
            transform: translateX(-4px);
        }

        /* Banner Header Kegiatan */
        .detail-header { 
            background: linear-gradient(135deg, #1b4332, #2e7d32); 
            color: white; 
            padding: 30px; 
            border-radius: 20px; 
            margin-bottom: 30px; 
            box-shadow: 0 8px 20px rgba(27, 67, 50, 0.25);
        }
        .detail-header h1 { margin: 0 0 12px 0; font-size: 28px; font-weight: 800; }
        .detail-meta { display: flex; gap: 20px; font-size: 14px; color: #a7f3d0; font-weight: 600; flex-wrap: wrap; }

        /* Bento Grid Tata Letak Informasi Utama */
        .bento-grid { display: grid; grid-template-columns: 1.2fr 1fr; gap: 22px; margin-bottom: 35px; }
        
        .bento-card { 
            background: #ffffff; 
            border: 1px solid #e2e8f0; 
            border-radius: 20px; 
            padding: 22px; 
            box-shadow: 0 8px 20px rgba(0,0,0,0.04);
        }
        
        .bento-card.main-img-card { 
            padding: 0; 
            min-height: 320px; 
            overflow: hidden; 
            position: relative;
            border-color: #a7f3d0;
        }
        .bento-card.main-img-card img { width: 100%; height: 100%; object-fit: cover; }
        .bento-title { font-size: 18px; color: #1b4332; font-weight: 700; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }

        /* Section Galeri Foto Tambahan */
        .gallery-section {
            border-top: 2px dashed rgba(46, 125, 50, 0.2);
            padding-top: 25px;
        }

        .gallery-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); 
            gap: 20px; 
            margin-top: 18px; 
        }

        .gallery-card { 
            background: #ffffff; 
            border-radius: 18px; 
            overflow: hidden; 
            border: 1px solid #e2e8f0; 
            box-shadow: 0 6px 16px rgba(0,0,0,0.05); 
            transition: all 0.3s ease;
        }

        .gallery-card:hover {
            transform: translateY(-6px);
            border-color: #2e7d32;
            box-shadow: 0 12px 25px rgba(46, 125, 50, 0.18);
        }

        .gallery-img { width: 100%; height: 190px; object-fit: cover; display: block; }
        .gallery-caption { padding: 14px; font-size: 13px; color: #475569; }

        @media (max-width: 768px) {
            .bento-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div class="container">
    <a href="index.php" class="btn-back">&larr; Kembali ke Daftar Kegiatan</a>

    <div class="detail-header">
        <h1>🌱 <?= htmlspecialchars($data['nama_kegiatan']); ?></h1>
        <div class="detail-meta">
            <span>📅 Tanggal: <?= date('d F Y', strtotime($data['tanggal'])); ?></span>
            <span>👤 Pembimbing: <?= htmlspecialchars($data['pembimbing']); ?></span>
        </div>
    </div>

    <!-- Layout Bento Info Utama -->
    <div class="bento-grid">
        <div class="bento-card main-img-card">
            <?php if (!empty($data['foto']) && file_exists('uploads/' . $data['foto'])): ?>
                <img src="uploads/<?= htmlspecialchars($data['foto']); ?>" alt="Foto Utama Kegiatan">
            <?php else: ?>
                <div style="display:flex; align-items:center; justify-content:center; height:100%; color:#94a3b8; font-weight:600;">Tanpa Foto Utama</div>
            <?php endif; ?>
        </div>

        <div class="bento-card">
            <div class="bento-title">📋 Deskripsi Kegiatan</div>
            <div style="font-size: 14px; color: #475569; line-height: 1.7;"><?= nl2br(htmlspecialchars($data['deskripsi'])); ?></div>
        </div>
    </div>

    <!-- Galeri Foto Dokumentasi Tambahan -->
    <div class="gallery-section">
        <h3 style="color: #1b4332; margin: 0; font-size: 20px;">📷 Galeri Dokumentasi Tambahan</h3>
        <div class="gallery-grid">
            <?php
            $query_foto = mysqli_query($koneksi, "SELECT * FROM foto_kegiatan WHERE id_kegiatan = '$id'");
            if (mysqli_num_rows($query_foto) > 0) {
                while ($f = mysqli_fetch_array($query_foto)) {
            ?>
                <div class="gallery-card">
                    <img src="uploads/<?= htmlspecialchars($f['nama_foto']); ?>" class="gallery-img" alt="Dokumentasi">
                    <div class="gallery-caption">
                        <p style="margin: 0; color: #64748b;"><?= !empty($f['keterangan']) ? htmlspecialchars($f['keterangan']) : 'Dokumentasi Kegiatan'; ?></p>
                    </div>
                </div>
            <?php 
                }
            } else {
                echo "<p style='color:#64748b; grid-column: 1/-1; margin-top: 10px;'>Belum ada foto dokumentasi tambahan untuk kegiatan ini.</p>";
            }
            ?>
        </div>
    </div>
</div>

</body>
</html>