<?php
// ========== config/errors.php - Конфигурация обработки ошибок ==========

// Настройки логирования 404 ошибок
define('LOG_404_ERRORS', true);
define('LOG_404_PATH', __DIR__ . '/../logs/404_errors.log');

// Создаем папку для логов если не существует
$logDir = dirname(LOG_404_PATH);
if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}

/**
 * Функция для логирования 404 ошибок
 */
function log404Error($additional_data = []) {
    if (!LOG_404_ERRORS) return;
    
    $log_data = [
        'timestamp' => date('Y-m-d H:i:s'),
        'url' => $_SERVER['REQUEST_URI'] ?? '',
        'method' => $_SERVER['REQUEST_METHOD'] ?? 'GET',
        'referrer' => $_SERVER['HTTP_REFERER'] ?? 'direct',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'query_string' => $_SERVER['QUERY_STRING'] ?? '',
    ];
    
    // Добавляем дополнительные данные если переданы
    if (!empty($additional_data)) {
        $log_data = array_merge($log_data, $additional_data);
    }
    
    $log_line = json_encode($log_data, JSON_UNESCAPED_UNICODE) . "\n";
    
    try {
        file_put_contents(LOG_404_PATH, $log_line, FILE_APPEND | LOCK_EX);
    } catch (Exception $e) {
        error_log("Failed to write 404 log: " . $e->getMessage());
    }
}

/**
 * Функция для отправки уведомления администратору (опционально)
 */
function notify404Error($threshold = 10) {
    static $errorCount = 0;
    $errorCount++;
    
    // Если достигнут порог ошибок за сессию, отправляем уведомление
    if ($errorCount >= $threshold) {
        // Здесь можно добавить отправку email или уведомления в Telegram
        // Пример: отправка в лог для администратора
        error_log("WARNING: High number of 404 errors detected ($errorCount) from IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
        $errorCount = 0; // Сбрасываем счетчик
    }
}

/**
 * Проверка подозрительных запросов (возможные атаки)
 */
function checkSuspiciousRequest() {
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    
    $suspicious_patterns = [
        '/\.php\?.*eval\(/i',           // PHP инъекции
        '/\.\./i',                      // Directory traversal
        '/union.*select/i',             // SQL инъекции
        '/script.*alert/i',             // XSS попытки
        '/wp-admin|wp-login/i',         // WordPress атаки
        '/phpmyadmin/i',                // phpMyAdmin атаки
        '/\.env$/i',                    // Попытки доступа к .env файлам
        '/config\.php/i',               // Доступ к конфигам
    ];
    
    foreach ($suspicious_patterns as $pattern) {
        if (preg_match($pattern, $uri)) {
            // Логируем подозрительную активность
            log404Error([
                'type' => 'suspicious_request',
                'pattern_matched' => $pattern,
                'severity' => 'high'
            ]);
            
            // Можно добавить блокировку IP или другие меры безопасности
            return true;
        }
    }
    
    return false;
}

/**
 * Улучшенная функция для отправки 404 с дополнительными проверками
 */
function send404WithChecks($reason = 'page_not_found') {
    // Проверяем на подозрительную активность
    $is_suspicious = checkSuspiciousRequest();
    
    // Логируем ошибку
    log404Error([
        'reason' => $reason,
        'is_suspicious' => $is_suspicious
    ]);
    
    // Уведомляем о высокой активности
    notify404Error();
    
    // Устанавливаем правильный HTTP код
    http_response_code(404);
    
    // Если это подозрительный запрос, можно вернуть более скромный ответ
    if ($is_suspicious) {
        header('X-Robots-Tag: noindex, nofollow');
        exit('Not Found');
    }
    
    // Иначе показываем нормальную 404 страницу
    include __DIR__ . '/../404.php';
    exit;
}

/**
 * Автоматическая очистка старых логов
 */
function cleanOldLogs($days = 30) {
    if (!file_exists(LOG_404_PATH)) return;
    
    $lines = file(LOG_404_PATH, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (!$lines) return;
    
    $cutoff = time() - ($days * 24 * 60 * 60);
    $filtered_lines = [];
    
    foreach ($lines as $line) {
        $data = json_decode($line, true);
        if ($data && isset($data['timestamp'])) {
            $timestamp = strtotime($data['timestamp']);
            if ($timestamp > $cutoff) {
                $filtered_lines[] = $line;
            }
        }
    }
    
    // Перезаписываем файл с отфильтрованными данными
    file_put_contents(LOG_404_PATH, implode("\n", $filtered_lines) . "\n");
}

/**
 * Функция для получения статистики 404 ошибок
 */
function get404Stats($days = 7) {
    if (!file_exists(LOG_404_PATH)) {
        return ['total' => 0, 'by_day' => [], 'top_urls' => [], 'top_referrers' => []];
    }
    
    $lines = file(LOG_404_PATH, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (!$lines) return ['total' => 0, 'by_day' => [], 'top_urls' => [], 'top_referrers' => []];
    
    $cutoff = time() - ($days * 24 * 60 * 60);
    $by_day = [];
    $urls = [];
    $referrers = [];
    $total = 0;
    
    foreach ($lines as $line) {
        $data = json_decode($line, true);
        if (!$data || !isset($data['timestamp'])) continue;
        
        $timestamp = strtotime($data['timestamp']);
        if ($timestamp <= $cutoff) continue;
        
        $total++;
        $day = date('Y-m-d', $timestamp);
        $by_day[$day] = ($by_day[$day] ?? 0) + 1;
        
        $url = $data['url'] ?? 'unknown';
        $urls[$url] = ($urls[$url] ?? 0) + 1;
        
        $referrer = $data['referrer'] ?? 'direct';
        if ($referrer !== 'direct') {
            $referrers[$referrer] = ($referrers[$referrer] ?? 0) + 1;
        }
    }
    
    arsort($urls);
    arsort($referrers);
    
    return [
        'total' => $total,
        'by_day' => $by_day,
        'top_urls' => array_slice($urls, 0, 10, true),
        'top_referrers' => array_slice($referrers, 0, 10, true)
    ];
}

// Автоматическая очистка старых логов при каждом 100-м запросе
if (rand(1, 100) === 1) {
    cleanOldLogs(30);
}
?>