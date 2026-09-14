<?php
include '../koneksi.php';

if (isset($_POST['simpan'])) {
    $nip          = $_POST['nip'];
    $nama         = $_POST['nama']; // dulu: nama_guru
    $mapel_utama  = $_POST['mapel_utama']; // dulu: jabatan
    $wali_kelas   = $_POST['wali_kelas']; // field baru

    // Proses Upload Foto
    $foto_name = $_FILES['foto']['name'];
    $foto_tmp  = $_FILES['foto']['tmp_name'];
    
    // Rename foto agar unik
    $foto_baru = time() . '_' . $foto_name;
    $folder    = 'uploads/' . $foto_baru;

    if (move_uploaded_file($foto_tmp, $folder)) {
        $query = "INSERT INTO guru (Nip, Nama, Mapel_Utama, Wali_Kelas, Foto) 
                  VALUES ('$nip', '$nama', '$mapel_utama', '$wali_kelas', '$foto_baru')";
        
        if (mysqli_query($koneksi, $query)) {
            header("Location: index.php");
            exit;
        } else {
            echo "Gagal menyimpan data ke database: " . mysqli_error($koneksi);
        }
    } else {
        echo "Gagal mengupload foto.";
    }
}
?>