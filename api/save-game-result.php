<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';

// Проверяем метод запроса
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Метод не разрешен']);
    exit;
}

// Получаем данные POST запроса
$input = json_decode(file_get_contents('php://input'), true);

// Валидация входящих данных
$required_fields = ['nickname', 'direction', 'direction_name', 'level_reached', 'total_score'];
foreach ($required_fields as $field) {
    if (!isset($input[$field]) || empty($input[$field])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => "Поле {$field} обязательно"]);
        exit;
    }
}

// Очистка и валидация данных
$nickname = trim($input['nickname']);
$direction = trim($input['direction']);
$direction_name = trim($input['direction_name']);
$level_reached = (int) $input['level_reached'];
$total_score = (int) $input['total_score'];
$accuracy = isset($input['accuracy']) ? (float) $input['accuracy'] : 0;
$perfect_hits = isset($input['perfect_hits']) ? (int) $input['perfect_hits'] : 0;
$good_hits = isset($input['good_hits']) ? (int) $input['good_hits'] : 0;
$max_combo = isset($input['max_combo']) ? (int) $input['max_combo'] : 0;
$total_notes = isset($input['total_notes']) ? (int) $input['total_notes'] : 0;

// Валидация данных
if (strlen($nickname) < 2 || strlen($nickname) > 20) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Никнейм должен быть от 2 до 20 символов']);
    exit;
}

// Проверка никнейма на недопустимые символы
if (!preg_match('/^[а-яА-ЯёЁa-zA-Z0-9_\-\s]+$/u', $nickname)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Никнейм содержит недопустимые символы']);
    exit;
}

$valid_directions = ['classic', 'folk', 'modern', 'kids'];
if (!in_array($direction, $valid_directions)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Недопустимое направление']);
    exit;
}

if ($level_reached < 1 || $level_reached > 15) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Недопустимый уровень']);
    exit;
}

if ($total_score < 0 || $total_score > 1000000) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Недопустимый счет']);
    exit;
}

if ($accuracy < 0 || $accuracy > 100) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Недопустимое значение точности']);
    exit;
}

// Получаем IP адрес пользователя
$user_ip = $_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];

try {
    // Проверяем, нет ли дублирующихся записей от этого IP за последние 10 секунд
    $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM game_results 
        WHERE user_ip = ? AND created_at > DATE_SUB(NOW(), INTERVAL 10 SECOND)
    ");
    $stmt->execute([$user_ip]);
    
    if ($stmt->fetchColumn() > 0) {
        http_response_code(429);
        echo json_encode(['success' => false, 'error' => 'Слишком частые запросы. Подождите немного.']);
        exit;
    }
    
    // Сохраняем результат
    $stmt = $pdo->prepare("
        INSERT INTO game_results (
            nickname, direction, direction_name, level_reached, total_score, 
            accuracy, perfect_hits, good_hits, max_combo, total_notes, 
            user_ip, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    
    $result = $stmt->execute([
        $nickname, $direction, $direction_name, $level_reached, $total_score,
        $accuracy, $perfect_hits, $good_hits, $max_combo, $total_notes,
        $user_ip
    ]);
    
    if ($result) {
        $insert_id = $pdo->lastInsertId();
        
        // Получаем позицию в рейтинге
        $stmt = $pdo->prepare("
            SELECT COUNT(*) + 1 as position
            FROM game_results 
            WHERE total_score > ? AND direction = ?
        ");
        $stmt->execute([$total_score, $direction]);
        $position = $stmt->fetchColumn();
        
        echo json_encode([
            'success' => true,
            'message' => 'Результат успешно сохранен!',
            'id' => $insert_id,
            'leaderboard_position' => $position
        ]);
    } else {
        throw new Exception('Ошибка при вставке данных');
    }
    
} catch (Exception $e) {
    error_log("Game result save error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Ошибка сервера при сохранении результата']);
}
?>