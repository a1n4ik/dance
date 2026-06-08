<?php
session_start();
require_once 'config/database.php';
require_once 'router.php';
require_once 'config/errors.php';

// SEO мета-данные
$page_title = "Джаз-модерн - Современная хореография для детей и взрослых | Театр танца Столица";
$page_description = "Обучение современному танцу джаз-модерн в Москве. Контемпорари, афро-джаз, эстрадный танец. Развитие пластики, импровизации и творческого самовыражения.";
$page_keywords = "джаз модерн, современный танец, контемпорари, афро-джаз, эстрадный танец, импровизация, современная хореография";

// Дополнительные CSS файлы
$additional_css = [
    '/assets/css/home.css',
	'/assets/css/improved-news.css',
	'/assets/css/main.css',
	'/assets/css/pages.css'
];

// Дополнительные JS файлы
$additional_js = [
    '/assets/js/main.js',
    '/assets/js/services-interactive.js',
    '/assets/js/sliders.js',
    '/assets/js/improved-news.js'
];
// Включаем заголовок
require_once 'includes/header.php';
?>

    <!-- Page Hero -->
    <section class="page-hero page-hero-modern">
        <div class="container">
            <div class="page-hero-content">
                <h1 class="page-title">Джаз-модерн</h1>
                <p class="page-subtitle">Современная хореография</p>
            </div>
        </div>
    </section>

    <!-- Breadcrumbs -->
    <section class="breadcrumbs">
        <div class="container">
            <ul class="breadcrumbs-list">
                <li><a href="/">Главная</a></li>
                <li><a href="/#services">Направления</a></li>
                <li>Джаз-модерн</li>
            </ul>
        </div>
    </section>

    <!-- Content Section -->
    <section class="content-section">
        <div class="container">
            <div class="content-grid">
                <div class="content-main">
                    <h2>О направлении джаз-модерн</h2>
                    <p>
                        Джаз-модерн – это современное танцевальное направление, которое объединяет в себе 
                        элементы джазового танца, модерна и контемпорари. Это направление очень экспрессивно, 
                        позволяет при помощи движений выразить сложные эмоции и чувства.
                    </p>
                    <p>
                        Современную хореографию отличают динамичные и свободные движения корпуса, грациозные 
                        вращения и прыжки, яркая импровизация. На занятиях изучается техника контемпорари, 
                        афро-джаза, диско-танца, эстрады, современной поп-культуры, адаптированной под детский возраст.
                    </p>

                    <h3>Особенности джаз-модерна</h3>
                    <ul>
                        <li>Свобода движений и импровизация</li>
                        <li>Работа с различными уровнями пространства</li>
                        <li>Изоляция различных частей тела</li>
                        <li>Сочетание плавности и резкости движений</li>
                        <li>Эмоциональная выразительность</li>
                        <li>Использование современной музыки</li>
                    </ul>

                    <h3>Что включает программа обучения</h3>
                    <ul>
                        <li>Основы джазового танца</li>
                        <li>Техника модерн и контемпорари</li>
                        <li>Элементы афро-джаза</li>
                        <li>Эстрадный танец</li>
                        <li>Импровизация и композиция</li>
                        <li>Работа с ритмом и музыкальностью</li>
                        <li>Развитие артистизма</li>
                    </ul>

                    <h3>Польза занятий джаз-модерном</h3>
                    <p>
                        Занятия современной хореографией развивают не только физические данные, но и 
                        творческое мышление, способность к самовыражению через движение.
                    </p>
                    <ul>
                        <li>Развитие гибкости и пластичности</li>
                        <li>Улучшение координации движений</li>
                        <li>Формирование чувства ритма</li>
                        <li>Развитие творческого потенциала</li>
                        <li>Укрепление мышечного корсета</li>
                        <li>Повышение уверенности в себе</li>
                    </ul>

                    <h3>Возрастные группы</h3>
                    <p>
                        Мы предлагаем занятия джаз-модерном для детей от 6 лет. Группы формируются 
                        по возрасту и уровню подготовки.
                    </p>
                    <ul>
                        <li>6-8 лет – основы современного танца</li>
                        <li>9-11 лет – базовая техника джаз-модерна</li>
                        <li>12-14 лет – продвинутый уровень</li>
                        <li>15+ лет – профессиональная подготовка</li>
                    </ul>
                </div>

                <div class="content-sidebar">
                    <div class="sidebar-card">
                        <h3> Записаться на занятие</h3>
                                                <a href="https://wa.me/79154134347?text=Здравствуйте! Хочу записаться на джаз-модерн" target="_blank" class="cta-button">
                            Записаться на занятие
                        </a>
                    </div>

                    <div class="sidebar-card">
                        <h3>Расписание</h3>
                        <p style="margin-bottom: 1rem;">Занятия проходят:</p>
                        <ul style="list-style: none; padding: 0;">
                            <li style="margin-bottom: 0.5rem;">📍 м. Ломоносовский пр.</li>
                            <li style="margin-bottom: 0.5rem;">📍 м. Белорусская</li>
                        </ul>
                        <a href="/#schedule" class="cta-button">Смотреть расписание</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="gallery-section">
        <div class="container">
            <h2 style="text-align: center; font-size: 3rem; margin-bottom: 1rem;">Фотогалерея</h2>
            <p style="text-align: center; color: #666; margin-bottom: 3rem;">Моменты с наших занятий</p>
            
            <div class="gallery-grid">
				<div class="gallery-grid">
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/jazz/1.webp?w=600" alt="Джаз-модерн">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/jazz/2.webp?w=600" alt="Джаз-модерн">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/jazz/3.webp?w=600" alt="Джаз-модерн">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/jazz/4.webp?w=600" alt="Джаз-модерн">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/jazz/5.webp?w=600" alt="Джаз-модерн">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/jazz/6.webp?w=600" alt="Джаз-модерн">
                </div>
				<div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/jazz/7.webp?w=600" alt="Джаз-модерн">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/jazz/8.webp?w=600" alt="Современный танец">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/jazz/9.webp?w=600" alt="Урок джаз-модерна">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/jazz/10.webp?w=600" alt="Урок джаз-модерна">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/jazz/11.webp?w=600" alt="Урок джаз-модерна">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/jazz/12.webp?w=600" alt="Выступление">
                </div>
				<div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/jazz/13.webp?w=600" alt="Выступление">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/jazz/14.webp?w=600" alt="Джаз-модерн">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/jazz/15.webp?w=600" alt="Современный танец">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/jazz/16.webp?w=600" alt="Современный танец">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/jazz/17.webp?w=600" alt="Современный танец">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/jazz/18.webp?w=600" alt="Выступление">
                </div>
				<div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/jazz/19.webp?w=600" alt="Джаз-модерн">
                </div>
				<div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/jazz/20.webp?w=600" alt="Современный танец">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/jazz/21.webp?w=600" alt="Современный танец">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/jazz/22.webp?w=600" alt="Современный танец">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/jazz/23.webp?w=600" alt="Выступление">
                </div>
				<div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/jazz/24.webp?w=600" alt="Джаз-модерн">
                </div>
            </div>
        </div>
    </section>
	<!-- Teachers Section -->
    <section class="teachers-section">
        <div class="container">
            <h2 style="text-align: center; font-size: 3rem; margin-bottom: 1rem;">Наши педагоги</h2>
            <div class="teachers-grid">
                <div class="teacher-card">
                    <div class="teacher-photo">
                        <img src="https://stolitsa-dance.ru/uploads/teach/v.JPG" alt="Владимир Журавлёв">
                    </div>
                    <h3 class="teacher-name">Владимир Журавлёв</h3>
                    <p class="teacher-title">Солист театра танца «Гжель»</p>
                    <p class="teacher-bio">
                        Артистический стаж более 20 лет. Танцевал на сценах России, СНГ, США, 
                        Канады, Китая, Европы и других стран. Ветеран боевых действий.
                    </p>
                </div>
                <div class="teacher-card">
                    <div class="teacher-photo">
                        <img src="https://stolitsa-dance.ru/uploads/teach/o.JPG" alt="Ольга Журавлёва">
                    </div>
                    <h3 class="teacher-name">Ольга Журавлёва</h3>
                    <p class="teacher-title">Солистка театра танца «Гжель»</p>
                    <p class="teacher-bio">
                        Артистический стаж более 20 лет. Лауреат международных конкурсов. 
                        Специализируется на работе с детьми и подростками.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <?php
// Включаем подвал
require_once 'includes/footer.php';
?>