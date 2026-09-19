<?php
session_start();
include '../koneksi.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
    $user  = mysqli_fetch_assoc($query);

  if ($user && $password == $user['password']) {
        $_SESSION['user_id']      = $user['id_user'];
        $_SESSION['username']     = $user['username'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
        $_SESSION['role']         = $user['role'];

        // CEK APAKAH PROFIL SUDAH DIISI ATAU BELUM
        if (empty($user['nama_lengkap']) || empty($user['email'])) {
            // Jika data profil masih kosong, arahkan ke halaman profil.php
            header("Location: profil.php");
            exit();
        }

        // Redirect sesuai role jika profil sudah lengkap
        if ($user['role'] == 'admin') {
            header("Location: index.php"); // Atau dashboard admin
        } else {
            header("Location: index.php"); // Ke halaman utama siswa
        }
        exit();
    } else {
        $error = "Username atau password salah!";
    }

}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - ATPH System</title>
    <style>
        body { font-family: sans-serif; background: #f0f7f4; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-card { background: white; padding: 30px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); width: 320px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; color: #1b4332; font-weight: bold; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px; box-sizing: border-box; }
        .btn-login { width: 100%; padding: 12px; background: #2e7d32; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>

<div class="login-card">
    <h2 style="color: #1b4332; text-align: center; margin-bottom: 20px;">Login ATPH</h2>
    
    <?php if (isset($error)): ?>
        <p style="color: red; font-size: 13px; text-align: center;"><?= $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required placeholder="Masukkan username">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required placeholder="Masukkan password">
        </div>
        <button type="submit" name="login" class="btn-login">Masuk</button>
    </form>
</div>

</body>
</html>