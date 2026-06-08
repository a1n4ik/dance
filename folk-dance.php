<?php
session_start();
require_once 'config/database.php';
require_once 'router.php';
require_once 'config/errors.php';
// SEO мета-данные
$page_title = "Народный танец - Изучение культуры народов мира | Театр танца Столица";
$page_description = "Занятия народным танцем в Москве. Изучение народных танцев. Знакомство с культурой и традициями разных стран.";
$page_keywords = "народный танец, русский танец,культура народов, традиционные танцы";

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
    <section class="page-hero page-hero-folk">
        <div class="container">
            <div class="page-hero-content">
                <h1 class="page-title">Народный танец</h1>
                <p class="page-subtitle">Культура танцев разных народов мира</p>
            </div>
        </div>
    </section>

    <!-- Breadcrumbs -->
    <section class="breadcrumbs">
        <div class="container">
            <ul class="breadcrumbs-list">
                <li><a href="/">Главная</a></li>
                <li><a href="/#services">Направления</a></li>
                <li>Народный танец</li>
            </ul>
        </div>
    </section>

    <!-- Content Section -->
    <section class="content-section">
        <div class="container">
            <div class="content-grid">
                <div class="content-main">
                    <h2>О народном танце</h2>
                    <p>
                        Народный танец – это важнейшее танцевальное направление, являющееся составной частью 
                        художественно-эстетического образования и воспитания. Это уникальная возможность 
                        познакомиться с богатейшим культурным наследием различных народов мира.
                    </p>
                    <p>
                        Занятия народным танцем в нашем театре позволяют детям не только освоить технику 
                        исполнения различных национальных танцев, но и погрузиться в культуру и традиции 
                        разных стран и народов.
                    </p>

                    <h3>Цели и задачи обучения</h3>
                    <p>
                        Главная цель урока народного танца заключается в познании природы танца разных 
                        национальностей, в освоении методики и техники его исполнения.
                    </p>
                    <ul>
                        <li>Профессиональная постановка корпуса, рук и ног</li>
                        <li>Укрепление и развитие всего опорно-двигательного аппарата</li>
                        <li>Воспитание музыкальности и чувства ритма</li>
                        <li>Развитие выразительности движения</li>
                        <li>Знакомство с культурой и традициями разных народов мира</li>
                        <li>Изучение характерных особенностей национальных танцев</li>
                    </ul>



                    <h3>Что развивает народный танец</h3>
                    <ul>
                        <li>Координацию движений и пластику</li>
                        <li>Музыкальность и ритмичность</li>
                        <li>Артистизм и эмоциональность</li>
                        <li>Знание культурных традиций</li>
                        <li>Умение работать в ансамбле</li>
                        <li>Физическую выносливость</li>
                    </ul>

                    <h3>Костюмы и выступления</h3>
                    <p>
                        Наш театр обладает богатой коллекцией аутентичных народных костюмов. 
                        Дети регулярно выступают на концертах, фестивалях и конкурсах, 
                        демонстрируя мастерство исполнения народных танцев в традиционных костюмах.
                    </p>
                </div>

                <div class="content-sidebar">
                    <div class="sidebar-card">
                        <h3>Записаться на занятие</h3>
                        
                        <a href="https://wa.me/79154134347?text=Здравствуйте! Хочу записаться на народный танец" target="_blank" class="cta-button">
                            Записаться на занятие
                        </a>
                    </div>

                    
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="gallery-section">
        <div class="container">
            <h2 style="text-align: center; font-size: 3rem; margin-bottom: 1rem;">Фотогалерея</h2>
            <p style="text-align: center; color: #666; margin-bottom: 3rem;">Яркие моменты наших выступлений</p>
            
            <div class="gallery-grid">
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/falk/1.webp" alt="Народный танец">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/falk/2.webp" alt="Русский народный танец">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/falk/3.webp" alt="Народные костюмы">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/falk/4.webp" alt="Выступление ансамбля">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/falk/5.webp" alt="Концерт">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/falk/6.webp" alt="Народные костюмы">
                </div>
				 <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/falk/7.webp" alt="Народный танец">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/falk/8.webp" alt="Русский народный танец">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/falk/9.webp" alt="Народные костюмы">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/falk/10.webp" alt="Выступление ансамбля">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/falk/11.webp" alt="Концерт">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/falk/12.webp" alt="Народные костюмы">
                </div>
				 <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/falk/13.webp" alt="Народный танец">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/falk/14.webp" alt="Русский народный танец">
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