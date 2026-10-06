<?php
require_once 'content.php';
include 'header.php';
$id = (string) ($_GET['id'] ?? '');
$all_posts = public_posts();
$article = null;
foreach ($all_posts as $post) if ((string) ($post['id'] ?? '') === $id) { $article = $post; break; }
if (!$article) { echo '<main class="container" style="padding:80px 20px"><h1>Bài viết không tồn tại.</h1><a href="Blog.php">Quay lại Blog</a></main>'; include 'footer.php'; exit; }
$recent_posts = array_slice(array_values(array_filter(array_reverse($all_posts), fn($post) => (string) ($post['id'] ?? '') !== $id)), 0, 4);
$label_class = ($article['role'] ?? '') === 'Sinh viên' ? 'badge-stu' : 'badge-adm';
$author_initial = mb_strtoupper(mb_substr((string) ($article['author'] ?? ''), 0, 1, 'UTF-8'), 'UTF-8');
?>
<link rel="stylesheet" href="CSS/about.css"><link rel="stylesheet" href="CSS/detail_blog.css">
<main class="journal-page"><div class="journal-layout"><div class="journal-article"><div class="journal-cover-image"><img src="<?= htmlspecialchars($article['image'] ?? '') ?>" alt="Ảnh bìa bài viết"></div><div class="journal-body-paper"><div class="quote-sticker"><i class="fas fa-heart" style="color:#e67e22"></i><br><?= htmlspecialchars(mb_substr(strip_tags((string) ($article['excerpt'] ?? $article['content'] ?? '')), 0, 55, 'UTF-8')) ?>...</div><div class="journal-meta"><span class="journal-role-badge <?= $label_class ?>"><?= htmlspecialchars($article['role'] ?? '') ?></span><span class="journal-author-name"><i class="fas fa-feather-alt"></i> <?= htmlspecialchars($article['author'] ?? '') ?></span><span class="journal-date-stamp"><i class="far fa-calendar-alt"></i> <?= htmlspecialchars($article['date'] ?? '') ?></span></div><h1 class="journal-title"><?= htmlspecialchars($article['title'] ?? '') ?></h1><div class="journal-content-wrap"><div class="journal-content"><?= nl2br(htmlspecialchars($article['content'] ?? '')) ?></div></div><div class="journal-actions"><a href="Blog.php" class="btn-back"><i class="fas fa-arrow-left"></i> Quay lại Blog</a></div></div></div><aside class="journal-sidebar"><div class="author-card"><div class="author-avatar-placeholder"><?= htmlspecialchars($author_initial) ?></div><p class="author-card-name"><?= htmlspecialchars($article['author'] ?? '') ?></p><p class="author-card-role"><?= htmlspecialchars($article['role'] ?? '') ?></p></div><?php if ($recent_posts): ?><div class="sticky-note-widget"><h3 class="widget-heading"><i class="fas fa-thumbtack"></i> Bài đăng gần đây</h3><?php foreach ($recent_posts as $post): ?><a href="detail_blog.php?id=<?= urlencode((string) $post['id']) ?>" class="recent-post-item"><div class="rp-thumb"><img src="<?= htmlspecialchars($post['image'] ?? '') ?>" alt=""></div><div class="rp-info"><p class="rp-title"><?= htmlspecialchars($post['title'] ?? '') ?></p><span class="rp-date"><?= htmlspecialchars($post['date'] ?? '') ?></span></div></a><?php endforeach; ?></div><?php endif; ?></aside></div></main>
<?php include 'footer.php'; ?>
