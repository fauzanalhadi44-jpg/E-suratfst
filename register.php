<?php require __DIR__ . '/includes/bootstrap.php';
if (user()) redirect('index.php');
$errors = []; $v = ['nama' => '', 'nim' => '', 'email' => '', 'no_hp' => '', 'prodi' => '', 'semester' => ''];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    foreach ($v as $k => $_) $v[$k] = trim($_POST[$k] ?? '');
    $pw = $_POST['kata_sandi'] ?? ''; $pw2 = $_POST['ulang_sandi'] ?? '';
    if ($v['nama'] === '') $errors[] = 'Nama lengkap wajib diisi.';
    if (!preg_match('/^\d{6,20}$/', $v['nim'])) $errors[] = 'NIM harus berupa angka (6-20 digit).';
    if (!filter_var($v['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Format email tidak valid.';
    if (!preg_match('/^[0-9+]{8,20}$/', $v['no_hp'])) $errors[] = 'Nomor HP tidak valid.';
    if (!in_array($v['prodi'], PRODI, true)) $errors[] = 'Pilih program studi.';
    if (!ctype_digit($v['semester']) || $v['semester'] < 1 || $v['semester'] > 14) $errors[] = 'Semester harus 1-14.';
    if (!valid_password($pw)) $errors[] = 'Kata sandi minimal 8 karakter dan memuat huruf serta angka.';
    if ($pw !== $pw2) $errors[] = 'Pengulangan kata sandi tidak sama.';
    if (!$errors) {
        if (q($pdo, 'SELECT 1 FROM pengguna WHERE nim=?', [$v['nim']])->fetch()) $errors[] = 'NIM sudah terdaftar.';
        if (q($pdo, 'SELECT 1 FROM pengguna WHERE email=?', [$v['email']])->fetch()) $errors[] = 'Email sudah terdaftar.';
    }
    if (!$errors) {
        q($pdo, "INSERT INTO pengguna(nim,nama,email,kata_sandi,no_hp,prodi,semester,role) VALUES(?,?,?,?,?,?,?,'mahasiswa')",
            [$v['nim'], $v['nama'], $v['email'], password_hash($pw, PASSWORD_DEFAULT), $v['no_hp'], $v['prodi'], (int)$v['semester']]);
        flash('ok', 'Akun berhasil dibuat. Silakan masuk.');
        redirect('login.php');
    }
}
$judul = 'Daftar';
require __DIR__ . '/includes/public_header.php'; ?>
<div class="auth"><div class="card"><h1>Daftar Akun Mahasiswa</h1>
<?php if ($errors): ?><div class="alert err"><ul><?php foreach ($errors as $x): ?><li><?= e($x) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form method="post"><?= csrf_field() ?>
<label>Nama lengkap</label><input type="text" name="nama" value="<?= e($v['nama']) ?>" required>
<label>NIM</label><input type="text" name="nim" value="<?= e($v['nim']) ?>" inputmode="numeric" required>
<label>Email</label><input type="email" name="email" value="<?= e($v['email']) ?>" required>
<label>Nomor HP</label><input type="text" name="no_hp" value="<?= e($v['no_hp']) ?>" required>
<label>Program studi</label><select name="prodi" required><option value="">-- pilih --</option>
<?php foreach (PRODI as $p): ?><option <?= $v['prodi'] === $p ? 'selected' : '' ?>><?= e($p) ?></option><?php endforeach; ?></select>
<label>Semester</label><input type="number" name="semester" min="1" max="14" value="<?= e($v['semester']) ?>" required>
<label>Kata sandi</label><input type="password" name="kata_sandi" required>
<label>Ulangi kata sandi</label><input type="password" name="ulang_sandi" required>
<p><button class="btn" type="submit" style="width:100%">Daftar</button></p></form>
<p class="muted">Sudah punya akun? <a href="<?= url('login.php') ?>">Masuk</a></p></div></div>
<?php require __DIR__ . '/includes/public_footer.php';
