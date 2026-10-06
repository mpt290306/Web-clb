<?php
declare(strict_types=1);

session_start();

if (is_file(__DIR__ . '/../.env')) {
    foreach (file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (strpos(trim($line), '#') === 0 || strpos($line, '=') === false) continue;
        [$name, $value] = explode('=', $line, 2);
        putenv(trim($name) . '=' . trim($value, " \t\n\r\0\x0B\"'"));
    }
}
define('ADMIN_PASSWORD_HASH', getenv('ADMIN_PASSWORD_HASH') ?: '$2y$10$ujiOz48AEAmIiOZrrxUf0O7RfURKIypB67NNh3UvI1ZwAAf7PPySa');
const ADMIN_DATA_DIR = __DIR__ . '/../data';
const ADMIN_UPLOAD_DIR = __DIR__ . '/../uploads';

if (!is_dir(ADMIN_DATA_DIR)) {
    mkdir(ADMIN_DATA_DIR, 0755, true);
}
if (!is_dir(ADMIN_UPLOAD_DIR)) {
    mkdir(ADMIN_UPLOAD_DIR, 0755, true);
}

function admin_is_logged_in(): bool
{
    return !empty($_SESSION['admin_logged_in']);
}

function admin_require_login(): void
{
    if (!admin_is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function admin_e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function admin_read_json(string $filename, array $default = []): array
{
    $path = ADMIN_DATA_DIR . '/' . $filename;
    if (!is_file($path)) return $default;
    $decoded = json_decode((string) file_get_contents($path), true);
    return is_array($decoded) ? $decoded : $default;
}

function admin_write_json(string $filename, array $data): bool
{
    $path = ADMIN_DATA_DIR . '/' . $filename;
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    return $json !== false && file_put_contents($path, $json . PHP_EOL, LOCK_EX) !== false;
}

function admin_redirect(string $url, string $message = '', string $type = 'success'): never
{
    if ($message !== '') $_SESSION['admin_flash'] = ['message' => $message, 'type' => $type];
    header('Location: ' . $url);
    exit;
}

function admin_flash(): ?array
{
    $flash = $_SESSION['admin_flash'] ?? null;
    unset($_SESSION['admin_flash']);
    return $flash;
}

function admin_team_token(): string
{
    if (empty($_SESSION['team_token'])) $_SESSION['team_token'] = bin2hex(random_bytes(32));
    return (string) $_SESSION['team_token'];
}

function admin_team_check_token(): bool
{
    $submitted = (string) ($_POST['token'] ?? '');
    return $submitted !== '' && hash_equals(admin_team_token(), $submitted);
}

function admin_team_image_src(string $image): string
{
    if (preg_match('~^https?://~i', $image)) return $image;
    return '../' . ltrim($image, '/');
}

function admin_slug(string $value): string
{
    $value = trim($value);
    $value = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value) ?: $value;
    $value = strtolower($value);
    $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? '';
    return trim($value, '-') ?: 'bai-viet';
}

function admin_layout_start(string $title): void
{
    $flash = admin_flash();
    ?><!doctype html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= admin_e($title) ?> · Golf Đa Phước Admin</title>
        <link rel="stylesheet" href="admin.css">
    </head>
    <body class="admin-body">
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <a class="admin-brand" href="index.php"><span>⛳</span> Golf Đa Phước</a>
            <nav>
                <a href="index.php">Tổng quan</a>
                <a href="posts.php">Bài viết</a>
                <a href="team.php?type=board">Ban điều hành</a>
                <a href="team.php?type=student">Sinh viên</a>
                <a href="settings.php">Thông tin website</a>
                <a href="../" target="_blank">Xem website ↗</a>
            </nav>
            <a class="admin-logout" href="logout.php">Đăng xuất</a>
        </aside>
        <main class="admin-main">
            <div class="admin-topbar"><span>Trang quản trị</span><strong><?= admin_e($_SESSION['admin_username'] ?? 'Admin') ?></strong></div>
            <?php if ($flash): ?><div class="flash <?= admin_e($flash['type']) ?>"><?= admin_e($flash['message']) ?></div><?php endif; ?>
<?php
}

function admin_layout_end(): void
{
    ?></main></div></body></html><?php
}
