<?php include 'header.php'; ?>
<?php include 'config.php'; ?>

<?php
$settings = api_read_json(URL_HERO_BG);
$sponsor_banner_url = $settings[3]['link'] ?? 'Image/default-sponsor.jpg'; 
?>

<link rel="stylesheet" href="CSS/about.css">
<link rel="stylesheet" href="CSS/sponsorship.css">

<section class="hero-section text-center" style="background-image: linear-gradient(rgba(87, 81, 81, 0.3), rgba(97, 88, 88, 0.3)), url('<?php echo $sponsor_banner_url; ?>'); background-size: cover; background-position: center 60%;">
    <div class="hero-content">
        <div class="hero-text full-width">
            <h1 class="hero-title">Chung Tay Tiếp Sức Ước Mơ</h1>
            <p class="hero-subtitle">Mỗi đóng góp của bạn, dù lớn hay nhỏ, đều là một viên gạch vững chắc xây dựng tương lai tươi sáng cho các em học sinh, sinh viên hiếu học có hoàn cảnh khó khăn.</p>
        </div>
    </div>
</section>

<main class="container donate-main">
    
    <section class="donate-section">
        <h2 class="section-title center-text">Vì sao chúng tôi cần bạn?</h2>
        <div class="section-content text-center">
            <p>Hiện nay, vẫn còn rất nhiều tài năng trẻ phải gác lại giấc mơ giảng đường vì gánh nặng tài chính. Quỹ Học Bổng được lập ra để làm cầu nối giữa những tấm lòng vàng và các em học sinh. Toàn bộ 100% số tiền tài trợ sẽ được chuyển trực tiếp thành các suất học bổng định kỳ, chi phí sinh hoạt và dụng cụ học tập.</p>
        </div>
    </section>

    <section class="donate-packages">
        <h2 class="section-title center-text">Các chương trình tài trợ</h2>
        <div class="packages-grid">
            
            <div class="package-card">
                <h3 class="package-name">Đồng Hành Nhỏ</h3>
                <div class="package-amount">Tùy tâm</div>
                <p class="package-desc">Đóng góp một phần vào quỹ chung. Số tiền này sẽ được gộp lại để tạo thành các suất học bổng trao đầu năm học.</p>
            </div>

            <div class="package-card highlight">
                <h3 class="package-name">Nuôi Em Tới Trường</h3>
                <div class="package-amount">500.000 VNĐ<span>/tháng</span></div>
                <p class="package-desc">Bảo trợ chi phí sinh hoạt cơ bản hàng tháng cho một sinh viên trong suốt một năm học. Bạn sẽ nhận được báo cáo học tập của em định kỳ.</p>
                <div class="package-badge">Được đề xuất</div>
            </div>

            <div class="package-card">
                <h3 class="package-name">Học Bổng Toàn Phần</h3>
                <div class="package-amount">15.000.000 VNĐ<span>/năm</span></div>
                <p class="package-desc">Đài thọ toàn bộ học phí 1 năm học cho một sinh viên xuất sắc. Bạn/Doanh nghiệp của bạn sẽ được vinh danh trên bảng vàng của Quỹ.</p>
            </div>

        </div>
    </section>

    <section class="donate-methods">
        <h2 class="section-title center-text">Phương thức ủng hộ</h2>
        
        <div class="transfer-box">
            <div class="transfer-info">
                <h3>Chuyển khoản Ngân hàng</h3>
                <p class="note">Vui lòng ghi rõ nội dung chuyển khoản để chúng tôi có thể gửi thư cảm ơn và ghi nhận đóng góp của bạn một cách chính xác nhất.</p>
                
                <table class="bank-details">
                    <tr>
                        <td>Ngân hàng:</td>
                        <th>Ngân hàng TMCP Ngoại thương Việt Nam (Vietcombank)</th>
                    </tr>
                    <tr>
                        <td>Tên tài khoản:</td>
                        <th>QUY HOC BONG CHUNG TOI</th>
                    </tr>
                    <tr>
                        <td>Số tài khoản:</td>
                        <th class="account-number">0123 4567 8910</th>
                    </tr>
                    <tr>
                        <td>Chi nhánh:</td>
                        <th>Chi nhánh Sở Giao Dịch</th>
                    </tr>
                    <tr>
                        <td>Nội dung:</td>
                        <th class="transfer-syntax">HOVTEN_SODIENTHOAI_TAITRO</th>
                    </tr>
                </table>
            </div>
            
            <div class="qr-code-box">
                <div class="qr-placeholder">
                    [MÃ QR CHUYỂN KHOẢN]
                </div>
                <p>Quét mã bằng ứng dụng ngân hàng</p>
            </div>
        </div>
    </section>

</main>

<?php include 'footer.php'; ?>
