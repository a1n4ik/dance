<?php
// ========== router.php - Простой роутер для обработки страниц ==========

/**
 * Простой роутер для проверки существования страниц
 * Можно подключить в начале каждой PHP страницы
 */

class PageRouter {
    
    private static $validPages = [
        // Основные страницы
        'index.php' => true,
        'contacts.php' => true,
        'news.php' => true,
        'projects.php' => true,
        
        // Страницы направлений
        'classical-dance.php' => true,
        'folk-dance.php' => true,
        'jazz-modern.php' => true,
        'baby-ballet.php' => true,
        'gymnastics.php' => true,
        'acrobatics.php' => true,
        
        // Служебные страницы
        'sitemap.php' => true,
        'privacy.php' => true,
        'terms.php' => true,
        '404.php' => true,
    ];
    
    /**
     * Проверяет существование текущей страницы
     * @return bool
     */
    public static function checkCurrentPage() {
        $currentPage = basename($_SERVER['PHP_SELF']);
        
        // Если страница в списке разрешенных
        if (isset(self::$validPages[$currentPage])) {
            return true;
        }
        
        // Проверяем физическое существование файла
        $filePath = $_SERVER['DOCUMENT_ROOT'] . $_SERVER['PHP_SELF'];
        if (file_exists($filePath)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Проверяет динамические страницы (новости, проекты)
     * @param string $type - тип контента (news, projects)
     * @param mixed $id - ID или slug
     * @return bool
     */
    public static function checkDynamicContent($type, $id) {
        if (!$id) return false;
        
        try {
            require_once __DIR__ . '/config/database.php';
            
            switch ($type) {
                case 'news':
                    if (is_numeric($id)) {
                        $stmt = $pdo->prepare("SELECT id FROM news WHERE id = ? AND status = 'published'");
                    } else {
                        $stmt = $pdo->prepare("SELECT id FROM news WHERE slug = ? AND status = 'published'");
                    }
                    break;
                    
                case 'projects':
                    if (is_numeric($id)) {
                        $stmt = $pdo->prepare("SELECT id FROM projects WHERE id = ?");
                    } else {
                        $stmt = $pdo->prepare("SELECT id FROM projects WHERE slug = ?");
                    }
                    break;
                    
                default:
                    return false;
            }
            
            $stmt->execute([$id]);
            return $stmt->fetchColumn() !== false;
            
        } catch (Exception $e) {
            error_log("Router error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Редирект на 404 страницу
     */
    public static function show404() {
        http_response_code(404);
        
        // Если мы уже на странице 404, избегаем зацикливания
        if (basename($_SERVER['PHP_SELF']) === '404.php') {
            return;
        }
        
        // Проверяем, существует ли файл 404.php
        $error404Path = $_SERVER['DOCUMENT_ROOT'] . '/404.php';
        if (file_exists($error404Path)) {
            include $error404Path;
        } else {
            // Минимальная 404 страница если файл не найден
            self::showMinimal404();
        }
        exit;
    }
    
    /**
     * Минимальная 404 страница
     */
    private static function showMinimal404() {
        ?>
        <!DOCTYPE html>
        <html lang="ru">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>404 - Страница не найдена</title>
            <style>
                body { 
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                    display: flex; 
                    align-items: center; 
                    justify-content: center; 
                    min-height: 100vh; 
                    margin: 0; 
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    text-align: center;
                }
                .container { max-width: 400px; padding: 2rem; }
                h1 { font-size: 4rem; margin: 0; }
                p { font-size: 1.2rem; margin: 1rem 0; }
                a { 
                    color: white; 
                    text-decoration: none; 
                    background: rgba(255,255,255,0.2);
                    padding: 0.8rem 1.5rem;
                    border-radius: 25px;
                    display: inline-block;
                    margin-top: 1rem;
                    transition: all 0.3s ease;
                }
                a:hover { background: rgba(255,255,255,0.3); }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>404</h1>
                <p>Страница не найдена</p>
                <a href="/">На главную</a>
            </div>
        </body>
        </html>
        <?php
    }
    
    /**
     * Валидация URL параметров
     * @param array $params - массив параметров для проверки
     * @return bool
     */
    public static function validateParams($params) {
        foreach ($params as $key => $rules) {
            $value = $_GET[$key] ?? null;
            
            if (isset($rules['required']) && $rules['required'] && empty($value)) {
                return false;
            }
            
            if (!empty($value)) {
                if (isset($rules['type'])) {
                    switch ($rules['type']) {
                        case 'int':
                            if (!filter_var($value, FILTER_VALIDATE_INT)) {
                                return false;
                            }
                            break;
                        case 'slug':
                            if (!preg_match('/^[a-z0-9\-]+$/', $value)) {
                                return false;
                            }
                            break;
                        case 'email':
                            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                return false;
                            }
                            break;
                    }
                }
                
                if (isset($rules['max_length']) && strlen($value) > $rules['max_length']) {
                    return false;
                }
            }
        }
        
        return true;
    }
}

/**
 * Функция для быстрой проверки страницы
 * Использовать в начале каждой страницы
 */
function checkPageExists() {
    if (!PageRouter::checkCurrentPage()) {
        PageRouter::show404();
    }
}

/**
 * Функция для проверки динамического контента
 */
function checkDynamicPage($type, $id = null) {
    // Если ID не передан, получаем из GET параметров
    if ($id === null) {
        $id = $_GET['id'] ?? $_GET['slug'] ?? null;
    }
    
    if (!$id || !PageRouter::checkDynamicContent($type, $id)) {
        PageRouter::show404();
    }
}

/**
 * Безопасное получение параметра с валидацией
 */
function getSafeParam($key, $type = 'string', $default = null) {
    $value = $_GET[$key] ?? $default;
    
    if ($value === null || $value === '') {
        return $default;
    }
    
    switch ($type) {
        case 'int':
            return filter_var($value, FILTER_VALIDATE_INT) ?: $default;
        case 'slug':
            return preg_match('/^[a-z0-9\-]+$/', $value) ? $value : $default;
        case 'email':
            return filter_var($value, FILTER_VALIDATE_EMAIL) ?: $default;
        default:
            return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }
}
?>