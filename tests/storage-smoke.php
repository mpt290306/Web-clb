<?php
declare(strict_types=1);

putenv('SUPABASE_URL=');
putenv('SUPABASE_SECRET_KEY=');
$root = sys_get_temp_dir() . '/golf-storage-smoke-' . bin2hex(random_bytes(5));
putenv('APP_DATA_DIR=' . $root . '/data');
putenv('APP_UPLOAD_DIR=' . $root . '/uploads');
require_once __DIR__ . '/../storage.php';

try {
    $team = app_read_json('team.json');
    if (count($team) !== 7) throw new RuntimeException('Không khởi tạo đủ thành viên.');
    $sample = [['id' => 'smoke', 'status' => 'draft']];
    if (!app_write_json('posts.json', $sample)) throw new RuntimeException('Không ghi được dữ liệu.');
    if (app_read_json('posts.json') !== $sample) throw new RuntimeException('Dữ liệu đọc lại không khớp.');
    echo "Storage smoke test passed\n";
} finally {
    foreach (['posts.json', 'team.json', 'settings.json'] as $filename) {
        $path = $root . '/data/' . $filename;
        if (is_file($path)) unlink($path);
    }
    foreach ([$root . '/data', $root . '/uploads', $root] as $directory) {
        if (is_dir($directory)) rmdir($directory);
    }
}
