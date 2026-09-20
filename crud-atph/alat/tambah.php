<?php
// Menggunakan path absolut berbasis direktori saat ini
include __DIR__ . "/../../koneksi.php";

/** @var mysqli $koneksi */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nama_alat       = trim($_POST["nama_alat"] ?? "");
    $jumlah_stok     = trim($_POST["jumlah_stok"] ?? "");
    $kondisi = trim($_POST["kondisi"] ?? "");
    $foto = null;

    if ($nama_alat === "" || $jumlah_stok === "") {
        die("Nama alat dan jumlah stok wajib diisi. <a href='index.php'>Kembali</a>");
    }

    $nama_foto = null;

    // ===== Proses upload foto (opsional) =====
    if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] === UPLOAD_ERR_OK) {
        $ekstensi_diizinkan = ["jpg", "jpeg", "png", "webp"];
        $ekstensi = strtolower(pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION));
        $ukuran_maks = 2 * 1024 * 1024; // 2MB

        if (!in_array($ekstensi, $ekstensi_diizinkan)) {
            die("Format foto harus jpg, jpeg, png, atau webp. <a href='index.php'>Kembali</a>");
        }
        if ($_FILES["foto"]["size"] > $ukuran_maks) {
            die("Ukuran foto maksimal 2MB. <a href='index.php'>Kembali</a>");
        }

        $folder_upload = "uploads/";
        if (!is_dir($folder_upload)) {
            mkdir($folder_upload, 0755, true);
        }

        $foto = "alat_" . time() . "_" . rand(100, 999) . "." . $ekstensi;
        $tujuan = $folder_upload . $foto;

        if (!move_uploaded_file($_FILES["foto"]["tmp_name"], $tujuan)) {
            die("Gagal mengunggah foto. <a href='index.php'>Kembali</a>");
        }
    }

    // ===== Simpan ke database =====
    $stmt = mysqli_prepare(
        $koneksi,
        "INSERT INTO alat (nama_alat, jumlah_stok, kondisi, foto) VALUES (?, ?, ?, ?)"
    );
    mysqli_stmt_bind_param($stmt, "ssss", $nama_alat, $jumlah_stok, $kondisi, $foto);

    if (mysqli_stmt_execute($stmt)) {
        $id_baru = mysqli_insert_id($koneksi);
        header("Location: index.php?pesan=tambah_sukses&id=" . $id_baru);
        exit;
    } else {
        echo "Gagal menambah data: " . mysqli_error($koneksi);
    }

    mysqli_stmt_close($stmt);
} else {
    header("Location: index.php");
    exit;
}
?>