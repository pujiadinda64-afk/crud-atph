<?php
include '../koneksi.php';
$nip = $_GET['nip'];
$q = $koneksi->query("SELECT Foto FROM guru WHERE Nip='$nip'")->fetch_assoc();
if($q && file_exists("uploads/".$q['Foto'])) unlink("uploads/".$q['Foto']);
$koneksi->query("DELETE FROM guru WHERE Nip='$nip'");
echo "<script>alert('Data dihapus');location='index.php';</script>";
?>