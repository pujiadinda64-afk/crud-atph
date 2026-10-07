<?php
session_start();
// Sesuaikan path file koneksi database berdasarkan letaknya
include '../config/koneksi.php'; 

// Cek apakah user sudah login dan rolenya siswa
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] == '') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$pesan_sukses = "";
$pesan_error = "";

// Jika form dikirimkan
if (isset($_POST['kirim_jurnal'])) {
    $nama_kegiatan = mysqli_real_escape_string($koneksi, $_POST['nama_kegiatan']);
    $tanggal       = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    $keterangan    = mysqli_real_escape_string($koneksi, $_POST['keterangan']);
    
    // Upload foto dokumentasi kegiatan (opsional)
    $nama_file = $_FILES['foto']['name'];
    $ukuran_file = $_FILES['foto']['size'];
    $tmp_file = $_FILES['foto']['tmp_name'];
    
    if ($nama_file != "") {
        $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg');
        $x = explode('.', $nama_file);
        $ekstensi = strtolower(end($x));
        
        if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
            $nama_file_baru = uniqid() . '.' . $ekstensi;
            // Pastikan folder 'uploads/' sudah ada di dalam proyekmu
            move_uploaded_file($tmp_file, 'uploads/' . $nama_file_baru);
            
            // Simpan ke database jurnal
            $query_simpan = mysqli_query($koneksi, "INSERT INTO jurnal_praktikum (id_user, nama_kegiatan, tanggal, keterangan, foto) VALUES ('$user_id', '$nama_kegiatan', '$tanggal', '$keterangan', '$nama_file_baru')");
            
            if ($query_simpan) {
                // Tambahkan poin ke user (+50 poin untuk setiap jurnal yang dikirim)
                mysqli_query($koneksi, "UPDATE users SET poin = COALESCE(poin, 0) + 50 WHERE id_user = '$user_id'");
                
                $pesan_sukses = "Jurnal berhasil dikirim! Kamu mendapatkan +50 Poin Kinerja 🌱.";
            } else {
                $pesan_error = "Gagal menyimpan jurnal ke database.";
            }
        } else {
            $pesan_error = "Ekstensi file gambar harus berformat PNG, JPG, atau JPEG.";
        }
    } else {
        // Jika tanpa foto
        $query_simpan = mysqli_query($koneksi, "INSERT INTO jurnal_praktikum (id_user, nama_kegiatan, tanggal, keterangan) VALUES ('$user_id', '$nama_kegiatan', '$tanggal', '$keterangan')");
        
        if ($query_simpan) {
            mysqli_query($koneksi, "UPDATE users SET poin = COALESCE(poin, 0) + 50 WHERE id_user = '$user_id'");
            $pesan_sukses = "Jurnal berhasil dikirim! Kamu mendapatkan +50 Poin Kinerja 🌱.";
        } else {
            $pesan_error = "Gagal menyimpan jurnal ke database.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jurnal Praktikum - Portal Siswa ATPH</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #10b981;
            --primary-dark: #059669;
            --primary-light: #ecfdf5;
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --sidebar-width: 260px;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { background-color: var(--bg-color); color: var(--text-main); display: flex; min-height: 100vh; }
        
        /* Sidebar Sederhana */
        aside { width: var(--sidebar-width); background: var(--card-bg); border-right: 1px solid var(--border-color); display: flex; flex-direction: column; position: fixed; height: 100vh; z-index: 10; }
        .sidebar-brand { padding: 24px; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid var(--border-color); }
        .sidebar-brand .logo-icon { width: 40px; height: 40px; background: var(--primary-light); color: var(--primary); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
        .sidebar-brand h1 { font-size: 16px; font-weight: 700; }
        .sidebar-brand p { font-size: 12px; color: var(--text-muted); }
        .sidebar-menu { padding: 20px 16px; display: flex; flex-direction: column; gap: 6px; }
        .nav-item { display: flex; align-items: center; gap: 12px; padding: 12px 16px; color: var(--text-muted); text-decoration: none; font-weight: 500; font-size: 14px; border-radius: 10px; transition: 0.2s; }
        .nav-item:hover, .nav-item.active { background: var(--primary-light); color: var(--primary-dark); }

        /* Konten Utama */
        main { margin-left: var(--sidebar-width); flex-grow: 1; padding: 30px; max-width: 900px; }
        .form-card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 16px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 600; font-size: 14px; margin-bottom: 8px; color: var(--text-main); }
        .form-group input, .form-group textarea { width: 100%; padding: 12px 16px; border: 1px solid var(--border-color); border-radius: 10px; font-size: 14px; outline: none; transition: border-color 0.2s; }
        .form-group input:focus, .form-group textarea:focus { border-color: var(--primary); }
        .btn-submit { background: var(--primary); color: white; border: none; padding: 12px 24px; border-radius: 10px; font-weight: 600; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: background 0.2s; }
        .btn-submit:hover { background: var(--primary-dark); }
        .alert-success { background: var(--primary-light); color: var(--primary-dark); padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; font-weight: 500; }
        .alert-error { background: #fee2e2; color: #dc2626; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; font-weight: 500; }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <aside>
        <div class="sidebar-brand">
            <div class="logo-icon"><i class="fa-solid fa-seedling"></i></div>
            <div>
                <h1>Portal ATPH</h1>
                <p>SMK Pertanian Unggul</p>
            </div>
        </div>
        <div class="sidebar-menu">
            <a href="index.php" class="nav-item"><i class="fa-solid fa-house"></i> Beranda</a>
            <a href="../peminjaman/tambah.php" class="nav-item"><i class="fa-solid fa-pen-to-square"></i> Pinjam Alat</a>
            <a href="../jadwal/index.php" class="nav-item"><i class="fa-solid fa-calendar-days"></i> Jadwal Lapang</a>
            <a href="../alat/index.php" class="nav-item"><i class="fa-solid fa-tractor"></i> Cek Stok Alat</a>
            <a href="jurnal.php" class="nav-item active"><i class="fa-solid fa-book"></i> Jurnal Praktikum</a>
            <a href="riwayat.php" class="nav-item"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Pinjam</a>
        </div>
    </aside>

    <!-- KONTEN -->
    <main>
        <div style="margin-bottom: 25px;">
            <h2 style="font-size: 24px; margin-bottom: 5px;">Kirim Jurnal Praktikum Harian</h2>
            <p style="color: var(--text-muted); font-size: 14px;">Catat laporan pengamatan tanaman dan kegiatan lahanmu di sini untuk mendapatkan **+50 Poin Kinerja**.</p>
        </div>

        <?php if (!empty($pesan_sukses)) { echo "<div class='alert-success'><i class='fa-solid fa-circle-check'></i> $pesan_sukses</div>"; } ?>
        <?php if (!empty($pesan_error)) { echo "<div class='alert-error'><i class='fa-solid fa-circle-exclamation'></i> $pesan_error</div>"; } ?>

        <div class="form-card">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label><i class="fa-solid fa-seedling" style="color: var(--primary);"></i> Nama Kegiatan / Tanaman</label>
                    <input type="text" name="nama_kegiatan" placeholder="Contoh: Pemupukan Cabai Rawit / Penyiraman Green House" required>
                </div>

                <div class="form-group">
                    <label><i class="fa-solid fa-calendar" style="color: var(--primary);"></i> Tanggal Prakttek</label>
                    <input type="date" name="tanggal" value="<?= date('Y-m-d'); ?>" required>
                </div>

                <div class="form-group">
                    <label><i class="fa-solid fa-pen" style="color: var(--primary);"></i> Keterangan / Laporan Pengamatan</label>
                    <textarea name="keterangan" rows="5" placeholder="Tuliskan hasil pengamatan pertumbuhan tanaman, kendala, atau tindakan perawatan yang dilakukan..." required></textarea>
                </div>

                <div class="form-group">
                    <label><i class="fa-solid fa-camera" style="color: var(--primary);"></i> Foto Dokumentasi (Opsional)</label>
                    <input type="file" name="foto" accept="image/png, image/jpeg, image/jpg">
                    <small style="color: var(--text-muted); font-size: 12px; margin-top: 5px; display: block;">Format yang diizinkan: JPG, JPEG, PNG.</small>
                </div>

                <button type="submit" name="kirim_jurnal" class="btn-submit">
                    <i class="fa-solid fa-paper-plane"></i> Kirim Laporan & Klaim Poin
                </button>
            </form>
        </div>

        <!-- Bagian Riwayat Jurnal yang Sudah Dikirim -->
<div style="margin-top: 40px; background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 16px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
    <h3 style="font-size: 18px; margin-bottom: 15px; color: var(--text-main);">
        <i class="fa-solid fa-list-check" style="color: var(--primary);"></i> Riwayat Jurnal Praktikum Kamu
    </h3>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 14px; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid var(--border-color); color: var(--text-muted);">
                    <th style="padding: 10px;">Tanggal</th>
                    <th style="padding: 10px;">Kegiatan / Tanaman</th>
                    <th style="padding: 10px;">Keterangan</th>
                    <th style="padding: 10px;">Dokumentasi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Ambil data jurnal khusus untuk user yang sedang login
                $query_riwayat = mysqli_query($koneksi, "SELECT * FROM jurnal_praktikum WHERE id_user = '$user_id' ORDER BY id_jurnal DESC");
                
                if (mysqli_num_rows($query_riwayat) > 0) {
                    while ($row = mysqli_fetch_assoc($query_riwayat)) {
                        echo "<tr style='border-bottom: 1px solid var(--border-color);'>";
                        echo "<td style='padding: 12px 10px; white-space: nowrap;'>" . htmlspecialchars($row['tanggal']) . "</td>";
                        echo "<td style='padding: 12px 10px; font-weight: 600;'>" . htmlspecialchars($row['nama_kegiatan']) . "</td>";
                        echo "<td style='padding: 12px 10px; color: var(--text-muted);'>" . htmlspecialchars($row['keterangan']) . "</td>";
                        
                        // Cek apakah ada foto yang di-upload
                        echo "<td style='padding: 12px 10px;'>";
                        if (!empty($row['foto'])) {
                            echo "<a href='uploads/" . htmlspecialchars($row['foto']) . "' target='_blank' style='color: var(--primary); font-weight: 600; text-decoration: none;'><i class='fa-solid fa-image'></i> Lihat Foto</a>";
                        } else {
                            echo "<span style='color: var(--text-muted); font-size: 12px;'>Tanpa Foto</span>";
                        }
                        echo "</td>";
                        
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' style='padding: 20px; text-align: center; color: var(--text-muted);'>Belum ada jurnal praktikum yang dikirim.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
    </main>

</body>
</html>