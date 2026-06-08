<?php
// ========== admin/news.php ==========
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Обработка добавления новости
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'add') {
        $title = $_POST['title'];
        $excerpt = $_POST['excerpt'];
        $content = $_POST['content'];
        $category = $_POST['category'];
        $status = $_POST['status'];
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9-]/', '-', $title));
        
        // Обработка загрузки изображения
        $image = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $uploadDir = '../uploads/news/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $fileName = time() . '_' . basename($_FILES['image']['name']);
            $uploadFile = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                $image = '/uploads/news/' . $fileName;
            }
        }
        
        $stmt = $pdo->prepare("INSERT INTO news (title, slug, excerpt, content, image, category, status, author_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $slug, $excerpt, $content, $image, $category, $status, $_SESSION['admin_id']]);
        
        header('Location: news.php?success=added');
        exit;
    }
}

// Получаем список новостей
$news = $pdo->query("SELECT * FROM news ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление новостями</title>
    <style>
        /* Используем стили из dashboard.php */
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
            <h1>🎭 Управление новостями</h1>
            <div>
                <a href="dashboard.php" style="color: white;">← Назад</a>
            </div>
        </div>
    </header>

    <aside class="sidebar">
        <ul class="sidebar-menu">
            <li><a href="dashboard.php">📊 Главная</a></li>
            <li><a href="news.php" class="active">📰 Новости</a></li>
            <li><a href="projects.php">🎪 Проекты</a></li>
            <li><a href="reviews.php">⭐ Отзывы</a></li>
            <li><a href="schedule.php">📅 Расписание</a></li>
            <li><a href="applications.php">📝 Заявки</a></li>
            <li><a href="settings.php">⚙️ Настройки</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="content-header">
            <h2>Все новости</h2>
            <button class="btn" onclick="document.getElementById('addModal').style.display='block'">
                + Добавить новость
            </button>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 10px; margin-bottom: 2rem;">
                Новость успешно добавлена!
            </div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Заголовок</th>
                    <th>Категория</th>
                    <th>Статус</th>
                    <th>Дата</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($news as $item): ?>
                <tr>
                    <td><?= $item['id'] ?></td>
                    <td><?= htmlspecialchars($item['title']) ?></td>
                    <td><?= htmlspecialchars($item['category']) ?></td>
                    <td>
                        <?php if ($item['status'] == 'published'): ?>
                            <span class="badge badge-success">Опубликовано</span>
                        <?php else: ?>
                            <span class="badge badge-warning">Черновик</span>
                        <?php endif; ?>
                    </td>
                    <td><?= date('d.m.Y', strtotime($item['created_at'])) ?></td>
                    <td>
                        <div class="actions">
                            <a href="news-edit.php?id=<?= $item['id'] ?>" class="btn btn-small btn-edit">✏️</a>
                            <a href="news-delete.php?id=<?= $item['id'] ?>" class="btn btn-small btn-delete" onclick="return confirm('Удалить новость?')">🗑️</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

    <!-- Modal для добавления новости -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <h2>Добавить новость</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add">
                
                <div class="form-group">
                    <label for="title">Заголовок</label>
                    <input type="text" id="title" name="title" required>
                </div>

                <div class="form-group">
                    <label for="category">Категория</label>
                    <select id="category" name="category" required>
                        <option value="Новости">Новости</option>
                        <option value="Анонсы">Анонсы</option>
                        <option value="Мастер-классы">Мастер-классы</option>
                        <option value="Конкурсы">Конкурсы</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="excerpt">Краткое описание</label>
                    <textarea id="excerpt" name="excerpt" required></textarea>
                </div>

                <div class="form-group">
                    <label for="content">Полный текст</label>
                    <textarea id="content" name="content" style="min-height: 300px;" required></textarea>
                </div>

                <div class="form-group">
                    <label for="image">Изображение</label>
                    <input type="file" id="image" name="image" accept="image/*">
                </div>

                <div class="form-group">
                    <label for="status">Статус</label>
                    <select id="status" name="status" required>
                        <option value="draft">Черновик</option>
                        <option value="published">Опубликовано</option>
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