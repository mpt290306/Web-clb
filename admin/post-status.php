<?php
require_once __DIR__ . '/_bootstrap.php';
admin_require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') admin_redirect('posts.php');
$token = (string) ($_POST['token'] ?? '');
if ($token === '' || !hash_equals((string) ($_SESSION['post_status_token'] ?? ''), $token)) {
    admin_redirect('posts.php', 'Yêu cầu không hợp lệ. Vui lòng thử lại.', 'error');
}

$id = trim((string) ($_POST['id'] ?? ''));
if ($id === '') admin_redirect('posts.php', 'Không tìm thấy bài viết.', 'error');

$posts = admin_read_json('posts.json');
foreach ($posts as &$post) {
    if ((string) ($post['id'] ?? '') !== $id) continue;
    $post['status'] = ($post['status'] ?? 'draft') === 'published' ? 'draft' : 'published';
    $message = $post['status'] === 'draft' ? 'Đã ẩn bài viết khỏi website.' : 'Đã hiển thị bài viết trên website.';
    unset($post);
    if (!admin_write_json('posts.json', $posts)) {
        admin_redirect('posts.php', 'Không lưu được trạng thái bài viết.', 'error');
    }
    admin_redirect('posts.php', $message);
}
unset($post);
admin_redirect('posts.php', 'Không tìm thấy bài viết.', 'error');
