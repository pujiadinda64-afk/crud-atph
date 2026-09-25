<?php
declare(strict_types=1);

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

$stmt = mysqli_prepare($koneksi, 'SELECT foto FROM kegiatan WHERE id_kegiatan = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$data) {
    header('Location: index.php?pesan=tidak_ditemukan');
    exit;
}

mysqli_begin_transaction($koneksi);
try {
    // Hapus semua foto tambahan dan file fisiknya.
    $stmt = mysqli_prepare($koneksi, 'SELECT nama_foto FROM foto_kegiatan WHERE id_kegiatan = ?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $files = [];
    while ($row = mysqli_fetch_assoc($result)) {
        if (!empty($row['nama_foto'])) $files[] = $row['nama_foto'];
    }
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($koneksi, 'DELETE FROM foto_kegiatan WHERE id_kegiatan = ?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($koneksi, 'DELETE FROM kegiatan WHERE id_kegiatan = ?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    mysqli_commit($koneksi);

    if (!empty($data['foto'])) {
        $path = __DIR__ . '/uploads/' . basename($data['foto']);
        if (is_file($path)) @unlink($path);
    }
    foreach ($files as $file) {
        $path = __DIR__ . '/uploads/' . basename($file);
        if (is_file($path)) @unlink($path);
    }

    header('Location: index.php?pesan=hapus_sukses');
    exit;
} catch (Throwable $e) {
    mysqli_rollback($koneksi);
    header('Location: index.php?pesan=hapus_gagal');
    exit;
}
