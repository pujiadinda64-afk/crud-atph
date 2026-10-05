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

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Siswa - ATPH</title>
    <link rel="stylesheet" href="../style.css">
    <!-- Tambahan FontAwesome untuk ikon agar lebih menarik -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="hero-container" style="min-height: auto; padding-bottom: 40px; max-width: 900px; margin: 40px auto; background: #ffffff; padding: 30px; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.05);">
        
        <!-- Header -->
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px; border-bottom: 2px solid #f0f0f0; padding-bottom: 15px;">
            <h2>Halo, <?= htmlspecialchars($_SESSION['username'] ?? 'Siswa'); ?>! 👋</h2>
            <a href="../logout.php" class="btn-primary" style="background:#e63946; padding: 8px 16px; border-radius: 8px; color: #fff; text-decoration:none;">Keluar</a>
        </div>

        <p style="color: #666; margin-bottom: 20px;">Berikut adalah menu akses untuk kegiatan praktikum dan peminjaman alat pertanian kamu:</p>

        <!-- Ringkasan Fitur / Menu Siswa -->
        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:20px; margin-top:20px;">
            
            <!-- MENU UTAMA PEMINJAMAN ALAT -->
            <div style="background:#f8f9fa; padding:20px; border-radius:16px; border:1px solid #e2e8f0; transition: transform 0.2s;">
                <h3 style="color: #2b6cb0; margin-bottom: 8px;"><i class="fa-solid fa-pen-to-square"></i> Pinjam Alat</h3>
                <p style="font-size: 14px; color: #4a5568; margin-bottom: 12px;">Ajukan peminjaman alat praktikum baru.</p>
                <!-- Sesuaikan path ini dengan letak folder peminjaman kamu -->
                <a href="../peminjaman/tambah.php" style="color: #2b6cb0; font-weight: bold; text-decoration: none;">Form Peminjaman &rarr;</a>
            </div>

            <div style="background:#f8f9fa; padding:20px; border-radius:16px; border:1px solid #e2e8f0;">
                <h3 style="color: #2b6cb0; margin-bottom: 8px;"><i class="fa-solid fa-calendar-days"></i> Jadwal Lapang</h3>
                <p style="font-size: 14px; color: #4a5568; margin-bottom: 12px;">Cek agenda kegiatan pengolahan lahan & penanaman.</p>
                <a href="../jadwal/index.php" style="color: #2b6cb0; font-weight: bold; text-decoration: none;">Lihat Jadwal &rarr;</a>
            </div>

            <div style="background:#f8f9fa; padding:20px; border-radius:16px; border:1px solid #e2e8f0;">
                <h3 style="color: #2b6cb0; margin-bottom: 8px;"><i class="fa-solid fa-tractor"></i> Cek Stok Alat</h3>
                <p style="font-size: 14px; color: #4a5568; margin-bottom: 12px;">Lihat ketersediaan alat sebelum dipinjam ke kebun.</p>
                <a href="../alat/index.php" style="color: #2b6cb0; font-weight: bold; text-decoration: none;">Lihat Alat &rarr;</a>
            </div>

            <div style="background:#f8f9fa; padding:20px; border-radius:16px; border:1px solid #e2e8f0;">
                <h3 style="color: #2b6cb0; margin-bottom: 8px;"><i class="fa-solid fa-book"></i> Jurnal Praktikum</h3>
                <p style="font-size: 14px; color: #4a5568; margin-bottom: 12px;">Kirimkan laporan pengamatan tanaman harianmu.</p>
                <a href="upload-laporan.php" style="color: #2b6cb0; font-weight: bold; text-decoration: none;">Kirim Laporan &rarr;</a>
            </div>

            <div style="background:#f8f9fa; padding:20px; border-radius:16px; border:1px solid #e2e8f0;">
    <h3 style="color: #2b6cb0; margin-bottom: 8px;"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Pinjam</h3>
    <p style="font-size: 14px; color: #4a5568; margin-bottom: 12px;">Cek status disetujui atau ditolak.</p>
    <a href="riwayat.php" style="color: #2b6cb0; font-weight: bold; text-decoration: none;">Lihat Status &rarr;</a>
</div>

        </div>
    </div>
</body>
</html>