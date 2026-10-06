<?php
require_once __DIR__ . '/team-data.php';
$students = team_members('student');
include 'header.php';
?>
<link rel="stylesheet" href="CSS/about.css">
<link rel="stylesheet" href="CSS/team.css">
<main class="container team-main">
    <section class="team-group">
        <h1 class="section-subtitle">Sinh viên</h1>
        <div class="team-grid">
            <?php foreach ($students as $student) team_render_card($student); ?>
            <?php if (!$students): ?><p>Chưa có thông tin sinh viên.</p><?php endif; ?>
        </div>
    </section>
</main>
<?php include 'footer.php'; ?>
