<?php
session_start();
include '../config/koneksi.php'; // Sesuaikan jalur koneksi database kamu

// Pastikan yang login benar-benar admin (opsional jika sudah ada sistem session)
// if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') { header("Location: ../login.php"); exit; }

// Query untuk mengambil data jurnal gabungan dengan data siswa dari tabel users
$query_jurnal = mysqli_query($koneksi, "
    SELECT jurnal_praktikum.*, users.nama_lengkap, users.kelas 
    FROM jurnal_praktikum 
    JOIN users ON jurnal_praktikum.id_user = users.id_user 
    ORDER BY jurnal_praktikum.id_user DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Jurnal Harian - Admin ATPH</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f3f4f6; margin: 0; }
        .container { padding: 30px; max-width: 1100px; margin: auto; }
        .card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        h2 { color: #1f2937; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e5e7eb; font-size: 14px; vertical-align: middle; }
        th { background: #f9fafb; color: #374151; font-weight: 600; }
        .btn-back { display: inline-block; margin-bottom: 15px; color: #10b981; text-decoration: none; font-weight: 500; }
        .btn-back:hover { text-decoration: underline; }
        .img-jurnal { width: 60px; height: 60px; object-fit: cover; border-radius: 6px; border: 1px solid #e5e7eb; }
        .user-cell { display: flex; align-items: center; gap: 10px; }
        .user-avatar { width: 32px; height: 32px; border-radius: 50%; object-fit: cover; }
    </style>
</head>
<body>

    <div class="container">
        <a href="../admin/index.php" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali ke halaman admin</a>
        
        <div class="card">
            <h2>Daftar Jurnal Harian Siswa</h2>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Kegiatan</th>
                        <th>Keterangan</th>
                        <th>Foto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if ($query_jurnal && mysqli_num_rows($query_jurnal) > 0) {
                        while ($row = mysqli_fetch_assoc($query_jurnal)) {
                            // Avatar inisial otomatis untuk nama siswa
                            $avatar_url = "https://ui-avatars.com/api/?name=" . urlencode($row['nama_lengkap'] ?? 'Siswa') . "&background=10b981&color=fff&size=128";
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($row['tanggal'] ?? '-'); ?></td>
                            <td>
                                <div class="user-cell">
                                    <img src="<?php echo $avatar_url; ?>" alt="Avatar" class="user-avatar">
                                    <span><?php echo htmlspecialchars($row['nama_lengkap'] ?? 'Siswa'); ?></span>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($row['kelas'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($row['nama_kegiatan'] ?? $row['kegiatan'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($row['keterangan'] ?? '-'); ?></td>
                            <td>
                                <?php if (!empty($row['foto']) && file_exists("../siswa/uploads/" . $row['foto'])): ?>
                                    <a href="../siswa/uploads/<?php echo $row['foto']; ?>" target="_blank">
                                        <img src="../siswa/uploads/<?php echo $row['foto']; ?>" alt="Bukti Foto" class="img-jurnal">
                                    </a>
                                <?php else: ?>
                                    <span style="color: #9ca3af; font-size: 12px; font-style: italic;">Tidak ada foto</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php 
                        }
                    } else {
                    ?>
                        <tr>
                            <td colspan="7" style="text-align: center; color: #6b7280; padding: 20px;">Belum ada jurnal harian yang dikirimkan siswa.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>