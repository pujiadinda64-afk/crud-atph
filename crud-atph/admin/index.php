<?php
session_start();

// Proteksi Halaman: Jika belum login atau bukan admin, tendang balik ke login.php
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

include '../config/koneksi.php';

// Mengambil jumlah total data dari database untuk statistik di dashboard
$total_kegiatan = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM kegiatan"));
$total_alat = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM alat"));
$total_guru = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM guru"));
$total_peminjaman = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM peminjaman"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - ATPH</title>
    <!-- Menggunakan font modern Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background-color: #fcf8f2; color: #2d3748; display: flex; min-height: 100vh; }

        /* Sidebar Kiri */
        .sidebar {
            width: 260px;
            background: #ffffff;
            border-right: 1px solid rgba(0,0,0,0.06);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
        }
        .sidebar-brand {
            padding: 24px;
            font-size: 18px;
            font-weight: 800;
            color: #1b4332;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid rgba(0,0,0,0.04);
        }
        .sidebar-menu {
            list-style: none;
            padding: 20px 15px;
            flex-grow: 1;
        }
        .sidebar-menu li {
            margin-bottom: 8px;
        }
        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: #4a5568;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s;
        }
        .sidebar-menu a:hover, .sidebar-menu a.active {
            background-color: #f0fdf4;
            color: #276749;
        }
        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid rgba(0,0,0,0.04);
        }
        .btn-logout {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 12px;
            background-color: #fff5f5;
            color: #e53e3e;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 14px;
            transition: all 0.2s;
        }
        .btn-logout:hover {
            background-color: #e53e3e;
            color: white;
        }

        /* Area Utama Kanan */
        .main-content {
            margin-left: 260px;
            flex-grow: 1;
            padding: 40px;
        }

        /* Banner Sambutan */
        .welcome-banner {
            background: linear-gradient(135deg, #1b4332 0%, #2d6a4f 100%);
            color: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(27, 67, 50, 0.15);
            margin-bottom: 30px;
        }
        .welcome-banner h1 { font-size: 24px; font-weight: 800; margin-bottom: 8px; }
        .welcome-banner p { font-size: 14px; opacity: 0.9; }

        /* Grid Statistik */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
            border: 1px solid rgba(0,0,0,0.04);
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .stat-icon {
            width: 50px;
            height: 50px;
            background: #f0fdf4;
            color: #276749;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        .stat-info h3 { font-size: 22px; font-weight: 800; color: #1a202c; }
        .stat-info p { font-size: 13px; color: #718096; font-weight: 600; }

        /* Menu Pintasan Cepat */
        .section-title { font-size: 18px; font-weight: 800; color: #1a202c; margin-bottom: 16px; }
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
        }
        .menu-card {
            background: white;
            padding: 24px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
            border: 1px solid rgba(0,0,0,0.04);
            text-decoration: none;
            color: inherit;
            transition: all 0.2s;
            display: block;
        }
        .menu-card:hover {
            transform: translateY(-4px);
            border-color: #48bb78;
            box-shadow: 0 10px 25px rgba(72,187,120,0.1);
        }
        .menu-card h4 { font-size: 16px; font-weight: 700; color: #2d3748; margin-bottom: 6px; }
        .menu-card p { font-size: 13px; color: #718096; line-height: 1.4; }
    </style>
</head>
<body>

    <!-- Sidebar Menu Samping -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <i class="fa-solid fa-seedling" style="color: #48bb78;"></i> Admin ATPH
        </div>
        <ul class="sidebar-menu">
            <li><a href="index.php" class="active"><i class="fa-solid fa-house"></i> Dashboard</a></li>
            <li><a href="../kegiatan/index.php"><i class="fa-solid fa-clipboard-list"></i> Data Kegiatan</a></li>
            <li><a href="../guru/index.php"><i class="fa-solid fa-chalkboard-user"></i> Data Guru</a></li>
            <li><a href="../alat/index.php"><i class="fa-solid fa-toolbox"></i> Data Alat</a></li>
            <li><a href="../peminjaman/index.php"><i class="fa-solid fa-handshake-angle"></i> Peminjaman</a></li>
            <li><a href="../jadwal/index.php"><i class="fa-solid fa-calendar-days"></i> Jadwal Lahan</a></li>
        </ul>
        <div class="sidebar-footer">
            <a href="../logout.php" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </div>
    </div>

    <!-- Area Konten Utama -->
    <div class="main-content">
        <!-- Banner Sambutan -->
        <div class="welcome-banner">
            <h1>Selamat Datang, <?= htmlspecialchars($_SESSION['username']); ?>! 👋</h1>
            <p>Panel Kontrol Administrator Sistem Informasi Agribisnis Tanaman Pangan dan Hortikultura.</p>
        </div>

        <!-- Kartu Statistik Ringkasan -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="fa-solid fa-clipboard-list"></i></div>
                <div class="stat-info">
                    <h3><?= $total_kegiatan; ?></h3>
                    <p>Total Kegiatan</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fa-solid fa-toolbox"></i></div>
                <div class="stat-info">
                    <h3><?= $total_alat; ?></h3>
                    <p>Total Alat</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fa-solid fa-handshake-angle"></i></div>
                <div class="stat-info">
                    <h3><?= $total_peminjaman; ?></h3>
                    <p>Peminjaman</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fa-solid fa-chalkboard-user"></i></div>
                <div class="stat-info">
                    <h3><?= $total_guru; ?></h3>
                    <p>Data Guru</p>
                </div>
            </div>
        </div>

        <!-- Menu Pintasan Cepat -->
        <h2 class="section-title">Akses Cepat Pengelolaan</h2>
        <div class="menu-grid">
            <a href="../kegiatan/index.php" class="menu-card">
                <h4>Kelola Kegiatan</h4>
                <p>Tambah, edit, dan hapus dokumentasi foto kegiatan pembelajaran.</p>
            </a>
            <a href="../peminjaman/index.php" class="menu-card">
                <h4>Konfirmasi Peminjaman</h4>
                <p>Setujui atau tolak permintaan peminjaman alat oleh siswa.</p>
            </a>
            <a href="../alat/index.php" class="menu-card">
                <h4>Inventaris Alat</h4>
                <p>Kelola data ketersediaan alat-alat praktik pertanian.</p>
            </a>
        </div>
    </div>

</body>
</html>