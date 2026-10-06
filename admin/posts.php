<?php
require_once __DIR__ . '/_bootstrap.php';
admin_require_login();
$posts = array_reverse(admin_read_json('posts.json'));
admin_layout_start('Bài viết');
?>
<section class="page-heading"><div><p class="eyebrow">Nội dung</p><h1>Bài viết</h1><p>Tạo và chỉnh sửa bài đăng hiển thị trên website.</p></div><a class="button primary" href="post-edit.php">+ Đăng bài mới</a></section>
<section class="panel table-wrap"><table><thead><tr><th>Tiêu đề</th><th>Ngày</th><th>Trạng thái</th><th></th></tr></thead><tbody><?php if (!$posts): ?><tr><td colspan="4" class="empty">Chưa có bài viết.</td></tr><?php else: foreach ($posts as $post): ?><tr><td><strong><?= admin_e($post['title']) ?></strong><small><?= admin_e($post['excerpt'] ?? '') ?></small></td><td><?= admin_e($post['date'] ?? '') ?></td><td><span class="status <?= admin_e($post['status'] ?? 'draft') ?>"><?= ($post['status'] ?? 'draft') === 'published' ? 'Đã đăng' : 'Bản nháp' ?></span></td><td class="actions"><a href="post-edit.php?id=<?= admin_e($post['id']) ?>">Sửa</a><form method="post" action="post-delete.php" onsubmit="return confirm('Xóa bài viết này?')"><input type="hidden" name="id" value="<?= admin_e($post['id']) ?>"><button type="submit" class="link-danger">Xóa</button></form></td></tr><?php endforeach; endif; ?></tbody></table></section>
<?php admin_layout_end();
