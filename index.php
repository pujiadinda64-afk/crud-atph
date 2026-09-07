<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'koneksi.php';

$is_logged_in = isset($_SESSION['user_id']);
$role = $is_logged_in ? $_SESSION['role'] : 'guest';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Aplikasi CRUD Jurusan ATPH</title>
  <link rel="stylesheet" href="style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

  <!-- HERO CONTAINER -->
  <div class="hero-container">
    <!-- Navigation Bar -->
    <nav class="navbar">
      <div class="logo">
        <span class="logo-icon">🌿</span> ATPH SMKN 1
      </div>
      
      <ul class="nav-links">
        <li><a href="index.php" class="active">Home</a></li>
        <li><a href="guru/index.php">Data Guru</a></li>
        <li><a href="alat/index.php">Data Alat</a></li>
        <li><a href="jadwal/index.php">Jadwal Lapang</a></li>
        <li><a href="kegiatan/index.php">Kegiatan</a></li>
      </ul>

      <div class="nav-actions">
        <button class="icon-btn" title="Favorit">♡</button>
        
        <?php if ($is_logged_in): ?>
          <a href="logout.php" class="btn-auth btn-logout">Logout</a>
        <?php else: ?>
          <a href="login.php" class="btn-auth btn-login">Login</a>
        <?php endif; ?>
      </div>
    </nav>

    <!-- Content Hero Utama -->
    <div class="hero-body">
      <div class="hero-text">
        <span class="tag">🌱 Modern • Digital • Terintegrasi</span>
        <h1>Kelola Data Praktikum & Aset Jurusan <span class="highlight">ATPH</span></h1>
        <p>Platform terpadu untuk pencatatan data guru, inventaris alat pertanian, penjadwalan lapangan, serta pemantauan praktikum tanaman secara efektif dan real-time.</p>
      </div>

      <!-- Profil Sekolah (Menggunakan SVG Lokal Agar Tidak Error Muter) -->
      <div class="school-profile">
        <div class="school-avatar">
          <span class="avatar-icon">🏫</span>
        </div>
        <div class="proof-text">
          <strong>SMKN 1 ATPH</strong>
          <span>100% Digital System</span>
        </div>
      </div>
    </div>

    <!-- Floating Search Bar Interaktif -->
    <form action="pencarian.php" method="GET" class="search-bar">
      <div class="search-field">
        <span class="field-icon">🔍</span>
        <div class="field-content">
          <span class="filter-label">Kata Kunci</span>
          <input type="text" name="keyword" class="filter-input" placeholder="Cari guru, alat, atau agenda...">
        </div>
      </div>

      <div class="search-field border-left">
        <span class="field-icon">📁</span>
        <div class="field-content">
          <span class="filter-label">Kategori Data</span>
          <select name="kategori" class="filter-select">
            <option value="semua">Semua Kategori</option>
            <option value="guru">Data Guru</option>
            <option value="alat">Inventaris Alat</option>
            <option value="jadwal">Jadwal Lapang</option>
            <option value="kegiatan">Kegiatan</option>
          </select>
        </div>
      </div>

      <button type="submit" class="btn-search-submit">
        Cari Data ➔
      </button>
    </form>
  </div>

  <!-- SECTION DESKRIPSI JURUSAN -->
<section class="hero-section" id="home">
    <div class="hero-content">
      <span class="hero-tag">JURUSAN ATPH</span>
      <h1 class="hero-title">Agribisnis Tanaman Pangan & Hortikultura</h1>
      <p class="hero-desc">
        Pertanian bukan sekadar bercocok tanam, melainkan seni mengelola masa depan pangan berbasis teknologi modern. Kami berfokus pada pengembangan keterampilan siswa dalam mengelola tanaman pangan, buah, sayuran, hingga sistem hidroponik.
      </p>

      <div class="hero-badges">
        <span class="badge">Modern Agri</span>
        <span class="badge">Hidroponik</span>
        <span class="badge">Teknologi Digital</span>
      </div>
    </div>

    <div class="hero-image-wrapper">
      <img src="suga3-removebg-preview.png" alt="Foto Utama ATPH" class="hero-img">
    </div>
  </section>

<!-- KARTU BAWAH YANG MENUMPUK KE ATAS -->
<div class="about-grid">
  <!-- Card 1: Galeri -->
  <div class="glass-box">
    <p class="box-title"><strong>Galeri Praktikum:</strong></p>
    <div class="gallery-mini">
      <div class="gallery-item">🥬</div>
      <div class="gallery-item">🍓</div>
      <div class="gallery-item">🌱</div>
    </div>
    <p class="box-desc">Menggabungkan ilmu sains budidaya dengan praktik kerja lapangan secara langsung.</p>
  </div>

  <!-- Card 2: Deskripsi Ringkas -->
  <div class="glass-box box-between">
    <p class="box-desc-large">
      Jurusan Agribisnis Tanaman Pangan dan Hortikultura berfokus pada pengembangan keterampilan siswa dalam mengelola tanaman pangan, buah-buahan, sayuran, hidroponik, hingga pemasaran hasil tani.
    </p>
    <div class="stats-group">
      <div>
        <strong>100%</strong>
        <span>Praktik Digital</span>
      </div>
      <div>
        <strong>5+</strong>
        <span>Lahan Lapang</span>
      </div>
    </div>
  </div>

  <!-- Card 3: Barcode Instagram (Tumpang tindih dengan bawah foto) -->
  <div class="glass-box box-center">
  <p class="box-title"><strong>Ikuti Instagram Kami ↗</strong></p>
  
  <!-- MASUKKAN FOTO QR CODE DI SINI -->
  <div class="qr-box">
    <img src="brcd.png" alt="QR Code Instagram" class="qr-img">
  </div>
  
  <span class="ig-handle">@atphsmkn1maja</span>
  <span class="qr-sub">Scan untuk info kegiatan terbaru</span>
</div>
  </section>

  <!-- SECTION MENU DATA INTERAKTIF -->
  <section id="menu-data">
    <div class="container">
      <div class="section-header">
        <div>
          <span class="section-tag">Katalog Data</span>
          <h2>Pilih Data yang Ingin Dilihat</h2>
        </div>
        <a href="#" class="link-more">Lihat Semua Aset &rarr;</a>
      </div>

      <div class="grid-cards">
        
        <!-- Card 1 -->
        <div class="card-item">
          <div class="card-thumb">
            <span class="card-emoji">👨‍🏫</span>
            <span class="badge-card">Pengajar</span>
          </div>
          <div class="card-body">
            <div>
              <h3>Data Guru & Staf</h3>
              <p>Profil pengajar dan penanggung jawab laboratorium jurusan ATPH.</p>
            </div>
            <div class="card-footer">
              <span>Guru • Profil</span>
              <a href="guru/index.php" class="btn-arrow">&rarr;</a>
            </div>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="card-item">
          <div class="card-thumb">
            <span class="card-emoji">🚜</span>
            <span class="badge-card">Inventaris</span>
          </div>
          <div class="card-body">
            <div>
              <h3>Inventaris Alat</h3>
              <p>Katalog mesin dan peralatan pertanian modern serta hidroponik.</p>
            </div>
            <div class="card-footer">
              <span>Alat • Barang</span>
              <a href="alat/index.php" class="btn-arrow">&rarr;</a>
            </div>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="card-item">
          <div class="card-thumb">
            <span class="card-emoji">📅</span>
            <span class="badge-card">Agendakan</span>
          </div>
          <div class="card-body">
            <div>
              <h3>Jadwal Lapang</h3>
              <p>Penjadwalan penggunaan lahan praktik dan kegiatan siswa.</p>
            </div>
            <div class="card-footer">
              <span>Jadwal • Lahan</span>
              <a href="jadwal/index.php" class="btn-arrow">&rarr;</a>
            </div>
          </div>
        </div>

        <!-- Card 4 -->
        <div class="card-item">
          <div class="card-thumb">
            <span class="card-emoji">🌱</span>
            <span class="badge-card">Kegiatan</span>
          </div>
          <div class="card-body">
            <div>
              <h3>Kegiatan Pembelajaran</h3>
              <p>Dokumentasi dan materi kegiatan agribisnis serta hortikultura.</p>
            </div>
            <div class="card-footer">
              <span>Tanaman • Hasil</span>
              <a href="kegiatan/index.php" class="btn-arrow">&rarr;</a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- SECTION KONTAK -->
  <section id="kontak">
    <div class="container">
      <div class="contact-box">
        <div class="contact-grid">
          <div class="contact-info">
            <h2>🛠️ Mari Terhubung Bersama Kami</h2>
            <p class="contact-sub">Punya pertanyaan seputar jurusan ATPH, kerjasama, atau program praktik? Jangan ragu untuk menghubungi kami.</p>

            <div class="contact-item">
              <strong>EMAIL</strong>
              <span>atph@smkn1.sch.id</span>
            </div>
            
            <div class="contact-item">
              <strong>TELEPON / WA</strong>
              <span>+62 812-3456-7890</span>
            </div>

            <div class="contact-item">
              <strong>LOKASI</strong>
              <span>Area Green House SMKN 1</span>
            </div>
          </div>

          <div class="contact-form-wrapper">
            <form action="proses_kontak.php" method="POST" class="contact-form">
              <input type="text" name="nama" placeholder="Nama Anda" required>
              <input type="email" name="email" placeholder="Email Anda" required>
              <input type="text" name="subjek" placeholder="Subjek / Perihal">
              <textarea name="pesan" rows="4" placeholder="Pesan Anda..." required></textarea>
              <button type="submit" class="btn-submit-contact">Kirim Pesan</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
<script>
  // Efek elemen muncul berurutan saat halaman di-scroll
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = '1';
        entry.target.style.transform = 'translateY(0)';
      }
    });
  }, { threshold: 0.1 });

  document.querySelectorAll('.card-item, .glass-box').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(30px)';
    el.style.transition = 'all 0.6s ease-out';
    observer.observe(el);
  });
</script>
</body>
</html>