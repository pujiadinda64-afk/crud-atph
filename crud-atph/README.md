# CRUD ATPH — versi diperbaiki

## Struktur
- `config/koneksi.php` — konfigurasi koneksi MySQL.
- `database/projek.sql` — struktur dan data awal database.
- `guru/` — CRUD data guru + folder upload.
- `alat/` — CRUD inventaris alat + folder upload.
- `kegiatan/` — CRUD kegiatan + galeri foto + folder upload.
- `jadwal/` — CRUD jadwal lapang.
- `assets` tetap berupa file gambar/CSS di root agar kompatibel dengan halaman lama.

## Perbaikan utama
1. Struktur folder dirapikan: tidak ada lagi folder `crud-atph/crud-atph` dan `.git` bawaan.
2. Semua halaman memakai `config/koneksi.php` dengan path yang konsisten.
3. Fitur hapus **Guru, Alat, Kegiatan, dan Jadwal** diperbaiki.
4. Penghapusan kegiatan juga menghapus foto utama dan foto galeri terkait.
5. Penghapusan guru/alat juga menghapus file foto dari folder upload.
6. Query hapus memakai prepared statement dan validasi ID.
7. Hak hapus/edit/tambah dibatasi untuk akun `admin`.
8. Bug `alat` yang sebelumnya memakai kolom `id`/`cara_pakai` yang tidak ada di database disesuaikan dengan schema: `id_alat`, `jumlah_stok`, `kondisi`.
9. CRUD Jadwal dibuat ulang karena file jadwal pada paket sebelumnya kosong.
10. Bug form `kegiatan/tambah.php` (nama field dan tombol submit tidak cocok dengan proses PHP) diperbaiki.

## Menjalankan
1. Ekstrak folder `crud-atph-fixed` ke `htdocs` (XAMPP) atau web root.
2. Buat database `projek`.
3. Import `database/projek.sql` lewat phpMyAdmin.
4. Pastikan `config/koneksi.php` sesuai dengan username/password MySQL lokal.
5. Buka `http://localhost/crud-atph-fixed/`.

Akun contoh dari database:
- admin: `admin` / `4dm1n`
- siswa: `siswa01` / `s1sw4`

> Untuk produksi, sebaiknya password di database diubah menjadi password hash (`password_hash`) dan login menggunakan `password_verify`.
