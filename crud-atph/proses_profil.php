<?php
session_start();
include '../koneksi.php'; // Sesuaikan path koneksi jika file proses-profil.php ada di dalam folder siswa, ubah jadi '../config/koneksi.php'

if (isset($_POST['proses_profil'])) {
    $user_id = $_SESSION['id_user'];
    
    $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $kelas = mysqli_real_escape_string($koneksi, $_POST['kelas']);
// Ganti bagian pengecekan upload di proses-profil.php dengan ini:
if (!empty($_FILES['foto']['name'])) {
    $nama_file = $_FILES['foto']['name'];
    $tmp_file = $_FILES['foto']['tmp_name'];
    $error_upload = $_FILES['foto']['error'];

    // Cek apakah ada error saat file dikirim oleh browser
    if ($error_upload === 0) {
        $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg');
        $x = explode('.', $nama_file);
        $ekstensi = strtolower(end($x));
        
        $nama_file_baru = 'profil_' . $user_id . '_' . time() . '.' . $ekstensi;

        if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
            // Perhatikan apakah folder uploads ada di luar atau di dalam folder yang sama. 
            // Jika file proses-profil.php ada di luar (satu level dengan folder uploads), gunakan 'uploads/'
            // Jika file proses-profil.php ada di dalam folder siswa, gunakan '../uploads/'
            $tujuan = '../../uploads/' . $nama_file_baru; 

            if (move_uploaded_file($tmp_file, $tujuan)) {
                $query = "UPDATE users SET nama_lengkap = '$nama_lengkap', email = '$email', kelas = '$kelas', foto = '$nama_file_baru' WHERE id_user = '$user_id'";
            } else {
                echo "<script>alert('Gagal memindahkan file! Periksa izin folder uploads.'); window.location='profil.php';</script>";
                exit;
            }
        } else {
            echo "<script>alert('Format file harus JPG, JPEG, atau PNG!'); window.location='profil.php';</script>";
            exit;
        }
    } else {
        echo "<script>alert('Terjadi kesalahan error pada sistem file (Kode error: $error_upload)!'); window.location='profil.php';</script>";
        exit;
    }

        } else {
            echo "<script>alert('Format file harus JPG, JPEG, atau PNG!'); window.location='profil.php';</script>";
            exit;
        }
    } else {
        // Jika tidak ganti foto
        $query = "UPDATE users SET nama_lengkap = '$nama_lengkap', email = '$email', kelas = '$kelas' WHERE id_user = '$user_id'";
    }

    $update = mysqli_query($koneksi, $query);
    
    if ($update) {
        echo "<script>alert('Profil berhasil diperbarui!'); window.location='siswa/index.php';</script>";
    } else {
        echo "<script>alert('Gagal update database: " . mysqli_error($koneksi) . "'); window.location='profil.php';</script>";
    
}

?>