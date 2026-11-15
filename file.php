<?php // Menyajikan dokumen unggahan hanya kepada pemiliknya atau admin
require __DIR__ . '/includes/bootstrap.php';
$u = require_login();
$d = q($pdo, 'SELECT d.*, p.id_pengguna FROM dokumen_pengajuan d JOIN pengajuan p ON p.id_pengajuan=d.id_pengajuan WHERE d.id_dokumen=?', [(int)($_GET['id'] ?? 0)])->fetch();
if (!$d || ($u['role'] !== 'admin' && (int)$d['id_pengguna'] !== $u['id'])) { http_response_code(404); exit('Berkas tidak ditemukan.'); }
$path = UPLOAD_DIR . basename($d['nama_file']);
if (!is_file($path)) { http_response_code(404); exit('Berkas tidak ada di server.'); }
$mime = ['pdf' => 'application/pdf', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png'][$d['tipe_file']] ?? 'application/octet-stream';
header('Content-Type: ' . $mime); header('Content-Length: ' . filesize($path));
header('X-Content-Type-Options: nosniff'); header('Content-Disposition: inline; filename="dokumen.' . $d['tipe_file'] . '"');
readfile($path);
