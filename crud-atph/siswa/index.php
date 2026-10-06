<?php
session_start();
include '../config/koneksi.php';

// Proteksi Halaman: Hanya Siswa yang bisa akses
// Sesuaikan jalur ke login.php jika file login.php ada di luar folder siswa (root)
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'siswa') {
    header("Location: ../login.php");
    exit();
}
?>

<?php
// Pastikan session sudah dimulai jika menggunakan login session
// session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Siswa - ATPH</title>
    <!-- FontAwesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #10b981; /* Hijau Pertanian / ATPH */
            --primary-dark: #059669;
            --primary-light: #ecfdf5;
            --accent: #f59e0b; /* Kuning / Oranye aksen hangat */
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --sidebar-width: 260px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
        }

        /* --- SIDEBAR KIRI --- */
        aside {
            width: var(--sidebar-width);
            background: var(--card-bg);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 10;
        }

        .sidebar-brand {
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid var(--border-color);
        }

        .sidebar-brand .logo-icon {
            width: 40px;
            height: 40px;
            background: var(--primary-light);
            color: var(--primary);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .sidebar-brand h1 {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-main);
        }

        .sidebar-brand p {
            font-size: 12px;
            color: var(--text-muted);
        }

        .sidebar-menu {
            padding: 20px 16px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex-grow: 1;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .nav-item:hover, .nav-item.active {
            background: var(--primary-light);
            color: var(--primary-dark);
        }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid var(--border-color);
        }

        .user-profile-mini {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-info img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
        }

        .user-info .name {
            font-size: 13px;
            font-weight: 600;
        }

        .user-info .role {
            font-size: 11px;
            color: var(--text-muted);
        }

        /* --- KONTEN UTAMA --- */
        main {
            margin-left: var(--sidebar-width);
            flex-grow: 1;
            padding: 30px;
            max-width: 1200px;
        }

        /* Top Header Bar */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            background: var(--card-bg);
            padding: 12px 24px;
            border-radius: 14px;
            border: 1px solid var(--border-color);
        }

        .search-box {
            display: flex;
            align-items: center;
            background: var(--bg-color);
            border: 1px solid var(--border-color);
            padding: 8px 16px;
            border-radius: 10px;
            width: 300px;
            gap: 10px;
        }

        .search-box input {
            border: none;
            background: transparent;
            outline: none;
            font-size: 14px;
            width: 100%;
        }

        .top-stats {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .badge-stat {
            background: var(--primary-light);
            color: var(--primary-dark);
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .badge-stat.warning {
            background: #fef3c7;
            color: #d97706;
        }

        /* Banner Utama Bertema Pertanian */
        .hero-banner {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            border-radius: 20px;
            padding: 35px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.2);
            position: relative;
            overflow: hidden;
        }

        .hero-banner h2 {
            font-size: 26px;
            margin-bottom: 10px;
        }

        .hero-banner p {
            font-size: 14px;
            opacity: 0.9;
            max-width: 500px;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .btn-banner {
            background: white;
            color: var(--primary-dark);
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: transform 0.2s;
        }

        .btn-banner:hover {
            transform: translateY(-2px);
        }

        .weather-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 14px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .weather-card i {
            font-size: 32px;
            color: var(--accent);
            margin-bottom: 5px;
        }

        /* Layout Grid Bagian Bawah */
        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 25px;
        }

        .menu-section h3.section-title {
            font-size: 18px;
            margin-bottom: 15px;
            color: var(--text-main);
        }

        /* Card Menu Akses Praktikum (Sesuai Data Asli Kamu) */
        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 18px;
        }

        .menu-card {
            background: var(--card-bg);
            padding: 20px;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .menu-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            border-color: var(--primary);
        }

        .menu-card .card-icon {
            width: 42px;
            height: 42px;
            background: var(--primary-light);
            color: var(--primary);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-bottom: 12px;
        }

        .menu-card h4 {
            color: var(--text-main);
            margin-bottom: 6px;
            font-size: 16px;
        }

        .menu-card p {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 15px;
            line-height: 1.4;
        }

        .menu-card a {
            color: var(--primary);
            font-weight: 600;
            font-size: 13px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .menu-card a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        /* Leaderboard / Info Tambahan Samping */
        .side-panel {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 20px;
        }

        .side-panel h4 {
            font-size: 16px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .leaderboard-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .leaderboard-item:last-child {
            border-bottom: none;
        }

        .leaderboard-user {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .leaderboard-user img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
        }

        .leaderboard-user .name {
            font-size: 13px;
            font-weight: 600;
        }

        .leaderboard-user .class {
            font-size: 11px;
            color: var(--text-muted);
        }

        .score-badge {
            background: var(--primary-light);
            color: var(--primary-dark);
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        /* Tombol Keluar Kustom */
        .btn-keluar {
            background: #fee2e2;
            color: #dc2626;
            padding: 6px 12px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.2s;
        }
        .btn-keluar:hover {
            background: #fecaca;
        }

        /* Responsif untuk layar kecil */
        @media(max-width: 900px) {
            aside { display: none; }
            main { margin-left: 0; padding: 15px; }
            .content-grid { grid-template-columns: 1fr; }
            .hero-banner { flex-direction: column; text-align: center; gap: 20px; }
            .hero-banner p { max-width: 100%; }
        }
    </style>
</head>
<body>

    <!-- SIDEBAR KIRI -->
    <aside>
        <div class="sidebar-brand">
            <div class="logo-icon"><i class="fa-solid fa-seedling"></i></div>
            <div>
                <h1>Portal ATPH</h1>
                <p>SMK Pertanian Unggul</p>
            </div>
        </div>
        <div class="sidebar-menu">
            <a href="index.php" class="nav-item active"><i class="fa-solid fa-house"></i> Beranda</a>
            <a href="../peminjaman/tambah.php" class="nav-item"><i class="fa-solid fa-pen-to-square"></i> Pinjam Alat</a>
            <a href="../jadwal/index.php" class="nav-item"><i class="fa-solid fa-calendar-days"></i> Jadwal Lapang</a>
            <a href="../alat/index.php" class="nav-item"><i class="fa-solid fa-tractor"></i> Cek Stok Alat</a>
            <a href="upload-laporan.php" class="nav-item"><i class="fa-solid fa-book"></i> Jurnal Praktikum</a>
            <a href="riwayat.php" class="nav-item"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Pinjam</a>
        </div>
        <div class="sidebar-footer">
            <div class="user-profile-mini">
                <div class="user-info">
                    <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100" alt="User">
                    <div>
                        <div class="name"><?= htmlspecialchars($_SESSION['username'] ?? 'Siswa ATPH'); ?></div>
                        <div class="role">Kelas XI - ATPH 1</div>
                    </div>
                </div>
                <a href="../logout.php" title="Keluar" style="color: #dc2626;"><i class="fa-solid fa-right-from-bracket"></i></a>
            </div>
        </div>
    </aside>

    <!-- KONTEN UTAMA -->
    <main>
        <!-- Top Bar Header -->
        <div class="top-bar">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass" style="color: var(--text-muted);"></i>
                <input type="text" placeholder="Cari alat, jadwal, atau modul...">
            </div>
            <div class="top-stats">
                <div class="badge-stat">
                    <i class="fa-solid fa-seedling"></i> Praktik Lapang: 12/15 Jam
                </div>
                <div class="badge-stat warning">
                    <i class="fa-solid fa-star"></i> 750 Poin Kinerja
                </div>
                <a href="../logout.php" class="btn-keluar">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar
                </a>
            </div>
        </div>

        <!-- Banner Sapaan Bertema Pertanian -->
        <div class="hero-banner">
            <div>
                <span style="background: rgba(255,255,255,0.2); padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                    <i class="fa-solid fa-sun"></i> Sesi Pembelajaran Aktif • Musim Tanam Hortikultura
                </span>
                <h2 style="margin-top: 10px;">Selamat pagi, <?= htmlspecialchars($_SESSION['username'] ?? 'Siswa'); ?>! 🌱</h2>
                <p>Satu langkah lebih dekat menuju petani milenial profesional. Jangan lupa isi jurnal harian dan cek jadwal pengolahan lahan hari ini.</p>
                <a href="../peminjaman/tambah.php" class="btn-banner">
                    Mulai Aktivitas <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
            <div class="weather-card">
                <i class="fa-solid fa-cloud-sun"></i>
                <div style="font-size: 22px; font-weight: 700; margin-top: 5px;">28°C</div>
                <div style="font-size: 12px; opacity: 0.9;">Kondisi Green House Optimal</div>
            </div>
        </div>

        <!-- Bagian Grid Menu Akses Praktikum & Leaderboard -->
        <div class="content-grid">
            
            <!-- Kolom Menu Utama -->
            <div class="menu-section">
                <h3 class="section-title">Menu Akses Praktikum</h3>
                
                <div class="cards-container">
                    
                    <!-- Pinjam Alat -->
                    <div class="menu-card">
                        <div>
                            <div class="card-icon"><i class="fa-solid fa-pen-to-square"></i></div>
                            <h4>Pinjam Alat</h4>
                            <p>Ajukan peminjaman alat praktikum baru untuk keperluan lahan.</p>
                        </div>
                        <a href="../peminjaman/tambah.php">Form Peminjaman &rarr;</a>
                    </div>

                    <!-- Jadwal Lapang -->
                    <div class="menu-card">
                        <div>
                            <div class="card-icon"><i class="fa-solid fa-calendar-days"></i></div>
                            <h4>Jadwal Lapang</h4>
                            <p>Cek agenda kegiatan pengolahan lahan & penanaman.</p>
                        </div>
                        <a href="../jadwal/index.php">Lihat Jadwal &rarr;</a>
                    </div>

                    <!-- Cek Stok Alat -->
                    <div class="menu-card">
                        <div>
                            <div class="card-icon"><i class="fa-solid fa-tractor"></i></div>
                            <h4>Cek Stok Alat</h4>
                            <p>Lihat ketersediaan alat sebelum dipinjam ke kebun.</p>
                        </div>
                        <a href="../alat/index.php">Lihat Alat &rarr;</a>
                    </div>

                    <!-- Jurnal Praktikum -->
                    <div class="menu-card">
                        <div>
                            <div class="card-icon"><i class="fa-solid fa-book"></i></div>
                            <h4>Jurnal Praktikum</h4>
                            <p>Kirimkan laporan pengamatan tanaman harianmu.</p>
                        </div>
                        <a href="upload-laporan.php">Kirim Laporan &rarr;</a>
                    </div>

                    <!-- Riwayat Pinjam -->
                    <div class="menu-card">
                        <div>
                            <div class="card-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
                            <h4>Riwayat Pinjam</h4>
                            <p>Cek status disetujui atau ditolak.</p>
                        </div>
                        <a href="riwayat.php">Lihat Status &rarr;</a>
                    </div>

                </div>
            </div>

            <!-- Kolom Samping: Leaderboard Praktik -->
            <div>
                <div class="side-panel">
                    <h4>
                        <span>Leaderboard Praktik</span>
                        <span style="font-size: 11px; color: var(--text-muted); font-weight: normal;">Minggu Ini</span>
                    </h4>
                    
                    <div class="leaderboard-item">
                        <div class="leaderboard-user">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100" alt="Budi">
                            <div>
                                <div class="name">Budi Santoso</div>
                                <div class="class">XI ATPH 2</div>
                            </div>
                        </div>
                        <div class="score-badge">920 Poin</div>
                    </div>

                    <div class="leaderboard-item">
                        <div class="leaderboard-user">
                            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100" alt="Siti">
                            <div>
                                <div class="name"><?= htmlspecialchars($_SESSION['username'] ?? 'Siti Aminah'); ?> (Kamu)</div>
                                <div class="class">XI ATPH 1</div>
                            </div>
                        </div>
                        <div class="score-badge">750 Poin</div>
                    </div>

                    <div class="leaderboard-item">
                        <div class="leaderboard-user">
                            <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100" alt="Rian">
                            <div>
                                <div class="name">Rian Hidayat</div>
                                <div class="class">XI ATPH 1</div>
                            </div>
                        </div>
                        <div class="score-badge">680 Poin</div>
                    </div>

                </div>
            </div>

        </div>
    </main>

</body>
</html>