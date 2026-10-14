<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tambah Guru</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI',Arial,sans-serif}
body{
    min-height:100vh;
    background: url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=1920') no-repeat center center fixed;
    background-size:cover;
    display:flex;justify-content:center;align-items:center;
    padding:20px;position:relative
}
body::before{content:'';position:absolute;inset:0;background:rgba(16,58,29,0.75);backdrop-filter:blur(2px)}
.card{
    position:relative;z-index:2;width:100%;max-width:480px;
    background:linear-gradient(180deg,rgba(40,80,50,0.85),rgba(20,50,30,0.92));
    backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,0.1);
    border-radius:16px;padding:35px 35px 25px;
    box-shadow:0 10px 30px rgba(0,0,0,0.4)
}
.card h2{text-align:center;color:#a8ff78;font-size:28px;font-weight:800;letter-spacing:.5px}
.sub{text-align:center;color:rgba(255,255,255,0.8);font-size:12px;margin:8px 0 28px;font-weight:600;letter-spacing:.8px}
label{display:block;color:#d1e7c9;font-size:14px;font-weight:700;margin:18px 0 8px}
input[type=text]{
    width:100%;padding:14px 16px;background:rgba(0,0,0,0.35);
    border:1px solid rgba(255,255,255,0.15);border-radius:12px;
    color:white;font-size:14px;outline:none
}
input[type=text]:focus{border-color:#a8ff78;background:rgba(0,0,0,0.5)}
.file-box{
    width:100%;padding:12px;background:rgba(0,0,0,0.35);
    border:1px solid rgba(255,255,255,0.15);border-radius:12px;color:#ccc;font-size:13px
}
.btn{
    width:100%;margin-top:25px;padding:14px;
    background:linear-gradient(90deg,#7ed957,#5fbb3f);
    color:#0f2a15;border:none;border-radius:12px;
    font-weight:800;font-size:15px;cursor:pointer
}
.btn:hover{transform:translateY(-1px);box-shadow:0 5px 15px rgba(126,217,87,0.4)}
.back{display:block;text-align:center;margin-top:15px;color:rgba(255,255,255,0.6);text-decoration:none;font-size:13px}
.back:hover{color:#a8ff78}
</style>
</head>
<body>
<div class="card">
    <h2>Tambah Data Guru</h2>
    <div class="sub">AGRIBISNIS TANAMAN PANGAN & HORTIKULTURA</div>

    <form action="proses_tambah.php" method="POST" enctype="multipart/form-data">
        <label>NIP</label>
        <input type="text" name="nip" required placeholder="Contoh: 19750518 201001 1 008">

        <label>Nama Guru</label>
        <input type="text" name="Nama" required placeholder="Nama Lengkap">

        <label>Mapel Utama</label>
        <input type="text" name="Mapel_Utama" required placeholder="Contoh: Dasar ATPH">

        <label>Wali Kelas</label>
        <input type="text" name="Wali_Kelas">

        <label>Foto Guru</label>
        <div class="file-box">
            <input type="file" name="Foto" accept="image/*">
        </div>

        <button type="submit" class="btn">SIMPAN DATA</button>
    </form>

    <a href="index.php" class="back">← Kembali ke Data Guru</a>
</div>
</body>
</html>