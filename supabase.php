<?php
declare(strict_types=1);

function supabase_configured(): bool
{
    $url = (string) getenv('SUPABASE_URL');
    $key = (string) getenv('SUPABASE_SECRET_KEY');
    if ($url === '' && $key === '') return false;
    if ($url === '' || $key === '' || !str_starts_with($url, 'https://')) {
        throw new RuntimeException('Cấu hình lưu trữ chưa đầy đủ.');
    }
    return true;
}

function supabase_request(string $method, string $path, ?string $body = null, string $contentType = 'application/json'): array
{
    $base = rtrim((string) getenv('SUPABASE_URL'), '/');
    $key = (string) getenv('SUPABASE_SECRET_KEY');
    $headers = [
        'apikey: ' . $key,
        'Authorization: Bearer ' . $key,
        'Content-Type: ' . $contentType,
    ];
    if ($method === 'POST' && str_starts_with($path, '/rest/v1/')) {
        $headers[] = 'Prefer: resolution=merge-duplicates,return=minimal';
    }
    $context = stream_context_create(['http' => [
        'method' => $method,
        'header' => implode("\r\n", $headers),
        'content' => $body ?? '',
        'timeout' => 8,
        'ignore_errors' => true,
    ]]);
    $response = @file_get_contents($base . $path, false, $context);
    $statusLine = $http_response_header[0] ?? '';
    preg_match('/^HTTP\/\S+\s+(\d{3})/', $statusLine, $matches);
    return [(int) ($matches[1] ?? 0), $response === false ? '' : $response];
}

function supabase_read_json(string $filename): ?array
{
    $path = '/rest/v1/site_data?name=eq.' . rawurlencode($filename) . '&select=payload';
    [$status, $body] = supabase_request('GET', $path);
    if ($status !== 200) throw new RuntimeException('Không đọc được dữ liệu từ nơi lưu trữ.');
    $rows = json_decode($body, true);
    if (!is_array($rows)) throw new RuntimeException('Dữ liệu lưu trữ không hợp lệ.');
    if ($rows === []) return null;
    if (!isset($rows[0]['payload']) || !is_array($rows[0]['payload'])) {
        throw new RuntimeException('Dữ liệu lưu trữ không hợp lệ.');
    }
    return $rows[0]['payload'];
}

function supabase_write_json(string $filename, array $data): bool
{
    $body = json_encode(['name' => $filename, 'payload' => $data], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($body === false) return false;
    [$status] = supabase_request('POST', '/rest/v1/site_data?on_conflict=name', $body);
    return $status >= 200 && $status < 300;
}

function supabase_upload_image(string $filename, string $mime, string $temporaryPath): string|false
{
    $bucket = 'site-media';
    $bytes = file_get_contents($temporaryPath);
    if ($bytes === false) return false;
    [$status] = supabase_request('POST', '/storage/v1/object/' . $bucket . '/' . rawurlencode($filename), $bytes, $mime);
    if ($status < 200 || $status >= 300) return false;
    return rtrim((string) getenv('SUPABASE_URL'), '/') . '/storage/v1/object/public/' . $bucket . '/' . rawurlencode($filename);
}
