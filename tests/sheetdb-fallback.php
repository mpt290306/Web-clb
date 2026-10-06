<?php
declare(strict_types=1);

putenv('SHEETDB_API_LINK=');
require_once __DIR__ . '/../config.php';

$expected = [
    'notifications' => 2,
    'blog' => 2,
    'sponsors' => 4,
    'activities' => 4,
    'defaults' => 5,
    'abouts' => 18,
];
foreach ($expected as $sheet => $count) {
    if (count(api_read_json('?sheet=' . $sheet)) !== $count) {
        throw new RuntimeException('Thiếu dữ liệu dự phòng của ' . $sheet);
    }
}
$blog = api_read_json('?sheet=blog');
$id = (string) ($blog[0]['id'] ?? '');
if ($id === '' || count(api_read_json('/search?id=' . rawurlencode($id) . '&sheet=blog')) !== 1) {
    throw new RuntimeException('Tra cứu bài chi tiết từ dữ liệu dự phòng bị lỗi.');
}
echo "SheetDB fallback test passed\n";
