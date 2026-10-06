<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'header.php';
include 'config.php';

// 1. Lấy ID và Type từ URL
$id = isset($_GET['id']) ? $_GET['id'] : null;
$type = isset($_GET['type']) ? $_GET['type'] : 'notifications'; 
if (!$id) {
    echo "<script>alert('Không tìm thấy bài viết!'); window.history.back();</script>";
    exit;
}

$sheet_name = 'notifications'; 
if ($type === 'activities') {
    $sheet_name = 'blog';
} elseif ($type === 'sponsors') {
    $sheet_name = 'sponsors';
}

$api_detail = BASE_URL . "/search?id=" . rawurlencode((string) $id) . "&sheet=" . $sheet_name;
$data_detail = api_read_json($api_detail);
$article = $data_detail[0] ?? null;

if (!$article) {
    echo "<script>alert('Bài viết không tồn tại!'); window.history.back();</script>";
    exit;
}

$api_all = BASE_URL . "?sheet=" . $sheet_name;
$all_posts = api_read_json($api_all);

if ($all_posts) {
    $recent_posts = array_slice(array_reverse($all_posts), 0, 4);
} else {
    $recent_posts = [];
}
?>

<link rel="stylesheet" href="CSS/about.css">
<link rel="stylesheet" href="CSS/detail.css">

<main class="container" style="margin-top: 140px; margin-bottom: 80px;">
    <div class="blog-layout">
        
        <article class="article-content-wrapper">
            <img src="<?php echo $article['image']; ?>" alt="Ảnh bìa" class="article-main-image">

            <div class="article-meta-inline">
                <?php if(!empty($article['author'])): ?>
                    <span><i class="fas fa-user"></i> <?php echo strtoupper($article['author']); ?></span>
                    <span class="divider">|</span>
                <?php endif; ?>
                <span><i class="far fa-calendar-alt"></i> <?php echo $article['date']; ?></span>
            </div>

            <h1 class="article-main-title"><?php echo $article['title']; ?></h1>

            <div class="article-body">
                <?php echo $article['content']; ?>
            </div>
            
            <div style="margin-top: 40px; border-top: 1px solid #eee; padding-top: 20px;">
                <button onclick="window.history.back()" class="btn btn-outline" style="cursor: pointer; padding: 10px 20px; background: transparent; border: 1px solid #ccc; border-radius: 5px;">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </button>
            </div>
        </article>

        <aside class="sidebar">
            <div class="widget">
                <h3 class="widget-title">Bài đăng mới</h3>
                <div class="widget-content">
                    <?php 
                    $count = 0;
                    foreach ($recent_posts as $post): 
                        if ($post['id'] == $id) continue;
                        if ($count >= 4) break;
                        $count++;
                    ?>
                        <div class="sidebar-post">
                            <a href="detail.php?type=<?php echo $type; ?>&id=<?php echo $post['id']; ?>" class="sp-image">
                                <img src="<?php echo $post['image']; ?>" alt="Thumbnail">
                            </a>
                            <div class="sp-content">
                                <a href="detail.php?type=<?php echo $type; ?>&id=<?php echo $post['id']; ?>" class="sp-title">
                                    <?php echo $post['title']; ?>
                                </a>
                                <span class="sp-date"><?php echo $post['date']; ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </aside>

    </div>
</main>

<?php include 'footer.php'; ?>
