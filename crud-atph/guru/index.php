<?php include '../koneksi.php'; $query = mysqli_query($koneksi, "SELECT * FROM guru ORDER BY Nip DESC"); ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Guru ATPH</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="wrapper">
  <div class="container">
    <h1 class="header-title">DATA GURU ATPH</h1>
    <p class="subtitle">AGRIBISNIS TANAMAN PANGAN & HORTIKULTURA</p>
    <a href="tambah.php" class="btn-tambah">+ Tambah Guru</a>

    <div class="card-grid">
      <?php if ($query && mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
          $foto = (!empty($row['Foto']) && file_exists("uploads/".$row['Foto'])) ? "uploads/".$row['Foto'] : "https://via.placeholder.com/250x300?text=No+Photo";
      ?>
          <div class="card">
            <div class="card-img-wrapper"><img src="<?= $foto; ?>" alt="<?= $row['Nama']; ?>"></div>
            <div class="card-info">
              <div class="nip">NIP: <?= $row['Nip']; ?></div>
              <h3><?= $row['Nama']; ?></h3>
              <span class="badge"><?= $row['Mapel_Utama']; ?></span>
              <span class="badge">Wali: <?= $row['Wali_Kelas'] ?: '-'; ?></span>
              <div class="card-actions">
                <a href="edit.php?nip=<?= $row['Nip']; ?>" class="btn-action btn-edit">Edit</a>
                <a href="hapus.php?nip=<?= $row['Nip']; ?>" class="btn-action btn-hapus" onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
              </div>
            </div>
          </div>
      <?php } } else { ?>
        <div class="empty-state">Belum ada data guru. Silakan tambah data dulu.</div>
      <?php } ?>
    </div>
  </div>
</div>
</body>
</html>