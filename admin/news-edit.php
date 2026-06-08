<?php
// ========== admin/news-edit.php - Редактирование новости ==========
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
if ($id <= 0) {
    header('Location: news.php');
    exit;
}

// Обработка обновления
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update') {
    $title    = $_POST['title'] ?? '';
    $excerpt  = $_POST['excerpt'] ?? '';
    $content  = $_POST['content'] ?? '';
    $category = $_POST['category'] ?? '';
    $status   = $_POST['status'] ?? 'draft';
    $slug     = strtolower(preg_replace('/[^a-zA-Z0-9-]/', '-', $title));

    // Обработка нового изображения (если загружено)
    $imageSql = '';
    $params = [$title, $slug, $excerpt, $content, $category, $status];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $uploadDir = '../uploads/news/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $fileName = time() . '_' . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)) {
            $imageSql = ', image = ?';
            $params[] = '/uploads/news/' . $fileName;
        }
    }
    $params[] = $id;

    try {
        $stmt = $pdo->prepare("UPDATE news SET title = ?, slug = ?, excerpt = ?, content = ?, category = ?, status = ?{$imageSql} WHERE id = ?");
        $stmt->execute($params);
        header('Location: news.php?success=updated');
        exit;
    } catch (Exception $e) {
        error_log('News update error: ' . $e->getMessage());
        $error = 'Ошибка при сохранении изменений';
    }
}

// Загружаем новость
$stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
$stmt->execute([$id]);
$news = $stmt->fetch();

if (!$news) {
    header('Location: news.php');
    exit;
}

$categories = ['Новости', 'Анонсы', 'Мастер-классы', 'Конкурсы'];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование новости - Админ-панель</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f5f5f5; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1.5rem; }
        .header-content { max-width: 900px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; }
        .header a { color: white; text-decoration: none; }
        .container { max-width: 900px; margin: 2rem auto; padding: 0 1rem; }
        .card { background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .form-group { margin-bottom: 1.25rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; }
        .form-group input[type="text"], .form-group select, .form-group textarea {
            width: 100%; padding: 0.7rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; font-family: inherit;
        }
        .form-group textarea { min-height: 120px; resize: vertical; }
        .btn { padding: 0.7rem 1.5rem; border: none; border-radius: 8px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-size: 1rem; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-cancel { background: #6c757d; }
        .error { background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; }
        .current-image { max-width: 200px; border-radius: 8px; margin-bottom: 0.5rem; display: block; }
        @media (max-width: 600px) { .header-content, .container { padding-left: 1rem; padding-right: 1rem; } }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <h1>✏️ Редактирование новости</h1>
            <a href="news.php">← Назад к списку</a>
        </div>
    </header>

    <div class="container">
        <div class="card">
            <?php if (!empty($error)): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" value="<?= (int) $news['id'] ?>">

                <div class="form-group">
                    <label for="title">Заголовок</label>
                    <input type="text" id="title" name="title" value="<?= htmlspecialchars($news['title']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="category">Категория</label>
                    <select id="category" name="category" required>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat ?>" <?= $news['category'] === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="excerpt">Краткое описание</label>
                    <textarea id="excerpt" name="excerpt" required><?= htmlspecialchars($news['excerpt']) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="content">Полный текст</label>
                    <textarea id="content" name="content" style="min-height: 300px;" required><?= htmlspecialchars($news['content']) ?></textarea>
                </div>

                <div class="form-group">
                    <label>Изображение</label>
                    <?php if (!empty($news['image'])): ?>
                        <img src="<?= htmlspecialchars($news['image']) ?>" alt="" class="current-image">
                    <?php endif; ?>
                    <input type="file" id="image" name="image" accept="image/*">
                    <small>Оставьте пустым, чтобы сохранить текущее изображение</small>
                </div>

                <div class="form-group">
                    <label for="status">Статус</label>
                    <select id="status" name="status" required>
                        <option value="draft" <?= $news['status'] === 'draft' ? 'selected' : '' ?>>Черновик</option>
                        <option value="published" <?= $news['status'] === 'published' ? 'selected' : '' ?>>Опубликовано</option>
                    </select>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn">Сохранить</button>
                    <a href="news.php" class="btn btn-cancel">Отмена</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
