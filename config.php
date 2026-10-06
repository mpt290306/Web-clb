<?php
// ==========================================
// 1. HÀM ĐỌC FILE .ENV (Dùng cho máy cá nhân)
// ==========================================
function loadEnv($path) {
    if (!file_exists($path)) return;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value, " \t\n\r\0\x0B\"'"); 
        if (getenv($name) === false) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
        }
    }
}

// Chạy hàm nạp file .env
loadEnv(__DIR__ . '/.env');
// config.php
define('API_LINK', (string) (getenv('SHEETDB_API_LINK') ?: ''));
define('BASE_URL', API_LINK);

define('URL_NOTIFICATIONS', BASE_URL . '?sheet=notifications');
define('URL_BLOG', BASE_URL . '?sheet=blog');
define('URL_BDH', BASE_URL . '?sheet=bdh');
define('URL_SPONSORS', BASE_URL . '?sheet=sponsors');
define('URL_ACTIVITIES', BASE_URL . '?sheet=activities');
define('URL_HERO_BG', BASE_URL . '?sheet=defaults');
define('URL_ABOUT_DATA', BASE_URL . '?sheet=abouts');

function api_read_json(string $url): array {
    if (!filter_var($url, FILTER_VALIDATE_URL)) return [];
    $context = stream_context_create(['http' => ['timeout' => 5]]);
    $response = @file_get_contents($url, false, $context);
    if ($response === false) return [];
    $data = json_decode($response, true);
    return is_array($data) ? $data : [];
}
?>
