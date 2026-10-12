<?php
include '../config/koneksi.php';

/** @var mysqli $koneksi */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nama_alat   = trim($_POST["nama_alat"] ?? "");
    $jumlah_stok = trim($_POST["jumlah_stok"] ?? "");
    $kondisi     = trim($_POST["kondisi"] ?? "");

    if ($nama_alat === "" || $jumlah_stok === "" || $kondisi === "") {
        echo "<div style='font-family:sans-serif;max-width:600px;margin:60px auto;padding:24px;border:1px solid #fca5a5;background:#fef2f2;border-radius:12px;'>";
        echo "<h3 style='color:#b91c1c;margin-top:0;'>Nama alat, jumlah stok, dan kondisi wajib diisi.</h3>";
        echo "<p style='font-size:13px;color:#7f1d1d;'>Data yang diterima server (untuk pengecekan):</p>";
        echo "<pre style='background:#fff;padding:12px;border-radius:8px;overflow:auto;font-size:12px;'>";
        echo "REQUEST_METHOD: " . htmlspecialchars($_SERVER["REQUEST_METHOD"]) . "\n";
        echo "\$_POST:\n" . htmlspecialchars(print_r($_POST, true));
        echo "\$_FILES:\n" . htmlspecialchars(print_r($_FILES, true));
        echo "</pre>";
        echo "<p><a href='index.php'>← Kembali</a></p>";
        echo "</div>";
        exit;
    }

    $nama_foto = null;

    // ===== Proses upload file (foto / dokumen WPS), opsional =====
    if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] === UPLOAD_ERR_OK) {

       $ekstensi_gambar = ["jpg", "jpeg", "jfif", "png", "webp", "gif", "bmp", "avif"];
$ekstensi_diizinkan = [
    // gambar
    "jpg", "jpeg", "jfif", "png", "webp", "gif", "bmp", "avif",
    // WPS Office
    "wps", "et", "dps",
    // dokumen umum lain
    "doc", "docx", "xls", "xlsx", "ppt", "pptx", "pdf"
        ];
        $ekstensi = strtolower(pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION));
        $ukuran_maks = 5 * 1024 * 1024; // 5MB

        if (!in_array($ekstensi, $ekstensi_diizinkan, true)) {
            die("Format file tidak diizinkan (terdeteksi: ." . htmlspecialchars($ekstensi) . "). Gunakan: " . implode(", ", $ekstensi_diizinkan) . ". <a href='index.php'>Kembali</a>");
        }
        if ($_FILES["foto"]["size"] > $ukuran_maks) {
            die("Ukuran file maksimal 5MB. <a href='index.php'>Kembali</a>");
        }

        // Validasi isi file hanya untuk gambar (file WPS tidak punya MIME standar)
        if (in_array($ekstensi, $ekstensi_gambar, true)) {
            if (@getimagesize($_FILES["foto"]["tmp_name"]) === false) {
                die("File bukan gambar yang valid. <a href='index.php'>Kembali</a>");
            }
        }

        $folder_upload = "uploads/";
        if (!is_dir($folder_upload)) {
            mkdir($folder_upload, 0755, true);
        }

        // nama file unik supaya tidak bentrok
        $nama_foto = "alat_" . time() . "_" . rand(100, 999) . "." . $ekstensi;
        $tujuan = $folder_upload . $nama_foto;

        if (!move_uploaded_file($_FILES["foto"]["tmp_name"], $tujuan)) {
            die("Gagal mengunggah file. <a href='index.php'>Kembali</a>");
        }
    } elseif (isset($_FILES["foto"]) && $_FILES["foto"]["error"] !== UPLOAD_ERR_NO_FILE && $_FILES["foto"]["error"] !== UPLOAD_ERR_OK) {
        // error upload selain "tidak ada file", mis. melebihi upload_max_filesize di php.ini
        die("Upload gagal (kode error " . (int)$_FILES["foto"]["error"] . "). Cek upload_max_filesize dan post_max_size di php.ini. <a href='index.php'>Kembali</a>");
    }

    // ===== Simpan ke database (prepared statement, aman dari SQL injection) =====
    $stmt = mysqli_prepare(
        $koneksi,
        "INSERT INTO alat (nama_alat, jumlah_stok, kondisi, foto) VALUES (?, ?, ?, ?)"
    );
    mysqli_stmt_bind_param($stmt, "ssss", $nama_alat, $jumlah_stok, $kondisi, $nama_foto);

    if (mysqli_stmt_execute($stmt)) {
        $id_baru = mysqli_insert_id($koneksi); // id (primary key) hasil auto increment
        header("Location: index.php?pesan=tambah_sukses&id=" . $id_baru);
        exit;
    } else {
        echo "Gagal menambah data: " . mysqli_error($koneksi);
    }

    mysqli_stmt_close($stmt);
} else {
    echo "<div style='font-family:sans-serif;max-width:600px;margin:60px auto;padding:24px;border:1px solid #fca5a5;background:#fef2f2;border-radius:12px;'>";
    echo "<h3 style='color:#b91c1c;margin-top:0;'>Halaman ini diakses tanpa mengirim form (method: " . htmlspecialchars($_SERVER["REQUEST_METHOD"]) . ").</h3>";
    echo "<p style='font-size:13.5px;'>Pastikan kamu mengisi form lewat tab <b>Kelola Data</b> di <code>index.php</code>, bukan membuka <code>tambah.php</code> langsung dari address bar.</p>";
    echo "<p><a href='index.php'>← Kembali ke index.php</a></p>";
    echo "</div>";
    exit;
}
?>