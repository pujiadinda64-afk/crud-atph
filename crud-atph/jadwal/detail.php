<?php

include '../../koneksi.php';
// Tangkap kelas & rombel dari URL, contoh: detail.php?kelas=11&rombel=3
$kelas  = isset($_GET['kelas'])  ? intval($_GET['kelas'])  : 10;
$rombel = isset($_GET['rombel']) ? intval($_GET['rombel']) : 1;

// Jumlah rombel ATPH per tingkat (sama dengan index.php)
$rombel_maks = [10 => 4, 11 => 5, 12 => 5];

// Jika kelas/rombel tidak ada (mis. Kelas 10 ATPH 5), kembali ke halaman pilih kelas
if (!isset($rombel_maks[$kelas]) || $rombel < 1 || $rombel > $rombel_maks[$kelas]) {
    header('Location: index.php');
    exit;
}

// Ambil jadwal milik kelas & rombel ini dari database
$stmt = mysqli_prepare($koneksi, "SELECT * FROM jadwal WHERE kelas = ? AND rombel = ? ORDER BY tanggal ASC");
if (!$stmt) {
    die("Query gagal: " . mysqli_error($koneksi));
}
mysqli_stmt_bind_param($stmt, "ii", $kelas, $rombel);
mysqli_stmt_execute($stmt);
$jadwal = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Lapang Kelas <?= $kelas; ?> ATPH <?= $rombel; ?> - Sistem ATPH</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-green: #2e7d32;
            --light-green: #4caf50;
            --accent-green: #e8f5e9;
            --dark-text: #2c3e50;
            --bg-gradient: linear-gradient(135deg, #f1f8f5 0%, #e2eee6 100%);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }

        body { background: var(--bg-gradient); color: var(--dark-text); min-height: 100vh; padding: 40px 20px; }

        .container { max-width: 1100px; margin: 0 auto; }

        .card-box {
            background: #ffffff;
            padding: 35px;
            border-radius: 24px;
            box-shadow: 0 15px 35px rgba(46, 125, 50, 0.1);
            border: 1px solid rgba(76, 175, 80, 0.2);
        }

        .header-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        h2 { color: var(--primary-green); font-size: 24px; display: flex; align-items: center; gap: 10px; }

        .btn {
            background: var(--primary-green);
            color: white;
            padding: 10px 20px;
            border-radius: 12px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
            transition: 0.3s;
        }

        .btn:hover { background: #1b5e20; transform: translateY(-2px); }
        .btn-back { background: #e2e8f0; color: #475569; box-shadow: none; }
        .btn-back:hover { background: #cbd5e1; color: #1e293b; }

        /* Tabel Kustom */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
        }

        th, td {
            padding: 14px 16px;
            text-align: left;
            font-size: 14px;
            border-bottom: 1px solid #f1f5f9;
        }

        th {
            background-color: var(--accent-green);
            color: var(--primary-green);
            font-weight: 600;
        }

        tr:hover { background-color: #f8fafc; }

        .action-links { display: flex; gap: 12px; align-items: center; }
        .action-links form { margin: 0; }
        .action-links a, .action-links button {
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
            background: none;
            border: none;
            cursor: pointer;
        }
        .edit-link { color: #d97706; }
        .delete-link { color: #dc2626; }
        .kosong { text-align: center; color: #64748b; }
    </style>
</head>
<body>

<div class="container">
    <div class="card-box">
        <div class="header-flex">
            <div>
                <a href="index.php" class="btn btn-back" style="margin-bottom: 15px;"><i class="fa-solid fa-arrow-left"></i> Kembali ke Pilih Kelas</a>
                <h2><i class="fa-solid fa-calendar-check"></i> Jadwal Lapang - Kelas <?= $kelas; ?> ATPH <?= $rombel; ?></h2>
                <p style="color: #64748b; font-size: 14px; margin-top: 4px;">Daftar agenda penggunaan lahan praktik untuk Kelas <?= $kelas; ?> ATPH <?= $rombel; ?>.</p>
            </div>
            <a href="tambah.php?kelas=<?= $kelas; ?>&rombel=<?= $rombel; ?>" class="btn"><i class="fa-solid fa-plus"></i> Tambah Jadwal</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kegiatan</th>
                    <th>Lokasi Lahan</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Pembimbing</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Praktik Hidroponik Sawi</td>
                    <td>Greenhouse 1</td>
                    <td>2024-06-15</td>
                    <td>08.00 - 10.00</td>
                    <td>Bpk. Ahmad S.Pd.</td>
                    <td class="action-links">   
                </tr>
                <?php $no = 2; while ($row = mysqli_fetch_assoc($jadwal)): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($row['kegiatan']); ?></td>
                    <td><?= htmlspecialchars($row['lokasi']); ?></td>
                    <td><?= htmlspecialchars($row['tanggal']); ?></td>
                    <td><?= htmlspecialchars($row['jam']); ?></td>
                    <td><?= htmlspecialchars($row['pembimbing']); ?></td>
                    <td class="action-links">
                        <a href="edit.php?id=<?= (int) $row['id']; ?>" class="edit-link">Edit</a>
                        <form method="POST" action="hapus.php" onsubmit="return confirm('Yakin ingin menghapus?')">
                            <input type="hidden" name="id" value="<?= (int) $row['id']; ?>">
                            <input type="hidden" name="kelas" value="<?= $kelas; ?>">
                            <input type="hidden" name="rombel" value="<?= $rombel; ?>">
                            <button type="submit" class="delete-link">Hapus</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
