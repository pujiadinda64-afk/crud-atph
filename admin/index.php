<?php
session_start();

// Proteksi Halaman: Jika belum login atau bukan guru, tendang balik ke login.php
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - ATPH</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #2c3e50;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
        }
        .btn-logout {
            background-color: #e74c3c;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .btn-logout:hover {
            background-color: #c0392b;
        }
        .container {
            padding: 30px;
            max-width: 1000px;
            margin: auto;
        }
        .welcome-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 25px;
        }
        .grid-menu {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }
        .card-menu {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.2s;
        }
        .card-menu:hover {
            transform: translateY(-5px);
        }
        .card-menu h3 {
            margin-top: 0;
            color: #2c3e50;
        }
        .card-menu a {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 16px;
            background-color: #27ae60;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .card-menu a:hover {
            background-color: #219150;
        }
    </style>
</head>
<body>

    <!-- Header / Navbar -->
    <div class="header">
        <h1>Sistem Informasi ATPH - Panel Administrator</h1>
        <a href="../logout.php" class="btn-logout">Logout</a>
    </div>

    <!-- Main Container -->
    <div class="container">
        <div class="welcome-card">
            <h2>Selamat Datang, <?= htmlspecialchars($_SESSION['username']); ?>!</h2>
            <p>Anda masuk sebagai <strong>Administrator</strong>. Silakan pilih menu pengelolaan di bawah ini.</p>
        </div>

        <!-- Menu Utama Guru -->
        <div class="grid-menu">
            <div class="card-menu">
                <h3>Kelola Data Guru</h3>
                <p>Lihat dan edit daftar data guru.</p>
                <a href="data_guru.php">Buka Data Guru</a>
            </div>

            <div class="card-menu">
                <h3>Kelola Alat</h3>
                <p>Tambah, edit, dan hapus data alat ATPH.</p>
                <a href="../alat/index.php">Buka Data Alat</a>
            </div>

            <div class="card-menu">
                <h3>Jadwal Lahan</h3>
                <p>Atur pemakaian lahan praktikum.</p>
                <a href="../jadwal/index.php">Buka Jadwal</a>
            </div>

             <div class="card-menu">
                <h3>Tanaman Praktikum</h3>
                <p>Atur Tanaman praktikum.</p>
                <a href="../tanaman/index.php">Buka Jadwal</a>
            </div>
        </div>
    </div>

</body>
</html>