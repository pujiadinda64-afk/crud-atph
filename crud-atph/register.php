<?php
session_start();
include '../koneksi.php'; // Sesuaikan jalur file koneksi database kamu

if (isset($_POST['register'])) {
    $username     = mysqli_real_escape_string($koneksi, $_POST['username']);
    $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $kelas        = mysqli_real_escape_string($koneksi, $_POST['kelas']); // Tambahan untuk menyimpan kelas
    $password     = $_POST['password']; 
    $role         = 'siswa'; // Diubah otomatis menjadi 'siswa' agar masuk ke portal siswa

    // Cek apakah username sudah digunakan sebelumnya
    $cek_user = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($cek_user) > 0) {
        echo "<script>alert('Username sudah digunakan, silakan pilih username lain!');</script>";
    } else {
        // Simpan data ke tabel users beserta kelas, role siswa, dan poin awal 0
        $query = "INSERT INTO users (username, password, nama_lengkap, kelas, role, poin) VALUES ('$username', '$password', '$nama_lengkap', '$kelas', '$role', 0)";
        $insert = mysqli_query($koneksi, $query);

        if ($insert) {
            echo "<script>alert('Registrasi akun siswa berhasil! Silakan login.'); window.location='login.php';</script>";
        } else {
            echo "<script>alert('Registrasi gagal, silakan coba lagi.');</script>";
        }
    }
}
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
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
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
        input[type="text"], input[type="password"], select {
            width: 100%;
            padding: 10px;
            border: 1px solid #4CAF50;
            border-radius: 5px;
            background: #122818;
            color: #fff;
            box-sizing: border-box;
        }
        select option {
            background: #122818;
            color: #fff;
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
            margin-top: 10px;
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
                <label>Kelas:</label>
                <select name="kelas" required>
                    <option value="">-- Pilih Kelas --</option>
                    <option value="XI ATPH 1">XI ATPH 1</option>
                    <option value="XI ATPH 2">XI ATPH 2</option>
                    <option value="XII ATPH 1">XII ATPH 1</option>
                    <option value="XII ATPH 2">XII ATPH 2</option>
                </select>
            </div>

            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" placeholder="Buat username unik" required>
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