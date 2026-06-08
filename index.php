<?php
// ========== index.php - Обновленная главная страница ==========

session_start();
require_once 'config/database.php';
require_once 'router.php';
require_once 'config/errors.php';

// Мета-данные для страницы
$page_title = "Театр танца \"Столица\" - Танцевальная школа в Москве";
$page_description = "Театр танца Столица - профессиональное обучение классическому танцу, народному танцу, современной хореографии. Педагоги - солисты театра Гжель.";
$page_keywords = "театр танца, балет, классический танец, народный танец, москва, дети, хореография, школа танцев";

// Дополнительные CSS файлы
$additional_css = [
    '/assets/css/home.css',
				  '/assets/css/reviews-slideshow.css'
];

// Дополнительные JS файлы
$additional_js = [
    '/assets/js/schedule.js',
    '/assets/js/services-interactive.js',
    '/assets/js/sliders.js',
    '/assets/js/improved-news.js',
				'/assets/js/reviews-slideshow.js'
];

// Получаем данные для отображения
try {
    // Последние новости
    $latest_news = $pdo->query("SELECT * FROM news WHERE status = 'published' ORDER BY created_at DESC LIMIT 8")->fetchAll();
    
    // Проекты
    $latest_projects = $pdo->query("SELECT * FROM projects ORDER BY project_date DESC LIMIT 8")->fetchAll();
    
    // Одобренные отзывы
    $approved_reviews = $pdo->query("SELECT * FROM reviews WHERE status = 'approved' ORDER BY created_at DESC LIMIT 6")->fetchAll();
} catch (Exception $e) {
    error_log("Index page database error: " . $e->getMessage());
    $latest_news = [];
    $latest_projects = [];
    $approved_reviews = [];
}

// Включаем заголовок
require_once 'includes/header.php';
?>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-bg">
            <div class="hero-slider">
                <div class="hero-slide"></div>
                <div class="hero-slide"></div>
                <div class="hero-slide"></div>
                <div class="hero-slide"></div>
            </div>
        </div>
        <div class="hero-content">
            <h1 class="hero-title">Театр танца "СТОЛИЦА"</h1>
            <h2 class="hero-subtitle">Танцевальный мир Владимира и Ольги Журавлевых</h2>
            <p class="hero-description">
                15 лет мы дарим возможность учиться искусству танца у лучших педагогов и артистов Москвы.
                Наш театр - это место, где рождаются настоящие таланты и воплощаются танцевальные мечты.
            </p>
            <a href="https://wa.me/79999303660" target="_blank" rel="noopener noreferrer" class="hero-cta">
                <span>Записаться на занятие</span>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </section>

    <!-- Theater Info Section -->
    <section class="theater-info">
        <div class="container">
            <div class="theater-info-content">
                <div class="theater-info-title">
                    <h2>СТОЛИЦА</h2>
                </div>
                <div class="theater-info-text">
                    <p>
                        Детский Театр танца «Столица» задуман хореографами как центр эстетического 
                        и профессионального воспитания учащихся. Мы изучаем разные жанры хореографии, 
                        знакомимся с культурой танцев разных народов, развиваем артистические задатки детей.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="services">
        <!-- Background images -->
        <div class="services-bg">
            <div class="services-bg-image" data-bg="1"></div>
            <div class="services-bg-image" data-bg="2"></div>
            <div class="services-bg-image" data-bg="3"></div>
            <div class="services-bg-image" data-bg="4"></div>
            <div class="services-bg-image" data-bg="5"></div>
            <div class="services-bg-image" data-bg="6"></div>
        </div>

        <!-- Service items -->
        <a href="/classical-dance.php" class="services-item" data-bg="1">
            <div class="services-item-container">
                <div class="services-item-logo">
                    <h3>Классический танец</h3>
                </div>
                <p class="services-item-title">Основа хореографического искусства</p>
                <div class="services-item-description">
                    <p>Строгая система движений, формировавшаяся веками. Основа для изучения всех танцевальных направлений. Преподают солисты театра «Гжель».</p>
                </div>
            </div>
        </a>

        <a href="/folk-dance.php" class="services-item" data-bg="2">
            <div class="services-item-container">
                <div class="services-item-logo">
                    <h3>Народный танец</h3>
                </div>
                <p class="services-item-title">Культура танцев разных народов</p>
                <div class="services-item-description">
                    <p>Изучение богатейшего культурного наследия различных народов мира. Знакомство с традициями и обычаями через танец.</p>
                </div>
            </div>
        </a>

        <a href="/jazz-modern.php" class="services-item" data-bg="3">
            <div class="services-item-container">
                <div class="services-item-logo">
                    <h3>Джаз-модерн</h3>
                </div>
                <p class="services-item-title">Современная хореография</p>
                <div class="services-item-description">
                    <p>Динамичные движения корпуса, грациозные вращения и яркая импровизация. Освоение современных танцевальных техник.</p>
                </div>
            </div>
        </a>

        <a href="/baby-ballet.php" class="services-item" data-bg="4">
            <div class="services-item-container">
                <div class="services-item-logo">
                    <h3>Детский балет</h3>
                </div>
                <p class="services-item-title">Baby балет с 2 лет</p>
                <div class="services-item-description">
                    <p>Специальная программа раннего танцевального развития в игровой форме. Развитие чувства ритма, пластики и музыкальности.</p>
                </div>
            </div>
        </a>

        <a href="/gymnastics.php" class="services-item" data-bg="5">
            <div class="services-item-container">
                <div class="services-item-logo">
                    <h3>Партерная гимнастика</h3>
                </div>
                <p class="services-item-title">Растяжка и подготовка тела</p>
                <div class="services-item-description">
                    <p>Комплекс упражнений на полу для развития гибкости, укрепления мышц и улучшения осанки. Формирование мышечного корсета.</p>
                </div>
            </div>
        </a>

        <a href="/acrobatics.php" class="services-item" data-bg="6">
            <div class="services-item-container">
                <div class="services-item-logo">
                    <h3>Акробатика</h3>
                </div>
                <p class="services-item-title">Физическая подготовка и трюки</p>
                <div class="services-item-description">
                    <p>Развитие силы, гибкости, координации и пластики. Освоение трюковых элементов для танцевальных постановок.</p>
                </div>
            </div>
        </a>
    </section>

    <!-- Schedule Section -->
    <section id="schedule" class="schedule">
        <div class="container">
            <h2 class="section-title reveal">Расписание занятий</h2>
            
            <div class="schedule-selector">
                <div class="selector-group">
                    <label class="selector-label">Выберите филиал:</label>
                    <div class="selector-options">
                        <button class="selector-btn metro-btn" data-metro="lomonosovsky">м. Ломоносовский пр.</button>
                        <button class="selector-btn metro-btn" data-metro="belorusskaya">м. Белорусская</button>
                    </div>
                </div>
                
                <div class="selector-group" id="classTypeSelector" style="display: none;">
                    <label class="selector-label">Выберите тип занятий:</label>
                    <div class="selector-options">
                        <button class="selector-btn class-btn" data-class="classic">Классический танец</button>
                        <button class="selector-btn class-btn" data-class="folk">Народный танец</button>
                        <button class="selector-btn class-btn" data-class="modern">Современный танец</button>
                        <button class="selector-btn class-btn" data-class="kids">Детская хореография</button>
                    </div>
                </div>
            </div>

            <div class="schedule-table-wrapper" id="scheduleTableWrapper">
                <table class="schedule-table" id="scheduleTable">
                    <!-- Table content will be inserted by JavaScript -->
                </table>
            </div>
        </div>
    </section>
<!-- Stats Section (Наши достижения) -->
    <section id="stats" class="stats">
        <div class="container">
            <h2 class="section-title reveal">Наши достижения</h2>
            <div class="stats-grid">
                <div class="stat-item reveal">
                    <div class="stat-number">15+</div>
                    <div class="stat-text">Лет успешной работы</div>
                </div>
                <div class="stat-item reveal">
                    <div class="stat-number">20+</div>
                    <div class="stat-text">Лет артистического стажа</div>
                </div>
                <div class="stat-item reveal">
                    <div class="stat-number">20+</div>
                    <div class="stat-text">Авторских балетных спектаклей</div>
                </div>
                <div class="stat-item reveal">
                    <div class="stat-number">60+</div>
                    <div class="stat-text">Стран гастролей</div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section (О театре) -->
    <section id="about" class="about">
        <div class="container">
            <div class="about-content">
                <div class="about-text reveal">
                    <h2>О театре танца "Столица"</h2>
                    <p>Детский Театр танца «Столица» задуман хореографами как центр эстетического и профессионального воспитания учащихся.</p>
                    <p>Изучать разные жанры хореографии, знакомиться с культурой танцев разных народов, развивать артистические задатки детей — наша цель.</p>
                    <p>За время работы нами были поставлены множество танцевальных номеров и детские балетные спектакли, где дети выступали на одной сцене со взрослыми артистами и солистами Большого Театра России.</p>
                </div>
                <div class="directors reveal">
                    <h3>Руководители и педагоги</h3>
                    <h4>Владимир и Ольга Журавлёвы</h4>
                    <p><strong>Солисты Государственного академического театра танца «Гжель»</strong></p>
                    <p>Артистический стаж более 20 лет</p>
                    <p>Танцевали на сценах России, СНГ, США, Канады, Китая, Европы и других стран</p>
                    <p>Ветераны боевых действий</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section (Стоимость занятий) -->
    <section id="pricing" class="pricing">
        <div class="container">
            <h2 class="section-title reveal">Стоимость занятий на м.Белорусская</h2>
            <div class="pricing-table reveal">
                <div class="pricing-row">
                    <div class="pricing-service">Абонемент (групповой) - 16 занятий по 2 часа </div>
                    <div class="pricing-cost">16 000₽</div>
                </div>
                <div class="pricing-row">
                    <div class="pricing-service">Абонемент (групповой) для Мальчиков (скидка 30%)</div>
                    <div class="pricing-cost">11 200₽</div>
                </div>
                <div class="pricing-row">
                    <div class="pricing-service">Индивидуальное занятие</div>
                    <div class="pricing-cost">4 000₽</div>
                </div>
                <div class="pricing-note">
                    Действие групповых абонементов — 4 недели
                </div>
            </div>
			
			<h2 class="section-title reveal">Стоимость занятий на м.Ломоносовский проспект</h2>
            <div class="pricing-table reveal">
                <div class="pricing-row">
                    <div class="pricing-service">Абонемент на 8 занятий (1,5 часа)</div>
                    <div class="pricing-cost">20 000₽</div>
                </div>
                <div class="pricing-row">
                    <div class="pricing-service">Абонемент на 8 занятий (2 часа)</div>
                    <div class="pricing-cost">24 000₽</div>
                </div>
               <div class="pricing-row">
                    <div class="pricing-service">Разовое занятие (групповое — 55 минут)</div>
                    <div class="pricing-cost">3 000₽</div>
                </div>
                <div class="pricing-row">
                    <div class="pricing-service">Пробное занятие</div>
                    <div class="pricing-cost">от 1500</div>
                </div>
                <div class="pricing-row">
                    <div class="pricing-service">Индивидуальное занятие</div>
                    <div class="pricing-cost">4 000₽</div>
                </div>
				<div class="pricing-row">
                    <div class="pricing-service">Индивидуальное занятие блок из 5 занятий </div>
                    <div class="pricing-cost">18 000₽</div>
                </div>
				<div class="pricing-row">
                    <div class="pricing-service">Индивидуальное занятие блок из 10 занятий </div>
                    <div class="pricing-cost">35 000₽</div>
                </div>
                <div class="pricing-note">
                    Действие групповых абонементов — 4 недели
                </div>
            </div>
            
            <!-- Special Offers (Специальные предложения) -->
            <div class="promo-section reveal">
                <h3>Специальные предложения</h3>
                <div class="promo-cards">
                    <div class="promo-card">
                        <h4>Акция "Приведи друга"</h4>
                        <div class="discount">-50%</div>
                        <p>Скидка на следующий абонемент за каждого приведенного друга</p>
                    </div>
                    <div class="promo-card">
                        <h4>Для мальчиков</h4>
                        <div class="discount">-30%</div>
                        <p>Постоянная скидка на все групповые занятия</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


  <section class="news-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">
                <span>Н</span><span>о</span><span>в</span><span>о</span><span>с</span><span>т</span><span>и</span>
            </h2>
            <a href="/news.php" class="view-all-btn">
                <span>ВСЕ НОВОСТИ</span>
                <svg width="14" height="20" viewBox="0 0 14 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M14 7L12.59 8.41L8 3.83L8 20L6 20L6 3.83L1.41 8.42L3.0598e-07 7L7 -3.0598e-07L14 7Z" fill="currentColor"/>
                </svg>
            </a>
        </div>

        <div class="swiper news-slider">
            <div class="swiper-wrapper">
                <?php if (!empty($latest_news)): ?>
                    <?php foreach ($latest_news as $index => $item): ?>
                    <div class="swiper-slide">
                        <div class="news-card <?= $index === 0 ? 'news-card-large' : '' ?>" onclick="openNewsModal(<?= $item['id'] ?>)" data-news-id="<?= $item['id'] ?>">
                            <img src="<?= htmlspecialchars($item['image'] ?: 'https://stolitsa-dance.ru/wp-content/uploads/2024/05/photo_2024-05-11_12-19-27.jpg') ?>" 
                                 alt="<?= htmlspecialchars($item['title']) ?>" 
                                 class="news-image"
                                 loading="lazy">
                            <div class="news-content">
                                <div class="news-date"><?= date('d M Y', strtotime($item['created_at'])) ?> - <?= htmlspecialchars($item['category'] ?: 'Театр танца') ?></div>
                                <h3 class="news-title"><?= htmlspecialchars($item['title']) ?></h3>
                                <p class="news-excerpt"><?= htmlspecialchars($item['excerpt']) ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Демонстрационные новости -->
                    <div class="swiper-slide">
                        <div class="news-card news-card-large" onclick="openNewsModal('demo1')">
                            <img src="https://stolitsa-dance.ru/wp-content/uploads/2024/05/photo_2024-05-11_12-19-27.jpg" 
                                 alt="Новое и лучшее" 
                                 class="news-image"
                                 loading="lazy">
                            <div class="news-content">
                                <div class="news-date">5 июня 2025 - Театр танца</div>
                                <h3 class="news-title">НОВОЕ И ЛУЧШЕЕ: ТЕАТР TODES ПОКАЖЕТ «ПРЕВЬЮ»</h3>
                                <p class="news-excerpt">Премьера нового спектакля в нашем театре. Готовимся к грандиозному шоу с участием лучших артистов!</p>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="news-card" onclick="openNewsModal('demo2')">
                            <img src="https://stolitsa-dance.ru/wp-content/uploads/2024/05/photo_2024-05-11_12-21-08.jpg" 
                                 alt="Новый сезон" 
                                 class="news-image"
                                 loading="lazy">
                            <div class="news-content">
                                <div class="news-date">6 августа 2025 - Студии-школы</div>
                                <h3 class="news-title">НОВЫЙ СЕЗОН – НОВЫЕ ТАНЦЫ!</h3>
                                <p class="news-excerpt">Открыт набор на новый танцевальный сезон для всех возрастов. Присоединяйтесь к нам!</p>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="news-card" onclick="openNewsModal('demo3')">
                            <img src="https://stolitsa-dance.ru/wp-content/uploads/2022/09/jazz.jpg" 
                                 alt="Летние интенсивы" 
                                 class="news-image"
                                 loading="lazy">
                            <div class="news-content">
                                <div class="news-date">25 июля 2025 - Интенсивы</div>
                                <h3 class="news-title">ЛЕТНИЕ ТАНЦЕВАЛЬНЫЕ ИНТЕНСИВЫ</h3>
                                <p class="news-excerpt">Погрузитесь в мир танца этим летом с нашими интенсивными курсами. Ограниченные места!</p>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="news-card" onclick="openNewsModal('demo4')">
                            <img src="https://stolitsa-dance.ru/wp-content/uploads/2023/08/bb.jpeg" 
                                 alt="Мастер-классы" 
                                 class="news-image"
                                 loading="lazy">
                            <div class="news-content">
                                <div class="news-date">15 мая 2025 - Мастер-классы</div>
                                <h3 class="news-title">ОТКРЫТЫЕ МАСТЕР-КЛАССЫ</h3>
                                <p class="news-excerpt">Приглашаем всех желающих на открытые мастер-классы от ведущих педагогов театра.</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            
        </div>
    </div>
</section>

<!-- Reviews Section -->
    <section class="reviews-section">
        <div class="container">
            <h2 class="reviews-title">Отзывы наших учеников</h2>
            
            <div class="slideshow-container" id="reviewsSlideshow">
            <!-- Slide 1 -->
            <div class="slide active">
                <div class="reviews-grid">
                    <div class="review-item">
                        <img src="https://stolitsa-dance.ru/uploads/com/2.jpg" alt="Отзыв 1" loading="lazy">
                    </div>
                    <div class="review-item">
                        <img src="https://stolitsa-dance.ru/uploads/com/1.jpg" alt="Отзыв 2" loading="lazy">
                    </div>
                    <div class="review-item">
                        <img src="https://stolitsa-dance.ru/uploads/com/3.jpg" alt="Отзыв 3" loading="lazy">
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="slide">
                <div class="reviews-grid">
                    <div class="review-item">
                        <img src="https://stolitsa-dance.ru/uploads/com/4.jpg" alt="Отзыв 4" loading="lazy">
                    </div>
                    <div class="review-item">
                        <img src="https://stolitsa-dance.ru/uploads/com/5.jpg" alt="Отзыв 5" loading="lazy">
                    </div>
                    <div class="review-item">
                        <img src="https://stolitsa-dance.ru/uploads/com/6.jpg" alt="Отзыв 6" loading="lazy">
                    </div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="slide">
                <div class="reviews-grid">
                    <div class="review-item">
                        <img src="https://stolitsa-dance.ru/uploads/com/7.jpg" alt="Отзыв 7" loading="lazy">
                    </div>
                    <div class="review-item">
                        <img src="https://stolitsa-dance.ru/uploads/com/8.jpg" alt="Отзыв 8" loading="lazy">
                    </div>
                    <div class="review-item">
                        <img src="https://stolitsa-dance.ru/uploads/com/9.jpg" alt="Отзыв 9" loading="lazy">
                    </div>
                </div>
            </div>

            <!-- Navigation buttons -->
            <button class="nav-button prev" onclick="changeSlide(-1)" aria-label="Предыдущий слайд">❮</button>
            <button class="nav-button next" onclick="changeSlide(1)" aria-label="Следующий слайд">❯</button>

            <!-- Mobile navigation -->
            <div class="nav-controls" style="display: none;">
                <button class="nav-button" onclick="changeSlide(-1)" aria-label="Предыдущий слайд">❮</button>
                <button class="nav-button" onclick="changeSlide(1)" aria-label="Следующий слайд">❯</button>
            </div>

            <!-- Progress bar -->
            <div class="progress-bar"></div>
        </div>

            <!-- Indicators -->
            <div class="indicators">
                <span class="indicator active" onclick="currentSlide(1)" aria-label="Слайд 1"></span>
                <span class="indicator" onclick="currentSlide(2)" aria-label="Слайд 2"></span>
                <span class="indicator" onclick="currentSlide(3)" aria-label="Слайд 3"></span>
            </div>
        </div>
    </section>

<!-- Модальные окна -->
<div id="newsModal" class="news-modal">
    <div class="news-modal-content">
        <div class="news-modal-header" id="newsModalHeader">
            <button class="news-modal-close" onclick="closeNewsModal()">&times;</button>
        </div>
        <div class="news-modal-body">
            <div class="news-modal-date" id="newsModalDate"></div>
            <h2 class="news-modal-title" id="newsModalTitle"></h2>
            <div class="news-modal-text" id="newsModalText"></div>
        </div>
    </div>
</div>

<div id="projectModal" class="news-modal">
    <div class="news-modal-content">
        <div class="news-modal-header" id="projectModalHeader">
            <button class="news-modal-close" onclick="closeProjectModal()">&times;</button>
        </div>
        <div class="news-modal-body">
            <div class="news-modal-date" id="projectModalDate"></div>
            <h2 class="news-modal-title" id="projectModalTitle"></h2>
            <div class="news-modal-text" id="projectModalText"></div>
        </div>
    </div>
</div>

<?php
// Включаем подвал
require_once 'includes/footer.php';
?>