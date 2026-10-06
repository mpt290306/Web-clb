<?php
include 'header.php';
include 'config.php'; 

// 1. Lấy dữ liệu từ Sheet (Giả sử bạn dùng chung URL_ABOUT_DATA hoặc URL_HERO_BG tùy cấu hình)
$about_data = api_read_json(URL_ABOUT_DATA);

// Hàm hỗ trợ lọc dữ liệu theo nhóm (section_key)
function get_content_by_section($data, $key) {
    return array_filter($data, function($item) use ($key) {
        return isset($item['section_key']) && $item['section_key'] === $key;
    });
}

// 2. Lấy link ảnh banner hàng 2 như đã làm ở bước trước
$settings = api_read_json(URL_HERO_BG);
$about_banner_url = $settings[1]['link'] ?? 'Image/default-about-banner.jpg'; 
?>

<link rel="stylesheet" href="CSS/about.css">

<section class="about-hero hero-section photo-hero" style="background-image: linear-gradient(rgba(61, 55, 55, 0.3), rgba(37, 36, 36, 0.3)), url('<?php echo $about_banner_url; ?>');">
    <div class="hero-content">
        <div class="hero-text">
            <h1 class="hero-title">Về Quỹ Học Bổng Chúng Tôi</h1>
            <p class="hero-subtitle">Thắp sáng ước mơ, kiến tạo tương lai.</p>
        </div>
    </div>
</section>

<main class="Introduce_contents about-main">
    
    <section class="about-grid-2">
        <div class="about-card">
            <h3 class="card-title">Tôn chỉ - Mục đích</h3>
            <ul>
                <?php 
                $principles = get_content_by_section($about_data, 'principle');
                foreach ($principles as $item) {
                    echo "<li>" . $item['content'] . "</li>";
                }
                ?>
            </ul>
        </div>

        <div class="about-card">
            <h3 class="card-title">Phạm vi - Đối tượng</h3>
            <ul>
                <?php 
                $scopes = get_content_by_section($about_data, 'scope');
                foreach ($scopes as $item) {
                    echo "<li>" . $item['content'] . "</li>";
                }
                ?>
            </ul>
        </div>
    </section>

    <section class="about-section values-section">
        <h2 class="section-title center-text">Mục tiêu</h2>
        <div class="values-grid">
            <?php 
            // Gom nhóm theo giai đoạn (Ví dụ: Giai đoạn 1, Giai đoạn 2)
            $targets = get_content_by_section($about_data, 'target');
            $grouped_targets = [];
            foreach ($targets as $t) {
                $grouped_targets[$t['category']][] = $t['content'];
            }

            foreach ($grouped_targets as $giai_doan => $list): ?>
                <div class="value-item">
                    <h4><?php echo $giai_doan; ?></h4>
                    <ul>
                        <?php foreach ($list as $li) echo "<li>" . $li . "</li>"; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="about-section orientation-section">
        <div class="orientation-header">
            <h2 class="section-title">Phương thức hoạt động</h2>
            <a href="#" class="btn btn-normal">Đồng hành cùng chúng tôi</a>
        </div>
        <div class="section-content">
            <ul>
                <?php 
                $methods = get_content_by_section($about_data, 'method');
                foreach ($methods as $m) {
                    echo "<li>" . $m['content'] . "</li>";
                }
                ?>
            </ul>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>
