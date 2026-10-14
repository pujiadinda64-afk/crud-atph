<?php
include '../../koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jadwal Lapang per Kelas</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <i class="fa-solid fa-leaf floaty a"></i>
  <i class="fa-solid fa-seedling floaty b"></i>
  <i class="fa-solid fa-wheat-awn floaty c"></i>

  <div class="container">
    <div class="top-nav">
      <a href="../index.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard</a>
    </div>

    <div class="header">
      <i class="fa-solid fa-tractor big-icon"></i>
      <h1><i class="fa-solid fa-calendar-days"></i> Jadwal Lapang per Kelas</h1>
      <p>Pilih kelas di bawah untuk melihat detail agenda penggunaan lahan praktik.</p>
      <div class="stats" id="stats"></div>
    </div>

    <div class="tabs" id="tabs"></div>
    <div id="daftar-kelas"></div>
  </div>

  <script>
    // Atur jumlah rombel per tingkat di sini
    const tingkat = [
      { kelas: 10, jumlah: 4, lencana: 'fa-seedling' },
      { kelas: 11, jumlah: 5, lencana: 'fa-spa' },
      { kelas: 12, jumlah: 5, lencana: 'fa-leaf' }
    ];

    // Ikon berbeda untuk setiap kartu (urut dari Kelas 10 ATPH 1 dst.)
    const ikon = [
      'fa-seedling', 'fa-seedling', 'fa-seedling', 'fa-seedling',
      'fa-spa', 'fa-spa', 'fa-spa', 'fa-spa', 'fa-spa',
      'fa-leaf', 'fa-leaf', 'fa-leaf', 'fa-leaf', 'fa-leaf'
    ];

    const wadah = document.getElementById('daftar-kelas');
    const tabs = document.getElementById('tabs');
    const stats = document.getElementById('stats');
    let urutan = 0;

    const total = tingkat.reduce((a, t) => a + t.jumlah, 0);
    stats.innerHTML = `
      <span class="stat"><i class="fa-solid fa-layer-group"></i><b>${total}</b> Kelas</span>
      <span class="stat"><i class="fa-solid fa-graduation-cap"></i><b>${tingkat.length}</b> Tingkat</span>
      <span class="stat"><i class="fa-solid fa-map-location-dot"></i> Lahan Praktik</span>`;

    tingkat.forEach(({ kelas, jumlah, lencana }) => {
      let kartu = '';
      for (let i = 1; i <= jumlah; i++) {
        kartu += `
          <a class="card" href="detail.php?kelas=${kelas}&rombel=${i}" style="animation-delay:${urutan * 0.06}s">
            <div class="card-banner">
              <span class="watermark">${i}</span>
            </div>
            <div class="icon"><i class="fa-solid ${ikon[urutan]}"></i></div>
            <div class="card-body">
              <h3>Kelas ${kelas} <span>ATPH ${i}</span></h3>
              <p>Kelola dan lihat jadwal penggunaan lahan praktikum untuk siswa/i Kelas ${kelas} ATPH ${i}.</p>
              <div class="chips">
                <span class="chip"><i class="fa-regular fa-calendar-check"></i> Agenda</span>
                <span class="chip"><i class="fa-solid fa-location-dot"></i> Lahan</span>
              </div>
              <span class="btn-open">Buka Jadwal <i class="fa-solid fa-arrow-right"></i></span>
              
            </div>
          </a>`;
        urutan++;
      }

      wadah.insertAdjacentHTML('beforeend', `
        <section class="section t${kelas}" data-kelas="${kelas}">
          <div class="section-title">
            <span class="badge"><i class="fa-solid ${lencana}"></i> Kelas ${kelas}</span>
            <span class="line"></span>
            <span class="count">${jumlah} rombel</span>
          </div>
          <div class="grid">${kartu}</div>
        </section>`);
    });

    // Tab filter
    const daftarTab = [{ label: 'Semua', nilai: 'all' }, ...tingkat.map(t => ({ label: 'Kelas ' + t.kelas, nilai: String(t.kelas) }))];
    tabs.innerHTML = daftarTab.map((t, i) =>
      `<button class="tab ${i === 0 ? 'active' : ''}" data-nilai="${t.nilai}">${t.label}</button>`).join('');

    tabs.addEventListener('click', e => {
      const btn = e.target.closest('.tab');
      if (!btn) return;
      tabs.querySelectorAll('.tab').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      document.querySelectorAll('.section').forEach(s => {
        s.classList.toggle('hide', btn.dataset.nilai !== 'all' && s.dataset.kelas !== btn.dataset.nilai);
      });
    });
  </script>
</body>
</html>