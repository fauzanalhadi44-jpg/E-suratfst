# Sistem Informasi Pengajuan dan Pemantauan Surat Mahasiswa (FST UIN Imam Bonjol Padang)

PHP 8.1+ (native, PDO) + MySQL/MariaDB, sesuai dokumen perancangan (Prototype -> Pengodean).
Ekstensi PHP yang dibutuhkan: pdo_mysql, mbstring, fileinfo (aktif di XAMPP dan kebanyakan hosting).

## Struktur
- `database.sql` : skema 8 tabel sesuai ERD + data 7 jenis surat + contoh persyaratan
- `config/config.php` : koneksi DB, BASE_URL, daftar prodi
- `includes/` : bootstrap, helper (CSRF, validasi upload, notifikasi), layout
- `index.php, register.php, login.php, logout.php, setup.php` : halaman publik
- `mahasiswa/` : dashboard, ajukan (+ajukan ulang), riwayat, detail
- `admin/` : dashboard, pengajuan (daftar semua/diproses/disetujui/ditolak), detail (periksa, proses, setujui, tolak), terbitkan, mahasiswa, laporan
- `notifikasi.php, profil.php, file.php, surat_cetak.php` : dipakai kedua peran (surat_cetak.php menyajikan PDF surat terbit)
- `uploads/` : dokumen mahasiswa (diblokir dari akses langsung; dibuka lewat `file.php` dengan cek hak akses)

## Menjalankan di laptop (XAMPP)
1. Install XAMPP, jalankan Apache dan MySQL.
2. Salin folder ini ke `C:\xampp\htdocs\suratfst`.
3. Buka `http://localhost/phpmyadmin` -> tab Import -> pilih `database.sql`.
4. Cek `config/config.php` (default XAMPP: user `root`, sandi kosong, BASE_URL `/suratfst`).
5. Buka `http://localhost/suratfst/setup.php`, buat akun admin, lalu HAPUS `setup.php`.
6. Uji: daftar sebagai mahasiswa -> ajukan surat -> login admin -> periksa dokumen -> proses -> setujui -> terbitkan -> kembali ke akun mahasiswa untuk mengunduh. Uji juga tolak + ajukan ulang.

## Deploy agar bisa dipakai mahasiswa
Pilihan hosting:
- Subdomain kampus (mis. surat.namakampus.ac.id) lewat unit TIK/PTIPD kampus: paling cocok karena data mahasiswa tetap di infrastruktur kampus dan lebih mudah mendapat persetujuan fakultas. Ajukan permohonan resmi lewat dosen pengampu/Administrasi FST.
- Shared hosting berbayar (cPanel) dengan domain .id atau .ac.id: cara paling mudah bila kampus tidak menyediakan server. Cek harga terbaru di penyedia.
- Hosting gratis (mis. InfinityFree): hanya untuk demo/UAT, bukan untuk data mahasiswa sungguhan.
- VPS (Nginx/Apache + PHP-FPM + MariaDB): fleksibel, tetapi Anda yang mengurus keamanan dan pembaruan server.

Langkah di cPanel:
1. Pilih PHP 8.1 atau lebih baru (menu "Select PHP Version") dan pastikan pdo_mysql, mbstring, fileinfo aktif.
2. Menu "MySQL Databases": buat database dan user, beri semua hak akses.
3. phpMyAdmin -> pilih database tadi -> Import `database.sql` (HAPUS dulu dua baris teratas `CREATE DATABASE` dan `USE` bila hosting tidak mengizinkan).
4. Upload isi folder ke `public_html` (atau subfolder, lalu isi BASE_URL sesuai, mis. `/surat`). Isi BASE_URL `''` jika di root domain.
5. Edit `config/config.php`: DB_HOST, DB_NAME, DB_USER, DB_PASS, BASE_URL.
6. Buka `/setup.php` -> buat admin -> hapus `setup.php` dan `database.sql` dari server.
7. Aktifkan SSL (AutoSSL / Let's Encrypt), lalu aktifkan 3 baris redirect HTTPS di `.htaccess`.
8. Pastikan folder `uploads/` bisa ditulis PHP (permission 755; naikkan ke 775 bila upload gagal).
9. Jadwalkan backup: backup cPanel harian + ekspor database berkala.
10. Matikan tampilan error di produksi (`display_errors = Off` di pengaturan PHP).
Catatan: `.htaccess` berlaku untuk Apache/LiteSpeed. Di Nginx, blokir folder `uploads/`, `config/`, `includes/` lewat konfigurasi server.

## Perlu Anda sesuaikan / konfirmasi sebelum go-live
- Daftar persyaratan dokumen per jenis surat di `database.sql` adalah CONTOH. Konfirmasi dengan Ibu Titin, lalu ubah tabel `persyaratan`.
- Daftar prodi (`PRODI`) di `config.php`.
- Naikkan `upload_max_filesize` di php.ini bila PDF surat atau dokumen mahasiswa lebih dari 2 MB.

## Batasan versi ini
- Surat dibuat admin di luar sistem (Word, bertanda tangan dan stempel), lalu diunggah sebagai PDF pada halaman Terbitkan. Nomor surat diisi admin dan harus unik.
- Laporan: ekspor Excel berupa CSV (terbuka di Excel); PDF lewat cetak.
- Di luar ruang lingkup sesuai Project Charter: integrasi SIAKAD, notifikasi email/WA, peran lain.
