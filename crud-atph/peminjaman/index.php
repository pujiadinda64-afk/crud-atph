<?php
session_start();

// Jika admin mengklik aksi Selesai / Dikembalikan
if (isset($_GET['aksi']) && $_GET['aksi'] == 'selesai') {
    $id_peminjaman = $_GET['id'];

    // 1. Ambil data peminjaman (id_alat dan jumlah_pinjam) berdasarkan id_peminjaman
    $q_pem = mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE id_peminjaman = '$id_peminjaman'");
    $data_pem = mysqli_fetch_assoc($q_pem);
    
    $id_alat = $data_pem['id_alat'];
    $jumlah = $data_pem['jumlah_pinjam'];
    $status_sekarang = $data_pem['status'];
    

    // Pastikan stok hanya dikembalikan jika status sebelumnya benar-benar 'Disetujui' 
    // (agar stok tidak bertambah 2x jika tombol diklik berkali-kali)
    if ($status_sekarang == 'Disetujui') {
        // 2. Kembalikan stok alat ke tabel alat (Stok lama + jumlah yang dipinjam)
        mysqli_query($koneksi, "UPDATE alat SET jumlah_stok = jumlah_stok + $jumlah WHERE id_alat = '$id_alat'");
    }

    // 3. Update status peminjaman menjadi 'Selesai'
    mysqli_query($koneksi, "UPDATE peminjaman SET status = 'Selesai' WHERE id_peminjaman = '$id_peminjaman'");

if ($row['status'] == 'Disetujui') {
    echo "<a href='index.php?aksi=selesai&id={$row['id_peminjaman']}' style='background:#3182ce; color:white; padding:6px 12px; border-radius:6px; text-decoration:none; font-size:12px;'>Selesai / Kembali</a>";
}

    echo "<script>alert('Alat berhasil dikembalikan dan stok telah diperbarui!'); window.location='index.php';</script>";
}

// Proteksi Halaman khusus Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

include '../config/koneksi.php';

// Proses Aksi Ubah Status (Setujui / Tolak)
if (isset($_GET['aksi']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $aksi = $_GET['aksi'];
    
    if ($aksi == 'setujui') {
        mysqli_query($koneksi, "UPDATE peminjaman SET status='Disetujui' WHERE id_pinjam='$id'");
    } elseif ($aksi == 'tolak') {
        mysqli_query($koneksi, "UPDATE peminjaman SET status='Ditolak' WHERE id_pinjam='$id'");
    }
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Peminjaman Alat - ATPH</title>
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

        .container { padding: 40px; max-width: 1100px; margin: auto; width: 100%; }
        
        .card-table {
            background: #ffffff;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            border: 1px solid rgba(72,187,120,0.15);
        }
        .card-table h2 { color: #2d3748; font-size: 20px; font-weight: 800; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .card-table h2 i { color: #48bb78; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; text-align: left; }
        th { background-color: #f0fdf4; color: #1b4332; padding: 14px 16px; font-size: 13px; font-weight: 700; border-bottom: 2px solid #e2e8f0; }
        td { padding: 14px 16px; font-size: 14px; color: #4a5568; border-bottom: 1px solid #edf2f7; }
        tr:hover { background-color: #f8fafc; }

        .badge {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            display: inline-block;
        }
        .badge-pending { background-color: #feebc8; color: #c05621; }
        .badge-setuju { background-color: #c6f6d5; color: #22543d; }
        .badge-tolak { background-color: #fed7d7; color: #822727; }

        .btn-action {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            margin-right: 5px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: all 0.2s;
        }
        .btn-setuju { background-color: #48bb78; color: white; }
        .btn-setuju:hover { background-color: #38a169; }
        .btn-tolak { background-color: #e53e3e; color: white; }
        .btn-tolak:hover { background-color: #c53030; }
    </style>
</head>
<body>

    <div class="header">
        <h1><i class="fa-solid fa-handshake-angle"></i> Konfirmasi Peminjaman Alat</h1>
        <a href="../admin/index.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Kembali ke Halaman admin</a>
    </div>

    <div class="container">
        <div class="card-table">
            <h2><i class="fa-solid fa-list-check"></i> Daftar Permintaan Peminjaman Alat Praktikum</h2>
            
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Peminjam</th>
                        <th>Nama Alat</th>
                        <th>Jumlah</th>
                        <th>Tanggal Pinjam</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    // Menggabungkan tabel peminjaman dengan tabel alat menggunakan kolom id_alat
                    $sql = "SELECT peminjaman.*, alat.nama_alat 
                            FROM peminjaman 
                            LEFT JOIN alat ON peminjaman.id_alat = alat.id_alat 
                            ORDER BY peminjaman.id_pinjam DESC";
                    $query = mysqli_query($koneksi, $sql);

                    if ($query && mysqli_num_rows($query) > 0) {
                        while ($row = mysqli_fetch_assoc($query)) {
                            $status = isset($row['status']) ? $row['status'] : 'Pending';
                            $badge_class = 'badge-pending';
                            if ($status == 'Disetujui') $badge_class = 'badge-setuju';
                            if ($status == 'Ditolak') $badge_class = 'badge-tolak';
                    ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><strong><?= htmlspecialchars($row['nama_peminjam']); ?></strong></td>
                        <td><?= htmlspecialchars($row['nama_alat'] ?? 'Alat Tidak Dikenal'); ?></td>
                        <td><?= htmlspecialchars($row['jumlah_pinjam']); ?></td>
                        <td><?= htmlspecialchars($row['tgl_pinjam']); ?></td>
                        <td><span class="badge <?= $badge_class; ?>"><?= $status; ?></span></td>
                        <td>
                            <a href="index.php?aksi=setujui&id=<?= $row['id_pinjam']; ?>" class="btn-action btn-setuju" onclick="return confirm('Setujui peminjaman alat ini?')"><i class="fa-solid fa-check"></i> Setujui</a>
                            <a href="index.php?aksi=tolak&id=<?= $row['id_pinjam']; ?>" class="btn-action btn-tolak" onclick="return confirm('Tolak peminjaman alat ini?')"><i class="fa-solid fa-xmark"></i> Tolak</a>
                        </td>
                    </tr>
                    <?php 
                        }
                    } else {
                        echo "<tr><td colspan='7' style='text-align: center; color: #a0aec0; padding: 30px;'>Belum ada data peminjaman alat.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>