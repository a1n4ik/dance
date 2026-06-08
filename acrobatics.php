<?php
session_start();
require_once 'config/database.php';
require_once 'router.php';
require_once 'config/errors.php';
// SEO мета-данные
$page_title = "Акробатика - Физическая подготовка и трюки | Театр танца Столица";
$page_description = "Обучение акробатике в Москве. Развитие силы, гибкости, координации. Акробатические элементы для танцоров. Безопасное освоение трюков под руководством профессионалов.";
$page_keywords = "акробатика, акробатические элементы, физическая подготовка, координация, сила, гибкость, трюки, спортивная акробатика";

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
    <section class="page-hero page-hero-acrobatics">
        <div class="container">
            <div class="page-hero-content">
                <h1 class="page-title">Акробатика</h1>
                <p class="page-subtitle">Физическая подготовка и трюковые элементы</p>
            </div>
        </div>
    </section>

    <!-- Breadcrumbs -->
    <section class="breadcrumbs">
        <div class="container">
            <ul class="breadcrumbs-list">
                <li><a href="/">Главная</a></li>
                <li><a href="/#services">Направления</a></li>
                <li>Акробатика</li>
            </ul>
        </div>
    </section>

    <!-- Content Section -->
    <section class="content-section">
        <div class="container">
            <div class="content-grid">
                <div class="content-main">
                    <h2>Об акробатике</h2>
                    <p>
                        Акробатика – это вид физической подготовки, направленный на развитие 
                        силы, гибкости, координации и пластики. Акробатические элементы являются 
                        неотъемлемой частью современной хореографии и придают танцу зрелищность.
                    </p>
                    <p>
                        На занятиях акробатикой дети осваивают различные трюковые элементы, 
                        которые затем используются в танцевальных постановках. Все упражнения 
                        выполняются с соблюдением техники безопасности под руководством опытных педагогов.
                    </p>

                    <h3>Основные направления</h3>
                    <ul>
                        <li>Базовая акробатика</li>
                        <li>Партерная акробатика</li>
                        <li>Прыжковая акробатика</li>
                        <li>Парная акробатика</li>
                        <li>Танцевальная акробатика</li>
                        <li>Силовые элементы</li>
                    </ul>

                    <h3>Изучаемые элементы</h3>
                    <ul>
                        <li>Кувырки вперед и назад</li>
                        <li>Колесо и рондат</li>
                        <li>Стойки на руках</li>
                        <li>Мостики и перевороты</li>
                        <li>Шпагаты в прыжке</li>
                        <li>Различные виды сальто</li>
                        <li>Акробатические связки</li>
                    </ul>

                    <h3>Польза занятий акробатикой</h3>
                    <p>
                        Акробатика развивает не только физические качества, но и характер. 
                        Дети учатся преодолевать страхи, становятся более уверенными в себе 
                        и целеустремленными.
                    </p>
                    <ul>
                        <li>Развитие силы всех групп мышц</li>
                        <li>Улучшение координации и баланса</li>
                        <li>Развитие гибкости и пластичности</li>
                        <li>Формирование правильной осанки</li>
                        <li>Развитие вестибулярного аппарата</li>
                        <li>Повышение выносливости</li>
                        <li>Воспитание смелости и решительности</li>
                    </ul>

                    <h3>Безопасность на занятиях</h3>
                    <p>
                        Безопасность детей – наш главный приоритет. Все занятия проводятся 
                        в специально оборудованных залах с использованием профессиональных матов 
                        и страховочного оборудования.
                    </p>
                    <ul>
                        <li>Обязательная разминка перед занятием</li>
                        <li>Постепенное усложнение элементов</li>
                        <li>Индивидуальный подход к каждому ребенку</li>
                        <li>Профессиональная страховка при выполнении элементов</li>
                        <li>Использование защитного оборудования</li>
                    </ul>

                    <h3>Возрастные группы</h3>
                    <ul>
                        <li>5-7 лет – основы акробатики в игровой форме</li>
                        <li>8-10 лет – базовые акробатические элементы</li>
                        <li>11-13 лет – сложные элементы и связки</li>
                        <li>14+ лет – профессиональная акробатика</li>
                    </ul>
                </div>

                <div class="content-sidebar">
                    <div class="sidebar-card">
                        <h3>Записаться на занятие</h3>
                        
                        <a href="https://wa.me/79154134347?text=Здравствуйте! Хочу записаться на акробатику" target="_blank" class="cta-button">
                            Записаться на занятие
                        </a>
                    </div>

                    <div class="sidebar-card">
                        <h3>Важная информация</h3>
                        <p style="margin-bottom: 1rem; color: #666;">
                            Перед началом занятий акробатикой необходима справка от врача 
                            о допуске к физическим нагрузкам.
                        </p>
                        <p style="color: #666;">
                            Все элементы изучаются постепенно, с учетом индивидуальных 
                            особенностей каждого ребенка.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Gallery Section -->
    <section class="gallery-section">
        <div class="container">
            <h2 style="text-align: center; font-size: 3rem; margin-bottom: 1rem;">Фотогалерея</h2>
            
            <div class="gallery-grid">
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/acr/1.jpg?w=600" alt="Акробатика">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/jazz/15.webp?w=600" alt="Акробатические танец">
                </div>
				<div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/jazz/13.webp?w=600" alt="Акробатические элементы">
                </div>
				<div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/jazz/10.webp?w=600" alt="Акробатические элементы">
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