<?php
require_once __DIR__ . '/_bootstrap.php';
if (admin_is_logged_in()) { header('Location: index.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = (string) ($_POST['password'] ?? '');
    $validPassword = ADMIN_PASSWORD !== ''
        ? hash_equals(ADMIN_PASSWORD, $password)
        : (ADMIN_PASSWORD_HASH !== '' && password_verify($password, ADMIN_PASSWORD_HASH));
    if ($username === 'admin' && $validPassword) {
        session_regenerate_id(true);
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        header('Location: index.php');
        exit;
    }
    $error = 'Tên đăng nhập hoặc mật khẩu không đúng.';
}
?><!doctype html>
<html lang="vi"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Đăng nhập quản trị</title><link rel="stylesheet" href="admin.css"></head>
<body class="login-body"><div class="login-card"><div class="login-logo">⛳</div><h1>Golf Đa Phước</h1><p>Đăng nhập trang quản trị</p><?php if ($error): ?><div class="flash error"><?= admin_e($error) ?></div><?php endif; ?><form method="post"><label>Tên đăng nhập<input name="username" required autofocus></label><label>Mật khẩu<input type="password" name="password" required></label><button class="button primary" type="submit">Đăng nhập</button></form></div></body></html>
