<?php
session_start();
require_once 'config/database.php';
require_once 'router.php';
require_once 'config/errors.php';
// SEO мета-данные
$page_title = "Танцевальная практика - с 5 лет | Театр танца Столица";
$page_description = "Танцевальная практика от 5 лет в Москве. Игровая методика обучения, развитие чувства ритма, пластики и музыкальности. Первые шаги в мире танца.";
$page_keywords = "Танцевальная практика с 5 лет, детская хореография, раннее развитие, ритмика";

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
    <section class="page-hero page-hero-baby">
        <div class="container">
            <div class="page-hero-content">
                <h1 class="page-title">Танцевальная практика</h1>
                <p class="page-subtitle">Танцевальная практика с 5 лет</p>
            </div>
        </div>
    </section>

    <!-- Breadcrumbs -->
    <section class="breadcrumbs">
        <div class="container">
            <ul class="breadcrumbs-list">
                <li><a href="/">Главная</a></li>
                <li><a href="/#services">Направления</a></li>
                <li>Танцевальная практика</li>
            </ul>
        </div>
    </section>

    <!-- Content Section -->
    <section class="content-section">
        <div class="container">
            <div class="content-grid">
                <div class="content-main">
                    <h2>Зачем нужны танцевальные практики и репетиции?</h2>
                    <p>
                        Танцевальная практика – Практики позволяют постепенно улучшать технику танца,
						доводя движения до автоматизма. Регулярные тренировки помогают телу запомнить
						правильные положения и траектории движений, минимизируя риск ошибок на выступлениях.
.                    </p>

                    <h3>Основные цели</h3>
                    <ul>
                        <li>Совершенствование техники исполнения движений</li>
                        <li>Развитие артистического мастерства</li>
                        <li>Улучшение взаимодействия в группе</li>
						<li>Подготовка к выступлению</li>
                    </ul>

                    

                    <h3>Польза занятий </h3>
                    <p>
                        Регулярная танцевальная практика и репетиция 
						являются необходимыми элементами успешного 
						развития танцовщика и достижения высоких результатов в искусстве танца..
                    </p>
                    <ul>
                        <li>Формирование правильной осанки с раннего возраста</li>
                        <li>Развитие музыкального слуха и чувства ритма</li>
                        <li>Укрепление мышечного корсета</li>
                        <li>Развитие памяти и внимания</li>
                        <li>Социализация и умение работать в группе</li>
                        <li>Повышение уверенности в себе</li>
                        <li>Развитие творческого воображения</li>
                    </ul>

                    <h3>Как проходят занятия</h3>
                    <p>
                        Во время репетиции юные артисты учатся 
						передавать эмоции и настроение через танец,
						развивать выразительность жестов и мимики. 
						Это помогает создать уникальный образ на сцене и сделать выступление запоминающимся.
                    </p>
                </div>

                <div class="content-sidebar">
                    <div class="sidebar-card">
                        <h3>Записаться на занятие</h3>
                        
                        <a href="https://wa.me/79154134347?text=Здравствуйте! Хочу записать ребенка на практику" target="_blank" class="cta-button">
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
            <p style="text-align: center; color: #666; margin-bottom: 3rem;">Наши звездочки</p>
            
            <div class="gallery-grid">
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/baby/1.webp" alt="Выступление">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/baby/2.webp" alt="Выступление">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/baby/3.webp" alt="Выступление">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/baby/4.webp" alt="Выступление">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/baby/5.webp" alt="Выступление">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/baby/6.webp" alt="Выступление">
                </div>
				<div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/baby/7.webp" alt="Выступление">
                </div>
				<div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/baby/8.webp" alt="Выступление">
                </div>
				<div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/baby/9.webp" alt="Выступление">
                </div>
				<div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/baby/10.webp" alt="Выступление">
                </div>
				<div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/baby/11.webp" alt="Выступление">
                </div>
				<div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/baby/12.webp" alt="Выступление">
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