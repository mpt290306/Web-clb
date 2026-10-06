<?php
require_once __DIR__ . '/_bootstrap.php';
admin_require_login();
$posts = admin_read_json('posts.json');
$id = trim($_GET['id'] ?? $_POST['id'] ?? '');
$post = ['id' => uniqid('post_', true), 'title' => '', 'excerpt' => '', 'content' => '', 'image' => '', 'author' => 'CLB Golf Đa Phước', 'role' => 'CLB Golf Đa Phước', 'date' => date('d/m/Y'), 'status' => 'draft'];
foreach ($posts as $item) if (($item['id'] ?? '') === $id) $post = array_merge($post, $item);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!admin_form_check_token()) admin_redirect('posts.php', 'Yêu cầu không hợp lệ.', 'error');
    $post['title'] = trim($_POST['title'] ?? '');
    $post['excerpt'] = trim($_POST['excerpt'] ?? '');
    $post['content'] = trim($_POST['content'] ?? '');
    $post['author'] = trim($_POST['author'] ?? 'CLB Golf Đa Phước');
    $post['role'] = trim($_POST['role'] ?? 'CLB Golf Đa Phước');
    $post['date'] = trim($_POST['date'] ?? date('d/m/Y'));
    $post['status'] = ($_POST['status'] ?? 'draft') === 'published' ? 'published' : 'draft';
    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES['image'];
        if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > 5 * 1024 * 1024 || !is_uploaded_file($file['tmp_name'])) {
            admin_redirect('post-edit.php' . ($id ? '?id=' . urlencode($id) : ''), 'Ảnh không hợp lệ hoặc vượt quá 5 MB.', 'error');
        }
        $mime = mime_content_type($file['tmp_name']);
        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
        if (!isset($allowed[$mime]) || @getimagesize($file['tmp_name']) === false) {
            admin_redirect('post-edit.php' . ($id ? '?id=' . urlencode($id) : ''), 'Chỉ chấp nhận ảnh JPG, PNG, WebP hoặc GIF.', 'error');
        }
        $filename = 'post-' . bin2hex(random_bytes(12)) . '.' . $allowed[$mime];
        $imageUrl = app_upload_image($filename, $mime, $file['tmp_name']);
        if ($imageUrl === false) {
            admin_redirect('post-edit.php' . ($id ? '?id=' . urlencode($id) : ''), 'Không lưu được ảnh.', 'error');
        }
        $post['image'] = $imageUrl;
    }
    if ($post['title'] === '') { admin_redirect('post-edit.php' . ($id ? '?id=' . urlencode($id) : ''), 'Vui lòng nhập tiêu đề.', 'error'); }
    $found = false;
    foreach ($posts as $key => $item) if (($item['id'] ?? '') === $post['id']) { $posts[$key] = $post; $found = true; }
    if (!$found) $posts[] = $post;
    if (!admin_write_json('posts.json', $posts)) admin_redirect('posts.php', 'Không lưu được bài viết.', 'error');
    admin_redirect('posts.php', 'Đã lưu bài viết.');
}
admin_layout_start($id ? 'Sửa bài viết' : 'Đăng bài mới');
?>
<section class="page-heading"><div><p class="eyebrow">Nội dung</p><h1><?= $id ? 'Sửa bài viết' : 'Đăng bài mới' ?></h1></div><a class="button ghost" href="posts.php">← Quay lại</a></section>
<form class="panel editor-form" method="post" enctype="multipart/form-data"><input type="hidden" name="token" value="<?= admin_e(admin_form_token()) ?>"><input type="hidden" name="id" value="<?= admin_e($post['id']) ?>"><div class="form-grid"><label>Tiêu đề<input name="title" value="<?= admin_e($post['title']) ?>" required></label><label>Ngày đăng<input name="date" value="<?= admin_e($post['date']) ?>"></label><label>Tác giả<input name="author" value="<?= admin_e($post['author']) ?>"></label><label>Nhãn bài viết<input name="role" value="<?= admin_e($post['role']) ?>"></label></div><label>Tóm tắt<textarea name="excerpt" rows="3"><?= admin_e($post['excerpt']) ?></textarea></label><label>Nội dung bài viết<textarea name="content" rows="12" placeholder="Nhập nội dung bài viết..."><?= admin_e($post['content']) ?></textarea></label><div class="form-grid"><label>Ảnh đại diện<input type="file" name="image" accept="image/jpeg,image/png,image/webp,image/gif"><small>Tối đa 5MB. <?= $post['image'] ? 'Ảnh hiện tại: ' . admin_e($post['image']) : '' ?></small></label><label>Trạng thái<select name="status"><option value="draft" <?= $post['status'] === 'draft' ? 'selected' : '' ?>>Bản nháp</option><option value="published" <?= $post['status'] === 'published' ? 'selected' : '' ?>>Đã đăng</option></select></label></div><button class="button primary" type="submit">Lưu bài viết</button></form>
<?php admin_layout_end();
