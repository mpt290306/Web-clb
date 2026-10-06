<?php
require_once __DIR__ . '/_bootstrap.php';
admin_require_login();
$posts = admin_read_json('posts.json');
$settings = admin_read_json('settings.json');
admin_layout_start('Tổng quan');
?>
<section class="page-heading"><div><p class="eyebrow">Xin chào, <?= admin_e($_SESSION['admin_username'] ?? 'Admin') ?></p><h1>Tổng quan</h1><p>Quản lý nội dung website không cần database.</p></div><a class="button primary" href="post-edit.php">+ Đăng bài mới</a></section>
<div class="stats-grid"><div class="stat-card"><span>Bài viết</span><strong><?= count($posts) ?></strong></div><div class="stat-card"><span>Đã xuất bản</span><strong><?= count(array_filter($posts, fn($p) => ($p['status'] ?? '') === 'published')) ?></strong></div><div class="stat-card"><span>Ảnh đã tải</span><strong><?= is_dir(ADMIN_UPLOAD_DIR) ? count(array_diff(scandir(ADMIN_UPLOAD_DIR), ['.', '..'])) : 0 ?></strong></div></div>
<section class="panel"><div class="panel-heading"><h2>Thông tin hiện tại</h2><a href="settings.php">Chỉnh sửa</a></div><div class="info-grid"><div><span>Tên website</span><strong><?= admin_e($settings['site_name'] ?? 'Chưa thiết lập') ?></strong></div><div><span>Thông báo</span><strong><?= admin_e($settings['notice'] ?? 'Chưa thiết lập') ?></strong></div></div></section>
<section class="panel"><div class="panel-heading"><h2>Bài viết gần đây</h2><a href="posts.php">Xem tất cả</a></div><?php if (!$posts): ?><p class="empty">Chưa có bài viết. Hãy tạo bài đầu tiên.</p><?php else: ?><div class="recent-list"><?php foreach (array_slice(array_reverse($posts), 0, 5) as $post): ?><a href="post-edit.php?id=<?= admin_e($post['id']) ?>"><span><?= admin_e($post['title']) ?></span><small><?= admin_e($post['date'] ?? '') ?></small></a><?php endforeach; ?></div><?php endif; ?></section>
<?php admin_layout_end();
