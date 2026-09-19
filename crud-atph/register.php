<?php
session_start();

// 1. Cek apakah user belum login sama sekali
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location='../../login.php';</script>";
    exit();
}

// 2. Cek apakah yang login bukan admin (siswa/tamu ditolak)
if ($_SESSION['role'] != 'admin') {
    echo "<script>alert('Akses ditolak! Hanya admin yang dapat melakukan perubahan.'); window.location='index.php';</script>";
    exit();
}

include '../../koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register Siswa - ATPH</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #122818;
            color: #fff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .register-container {
            width: 100%;
            max-width: 400px;
            background: #1b3823;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }
        h2 {
            text-align: center;
            color: #4CAF50;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #4CAF50;
            border-radius: 5px;
            background: #122818;
            color: #fff;
            box-sizing: border-box;
        }
        .btn-register {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            font-weight: bold;
        }
        .btn-register:hover {
            background-color: #45a049;
        }
        .login-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #aaa;
            text-decoration: none;
        }
        .login-link:hover {
            color: #fff;
        }
    </style>
</head>
<body>

    <div class="register-container">
        <h2>Daftar Akun Siswa</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label>Nama Lengkap:</label>
                <input type="text" name="nama_lengkap" placeholder="Masukkan nama lengkap" required>
            </div>
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" placeholder="Buat username" required>
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" placeholder="Buat password" required>
            </div>
            <button type="submit" name="register" class="btn-register">Daftar Sekarang</button>
        </form>
        <a href="login.php" class="login-link">Sudah punya akun? Login di sini</a>
    </div>

</body>
</html>