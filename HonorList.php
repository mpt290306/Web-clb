<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'header.php';
include 'config.php';

// Gọi API lấy TOÀN BỘ danh sách Mạnh Thường Quân
$api_url = URL_SPONSORS;
$sponsors_all = api_read_json($api_url);

if ($sponsors_all) {
    // Đảo ngược mảng để người mới đóng góp hiện lên đầu
    $sponsors_all = array_reverse($sponsors_all); 
} else {
    $sponsors_all = []; 
}
?>

<link rel="stylesheet" href="CSS/about.css">
<link rel="stylesheet" href="CSS/HonorList.css"> <section class="hero-section honor-list-hero" style="border-bottom: 1px solid #e5e7eb;">
    <div class="hero-content">
        <div class="hero-text honor-text" style="text-align: center; flex: 1;">
            <h1 class="hero-title1 honor-list-title" style="color: var(--primary-color, #0d3b75) !important;">TRI ÂN MẠNH THƯỜNG QUÂN</h1>
            <p class="honor-list-subtitle" style="color: var(--text-secondary); margin-top: 15px;">Chân thành cảm ơn những tấm lòng vàng đã chắp cánh ước mơ cho các em sinh viên</p>
        </div>
    </div>
</section>

<main class="honor-container">
    <div class="honor-grid">
        <?php 
        if (!empty($sponsors_all)):
            foreach ($sponsors_all as $sponsor): 
        ?>
            <div class="honor-plaque">
                <img src="<?php echo htmlspecialchars($sponsor['image']); ?>" alt="Nhà tài trợ" class="plaque-avatar">
                
                <div class="heart-icon"><i class="fas fa-hand-holding-heart"></i></div>
                
                <h3 class="sponsor-name"><?php echo htmlspecialchars($sponsor['title']); ?></h3>
                
                <p class="sponsor-label">Đã đóng góp</p>
                
                <div class="sponsor-contribution">
                    <?php echo htmlspecialchars($sponsor['excerpt']); ?>
                </div>
            </div>
        <?php 
            endforeach; 
        else:
            echo "<div class='no-data-wrapper'>
                    <i class='fas fa-box-open'></i>
                    <p>Hiện chưa có dữ liệu nhà tài trợ.</p>
                  </div>";
        endif;
        ?>
    </div>
</main>

<?php include 'footer.php'; ?>
