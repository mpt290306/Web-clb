<?php
// 1. Cấu hình cơ bản
$assetBasePath = '';
if (isset($baseHref)) {
    $trimmedBase = rtrim($baseHref, "/\\");
    $assetBasePath = $trimmedBase === '' ? '' : $trimmedBase . '/';
}

// 2. Định nghĩa Menu thống nhất
$menuDefinitions = [
    [
        'label' => 'Giới thiệu',
        'href_php' => 'About.php',
        'children' => [
            ['label' => 'Ban điều hành Quỹ', 'href_php' => 'ExecutiveBoard.php'],
            ['label' => 'Sinh viên', 'href_php' => 'Students.php'],
        ]
    ],
    [
        'label' => 'Thông báo',
        'href_php' => 'Notifications.php',
        'children' => []
    ],
    [
        'label' => 'Hoạt động',
        'href_php' => 'Activities.php',
        'children' => []
    ],
    [
        'label' => 'Blog',
        'href_php' => 'Blog.php',
        'children' => []
    ],
    [
        'label' => 'Tài trợ',
        'href_php' => 'Sponsorship.php',
        'children' => []
    ]
];

// 3. Xác định trang hiện tại để highlight
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="CSS/header.css" />
    <link rel="stylesheet" href="CSS/footer.css">
    <link rel="stylesheet" href="CSS/style.css" />
    <link rel="stylesheet" href="CSS/responsive.css" />
    <link rel="icon" type="image/png" href="<?php echo htmlspecialchars($assetBasePath . 'Image/LOGO.png'); ?>" />
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" />

    <title>CLB Golf Đa Phước và những người bạn</title>
</head>

<body>
    <header class="custom_header">
        <a href="index.php" class="logo-banner-svg-wrapper desktop-logo">
            <svg class="logo-banner-svg" viewBox="0 0 180 180" preserveAspectRatio="none">
                <path d="M 0,0 L 180,0 L 180,147 Q 180,162 165,163.5 L 15,178.5 Q 0,180 0,165 Z" fill="#6dcaec"/>
            </svg>
            <img src="Image/logodaphuoc.png" alt="Logo" class="logo-img">
        </a>

        <div class="menu-toggle" id="menuToggle">
            <span></span><span></span><span></span>
        </div>
        
        <ul class="menu nav-list" id="mobileMenu">
            <li class="mobile-menu-logo-wrapper">
                <a href="index.php" class="mobile-menu-logo">
                    <img src="Image/LOGO.png" alt="Logo Đa Phước">
                </a>
            </li>

            <?php foreach ($menuDefinitions as $item): ?>
            <li class="menu-item">
                <?php 
                    $href = $item['href_php'];
                    $isActive = ($currentPage === $href);
                ?>
                <a href="<?php echo htmlspecialchars($assetBasePath . $href); ?>" class="<?php echo $isActive ? 'active' : ''; ?>">
                    <?php if (!empty($item['icon'])): ?>
                    <i class="bi <?php echo htmlspecialchars($item['icon']); ?>"></i>
                    <?php endif; ?>
                    <span><?php echo htmlspecialchars($item['label']); ?></span>
                </a>

                <?php if (!empty($item['children'])): ?>
                <ul class="sub_menu">
                    <?php foreach ($item['children'] as $child): ?>
                    <li>
                        <a href="<?php echo htmlspecialchars($assetBasePath . $child['href_php']); ?>">
                            <?php echo htmlspecialchars($child['label']); ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </li>
            <?php endforeach; ?>
        </ul>
    </header>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menuToggle');
            const mobileMenu = document.getElementById('mobileMenu');

            if (menuToggle && mobileMenu) {
                // Mở/Đóng menu khi bấm vào nút 3 gạch
                menuToggle.addEventListener('click', function(e) {
                    e.stopPropagation(); 
                    this.classList.toggle('active');
                    mobileMenu.classList.toggle('active');
                });

                // Tự động đóng menu khi bấm ra ngoài khoảng trống
                document.addEventListener('click', function(event) {
                    if (!mobileMenu.contains(event.target) && !menuToggle.contains(event.target)) {
                        mobileMenu.classList.remove('active');
                        menuToggle.classList.remove('active');
                    }
                });
            }
        });
    </script>

    <div style="padding-top: 80px;">
