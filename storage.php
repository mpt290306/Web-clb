<?php
declare(strict_types=1);
require_once __DIR__ . '/supabase.php';

function app_storage_path(string $envName, string $fallback): string
{
    $value = trim((string) getenv($envName));
    return $value !== '' ? rtrim($value, '/\\') : $fallback;
}

function app_data_dir(): string
{
    return app_storage_path('APP_DATA_DIR', __DIR__ . '/data');
}

function app_upload_dir(): string
{
    return app_storage_path('APP_UPLOAD_DIR', __DIR__ . '/uploads');
}

function app_ensure_storage(): void
{
    foreach ([app_data_dir(), app_upload_dir()] as $directory) {
        if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            throw new RuntimeException('Không tạo được thư mục lưu dữ liệu.');
        }
    }
    foreach (['posts.json', 'team.json', 'settings.json'] as $filename) {
        $target = app_data_dir() . '/' . $filename;
        $seed = __DIR__ . '/data/' . $filename;
        if (!is_file($target) && is_file($seed) && !copy($seed, $target)) {
            throw new RuntimeException('Không khởi tạo được dữ liệu.');
        }
    }
}

function app_read_json(string $filename, array $default = []): array
{
    if (supabase_configured()) {
        $remote = supabase_read_json($filename);
        if ($remote !== null) return $remote;
        $seed = __DIR__ . '/data/' . $filename;
        $data = is_file($seed) ? json_decode((string) file_get_contents($seed), true) : $default;
        $data = is_array($data) ? $data : $default;
        if (!supabase_write_json($filename, $data)) throw new RuntimeException('Không khởi tạo được dữ liệu lưu trữ.');
        return $data;
    }
    app_ensure_storage();
    $path = app_data_dir() . '/' . $filename;
    if (!is_file($path)) return $default;
    $decoded = json_decode((string) file_get_contents($path), true);
    return is_array($decoded) ? $decoded : $default;
}

function app_write_json(string $filename, array $data): bool
{
    if (supabase_configured()) return supabase_write_json($filename, $data);
    app_ensure_storage();
    $path = app_data_dir() . '/' . $filename;
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($json === false) return false;
    $temporary = tempnam(app_data_dir(), '.write-');
    if ($temporary === false) return false;
    $ok = file_put_contents($temporary, $json . PHP_EOL, LOCK_EX) !== false && rename($temporary, $path);
    if (!$ok && is_file($temporary)) unlink($temporary);
    return $ok;
}

function app_upload_image(string $filename, string $mime, string $temporaryPath): string|false
{
    if (supabase_configured()) return supabase_upload_image($filename, $mime, $temporaryPath);
    app_ensure_storage();
    if (!move_uploaded_file($temporaryPath, app_upload_dir() . '/' . $filename)) return false;
    return 'media.php?file=' . $filename;
}
