-- Sistem Informasi Pengajuan dan Pemantauan Surat Mahasiswa (MySQL/MariaDB)
CREATE DATABASE IF NOT EXISTS surat_fst CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE surat_fst;

CREATE TABLE pengguna (
  id_pengguna INT AUTO_INCREMENT PRIMARY KEY,
  nim VARCHAR(20) NULL UNIQUE,
  nama VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  username VARCHAR(50) NULL UNIQUE,
  kata_sandi VARCHAR(255) NOT NULL,
  no_hp VARCHAR(20) NULL,
  prodi VARCHAR(80) NULL,
  semester TINYINT NULL,
  jabatan VARCHAR(80) NULL,
  role ENUM('mahasiswa','admin') NOT NULL,
  dibuat_pada DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE jenis_surat (
  id_jenis INT AUTO_INCREMENT PRIMARY KEY,
  nama_jenis VARCHAR(100) NOT NULL,
  deskripsi VARCHAR(255) NULL
) ENGINE=InnoDB;

CREATE TABLE persyaratan (
  id_persyaratan INT AUTO_INCREMENT PRIMARY KEY,
  id_jenis INT NOT NULL,
  nama_persyaratan VARCHAR(120) NOT NULL,
  FOREIGN KEY (id_jenis) REFERENCES jenis_surat(id_jenis) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE pengajuan (
  id_pengajuan INT AUTO_INCREMENT PRIMARY KEY,
  nomor_pengajuan VARCHAR(30) NULL UNIQUE,
  id_pengguna INT NOT NULL,
  id_jenis INT NOT NULL,
  tujuan VARCHAR(255) NOT NULL,
  keperluan TEXT NOT NULL,
  status ENUM('Menunggu','Diproses','Disetujui','Ditolak') NOT NULL DEFAULT 'Menunggu',
  catatan_admin TEXT NULL,
  alasan_penolakan TEXT NULL,
  tanggal_pengajuan DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_pengguna) REFERENCES pengguna(id_pengguna),
  FOREIGN KEY (id_jenis) REFERENCES jenis_surat(id_jenis),
  INDEX (status), INDEX (tanggal_pengajuan)
) ENGINE=InnoDB;

CREATE TABLE dokumen_pengajuan (
  id_dokumen INT AUTO_INCREMENT PRIMARY KEY,
  id_pengajuan INT NOT NULL,
  id_persyaratan INT NOT NULL,
  nama_file VARCHAR(120) NOT NULL,
  tipe_file VARCHAR(10) NOT NULL,
  tanggal_upload DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  status_pemeriksaan ENUM('belum','sesuai','tidak_sesuai') NOT NULL DEFAULT 'belum',
  catatan VARCHAR(255) NULL,
  id_pemeriksa INT NULL,
  tanggal_periksa DATETIME NULL,
  UNIQUE KEY uq_dok (id_pengajuan, id_persyaratan),
  FOREIGN KEY (id_pengajuan) REFERENCES pengajuan(id_pengajuan) ON DELETE CASCADE,
  FOREIGN KEY (id_persyaratan) REFERENCES persyaratan(id_persyaratan),
  FOREIGN KEY (id_pemeriksa) REFERENCES pengguna(id_pengguna) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE riwayat_status (
  id_riwayat INT AUTO_INCREMENT PRIMARY KEY,
  id_pengajuan INT NOT NULL,
  status VARCHAR(20) NOT NULL,
  catatan TEXT NULL,
  waktu DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  id_admin INT NULL,
  FOREIGN KEY (id_pengajuan) REFERENCES pengajuan(id_pengajuan) ON DELETE CASCADE,
  FOREIGN KEY (id_admin) REFERENCES pengguna(id_pengguna) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Surat jadi dibuat admin di luar sistem (Word, bertanda tangan/stempel), lalu diunggah sebagai PDF.
CREATE TABLE surat (
  id_surat INT AUTO_INCREMENT PRIMARY KEY,
  id_pengajuan INT NOT NULL UNIQUE,
  nomor_surat VARCHAR(60) NOT NULL UNIQUE,
  tanggal_terbit DATE NOT NULL,
  file_pdf VARCHAR(120) NULL,
  FOREIGN KEY (id_pengajuan) REFERENCES pengajuan(id_pengajuan) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE notifikasi (
  id_notifikasi INT AUTO_INCREMENT PRIMARY KEY,
  id_pengguna INT NOT NULL,
  id_pengajuan INT NULL,
  pesan VARCHAR(255) NOT NULL,
  sudah_dibaca TINYINT(1) NOT NULL DEFAULT 0,
  waktu DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX (id_pengguna, sudah_dibaca),
  FOREIGN KEY (id_pengguna) REFERENCES pengguna(id_pengguna) ON DELETE CASCADE,
  FOREIGN KEY (id_pengajuan) REFERENCES pengajuan(id_pengajuan) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO jenis_surat (nama_jenis) VALUES
('Surat Keterangan Aktif Kuliah'),
('Surat Keterangan Tidak Sedang Menerima Beasiswa'),
('Surat Keterangan Berakhlak Mulia'),
('Surat Keterangan Kelulusan'),
('Surat Izin Penelitian'),
('Surat Pengantar Kerja Praktek'),
('Surat Permohonan Izin Observasi');

-- CONTOH persyaratan: konfirmasikan daftar sebenarnya dengan Admin FST (Ibu Titin)
INSERT INTO persyaratan (id_jenis, nama_persyaratan) VALUES
(1,'Kartu Tanda Mahasiswa (KTM)'),(1,'KRS semester berjalan'),(1,'Bukti pembayaran UKT'),
(2,'Kartu Tanda Mahasiswa (KTM)'),(2,'KRS semester berjalan'),(2,'Surat pernyataan tidak menerima beasiswa'),
(3,'Kartu Tanda Mahasiswa (KTM)'),(3,'KRS semester berjalan'),(3,'Transkrip nilai sementara'),
(4,'Kartu Tanda Mahasiswa (KTM)'),(4,'Bukti yudisium / SK kelulusan'),(4,'Transkrip nilai'),
(5,'Kartu Tanda Mahasiswa (KTM)'),(5,'Proposal / outline penelitian'),(5,'Surat persetujuan pembimbing'),
(6,'Kartu Tanda Mahasiswa (KTM)'),(6,'KRS semester berjalan'),(6,'Proposal / rencana kerja praktek'),
(7,'Kartu Tanda Mahasiswa (KTM)'),(7,'KRS semester berjalan'),(7,'Rencana / instrumen observasi');
-- Akun admin dibuat lewat setup.php (sekali saja), bukan lewat SQL.
