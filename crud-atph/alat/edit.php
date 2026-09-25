<?php
declare(strict_types=1);

/** @var mysqli $koneksi */

session_start();
include '../config/koneksi.php';

if (($_SESSION['role'] ?? '') !== 'admin') {
    http_response_code(403);
    echo "<script>alert('Akses ditolak! Halaman ini khusus untuk Admin.'); window.location='index.php';</script>";
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    header('Location: index.php?pesan=tidak_ditemukan');
    exit;
}

$stmt = mysqli_prepare($koneksi, 'SELECT * FROM alat WHERE id_alat = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$alat = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$alat) {
    header('Location: index.php?pesan=tidak_ditemukan');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_alat'] ?? '');
    $stok = filter_input(INPUT_POST, 'jumlah_stok', FILTER_VALIDATE_INT);
    $kondisi = trim($_POST['kondisi'] ?? '');

    if ($nama === '' || $stok === false || $stok < 0 || $kondisi === '') {
        $error = 'Nama alat, stok, dan kondisi wajib diisi dengan benar.';
    } else {
        $fotoBaru = $alat['foto'] ?? null;
        $uploadedNew = false;

        if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
                $error = 'Upload foto gagal.';
            } else {
                $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg','jpeg','png','webp'];
                if (!in_array($ext, $allowed, true)) {
                    $error = 'Format foto harus JPG, JPEG, PNG, atau WEBP.';
                } elseif ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
                    $error = 'Ukuran foto maksimal 2MB.';
                } else {
                    $fotoBaru = 'alat_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                    if (!move_uploaded_file($_FILES['foto']['tmp_name'], __DIR__ . '../../uploads' . $fotoBaru)) {
                        $error = 'Foto baru gagal disimpan.';
                    } else {
                        $uploadedNew = true;
                    }
                }
            }
        }

        if (empty($error)) {
            $stmt = mysqli_prepare($koneksi, 'UPDATE alat SET nama_alat = ?, jumlah_stok = ?, kondisi = ?, foto = ? WHERE id_alat = ?');
            mysqli_stmt_bind_param($stmt, 'sissi', $nama, $stok, $kondisi, $fotoBaru, $id);
            $ok = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            if ($ok) {
                if ($uploadedNew && !empty($alat['foto'])) {
                    $old = __DIR__ . '../../uploads' . basename($alat['foto']);
                    if (is_file($old)) @unlink($old);
                }
                header('Location: index.php?pesan=edit_sukses');
                exit;
            }

            if ($uploadedNew) {
                $newPath = __DIR__ . '../../uploads' . basename($fotoBaru);
                if (is_file($newPath)) @unlink($newPath);
            }
            $error = 'Gagal menyimpan perubahan.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Alat</title>
<style>
body{font-family:Arial,sans-serif;background:#f0f7f4;margin:0;padding:30px;color:#1f2937}
.card{max-width:600px;margin:auto;background:#fff;padding:28px;border-radius:18px;box-shadow:0 8px 30px rgba(0,0,0,.08)}
label{display:block;font-weight:700;margin:14px 0 6px}
input,select{width:100%;box-sizing:border-box;padding:11px;border:1px solid #cbd5e1;border-radius:9px}
img{max-width:180px;max-height:140px;object-fit:cover;border-radius:10px;margin:8px 0}
button{margin-top:20px;padding:12px 18px;border:0;border-radius:9px;background:#2e7d32;color:#fff;font-weight:700;cursor:pointer}
a{display:inline-block;margin-left:8px;padding:11px 15px;background:#64748b;color:#fff;border-radius:9px;text-decoration:none}
.error{padding:12px;background:#fee2e2;color:#991b1b;border-radius:9px;margin-bottom:14px}
</style>
</head>
<body>
<div class="card">
<h2>✏️ Edit Data Alat</h2>
<?php if (!empty($error)): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="post" enctype="multipart/form-data">
    <label>Nama Alat</label>
        <input type="text" name="nama_alat" value="<?= htmlspecialchars($alat['nama_alat']) ?>" required>
    <label>Jumlah Stok</label>
        <input type="number" name="jumlah_stok" min="0" value="<?= (int)$alat['jumlah_stok'] ?>" required>
    <label>Kondisi</label>
        <input type="text" name="kondisi" value="<?= htmlspecialchars($alat['kondisi']) ?>" required>
    <label>Foto</label>
<?php if (!empty($alat['foto']) && is_file(__DIR__.'/uploads/'.$alat['foto'])): ?>
<img src="uploads/<?= htmlspecialchars($alat['foto']) ?>" alt="Foto alat">
<?php else: ?><p>Tidak ada foto.</p><?php endif; ?>
<input type="file" name="foto" accept=".jpg,.jpeg,.png,.webp">
<button type="submit">💾 Simpan Perubahan</button>
<a href="index.php">Batal</a>
</form>
</div>
</body>
</html>
