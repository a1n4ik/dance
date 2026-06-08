<?php
session_start();
require_once 'config/database.php';
require_once 'router.php';
require_once 'config/errors.php';
// SEO мета-данные
$page_title = "Партерная гимнастика - Растяжка и подготовка тела к танцу | Театр танца Столица";
$page_description = "Занятия партерной гимнастикой в Москве. Развитие гибкости, растяжка, укрепление мышц. Формирование мышечного корсета и правильной осанки.";
$page_keywords = "партерная гимнастика, растяжка, гибкость, стретчинг, укрепление мышц, осанка, подготовка к танцу";

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
    <section class="page-hero page-hero-gymnastics">
        <div class="container">
            <div class="page-hero-content">
                <h1 class="page-title">Партерная гимнастика</h1>
                <p class="page-subtitle">Растяжка и подготовка тела</p>
            </div>
        </div>
    </section>

    <!-- Breadcrumbs -->
    <section class="breadcrumbs">
        <div class="container">
            <ul class="breadcrumbs-list">
                <li><a href="/">Главная</a></li>
                <li><a href="/#services">Направления</a></li>
                <li>Партерная гимнастика</li>
            </ul>
        </div>
    </section>

    <!-- Content Section -->
    <section class="content-section">
        <div class="container">
            <div class="content-grid">
                <div class="content-main">
                    <h2>О партерной гимнастике</h2>
                    <p>
                        Партерная гимнастика – это комплекс упражнений, выполняемых на полу (партере), 
                        направленных на развитие гибкости, укрепление мышц и улучшение осанки. 
                        Этот вид занятий является важной составляющей подготовки танцоров любого уровня.
                    </p>
                    <p>
                        Наш комплекс разработан и адаптирован для детей с разными физическими и 
                        анатомическими данными. Занятия помогают повысить гибкость суставов, 
                        улучшить эластичность мышц и связок, сформировать мышечный корсет.
                    </p>

                    <h3>Основные направления работы</h3>
                    <ul>
                        <li>Развитие гибкости позвоночника</li>
                        <li>Укрепление мышц спины и пресса</li>
                        <li>Улучшение растяжки ног</li>
                        <li>Развитие выворотности</li>
                        <li>Работа над стопами</li>
                        <li>Формирование правильной осанки</li>
                        <li>Развитие координации движений</li>
                    </ul>

                    <h3>Что включает программа</h3>
                    <ul>
                        <li>Упражнения на развитие гибкости спины</li>
                        <li>Растяжка на шпагаты (продольный и поперечный)</li>
                        <li>Упражнения для развития стоп</li>
                        <li>Работа над выворотностью ног</li>
                        <li>Укрепление мышечного корсета</li>
                        <li>Элементы йоги и пилатеса</li>
                        <li>Дыхательные упражнения</li>
                    </ul>

                    <h3>Польза партерной гимнастики</h3>
                    <p>
                        Регулярные занятия партерной гимнастикой способствуют гармоничному 
                        физическому развитию ребенка и подготавливают тело к более сложным 
                        танцевальным элементам.
                    </p>
                    <ul>
                        <li>Улучшение осанки и походки</li>
                        <li>Развитие гибкости всего тела</li>
                        <li>Укрепление всех групп мышц</li>
                        <li>Профилактика сколиоза и плоскостопия</li>
                        <li>Повышение выносливости</li>
                        <li>Снижение риска травм при танцах</li>
                        <li>Улучшение координации движений</li>
                    </ul>

                    <h3>Для кого подходят занятия</h3>
                    <p>
                        Партерная гимнастика подходит для детей любого уровня подготовки от 4 лет. 
                        Особенно рекомендуется как дополнение к занятиям классическим танцем, 
                        народным танцем и современной хореографией.
                    </p>
                    <ul>
                        <li>Начинающим танцорам для базовой подготовки</li>
                        <li>Продолжающим для улучшения техники</li>
                        <li>Профессионалам для поддержания формы</li>
                        <li>Детям с нарушениями осанки</li>
                        <li>Для общего физического развития</li>
                    </ul>
                </div>

                <div class="content-sidebar">
                    <div class="sidebar-card">
                        <h3>Записаться на занятие</h3>
                        
                        <a href="https://wa.me/79154134347?text=Здравствуйте! Хочу записаться на партерную гимнастику" target="_blank" class="cta-button">
                            Записаться на занятие
                        </a>
                    </div>

                    <div class="sidebar-card">
                        <h3>Что понадобится</h3>
                        <ul style="list-style: none; padding: 0;">
                            <li style="margin-bottom: 0.8rem;">👕 Удобная спортивная форма</li>
                            <li style="margin-bottom: 0.8rem;">🧦 Носочки или чешки</li>
                            <li style="margin-bottom: 0.8rem;">🧘 Коврик для йоги</li>
                            <li style="margin-bottom: 0.8rem;">💧 Вода</li>
                        </ul>
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
                    <img src="https://stolitsa-dance.ru/uploads/part/1.webp?w=600" alt="Партерная гимнастика">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/part/2.webp?w=600" alt="Растяжка">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/part/3.webp?w=600" alt="Упражнения на полу">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/part/4.webp?w=600" alt="Гимнастика">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/part/5.webp?w=600" alt="Занятие">
                </div>
                <div class="gallery-item">
                    <img src="https://stolitsa-dance.ru/uploads/part/6.webp?w=600" alt="Растяжка на шпагат">
                </div>
            </div>
        </div>
    </section>
	<!-- Teachers Section -->
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