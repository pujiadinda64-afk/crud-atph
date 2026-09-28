<?php
declare(strict_types=1);

/** @var mysqli $koneksi */

session_start();
include '../config/koneksi.php';

if (($_SESSION['role'] ?? '') !== 'admin') {
    http_response_code(403);
    echo "<script>alert('Akses ditolak! Hanya admin yang dapat menghapus data.'); window.location='index.php';</script>";
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    header('Location: index.php?pesan=tidak_ditemukan');
    exit;
}

$stmt = mysqli_prepare($koneksi, 'SELECT foto FROM alat WHERE id_alat = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$data) {
    header('Location: index.php?pesan=tidak_ditemukan');
    exit;
}

$stmt = mysqli_prepare($koneksi, 'DELETE FROM alat WHERE id_alat = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
$ok = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

if ($ok) {
    if (!empty($data['foto'])) {
        $path = __DIR__ . '/uploads/' . basename($data['foto']);
        if (is_file($path)) @unlink($path);
    }
    header('Location: index.php?pesan=hapus_sukses');
} else {
    header('Location: index.php?pesan=hapus_gagal');
}
exit;
