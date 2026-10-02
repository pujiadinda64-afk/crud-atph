<?php
// Memanggil koneksi database
include '../../koneksi.php'; 

$pesan_error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_kegiatan = trim($_POST['nama_kegiatan']);
    $deskripsi     = trim($_POST['deskripsi']);
    $pembimbing    = trim($_POST['pembimbing']);
    $tanggal       = $_POST['tanggal'];

    if (empty($nama_kegiatan) || empty($tanggal)) {
        $pesan_error = "Nama kegiatan dan tanggal wajib diisi!";
    } else {
        // 1. Upload Foto Utama (Cover)
        $nama_foto = "";
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $file_tmp   = $_FILES['foto']['tmp_name'];
            $file_name  = $_FILES['foto']['name'];
            $ekstensi   = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed    = ['jpg', 'jpeg', 'png', 'webp'];

            if (in_array($ekstensi, $allowed)) {
                $nama_foto = time() . '_' . uniqid() . '.' . $ekstensi;
                if (!is_dir('uploads')) {
                    mkdir('uploads', 0777, true);
                }
                move_uploaded_file($file_tmp, 'uploads/' . $nama_foto);
            }
        }

        if (empty($pesan_error)) {
            // SIMPAN KE TABEL KEGIATAN HANYA SEKALI
            $stmt = $koneksi->prepare("INSERT INTO kegiatan (nama_kegiatan, deskripsi, pembimbing, tanggal, foto) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $nama_kegiatan, $deskripsi, $pembimbing, $tanggal, $nama_foto);
            
            if ($stmt->execute()) {
                $kegiatan_id = $stmt->insert_id; // Ambil ID kegiatan yang baru saja dibuat
                $stmt->close();

                // 2. PROSES UPLOAD FOTO TAMBAHAN (MULTI)
                if (isset($_FILES['foto_tambahan']) && !empty($_FILES['foto_tambahan']['name'][0])) {
                    $jumlah_file = count($_FILES['foto_tambahan']['name']);

                    for ($i = 0; $i < $jumlah_file; $i++) {
                        if ($_FILES['foto_tambahan']['error'][$i] === UPLOAD_ERR_OK) {
                            $tmp_name  = $_FILES['foto_tambahan']['tmp_name'][$i];
                            $orig_name = $_FILES['foto_tambahan']['name'][$i];
                            $ext       = strtolower(pathinfo($orig_name, PATHINFO_EXTENSION));

                            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                                $nama_foto_tambah = time() . '_tambahan_' . $i . '_' . uniqid() . '.' . $ext;
                                move_uploaded_file($tmp_name, 'uploads/' . $nama_foto_tambah);

                                // Masukkan ke tabel foto_kegiatan (mengisi id_kegiatan, nama_foto, dan nama_file sesuai struktur database kamu)
                                $stmt_foto = $koneksi->prepare("INSERT INTO foto_kegiatan (id_kegiatan, nama_foto, nama_file) VALUES (?, ?, ?)");
                                $stmt_foto->bind_param("iss", $id_kegiatan, $nama_foto_tambah, $nama_foto_tambah);
                                $stmt_foto->execute();
                                $stmt_foto->close();
                            }
                        }
                    }
                }

                echo "<script>alert('Data kegiatan berhasil ditambahkan!'); window.location='index.php';</script>";
                exit();
            } else {
                $pesan_error = "Gagal menyimpan ke database: " . $koneksi->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kegiatan - Sistem ATPH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-hover: #1d4ed8;
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #cbd5e1;
            --radius: 12px;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { background-color: var(--bg-color); color: var(--text-main); padding: 40px 20px; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .container { width: 100%; max-width: 680px; background: var(--card-bg); padding: 40px; border-radius: var(--radius); box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05); border: 1px solid #e2e8f0; }
        .header-title { margin-bottom: 25px; border-bottom: 2px solid #f1f5f9; padding-bottom: 15px; }
        .header-title h2 { font-size: 24px; font-weight: 700; color: var(--text-main); }
        .header-title p { font-size: 14px; color: var(--text-muted); margin-top: 4px; }
        .alert { padding: 14px 16px; border-radius: 8px; font-size: 14px; margin-bottom: 20px; font-weight: 500; background-color: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-size: 14px; font-weight: 600; color: var(--text-main); margin-bottom: 8px; }
        input[type="text"], input[type="date"], textarea { width: 100%; padding: 12px 16px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; color: var(--text-main); background-color: #fff; }
        input[type="text"]:focus, input[type="date"]:focus, textarea:focus { outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15); }
        textarea { resize: vertical; min-height: 110px; }
        .file-upload-box { border: 2px dashed var(--border-color); padding: 20px; border-radius: 8px; text-align: center; background: #fafafa; }
        input[type="file"] { font-size: 13px; color: var(--text-muted); }
        .helper-text { font-size: 12px; color: var(--text-muted); margin-top: 6px; }
        .btn-container { display: flex; gap: 12px; margin-top: 30px; }
        button.btn-submit { flex: 1; background-color: var(--primary-color); color: white; border: none; padding: 12px 20px; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; }
        button.btn-submit:hover { background-color: var(--primary-hover); }
        a.btn-back { flex: 1; background-color: #e2e8f0; color: #475569; text-align: center; text-decoration: none; padding: 12px 20px; border-radius: 8px; font-size: 15px; font-weight: 600; display: inline-flex; justify-content: center; align-items: center; }
    </style>
</head>
<body>

<div class="container">
    <div class="header-title">
        <h2>Tambah Kegiatan Baru</h2>
        <p>Lengkapi formulir di bawah ini untuk menambahkan data kegiatan beserta foto.</p>
    </div>

    <?php if (!empty($pesan_error)): ?>
        <div class="alert"><?= htmlspecialchars($pesan_error); ?></div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nama_kegiatan">Nama Kegiatan</label>
            <input type="text" id="nama_kegiatan" name="nama_kegiatan" placeholder="Contoh: Praktik Pemangkasan Tanaman" required>
        </div>

        <div class="form-group">
            <label for="pembimbing">Pembimbing</label>
            <input type="text" id="pembimbing" name="pembimbing" placeholder="Contoh: Pak Sule / Bu Dera" required>
        </div>

        <div class="form-group">
            <label for="tanggal">Tanggal Kegiatan</label>
            <input type="date" id="tanggal" name="tanggal" required>
        </div>

        <div class="form-group">
            <label for="deskripsi">Deskripsi / Keterangan</label>
            <textarea id="deskripsi" name="deskripsi" placeholder="Tuliskan detail pelaksanaan kegiatan di sini..."></textarea>
        </div>

        <div class="form-group">
            <label for="foto">Foto Utama (Cover)</label>
            <div class="file-upload-box">
                <input type="file" id="foto" name="foto" accept="image/*">
            </div>
            <div class="helper-text">Format: JPG, JPEG, PNG, WEBP.</div>
        </div>

        <div class="form-group">
            <label for="foto_tambahan">Foto Tambahan (Bisa pilih banyak sekaligus)</label>
            <div class="file-upload-box">
                <input type="file" id="foto_tambahan" name="foto_tambahan[]" accept="image/*" multiple>
            </div>
            <div class="helper-text">Tekan tombol Ctrl saat memilih file untuk memilih lebih dari satu foto.</div>
        </div>

        <div class="btn-container">
            <a href="index.php" class="btn-back">Kembali</a>
            <button type="submit" class="btn-submit">Simpan Kegiatan</button>
        </div>
    </form>
</div>

</body>
</html>