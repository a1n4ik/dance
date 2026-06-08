<?php
/**
 * Видео виджет для footer сайта
 * Настройки виджета
 */

// Конфигурация виджета
$widget_config = [
    'enabled' => true,
    'video_url' => '/assets/videos/promo-video.mp4', // Замените на ваш видеофайл
    'video_type' => 'video/mp4',
    'widget_label' => 'Получить подарок!',
    'show_on_mobile' => true,
    'lazy_load' => true,
    'analytics_enabled' => true,
    // Страницы, где НЕ показывать виджет
    'exclude_pages' => ['checkout', 'payment', 'admin'],
    // Минимальное время на странице перед показом (мс)
    'show_delay' => 2000
];

// Проверяем, нужно ли показывать виджет
function shouldShowWidget($config) {
    // Проверяем, включен ли виджет
    if (!$config['enabled']) return false;
    
    // Проверяем мобильные устройства
    if (!$config['show_on_mobile'] && isMobileDevice()) return false;
    
    // Проверяем исключенные страницы
    $current_page = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
    foreach ($config['exclude_pages'] as $excluded) {
        if (strpos($current_page, $excluded) !== false) return false;
    }
    
    return true;
}

function isMobileDevice() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

// Показываем виджет только если разрешено
if (shouldShowWidget($widget_config)):
?>

<!-- Подключение стилей виджета -->
<link rel="stylesheet" href="/assets/css/video-widget.css">

<!-- Видео виджет -->
<div class="video-widget" id="videoWidget" style="display: none;">
    <div class="video-container" id="videoContainer">
        <div class="loading-spinner" id="loadingSpinner"></div>
        <video 
            id="widgetVideo" 
            loop 
            muted 
            playsinline 
            preload="metadata"
            poster="/assets/images/video-poster.jpg"
        >
            <source src="<?php echo htmlspecialchars($widget_config['video_url']); ?>" type="<?php echo htmlspecialchars($widget_config['video_type']); ?>">
            <!-- Дополнительные форматы для лучшей совместимости -->
            <source src="<?php echo str_replace('.mp4', '.webm', $widget_config['video_url']); ?>" type="video/webm">
            <p>Ваш браузер не поддерживает видео.</p>
        </video>
        
        <div class="video-overlay">
            <div class="play-button">
                <div class="play-icon"></div>
            </div>
        </div>
        
        <div class="close-button" id="closeButton">×</div>
        
        <div class="volume-indicator">
            🔊 Звук включен
        </div>
        
        <button class="whatsapp-button" id="whatsappButton">
            <svg class="whatsapp-icon" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488z"/>
            </svg>
            Записаться
        </button>
        
        <div class="widget-label"><?php echo htmlspecialchars($widget_config['widget_label']); ?></div>
    </div>
</div>

<!-- Подключение JavaScript виджета -->
<script src="/assets/js/video-widget.js"></script>

<script>
// Конфигурация для JavaScript
window.videoWidgetConfig = <?php echo json_encode([
    'showDelay' => $widget_config['show_delay'],
    'analyticsEnabled' => $widget_config['analytics_enabled'],
    'isMobile' => isMobileDevice()
]); ?>;

// Инициализация с задержкой
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const widget = document.getElementById('videoWidget');
        if (widget) {
            widget.style.display = 'block';
            
            // Инициализируем виджет после отображения
            if (typeof initVideoWidget === 'function') {
                initVideoWidget();
            }
        }
    }, window.videoWidgetConfig.showDelay);
});

<?php if ($widget_config['analytics_enabled']): ?>
// Настройка аналитики
window.yandexMetricaId = <?php echo defined('YANDEX_METRICA_ID') ? YANDEX_METRICA_ID : 'null'; ?>;
<?php endif; ?>
</script>

<?php endif; ?>

<?php
/**
 * Дополнительные функции для управления виджетом
 */

// Функция для логирования показов виджета
function logWidgetShow($page = '') {
    if (empty($page)) {
        $page = $_SERVER['REQUEST_URI'];
    }
    
    $log_data = [
        'timestamp' => date('Y-m-d H:i:s'),
        'page' => $page,
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
        'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
        'referer' => $_SERVER['HTTP_REFERER'] ?? ''
    ];
    
    // Сохраняем в лог файл или базу данных
    $log_file = __DIR__ . '/logs/widget-shows.log';
    if (is_writable(dirname($log_file))) {
        file_put_contents($log_file, json_encode($log_data) . "\n", FILE_APPEND | LOCK_EX);
    }
}

// AJAX обработчик для событий виджета
if (isset($_POST['action']) && $_POST['action'] === 'widget_event') {
    header('Content-Type: application/json');
    
    $event_type = $_POST['event_type'] ?? '';
    $page = $_POST['page'] ?? $_SERVER['REQUEST_URI'];
    
    $response = ['status' => 'success'];
    
    switch ($event_type) {
        case 'widget_shown':
            logWidgetShow($page);
            break;
        case 'widget_expanded':
            // Логика для расширения виджета
            break;
        case 'widget_clicked':
            // Перенаправление на целевую страницу
            $response['redirect'] = '/promo-page/';
            break;
    }
    
    echo json_encode($response);
    exit;
}
?>