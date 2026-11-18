
DELETE FROM surat;   -- hapus surat percobaan lama; pengajuannya kembali "Belum terbit"
ALTER TABLE surat DROP INDEX uq_urut, DROP COLUMN tahun, DROP COLUMN urutan, DROP COLUMN penandatangan;
