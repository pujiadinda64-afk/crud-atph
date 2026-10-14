<?php
session_start();

if (($_SESSION['role'] ?? '') !== 'admin') {
    echo "<script>alert('Akses ditolak! Halaman ini khusus untuk Admin.'); window.location='index.php';</script>";
    exit();
}
include '../config/koneksi.php';
$nip = $_GET['nip'];
mysqli_query($koneksi, "DELETE FROM guru WHERE Nip='$nip' OR nip='$nip'");
header("Location: index.php");
?>