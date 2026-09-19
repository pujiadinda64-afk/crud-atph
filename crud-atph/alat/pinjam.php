<?php
session_start();

// 1. CEK KEAMANAN: Tamu (belum login) dilarang pinjam!
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Silakan login terlebih dahulu sebagai siswa atau admin untuk meminjam alat!'); window.location='../../login.php';</script>";
    exit();
}

include '../../koneksi.php';

// Ambil ID atau kode alat yang diklik dari halaman sebelumnya
$id_alat = isset($_GET['id']) ? $_GET['id'] : '';

// Ambil data alat berdasarkan ID untuk ditampilkan di form
$query_alat = mysqli_query($koneksi, "SELECT * FROM alat WHERE id_alat = '$id_alat'"); // Sesuaikan nama kolom primary key alat kamu
$alat = mysqli_fetch_assoc($query_alat);

// Jika tombol pinjam diklik
if (isset($_POST['proses_pinjam'])) {
    $user_id       = $_SESSION['user_id'];
    $tanggal_pinjam = $_POST['tanggal_pinjam'];
    $jumlah        = $_POST['jumlah'];
    $status        = 'Dipinjam';

    // Simpan ke database tabel peminjaman
    $simpan = mysqli_query($koneksi, "INSERT INTO peminjaman (id_user, id_alat, tanggal_pinjam, jumlah, status) 
              VALUES ('$user_id', '$id_alat', '$tanggal_pinjam', '$jumlah', '$status')");

    if ($simpan) {
        echo "<script>alert('Peminjaman berhasil diajukan!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal meminjam alat, coba lagi.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Peminjaman Alat - ATPH</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #122818;
            color: #fff;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 500px;
            margin: 40px auto;
            background: #1b3823;
            padding: 25px;
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
        input[type="text"], input[type="date"], input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #4CAF50;
            border-radius: 5px;
            background: #122818;
            color: #fff;
            box-sizing: border-box;
        }
        .btn-submit {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            font-weight: bold;
        }
        .btn-submit:hover {
            background-color: #45a049;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #aaa;
            text-decoration: none;
        }
        .back-link:hover {
            color: #fff;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Form Peminjaman Alat</h2>
        
        <form action="" method="POST">
            <div class="form-group">
                <label>Peminjam:</label>
                <!-- Menampilkan nama lengkap dari session orang yang sedang login -->
                <input type="text" value="<?= $_SESSION['nama_lengkap']; ?>" disabled>
            </div>

            <div class="form-group">
                <label>Nama Alat:</label>
                <!-- Menampilkan nama alat yang dipilih -->
                <input type="text" value="<?= isset($alat['nama_alat']) ? $alat['nama_alat'] : 'Alat tidak ditemukan'; ?>" disabled>
            </div>

            <div class="form-group">
                <label>Tanggal Pinjam:</label>
                <input type="date" name="tanggal_pinjam" required value="<?= date('Y-m-d'); ?>">
            </div>

            <div class="form-group">
                <label>Jumlah yang Dipinjam:</label>
                <input type="number" name="jumlah" min="1" value="1" required>
            </div>

            <button type="submit" name="proses_pinjam" class="btn-submit">Ajukan Peminjaman</button>
        </form>

        <a href="index.php" class="back-link">&larr; Kembali ke Daftar Alat</a>
    </div>

</body>
</html>