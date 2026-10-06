<?php
require_once __DIR__ . '/team-data.php';
$members = team_members('board');
$board = array_values(array_filter($members, fn($member) => ($member['position'] ?? '') === 'Ban điều hành'));
$advisors = array_values(array_filter($members, fn($member) => ($member['position'] ?? '') === 'Ban cố vấn'));
include 'header.php';
?>
<link rel="stylesheet" href="CSS/about.css">
<link rel="stylesheet" href="CSS/team.css">
<main class="container team-main">
    <section class="team-group">
        <h1 class="section-subtitle">Ban Điều Hành</h1>
        <div class="team-grid">
            <?php foreach ($board as $member) team_render_card($member); ?>
            <?php if (!$board): ?><p>Chưa có thành viên Ban điều hành.</p><?php endif; ?>
        </div>
    </section>
    <hr class="section-divider">
    <section class="team-group">
        <h2 class="section-subtitle">Ban Cố Vấn</h2>
        <div class="team-grid">
            <?php foreach ($advisors as $member) team_render_card($member); ?>
            <?php if (!$advisors): ?><p>Chưa có thành viên Ban cố vấn.</p><?php endif; ?>
        </div>
    </section>
</main>
<?php include 'footer.php'; ?>
