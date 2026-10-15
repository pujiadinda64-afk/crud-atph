<?php
session_start(); // Wajib ada untuk membaca data login

// Cek apakah yang mengakses adalah admin ATAU siswa
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'siswa')) {
    echo "<script>alert('Akses ditolak! Halaman khusus Admin'); window.location='index.php';</script>";
    exit();
}

include '../../koneksi.php';

// Hanya menerima POST (dikirim dari form tombol Hapus)
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
    header("Location: index.php");
    exit();
}

$id      = intval($_POST['id']);
$kelas   = isset($_POST['kelas'])  ? intval($_POST['kelas'])  : 0;
$rombel  = isset($_POST['rombel']) ? intval($_POST['rombel']) : 0;
$kembali = (isset($_POST['kembali']) && $_POST['kembali'] === 'tambah') ? 'tambah.php' : 'detail.php';

// Hapus dari tabel jadwal_lapang
$stmt = mysqli_prepare($koneksi, "DELETE FROM jadwal_lapang WHERE id = ?");
if (!$stmt) {
    die("Query gagal: " . mysqli_error($koneksi));
}
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    header("Location: $kembali?kelas=$kelas&rombel=$rombel");
    exit();
} else {
    echo "Gagal menghapus data: " . mysqli_stmt_error($stmt);
}
?>
