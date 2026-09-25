<?php
declare(strict_types=1);

session_start();
include '../config/koneksi.php';

if (($_SESSION['role'] ?? '') !== 'admin') {
    http_response_code(403);
    echo "<script>alert('Akses ditolak! Hanya admin yang dapat menghapus data.'); window.location='index.php';</script>";
    exit;
}

$nip = trim($_GET['nip'] ?? '');
if ($nip === '' || strlen($nip) > 20) {
    header('Location: index.php?pesan=tidak_ditemukan');
    exit;
}

$stmt = mysqli_prepare($koneksi, 'SELECT Foto FROM guru WHERE Nip = ?');
mysqli_stmt_bind_param($stmt, 's', $nip);
mysqli_stmt_execute($stmt);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$data) {
    header('Location: index.php?pesan=tidak_ditemukan');
    exit;
}

$stmt = mysqli_prepare($koneksi, 'DELETE FROM guru WHERE Nip = ?');
mysqli_stmt_bind_param($stmt, 's', $nip);
$ok = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

if ($ok) {
    if (!empty($data['Foto'])) {
        $path = __DIR__ . '/uploads/' . basename($data['Foto']);
        if (is_file($path)) @unlink($path);
    }
    header('Location: index.php?pesan=hapus_sukses');
} else {
    header('Location: index.php?pesan=hapus_gagal');
}
exit;
