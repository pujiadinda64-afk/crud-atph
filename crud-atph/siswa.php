<?php
session_start();
include '../koneksi.php';

// Proteksi Halaman: Hanya Siswa yang bisa akses
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'siswa') {
    header("Location: ../login.php");
    exit();
}

// Ambil data jadwal lapang & alat untuk siswa
$query_jadwal = mysqli_query($koneksi, "SELECT * FROM jadwal");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Portal Siswa - ATPH</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="hero-container" style="min-height: auto; padding-bottom: 40px;">
        <!-- Header -->
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px;">
            <h2>Halo, <?= $_SESSION['username']; ?>! 👋</h2>
            <a href="../logout.php" class="btn-primary" style="background:#e63946;">Keluar</a>
        </div>

        <p>Berikut adalah informasi agenda kegiatan praktikum dan inventaris alat pertanian kamu:</p>

        <!-- Ringkasan Fitur Siswa -->
        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:15px; margin-top:20px;">
            <div style="background:#fff; padding:20px; border-radius:16px; border:1px solid #ddd;">
                <h3>📅 Jadwal Lapang</h3>
                <p>Cek agenda kegiatan pengolahan lahan & penanaman.</p>
                <a href="../jadwal/index.php">Lihat Jadwal &rarr;</a>
            </div>
            <div style="background:#fff; padding:20px; border-radius:16px; border:1px solid #ddd;">
                <h3>🚜 Cek Stok Alat</h3>
                <p>Lihat ketersediaan alat sebelum dipinjam ke kebun.</p>
                <a href="../alat/index.php">Lihat Alat &rarr;</a>
            </div>
            <div style="background:#fff; padding:20px; border-radius:16px; border:1px solid #ddd;">
                <h3>🌱 Jurnal Praktikum</h3>
                <p>Kirimkan laporan pengamatan tanaman harianmu.</p>
                <a href="upload-laporan.php">Kirim Laporan &rarr;</a>
            </div>
        </div>
    </div>
</body>
</html>