<?php
require_once __DIR__ . '/_bootstrap.php';
admin_require_login();
$posts = array_reverse(admin_read_json('posts.json'));
if (empty($_SESSION['post_status_token'])) {
    $_SESSION['post_status_token'] = bin2hex(random_bytes(32));
}
admin_layout_start('Bài viết');
?>
<section class="page-heading">
    <div><p class="eyebrow">Nội dung</p><h1>Bài viết</h1><p>Tạo và chỉnh sửa bài đăng hiển thị trên website.</p></div>
    <a class="button primary" href="post-edit.php">+ Đăng bài mới</a>
</section>
<section class="panel table-wrap">
    <table>
        <thead><tr><th>Tiêu đề</th><th>Ngày</th><th>Trạng thái</th><th>Thao tác</th></tr></thead>
        <tbody>
        <?php if (!$posts): ?>
            <tr><td colspan="4" class="empty">Chưa có bài viết.</td></tr>
        <?php else: foreach ($posts as $post):
            $published = ($post['status'] ?? 'draft') === 'published';
        ?>
            <tr>
                <td><strong><?= admin_e($post['title']) ?></strong><small><?= admin_e($post['excerpt'] ?? '') ?></small></td>
                <td><?= admin_e($post['date'] ?? '') ?></td>
                <td><span class="status <?= $published ? 'published' : 'draft' ?>"><?= $published ? 'Đã đăng' : 'Bản nháp / đã ẩn' ?></span></td>
                <td class="actions">
                    <a href="post-edit.php?id=<?= urlencode((string) $post['id']) ?>">Sửa</a>
                    <form method="post" action="post-status.php">
                        <input type="hidden" name="id" value="<?= admin_e($post['id']) ?>">
                        <input type="hidden" name="token" value="<?= admin_e($_SESSION['post_status_token']) ?>">
                        <button type="submit" class="link-action"><?= $published ? 'Ẩn' : 'Hiện' ?></button>
                    </form>
                    <form method="post" action="post-delete.php" onsubmit="return confirm('Xóa bài viết này?')">
                        <input type="hidden" name="id" value="<?= admin_e($post['id']) ?>">
                        <button type="submit" class="link-danger">Xóa</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</section>
<?php admin_layout_end();
