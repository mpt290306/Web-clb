<?php
require_once __DIR__ . '/_bootstrap.php';
admin_require_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') admin_redirect('posts.php');
$id = trim($_POST['id'] ?? '');
$posts = array_values(array_filter(admin_read_json('posts.json'), fn($post) => ($post['id'] ?? '') !== $id));
admin_write_json('posts.json', $posts);
admin_redirect('posts.php', 'Đã xóa bài viết.');
