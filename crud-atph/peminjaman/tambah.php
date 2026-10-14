<?php
session_start();
include '../config/koneksi.php';

// Hapus proteksi role siswa ketat jika siswa tidak punya akun login khusus, 
// atau biarkan jika halaman ini bebas diakses oleh siapa saja yang meminjam.

// Jika tombol pinjam diklik
if (isset($_POST['pinjam'])) {
    $nama_peminjam = mysqli_real_escape_string($koneksi, $_POST['nama_peminjam']); // Diambil dari ketikan manual
    $id_alat       = $_POST['id_alat']; 
    $jumlah_pinjam = $_POST['jumlah_pinjam'];
    $tgl_pinjam    = $_POST['tgl_pinjam'];
    $status        = 'Pending'; // Status awal saat meminjam

    // Simpan ke database
    $query = "INSERT INTO peminjaman (nama_peminjam, id_alat, jumlah_pinjam, tgl_pinjam, status) 
              VALUES ('$nama_peminjam', '$id_alat', '$jumlah_pinjam', '$tgl_pinjam', '$status')";
              
    if (mysqli_query($koneksi, $query)) {
       echo "<script>alert('Profil berhasil dilengkapi!'); window.location='../siswa/index.php';</script>";
    } else {
        echo "<script>alert('Gagal mengajukan peminjaman: " . mysqli_error($koneksi) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Peminjaman Alat - ATPH</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background-color: #fcf8f2; color: #2d3748; min-height: 100vh; display: flex; flex-direction: column; }
        
        .header {
            background: #ffffff;
            border-bottom: 1px solid rgba(0,0,0,0.06);
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        }
        .header h1 { color: #1b4332; font-size: 20px; font-weight: 800; }
        .btn-back {
            background-color: #718096;
            color: white;
            padding: 10px 18px;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 13px;
            transition: all 0.2s;
        }
        .btn-back:hover { background-color: #4a5568; }

        .container { padding: 40px; max-width: 600px; margin: auto; width: 100%; }
        
        .card-form {
            background: #ffffff;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            border: 1px solid rgba(72,187,120,0.15);
        }
        .card-form h2 { color: #2d3748; font-size: 20px; font-weight: 800; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .card-form h2 i { color: #48bb78; }

        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 700; font-size: 13px; color: #1b4332; margin-bottom: 8px; }
        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            outline: none;
            transition: all 0.2s;
            background-color: #fff;
        }
        .form-control:focus { border-color: #48bb78; }

        .btn-submit {
            background-color: #48bb78;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            width: 100%;
            transition: all 0.2s;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }
        .btn-submit:hover { background-color: #38a169; }
    </style>
</head>
<body>

    <div class="header">
        <h1><i class="fa-solid fa-tools"></i> Form Pinjam Alat</h1>
        <a href="../index.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda</a>
    </div>

    <div class="container">
        <div class="card-form">
            <h2><i class="fa-solid fa-pen-to-square"></i> Form Pengajuan Peminjaman</h2>
            
            <form action="" method="POST">
                <div class="form-group">
                    <label>Nama Peminjam</label>
                    <!-- SEKARANG BISA DIKETIK MANUAL OLEH SISWA -->
                    <input type="text" name="nama_peminjam" class="form-control" required placeholder="Masukkan nama lengkap kamu">
                </div>

                <div class="form-group">
                    <label>Pilih Alat Praktikum</label>
                    <select name="id_alat" class="form-control" required>
                        <option value="">-- Pilih Alat yang Tersedia --</option>
                        <?php
                        // Ambil daftar alat dari database
                        $query_alat = mysqli_query($koneksi, "SELECT * FROM alat");
                        while ($alat = mysqli_fetch_assoc($query_alat)) {
                            echo "<option value='{$alat['id_alat']}'>{$alat['nama_alat']} (Stok: {$alat['jumlah_stok']})</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Jumlah Pinjam</label>
                    <input type="number" name="jumlah_pinjam" class="form-control" min="1" required placeholder="Masukkan jumlah yang dipinjam">
                </div>

                <div class="form-group">
                    <label>Tanggal Pinjam</label>
                    <input type="date" name="tgl_pinjam" class="form-control" required value="<?= date('Y-m-d'); ?>">
                </div>

                <button type="submit" name="pinjam" class="btn-submit">
                    <i class="fa-solid fa-paper-plane"></i> Ajukan Peminjaman
                </button>
            </form>
        </div>
    </div>

</body>
</html>