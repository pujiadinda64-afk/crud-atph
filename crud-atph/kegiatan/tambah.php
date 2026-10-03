<?php
session_start();

if (($_SESSION['role'] ?? '') !== 'admin') {
    echo "<script>alert('Akses ditolak! Halaman ini khusus untuk Admin.'); window.location='index.php';</script>";
    exit();
}
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

session_start();
include '../config/koneksi.php';


if (($_SESSION['role'] ?? '') !== 'admin') {
    echo "<script>alert('Akses ditolak! Hanya admin yang dapat menambah data.'); window.location='index.php';</script>";
    exit;
}

if (isset($_POST['simpan'])) {

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "<script>alert('Akses ditolak! Halaman ini khusus untuk Admin.'); window.location='index.php';</script>";
    exit();
}

if (isset($_POST['submit'])) {

    $nama_kegiatan = $_POST['nama_kegiatan'];
    $deskripsi     = $_POST['deskripsi'];
    $pembimbing    = $_POST['pembimbing'];
    $tanggal       = $_POST['tanggal'];

    // Upload Foto Utama
    $foto_utama = time() . '_' . $_FILES['foto']['name'];
    $tmp_utama  = $_FILES['foto']['tmp_name'];
    move_uploaded_file($tmp_utama, 'uploads/' . $foto_utama);


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
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kegiatan - Sistem ATPH</title>
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome untuk Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-green: #2e7d32;
            --light-green: #4caf50;
            --accent-green: #e8f5e9;
            --dark-text: #2c3e50;
            --gray-border: #cbd5e1;
            --bg-gradient: linear-gradient(135deg, #f1f8f5 0%, #e2eee6 100%);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: var(--bg-gradient);
            color: var(--dark-text);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .form-container {
            background: #ffffff;
            width: 100%;
            max-width: 750px;
            padding: 40px;
            border-radius: 24px;
            box-shadow: 0 15px 35px rgba(46, 125, 50, 0.1);
            border: 1px solid rgba(76, 175, 80, 0.2);
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-header {
            margin-bottom: 30px;
            border-bottom: 2px dashed var(--accent-green);
            padding-bottom: 20px;
        }

        .form-header h2 {
            font-size: 26px;
            color: var(--primary-green);
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .form-header h2 i {
            background: var(--accent-green);
            color: var(--primary-green);
            padding: 10px;
            border-radius: 12px;
            font-size: 20px;
        }

        .form-header p {
            color: #64748b;
            font-size: 14px;
            margin-top: 6px;
        }

        .alert {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            border: 1px solid #f5c6cb;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
        }

        .form-group label i {
            color: var(--light-green);
            margin-right: 6px;
        }

        .form-control, 
        .form-group input[type="text"], 
        .form-group input[type="date"], 
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid var(--gray-border);
            border-radius: 12px;
            font-size: 14px;
            color: var(--dark-text);
            background-color: #f8fafc;
            transition: all 0.3s ease;
        }

        .form-control:focus, 
        .form-group input[type="text"]:focus, 
        .form-group input[type="date"]:focus, 
        .form-group textarea:focus {
            outline: none;
            border-color: var(--light-green);
            background-color: #fff;
            box-shadow: 0 0 0 4px rgba(76, 175, 80, 0.15);
        }

        textarea {
            resize: vertical;
            min-height: 110px;
        }

        /* Kotak Upload Kustom yang Menarik */
        .file-upload-box {
            border: 2px dashed var(--gray-border);
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            background-color: #f8fafc;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-upload-box:hover {
            border-color: var(--light-green);
            background-color: var(--accent-green);
        }

        .file-upload-box i {
            font-size: 28px;
            color: var(--light-green);
            margin-bottom: 6px;
            display: block;
        }

        .file-upload-box p {
            font-size: 13px;
            color: #64748b;
        }

        .helper-text {
            font-size: 12px;
            color: #64748b;
            margin-top: 6px;
        }

        .file-selected {
            font-size: 13px;
            color: var(--primary-green);
            margin-top: 6px;
            font-weight: 600;
            display: block;
        }

        /* Tombol Aksi */
        .btn-container {
            display: flex;
            gap: 15px;
            margin-top: 35px;
        }

        .btn-submit, .btn-back {
            padding: 12px 24px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: none;
        }

        .btn-submit {
            background-color: var(--primary-green);
            color: white;
            flex: 2;
            box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
        }

        .btn-submit:hover {
            background-color: #1b5e20;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(46, 125, 50, 0.4);
        }

        .btn-back {
            background-color: #e2e8f0;
            color: #475569;
            flex: 1;
        }

        .btn-back:hover {
            background-color: #cbd5e1;
            color: #1e293b;
        }

        @media (max-width: 768px) {
            body { padding: 15px; }
            .form-container { padding: 20px; }
            .btn-container { flex-direction: column; }
        }
    </style>
</head>
<body>

<div class="form-container">
    <div class="form-header">
        <h2><i class="fa-solid fa-seedling"></i> Tambah Kegiatan Baru</h2>
        <p>Lengkapi formulir di bawah ini untuk menambahkan data kegiatan beserta foto.</p>
    </div>

    <?php if (!empty($pesan_error)): ?>
        <div class="alert"><?= htmlspecialchars($pesan_error); ?></div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        
        <div class="form-group">
            <label for="nama_kegiatan"><i class="fa-solid fa-pen-to-square"></i> Nama Kegiatan</label>
            <input type="text" id="nama_kegiatan" name="nama_kegiatan" placeholder="Contoh: Praktik Pemangkasan Tanaman" required>
        </div>

        <div class="form-group">
            <label for="pembimbing"><i class="fa-solid fa-chalkboard-user"></i> Pembimbing</label>
            <input type="text" id="pembimbing" name="pembimbing" placeholder="Contoh: Pak Sule / Bu Dera" required>
        </div>

        <div class="form-group">
            <label for="tanggal"><i class="fa-solid fa-calendar-days"></i> Tanggal Kegiatan</label>
            <input type="date" id="tanggal" name="tanggal" required>
        </div>

        <div class="form-group">
            <label for="deskripsi"><i class="fa-solid fa-circle-info"></i> Deskripsi / Keterangan</label>
            <textarea id="deskripsi" name="deskripsi" placeholder="Tuliskan detail pelaksanaan kegiatan di sini..."></textarea>
        </div>

        <!-- Foto Utama -->
        <div class="form-group">
            <label for="foto"><i class="fa-solid fa-image"></i> Foto Utama (Cover)</label>
            <div class="file-upload-box" onclick="document.getElementById('foto').click()">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                <p>Klik untuk memilih Foto Utama</p>
                <input type="file" id="foto" name="foto" accept="image/*" style="display: none;" onchange="showFileName('foto', 'file-name-cover')">
            </div>
            <div class="helper-text">Format: JPG, JPEG, PNG, WEBP.</div>
            <span id="file-name-cover" class="file-selected"></span>
        </div>

        <!-- Foto Tambahan -->
        <div class="form-group">
            <label for="foto_tanggal"><i class="fa-solid fa-images"></i> Foto Tambahan (Bisa pilih banyak sekaligus)</label>
            <div class="file-upload-box" onclick="document.getElementById('foto_tambahan').click()">
                <i class="fa-solid fa-folder-open"></i>
                <p>Klik untuk memilih beberapa Foto Tambahan</p>
                <input type="file" id="foto_tambahan" name="foto_tambahan[]" accept="image/*" multiple style="display: none;" onchange="showMultipleFiles(this, 'file-name-multiple')">
            </div>
            <div class="helper-text">Tekan tombol Ctrl saat memilih file untuk memilih lebih dari satu foto.</div>
            <span id="file-name-multiple" class="file-selected"></span>
        </div>

        <div class="btn-container">
            <a href="index.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
            <button type="submit" name="simpan" class="btn-submit"><i class="fa-solid fa-floppy-disk"></i> Simpan Kegiatan</button>
        </div>
    </form>
</div>

<script>
    // Menampilkan nama file untuk Foto Utama
    function showFileName(inputId, targetId) {
        const input = document.getElementById(inputId);
        const target = document.getElementById(targetId);
        if (input.files && input.files[0]) {
            target.textContent = "✔ Terpilih: " + input.files[0].name;
        }
    }

    // Menampilkan jumlah file untuk Foto Tambahan (Multiple)
    function showMultipleFiles(input, targetId) {
        const target = document.getElementById(targetId);
        if (input.files && input.files.length > 0) {
            if (input.files.length === 1) {
                target.textContent = "✔ Terpilih: " + input.files[0].name;
            } else {
                target.textContent = `✔ Terpilih: ${input.files.length} file foto tambahan`;
            }
        }
    }
</script>

</body>
</html>