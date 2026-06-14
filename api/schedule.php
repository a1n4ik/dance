<?php
// ========== api/schedule.php - Рабочая версия API для расписания ==========

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: https://stolitsa-dance.ru');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';

try {
    // Получаем все активные занятия из расписания - ИСПРАВЛЕННЫЙ ЗАПРОС
    $stmt = $pdo->prepare("SELECT * FROM schedule WHERE status = ? ORDER BY branch, day_of_week, time");
    $stmt->execute(['active']);
    $schedule_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Группируем данные по филиалам
    $schedule_data = [];
    
    foreach ($schedule_items as $item) {
        $branch = $item['branch'];
        $class_type = $item['class_type'];
        
        // Инициализируем структуру филиала
        if (!isset($schedule_data[$branch])) {
            $schedule_data[$branch] = [];
        }
        
        // Поскольку в БД все записи имеют class_type = 'general', 
        // создаем записи для всех типов занятий для совместимости с фронтендом
        $types_to_add = ['general'];
        
        foreach ($types_to_add as $type) {
            if (!isset($schedule_data[$branch][$type])) {
                $schedule_data[$branch][$type] = [];
            }
            
            // Добавляем занятие
            $schedule_data[$branch][$type][] = [
                'day' => $item['day_of_week'],
                'time' => substr($item['time'], 0, 5), // HH:MM формат (убираем секунды)
                'name' => $item['class_name'],
                'teacher' => $item['teacher'],
                'age_group' => $item['age_group'] ?? ''
            ];
        }
    }
    
    // Подсчитываем общее количество занятий
    $total_classes = count($schedule_items);
    
    // Возвращаем ответ
    echo json_encode([
        'success' => true,
        'data' => $schedule_data,
        'total_classes' => $total_classes,
        'branches' => array_keys($schedule_data),
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    error_log('Schedule API Database Error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Ошибка подключения к базе данных',
        'message' => 'Не удается загрузить расписание'
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    error_log('Schedule API General Error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Ошибка сервера',
        'message' => 'Внутренняя ошибка сервера'
    ], JSON_UNESCAPED_UNICODE);
}