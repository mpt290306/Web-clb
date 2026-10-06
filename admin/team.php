<?php
require_once __DIR__ . '/_bootstrap.php';
admin_require_login();
$type = ($_GET['type'] ?? '') === 'student' ? 'student' : 'board';
$title = $type === 'student' ? 'Sinh viên' : 'Ban điều hành';
$members = array_values(array_filter(admin_read_json('team.json'), fn($member) => ($member['type'] ?? 'board') === $type));
admin_layout_start($title);
?>
<section class="page-heading">
    <div><p class="eyebrow">Giới thiệu</p><h1><?= admin_e($title) ?></h1><p>Quản lý hồ sơ và ảnh đại diện hiển thị trên website.</p></div>
    <a class="button primary" href="team-edit.php?type=<?= $type ?>">+ Thêm <?= $type === 'student' ? 'sinh viên' : 'thành viên' ?></a>
</section>
<div class="team-tabs">
    <a class="<?= $type === 'board' ? 'active' : '' ?>" href="team.php?type=board">Ban điều hành / Cố vấn</a>
    <a class="<?= $type === 'student' ? 'active' : '' ?>" href="team.php?type=student">Sinh viên</a>
</div>
<section class="panel table-wrap">
    <table>
        <thead><tr><th>Ảnh</th><th>Họ tên</th><th><?= $type === 'student' ? 'Trường / Ngành' : 'Nhóm' ?></th><th>Thao tác</th></tr></thead>
        <tbody>
        <?php if (!$members): ?><tr><td colspan="4" class="empty">Chưa có hồ sơ nào.</td></tr><?php endif; ?>
        <?php foreach ($members as $member): ?>
            <tr>
                <td><?php if (!empty($member['image'])): ?><img class="team-thumb" src="<?= admin_e(admin_team_image_src((string) $member['image'])) ?>" alt=""><?php else: ?><span class="team-thumb-placeholder">—</span><?php endif; ?></td>
                <td><strong><?= admin_e($member['name'] ?? '') ?></strong><small><?= admin_e($member['detail'] ?? '') ?></small></td>
                <td><?= admin_e($type === 'student' ? trim(($member['school'] ?? '') . ' · ' . ($member['major'] ?? ''), ' ·') : ($member['position'] ?? '')) ?></td>
                <td class="actions">
                    <a href="team-edit.php?id=<?= urlencode((string) $member['id']) ?>">Sửa</a>
                    <form method="post" action="team-delete.php" onsubmit="return confirm('Xóa hồ sơ này?')">
                        <input type="hidden" name="id" value="<?= admin_e($member['id']) ?>">
                        <input type="hidden" name="token" value="<?= admin_e(admin_team_token()) ?>">
                        <button class="link-danger" type="submit">Xóa</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>
<?php admin_layout_end();
