<?php
// ========== api/schedule_debug.php - Отладочная версия для диагностики ==========

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: https://stolitsa-dance.ru');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';

$debug_info = [];

try {
    // 1. Проверяем подключение к БД
    $debug_info['connection'] = 'OK';
    $debug_info['database_name'] = $pdo->query("SELECT DATABASE()")->fetchColumn();
    
    // 2. Проверяем существование таблицы schedule
    $tables = $pdo->query("SHOW TABLES LIKE 'schedule'")->fetchAll();
    $debug_info['table_exists'] = count($tables) > 0;
    
    if ($debug_info['table_exists']) {
        // 3. Проверяем структуру таблицы
        $columns = $pdo->query("DESCRIBE schedule")->fetchAll();
        $debug_info['table_structure'] = array_column($columns, 'Field');
        
        // 4. Считаем общее количество записей
        $total_count = $pdo->query("SELECT COUNT(*) FROM schedule")->fetchColumn();
        $debug_info['total_records'] = (int)$total_count;
        
        // 5. Считаем активные записи
        $active_count = $pdo->query("SELECT COUNT(*) FROM schedule WHERE status = 'active'")->fetchColumn();
        $debug_info['active_records'] = (int)$active_count;
        
        // 6. Показываем все уникальные статусы
        $statuses = $pdo->query("SELECT DISTINCT status FROM schedule")->fetchAll(PDO::FETCH_COLUMN);
        $debug_info['unique_statuses'] = $statuses;
        
        // 7. Показываем первые 3 записи для примера
        $sample_records = $pdo->query("SELECT * FROM schedule LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
        $debug_info['sample_records'] = $sample_records;
        
        // 8. Пытаемся получить активные записи
        $stmt = $pdo->prepare("SELECT * FROM schedule WHERE status = 'active' ORDER BY branch, day_of_week, time LIMIT 5");
        $stmt->execute();
        $active_records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $debug_info['active_records_sample'] = $active_records;
        
    } else {
        $debug_info['error'] = 'Таблица schedule не найдена';
        
        // Показываем все таблицы
        $all_tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        $debug_info['available_tables'] = $all_tables;
    }
    
    // Возвращаем отладочную информацию
    echo json_encode([
        'success' => true,
        'debug_info' => $debug_info,
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    
} catch (PDOException $e) {
    $debug_info['pdo_error'] = $e->getMessage();
    $debug_info['connection'] = 'FAILED';
    
    echo json_encode([
        'success' => false,
        'error' => 'Database error',
        'debug_info' => $debug_info,
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => 'General error',
        'message' => $e->getMessage(),
        'debug_info' => $debug_info
    ], JSON_UNESCAPED_UNICODE);
}