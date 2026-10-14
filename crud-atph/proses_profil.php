<?php
session_start();
include '../config/koneksi.php'; // Sesuaikan dengan path folder config/koneksi kamu

if (isset($_POST['proses_profil'])) {
    $user_id = $_SESSION['id_user'];
    
    // Ambil dan amankan data inputan form
    $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $kelas = mysqli_real_escape_string($koneksi, $_POST['kelas']);
    
    // Query update data profil tanpa repot mengurus file foto
    $query = "UPDATE users SET nama_lengkap = '$nama_lengkap', email = '$email', kelas = '$kelas' WHERE id_user = '$user_id'";
    $update = mysqli_query($koneksi, $query);
    
    if ($update) {
        // Update juga session nama lengkap agar langsung berubah di navbar/sidebar
        $_SESSION['nama_lengkap'] = $nama_lengkap;
        
        echo "<script>alert('Profil berhasil diperbarui!'); window.location='siswa/index.php';</script>";
    } else {
        echo "<script>alert('Gagal update database: " . mysqli_error($koneksi) . "'); window.location='profil.php';</script>";
    }
} else {
    header("Location: profil.php");
    exit;
}
?>