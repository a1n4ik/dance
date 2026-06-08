<?php
// ========== sitemap.php - Динамическая карта сайта (XML) ==========

header('Content-Type: application/xml; charset=utf-8');

$baseUrl = 'https://stolitsa-dance.ru';

// Статические страницы: путь => [priority, changefreq]
$staticPages = [
    '/'                   => ['1.0', 'weekly'],
    '/contacts.php'       => ['0.8', 'monthly'],
    '/news.php'           => ['0.9', 'daily'],
    '/classical-dance.php' => ['0.8', 'monthly'],
    '/folk-dance.php'     => ['0.8', 'monthly'],
    '/jazz-modern.php'    => ['0.8', 'monthly'],
    '/baby-ballet.php'    => ['0.8', 'monthly'],
    '/gymnastics.php'     => ['0.8', 'monthly'],
    '/acrobatics.php'     => ['0.8', 'monthly'],
    '/privacy.php'        => ['0.3', 'yearly'],
    '/terms.php'          => ['0.3', 'yearly'],
];

$today = date('Y-m-d');

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

foreach ($staticPages as $path => $meta) {
    echo "  <url>\n";
    echo "    <loc>" . htmlspecialchars($baseUrl . $path) . "</loc>\n";
    echo "    <lastmod>{$today}</lastmod>\n";
    echo "    <changefreq>{$meta[1]}</changefreq>\n";
    echo "    <priority>{$meta[0]}</priority>\n";
    echo "  </url>\n";
}

// Динамические страницы новостей (если БД доступна)
$dbConfig = __DIR__ . '/config/database.php';
if (file_exists($dbConfig)) {
    try {
        require_once $dbConfig;
        if (isset($pdo)) {
            $stmt = $pdo->query("SELECT id, slug, updated_at, created_at FROM news WHERE status = 'published' ORDER BY created_at DESC");
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $loc = $baseUrl . '/news.php?id=' . urlencode($row['id']);
                $lastmod = date('Y-m-d', strtotime($row['updated_at'] ?? $row['created_at'] ?? 'now'));
                echo "  <url>\n";
                echo "    <loc>" . htmlspecialchars($loc) . "</loc>\n";
                echo "    <lastmod>{$lastmod}</lastmod>\n";
                echo "    <changefreq>monthly</changefreq>\n";
                echo "    <priority>0.6</priority>\n";
                echo "  </url>\n";
            }
        }
    } catch (Exception $e) {
        error_log('Sitemap DB error: ' . $e->getMessage());
        // Тихо пропускаем динамическую часть — статическая карта уже отдана
    }
}

echo '</urlset>';
