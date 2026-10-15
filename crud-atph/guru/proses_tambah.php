<?php
// --- KONEKSI ANTI ERROR ---
if(file_exists("../../koneksi.php")){
    include "../../koneksi.php";
} elseif(file_exists("../koneksi.php")){
    include "../koneksi.php";
} else {
    include "koneksi.php";
}
if(isset($koneksi) && !isset($conn)) $conn = $koneksi;
if(!isset($conn)) die("Koneksi database gagal");

$nip = $_POST['nip'] ?? '';
$Nama = $_POST['Nama'] ?? '';
$Mapel_Utama = $_POST['Mapel_Utama'] ?? '';
$Wali_Kelas = $_POST['Wali_Kelas'] ?? '';
$foto_nama = "";

// --- PROSES UPLOAD FOTO ---
if(isset($_FILES['Foto']) && $_FILES['Foto']['error'] == 0){
    $ext = pathinfo($_FILES['Foto']['name'], PATHINFO_EXTENSION);
    $ext = strtolower($ext);
    // Nama file: NIP tanpa spasi + timestamp biar unik
    $nip_bersih = preg_replace('/\s+/', '', $nip);
    $foto_nama = $nip_bersih."_".time().".".$ext;
    
    // Coba 2 lokasi folder foto
    $dir1 = "../../foto/";
    $dir2 = "../foto/";
    $dir3 = "foto/";
    
    if(!is_dir($dir1)) mkdir($dir1, 0777, true);
    
    $tujuan = $dir1.$foto_nama;
    move_uploaded_file($_FILES['Foto']['tmp_name'], $tujuan);
    
    // Copy juga ke lokasi lain biar kebaca di data_guru.php
    if(is_dir($dir2)){
        copy($tujuan, $dir2.$foto_nama);
    }
}

// --- SIMPAN KE DATABASE projek.guru ---
// Kolom sesuai screenshot phpMyAdmin lu: nip, Nama, Mapel_Utama, Wali_Kelas, Foto
$nip = mysqli_real_escape_string($conn, $nip);
$Nama = mysqli_real_escape_string($conn, $Nama);
$Mapel_Utama = mysqli_real_escape_string($conn, $Mapel_Utama);
$Wali_Kelas = mysqli_real_escape_string($conn, $Wali_Kelas);

$query = "INSERT INTO guru (nip, Nama, Mapel_Utama, Wali_Kelas, Foto) 
          VALUES ('$nip', '$Nama', '$Mapel_Utama', '$Wali_Kelas', '$foto_nama')";

if(mysqli_query($conn, $query)){
    header("Location: index.php");
    exit;
} else {
    echo "Gagal simpan: ".mysqli_error($conn);
    echo "<br><a href='tambah.php'>Kembali</a>";
}
?>