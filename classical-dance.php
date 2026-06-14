<?php
session_start();
require_once 'config/database.php';
require_once 'router.php';
require_once 'config/errors.php';
// SEO мета-данные
$page_title = "Классический танец и балет в Москве | Театр танца Столица";
$page_description = "Занятия классическим танцем и балетом в Москве. Профессиональная хореографическая подготовка для детей и взрослых. Педагоги — солисты театра «Гжель».";
$page_keywords = "классический танец, балет, хореография, классика, москва, дети, взрослые, балетная школа";

// Дополнительные CSS файлы
$additional_css = [
    '/assets/css/pages.css'
];

// Дополнительные JS файлы
$additional_js = [
    '/assets/js/main.js',
    '/assets/js/services-interactive.js'
];
// Включаем заголовок
require_once 'includes/header.php';
?>

    <!-- Page Hero -->
    <section class="page-hero">
        <div class="container">
            <div class="page-hero-content">
                <h1 class="page-title">Классический танец</h1>
                <p class="page-subtitle">Фундамент хореографического мастерства</p>
            </div>
        </div>
    </section>

    <!-- Breadcrumbs -->
    <section class="breadcrumbs">
        <div class="container">
            <ul class="breadcrumbs-list">
                <li><a href="/">Главная</a></li>
                <li><a href="/#services">Направления</a></li>
                <li>Классический танец</li>
            </ul>
        </div>
    </section>

    <!-- Content Section -->
    <section class="content-section">
        <div class="container">
            <div class="content-grid">
                <div class="content-main">
                    <h2>О классическом танце</h2>
                    <p>
                        Классический танец — это основа основ всей хореографии. Именно классика
                        формирует правильную осанку, координацию и культуру движения, которые
                        необходимы в любом танцевальном направлении.
                    </p>
                    <p>
                        Занятия классическим танцем в нашем театре строятся на проверенной
                        методике обучения. Дети и взрослые осваивают технику исполнения,
                        развивают пластику, музыкальность и артистизм.
                    </p>

                    <h3>Цели и задачи обучения</h3>
                    <p>
                        Главная цель уроков классического танца — заложить прочную хореографическую
                        базу и воспитать культуру движения.
                    </p>
                    <ul>
                        <li>Профессиональная постановка корпуса, рук и ног</li>
                        <li>Развитие выворотности, гибкости и силы мышц</li>
                        <li>Освоение основных позиций и движений классического экзерсиса</li>
                        <li>Воспитание музыкальности и чувства ритма</li>
                        <li>Развитие выразительности и артистизма</li>
                        <li>Укрепление опорно-двигательного аппарата</li>
                    </ul>

                    <h3>Что развивает классический танец</h3>
                    <ul>
                        <li>Правильную осанку и грацию</li>
                        <li>Координацию движений и пластику</li>
                        <li>Музыкальность и ритмичность</li>
                        <li>Дисциплину и трудолюбие</li>
                        <li>Физическую выносливость</li>
                        <li>Эстетический вкус</li>
                    </ul>

                    <h3>Выступления</h3>
                    <p>
                        Ученики регулярно демонстрируют свои достижения на концертах, фестивалях
                        и конкурсах, исполняя классические номера и фрагменты балетных постановок.
                    </p>
                </div>

                <div class="content-sidebar">
                    <div class="sidebar-card">
                        <h3>Записаться на занятие</h3>

                        <a href="https://wa.me/79154134347?text=Здравствуйте! Хочу записаться на классический танец" target="_blank" class="cta-button">
                            Записаться на занятие
                        </a>
                    </div>
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
