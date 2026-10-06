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
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        $context = stream_context_create(['http' => ['timeout' => 5]]);
        $response = @file_get_contents($url, false, $context);
        if ($response !== false) {
            $data = json_decode($response, true);
            if (is_array($data)) return $data;
        }
    }

    $query = parse_url($url, PHP_URL_QUERY);
    parse_str(is_string($query) ? $query : '', $parameters);
    $sheet = $parameters['sheet'] ?? '';
    if (!is_string($sheet) || !in_array($sheet, ['notifications', 'blog', 'sponsors', 'activities', 'defaults', 'abouts'], true)) return [];
    $path = __DIR__ . '/data/sheets/' . $sheet . '.json';
    $rows = is_file($path) ? json_decode((string) file_get_contents($path), true) : [];
    if (!is_array($rows)) return [];

    $urlPath = (string) parse_url($url, PHP_URL_PATH);
    if (str_ends_with($urlPath, '/search') && isset($parameters['id'])) {
        return array_values(array_filter($rows, fn($row) => (string) ($row['id'] ?? '') === (string) $parameters['id']));
    }
    return $rows;
}
?>
