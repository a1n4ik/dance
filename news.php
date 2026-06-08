<?php
session_start();
require_once 'config/database.php';
require_once 'router.php';
require_once 'config/errors.php';
// SEO мета-данные
$page_title = "Новости - Театр танца Столица | События и достижения";
$page_description = "Новости театра танца Столица. Последние события, конкурсы, фестивали, достижения учеников. Жизнь танцевальной школы в Москве.";
$page_keywords = "новости театр танца, события, конкурсы, фестивали, достижения, танцевальная школа москва";

// Дополнительные CSS файлы
$additional_css = ['/assets/css/news.css'];

// Дополнительные JS файлы  
$additional_js = ['/assets/js/improved-news.js'];

// Получение всех опубликованных новостей
try {
    $stmt = $pdo->query("SELECT * FROM news WHERE status = 'published' ORDER BY created_at DESC");
    $news = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error_message = "Ошибка базы данных: " . $e->getMessage();
    $news = [];
}

// Функция для форматирования даты
function formatDate($date) {
    $months = [
        1 => 'января', 2 => 'февраля', 3 => 'марта', 4 => 'апреля',
        5 => 'мая', 6 => 'июня', 7 => 'июля', 8 => 'августа',
        9 => 'сентября', 10 => 'октября', 11 => 'ноября', 12 => 'декабря'
    ];
    
    $timestamp = strtotime($date);
    $day = date('j', $timestamp);
    $month = $months[(int)date('n', $timestamp)];
    $year = date('Y', $timestamp);
    
    return "$day $month $year";
}

// Включаем заголовок
require_once 'includes/header.php';

?>

<!-- Встроенные стили точно как в рабочей версии -->
<style>
.news-hero-custom {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-align: center;
    padding: 8rem 0 2rem;
    margin-bottom: 3rem;
}

.news-hero-custom h1 {
    font-size: 3rem;
    margin-bottom: 1rem;
    font-weight: 900;
}

.news-hero-custom p {
    font-size: 1.2rem;
    opacity: 0.9;
}

.news-grid-custom {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.news-card-custom {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    cursor: pointer;
}

.news-card-custom:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 50px rgba(0,0,0,0.15);
}

.news-image-custom {
    width: 100%;
    height: 200px;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #667eea;
    font-size: 3rem;
}

.news-image-custom img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.news-content-custom {
    padding: 1.5rem;
}

.news-meta-custom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.news-date-custom {
    color: #667eea;
    font-size: 0.9rem;
    font-weight: 600;
}

.news-category-custom {
    background: #667eea;
    color: white;
    padding: 0.3rem 0.8rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
}

.news-title-custom {
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: #333;
    line-height: 1.4;
}

.news-excerpt-custom {
    color: #666;
    line-height: 1.6;
    margin-bottom: 1rem;
}

.read-more-custom {
    color: #667eea;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.read-more-custom:hover {
    color: #5a67d8;
    gap: 1rem;
}

.no-news-custom {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
}

.no-news-custom h3 {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: #333;
}

.no-news-custom p {
    font-size: 1.1rem;
    color: #666;
    line-height: 1.6;
}

.error-message-custom {
    background: #f8d7da;
    color: #721c24;
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 2rem;
    text-align: center;
}

@media (max-width: 768px) {
    .news-hero-custom h1 {
        font-size: 2rem;
    }
    
    .news-hero-custom p {
        font-size: 1rem;
    }
    
    .news-grid-custom {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
}
</style>

<!-- Header -->
<div class="news-hero-custom">
    <div class="container">
        <h1>📰 Новости театра</h1>
        <p>События, достижения и жизнь нашего танцевального мира</p>
    </div>
</div>

<!-- Main Content -->
<div class="container">
    <?php if (isset($error_message)): ?>
        <div class="error-message-custom">
            <?= htmlspecialchars($error_message) ?>
        </div>
    <?php endif; ?>

    <?php if (empty($news)): ?>
        <div class="no-news-custom">
            <h3>📰 Новостей пока нет</h3>
            <p>
                Скоро здесь появятся свежие новости о жизни нашего театра танца.<br>
                Следите за обновлениями и не пропустите самые интересные события!
            </p>
        </div>
    <?php else: ?>
        <!-- Сетка новостей -->
        <div class="news-grid-custom">
            <?php foreach ($news as $article): ?>
                <article class="news-card-custom" onclick="openNewsModalCustom(<?= htmlspecialchars(json_encode($article), ENT_QUOTES, 'UTF-8') ?>)">
                    <div class="news-image-custom">
                        <?php if (!empty($article['image'])): ?>
                            <img src="<?= htmlspecialchars($article['image']) ?>" 
                                 alt="<?= htmlspecialchars($article['title']) ?>"
                                 loading="lazy">
                        <?php else: ?>
                            🎭
                        <?php endif; ?>
                    </div>
                    
                    <div class="news-content-custom">
                        <div class="news-meta-custom">
                            <span class="news-date-custom">
                                📅 <?= formatDate($article['created_at']) ?>
                            </span>
                            <?php if (!empty($article['category'])): ?>
                                <span class="news-category-custom">
                                    <?= htmlspecialchars($article['category']) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <h3 class="news-title-custom">
                            <?= htmlspecialchars($article['title']) ?>
                        </h3>
                        
                        <?php if (!empty($article['excerpt'])): ?>
                            <p class="news-excerpt-custom">
                                <?= htmlspecialchars(mb_substr($article['excerpt'], 0, 200)) ?><?= mb_strlen($article['excerpt']) > 200 ? '...' : '' ?>
                            </p>
                        <?php endif; ?>
                        
                        <a href="#" class="read-more-custom" onclick="event.stopPropagation();">
                            Читать полностью →
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Модальное окно для просмотра новости -->
<div id="newsModalCustom" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 9999; overflow-y: auto;">
    <div style="background: white; margin: 2rem auto; max-width: 800px; border-radius: 20px; position: relative;">
        <div style="padding: 2rem; border-bottom: 1px solid #eee; position: relative;">
            <button onclick="closeNewsModalCustom()" style="position: absolute; top: 1rem; right: 1rem; background: #f8f9fa; border: none; font-size: 1.5rem; cursor: pointer; color: #666; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">&times;</button>
        </div>
        <div style="padding: 2rem;">
            <div id="newsModalDateCustom" style="color: #667eea; font-weight: 600; margin-bottom: 1rem;"></div>
            <h2 id="newsModalTitleCustom" style="font-size: 2rem; margin-bottom: 1.5rem; color: #333;"></h2>
            <div id="newsModalImageCustom" style="margin-bottom: 2rem; text-align: center;"></div>
            <div id="newsModalTextCustom" style="line-height: 1.8; color: #444;"></div>
        </div>
    </div>
</div>

<script>
// Уникальные имена функций чтобы избежать конфликтов
function openNewsModalCustom(article) {
    console.log('Открываем новость:', article);
    
    const modal = document.getElementById('newsModalCustom');
    const modalDate = document.getElementById('newsModalDateCustom');
    const modalTitle = document.getElementById('newsModalTitleCustom');
    const modalImage = document.getElementById('newsModalImageCustom');
    const modalText = document.getElementById('newsModalTextCustom');
    
    // Форматируем дату
    const date = new Date(article.created_at);
    const months = ['января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 
                   'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];
    const formattedDate = `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
    
    modalDate.textContent = `📅 ${formattedDate}`;
    modalTitle.textContent = article.title;
    
    // Изображение
    if (article.image) {
        modalImage.innerHTML = `<img src="${article.image}" alt="${article.title}" style="max-width: 100%; border-radius: 10px;">`;
    } else {
        modalImage.innerHTML = '';
    }
    
    // Текст новости
    let content = '';
    if (article.content && article.content.trim() !== '') {
        content = article.content;
    } else if (article.excerpt && article.excerpt.trim() !== '') {
        content = `<p>${article.excerpt}</p>`;
    } else {
        content = `<p style="color: #666; font-style: italic;">Содержимое новости загружается...</p>`;
    }
    
    modalText.innerHTML = content;
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeNewsModalCustom() {
    const modal = document.getElementById('newsModalCustom');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Закрытие модального окна при клике вне его
document.getElementById('newsModalCustom').addEventListener('click', function(e) {
    if (e.target === this) {
        closeNewsModalCustom();
    }
});

// Закрытие модального окна при нажатии Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeNewsModalCustom();
    }
});
</script>

<?php
// Включаем подвал
require_once 'includes/footer.php';
?>