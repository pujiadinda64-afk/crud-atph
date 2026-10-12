<?php
session_start();
include 'config/koneksi.php'; // Sesuaikan path jika letaknya berbeda (misal: '../koneksi.php' jika di dalam subfolder)

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role    = $_SESSION['role'];

// Ambil data terbaru dari database
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user = '$user_id'");
$user  = mysqli_fetch_assoc($query);

// Jika tombol simpan ditekan
if (isset($_POST['simpan_profil'])) {
    $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $email        = mysqli_real_escape_string($koneksi, $_POST['email']);
    // Jika role siswa ambil input kelas, jika admin set NULL
    $kelas        = ($role == 'siswa') ? mysqli_real_escape_string($koneksi, $_POST['kelas']) : NULL;

    // Update data ke database
    if ($role == 'siswa') {
        $update = mysqli_query($koneksi, "UPDATE users SET nama_lengkap = '$nama_lengkap', email = '$email', kelas = '$kelas' WHERE id_user = '$user_id'");
    } else {
        $update = mysqli_query($koneksi, "UPDATE users SET nama_lengkap = '$nama_lengkap', email = '$email' WHERE id_user = '$user_id'");
    }

   if ($update) {
        // Perbarui session nama lengkap juga (opsional untuk jaga-jaga)
        $_SESSION['nama_lengkap'] = $nama_lengkap;
        
        // Karena file proses ada di luar dan tujuan ada di dalam folder siswa, arahkan ke:
        echo "<script>alert('Profil berhasil dilengkapi!'); window.location='siswa/index.php';</script>";
        exit();
    } else {
        $error = "Gagal menyimpan profil, coba lagi.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lengkapi Profil - ATPH System</title>
    <style>
        body { font-family: sans-serif; background: #f0f7f4; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 30px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); width: 380px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; color: #1b4332; font-weight: bold; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px; box-sizing: border-box; }
        .btn-submit { width: 100%; padding: 12px; background: #2e7d32; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>

<div class="card">
    <h2 style="color: #1b4332; text-align: center; margin-bottom: 10px;">Lengkapi Profil</h2>
    <p style="font-size: 13px; color: #666; text-align: center; margin-bottom: 20px;">
        Halo <b><?= htmlspecialchars($user['username']); ?></b>, silakan lengkapi data diri Anda sebagai <b><?= ucfirst($role); ?></b>.
    </p>

    <?php if (isset($error)): ?>
        <p style="color: red; font-size: 13px; text-align: center;"><?= $error; ?></p>
    <?php endif; ?>

    <!-- FORM UTAMA MENCAKUP SEMUA INPUT -->
    <form action="proses_profil.php" method="POST" enctype="multipart/form-data">
        
        <div class="form-group" style="margin-bottom: 15px;">
            <label>Nama Lengkap</label><br>
            <input type="text" name="nama_lengkap" value="<?php echo $data_user['nama_lengkap'] ?? ''; ?>" style="width: 100%; padding: 8px;">
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <label>Gmail Valid</label><br>
            <input type="email" name="email" value="<?php echo $data_user['email'] ?? ''; ?>" style="width: 100%; padding: 8px;">
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <label>Ganti Foto Profil (JPG/PNG):</label><br>
            <input type="file" name="foto" accept="image/*" style="margin-top: 5px;">
            <small style="color: gray; display: block; margin-top: 3px;">*Kosongkan jika tidak ingin mengganti foto.</small>
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <label>Kelas</label><br>
            <input type="text" name="kelas" value="<?php echo $data_user['kelas'] ?? ''; ?>" style="width: 100%; padding: 8px;">
        </div>

        <!-- Tombol Simpan Utama di Bawah -->
        <button type="submit" name="simpan_profil" class="btn-primary" style="width: 100%; padding: 10px; background: #10b981; color: white; border: none; border-radius: 5px; cursor: pointer;">Simpan & Lanjutkan</button>
    
    </form>
</div>

</body>
</html>