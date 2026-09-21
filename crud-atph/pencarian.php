<?php
// 1. Panggil file koneksi database
include '../koneksi.php';

// 2. Ambil kata kunci dan kategori pencarian dari form
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : 'guru';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pencarian Data Proyek ATPH</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <h2>Pencarian Data Sistem ATPH</h2>
        
        <!-- Form Pencarian dengan Filter Kategori -->
        <form action="" method="GET">
            <input type="text" name="keyword" value="<?= htmlspecialchars($keyword); ?>" placeholder="Masukkan kata kunci..." autocomplete="off" required>
            
            <select name="kategori">
                <option value="guru" <?= ($kategori == 'guru') ? 'selected' : ''; ?>>Guru</option>
                <option value="kegiatan" <?= ($kategori == 'kegiatan') ? 'selected' : ''; ?>>Kegiatan</option>
                <option value="alat" <?= ($kategori == 'alat') ? 'selected' : ''; ?>>Alat</option>
            </select>

            <button type="submit">Cari</button>
        </form>

        <br>
        <a href="index.php">&laquo; Kembali ke Beranda</a>
        <br><br>

        <hr>

        <?php if ($keyword != ''): ?>
            <h3>Hasil Pencarian untuk: "<?= htmlspecialchars($keyword); ?>" (Kategori: <?= ucfirst($kategori); ?>)</h3>

            <!-- MENAMPILKAN HASIL PENCARIAN BERDASARKAN KATEGORI -->
            <table border="1" cellpadding="10" cellspacing="0">
                <?php if ($kategori == 'guru'): ?>
                    <!-- TABEL GURU -->
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Mapel Utama</th>
                        <th>Wali Kelas</th>
                    </tr>
                    <?php
                    $query = mysqli_query($koneksi, "SELECT * FROM guru WHERE Nama LIKE '%$keyword%' OR Nip LIKE '%$keyword%'");
                    if (mysqli_num_rows($query) > 0) {
                        $no = 1;
                        while ($data = mysqli_fetch_assoc($query)) {
                    ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><img src="uploads/<?= $data['Foto']; ?>" width="50" alt="Foto"></td>
                            <td><?= $data['Nip']; ?></td>
                            <td><?= $data['Nama']; ?></td>
                            <td><?= $data['Mapel_Utama']; ?></td>
                            <td><?= $data['Wali_Kelas']; ?></td>
                        </tr>
                    <?php 
                        }
                    } else {
                        echo '<tr><td colspan="6" align="center">Data guru tidak ditemukan.</td></tr>';
                    }
                    ?>

                <?php elseif ($kategori == 'kegiatan'): ?>
                    <!-- TABEL KEGIATAN -->
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>Nama Kegiatan</th>
                        <th>Deskripsi</th>
                        <th>Pembimbing</th>
                        <th>Tanggal</th>
                    </tr>
                    <?php
                    $query = mysqli_query($koneksi, "SELECT * FROM kegiatan WHERE nama_kegiatan LIKE '%$keyword%' OR deskripsi LIKE '%$keyword%' OR pembimbing LIKE '%$keyword%'");
                    if (mysqli_num_rows($query) > 0) {
                        $no = 1;
                        while ($data = mysqli_fetch_assoc($query)) {
                    ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><img src="uploads/<?= $data['foto']; ?>" width="50" alt="Foto"></td>
                            <td><?= $data['nama_kegiatan']; ?></td>
                            <td><?= $data['deskripsi']; ?></td>
                            <td><?= $data['pembimbing']; ?></td>
                            <td><?= $data['tanggal']; ?></td>
                        </tr>
                    <?php 
                        }
                    } else {
                        echo '<tr><td colspan="6" align="center">Data kegiatan tidak ditemukan.</td></tr>';
                    }
                    ?>

                <?php elseif ($kategori == 'alat'): ?>
                    <!-- TABEL ALAT -->
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>Nama Alat</th>
                        <th>Jumlah Stok</th>
                        <th>Kondisi</th>
                    </tr>
                    <?php
                    $query = mysqli_query($koneksi, "SELECT * FROM alat WHERE nama_alat LIKE '%$keyword%' OR kondisi LIKE '%$keyword%'");
                    if (mysqli_num_rows($query) > 0) {
                        $no = 1;
                        while ($data = mysqli_fetch_assoc($query)) {
                    ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><img src="uploads/<?= $data['foto']; ?>" width="50" alt="Foto"></td>
                            <td><?= $data['nama_alat']; ?></td>
                            <td><?= $data['jumlah_stok']; ?></td>
                            <td><?= $data['kondisi']; ?></td>
                        </tr>
                    <?php 
                        }
                    } else {
                        echo '<tr><td colspan="5" align="center">Data alat tidak ditemukan.</td></tr>';
                    }
                    ?>
                <?php endif; ?>
            </table>

        <?php endif; ?>
    </div>

</body>
</html>