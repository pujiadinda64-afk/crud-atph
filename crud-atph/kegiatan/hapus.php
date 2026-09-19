<?php
<?php
session_start(); // Wajib ada di paling atas

// 1. Cek apakah user BELUM LOGIN SAMA SEKALI
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location='../../login.php';</script>";
    exit();
}

// 2. Cek apakah role-nya bukan admin/guru (sesuaikan kebutuhan, misal hanya admin)
if ($_SESSION['role'] != 'admin' 'siswa') {
    echo "<script>alert('Akses ditolak! Anda tidak memiliki izin.'); window.location='index.php';</script>";
    exit();
}
?>
include '../../koneksi.php';

$id = $_GET['id'];
mysqli_query($koneksi, "DELETE FROM kegiatan WHERE id='$id'");

header("Location: index.php");
?>