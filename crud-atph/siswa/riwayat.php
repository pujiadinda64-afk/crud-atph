<?php
session_start();
include '../config/koneksi.php';

// Pastikan sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat & Status Peminjaman - ATPH</title>
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

        .container { padding: 40px; max-width: 1000px; margin: auto; width: 100%; }
        
        .card-table {
            background: #ffffff;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            border: 1px solid rgba(72,187,120,0.15);
        }
        .card-table h2 { color: #2d3748; font-size: 20px; font-weight: 800; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .card-table h2 i { color: #48bb78; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 14px 16px; text-align: left; font-size: 14px; border-bottom: 1px solid #edf2f7; }
        th { background-color: #f7fafc; color: #1b4332; font-weight: 700; }
        
        .badge-pending { background-color: #feebc8; color: #c05621; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 700; }
        .badge-disetujui { background-color: #c6f6d5; color: #22543d; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 700; }
        .badge-ditolak { background-color: #fed7d7; color: #742a2a; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 700; }
        .badge-selesai { background-color: #e2e8f0; color: #4a5568; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 700; }
    </style>
</head>
<body>

    <div class="header">
        <h1><i class="fa-solid fa-clock-rotate-left"></i> Status Peminjaman Alat</h1>
        <a href="index.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard</a>
    </div>

    <div class="container">
        <div class="card-table">
            <h2><i class="fa-solid fa-list-check"></i> Daftar Pengajuan Peminjaman</h2>
            
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Peminjam</th>
                        <th>Nama Alat</th>
                        <th>Jumlah</th>
                        <th>Tanggal Pinjam</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    // Menggabungkan tabel peminjaman dan tabel alat agar nama alatnya muncul
                    $query = mysqli_query($koneksi, "SELECT peminjaman.*, alat.nama_alat FROM peminjaman JOIN alat ON peminjaman.id_alat = alat.id_alat ORDER BY id_pinjam DESC");
                    
                    if (mysqli_num_rows($query) > 0) {
                        while ($row = mysqli_fetch_assoc($query)) {
                            // Tentukan warna badge berdasarkan status
                            $status = $row['status'];
                            $badge_class = 'badge-pending';
                            if ($status == 'Disetujui') $badge_class = 'badge-disetujui';
                            else if ($status == 'Ditolak') $badge_class = 'badge-ditolak';
                            else if ($status == 'Selesai' || $status == 'Dikembalikan') $badge_class = 'badge-selesai';

                            echo "<tr>
                                    <td>{$no}</td>
                                    <td>{$row['nama_peminjam']}</td>
                                    <td>{$row['nama_alat']}</td>
                                    <td>{$row['jumlah_pinjam']}</td>
                                    <td>{$row['tgl_pinjam']}</td>
                                    <td><span class='{$badge_class}'>{$status}</span></td>
                                  </tr>";
                            $no++;
                        }
                    } else {
                        echo "<tr><td colspan='6' style='text-align: center; color: #a0aec0;'>Belum ada riwayat peminjaman.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>