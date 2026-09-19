<?php
session_start(); // Wajib ada untuk baca session

// Cek apakah user adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "<script>alert('Akses ditolak! Fitur ini khusus untuk Admin.'); window.location='index.php';</script>";
    exit();
}

include '../../koneksi.php';
$nip = $_GET['nip'];
$q = $koneksi->query("SELECT Foto FROM guru WHERE Nip='$nip'")->fetch_assoc();
if($q && file_exists("uploads/".$q['Foto'])) unlink("uploads/".$q['Foto']);
$koneksi->query("DELETE FROM guru WHERE Nip='$nip'");
echo "<script>alert('Data dihapus');location='index.php';</script>";
?>