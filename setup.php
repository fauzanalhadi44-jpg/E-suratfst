<?php // Jalankan SEKALI untuk membuat akun admin pertama, lalu HAPUS file ini dari server.
require __DIR__ . '/includes/bootstrap.php';
$ada = (int)$pdo->query("SELECT COUNT(*) FROM pengguna WHERE role='admin'")->fetchColumn() > 0;
$msg = ''; $err = '';
if (!$ada && $_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $n = trim($_POST['nama'] ?? ''); $us = trim($_POST['username'] ?? ''); $em = trim($_POST['email'] ?? ''); $pw = $_POST['kata_sandi'] ?? '';
    if ($n === '' || !preg_match('/^[A-Za-z0-9_.-]{3,30}$/', $us) || !filter_var($em, FILTER_VALIDATE_EMAIL) || !valid_password($pw)) {
        $err = 'Data tidak valid (username 3-30 karakter; sandi min. 8 karakter huruf+angka).';
    } else {
        q($pdo, "INSERT INTO pengguna(nama,email,username,kata_sandi,jabatan,role) VALUES(?,?,?,?,'Staf Akademik','admin')",
            [$n, $em, $us, password_hash($pw, PASSWORD_DEFAULT)]);
        $ada = true; $msg = 'Admin dibuat. HAPUS file setup.php dari server sekarang.';
    }
}
$judul = 'Setup Admin';
require __DIR__ . '/includes/public_header.php'; ?>
<div class="auth"><div class="card"><h1>Setup Admin</h1>
<?php if ($msg): ?><div class="alert ok"><?= e($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert err"><?= e($err) ?></div><?php endif; ?>
<?php if ($ada): ?><p>Akun admin sudah ada. Hapus file <code>setup.php</code> dari server.</p>
<?php else: ?><form method="post"><?= csrf_field() ?>
<label>Nama</label><input type="text" name="nama" required><label>Username</label><input type="text" name="username" required>
<label>Email</label><input type="email" name="email" required><label>Kata sandi</label><input type="password" name="kata_sandi" required>
<p><button class="btn" type="submit">Buat Admin</button></p></form><?php endif; ?>
</div></div>
<?php require __DIR__ . '/includes/public_footer.php';
