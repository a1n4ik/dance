<?php
// ========== 404.php - Страница ошибки 404 Not Found ==========

// ВАЖНО: Устанавливаем правильный HTTP код 404
http_response_code(404);

// Мета-данные страницы
$page_title = "Страница не найдена - 404 | Театр танца Столица";
$page_description = "Запрашиваемая страница не найдена. Вернитесь на главную страницу Театра танца Столица.";
$page_keywords = "404, страница не найдена, театр танца столица";

// Подключаем header
require_once 'includes/header.php';
?>

<style>
    .error-404 {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        text-align: center;
        padding: 2rem 0;
    }
    
    .error-content {
        max-width: 600px;
        padding: 4rem;
    }
    
    .error-404-number {
        font-size: 8rem;
        font-weight: 900;
        line-height: 1;
        margin-bottom: 1rem;
        text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        background: linear-gradient(45deg, #ff6b6b, #feca57, #48cae4, #ff6b6b);
        background-size: 400% 400%;
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: gradientShift 3s ease infinite;
    }
    
    @keyframes gradientShift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }
    
    .error-404-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }
    
    .error-404-message {
        font-size: 1.2rem;
        margin-bottom: 2rem;
        opacity: 0.9;
        line-height: 1.6;
    }
    
    .error-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
        margin-bottom: 3rem;
    }
    
    .error-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem 2rem;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        text-decoration: none;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    .error-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }
    
    .error-btn.primary {
        background: linear-gradient(45deg, #ff6b6b, #ee5a52);
        border: none;
    }
    
    .error-btn.primary:hover {
        background: linear-gradient(45deg, #ee5a52, #ff6b6b);
    }
    
    .helpful-links {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 2rem;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .helpful-links h3 {
        margin-bottom: 1.5rem;
        font-size: 1.5rem;
    }
    
    .links-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }
    
    .link-item {
        padding: 1rem;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        text-decoration: none;
        color: white;
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .link-item:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
    }
    
    .link-item strong {
        display: block;
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
    }
    
    .link-item small {
        opacity: 0.8;
        font-size: 0.9rem;
    }
    
    .search-box {
        margin: 2rem 0;
        position: relative;
    }
    
    .search-input {
        width: 100%;
        max-width: 400px;
        padding: 1rem 1.5rem;
        border: none;
        border-radius: 50px;
        background: rgba(255, 255, 255, 0.9);
        color: #333;
        font-size: 1rem;
        outline: none;
        backdrop-filter: blur(10px);
    }
    
    .search-input::placeholder {
        color: #666;
    }
    
    .dancing-emoji {
        font-size: 3rem;
        margin: 2rem 0;
        animation: dance 2s ease-in-out infinite;
    }
    
    @keyframes dance {
        0%, 100% { transform: rotate(-10deg) scale(1); }
        50% { transform: rotate(10deg) scale(1.1); }
    }
    
    @media (max-width: 768px) {
        .error-404-number {
            font-size: 6rem;
        }
        
        .error-404-title {
            font-size: 2rem;
        }
        
        .error-404-message {
            font-size: 1.1rem;
        }
        
        .error-actions {
            flex-direction: column;
            align-items: center;
        }
        
        .links-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="error-404">
    <div class="error-content">
        <div class="error-404-number">404</div>
        <h1 class="error-404-title">Страница не найдена</h1>
        <p class="error-404-message">
            К сожалению, запрашиваемая страница не существует. 
            Возможно, она была удалена или вы перешли по неверной ссылке.
        </p>
        
        <div class="dancing-emoji">💃</div>
        
        <div class="error-actions">
            <a href="/" class="error-btn primary">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                </svg>
                На главную
            </a>
            <a href="/contacts.php" class="error-btn">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                </svg>
                Связаться с нами
            </a>
            <a href="javascript:history.back()" class="error-btn">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                </svg>
                Назад
            </a>
        </div>
        
        <div class="search-box">
            <input type="text" class="search-input" placeholder="Поиск по сайту..." 
                   onkeypress="if(event.key==='Enter') window.location.href='/?search='+encodeURIComponent(this.value)">
        </div>
        
        <div class="helpful-links">
            <h3>Полезные ссылки</h3>
            <div class="links-grid">
                <a href="/#services" class="link-item">
                    <strong>Направления</strong>
                    <small>Классический танец, народный танец, современная хореография</small>
                </a>
                <a href="/news.php" class="link-item">
                    <strong>Новости</strong>
                    <small>Последние события и достижения театра</small>
                </a>
                <a href="/projects.php" class="link-item">
                    <strong>Проекты</strong>
                    <small>Наши концерты и выступления</small>
                </a>
                <a href="/#schedule" class="link-item">
                    <strong>Расписание</strong>
                    <small>График занятий и цены</small>
                </a>
                <a href="/#about" class="link-item">
                    <strong>О театре</strong>
                    <small>История и преподаватели</small>
                </a>
                <a href="/contacts.php" class="link-item">
                    <strong>Контакты</strong>
                    <small>Адреса филиалов и способы связи</small>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Отправляем информацию о 404 ошибке в аналитику (если используется)
if (typeof gtag !== 'undefined') {
    gtag('event', 'page_not_found', {
        'page_location': window.location.href,
        'page_referrer': document.referrer
    });
}

// Автофокус на поле поиска через 2 секунды
setTimeout(() => {
    const searchInput = document.querySelector('.search-input');
    if (searchInput && window.innerWidth > 768) {
        searchInput.focus();
    }
}, 2000);

// Логирование 404 ошибки для администратора
console.warn('404 Error - Page not found:', {
    url: window.location.href,
    referrer: document.referrer,
    userAgent: navigator.userAgent,
    timestamp: new Date().toISOString()
});
</script>

<?php
// Логирование 404 ошибки в файл (опционально)
if (defined('LOG_404_ERRORS') && LOG_404_ERRORS) {
    $log_data = [
        'timestamp' => date('Y-m-d H:i:s'),
        'url' => $_SERVER['REQUEST_URI'],
        'referrer' => $_SERVER['HTTP_REFERER'] ?? 'direct',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
    ];
    
    $log_line = json_encode($log_data) . "\n";
    file_put_contents('logs/404_errors.log', $log_line, FILE_APPEND | LOCK_EX);
}

// Подключаем footer
require_once 'includes/footer.php';
?>