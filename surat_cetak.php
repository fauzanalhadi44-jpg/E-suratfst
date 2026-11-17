<?php // Menyajikan PDF surat terbit kepada pemilik pengajuan atau admin
require __DIR__ . '/includes/bootstrap.php';
$u = require_login();
$s = q($pdo, 'SELECT s.*, p.id_pengguna FROM surat s JOIN pengajuan p ON p.id_pengajuan=s.id_pengajuan WHERE s.id_pengajuan=?', [(int)($_GET['id'] ?? 0)])->fetch();
if (!$s || !$s['file_pdf'] || ($u['role'] !== 'admin' && (int)$s['id_pengguna'] !== $u['id'])) { http_response_code(404); exit('Surat belum terbit atau tidak ditemukan.'); }
$path = UPLOAD_DIR . basename($s['file_pdf']);
if (!is_file($path)) { http_response_code(404); exit('Berkas surat tidak ada di server.'); }
$nama = 'Surat-' . trim(preg_replace('/[^A-Za-z0-9_-]+/', '-', $s['nomor_surat']), '-') . '.pdf';
header('Content-Type: application/pdf'); header('Content-Length: ' . filesize($path)); header('X-Content-Type-Options: nosniff');
header('Content-Disposition: ' . (isset($_GET['unduh']) ? 'attachment' : 'inline') . '; filename="' . $nama . '"');
readfile($path);
