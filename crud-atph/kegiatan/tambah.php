<?php
// Panggil file koneksi database kamu di sini (sesuaikan nama file koneksinya jika beda, misal 'koneksi.php')
include '../../koneksi.php'; 

$pesan_error = "";
$pesan_sukses = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_kegiatan = trim($_POST['nama_kegiatan']);
    $deskripsi     = trim($_POST['deskripsi']);
    $tanggal       = $_POST['tanggal'];

    // Validasi input wajib diisi
    if (empty($nama_kegiatan) || empty($tanggal)) {
        $pesan_error = "Nama kegiatan dan tanggal wajib diisi!";
    } else {
        // 1. Upload Foto Utama (Wajib / Opsional sesuai kebutuhan)
        $nama_foto_utama = "";
        if (isset($_FILES['foto_utama']) && $_FILES['foto_utama']['error'] === UPLOAD_ERR_OK) {
            $file_tmp   = $_FILES['foto_utama']['tmp_name'];
            $file_name  = $_FILES['foto_utama']['name'];
            $ekstensi   = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed    = ['jpg', 'jpeg', 'png', 'webp'];

            if (in_array($ekstensi, $allowed)) {
                $nama_foto_utama = time() . '_utama_' . uniqid() . '.' . $ekstensi;
                $tujuan_folder   = 'uploads/' . $nama_foto_utama;
                
                // Pastikan folder uploads ada
                if (!is_dir('uploads')) {
                    mkdir('uploads', 0777, true);
                }

                move_uploaded_file($file_tmp, $tujuan_folder);
            } else {
                $pesan_error = "Format foto utama harus JPG, JPEG, PNG, atau WEBP!";
            }
        }

        if (empty($pesan_error)) {
            // 2. Simpan data kegiatan ke database menggunakan Prepared Statement
            $stmt = $conn->prepare("INSERT INTO kegiatan (nama_kegiatan, deskripsi, tanggal, foto_utama) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nama_kegiatan, $deskripsi, $tanggal, $nama_foto_utama);
            
            if ($stmt->execute()) {
                $kegiatan_id = $stmt->insert_id; // Ambil ID kegiatan yang baru saja dimasukkan
                $stmt->close();

                // 3. Proses Upload Foto Tambahan (Multiple)
                if (isset($_FILES['foto_tambahan']) && !empty($_FILES['foto_tambahan']['name'][0])) {
                    $jumlah_file = count($_FILES['foto_tambahan']['name']);

                    for ($i = 0; $i < $jumlah_file; $i++) {
                        if ($_FILES['foto_tambahan']['error'][$i] === UPLOAD_ERR_OK) {
                            $tmp_name = $_FILES['foto_tambahan']['tmp_name'][$i];
                            $orig_name = $_FILES['foto_tambahan']['name'][$i];
                            $ext = strtolower(pathinfo($orig_name, PATHINFO_EXTENSION));

                            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                                $nama_foto_tambah = time() . '_tambahan_' . $i . '_' . uniqid() . '.' . $ext;
                                move_uploaded_file($tmp_name, 'uploads/' . $nama_foto_tambah);

                                // Simpan ke tabel foto_kegiatan
                                $stmt_foto = $conn->prepare("INSERT INTO foto_kegiatan (kegiatan_id, nama_file) VALUES (?, ?)");
                                $stmt_foto->bind_param("is", $kegiatan_id, $nama_foto_tambah);
                                $stmt_foto->execute();
                                $stmt_foto->close();
                            }
                        }
                    }
                }

                $pesan_sukses = "Data kegiatan dan foto berhasil disimpan!";
                // Redirect setelah sukses (opsional)
                header("refresh:2;url=index.php");
            } else {
                $pesan_error = "Gagal menyimpan ke database: " . $conn->error;
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
    <title>Tambah Kegiatan Baru - Sistem ATPH</title>
    <!-- Google Fonts Inter -->
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

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 680px;
            background: var(--card-bg);
            padding: 40px;
            border-radius: var(--radius);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }

        .header-title {
            margin-bottom: 25px;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 15px;
        }

        .header-title h2 {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-main);
        }

        .header-title p {
            font-size: 14px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .alert {
            padding: 14px 16px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .alert-error {
            background-color: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background-color: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="date"],
        textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
            color: var(--text-main);
            background-color: #fff;
            transition: all 0.2s ease;
        }

        input[type="text"]:focus,
        input[type="date"]:focus,
        textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        textarea {
            resize: vertical;
            min-height: 110px;
        }

        .file-upload-box {
            border: 2px dashed var(--border-color);
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            background: #fafafa;
            transition: 0.2s;
        }

        .file-upload-box:hover {
            border-color: var(--primary-color);
            background: #f1f5f9;
        }

        input[type="file"] {
            font-size: 13px;
            color: var(--text-muted);
        }

        .helper-text {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 6px;
        }

        .btn-container {
            display: flex;
            gap: 12px;
            margin-top: 30px;
        }

        button.btn-submit {
            flex: 1;
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        button.btn-submit:hover {
            background-color: var(--primary-hover);
        }

        a.btn-back {
            flex: 1;
            background-color: #e2e8f0;
            color: #475569;
            text-align: center;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            transition: background 0.2s;
        }

        a.btn-back:hover {
            background-color: #cbd5e1;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header-title">
        <h2>Tambah Kegiatan Baru</h2>
        <p>Lengkapi formulir di bawah ini untuk menambahkan data kegiatan beserta foto.</p>
    </div>

    <?php if (!empty($pesan_error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($pesan_error); ?></div>
    <?php endif; ?>

    <?php if (!empty($pesan_sukses)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($pesan_sukses); ?></div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nama_kegiatan">Nama Kegiatan</label>
            <input type="text" id="nama_kegiatan" name="nama_kegiatan" placeholder="Contoh: Praktik Pemangkasan Tanaman" required>
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
            <label for="foto_utama">Foto Utama (Cover)</label>
            <div class="file-upload-box">
                <input type="file" id="foto_utama" name="foto_utama" accept="image/*">
            </div>
            <div class="helper-text">Format yang didukung: JPG, JPEG, PNG, WEBP.</div>
        </div>

        <div class="form-group">
            <label for="foto_tambahan">Foto Tambahan (Bisa pilih banyak sekaligus)</label>
            <div class="file-upload-box">
                <input type="file" id="foto_tambahan" name="foto_tambahan[]" accept="image/*" multiple>
            </div>
            <div class="helper-text">Tekan tombol Ctrl (atau Cmd di Mac) saat memilih file untuk memilih lebih dari satu foto.</div>
        </div>

        <div class="btn-container">
            <a href="index.php" class="btn-back">Kembali</a>
            <button type="submit" class="btn-submit">Simpan Kegiatan</button>
        </div>
    </form>
</div>

</body>
</html>