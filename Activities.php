<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'header.php';
include 'config.php';

// 1. Lấy dữ liệu bài viết hoạt động
$api_url = URL_BLOG;
$activities = api_read_json($api_url);

if ($activities) {
    $activities = array_reverse($activities); 
} else {
    $activities = []; 
}

$settings = api_read_json(URL_HERO_BG);
$activities_banner_url = $settings[3]['link'] ?? ''; 
?>

<link rel="stylesheet" href="CSS/about.css">
<link rel="stylesheet" href="CSS/activities.css"> 

<style>
    .hero-section {
    position: relative;
    background-image: linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.2)), url('<?php echo $activities_banner_url; ?>');
    background-size: cover;
    background-position: center 50%;
    min-height: 400px; 
    overflow: hidden; 
}
</style>

<section class="hero-section text-center">
    <div class="hero-content">
        <div class="hero-text full-width">
            <h1 class="hero-title">Hoạt Động</h1>
        </div>
    </div>
</section>

<main class="container" style="margin-bottom: 80px;">
    <div class="timeline-container">
        <?php 
        if (!empty($activities)):
            foreach ($activities as $index => $act): 
                $detail_link = "detail.php?type=activities&id=" . $act['id']; 
                // Kiểm tra chẵn lẻ để xếp so le trái - phải
                $position_class = ($index % 2 == 0) ? 'left' : 'right';
        ?>
            <div class="timeline-item <?php echo $position_class; ?>">
                <div class="timeline-content">
                    <span class="time-badge"><?php echo $act['date']; ?></span>
                    
                    <a href="<?php echo $detail_link; ?>" class="timeline-image-link" style="margin-top: 15px;">
                        <img src="<?php echo $act['image']; ?>" alt="Hình hoạt động" class="timeline-img">
                    </a>
                    
                    <a href="<?php echo $detail_link; ?>">
                        <h3><?php echo $act['title']; ?></h3>
                    </a>
                    
                    <p><?php echo $act['excerpt']; ?></p>
                </div>
            </div>
        <?php 
            endforeach; 
        else:
            echo "<p style='text-align: center; color: #666; padding: 50px 0;'>Hiện chưa có bài viết hoạt động nào.</p>";
        endif;
        ?>
    </div>
</main>

<?php include 'footer.php'; ?>
