<?php
session_start();
require_once '../config/database.php';
require_once '../router.php';
require_once '../config/errors.php';

// Проверка прав администратора
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Параметры фильтрации
$date_filter = $_GET['date_filter'] ?? 'week';
$direction_filter = $_GET['direction'] ?? 'all';
$page = max(1, (int) ($_GET['page'] ?? 1));
$per_page = 50;
$offset = ($page - 1) * $per_page;

// Построение WHERE условий
$where_conditions = ['1=1'];
$params = [];

// Фильтр по дате
switch ($date_filter) {
    case 'today':
        $where_conditions[] = 'DATE(created_at) = CURDATE()';
        break;
    case 'week':
        $where_conditions[] = 'created_at >= DATE_SUB(NOW(), INTERVAL 1 WEEK)';
        break;
    case 'month':
        $where_conditions[] = 'created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)';
        break;
    case 'all':
        break;
}

// Фильтр по направлению
if ($direction_filter !== 'all') {
    $where_conditions[] = 'direction = ?';
    $params[] = $direction_filter;
}

$where_sql = implode(' AND ', $where_conditions);

try {
    // Получаем общее количество записей
    $count_query = "SELECT COUNT(*) FROM game_results WHERE {$where_sql}";
    $stmt = $pdo->prepare($count_query);
    $stmt->execute($params);
    $total_records = $stmt->fetchColumn();
    $total_pages = ceil($total_records / $per_page);
    
    // Получаем записи для текущей страницы
    $results_query = "
        SELECT *,
               (SELECT COUNT(*) + 1 
                FROM game_results gr2 
                WHERE gr2.direction = game_results.direction 
                AND gr2.total_score > game_results.total_score) as direction_rank,
               (SELECT COUNT(*) + 1 
                FROM game_results gr3 
                WHERE gr3.total_score > game_results.total_score) as global_rank
        FROM game_results 
        WHERE {$where_sql}
        ORDER BY created_at DESC
        LIMIT {$per_page} OFFSET {$offset}
    ";
    
    $stmt = $pdo->prepare($results_query);
    $stmt->execute($params);
    $results = $stmt->fetchAll();
    
    // Статистика
    $stats_query = "
        SELECT 
            COUNT(*) as total_games,
            COUNT(DISTINCT nickname) as unique_players,
            AVG(total_score) as avg_score,
            MAX(total_score) as max_score,
            AVG(level_reached) as avg_level,
            MAX(level_reached) as max_level,
            AVG(accuracy) as avg_accuracy
        FROM game_results 
        WHERE {$where_sql}
    ";
    
    $stmt = $pdo->prepare($stats_query);
    $stmt->execute($params);
    $stats = $stmt->fetch();
    
    // Статистика по направлениям
    $direction_stats_query = "
        SELECT 
            direction,
            direction_name,
            COUNT(*) as games_count,
            AVG(total_score) as avg_score,
            MAX(total_score) as max_score,
            COUNT(DISTINCT nickname) as unique_players
        FROM game_results 
        WHERE {$where_sql}
        GROUP BY direction, direction_name
        ORDER BY games_count DESC
    ";
    
    $stmt = $pdo->prepare($direction_stats_query);
    $stmt->execute($params);
    $direction_stats = $stmt->fetchAll();
    
} catch (Exception $e) {
    error_log("Admin game results error: " . $e->getMessage());
    $results = [];
    $stats = null;
    $direction_stats = [];
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Результаты игры - Админ-панель</title>
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
            overflow-y: auto;
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
            background: #f8f9fa;
            color: #333;
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
        .content-header h2 {
            color: #333;
            font-size: 1.8rem;
        }
        .filters-section {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .filters-form {
            display: flex;
            gap: 2rem;
            align-items: end;
            flex-wrap: wrap;
        }
        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .filter-group label {
            font-weight: 600;
            color: #333;
            font-size: 0.9rem;
        }
        .filter-group select {
            padding: 0.8rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            min-width: 150px;
            font-size: 0.9rem;
            transition: border-color 0.3s;
        }
        .filter-group select:focus {
            outline: none;
            border-color: #667eea;
        }
        .filter-btn {
            padding: 0.8rem 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: transform 0.2s;
        }
        .filter-btn:hover {
            transform: translateY(-2px);
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-number {
            font-size: 2rem;
            font-weight: 900;
            color: #667eea;
            margin-bottom: 0.5rem;
        }
        .stat-label {
            color: #666;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
        }
        .direction-stats {
            margin-bottom: 2rem;
        }
        .direction-stats h3 {
            margin-bottom: 1rem;
            color: #333;
        }
        .direction-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
        }
        .direction-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .direction-card h4 {
            color: #667eea;
            margin-bottom: 1rem;
        }
        .direction-metrics {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        .metric {
            text-align: center;
        }
        .metric-value {
            display: block;
            font-size: 1.5rem;
            font-weight: 700;
            color: #667eea;
        }
        .metric-label {
            font-size: 0.8rem;
            color: #666;
            text-transform: uppercase;
        }
        .results-section {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .section-header {
            padding: 1.5rem;
            background: #f8f9fa;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .section-header h3 {
            color: #333;
        }
        .pagination-info {
            color: #666;
            font-size: 0.9rem;
        }
        .table-container {
            overflow-x: auto;
        }
        .results-table {
            width: 100%;
            border-collapse: collapse;
        }
        .results-table th,
        .results-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
        }
        .results-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
            font-size: 0.9rem;
            text-transform: uppercase;
        }
        .results-table td {
            font-size: 0.9rem;
        }
        .nickname {
            font-weight: 600;
            color: #333;
        }
        .direction-badge {
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            color: white;
            display: inline-block;
        }
        .direction-classic { background: #9c27b0; }
        .direction-folk { background: #ff9800; }
        .direction-modern { background: #2196f3; }
        .direction-kids { background: #4caf50; }
        .score {
            font-weight: 700;
            color: #667eea;
        }
        .rank {
            font-weight: 600;
            color: #28a745;
        }
        .actions {
            white-space: nowrap;
        }
        .btn-view, .btn-delete {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.2rem;
            margin: 0 0.2rem;
            padding: 0.3rem;
            border-radius: 4px;
            transition: background 0.3s;
        }
        .btn-view:hover { background: #e3f2fd; }
        .btn-delete:hover { background: #ffebee; }
        .pagination {
            padding: 1.5rem;
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            background: #f8f9fa;
        }
        .page-btn {
            padding: 0.5rem 1rem;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s;
        }
        .page-btn:hover, .page-btn.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        .no-results {
            padding: 3rem;
            text-align: center;
            color: #666;
        }
        .no-results h3 {
            margin-bottom: 1rem;
            color: #333;
        }
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            .main-content {
                margin-left: 0;
            }
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .direction-grid {
                grid-template-columns: 1fr;
            }
        }
        @media (max-width: 768px) {
            .main-content {
                padding: 1rem;
            }
            .filters-form {
                flex-direction: column;
                align-items: stretch;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .results-table th,
            .results-table td {
                padding: 0.5rem;
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <h1>🎮 Результаты игры "Ритм Столицы"</h1>
            <div>
                Привет, <?= htmlspecialchars($_SESSION['admin_username']) ?> | 
                <a href="logout.php" style="color: white;">Выйти</a>
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
            <li><a href="game-results.php" class="active">🎮 Результаты игры</a></li>
            <li><a href="settings.php">⚙️ Настройки</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="content-header">
            <h2>Результаты игры</h2>
        </div>

        <!-- Фильтры -->
        <div class="filters-section">
            <form method="GET" class="filters-form">
                <div class="filter-group">
                    <label>Период:</label>
                    <select name="date_filter" onchange="this.form.submit()">
                        <option value="today" <?= $date_filter === 'today' ? 'selected' : '' ?>>Сегодня</option>
                        <option value="week" <?= $date_filter === 'week' ? 'selected' : '' ?>>Неделя</option>
                        <option value="month" <?= $date_filter === 'month' ? 'selected' : '' ?>>Месяц</option>
                        <option value="all" <?= $date_filter === 'all' ? 'selected' : '' ?>>Все время</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label>Направление:</label>
                    <select name="direction" onchange="this.form.submit()">
                        <option value="all" <?= $direction_filter === 'all' ? 'selected' : '' ?>>Все направления</option>
                        <option value="classic" <?= $direction_filter === 'classic' ? 'selected' : '' ?>>Классический танец</option>
                        <option value="folk" <?= $direction_filter === 'folk' ? 'selected' : '' ?>>Народный танец</option>
                        <option value="modern" <?= $direction_filter === 'modern' ? 'selected' : '' ?>>Джаз-модерн</option>
                        <option value="kids" <?= $direction_filter === 'kids' ? 'selected' : '' ?>>Детский балет</option>
                    </select>
                </div>
                
                <button type="submit" class="filter-btn">Применить фильтр</button>
            </form>
        </div>

        <?php if ($stats): ?>
        <!-- Общая статистика -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?= number_format($stats['total_games']) ?></div>
                <div class="stat-label">Всего игр</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= number_format($stats['unique_players']) ?></div>
                <div class="stat-label">Уникальных игроков</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= number_format($stats['avg_score']) ?></div>
                <div class="stat-label">Средний счет</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= number_format($stats['max_score']) ?></div>
                <div class="stat-label">Лучший счет</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= number_format($stats['avg_level'], 1) ?></div>
                <div class="stat-label">Средний уровень</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= number_format($stats['avg_accuracy'], 1) ?>%</div>
                <div class="stat-label">Средняя точность</div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Статистика по направлениям -->
        <?php if (!empty($direction_stats)): ?>
        <div class="direction-stats">
            <h3>Статистика по направлениям</h3>
            <div class="direction-grid">
                <?php foreach ($direction_stats as $dir_stat): ?>
                <div class="direction-card">
                    <h4><?= htmlspecialchars($dir_stat['direction_name']) ?></h4>
                    <div class="direction-metrics">
                        <div class="metric">
                            <span class="metric-value"><?= number_format($dir_stat['games_count']) ?></span>
                            <span class="metric-label">игр</span>
                        </div>
                        <div class="metric">
                            <span class="metric-value"><?= number_format($dir_stat['unique_players']) ?></span>
                            <span class="metric-label">игроков</span>
                        </div>
                        <div class="metric">
                            <span class="metric-value"><?= number_format($dir_stat['avg_score']) ?></span>
                            <span class="metric-label">средний счет</span>
                        </div>
                        <div class="metric">
                            <span class="metric-value"><?= number_format($dir_stat['max_score']) ?></span>
                            <span class="metric-label">рекорд</span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Таблица результатов -->
        <div class="results-section">
            <div class="section-header">
                <h3>Результаты игр</h3>
                <div class="pagination-info">
                    Показано <?= count($results) ?> из <?= number_format($total_records) ?> записей
                </div>
            </div>

            <?php if (!empty($results)): ?>
            <div class="table-container">
                <table class="results-table">
                    <thead>
                        <tr>
                            <th>Никнейм</th>
                            <th>Направление</th>
                            <th>Уровень</th>
                            <th>Счет</th>
                            <th>Точность</th>
                            <th>Комбо</th>
                            <th>Место</th>
                            <th>Дата</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $result): ?>
                        <tr>
                            <td class="nickname"><?= htmlspecialchars($result['nickname']) ?></td>
                            <td>
                                <span class="direction-badge direction-<?= $result['direction'] ?>">
                                    <?= htmlspecialchars($result['direction_name']) ?>
                                </span>
                            </td>
                            <td><?= $result['level_reached'] ?></td>
                            <td class="score"><?= number_format($result['total_score']) ?></td>
                            <td><?= number_format($result['accuracy'], 1) ?>%</td>
                            <td><?= $result['max_combo'] ?></td>
                            <td class="rank">#<?= $result['direction_rank'] ?></td>
                            <td><?= date('d.m.Y H:i', strtotime($result['created_at'])) ?></td>
                            <td class="actions">
                                <button onclick="viewDetails(<?= $result['id'] ?>)" class="btn-view" title="Подробности">👁️</button>
                                <button onclick="deleteResult(<?= $result['id'] ?>)" class="btn-delete" title="Удалить">🗑️</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Пагинация -->
            <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>" class="page-btn">‹ Назад</a>
                <?php endif; ?>
                
                <?php
                $start_page = max(1, $page - 2);
                $end_page = min($total_pages, $page + 2);
                
                for ($i = $start_page; $i <= $end_page; $i++):
                ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>" 
                       class="page-btn <?= $i === $page ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
                
                <?php if ($page < $total_pages): ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>" class="page-btn">Далее ›</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <?php else: ?>
            <div class="no-results">
                <h3>Результатов не найдено</h3>
                <p>Попробуйте изменить фильтры или проверьте, играют ли пользователи в игру.</p>
            </div>
            <?php endif; ?>
        </div>
    </main>

<script>
function viewDetails(id) {
    alert('Просмотр деталей результата #' + id + '\n\nВ полной версии здесь будет подробная информация о игре.');
}

function deleteResult(id) {
    if (!confirm('Вы уверены, что хотите удалить этот результат?')) return;
    
    fetch('/admin/api/delete-game-result.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({id: id})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Ошибка удаления: ' + (data.error || 'Неизвестная ошибка'));
        }
    })
    .catch(error => {
        alert('Ошибка: ' + error.message);
    });
}
</script>

</body>
</html>
