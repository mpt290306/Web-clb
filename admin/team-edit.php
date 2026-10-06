<?php
require_once __DIR__ . '/_bootstrap.php';
admin_require_login();

$members = admin_read_json('team.json');
$id = trim((string) ($_GET['id'] ?? $_POST['id'] ?? ''));
$index = null;
foreach ($members as $key => $item) {
    if ((string) ($item['id'] ?? '') === $id) { $index = $key; break; }
}
if ($id !== '' && $index === null) admin_redirect('team.php', 'Không tìm thấy hồ sơ.', 'error');

$type = $index !== null ? ($members[$index]['type'] ?? 'board') : (($_GET['type'] ?? $_POST['type'] ?? '') === 'student' ? 'student' : 'board');
$type = $type === 'student' ? 'student' : 'board';
$member = $index !== null ? $members[$index] : [
    'id' => 'team_' . bin2hex(random_bytes(8)),
    'type' => $type,
    'name' => '',
    'position' => 'Ban điều hành',
    'school' => '',
    'major' => '',
    'detail' => '',
    'image' => '',
];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!admin_team_check_token()) admin_redirect('team.php?type=' . $type, 'Yêu cầu không hợp lệ. Vui lòng thử lại.', 'error');
    $member['name'] = trim((string) ($_POST['name'] ?? ''));
    $member['detail'] = trim((string) ($_POST['detail'] ?? ''));
    if ($type === 'board') {
        $member['position'] = ($_POST['position'] ?? '') === 'Ban cố vấn' ? 'Ban cố vấn' : 'Ban điều hành';
    } else {
        $member['school'] = trim((string) ($_POST['school'] ?? ''));
        $member['major'] = trim((string) ($_POST['major'] ?? ''));
    }
    if ($member['name'] === '') $error = 'Vui lòng nhập họ tên.';

    if ($error === '' && isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES['image'];
        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
        if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > 5 * 1024 * 1024 || !is_uploaded_file($file['tmp_name'])) {
            $error = 'Ảnh đại diện không hợp lệ hoặc vượt quá 5 MB.';
        } else {
            $mime = mime_content_type($file['tmp_name']);
            if (!isset($allowed[$mime]) || @getimagesize($file['tmp_name']) === false) {
                $error = 'Chỉ chấp nhận ảnh JPG, PNG, WebP hoặc GIF.';
            } else {
                $filename = 'team-' . bin2hex(random_bytes(12)) . '.' . $allowed[$mime];
                if (move_uploaded_file($file['tmp_name'], ADMIN_UPLOAD_DIR . '/' . $filename)) {
                    $member['image'] = 'uploads/' . $filename;
                } else {
                    $error = 'Không lưu được ảnh đại diện.';
                }
            }
        }
    } elseif ($error === '' && !empty($_POST['remove_image'])) {
        $member['image'] = '';
    }

    if ($error === '') {
        if ($index !== null) $members[$index] = $member;
        else $members[] = $member;
        if (admin_write_json('team.json', $members)) {
            admin_redirect('team.php?type=' . $type, 'Đã lưu hồ sơ.');
        }
        $error = 'Không lưu được hồ sơ. Vui lòng thử lại.';
    }
}

$title = $index === null ? 'Thêm hồ sơ' : 'Sửa hồ sơ';
admin_layout_start($title);
?>
<section class="page-heading">
    <div><p class="eyebrow"><?= $type === 'student' ? 'Sinh viên' : 'Ban điều hành / Cố vấn' ?></p><h1><?= $title ?></h1></div>
    <a class="button ghost" href="team.php?type=<?= $type ?>">← Quay lại</a>
</section>
<?php if ($error !== ''): ?><div class="flash error"><?= admin_e($error) ?></div><?php endif; ?>
<form class="panel editor-form" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= admin_e($index !== null ? $member['id'] : '') ?>">
    <input type="hidden" name="type" value="<?= $type ?>">
    <input type="hidden" name="token" value="<?= admin_e(admin_team_token()) ?>">
    <label>Họ tên<input name="name" value="<?= admin_e($member['name'] ?? '') ?>" required></label>
    <?php if ($type === 'board'): ?>
        <label>Nhóm<select name="position">
            <option value="Ban điều hành" <?= ($member['position'] ?? '') === 'Ban điều hành' ? 'selected' : '' ?>>Ban điều hành</option>
            <option value="Ban cố vấn" <?= ($member['position'] ?? '') === 'Ban cố vấn' ? 'selected' : '' ?>>Ban cố vấn</option>
        </select></label>
    <?php else: ?>
        <div class="form-grid">
            <label>Trường / đơn vị<input name="school" value="<?= admin_e($member['school'] ?? '') ?>"></label>
            <label>Ngành học / vai trò<input name="major" value="<?= admin_e($member['major'] ?? '') ?>"></label>
        </div>
    <?php endif; ?>
    <label>Thông tin giới thiệu<textarea name="detail" rows="5"><?= admin_e($member['detail'] ?? '') ?></textarea></label>
    <label>Ảnh đại diện<input type="file" name="image" accept="image/jpeg,image/png,image/webp,image/gif"><small>JPG, PNG, WebP hoặc GIF; tối đa 5 MB.</small></label>
    <?php if (!empty($member['image'])): ?>
        <div class="team-current-image"><img src="<?= admin_e(admin_team_image_src((string) $member['image'])) ?>" alt="Ảnh đại diện hiện tại"><span>Ảnh hiện tại</span></div>
        <label class="team-remove-image"><input type="checkbox" name="remove_image" value="1"> Xóa ảnh đại diện hiện tại</label>
    <?php endif; ?>
    <button class="button primary" type="submit">Lưu hồ sơ</button>
</form>
<?php admin_layout_end();
