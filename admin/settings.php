<?php
require_once __DIR__ . '/_bootstrap.php';
admin_require_login();
$settings = array_merge(['site_name' => 'CLB Golf Đa Phước', 'notice' => '', 'contact' => '', 'facebook' => ''], admin_read_json('settings.json'));
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!admin_form_check_token()) admin_redirect('settings.php', 'Yêu cầu không hợp lệ.', 'error');
    $settings['site_name'] = trim($_POST['site_name'] ?? '');
    $settings['notice'] = trim($_POST['notice'] ?? '');
    $settings['contact'] = trim($_POST['contact'] ?? '');
    $settings['facebook'] = trim($_POST['facebook'] ?? '');
    if (!admin_write_json('settings.json', $settings)) admin_redirect('settings.php', 'Không lưu được thông tin website.', 'error');
    admin_redirect('settings.php', 'Đã cập nhật thông tin website.');
}
admin_layout_start('Thông tin website');
?>
<section class="page-heading"><div><p class="eyebrow">Cài đặt</p><h1>Thông tin website</h1><p>Quản lý các thông tin cơ bản, sẵn sàng để kết nối vào giao diện public.</p></div></section>
<form class="panel editor-form" method="post"><input type="hidden" name="token" value="<?= admin_e(admin_form_token()) ?>"><label>Tên website<input name="site_name" value="<?= admin_e($settings['site_name']) ?>"></label><label>Thông báo / slogan<textarea name="notice" rows="3"><?= admin_e($settings['notice']) ?></textarea></label><label>Thông tin liên hệ<textarea name="contact" rows="4"><?= admin_e($settings['contact']) ?></textarea></label><label>Facebook<input name="facebook" value="<?= admin_e($settings['facebook']) ?>"></label><button class="button primary" type="submit">Lưu thông tin</button></form>
<?php admin_layout_end();
