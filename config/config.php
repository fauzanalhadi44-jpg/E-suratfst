<?php
// ====== SESUAIKAN BAGIAN INI ======
define('DB_HOST', 'localhost');
define('DB_NAME', 'surat_fst');
define('DB_USER', 'root');
define('DB_PASS', '');
define('BASE_URL', '/suratfst');   // tanpa slash di akhir. Isi '' jika aplikasi ada di root domain
define('APP_NAME', 'Layanan Surat FST');
// ==================================
define('UPLOAD_DIR', dirname(__DIR__) . '/uploads/');
define('MAX_UPLOAD', 2 * 1024 * 1024);      // 2 MB
const PRODI = ['Sistem Informasi', 'Matematika', 'aktuaria'];
date_default_timezone_set('Asia/Jakarta');
