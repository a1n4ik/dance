<?php
// ========== api/projects.php - API для работы с проектами ==========

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
    // Получение конкретного проекта по ID
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $project_id = (int)$_GET['id'];
        
        $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
        $stmt->execute([$project_id]);
        $project = $stmt->fetch();
        
        if ($project) {
            // Форматируем дату
            if ($project['project_date']) {
                $project['formatted_date'] = date('d M Y', strtotime($project['project_date']));
                $project['date'] = $project['formatted_date'];
            } else {
                $project['formatted_date'] = 'Скоро';
                $project['date'] = 'Скоро';
            }
            
            // Если нет изображения, используем заглушку
            if (empty($project['image'])) {
                $project['image'] = 'https://stolitsa-dance.ru/wp-content/uploads/2022/09/prob0.jpg';
            }
            
            // Переводим статус на русский
            $statusMap = [
                'active' => 'Активный',
                'completed' => 'Завершен',
                'upcoming' => 'Предстоящий'
            ];
            $project['status_ru'] = $statusMap[$project['status']] ?? 'Неизвестно';
            
            // Обрабатываем галерею если есть
            if (!empty($project['gallery'])) {
                $project['gallery'] = json_decode($project['gallery'], true);
            }
            
            echo json_encode([
                'success' => true,
                'data' => $project
            ], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Проект не найден'
            ], JSON_UNESCAPED_UNICODE);
        }
    }
    
    // Получение списка проектов
    elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Параметры пагинации
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = isset($_GET['limit']) ? min(50, max(1, (int)$_GET['limit'])) : 10;
        $offset = ($page - 1) * $limit;
        
        // Фильтр по статусу
        $status = isset($_GET['status']) ? trim($_GET['status']) : '';
        $statusFilter = '';
        $params = [];
        
        if (!empty($status) && in_array($status, ['active', 'completed', 'upcoming'])) {
            $statusFilter = "WHERE status = ?";
            $params[] = $status;
        }
        
        // Получаем общее количество проектов
        $countQuery = "SELECT COUNT(*) FROM projects $statusFilter";
        $countStmt = $pdo->prepare($countQuery);
        $countStmt->execute($params);
        $totalProjects = $countStmt->fetchColumn();
        
        // Получаем проекты с пагинацией
        $projectsQuery = "SELECT id, title, slug, description, image, status, project_date, created_at 
                          FROM projects 
                          $statusFilter 
                          ORDER BY 
                            CASE 
                                WHEN project_date IS NULL THEN 1 
                                ELSE 0 
                            END,
                            project_date DESC,
                            created_at DESC 
                          LIMIT ? OFFSET ?";
        
        $projectsParams = array_merge($params, [$limit, $offset]);
        $projectsStmt = $pdo->prepare($projectsQuery);
        $projectsStmt->execute($projectsParams);
        $projects = $projectsStmt->fetchAll();
        
        // Переводим статусы
        $statusMap = [
            'active' => 'Активный',
            'completed' => 'Завершен',
            'upcoming' => 'Предстоящий'
        ];
        
        // Форматируем данные
        foreach ($projects as &$item) {
            if ($item['project_date']) {
                $item['formatted_date'] = date('d M Y', strtotime($item['project_date']));
            } else {
                $item['formatted_date'] = 'Скоро';
            }
            
            if (empty($item['image'])) {
                $item['image'] = 'https://stolitsa-dance.ru/wp-content/uploads/2022/09/prob0.jpg';
            }
            
            $item['status_ru'] = $statusMap[$item['status']] ?? 'Неизвестно';
            
            // Обрезаем описание если слишком длинное
            if (strlen($item['description']) > 150) {
                $item['description'] = mb_substr($item['description'], 0, 147) . '...';
            }
        }
        
        echo json_encode([
            'success' => true,
            'data' => $projects,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $limit,
                'total' => $totalProjects,
                'total_pages' => ceil($totalProjects / $limit),
                'has_next' => $page < ceil($totalProjects / $limit),
                'has_prev' => $page > 1
            ]
        ], JSON_UNESCAPED_UNICODE);
    }
    
    // Создание нового проекта (только для админов)
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        session_start();
        
        // Проверяем авторизацию админа
        if (!isset($_SESSION['admin_id'])) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Требуется авторизация'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        // Получаем данные из POST
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $status = $_POST['status'] ?? 'upcoming';
        $project_date = !empty($_POST['project_date']) ? $_POST['project_date'] : null;
        
        // Валидация
        $errors = [];
        if (empty($title)) {
            $errors[] = 'Название проекта обязательно';
        }
        if (empty($description)) {
            $errors[] = 'Описание обязательно';
        }
        if (!in_array($status, ['active', 'completed', 'upcoming'])) {
            $status = 'upcoming';
        }
        
        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Ошибки валидации',
                'errors' => $errors
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        // Создаем slug
        $slug = createSlug($title);
        
        // Обработка загрузки изображения
        $image = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = uploadImage($_FILES['image'], 'projects');
            if ($uploadResult['success']) {
                $image = $uploadResult['path'];
            } else {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => $uploadResult['error']
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
        
        // Обработка галереи (множественные файлы)
        $gallery = [];
        if (isset($_FILES['gallery']) && is_array($_FILES['gallery']['name'])) {
            foreach ($_FILES['gallery']['name'] as $key => $name) {
                if ($_FILES['gallery']['error'][$key] === UPLOAD_ERR_OK) {
                    $file = [
                        'name' => $_FILES['gallery']['name'][$key],
                        'type' => $_FILES['gallery']['type'][$key],
                        'tmp_name' => $_FILES['gallery']['tmp_name'][$key],
                        'error' => $_FILES['gallery']['error'][$key],
                        'size' => $_FILES['gallery']['size'][$key]
                    ];
                    
                    $uploadResult = uploadImage($file, 'projects/gallery');
                    if ($uploadResult['success']) {
                        $gallery[] = $uploadResult['path'];
                    }
                }
            }
        }
        
        $galleryJson = !empty($gallery) ? json_encode($gallery) : null;
        
        // Сохраняем в БД
        $stmt = $pdo->prepare("
            INSERT INTO projects (title, slug, description, content, image, gallery, status, project_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $result = $stmt->execute([
            $title, $slug, $description, $content, $image, $galleryJson, $status, $project_date
        ]);
        
        if ($result) {
            $projectId = $pdo->lastInsertId();
            echo json_encode([
                'success' => true,
                'message' => 'Проект успешно создан',
                'id' => $projectId
            ], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Ошибка при сохранении в БД'
            ], JSON_UNESCAPED_UNICODE);
        }
    }
    
    else {
        http_response_code(405);
        echo json_encode([
            'success' => false,
            'message' => 'Метод не поддерживается'
        ], JSON_UNESCAPED_UNICODE);
    }

} catch (Exception $e) {
    error_log("Projects API Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Внутренняя ошибка сервера'
    ], JSON_UNESCAPED_UNICODE);
}

// Функция для создания slug
function createSlug($text) {
    // Транслитерация
    $translitMap = [
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
        'е' => 'e', 'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i',
        'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
        'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
        'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'ts', 'ч' => 'ch',
        'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '',
        'э' => 'e', 'ю' => 'yu', 'я' => 'ya'
    ];
    
    $text = mb_strtolower($text);
    $text = strtr($text, $translitMap);
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    $text = trim($text, '-');
    
    return $text;
}

// Функция для загрузки изображений
function uploadImage($file, $subfolder = 'projects') {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    $maxSize = 5 * 1024 * 1024; // 5MB
    
    // Проверка типа файла
    if (!in_array($file['type'], $allowedTypes)) {
        return ['success' => false, 'error' => 'Недопустимый тип файла'];
    }
    
    // Проверка размера
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'error' => 'Файл слишком большой'];
    }
    
    // Создаем папку если не существует
    $uploadDir = "../uploads/$subfolder/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    // Генерируем уникальное имя файла
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = time() . '_' . uniqid() . '.' . $extension;
    $filepath = $uploadDir . $filename;
    
    // Перемещаем файл
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return [
            'success' => true, 
            'path' => "/uploads/$subfolder/$filename",
            'filename' => $filename
        ];
    } else {
        return ['success' => false, 'error' => 'Ошибка при загрузке файла'];
    }
}
?>