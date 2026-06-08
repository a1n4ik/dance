<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Получаем статистику
$stats = [
    'news' => $pdo->query("SELECT COUNT(*) FROM news")->fetchColumn(),
    'projects' => $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn(),
    'reviews' => $pdo->query("SELECT COUNT(*) FROM reviews WHERE status = 'pending'")->fetchColumn(),
    'applications' => $pdo->query("SELECT COUNT(*) FROM applications WHERE status = 'new'")->fetchColumn()
];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель - Театр танца "Столица"</title>
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
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        .stat-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            text-align: center;
        }
        .stat-number {
            font-size: 3rem;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 0.5rem;
        }
        .stat-label {
            color: #999;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 1px;
        }
        .btn {
            display: inline-block;
            padding: 0.8rem 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            transition: transform 0.3s;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .recent-items {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            text-align: left;
            padding: 1rem;
            border-bottom: 2px solid #f0f0f0;
            color: #666;
            font-weight: 600;
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
        .badge-danger { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <h1>🎭 Админ-панель</h1>
            <div>
                Привет, <?= htmlspecialchars($_SESSION['admin_username']) ?> | 
                <a href="logout.php" style="color: white;">Выйти</a>
            </div>
        </div>
    </header>

    <aside class="sidebar">
        <ul class="sidebar-menu">
            <li><a href="dashboard.php" class="active">📊 Главная</a></li>
            <li><a href="news.php">📰 Новости</a></li>
            <li><a href="game-results.php">⭐ Игра</a></li>
            <li><a href="schedule.php">📅 Расписание</a></li>
            <li><a href="applications.php">📝 Заявки</a></li>
            <li><a href="settings.php">⚙️ Настройки</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?= $stats['news'] ?></div>
                <div class="stat-label">Новостей</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $stats['projects'] ?></div>
                <div class="stat-label">Проектов</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $stats['reviews'] ?></div>
                <div class="stat-label">Новых отзывов</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $stats['applications'] ?></div>
                <div class="stat-label">Новых заявок</div>
            </div>
        </div>

        <div class="recent-items">
            <h2 style="margin-bottom: 2rem;">Последние заявки</h2>
            <table>
                <thead>
                    <tr>
                        <th>Имя</th>
                        <th>Телефон</th>
                        <th>Направление</th>
                        <th>Дата</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $applications = $pdo->query("SELECT * FROM applications ORDER BY created_at DESC LIMIT 5")->fetchAll();
                    foreach ($applications as $app):
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($app['name']) ?></td>
                        <td><?= htmlspecialchars($app['phone']) ?></td>
                        <td><?= htmlspecialchars($app['class_type'] ?: '-') ?></td>
                        <td><?= date('d.m.Y', strtotime($app['created_at'])) ?></td>
                        <td>
                            <?php if ($app['status'] == 'new'): ?>
                                <span class="badge badge-warning">Новая</span>
                            <?php elseif ($app['status'] == 'processed'): ?>
                                <span class="badge badge-success">Обработана</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Отменена</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="application-view.php?id=<?= $app['id'] ?>" class="btn" style="padding: 0.5rem 1rem;">Просмотр</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>