<?php
// ========== admin/application-view.php - Просмотр заявки ==========
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: applications.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM applications WHERE id = ?");
$stmt->execute([$id]);
$app = $stmt->fetch();

if (!$app) {
    header('Location: applications.php');
    exit;
}

// Человекочитаемые названия
$classTypes = [
    'classical' => 'Классический танец', 'folk' => 'Народный танец',
    'jazz-modern' => 'Джаз-модерн', 'baby-ballet' => 'Танцевальная практика',
    'gymnastics' => 'Партерная гимнастика', 'acrobatics' => 'Акробатика',
];
$branches = ['lomonosovsky' => 'м. Ломоносовский проспект', 'belorusskaya' => 'м. Белорусская'];
$statuses = ['new' => 'Новая', 'processed' => 'Обработана', 'cancelled' => 'Отменена'];

function field($label, $value) {
    echo '<div class="row"><div class="label">' . htmlspecialchars($label) . '</div><div class="value">'
        . ($value !== '' && $value !== null ? htmlspecialchars($value) : '—') . '</div></div>';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заявка #<?= (int) $app['id'] ?> - Админ-панель</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f5f5f5; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1.5rem; }
        .header-content { max-width: 800px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; }
        .header a { color: white; text-decoration: none; }
        .container { max-width: 800px; margin: 2rem auto; padding: 0 1rem; }
        .card { background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .row { display: flex; border-bottom: 1px solid #eee; padding: 0.9rem 0; }
        .row:last-of-type { border-bottom: none; }
        .label { flex: 0 0 200px; font-weight: 600; color: #555; }
        .value { flex: 1; color: #222; white-space: pre-wrap; }
        .status-form { margin-top: 1.5rem; display: flex; gap: 1rem; align-items: center; flex-wrap: wrap; }
        select, .btn { padding: 0.6rem 1rem; border-radius: 8px; border: 1px solid #ddd; font-size: 1rem; }
        .btn { border: none; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; cursor: pointer; }
        @media (max-width: 600px) { .row { flex-direction: column; } .label { flex-basis: auto; margin-bottom: 0.25rem; } }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <h1>📝 Заявка #<?= (int) $app['id'] ?></h1>
            <a href="applications.php">← Назад к списку</a>
        </div>
    </header>

    <div class="container">
        <div class="card">
            <?php
            field('Имя', $app['name']);
            field('Телефон', $app['phone']);
            field('Email', $app['email']);
            field('Направление', $classTypes[$app['class_type']] ?? $app['class_type']);
            field('Филиал', $branches[$app['branch']] ?? $app['branch']);
            field('Возраст', $app['age'] ?? '');
            field('Сообщение', $app['message'] ?? '');
            field('Дата заявки', !empty($app['created_at']) ? date('d.m.Y H:i', strtotime($app['created_at'])) : '');
            field('Статус', $statuses[$app['status']] ?? ($app['status'] ?? ''));
            ?>

            <form class="status-form" method="POST" action="applications.php">
                <input type="hidden" name="action" value="update_status">
                <input type="hidden" name="id" value="<?= (int) $app['id'] ?>">
                <label for="status">Изменить статус:</label>
                <select name="status" id="status">
                    <?php foreach ($statuses as $val => $label): ?>
                        <option value="<?= $val ?>" <?= ($app['status'] ?? '') === $val ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn">Сохранить</button>
            </form>
        </div>
    </div>
</body>
</html>
