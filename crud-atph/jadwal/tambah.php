<?php

include '../../koneksi.php';

// Kelas & rombel dari URL (dikirim dari detail.php)
$kelas  = isset($_GET['kelas'])  ? intval($_GET['kelas'])  : (isset($_POST['kelas'])  ? intval($_POST['kelas'])  : 0);
$rombel = isset($_GET['rombel']) ? intval($_GET['rombel']) : (isset($_POST['rombel']) ? intval($_POST['rombel']) : 0);

$rombel_maks = [10 => 4, 11 => 5, 12 => 5];
if (!isset($rombel_maks[$kelas]) || $rombel < 1 || $rombel > $rombel_maks[$kelas]) {
    header('Location: index.php');
    exit;
}

// Pesan error & nilai lama (supaya isian tidak hilang saat data salah)
$pesan_error = '';
$detail_error = [];
$old = ['kegiatan' => '', 'lokasi' => '', 'tanggal' => '', 'jam' => '', 'pembimbing' => ''];

// Cek teks: harus ada huruf, 3-100 karakter, hanya karakter wajar
function teks_valid($nilai) {
    $panjang = mb_strlen($nilai);
    if ($panjang < 3 || $panjang > 100) return false;
    if (!preg_match('/\p{L}/u', $nilai)) return false;               // minimal ada 1 huruf
    if (!preg_match('/^[\p{L}\p{N}\s.,\-()\/&\']+$/u', $nilai)) return false; // tanpa simbol aneh
    return true;
}

// ===== SIMPAN DATA BARU ke tabel jadwal_lapang =====
if (isset($_POST['submit'])) {
    foreach ($old as $kolom => $v) {
        $old[$kolom] = trim($_POST[$kolom] ?? '');
    }

    $kegiatan   = $old['kegiatan'];
    $lokasi     = $old['lokasi'];
    $tanggal    = $old['tanggal'];
    $jam        = $old['jam'];
    $pembimbing = $old['pembimbing'];

    // --- Validasi ---
    if (!teks_valid($kegiatan)) {
        $detail_error[] = 'Nama Kegiatan harus berisi huruf (3-100 karakter).';
    }
    if (!teks_valid($lokasi)) {
        $detail_error[] = 'Lokasi Lahan harus berisi huruf (3-100 karakter).';
    }

    $tgl_obj = DateTime::createFromFormat('Y-m-d', $tanggal);
    if (!$tgl_obj || $tgl_obj->format('Y-m-d') !== $tanggal) {
        $detail_error[] = 'Tanggal tidak valid.';
    }

    // Format jam: 07.30 - 10.00 atau 07:30 - 10:00, boleh diakhiri WIB/WITA/WIT
    if (!preg_match('/^([01]?\d|2[0-3])[.:]([0-5]\d)\s*-\s*([01]?\d|2[0-3])[.:]([0-5]\d)(\s*(WIB|WITA|WIT))?$/i', $jam)) {
        $detail_error[] = 'Jam harus berformat seperti 07.30 - 10.00 WIB.';
    }

    if (!teks_valid($pembimbing)) {
        $detail_error[] = 'Pembimbing harus berisi huruf (3-100 karakter).';
    }

    if (!empty($detail_error)) {
        $pesan_error = 'Data tidak valid!, silahkan masukan data yang benar';
    } else {
        $stmt = mysqli_prepare($koneksi, "INSERT INTO jadwal_lapang (kelas, rombel, kegiatan, lokasi, tanggal, jam, pembimbing) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            die("Query gagal: " . mysqli_error($koneksi));
        }
        mysqli_stmt_bind_param($stmt, "iisssss", $kelas, $rombel, $kegiatan, $lokasi, $tanggal, $jam, $pembimbing);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: tambah.php?kelas=$kelas&rombel=$rombel");
            exit();
        } else {
            $pesan_error = 'Data tidak valid, silahkan masukan data yang benar';
            $detail_error[] = 'Gagal menyimpan data: ' . mysqli_stmt_error($stmt);
        }
    }
}

// ===== AMBIL DATA YANG SUDAH ADA (kelas & rombel ini) =====
$stmt = mysqli_prepare($koneksi, "SELECT * FROM jadwal_lapang WHERE kelas = ? AND rombel = ? ORDER BY tanggal ASC");
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
    <title>Tambah Jadwal ATPH</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
* { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
body { background-color: #f4f7f6; padding: 30px; display: flex; justify-content: center; min-height: 100vh; flex-wrap: wrap; gap: 25px; align-items: flex-start; }
.form-container { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); width: 100%; max-width: 500px; }
h2 { color: #2c3e50; margin-bottom: 20px; font-size: 22px; }
.form-group { margin-bottom: 15px; }
label { display: block; margin-bottom: 5px; font-size: 14px; font-weight: 500; color: #333; }
input[type="text"], input[type="date"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; outline: none; }
input:focus { border-color: #27ae60; }
.btn-submit { background: #27ae60; color: white; border: none; padding: 12px; width: 100%; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; margin-top: 10px; }
.btn-submit:hover { background: #219653; }
.back-link { display: block; text-align: center; margin-top: 15px; color: #7f8c8d; text-decoration: none; font-size: 14px; }
.back-link:hover { color: #333; }

.alert-error { background: #fdecea; border: 1px solid #f5c6cb; border-left: 4px solid #e74c3c; color: #c0392b; padding: 12px 14px; border-radius: 8px; margin-bottom: 18px; font-size: 14px; }
.alert-error strong { display: block; font-weight: 600; }
.alert-error ul { margin: 8px 0 0 18px; font-size: 13px; }

.list-container { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); width: 100%; max-width: 760px; }
.list-container table { width: 100%; border-collapse: collapse; }
.list-container th, .list-container td { padding: 10px 12px; text-align: left; font-size: 13px; border-bottom: 1px solid #eef1f0; }
.list-container th { background: #e8f5e9; color: #27ae60; font-weight: 600; }
.list-container tr:hover td { background: #f8fafa; }
.list-container .kosong { color: #7f8c8d; font-size: 14px; text-align: center; }
.aksi { display: flex; gap: 12px; align-items: center; }
.aksi form { margin: 0; }
.btn-edit { color: #f39c12; font-size: 13px; font-weight: 600; text-decoration: none; }
.btn-edit:hover { text-decoration: underline; }
.btn-hapus { background: none; border: none; color: #e74c3c; font-size: 13px; font-weight: 600; cursor: pointer; }
.btn-hapus:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Tambah Jadwal Baru <br><small style="font-size:14px;color:#7f8c8d;font-weight:500;">Kelas <?= $kelas; ?> ATPH <?= $rombel; ?></small></h2>

        <?php if ($pesan_error !== ''): ?>
        <div class="alert-error">
            <strong><?= htmlspecialchars($pesan_error); ?></strong>
            <?php if (!empty($detail_error)): ?>
            <ul>
                <?php foreach ($detail_error as $err): ?>
                <li><?= htmlspecialchars($err); ?></li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="tambah.php?kelas=<?= $kelas; ?>&rombel=<?= $rombel; ?>">
            <input type="hidden" name="kelas" value="<?= $kelas; ?>">
            <input type="hidden" name="rombel" value="<?= $rombel; ?>">
            <div class="form-group">
                <label>Nama Kegiatan</label>
                <input type="text" name="kegiatan" required maxlength="100" value="<?= htmlspecialchars($old['kegiatan']); ?>" placeholder="Contoh: Penanaman Bibit Cabai">
            </div>
            <div class="form-group">
                <label>Lokasi Lahan</label>
                <input type="text" name="lokasi" required maxlength="100" value="<?= htmlspecialchars($old['lokasi']); ?>" placeholder="Contoh: Lahan Blok A">
            </div>
            <div class="form-group">
                <label>Tanggal</label>
                <input type="date" name="tanggal" required value="<?= htmlspecialchars($old['tanggal']); ?>">
            </div>
            <div class="form-group">
                <label>Jam</label>
                <input type="text" name="jam" required value="<?= htmlspecialchars($old['jam']); ?>" placeholder="Contoh: 07.30 - 10.00 WIB">
            </div>
            <div class="form-group">
                <label>Pembimbing</label>
                <input type="text" name="pembimbing" required maxlength="100" value="<?= htmlspecialchars($old['pembimbing']); ?>" placeholder="Contoh: Bpk. Ahmad S.Pd.">
            </div>
            <button type="submit" name="submit" class="btn-submit">Simpan Jadwal</button>
        </form>
        <a href="detail.php?kelas=<?= $kelas; ?>&rombel=<?= $rombel; ?>" class="back-link">&larr; Kembali ke Jadwal Kelas <?= $kelas; ?> ATPH <?= $rombel; ?></a>
    </div>

    <div class="list-container">
        <h2>Jadwal Tersimpan</h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kegiatan</th>
                    <th>Lokasi</th>
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
                    <td>
                        <div class="aksi">
                            <a class="btn-edit" href="edit.php?id=<?= (int) $row['id']; ?>">Edit</a>
                            <form method="POST" action="hapus.php" onsubmit="return confirm('Yakin ingin menghapus?')">
                                <input type="hidden" name="id" value="<?= (int) $row['id']; ?>">
                                <input type="hidden" name="kelas" value="<?= $kelas; ?>">
                                <input type="hidden" name="rombel" value="<?= $rombel; ?>">
                                <input type="hidden" name="kembali" value="tambah">
                                <button type="submit" class="btn-hapus">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>