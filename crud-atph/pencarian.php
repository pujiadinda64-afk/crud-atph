<?php
// 1. Panggil file koneksi database
include '../koneksi.php';

// 2. Ambil kata kunci dan kategori pencarian dari form
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : 'guru';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pencarian Data Proyek ATPH</title>
    <style>
        /* Styling Utama - Tema Dark Nature / ATPH */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #0b130f; /* Latar belakang hijau sangat gelap ala hutan/pertanian malam */
            color: #e2e8f0;
            margin: 0;
            padding: 40px;
            min-height: 100vh;
        }

        h2, h3 {
            color: #ffffff;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        /* Tombol Kembali */
        .btn-kembali {
            display: inline-flex;
            align-items: center;
            background: #132219;
            color: #4ade80; /* Hijau daun neon lembut */
            padding: 10px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            border: 1px solid #1f3d2b;
            transition: all 0.2s ease;
            margin-bottom: 25px;
        }

        .btn-kembali:hover {
            background: #1f3d2b;
            color: #86efac;
            transform: translateX(-3px);
        }

        /* Form Pencarian */
        form {
            background: #132219;
            padding: 18px 22px;
            border-radius: 12px;
            border: 1px solid #1f3d2b;
            display: inline-flex;
            gap: 12px;
            margin-bottom: 25px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.4);
            align-items: center;
        }

        form input[type="text"], form select {
            background: #0b130f;
            border: 1px solid #2d5a3f;
            color: #f8fafc;
            padding: 10px 14px;
            border-radius: 6px;
            outline: none;
            font-size: 14px;
        }

        form input[type="text"]:focus, form select:focus {
            border-color: #4ade80;
        }

        form button {
            background-color: #16a34a; /* Tombol hijau tanaman */
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        form button:hover {
            background-color: #15803d;
        }

        hr {
            border: 0;
            height: 1px;
            background: #1f3d2b;
            margin: 25px 0;
        }

        /* Container untuk Card ke Samping (Horizontal Scroll) */
        .horizontal-scroll-container {
            display: flex;
            gap: 25px;
            overflow-x: auto; /* Bisa digeser ke samping jika data banyak */
            padding-bottom: 20px;
            padding-top: 10px;
            scroll-behavior: smooth;
        }

        /* Styling Scrollbar agar rapi dan estetis */
        .horizontal-scroll-container::-webkit-scrollbar {
            height: 8px;
        }
        .horizontal-scroll-container::-webkit-scrollbar-track {
            background: #132219;
            border-radius: 4px;
        }
        .horizontal-scroll-container::-webkit-scrollbar-thumb {
            background: #2d5a3f;
            border-radius: 4px;
        }
        .horizontal-scroll-container::-webkit-scrollbar-thumb:hover {
            background: #4ade80;
        }

        /* Desain Kartu (Mirip Referensi Pinterest) */
        .project-card {
            background: #132219;
            border: 1px solid #1f3d2b;
            border-radius: 16px;
            min-width: 300px;
            max-width: 300px;
            flex-shrink: 0;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
        }

        /* Efek Interaktif Saat Kursor Mendekat (Hover) */
        .project-card:hover {
            transform: translateY(-8px);
            border-color: #4ade80;
            box-shadow: 0 15px 30px rgba(74, 222, 128, 0.15);
        }

        /* Pembungkus Gambar di dalam Kartu */
        .card-img-wrapper {
            position: relative;
            width: 100%;
            height: 180px;
            overflow: hidden;
            background-color: #0b130f;
        }

        .card-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .project-card:hover .card-img-wrapper img {
            transform: scale(1.08); /* Efek zoom interaktif */
        }

        /* Label / Badge di atas Foto */
        .card-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            background: rgba(11, 19, 15, 0.75);
            backdrop-filter: blur(4px);
            color: #4ade80;
            padding: 4px 10px;
            font-size: 11px;
            font-weight: 600;
            border-radius: 20px;
            border: 1px solid #1f3d2b;
        }

        /* Bagian Konten Kartu */
        .card-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .card-title {
            font-size: 17px;
            font-weight: 700;
            color: #ffffff;
            margin: 0 0 8px 0;
        }

        .card-desc {
            font-size: 13px;
            color: #94a3b8;
            line-height: 1.5;
            margin: 0 0 15px 0;
            flex-grow: 1;
        }

        .card-footer {
            font-size: 12px;
            color: #4ade80;
            font-weight: 600;
            border-top: 1px solid #1f3d2b;
            padding-top: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Kotak Pesan Kosong / Tidak Ditemukan */
        .empty-state {
            background: #132219;
            border: 1px solid #1f3d2b;
            padding: 30px;
            text-align: center;
            border-radius: 12px;
            color: #94a3b8;
            font-size: 14px;
            width: 100%;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Pencarian Data Sistem ATPH</h2>
        
        <!-- Form Pencarian dengan Filter Kategori -->
        <form action="" method="GET">
            <input type="text" name="keyword" value="<?= htmlspecialchars($keyword); ?>" placeholder="Masukkan kata kunci..." autocomplete="off" required>
            
            <select name="kategori">
                <option value="guru" <?= ($kategori == 'guru') ? 'selected' : ''; ?>>Guru</option>
                <option value="kegiatan" <?= ($kategori == 'kegiatan') ? 'selected' : ''; ?>>Kegiatan</option>
                <option value="alat" <?= ($kategori == 'alat') ? 'selected' : ''; ?>>Alat</option>
            </select>

            <button type="submit">Cari</button>
        </form>

        <br>
        <a href="index.php" class="btn-kembali">&laquo; Kembali ke Beranda</a>
        
        <hr>

        <?php if ($keyword != ''): ?>
            <h3 style="margin-bottom: 20px;">Hasil Pencarian untuk: "<?= htmlspecialchars($keyword); ?>" (Kategori: <?= ucfirst($kategori); ?>)</h3>

            <!-- MENAMPILKAN HASIL PENCARIAN DALAM BENTUK KARTU (HORIZONTAL SCROLL) -->
            <?php if ($kategori == 'guru'): ?>
                <!-- DATA GURU -->
                <div class="horizontal-scroll-container">
                   <?php
                   $query = mysqli_query($koneksi, "SELECT * FROM guru WHERE Nama LIKE '%$keyword%' OR Nip LIKE '%$keyword%'");
                    if (mysqli_num_rows($query) > 0) {
                   $no = 1;
                   while ($data = mysqli_fetch_assoc($query)) {
        // Path fisik untuk dicek oleh file_exists dari posisi pencarian.php (di dalam folder guru/uploads/)
                  $cek_file = "guru/uploads/" . $data['Foto']; 
        
        // Path untuk ditampilkan ke tag img (sama persis dengan index guru asli)
        if (empty($data['Foto']) || !file_exists($cek_file)) {
            $foto_guru = "https://ui-avatars.com/api/?name=" . urlencode($data['Nama']) . "&background=2e7d32&color=fff&size=300";
        } else {
            $foto_guru = "guru/uploads/" . $data['Foto'];
        }
?>
    <div class="project-card">
        <div class="card-img-wrapper">
            <span class="card-badge">No. <?= $no++; ?></span>
            <img src="<?= $foto_guru; ?>" alt="Foto Guru">
        </div>
        <div class="card-body">
            <h3 class="card-title"><?= $data['Nama']; ?></h3>
            <p class="card-desc">
                <strong>NIP:</strong> <?= $data['Nip']; ?><br>
                <strong>Mapel Utama:</strong> <?= $data['Mapel_Utama']; ?>
            </p>
            <div class="card-footer">
                <span>Wali Kelas: <?= $data['Wali_Kelas']; ?></span>
            </div>
        </div>
    </div>
<?php 
    }
} else {
    echo '<div class="empty-state">Data guru tidak ditemukan.</div>';
}
?>
                </div>

            <?php elseif ($kategori == 'alat'): ?>
                <!-- DATA ALAT -->
                <div class="horizontal-scroll-container">
                    <?php
                    $query = mysqli_query($koneksi, "SELECT * FROM alat WHERE nama_alat LIKE '%$keyword%' OR kondisi LIKE '%$keyword%'");
                    if (mysqli_num_rows($query) > 0) {
                        $no = 1;
                        while ($data = mysqli_fetch_assoc($query)) {
                    ?>
                        <div class="project-card">
                            <div class="card-img-wrapper">
                                <span class="card-badge">Stok: <?= $data['jumlah_stok']; ?></span>
                                <img src="alat/uploads/<?= $data['foto']; ?>" alt="Foto Alat" onerror="this.src='https://via.placeholder.com/300x180?text=Alat+ATPH'">
                            </div>
                            <div class="card-body">
                                <h3 class="card-title"><?= $data['nama_alat']; ?></h3>
                                <p class="card-desc">
                                    <strong>Kondisi:</strong> <?= $data['kondisi']; ?>
                                </p>
                                <div class="card-footer">
                                    <span>No. Urut: <?= $no++; ?></span>
                                </div>
                            </div>
                        </div>
                    <?php 
                        }
                    } else {
                        echo '<div class="empty-state">Data alat tidak ditemukan.</div>';
                    }
                    ?>
                </div>
            <?php endif; ?>

        <?php endif; ?>
    </div>

</body>
</html>