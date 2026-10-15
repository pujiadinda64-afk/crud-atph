<?php
session_start(); // Wajib ada untuk membaca data login

// Cek apakah yang mengakses adalah admin ATAU siswa
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'siswa')) {
    echo "<script>alert('Akses ditolak! Halaman khusus Admin'); window.location='index.php';</script>";
    exit();
}

include '../../koneksi.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['id']) ? intval($_POST['id']) : 0);

if (!$id) {
    header("Location: index.php");
    exit;
}

// Ambil data yang akan diedit dari tabel jadwal_lapang
$stmt = mysqli_prepare($koneksi, "SELECT * FROM jadwal_lapang WHERE id = ?");
if (!$stmt) {
    die("Query gagal: " . mysqli_error($koneksi));
}
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$data) {
    header("Location: index.php");
    exit;
}

$kelas  = (int) $data['kelas'];
$rombel = (int) $data['rombel'];

// Simpan perubahan
if (isset($_POST['submit'])) {
    $kegiatan   = trim($_POST['kegiatan']);
    $lokasi     = trim($_POST['lokasi']);
    $tanggal    = $_POST['tanggal'];
    $jam        = trim($_POST['jam']);
    $pembimbing = trim($_POST['pembimbing']);

    $stmt = mysqli_prepare($koneksi, "UPDATE jadwal_lapang SET kegiatan=?, lokasi=?, tanggal=?, jam=?, pembimbing=? WHERE id=?");
    if (!$stmt) {
        die("Query gagal: " . mysqli_error($koneksi));
    }
    mysqli_stmt_bind_param($stmt, "sssssi", $kegiatan, $lokasi, $tanggal, $jam, $pembimbing, $id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: detail.php?kelas=$kelas&rombel=$rombel");
        exit;
    } else {
        echo "Gagal memperbarui data: " . mysqli_stmt_error($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Jadwal ATPH</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        body { background-color: #f4f7f6; padding: 30px; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .form-container { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); width: 100%; max-width: 500px; }
        h2 { color: #2c3e50; margin-bottom: 20px; font-size: 22px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-size: 14px; font-weight: 500; color: #333; }
        input[type="text"], input[type="date"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; outline: none; }
        input:focus { border-color: #f39c12; }
        .btn-submit { background: #f39c12; color: white; border: none; padding: 12px; width: 100%; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; margin-top: 10px; }
        .btn-submit:hover { background: #d68910; }
        .back-link { display: block; text-align: center; margin-top: 15px; color: #7f8c8d; text-decoration: none; font-size: 14px; }
        .back-link:hover { color: #333; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit Jadwal Kegiatan <br><small style="font-size:14px;color:#7f8c8d;font-weight:500;">Kelas <?= $kelas; ?> ATPH <?= $rombel; ?></small></h2>
        <form method="POST" action="edit.php?id=<?= $id; ?>">
            <input type="hidden" name="id" value="<?= $id; ?>">
            <div class="form-group">
                <label>Nama Kegiatan</label>
                <input type="text" name="kegiatan" value="<?= htmlspecialchars($data['kegiatan']); ?>" required>
            </div>
            <div class="form-group">
                <label>Lokasi Lahan</label>
                <input type="text" name="lokasi" value="<?= htmlspecialchars($data['lokasi']); ?>" required>
            </div>
            <div class="form-group">
                <label>Tanggal</label>
                <input type="date" name="tanggal" value="<?= htmlspecialchars($data['tanggal']); ?>" required>
            </div>
            <div class="form-group">
                <label>Jam</label>
                <input type="text" name="jam" value="<?= htmlspecialchars($data['jam']); ?>" required>
            </div>
            <div class="form-group">
                <label>Pembimbing</label>
                <input type="text" name="pembimbing" value="<?= htmlspecialchars($data['pembimbing']); ?>" required>
            </div>
            <button type="submit" name="submit" class="btn-submit">Perbarui Jadwal</button>
        </form>
        <a href="detail.php?kelas=<?= $kelas; ?>&rombel=<?= $rombel; ?>" class="back-link">&larr; Kembali ke Jadwal Kelas <?= $kelas; ?> ATPH <?= $rombel; ?></a>
    </div>
</body>
</html>
