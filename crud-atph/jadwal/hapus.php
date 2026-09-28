<?php
declare(strict_types=1);
session_start(); include '../config/koneksi.php';
if (($_SESSION['role'] ?? '') !== 'admin') { http_response_code(403); echo "<script>alert('Akses ditolak!');location='index.php';</script>"; exit; }
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);
if(!$id){header('Location:index.php');exit;}
$s=mysqli_prepare($koneksi,'DELETE FROM jadwal WHERE id_jadwal=?');mysqli_stmt_bind_param($s,'i',$id);$ok=mysqli_stmt_execute($s);mysqli_stmt_close($s);
header('Location:index.php?pesan='.($ok?'hapus_sukses':'hapus_gagal'));exit;
