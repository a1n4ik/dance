<?php
// ========== api/news.php - Исправленный API для новостей ==========

// Устанавливаем кодировку для всего скрипта
ini_set('default_charset', 'UTF-8');
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');

// Заголовки с правильной кодировкой
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Обработка OPTIONS запроса для CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../config/database.php';

try {
    // Принудительно устанавливаем кодировку для соединения с БД
    $pdo->exec("SET NAMES utf8mb4");
    $pdo->exec("SET CHARACTER SET utf8mb4");
    $pdo->exec("SET COLLATION_CONNECTION = 'utf8mb4_unicode_ci'");
    
    // Получение конкретной новости по ID
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $news_id = (int)$_GET['id'];
        
        $stmt = $pdo->prepare("SELECT * FROM news WHERE id = ? AND status = 'published'");
        $stmt->execute([$news_id]);
        $news = $stmt->fetch();
        
        if ($news) {
            // Форматируем дату
            $months = [
                1 => 'января', 2 => 'февраля', 3 => 'марта', 4 => 'апреля',
                5 => 'мая', 6 => 'июня', 7 => 'июля', 8 => 'августа',
                9 => 'сентября', 10 => 'октября', 11 => 'ноября', 12 => 'декабря'
            ];
            
            $timestamp = strtotime($news['created_at']);
            $day = date('j', $timestamp);
            $month = $months[(int)date('n', $timestamp)];
            $year = date('Y', $timestamp);
            
            $news['formatted_date'] = "$day $month $year";
            $news['date'] = $news['formatted_date'];
            
            // Если нет изображения, используем заглушку
            if (empty($news['image'])) {
                $news['image'] = 'https://stolitsa-dance.ru/wp-content/uploads/2024/05/photo_2024-05-11_12-19-27.jpg';
            }
            
            // Если нет категории, устанавливаем по умолчанию
            if (empty($news['category'])) {
                $news['category'] = 'Театр танца';
            }
            
            echo json_encode([
                'success' => true,
                'data' => $news
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } else {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Новость не найдена'
            ], JSON_UNESCAPED_UNICODE);
        }
    }
    
    // Получение списка новостей
    elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Параметры пагинации
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = isset($_GET['limit']) ? min(50, max(1, (int)$_GET['limit'])) : 10;
        $offset = ($page - 1) * $limit;
        
        // Фильтр по категории
        $category = isset($_GET['category']) ? trim($_GET['category']) : '';
        $categoryFilter = '';
        $params = [];
        
        if (!empty($category)) {
            $categoryFilter = "AND category = ?";
            $params[] = $category;
        }
        
        // Получаем общее количество новостей
        $countQuery = "SELECT COUNT(*) FROM news WHERE status = 'published' $categoryFilter";
        $countStmt = $pdo->prepare($countQuery);
        $countStmt->execute($params);
        $totalNews = $countStmt->fetchColumn();
        
        // Получаем новости с пагинацией
        $newsQuery = "SELECT id, title, slug, excerpt, image, category, created_at 
                      FROM news 
                      WHERE status = 'published' $categoryFilter 
                      ORDER BY created_at DESC 
                      LIMIT ? OFFSET ?";
        
        $newsParams = array_merge($params, [$limit, $offset]);
        $newsStmt = $pdo->prepare($newsQuery);
        $newsStmt->execute($newsParams);
        $news = $newsStmt->fetchAll();
        
        // Форматируем данные
        $months = [
            1 => 'января', 2 => 'февраля', 3 => 'марта', 4 => 'апреля',
            5 => 'мая', 6 => 'июня', 7 => 'июля', 8 => 'августа',
            9 => 'сентября', 10 => 'октября', 11 => 'ноября', 12 => 'декабря'
        ];
        
        foreach ($news as &$item) {
            $timestamp = strtotime($item['created_at']);
            $day = date('j', $timestamp);
            $month = $months[(int)date('n', $timestamp)];
            $year = date('Y', $timestamp);
            
            $item['formatted_date'] = "$day $month $year";
            $item['date'] = $item['formatted_date'];
            
            if (empty($item['image'])) {
                $item['image'] = 'https://stolitsa-dance.ru/wp-content/uploads/2024/05/photo_2024-05-11_12-19-27.jpg';
            }
            
            if (empty($item['category'])) {
                $item['category'] = 'Театр танца';
            }
            
            // Обрезаем excerpt если слишком длинный
            if (strlen($item['excerpt']) > 150) {
                $item['excerpt'] = mb_substr($item['excerpt'], 0, 147) . '...';
            }
        }
        
        echo json_encode([
            'success' => true,
            'data' => $news,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $limit,
                'total' => $totalNews,
                'total_pages' => ceil($totalNews / $limit),
                'has_next' => $page < ceil($totalNews / $limit),
                'has_prev' => $page > 1
            ]
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
    
    else {
        http_response_code(405);
        echo json_encode([
            'success' => false,
            'message' => 'Метод не поддерживается'
        ], JSON_UNESCAPED_UNICODE);
    }

} catch (Exception $e) {
    error_log("News API Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Внутренняя ошибка сервера'
    ], JSON_UNESCAPED_UNICODE);
}