<?php
// ========== includes/header.php - Общий заголовок сайта ==========

// Определяем текущую страницу для активных пунктов меню
$current_page = basename($_SERVER['PHP_SELF'], '.php');
$current_url = $_SERVER['REQUEST_URI'];

// Функция для определения активного пункта меню
function isActive($page, $current) {
    if ($page === 'home' && ($current === 'index' || $current === '')) {
        return 'active';
    }
    if ($page === 'contacts' && $current === 'contacts') {
        return 'active';
    }
    if ($page === 'news' && $current === 'news') {
        return 'active';
    }
    if ($page === 'projects' && $current === 'projects') {
        return 'active';
    }
    if ($page === 'reviews' && $current === 'reviews') {
        return 'active';
    }
    if ($page === 'schedule' && $current === 'schedule') {
        return 'active';
    }
    // Для страниц направлений
    if ($page === 'services' && in_array($current, ['classical-dance', 'folk-dance', 'jazz-modern', 'baby-ballet', 'gymnastics', 'acrobatics'])) {
        return 'active';
    }
    return '';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#03A9F4">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="default">
	<meta name="yandex-verification" content="f27a78764e2443e6" />
    <!-- SEO мета-теги (переопределяются на каждой странице) -->
    <?php if (!isset($page_title)): ?>
        <title>Театр танца "Столица" - Танцевальная школа в Москве</title>
        <meta name="description" content="Театр танца Столица - профессиональное обучение классическому танцу, народному танцу, современной хореографии. Педагоги - солисты театра Гжель.">
        <meta name="keywords" content="театр танца, балет, классический танец, народный танец, москва, дети, хореография">
    <?php else: ?>
        <title><?= htmlspecialchars($page_title) ?></title>
        <?php if (isset($page_description)): ?>
            <meta name="description" content="<?= htmlspecialchars($page_description) ?>">
        <?php endif; ?>
        <?php if (isset($page_keywords)): ?>
            <meta name="keywords" content="<?= htmlspecialchars($page_keywords) ?>">
        <?php endif; ?>
    <?php endif; ?>
    
    <!-- Open Graph мета-теги -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Театр танца Столица">
    <meta property="og:title" content="<?= isset($page_title) ? htmlspecialchars($page_title) : 'Театр танца "Столица"' ?>">
    <meta property="og:description" content="<?= isset($page_description) ? htmlspecialchars($page_description) : 'Профессиональное обучение танцам в Москве' ?>">
    <meta property="og:image" content="https://stolitsa-dance.ru/wp-content/uploads/2025/07/logo_new.png">
    <meta property="og:url" content="https://<?= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="https://<?= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
    <link rel="apple-touch-icon" href="/assets/images/apple-touch-icon.png">
    
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">
    <link rel="stylesheet" href="/assets/css/main.css">
	<link rel="stylesheet" href="/assets/css/home.css">
    <?php if (isset($additional_css)): ?>
        <?php foreach ($additional_css as $css_file): ?>
            <link rel="stylesheet" href="<?= htmlspecialchars($css_file) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    <!-- ⭐ НОВЫЙ адаптивный CSS - добавить в самом конце -->
	<link rel="stylesheet" href="/assets/css/responsive.css">
    <!-- Дополнительные стили для текущей страницы -->
    <?php if (isset($page_styles)): ?>
        <style><?= $page_styles ?></style>
    <?php endif; ?>
    
    <!-- Стили для игровой ссылки -->
    <style>
    .game-nav-link {
        display: flex !important;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 1rem !important;
        border-radius: 20px;
        background: linear-gradient(135deg, #FF7AB8 0%, #03A9F4 100%);
        color: white !important;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(255, 122, 184, 0.3);
        animation: gameGlow 3s ease-in-out infinite alternate;
    }
    
    .game-nav-link:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(255, 122, 184, 0.5);
        text-decoration: none;
        color: white !important;
    }
    
    .game-nav-link:active {
        transform: translateY(0);
    }
    
    @keyframes gameGlow {
        0% { box-shadow: 0 2px 10px rgba(255, 122, 184, 0.3); }
        100% { box-shadow: 0 2px 15px rgba(3, 169, 244, 0.4); }
    }
    
    .game-icon {
        font-size: 1.2em;
        animation: gameIconPulse 2s ease-in-out infinite;
    }
    
    @keyframes gameIconPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
    
    /* Мобильная версия */
    @media (max-width: 768px) {
        .mobile-game-link {
            background: linear-gradient(135deg, #FF7AB8 0%, #03A9F4 100%);
            color: white !important;
            font-weight: 600;
            padding: 0.8rem 1rem;
            border-radius: 10px;
            margin: 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 2px 10px rgba(255, 122, 184, 0.3);
        }
        
        .mobile-game-link:hover {
            color: white !important;
            text-decoration: none;
        }
        
        .game-nav-link .game-text {
            display: none;
        }
        
        .game-nav-link {
            padding: 0.5rem !important;
            min-width: 40px;
            justify-content: center;
        }
    }
    
    @media (max-width: 480px) {
        .game-nav-link .game-text {
            display: none;
        }
    }
    </style>
    
    <!-- JSON-LD разметка -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "DanceSchool",
        "name": "Театр танца Столица",
        "description": "Профессиональное обучение классическому танцу, народному танцу, современной хореографии",
        "url": "https://stolitsa-dance.ru",
        "logo": "https://stolitsa-dance.ru/wp-content/uploads/2025/07/logo_new.png",
        "telephone": "+7-915-413-43-47",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "Москва",
            "addressCountry": "RU"
        },
        "sameAs": [
            "https://instagram.com/stolitsa_dance",
            "https://t.me/theatrestolitsa"
        ]
    }
    </script>
	<script type="text/javascript">
(function() {
    // Создаем счетчик через изображение (работает всегда)
    var metrikaImg = new Image();
    metrikaImg.src = 'https://mc.yandex.ru/watch/103716322?page-url=' + 
                     encodeURIComponent(location.href) + 
                     '&rn=' + Math.random();
    metrikaImg.style.cssText = 'position:absolute;left:-9999px;width:1px;height:1px;';
    
    // Добавляем в DOM
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            document.body.appendChild(metrikaImg);
        });
    } else {
        document.body.appendChild(metrikaImg);
    }
    
    // Функция ym для совместимости
    window.ym = function(id, method, goal) {
        if (method === 'reachGoal' && goal) {
            var goalImg = new Image();
            goalImg.src = 'https://mc.yandex.ru/watch/103716322?ut=' + goal + '&rn=' + Math.random();
            console.log('🎯 Цель отправлена:', goal);
        }
    };
    
    console.log('✅ Яндекс.Метрика активна');
})();
</script>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="nav-container">
            <div class="logo-container">
                <a href="/" class="logo-image" aria-label="Театр танца Столица - Главная страница">
                    <img src="https://stolitsa-dance.ru/wp-content/uploads/2025/07/logo_new.png" 
                         alt="Театр танца Столица" 
                         width="120" 
                         height="auto">
                </a>
            </div>
            
            <ul class="nav-menu" role="navigation" aria-label="Основное меню">
                <li><a href="/" class="<?= isActive('home', $current_page) ?>">Главная</a></li>
                <li class="nav-dropdown">
                    <a href="/#services" class="<?= isActive('services', $current_page) ?>">Направления</a>
                    <ul class="nav-dropdown-menu">
                        <li><a href="/classical-dance.php">Классический танец</a></li>
                        <li><a href="/folk-dance.php">Народный танец</a></li>
                        <li><a href="/jazz-modern.php">Джаз-модерн</a></li>
                        <li><a href="/baby-ballet.php">Танцевальная практика</a></li>
                        <li><a href="/gymnastics.php">Партерная гимнастика</a></li>
                        <li><a href="/acrobatics.php">Акробатика</a></li>
                    </ul>
                </li>
                <li><a href="/#schedule" class="<?= isActive('schedule', $current_page) ?>">Расписание</a></li>
                <li><a href="/#about">О театре</a></li>
                <li><a href="/#pricing">Цены</a></li>
                <li><a href="/contacts.php" class="<?= isActive('contacts', $current_page) ?>">Контакты</a></li>
                
                <!-- Ссылка на игру -->
                <li>
                    <a href="https://stolitsa-dance.ru/game.html" class="game-nav-link" title="Ритм Столицы - Развивающая игра" target="_blank">
                        <span class="game-icon">🎮</span>
                        <span class="game-text">Игра</span>
                    </a>
                </li>
            </ul>
            
            <!-- Мобильное меню -->
            <div class="menu-burger" aria-label="Меню" tabindex="0">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </nav>
    </header>

    <!-- Мобильное меню overlay -->
    <div class="mobile-menu-overlay">
        <div class="mobile-menu">
            <div class="mobile-menu-header">
                <img src="https://stolitsa-dance.ru/wp-content/uploads/2025/07/logo_new.png" 
                     alt="Театр танца Столица" 
                     class="mobile-logo">
                <button class="mobile-menu-close">&times;</button>
            </div>
            <ul class="mobile-menu-list">
                <li><a href="/">Главная</a></li>
                <li class="mobile-submenu">
                     <a href="#" class="submenu-toggle">Направления <span class="submenu-arrow">▼</span></a>
                    <ul class="mobile-submenu-list">
                        <li><a href="/classical-dance.php">Классический танец</a></li>
                        <li><a href="/folk-dance.php">Народный танец</a></li>
                        <li><a href="/jazz-modern.php">Джаз-модерн</a></li>
                        <li><a href="/baby-ballet.php">Танцевальная практика</a></li>
                        <li><a href="/gymnastics.php">Партерная гимнастика</a></li>
                        <li><a href="/acrobatics.php">Акробатика</a></li>
                    </ul>
                </li>
                <li><a href="/#schedule">Расписание</a></li>
                <li><a href="/#about">О театре</a></li>
                <li><a href="/#pricing">Цены</a></li>
                <li><a href="/contacts.php">Контакты</a></li>
                
                <!-- Ссылка на игру в мобильном меню -->
                <li>
                    <a href="https://stolitsa-dance.ru/game.html" class="mobile-game-link" target="_blank">
                        <span class="game-icon">🎮</span>
                        <span>Ритм Столицы - Игра</span>
                    </a>
                </li>
            </ul>
            <div class="mobile-menu-contacts">
                <a href="tel:+79163942321" class="mobile-phone">+7 (916) 394-23-21</a>
				<a href="tel:+79154134347" class="mobile-phone">+7 (915) 413-43-47</a>
                <div class="mobile-social">
                    <a href="https://wa.me/79154134347" target="_blank" aria-label="WhatsApp">📱</a>
                    <a href="https://t.me/theatrestolitsa" target="_blank" aria-label="Telegram">📞</a>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Основной контент страницы начинается здесь -->
    <main class="main-content">