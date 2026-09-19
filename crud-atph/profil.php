<?php
session_start();
include '../koneksi.php'; // Sesuaikan path jika letaknya berbeda (misal: '../koneksi.php' jika di dalam subfolder)

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
        // Perbarui session nama lengkap
        $_SESSION['nama_lengkap'] = $nama_lengkap;
        
        echo "<script>alert('Profil berhasil dilengkapi!'); window.location='index.php';</script>";
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

    <form method="POST" action="">
        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" required value="<?= htmlspecialchars($user['nama_lengkap'] ?? ''); ?>" placeholder="Masukkan nama lengkap">
        </div>

        <div class="form-group">
            <label>Gmail Valid</label>
            <!-- Menggunakan type="email" agar tervalidasi otomatis oleh browser -->
            <input type="email" name="email" required value="<?= htmlspecialchars($user['email'] ?? ''); ?>" placeholder="contoh@gmail.com">
        </div>

        <?php if ($role == 'siswa'): ?>
            <div class="form-group">
                <label>Kelas</label>
                <input type="text" name="kelas" required value="<?= htmlspecialchars($user['kelas'] ?? ''); ?>" placeholder="Contoh: XII ATPH 1">
            </div>
        <?php endif; ?>

        <button type="submit" name="simpan_profil" class="btn-submit">Simpan & Lanjutkan</button>
    </form>
</div>

</body>
</html>