<?php
include "../../koneksi.php";

/** @var mysqli $koneksi */

if (!isset($_GET["id"]) || !ctype_digit($_GET["id"])) {
    header("Location: index.php");
    exit;
}

$id = (int) $_GET["id"];

// ambil nama file foto dulu, supaya file di folder uploads/ ikut dihapus
$stmt = mysqli_prepare($koneksi, "SELECT foto FROM alat WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$hasil = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($hasil);
mysqli_stmt_close($stmt);

if (!$data) {
    header("Location: index.php?pesan=tidak_ditemukan");
    exit;
}

if (!empty($data["foto"]) && file_exists("uploads/" . $data["foto"])) {
    unlink("uploads/" . $data["foto"]);
}

$stmt_hapus = mysqli_prepare($koneksi, "DELETE FROM alat WHERE id = ?");
mysqli_stmt_bind_param($stmt_hapus, "i", $id);

if (mysqli_stmt_execute($stmt_hapus)) {
    header("Location: index.php?pesan=hapus_sukses");
} else {
    header("Location: index.php?pesan=hapus_gagal");
}
mysqli_stmt_close($stmt_hapus);
exit;
?>