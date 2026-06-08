// ========== admin/projects.php - Управление проектами ========== 
<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Обработка добавления проекта
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'add') {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $content = $_POST['content'];
        $status = $_POST['status'];
        $project_date = $_POST['project_date'];
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9-]/', '-', $title));
        
        // Обработка загрузки изображения
        $image = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $uploadDir = '../uploads/projects/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $fileName = time() . '_' . basename($_FILES['image']['name']);
            $uploadFile = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                $image = '/uploads/projects/' . $fileName;
            }
        }
        
        $stmt = $pdo->prepare("INSERT INTO projects (title, slug, description, content, image, status, project_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $slug, $description, $content, $image, $status, $project_date]);
        
        header('Location: projects.php?success=added');
        exit;
    }
}

// Получаем список проектов
$projects = $pdo->query("SELECT * FROM projects ORDER BY project_date DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление проектами - Админ-панель</title>
    <style>
        /* Используем те же стили, что и в applications.php */
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
        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
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
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
        }
        .modal-content {
            background: white;
            margin: 2% auto;
            padding: 2rem;
            width: 80%;
            max-width: 800px;
            border-radius: 15px;
            max-height: 90vh;
            overflow-y: auto;
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
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
        }
        .form-group textarea {
            min-height: 150px;
            resize: vertical;
        }
        table {
            width: 100%;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        th {
            background: #f8f8f8;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #666;
        }
        td {
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
        }
        .badge {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-info { background: #d1ecf1; color: #0c5460; }
        .actions {
            display: flex;
            gap: 0.5rem;
        }
        .btn-small {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
        .btn-edit {
            background: #28a745;
        }
        .btn-delete {
            background: #dc3545;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <h1>🎪 Управление проектами</h1>
            <div>
                <a href="dashboard.php" style="color: white;">← Назад</a>
            </div>
        </div>
    </header>

    <aside class="sidebar">
        <ul class="sidebar-menu">
            <li><a href="dashboard.php">📊 Главная</a></li>
            <li><a href="news.php">📰 Новости</a></li>
            <li><a href="projects.php" class="active">🎪 Проекты</a></li>
            <li><a href="reviews.php">⭐ Отзывы</a></li>
            <li><a href="schedule.php">📅 Расписание</a></li>
            <li><a href="applications.php">📝 Заявки</a></li>
            <li><a href="settings.php">⚙️ Настройки</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="content-header">
            <h2>Все проекты</h2>
            <button class="btn" onclick="document.getElementById('addModal').style.display='block'">
                + Добавить проект
            </button>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 10px; margin-bottom: 2rem;">
                Проект успешно добавлен!
            </div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Статус</th>
                    <th>Дата проекта</th>
                    <th>Дата создания</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($projects as $project): ?>
                <tr>
                    <td><?= $project['id'] ?></td>
                    <td><?= htmlspecialchars($project['title']) ?></td>
                    <td>
                        <?php if ($project['status'] == 'active'): ?>
                            <span class="badge badge-success">Активный</span>
                        <?php elseif ($project['status'] == 'completed'): ?>
                            <span class="badge badge-info">Завершен</span>
                        <?php else: ?>
                            <span class="badge badge-warning">Предстоящий</span>
                        <?php endif; ?>
                    </td>
                    <td><?= $project['project_date'] ? date('d.m.Y', strtotime($project['project_date'])) : '-' ?></td>
                    <td><?= date('d.m.Y', strtotime($project['created_at'])) ?></td>
                    <td>
                        <div class="actions">
                            <a href="project-edit.php?id=<?= $project['id'] ?>" class="btn btn-small btn-edit">✏️</a>
                            <a href="project-delete.php?id=<?= $project['id'] ?>" class="btn btn-small btn-delete" onclick="return confirm('Удалить проект?')">🗑️</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

    <!-- Modal для добавления проекта -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <h2>Добавить проект</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add">
                
                <div class="form-group">
                    <label for="title">Название проекта</label>
                    <input type="text" id="title" name="title" required>
                </div>

                <div class="form-group">
                    <label for="description">Краткое описание</label>
                    <textarea id="description" name="description" required></textarea>
                </div>

                <div class="form-group">
                    <label for="content">Полное описание</label>
                    <textarea id="content" name="content" style="min-height: 300px;" required></textarea>
                </div>

                <div class="form-group">
                    <label for="project_date">Дата проекта</label>
                    <input type="date" id="project_date" name="project_date">
                </div>

                <div class="form-group">
                    <label for="image">Изображение</label>
                    <input type="file" id="image" name="image" accept="image/*">
                </div>

                <div class="form-group">
                    <label for="status">Статус</label>
                    <select id="status" name="status" required>
                        <option value="upcoming">Предстоящий</option>
                        <option value="active">Активный</option>
                        <option value="completed">Завершен</option>
                    </select>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn">Сохранить</button>
                    <button type="button" class="btn" style="background: #6c757d;" onclick="document.getElementById('addModal').style.display='none'">Отмена</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Закрытие модального окна при клике вне его
        window.onclick = function(event) {
            var modal = document.getElementById('addModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>