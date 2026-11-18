-- Memperbarui daftar persyaratan pada database yang SUDAH terpasang. Untuk XAMPP (MariaDB).
-- Aman dijalankan lebih dari sekali. Cara: phpMyAdmin > tab Import > pilih file ini > Import.
USE surat_fst;
ALTER TABLE persyaratan ADD COLUMN IF NOT EXISTS kondisi ENUM('semua','anak_pns','bukan_pns','nonaktif') NOT NULL DEFAULT 'semua';
ALTER TABLE persyaratan MODIFY kondisi ENUM('semua','anak_pns','bukan_pns','nonaktif') NOT NULL DEFAULT 'semua';
ALTER TABLE pengajuan ADD COLUMN IF NOT EXISTS anak_pns TINYINT(1) NOT NULL DEFAULT 0;

-- 1) Sembunyikan semua persyaratan lama (tidak dihapus, jadi data lama tetap aman)
UPDATE persyaratan SET kondisi='nonaktif' WHERE id_jenis IN (1,2,3,4,5,7);

-- 2) Aktifkan / tambahkan persyaratan yang baru
UPDATE persyaratan SET kondisi='semua' WHERE id_jenis=1 AND nama_persyaratan='Bukti pembayaran UKT';
INSERT INTO persyaratan (id_jenis, nama_persyaratan, kondisi) SELECT 1,'Bukti pembayaran UKT','semua' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM persyaratan WHERE id_jenis=1 AND nama_persyaratan='Bukti pembayaran UKT');
UPDATE persyaratan SET kondisi='semua' WHERE id_jenis=2 AND nama_persyaratan='Bukti pembayaran UKT';
INSERT INTO persyaratan (id_jenis, nama_persyaratan, kondisi) SELECT 2,'Bukti pembayaran UKT','semua' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM persyaratan WHERE id_jenis=2 AND nama_persyaratan='Bukti pembayaran UKT');
UPDATE persyaratan SET kondisi='anak_pns' WHERE id_jenis=2 AND nama_persyaratan='SK PNS orang tua';
INSERT INTO persyaratan (id_jenis, nama_persyaratan, kondisi) SELECT 2,'SK PNS orang tua','anak_pns' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM persyaratan WHERE id_jenis=2 AND nama_persyaratan='SK PNS orang tua');
UPDATE persyaratan SET kondisi='semua' WHERE id_jenis=3 AND nama_persyaratan='Bukti pembayaran UKT';
INSERT INTO persyaratan (id_jenis, nama_persyaratan, kondisi) SELECT 3,'Bukti pembayaran UKT','semua' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM persyaratan WHERE id_jenis=3 AND nama_persyaratan='Bukti pembayaran UKT');
UPDATE persyaratan SET kondisi='semua' WHERE id_jenis=4 AND nama_persyaratan='Berita acara pelaksanaan ujian skripsi';
INSERT INTO persyaratan (id_jenis, nama_persyaratan, kondisi) SELECT 4,'Berita acara pelaksanaan ujian skripsi','semua' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM persyaratan WHERE id_jenis=4 AND nama_persyaratan='Berita acara pelaksanaan ujian skripsi');
UPDATE persyaratan SET kondisi='semua' WHERE id_jenis=5 AND nama_persyaratan='Surat permohonan (dibuat mahasiswa)';
INSERT INTO persyaratan (id_jenis, nama_persyaratan, kondisi) SELECT 5,'Surat permohonan (dibuat mahasiswa)','semua' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM persyaratan WHERE id_jenis=5 AND nama_persyaratan='Surat permohonan (dibuat mahasiswa)');
UPDATE persyaratan SET kondisi='semua' WHERE id_jenis=7 AND nama_persyaratan='Surat permohonan (dibuat mahasiswa)';
INSERT INTO persyaratan (id_jenis, nama_persyaratan, kondisi) SELECT 7,'Surat permohonan (dibuat mahasiswa)','semua' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM persyaratan WHERE id_jenis=7 AND nama_persyaratan='Surat permohonan (dibuat mahasiswa)');
