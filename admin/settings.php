<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Обработка настроек
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'update_admin') {
        $admin_id = $_SESSION['admin_id'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $new_password = $_POST['new_password'];
        
        // Проверяем текущий пароль
        $current_password = $_POST['current_password'];
        $stmt = $pdo->prepare("SELECT password FROM admins WHERE id = ?");
        $stmt->execute([$admin_id]);
        $admin = $stmt->fetch();
        
        if (!password_verify($current_password, $admin['password'])) {
            $error = 'Неверный текущий пароль';
        } else {
            // Обновляем данные
            if (!empty($new_password)) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE admins SET username = ?, email = ?, password = ? WHERE id = ?");
                $stmt->execute([$username, $email, $hashed_password, $admin_id]);
            } else {
                $stmt = $pdo->prepare("UPDATE admins SET username = ?, email = ? WHERE id = ?");
                $stmt->execute([$username, $email, $admin_id]);
            }
            
            $_SESSION['admin_username'] = $username;
            $success = 'Настройки успешно обновлены';
        }
    }
    
    if ($_POST['action'] == 'backup_db') {
        // Создание резервной копии
        $tables = ['admins', 'news', 'projects', 'reviews', 'schedule', 'applications'];
        $backup_content = "-- Резервная копия БД Театр танца Столица\n";
        $backup_content .= "-- Дата создания: " . date('Y-m-d H:i:s') . "\n\n";
        
        foreach ($tables as $table) {
            $backup_content .= "-- Таблица $table\n";
            $backup_content .= "DROP TABLE IF EXISTS `$table`;\n";
            
            // Получаем структуру таблицы
            $stmt = $pdo->query("SHOW CREATE TABLE `$table`");
            $create_table = $stmt->fetch();
            if ($create_table) {
                $backup_content .= $create_table['Create Table'] . ";\n\n";
                
                // Получаем данные
                $data = $pdo->query("SELECT * FROM `$table`")->fetchAll();
                if (!empty($data)) {
                    $backup_content .= "INSERT INTO `$table` VALUES\n";
                    $values = [];
                    foreach ($data as $row) {
                        $escaped_values = array_map(function($val) use ($pdo) {
                            return $val === null ? 'NULL' : $pdo->quote($val);
                        }, array_values($row));
                        $values[] = '(' . implode(', ', $escaped_values) . ')';
                    }
                    $backup_content .= implode(",\n", $values) . ";\n\n";
                }
            }
        }
        
        // Отправляем файл для скачивания
        header('Content-Type: application/sql');
        header('Content-Disposition: attachment; filename="stolitsa_dance_backup_' . date('Y-m-d_H-i-s') . '.sql"');
        header('Content-Length: ' . strlen($backup_content));
        echo $backup_content;
        exit;
    }
    
    if ($_POST['action'] == 'clear_old_data') {
        // Очистка старых данных (старше 6 месяцев)
        $six_months_ago = date('Y-m-d', strtotime('-6 months'));
        
        $pdo->prepare("DELETE FROM applications WHERE status = 'processed' AND created_at < ?")->execute([$six_months_ago]);
        $deleted_applications = $pdo->rowCount();
        
        $success = "Удалено $deleted_applications старых заявок";
    }
}

// Получаем текущие данные админа
$stmt = $pdo->prepare("SELECT * FROM admins WHERE id = ?");
$stmt->execute([$_SESSION['admin_id']]);
$current_admin = $stmt->fetch();

// Статистика
$stats = [
    'total_news' => $pdo->query("SELECT COUNT(*) FROM news")->fetchColumn(),
    'published_news' => $pdo->query("SELECT COUNT(*) FROM news WHERE status = 'published'")->fetchColumn(),
    'total_projects' => $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn(),
    'total_reviews' => $pdo->query("SELECT COUNT(*) FROM reviews")->fetchColumn(),
    'approved_reviews' => $pdo->query("SELECT COUNT(*) FROM reviews WHERE status = 'approved'")->fetchColumn(),
    'total_applications' => $pdo->query("SELECT COUNT(*) FROM applications")->fetchColumn(),
    'new_applications' => $pdo->query("SELECT COUNT(*) FROM applications WHERE status = 'new'")->fetchColumn()
];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Настройки - Админ-панель</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f5f5f5;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .sidebar {
            position: fixed;
            left: 0;
            top: 80px;
            width: 250px;
            height: calc(100vh - 80px);
            background: white;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            padding: 2rem 0;
        }
        .sidebar-menu {
            list-style: none;
        }
        .sidebar-menu a {
            display: block;
            padding: 1rem 2rem;
            color: #666;
            text-decoration: none;
            transition: all 0.3s;
        }
        .sidebar-menu a:hover, .sidebar-menu a.active {
            background: #f0f0f0;
            color: #667eea;
            border-left: 3px solid #667eea;
        }
        .main-content {
            margin-left: 250px;
            padding: 2rem;
        }
        .settings-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 3rem;
        }
        .settings-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #666;
            font-weight: 500;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
        }
        .btn {
            display: inline-block;
            padding: 0.8rem 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            transition: transform 0.3s;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        }
        .btn-warning {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: #333;
        }
        .stats-overview {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }
        .stats-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .stats-row:last-child {
            border-bottom: none;
        }
        .stat-label {
            color: #666;
            font-weight: 500;
        }
        .stat-value {
            font-size: 1.2rem;
            font-weight: 700;
            color: #667eea;
        }
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <h1>⚙️ Настройки системы</h1>
            <div>
                <a href="dashboard.php" style="color: white;">← Назад</a>
            </div>
        </div>
    </header>

    <aside class="sidebar">
        <ul class="sidebar-menu">
            <li><a href="dashboard.php">📊 Главная</a></li>
            <li><a href="news.php">📰 Новости</a></li>
            <li><a href="projects.php">🎪 Проекты</a></li>
            <li><a href="reviews.php">⭐ Отзывы</a></li>
            <li><a href="schedule.php">📅 Расписание</a></li>
            <li><a href="applications.php">📝 Заявки</a></li>
            <li><a href="settings.php" class="active">⚙️ Настройки</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <?php if (isset($success)): ?>
            <div class="success-message"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Статистика -->
        <div class="stats-overview">
            <h2 style="margin-bottom: 2rem; color: #333;">Общая статистика</h2>
            <div class="stats-row">
                <span class="stat-label">Всего новостей</span>
                <span class="stat-value"><?= $stats['total_news'] ?></span>
            </div>
            <div class="stats-row">
                <span class="stat-label">Опубликованных новостей</span>
                <span class="stat-value"><?= $stats['published_news'] ?></span>
            </div>
            <div class="stats-row">
                <span class="stat-label">Всего проектов</span>
                <span class="stat-value"><?= $stats['total_projects'] ?></span>
            </div>
            <div class="stats-row">
                <span class="stat-label">Всего отзывов</span>
                <span class="stat-value"><?= $stats['total_reviews'] ?></span>
            </div>
            <div class="stats-row">
                <span class="stat-label">Одобренных отзывов</span>
                <span class="stat-value"><?= $stats['approved_reviews'] ?></span>
            </div>
            <div class="stats-row">
                <span class="stat-label">Всего заявок</span>
                <span class="stat-value"><?= $stats['total_applications'] ?></span>
            </div>
            <div class="stats-row">
                <span class="stat-label">Новых заявок</span>
                <span class="stat-value"><?= $stats['new_applications'] ?></span>
            </div>
        </div>

        <div class="settings-grid">
            <!-- Настройки администратора -->
            <div class="settings-card">
                <h3 style="margin-bottom: 2rem; color: #333;">Настройки аккаунта</h3>
                <form method="POST">
                    <input type="hidden" name="action" value="update_admin">
                    
                    <div class="form-group">
                        <label for="username">Логин</label>
                        <input type="text" id="username" name="username" value="<?= htmlspecialchars($current_admin['username']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($current_admin['email']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="current_password">Текущий пароль</label>
                        <input type="password" id="current_password" name="current_password" required>
                    </div>

                    <div class="form-group">
                        <label for="new_password">Новый пароль (оставьте пустым, если не хотите менять)</label>
                        <input type="password" id="new_password" name="new_password" placeholder="Минимум 6 символов">
                    </div>

                    <button type="submit" class="btn">Сохранить изменения</button>
                </form>
            </div>

            <!-- Системные настройки -->
            <div class="settings-card">
                <h3 style="margin-bottom: 2rem; color: #333;">Системные функции</h3>
                
                <div style="margin-bottom: 2rem;">
                    <h4 style="margin-bottom: 1rem; color: #667eea;">Резервное копирование</h4>
                    <p style="color: #666; margin-bottom: 1rem; font-size: 0.9rem;">
                        Создать резервную копию всех данных сайта
                    </p>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="backup_db">
                        <button type="submit" class="btn btn-warning">📥 Скачать резервную копию</button>
                    </form>
                </div>

                <div style="margin-bottom: 2rem;">
                    <h4 style="margin-bottom: 1rem; color: #667eea;">Очистка данных</h4>
                    <p style="color: #666; margin-bottom: 1rem; font-size: 0.9rem;">
                        Удалить обработанные заявки старше 6 месяцев
                    </p>
                    <form method="POST" style="display: inline;" onsubmit="return confirm('Удалить старые заявки?')">
                        <input type="hidden" name="action" value="clear_old_data">
                        <button type="submit" class="btn btn-danger">🗑️ Очистить старые данные</button>
                    </form>
                </div>

                <div>
                    <h4 style="margin-bottom: 1rem; color: #667eea;">Информация о системе</h4>
                    <div style="background: #f8f9fa; padding: 1rem; border-radius: 10px; font-size: 0.9rem; color: #666;">
                        <p><strong>PHP версия:</strong> <?= phpversion() ?></p>
                        <p><strong>Размер базы данных:</strong> 
                            <?php
                            $stmt = $pdo->query("SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 1) AS 'db_size' FROM information_schema.tables WHERE table_schema = '" . DB_NAME . "'");
                            $db_size = $stmt->fetchColumn();
                            echo $db_size ? $db_size . ' MB' : 'Неизвестно';
                            ?>
                        </p>
                        <p><strong>Свободное место:</strong> <?= round(disk_free_space('.') / 1024 / 1024 / 1024, 1) ?> GB</p>
                        <p><strong>Последнее обновление:</strong> <?= date('d.m.Y H:i') ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Быстрые действия -->
        <div class="settings-card">
            <h3 style="margin-bottom: 2rem; color: #333;">Быстрые действия</h3>
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <a href="news.php" class="btn">📰 Управление новостями</a>
                <a href="projects.php" class="btn">🎪 Управление проектами</a>
                <a href="applications.php" class="btn">📝 Новые заявки (<?= $stats['new_applications'] ?>)</a>
                <a href="reviews.php" class="btn">⭐ Модерация отзывов</a>
                <a href="../" target="_blank" class="btn" style="background: #28a745;">🌐 Посмотреть сайт</a>
            </div>
        </div>

        <!-- Справочная информация -->
        <div class="settings-card">
            <h3 style="margin-bottom: 2rem; color: #333;">Справочная информация</h3>
            <div style="color: #666; line-height: 1.8;">
                <h4 style="color: #667eea; margin-bottom: 1rem;">Контакты театра:</h4>
                <p><strong>Телефоны:</strong> +7 (999) 930-36-60, +7 (985) 411-76-49, +7 (916) 394-23-21</p>
                <p><strong>Telegram:</strong> @stolitsa_dance</p>
                <p><strong>WhatsApp:</strong> +7 (999) 930-36-60</p>
                <p><strong>Instagram:</strong> @stolitsa_dance</p>
                
                <h4 style="color: #667eea; margin: 2rem 0 1rem;">Филиалы:</h4>
                <p>• м. Ломоносовский проспект (основной)</p>
                <p>• м. Белорусская (индивидуальные занятия)</p>
                <p>• м. 1905 года (по записи)</p>
                
                <h4 style="color: #667eea; margin: 2rem 0 1rem;">Педагоги:</h4>
                <p><strong>Владимир и Ольга Журавлёвы</strong></p>
                <p>Солисты Государственного академического театра танца «Гжель»</p>
                <p>Артистический стаж более 20 лет</p>
            </div>
        </div>
    </main>

    <script>
        // Валидация формы
        document.querySelector('form').addEventListener('submit', function(e) {
            const newPassword = document.getElementById('new_password').value;
            const currentPassword = document.getElementById('current_password').value;
            
            if (!currentPassword) {
                alert('Введите текущий пароль для сохранения изменений');
                e.preventDefault();
                return;
            }
            
            if (newPassword && newPassword.length < 6) {
                alert('Новый пароль должен содержать минимум 6 символов');
                e.preventDefault();
                return;
            }
        });

        // Автоматическое обновление статистики каждые 30 секунд
        setInterval(function() {
            // В реальном проекте здесь можно делать AJAX запрос для обновления статистики
            console.log('Stats update check...');
        }, 30000);
        
        // Показать подтверждение при опасных операциях
        document.querySelectorAll('.btn-danger').forEach(btn => {
            btn.addEventListener('click', function(e) {
                if (!confirm('Вы уверены? Это действие нельзя отменить.')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>