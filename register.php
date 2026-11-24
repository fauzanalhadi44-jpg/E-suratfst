<?php
// ====== GANTI BAGIAN INI dengan logika PHP register.php kamu yang lama ======
// (session_start, koneksi DB, proses $_POST, validasi, insert, dsb.)
// Pastikan atribut name="" di form bawah sama dengan yang dipakai kode lamamu.
$error = $error ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Daftar - Layanan Surat FST</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  :root { --hijau:#475421; --hijau-tua:#3b461b; --teks:#1c1f14; --abu:#6b7280; --garis:#d9dccf; }
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Inter', system-ui, sans-serif; color: var(--teks); min-height: 100vh; display: flex; }

  /* ===== Panel kiri ===== */
  .kiri { flex: 0 0 45%; background: var(--hijau); color: #fff; padding: 40px 42px;
          display: flex; flex-direction: column; justify-content: space-between; position: sticky; top: 0; height: 100vh; }
  .logo { display: flex; align-items: center; gap: 12px; font-weight: 700; font-size: 18px; }
  .logo span { width: 36px; height: 36px; border-radius: 10px; background: rgba(255,255,255,.18);
               display: inline-flex; align-items: center; justify-content: center; font-size: 18px; }
  .kiri h1 { font-size: 36px; line-height: 1.2; font-weight: 800; margin-bottom: 14px; }
  .kiri p { font-size: 16px; line-height: 1.55; opacity: .92; max-width: 330px; }
  .kiri ul { list-style: none; margin-top: 26px; }
  .kiri li { display: flex; gap: 12px; align-items: center; margin-bottom: 14px; font-size: 15px; }
  .kiri li::before { content: "✓"; font-weight: 700; }
  .kiri .bawah { margin-bottom: 40px; }

  /* ===== Panel kanan ===== */
  .kanan { flex: 1; padding: 40px 6vw; overflow-y: auto; background: #fff; }
  .form-wrap { max-width: 590px; margin: 0 auto; }
  .kanan h2 { font-size: 32px; font-weight: 800; }
  .sub { color: var(--abu); font-size: 15px; margin: 6px 0 28px; }

  label { display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; }
  .field { margin-bottom: 20px; }
  .row { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }

  input[type=text], input[type=email], input[type=password], input[type=tel], select {
    width: 100%; height: 50px; padding: 0 18px; font: inherit; font-size: 15px; color: var(--teks);
    background: #fff; border: 1px solid var(--garis); border-radius: 999px; outline: none;
    transition: border-color .15s, box-shadow .15s;
  }
  select { appearance: none; padding-right: 44px;
    background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%23333' stroke-width='2.5'><path d='M6 9l6 6 6-6'/></svg>");
    background-repeat: no-repeat; background-position: right 18px center; }
  input::placeholder { color: #9ca3af; }
  input:focus, select:focus { border-color: var(--hijau); box-shadow: 0 0 0 3px rgba(71,84,33,.15); }

  .hint { font-size: 12.5px; color: var(--abu); margin: -12px 0 18px 18px; }
  .cek { display: flex; gap: 12px; align-items: flex-start; font-size: 14px; color: #374151; margin: 6px 0 24px; }
  .cek input { margin-top: 3px; accent-color: var(--hijau); }

  .btn { display: block; width: 290px; max-width: 100%; margin: 0 auto; height: 50px; border: 0; border-radius: 999px;
         background: var(--hijau); color: #fff; font: inherit; font-weight: 700; font-size: 16px; cursor: pointer; }
  .btn:hover { background: var(--hijau-tua); }
  .masuk { text-align: center; font-size: 14px; color: var(--abu); margin-top: 18px; }
  .masuk a { color: var(--hijau); font-weight: 600; text-decoration: none; }
  .error { background: #fdecec; color: #9b1c1c; border-radius: 12px; padding: 12px 16px; font-size: 14px; margin-bottom: 20px; }

  /* ===== HP ===== */
  @media (max-width: 860px) {
    body { flex-direction: column; }
    .kiri { position: static; height: auto; flex: none; padding: 28px; gap: 24px; }
    .kiri .bawah { margin: 0; }
    .kiri h1 { font-size: 28px; }
    .kanan { padding: 28px 22px; }
  }
  @media (max-width: 520px) { .row { grid-template-columns: 1fr; gap: 0; } }
</style>
</head>
<body>

<aside class="kiri">
  <div class="logo"><span>➤</span> E-Surat FST</div>

  <div class="bawah">
    <h1>Buat akun<br>mahasiswa Anda.</h1>
    <p>Satu akun untuk mengajukan, memantau, dan mengunduh surat dari Administrasi FST.</p>
    <ul>
      <li>Tujuh jenis surat dalam satu formulir</li>
      <li>Status dan catatan admin terlihat langsung</li>
      <li>Surat final diunduh sebagai PDF</li>
    </ul>
  </div>
</aside>

<main class="kanan">
  <div class="form-wrap">
    <h2>Daftar</h2>
    <p class="sub">Isi data sesuai identitas mahasiswa Anda. Semua kolom wajib diisi.</p>

    <?php if (!empty($error)): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="">
      <div class="field">
        <label for="nama">Nama lengkap</label>
        <input type="text" id="nama" name="nama" placeholder="Sesuai data kampus" required>
      </div>

      <div class="row">
        <div class="field">
          <label for="nim">NIM</label>
          <input type="text" id="nim" name="nim" placeholder="contoh: 271028619" required>
        </div>
        <div class="field">
          <label for="no_hp">Nomor HP</label>
          <input type="tel" id="no_hp" name="no_hp" placeholder="0972836857" required>
        </div>
      </div>

      <div class="field">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="nama@gmail.com" required>
      </div>

      <div class="row">
        <div class="field">
          <label for="prodi">Program studi</label>
          <select id="prodi" name="prodi" required>
            <option value="" disabled selected>Pilih program studi</option>
            <!-- GANTI dengan daftar prodi yang sudah ada di kodemu -->
            <option>Sistem Informasi</option>
            <option>Matematika</option>
            <option>Biologi</option>
            <option>Kimia</option>
            <option>Fisika</option>
          </select>
        </div>
        <div class="field">
          <label for="semester">Semester</label>
          <select id="semester" name="semester" required>
            <option value="" disabled selected>Pilih semester</option>
            <?php for ($i = 1; $i <= 14; $i++): ?>
              <option value="<?= $i ?>"><?= $i ?></option>
            <?php endfor; ?>
          </select>
        </div>
      </div>

      <div class="row">
        <div class="field">
          <label for="password">Kata sandi</label>
          <input type="password" id="password" name="password" placeholder="Minimal 8 karakter" minlength="8" required>
        </div>
        <div class="field">
          <label for="password2">Ulangi kata sandi</label>
          <input type="password" id="password2" name="password2" placeholder="Ketik ulang kata sandi" minlength="8" required>
        </div>
      </div>
      <p class="hint">Gabungan huruf dan angka</p>

      <label class="cek">
        <input type="checkbox" name="setuju" required>
        <span>Data yang saya isi benar dan saya setujui digunakan untuk keperluan layanan surat FST.</span>
      </label>

      <button type="submit" class="btn">Daftar</button>
      <p class="masuk">Sudah punya akun? <a href="login.php">Masuk</a></p>
    </form>
  </div>
</main>

</body>
</html>
