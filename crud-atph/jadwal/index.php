<?php
session_start();
include '../config/koneksi.php';

$result = mysqli_query($koneksi, 'SELECT * FROM jadwal ORDER BY tanggal DESC, jam_mulai DESC');
$pesan = $_GET['pesan'] ?? '';
$messages = [
 'hapus_sukses' => 'Jadwal berhasil dihapus.',
 'hapus_gagal' => 'Jadwal gagal dihapus.',
 'tambah_sukses' => 'Jadwal berhasil ditambahkan.',
 'edit_sukses' => 'Jadwal berhasil diperbarui.',
];
?>
<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0"><title>Jadwal Lapang - ATPH</title>
<style>
body{font-family:Arial,sans-serif;background:#f0f7f4;margin:0;padding:30px;color:#1f2937}.container{max-width:1100px;margin:auto;background:#fff;padding:28px;border-radius:18px;box-shadow:0 8px 30px rgba(0,0,0,.08)}
.header{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap}.btn{display:inline-block;padding:10px 15px;border-radius:9px;text-decoration:none;font-weight:700}.add{background:#2e7d32;color:#fff}.back{background:#e2e8f0;color:#334155}
table{width:100%;border-collapse:collapse;margin-top:22px}th,td{padding:12px;border-bottom:1px solid #e2e8f0;text-align:left}th{background:#f8fafc}.actions a{margin-right:8px;text-decoration:none;font-weight:700}.edit{color:#d97706}.delete{color:#dc2626}.empty{text-align:center;color:#64748b;padding:25px}
.alert{padding:12px;background:#e8f5e9;color:#1b5e20;border-radius:9px;margin:15px 0}
@media(max-width:800px){table{display:block;overflow:auto;white-space:nowrap}}
</style></head>
<body><div class="container">
<a href="../index.php" class="btn back">← Dashboard</a>
<div class="header"><div><h2>📅 Jadwal Lapang</h2><p>Kelola agenda penggunaan lahan praktik.</p></div><a href="tambah.php" class="btn add">+ Tambah Jadwal</a></div>
<?php if(isset($messages[$pesan])): ?><div class="alert"><?= htmlspecialchars($messages[$pesan]) ?></div><?php endif; ?>
<table><thead><tr><th>Kegiatan</th><th>Lokasi</th><th>Tanggal</th><th>Jam</th><th>Pembimbing</th><th>Aksi</th></tr></thead><tbody>
<?php if(mysqli_num_rows($result)): while($d=mysqli_fetch_assoc($result)): ?>
<tr><td><?= htmlspecialchars($d['kegiatan']) ?></td><td><?= htmlspecialchars($d['lokasi']) ?></td><td><?= htmlspecialchars($d['tanggal']) ?></td><td><?= htmlspecialchars(substr($d['jam_mulai'],0,5)) ?> - <?= htmlspecialchars(substr($d['jam_selesai'],0,5)) ?></td><td><?= htmlspecialchars($d['pembimbing']) ?></td>
<td class="actions"><a class="edit" href="edit.php?id=<?= (int)$d['id_jadwal'] ?>">Edit</a><a class="delete" href="hapus.php?id=<?= (int)$d['id_jadwal'] ?>" onclick="return confirm('Yakin ingin menghapus jadwal ini?')">Hapus</a></td></tr>
<?php endwhile; else: ?><tr><td colspan="6" class="empty">Belum ada jadwal.</td></tr><?php endif; ?>
</tbody></table></div></body></html>
