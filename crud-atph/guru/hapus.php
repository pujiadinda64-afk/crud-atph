<?php
include '../config/koneksi.php';
$nip = $_GET['nip'];
mysqli_query($koneksi, "DELETE FROM guru WHERE Nip='$nip' OR nip='$nip'");
header("Location: index.php");
?>