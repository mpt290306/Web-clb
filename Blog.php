<?php
require_once 'content.php';
include 'header.php';
$blog_posts = array_reverse(public_posts());
?>
<link rel="stylesheet" href="CSS/about.css">
<link rel="stylesheet" href="CSS/blog.css">
<main class="scrapbook-container">
    <header class="scrapbook-header"><h1 class="scrapbook-title">Blog</h1><p class="scrapbook-desc">Nơi những tâm hồn đồng điệu gửi gắm tâm tình</p><div class="cta-wrapper"><button class="btn-write-handwritten"><i class="fas fa-feather-alt"></i> Gửi tâm tình của bạn</button></div></header>
    <section class="gallery-wall">
        <?php foreach ($blog_posts as $index => $post): $rotation = rand(-3, 3) . 'deg'; ?>
            <div class="polaroid-wrapper" style="--rotation: <?= htmlspecialchars($rotation) ?>; animation-delay: <?= 0.3 + ($index * 0.15) ?>s;"><article class="polaroid-card"><div class="polaroid-image"><img src="<?= htmlspecialchars($post['image'] ?? '') ?>" alt="Kỷ niệm"><div class="polaroid-label <?= ($post['role'] ?? '') === 'Sinh viên' ? 'label-stu' : 'label-adm' ?>"><?= htmlspecialchars($post['role'] ?? '') ?></div></div><div class="polaroid-body"><h2 class="polaroid-title"><?= htmlspecialchars($post['title'] ?? '') ?></h2><p class="polaroid-text">“<?= htmlspecialchars($post['excerpt'] ?? '') ?>”</p><a href="detail_blog.php?id=<?= urlencode((string) ($post['id'] ?? '')) ?>" class="read-more-link">Xem thêm...</a><div class="polaroid-footer"><span class="polaroid-author">— <?= htmlspecialchars($post['author'] ?? '') ?></span><span class="polaroid-date"><?= htmlspecialchars($post['date'] ?? '') ?></span></div></div></article></div>
        <?php endforeach; if (!$blog_posts): ?><p style="text-align:center;width:100%;">Chưa có bài viết được xuất bản.</p><?php endif; ?>
    </section>
</main>
<?php include 'footer.php'; ?>
