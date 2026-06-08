<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Обработка действий с отзывами
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'update_status') {
        $id = $_POST['id'];
        $status = $_POST['status'];
        
        $stmt = $pdo->prepare("UPDATE reviews SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        
        header('Location: reviews.php?success=updated');
        exit;
    }
    
    if ($_POST['action'] == 'add') {
        $author_name = $_POST['author_name'];
        $content = $_POST['content'];
        $rating = $_POST['rating'];
        $status = $_POST['status'];
        
        // Обработка загрузки фото
        $author_photo = '';
        if (isset($_FILES['author_photo']) && $_FILES['author_photo']['error'] == 0) {
            $uploadDir = '../uploads/reviews/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $fileName = time() . '_' . basename($_FILES['author_photo']['name']);
            $uploadFile = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES['author_photo']['tmp_name'], $uploadFile)) {
                $author_photo = '/uploads/reviews/' . $fileName;
            }
        }
        
        $stmt = $pdo->prepare("INSERT INTO reviews (author_name, author_photo, content, rating, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$author_name, $author_photo, $content, $rating, $status]);
        
        header('Location: reviews.php?success=added');
        exit;
    }
    
    if ($_POST['action'] == 'delete') {
        $id = $_POST['id'];
        
        // Получаем информацию о файле для удаления
        $stmt = $pdo->prepare("SELECT author_photo FROM reviews WHERE id = ?");
        $stmt->execute([$id]);
        $review = $stmt->fetch();
        
        if ($review && $review['author_photo']) {
            $filePath = '../' . ltrim($review['author_photo'], '/');
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = ?");
        $stmt->execute([$id]);
        
        header('Location: reviews.php?success=deleted');
        exit;
    }
}

// Получаем все отзывы
$reviews = $pdo->query("SELECT * FROM reviews ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление отзывами - Админ-панель</title>
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
            vertical-align: top;
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
        .badge-danger { background: #f8d7da; color: #721c24; }
        .actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        .btn-small {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
        .btn-approve {
            background: #28a745;
        }
        .btn-reject {
            background: #dc3545;
        }
        .btn-delete {
            background: #6c757d;
        }
        .review-content {
            max-width: 300px;
            max-height: 100px;
            overflow: auto;
            background: #f8f9fa;
            padding: 0.8rem;
            border-radius: 8px;
            font-size: 0.9rem;
            line-height: 1.4;
        }
        .review-photo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }
        .rating-stars {
            color: #ffc107;
            font-size: 1.2rem;
        }
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <h1>⭐ Управление отзывами</h1>
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
            <li><a href="reviews.php" class="active">⭐ Отзывы</a></li>
            <li><a href="schedule.php">📅 Расписание</a></li>
            <li><a href="applications.php">📝 Заявки</a></li>
            <li><a href="settings.php">⚙️ Настройки</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="content-header">
            <h2>Все отзывы</h2>
            <button class="btn" onclick="document.getElementById('addModal').style.display='block'">
                + Добавить отзыв
            </button>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="success-message">
                <?php 
                switch($_GET['success']) {
                    case 'updated': echo 'Статус отзыва обновлен!'; break;
                    case 'added': echo 'Отзыв успешно добавлен!'; break;
                    case 'deleted': echo 'Отзыв удален!'; break;
                    default: echo 'Операция выполнена успешно!';
                }
                ?>
            </div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Фото</th>
                    <th>Автор</th>
                    <th>Рейтинг</th>
                    <th>Содержание</th>
                    <th>Статус</th>
                    <th>Дата</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reviews as $review): ?>
                <tr>
                    <td><?= $review['id'] ?></td>
                    <td>
                        <?php if ($review['author_photo']): ?>
                            <img src="<?= htmlspecialchars($review['author_photo']) ?>" alt="Фото" class="review-photo">
                        <?php else: ?>
                            <div style="width: 60px; height: 60px; background: #e9ecef; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #6c757d;">
                                👤
                            </div>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($review['author_name']) ?></td>
                    <td>
                        <div class="rating-stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <?= $i <= $review['rating'] ? '★' : '☆' ?>
                            <?php endfor; ?>
                        </div>
                    </td>
                    <td>
                        <div class="review-content">
                            <?= htmlspecialchars($review['content']) ?>
                        </div>
                    </td>
                    <td>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="action" value="update_status">
                            <input type="hidden" name="id" value="<?= $review['id'] ?>">
                            <select name="status" onchange="this.form.submit()" style="padding: 0.3rem; border-radius: 5px;">
                                <option value="pending" <?= $review['status'] == 'pending' ? 'selected' : '' ?>>Ожидает</option>
                                <option value="approved" <?= $review['status'] == 'approved' ? 'selected' : '' ?>>Одобрен</option>
                                <option value="rejected" <?= $review['status'] == 'rejected' ? 'selected' : '' ?>>Отклонен</option>
                            </select>
                        </form>
                    </td>
                    <td><?= date('d.m.Y H:i', strtotime($review['created_at'])) ?></td>
                    <td>
                        <div class="actions">
                            <?php if ($review['status'] != 'approved'): ?>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="update_status">
                                    <input type="hidden" name="id" value="<?= $review['id'] ?>">
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="btn btn-small btn-approve">✓</button>
                                </form>
                            <?php endif; ?>
                            
                            <?php if ($review['status'] != 'rejected'): ?>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="update_status">
                                    <input type="hidden" name="id" value="<?= $review['id'] ?>">
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="btn btn-small btn-reject">✗</button>
                                </form>
                            <?php endif; ?>
                            
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Удалить отзыв?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $review['id'] ?>">
                                <button type="submit" class="btn btn-small btn-delete">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

    <!-- Modal для добавления отзыва -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <h2>Добавить отзыв</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add">
                
                <div class="form-group">
                    <label for="author_name">Имя автора</label>
                    <input type="text" id="author_name" name="author_name" required>
                </div>

                <div class="form-group">
                    <label for="author_photo">Фото автора</label>
                    <input type="file" id="author_photo" name="author_photo" accept="image/*">
                </div>

                <div class="form-group">
                    <label for="content">Текст отзыва</label>
                    <textarea id="content" name="content" required placeholder="Напишите отзыв..."></textarea>
                </div>

                <div class="form-group">
                    <label for="rating">Рейтинг</label>
                    <select id="rating" name="rating" required>
                        <option value="5" selected>⭐⭐⭐⭐⭐ (5 звезд)</option>
                        <option value="4">⭐⭐⭐⭐ (4 звезды)</option>
                        <option value="3">⭐⭐⭐ (3 звезды)</option>
                        <option value="2">⭐⭐ (2 звезды)</option>
                        <option value="1">⭐ (1 звезда)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">Статус</label>
                    <select id="status" name="status" required>
                        <option value="pending">Ожидает модерации</option>
                        <option value="approved">Одобрен</option>
                        <option value="rejected">Отклонен</option>
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

        // Предварительный просмотр загружаемого изображения
        document.getElementById('author_photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Можно добавить предварительный просмотр
                    console.log('Image selected:', file.name);
                };
                reader.readAsDataURL(file);
            }
        });

        // Автообновление статистики
        function updateStats() {
            const pendingCount = document.querySelectorAll('select[name="status"] option[value="pending"]:checked').length;
            const approvedCount = document.querySelectorAll('select[name="status"] option[value="approved"]:checked').length;
            
            console.log('Pending reviews:', pendingCount);
            console.log('Approved reviews:', approvedCount);
        }

        // Вызываем при загрузке страницы
        document.addEventListener('DOMContentLoaded', updateStats);
    </script>
</body>
</html>