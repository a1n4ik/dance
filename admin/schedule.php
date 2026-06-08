<?php
// ========== admin/schedule.php - Управление расписанием ==========

session_start();

// Проверка авторизации
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../config/database.php';

// Обработка действий
$message = '';
$error = '';

// Удаление записи
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM schedule WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
        $message = "Занятие удалено из расписания";
    } catch (Exception $e) {
        $error = "Ошибка при удалении: " . $e->getMessage();
    }
}

// Добавление нового занятия
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $branch = trim($_POST['branch']);
    $class_type = trim($_POST['class_type']);
    $day_of_week = trim($_POST['day_of_week']);
    $time = trim($_POST['time']);
    $class_name = trim($_POST['class_name']);
    $teacher = trim($_POST['teacher']);
    $age_group = trim($_POST['age_group']);
    
    if (empty($branch) || empty($class_type) || empty($day_of_week) || empty($time) || empty($class_name)) {
        $error = "Заполните все обязательные поля";
    } else {
        try {
            // Проверяем на дублирование
            $check = $pdo->prepare("SELECT id FROM schedule WHERE branch = ? AND day_of_week = ? AND time = ?");
            $check->execute([$branch, $day_of_week, $time . ':00']);
            
            if ($check->fetchColumn()) {
                $error = "На это время уже назначено занятие в данном филиале";
            } else {
                $stmt = $pdo->prepare("INSERT INTO schedule (branch, class_type, day_of_week, time, class_name, teacher, age_group) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$branch, $class_type, $day_of_week, $time . ':00', $class_name, $teacher, $age_group]);
                $message = "Занятие добавлено в расписание";
                
                // Очищаем форму
                $_POST = [];
            }
        } catch (Exception $e) {
            $error = "Ошибка при добавлении: " . $e->getMessage();
        }
    }
}

// Получение всех записей расписания
try {
    $schedule = $pdo->query("
        SELECT * FROM schedule 
        ORDER BY branch, 
                 FIELD(day_of_week, 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота', 'Воскресенье'), 
                 time
    ")->fetchAll();
} catch (Exception $e) {
    $error = "Ошибка загрузки данных: " . $e->getMessage();
    $schedule = [];
}

// Статистика
$stats = [
    'total' => count($schedule),
    'by_branch' => [],
    'by_type' => [],
    'by_day' => []
];

foreach ($schedule as $item) {
    // По филиалам
    if (!isset($stats['by_branch'][$item['branch']])) {
        $stats['by_branch'][$item['branch']] = 0;
    }
    $stats['by_branch'][$item['branch']]++;
    
    // По типам
    if (!isset($stats['by_type'][$item['class_type']])) {
        $stats['by_type'][$item['class_type']] = 0;
    }
    $stats['by_type'][$item['class_type']]++;
    
    // По дням
    if (!isset($stats['by_day'][$item['day_of_week']])) {
        $stats['by_day'][$item['day_of_week']] = 0;
    }
    $stats['by_day'][$item['day_of_week']]++;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление расписанием - Админ-панель</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8f9fa;
            color: #333;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .sidebar {
            position: fixed;
            left: 0;
            top: 80px;
            width: 250px;
            height: calc(100vh - 80px);
            background: white;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            overflow-y: auto;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 1rem 0;
        }
        
        .sidebar-menu a {
            display: block;
            padding: 1rem 1.5rem;
            color: #333;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .sidebar-menu a:hover, .sidebar-menu a.active {
            background: #f8f9fa;
            color: #667eea;
            border-right: 3px solid #667eea;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 2rem;
            min-height: calc(100vh - 80px);
        }
        
        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }
        
        .form-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        .form-group label {
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }
        
        .form-group input, .form-group select, .form-group textarea {
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn:hover {
            background: #5a6fd8;
            transform: translateY(-1px);
        }
        
        .btn-danger {
            background: #dc3545;
        }
        
        .btn-danger:hover {
            background: #c82333;
        }
        
        .btn-small {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
        
        .table-container {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
        }
        
        tr:hover {
            background: #f8f9fa;
        }
        
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
            border: 1px solid #c3e6cb;
        }
        
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
            border: 1px solid #f5c6cb;
        }
        
        .branch-tag {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 3px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .branch-lomonosovsky { background: #e3f2fd; color: #1976d2; }
        .branch-belorusskaya { background: #f3e5f5; color: #7b1fa2; }
        .branch-1905 { background: #e8f5e8; color: #388e3c; }
        
        .type-tag {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 3px;
            font-size: 0.8rem;
            background: #fff3e0;
            color: #f57c00;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #666;
        }
        
        .empty-state img {
            width: 100px;
            opacity: 0.5;
            margin-bottom: 1rem;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <h1>📅 Управление расписанием</h1>
            <div>
                <a href="dashboard.php" style="color: white; text-decoration: none;">← Назад</a>
            </div>
        </div>
    </header>

    <aside class="sidebar">
        <ul class="sidebar-menu">
            <li><a href="dashboard.php">📊 Главная</a></li>
            <li><a href="news.php">📰 Новости</a></li>
            <li><a href="projects.php">🎪 Проекты</a></li>
            <li><a href="reviews.php">⭐ Отзывы</a></li>
            <li><a href="schedule.php" class="active">📅 Расписание</a></li>
            <li><a href="applications.php">📝 Заявки</a></li>
            <li><a href="settings.php">⚙️ Настройки</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <?php if ($message): ?>
            <div class="success-message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Статистика -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?= $stats['total'] ?></div>
                <div class="stat-label">Всего занятий</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= count($stats['by_branch']) ?></div>
                <div class="stat-label">Филиалов</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= count($stats['by_type']) ?></div>
                <div class="stat-label">Типов занятий</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= count($stats['by_day']) ?></div>
                <div class="stat-label">Рабочих дней</div>
            </div>
        </div>

        <!-- Форма добавления -->
        <div class="form-container">
            <h3 style="margin-bottom: 1.5rem;">➕ Добавить занятие</h3>
            
            <form method="post">
                <input type="hidden" name="action" value="add">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="branch">Филиал *</label>
                        <select name="branch" id="branch" required>
                            <option value="">Выберите филиал</option>
                            <option value="lomonosovsky" <?= (($_POST['branch'] ?? '') === 'lomonosovsky') ? 'selected' : '' ?>>м. Ломоносовский пр.</option>
                            <option value="belorusskaya" <?= (($_POST['branch'] ?? '') === 'belorusskaya') ? 'selected' : '' ?>>м. Белорусская</option>
                            <option value="1905" <?= (($_POST['branch'] ?? '') === '1905') ? 'selected' : '' ?>>м. 1905 года</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="class_type">Тип занятия *</label>
                        <select name="class_type" id="class_type" required>
                            <option value="">Выберите тип</option>
                            <option value="classic" <?= (($_POST['class_type'] ?? '') === 'classic') ? 'selected' : '' ?>>Классический танец</option>
                            <option value="folk" <?= (($_POST['class_type'] ?? '') === 'folk') ? 'selected' : '' ?>>Народный танец</option>
                            <option value="modern" <?= (($_POST['class_type'] ?? '') === 'modern') ? 'selected' : '' ?>>Современный танец</option>
                            <option value="kids" <?= (($_POST['class_type'] ?? '') === 'kids') ? 'selected' : '' ?>>Детская хореография</option>
                            <option value="individual" <?= (($_POST['class_type'] ?? '') === 'individual') ? 'selected' : '' ?>>Индивидуальные</option>
                            <option value="body" <?= (($_POST['class_type'] ?? '') === 'body') ? 'selected' : '' ?>>Боди-балет</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="day_of_week">День недели *</label>
                        <select name="day_of_week" id="day_of_week" required>
                            <option value="">Выберите день</option>
                            <option value="Понедельник" <?= (($_POST['day_of_week'] ?? '') === 'Понедельник') ? 'selected' : '' ?>>Понедельник</option>
                            <option value="Вторник" <?= (($_POST['day_of_week'] ?? '') === 'Вторник') ? 'selected' : '' ?>>Вторник</option>
                            <option value="Среда" <?= (($_POST['day_of_week'] ?? '') === 'Среда') ? 'selected' : '' ?>>Среда</option>
                            <option value="Четверг" <?= (($_POST['day_of_week'] ?? '') === 'Четверг') ? 'selected' : '' ?>>Четверг</option>
                            <option value="Пятница" <?= (($_POST['day_of_week'] ?? '') === 'Пятница') ? 'selected' : '' ?>>Пятница</option>
                            <option value="Суббота" <?= (($_POST['day_of_week'] ?? '') === 'Суббота') ? 'selected' : '' ?>>Суббота</option>
                            <option value="Воскресенье" <?= (($_POST['day_of_week'] ?? '') === 'Воскресенье') ? 'selected' : '' ?>>Воскресенье</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="time">Время *</label>
                        <input type="time" name="time" id="time" value="<?= htmlspecialchars($_POST['time'] ?? '') ?>" required>
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="class_name">Название занятия *</label>
                        <input type="text" name="class_name" id="class_name" 
                               value="<?= htmlspecialchars($_POST['class_name'] ?? '') ?>" 
                               placeholder="Например: Народный танец" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="teacher">Преподаватель</label>
                        <input type="text" name="teacher" id="teacher" 
                               value="<?= htmlspecialchars($_POST['teacher'] ?? '') ?>" 
                               placeholder="Например: Журавлёв В.В.">
                    </div>
                    
                    <div class="form-group">
                        <label for="age_group">Возрастная группа</label>
                        <input type="text" name="age_group" id="age_group" 
                               value="<?= htmlspecialchars($_POST['age_group'] ?? '') ?>" 
                               placeholder="Например: 6-8 лет">
                    </div>
                </div>
                
                <button type="submit" class="btn">Добавить занятие</button>
            </form>
        </div>

        <!-- Таблица расписания -->
        <div class="table-container">
            <h3 style="padding: 1.5rem 1.5rem 0; margin: 0;">📋 Текущее расписание</h3>
            
            <?php if (empty($schedule)): ?>
                <div class="empty-state">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">📅</div>
                    <h3>Расписание пусто</h3>
                    <p>Добавьте первое занятие, используя форму выше</p>
                </div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Филиал</th>
                            <th>Тип</th>
                            <th>День</th>
                            <th>Время</th>
                            <th>Название группы</th>
                            <th>Преподаватель</th>
                            <th>Статус</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($schedule as $item): ?>
                            <tr>
                                <td>
                                    <span class="branch-tag branch-<?= htmlspecialchars($item['branch']) ?>">
                                        <?php 
                                        $branches = [
                                            'lomonosovsky' => 'м. Ломоносовский пр.',
                                            'belorusskaya' => 'м. Белорусская',
                                            '1905' => 'м. 1905 года'
                                        ];
                                        echo $branches[$item['branch']] ?? $item['branch'];
                                        ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="type-tag">
                                        <?php 
                                        $types = [
                                            'classic' => 'Классический',
                                            'folk' => 'Народный',
                                            'modern' => 'Современный',
                                            'kids' => 'Детская хореография',
                                            'individual' => 'Индивидуальное',
                                            'body' => 'Боди-балет'
                                        ];
                                        echo $types[$item['class_type']] ?? $item['class_type'];
                                        ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($item['day_of_week']) ?></td>
                                <td><strong><?= substr($item['time'], 0, 5) ?></strong></td>
                                <td>
                                    <strong><?= htmlspecialchars($item['class_name']) ?></strong>
                                    <?php if ($item['age_group']): ?>
                                        <br><small style="color: #666;"><?= htmlspecialchars($item['age_group']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($item['teacher'] ?: 'Не указан') ?></td>
                                <td><span style="color: #28a745; font-weight: bold;">●</span> Активное</td>
                                <td>
                                    <a href="?delete=<?= $item['id'] ?>" 
                                       class="btn btn-danger btn-small"
                                       onclick="return confirm('Удалить это занятие из расписания?')">
                                        🗑️ Удалить
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <!-- Кнопка обновления сайта -->
        <div style="margin-top: 2rem; text-align: center;">
            <button onclick="updateWebsiteSchedule()" class="btn" style="background: #28a745;">
                🔄 Обновить расписание на сайте
            </button>
            <p style="margin-top: 0.5rem; color: #666; font-size: 0.9rem;">
                Нажмите для синхронизации изменений с главной страницей
            </p>
        </div>
    </main>

    <script>
        // Функция обновления расписания на сайте
        function updateWebsiteSchedule() {
            if (window.parent && window.parent.scheduleModule) {
                window.parent.scheduleModule.refreshSchedule();
                alert('Расписание на сайте обновлено!');
            } else {
                // Альтернативный способ - отправка сообщения родительскому окну
                fetch('/api/schedule.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Расписание успешно обновлено на сайте!');
                        } else {
                            alert('Ошибка обновления: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Расписание изменено в базе данных. Обновите главную страницу для отображения изменений.');
                    });
            }
        }

        // Автозаполнение названий занятий в зависимости от типа
        document.getElementById('class_type').addEventListener('change', function() {
            const classNameInput = document.getElementById('class_name');
            const suggestions = {
                'classic': 'Классический танец',
                'folk': 'Народный танец', 
                'modern': 'Современный танец',
                'kids': 'Детская хореография',
                'individual': 'Индивидуальное занятие',
                'body': 'Боди-балет для взрослых'
            };
            
            if (suggestions[this.value] && !classNameInput.value) {
                classNameInput.value = suggestions[this.value];
            }
        });

        // Автозаполнение преподавателя
        document.getElementById('class_type').addEventListener('change', function() {
            const teacherInput = document.getElementById('teacher');
            const defaultTeachers = {
                'classic': 'Котова Е.А.',
                'folk': 'Журавлёв В.В.',
                'modern': 'Журавлёва О.А.',
                'kids': 'Журавлёва О.А.',
                'individual': 'По записи',
                'body': 'Журавлёва О.А.'
            };
            
            if (defaultTeachers[this.value] && !teacherInput.value) {
                teacherInput.value = defaultTeachers[this.value];
            }
        });
    </script>
</body>
</html>