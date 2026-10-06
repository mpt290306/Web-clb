<?php
require_once __DIR__ . '/_bootstrap.php';
admin_require_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') admin_redirect('posts.php');
if (!admin_form_check_token()) admin_redirect('posts.php', 'Yêu cầu không hợp lệ.', 'error');
$id = trim($_POST['id'] ?? '');
$posts = array_values(array_filter(admin_read_json('posts.json'), fn($post) => ($post['id'] ?? '') !== $id));
if (!admin_write_json('posts.json', $posts)) admin_redirect('posts.php', 'Không xóa được bài viết.', 'error');
admin_redirect('posts.php', 'Đã xóa bài viết.');
