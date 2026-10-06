<?php
require_once __DIR__ . '/_bootstrap.php';
admin_require_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') admin_redirect('team.php');
if (!admin_team_check_token()) admin_redirect('team.php', 'Yêu cầu không hợp lệ. Vui lòng thử lại.', 'error');

$id = trim((string) ($_POST['id'] ?? ''));
$members = admin_read_json('team.json');
$type = 'board';
$found = false;
foreach ($members as $member) {
    if ((string) ($member['id'] ?? '') === $id) {
        $type = ($member['type'] ?? '') === 'student' ? 'student' : 'board';
        $found = true;
        break;
    }
}
if (!$found) admin_redirect('team.php', 'Không tìm thấy hồ sơ.', 'error');
$members = array_values(array_filter($members, fn($member) => (string) ($member['id'] ?? '') !== $id));
if (!admin_write_json('team.json', $members)) {
    admin_redirect('team.php?type=' . $type, 'Không xóa được hồ sơ.', 'error');
}
admin_redirect('team.php?type=' . $type, 'Đã xóa hồ sơ.');
