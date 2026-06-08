<?php
// ========== api/schedule.php - Полностью исправленный API ==========

// Устанавливаем кодировку для всего скрипта
ini_set('default_charset', 'UTF-8');
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');

// Заголовки с правильной кодировкой
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';

try {
    // Принудительно устанавливаем кодировку для соединения с БД
    $pdo->exec("SET NAMES utf8mb4");
    $pdo->exec("SET CHARACTER SET utf8mb4");
    $pdo->exec("SET COLLATION_CONNECTION = 'utf8mb4_unicode_ci'");
    
    // Получаем все занятия из расписания
    $stmt = $pdo->query("SELECT * FROM schedule ORDER BY branch, class_type, day_of_week, time");
    $schedule_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Группируем данные по филиалам и типам занятий для фронтенда
    $schedule_data = [];
    $total_classes = 0;
    
    foreach ($schedule_items as $item) {
        $branch = $item['branch'];
        $class_type = $item['class_type'];
        
        // Инициализируем структуру, если её ещё нет
        if (!isset($schedule_data[$branch])) {
            $schedule_data[$branch] = [];
        }
        if (!isset($schedule_data[$branch][$class_type])) {
            $schedule_data[$branch][$class_type] = [];
        }
        
        // Исправляем пустые дни недели
        $day_of_week = !empty($item['day_of_week']) ? $item['day_of_week'] : 'По записи';
        
        // Добавляем занятие в нужную группу
        $schedule_data[$branch][$class_type][] = [
            'day' => $day_of_week,
            'time' => substr($item['time'], 0, 5), // Убираем секунды из времени
            'name' => $item['class_name'],
            'teacher' => $item['teacher'] ?: 'Не указан'
        ];
        
        $total_classes++;
    }
    
    // Если запрашивается конкретный филиал или тип занятий
    $branch_filter = $_GET['branch'] ?? null;
    $class_filter = $_GET['class_type'] ?? null;
    
    if ($branch_filter && isset($schedule_data[$branch_filter])) {
        $schedule_data = [$branch_filter => $schedule_data[$branch_filter]];
    }
    
    if ($class_filter && $branch_filter && isset($schedule_data[$branch_filter][$class_filter])) {
        $schedule_data = [$branch_filter => [$class_filter => $schedule_data[$branch_filter][$class_filter]]];
    }
    
    // Возвращаем успешный ответ с правильной кодировкой
    $response = [
        'success' => true,
        'data' => $schedule_data,
        'total_classes' => $total_classes,
        'timestamp' => date('Y-m-d H:i:s')
    ];
    
    // Используем JSON_UNESCAPED_UNICODE для правильного отображения русских символов
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    // Возвращаем ошибку
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Ошибка получения расписания',
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}