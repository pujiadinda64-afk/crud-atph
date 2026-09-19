<?php
session_start(); // Wajib ada di paling atas

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "<script>alert('Akses ditolak! Halaman ini khusus untuk Admin.'); window.location='index.php';</script>";
    exit();
}
// 2. Cek apakah role-nya bukan admin/guru (sesuaikan kebutuhan, misal hanya admin)
if ($_SESSION['role'] != 'admin') {
    echo "<script>alert('Akses ditolak! Anda tidak memiliki izin.'); window.location='index.php';</script>";
    exit();
}
?>
include '../../koneksi.php';

$id = $_GET['id'];
mysqli_query($koneksi, "DELETE FROM kegiatan WHERE id='$id'");

header("Location: index.php");
?>