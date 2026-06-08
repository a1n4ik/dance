<?php
// ========== privacy.php - Политика конфиденциальности ==========
session_start();
require_once 'config/database.php';
require_once 'router.php';
require_once 'config/errors.php';

// SEO мета-данные
$page_title = "Политика конфиденциальности - Театр танца Столица";
$page_description = "Политика обработки персональных данных и использования файлов cookie на сайте театра танца Столица. Информация о защите ваших данных.";
$page_keywords = "политика конфиденциальности, персональные данные, cookie, защита данных, театр танца столица";

// Дополнительные CSS файлы
$additional_css = [
    '/assets/css/pages.css'
];

// Дополнительные JS файлы
$additional_js = [];

// Включаем заголовок
require_once 'includes/header.php';
?>

<style>
/* Стили для страницы Privacy Policy */
.privacy-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-align: center;
    padding: 8rem 0 4rem;
    margin-bottom: 3rem;
}

.privacy-hero h1 {
    font-size: 3rem;
    margin-bottom: 1rem;
    font-weight: 900;
}

.privacy-hero p {
    font-size: 1.2rem;
    opacity: 0.9;
}

.privacy-content {
    padding: 2rem 0 4rem;
    background: white;
}

.content-wrapper {
    max-width: 800px;
    margin: 0 auto;
    padding: 0 2rem;
    line-height: 1.7;
}

.privacy-section {
    margin-bottom: 3rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #eee;
}

.privacy-section:last-child {
    border-bottom: none;
}

.privacy-section h2 {
    color: #333;
    font-size: 1.8rem;
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #667eea;
}

.privacy-section h3 {
    color: #555;
    font-size: 1.3rem;
    margin: 2rem 0 1rem;
}

.privacy-section p {
    color: #666;
    margin-bottom: 1rem;
    text-align: justify;
}

.privacy-section ul {
    margin: 1rem 0 1rem 2rem;
    color: #666;
}

.privacy-section li {
    margin-bottom: 0.5rem;
}

.contact-info {
    background: #f8f9fa;
    padding: 2rem;
    border-radius: 10px;
    margin-top: 2rem;
}

.contact-info h3 {
    color: #333;
    margin-bottom: 1rem;
}

.contact-info p {
    margin-bottom: 0.5rem;
}

.contact-info strong {
    color: #667eea;
}

.breadcrumbs {
    background: #f8f9fa;
    padding: 1rem 0;
}

.breadcrumbs-list {
    display: flex;
    list-style: none;
    gap: 1rem;
    margin: 0;
    padding: 0;
}

.breadcrumbs-list li {
    position: relative;
}

.breadcrumbs-list li:not(:last-child)::after {
    content: ">";
    margin-left: 1rem;
    color: #999;
}

.breadcrumbs-list a {
    color: #667eea;
    text-decoration: none;
}

.breadcrumbs-list a:hover {
    text-decoration: underline;
}

.update-date {
    background: #e3f2fd;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 2rem;
    text-align: center;
    color: #1976d2;
    font-weight: 500;
}

@media (max-width: 768px) {
    .privacy-hero {
        padding: 6rem 0 3rem;
    }
    
    .privacy-hero h1 {
        font-size: 2.2rem;
    }
    
    .content-wrapper {
        padding: 0 1rem;
    }
    
    .privacy-section h2 {
        font-size: 1.5rem;
    }
    
    .privacy-section ul {
        margin-left: 1rem;
    }
}
</style>

<!-- Hero Section -->
<section class="privacy-hero">
    <div class="container">
        <h1>Политика конфиденциальности</h1>
        <p>Защита ваших персональных данных - наш приоритет</p>
    </div>
</section>

<!-- Breadcrumbs -->
<section class="breadcrumbs">
    <div class="container">
        <ul class="breadcrumbs-list">
            <li><a href="/">Главная</a></li>
            <li>Политика конфиденциальности</li>
        </ul>
    </div>
</section>

<!-- Main Content -->
<section class="privacy-content">
    <div class="content-wrapper">
        
        <div class="update-date">
            Документ обновлен: <?= date('d.m.Y') ?>
        </div>

        <div class="privacy-section">
            <h2>1. Общие положения</h2>
            <p>
                Настоящая Политика конфиденциальности определяет порядок обработки и защиты персональных данных 
                пользователей сайта stolitsa-dance.ru (далее — "Сайт"), принадлежащего Театру танца "Столица" 
                (далее — "Мы", "Театр").
            </p>
            <p>
                Используя наш Сайт, вы соглашаетесь с условиями данной Политики конфиденциальности и обработкой 
                ваших персональных данных в соответствии с законодательством Российской Федерации.
            </p>
        </div>

        <div class="privacy-section">
            <h2>2. Какие данные мы собираем</h2>
            
            <h3>2.1 Персональные данные</h3>
            <p>Мы можем собирать следующие персональные данные:</p>
            <ul>
                <li>Имя и фамилия</li>
                <li>Номер телефона</li>
                <li>Адрес электронной почты</li>
                <li>Возраст ребенка (для детских групп)</li>
                <li>Комментарии и сообщения, оставленные на сайте</li>
            </ul>

            <h3>2.2 Технические данные</h3>
            <p>Автоматически собираемые данные:</p>
            <ul>
                <li>IP-адрес устройства</li>
                <li>Тип браузера и операционной системы</li>
                <li>Страницы сайта, которые вы посещаете</li>
                <li>Время и продолжительность посещений</li>
                <li>Источник перехода на наш сайт</li>
            </ul>
        </div>

        <div class="privacy-section">
            <h2>3. Цели обработки данных</h2>
            <p>Мы используем ваши персональные данные для:</p>
            <ul>
                <li>Обработки заявок на обучение и записи на занятия</li>
                <li>Консультирования по вопросам обучения</li>
                <li>Отправки информации о расписании занятий и мероприятиях</li>
                <li>Улучшения качества наших услуг</li>
                <li>Соблюдения правовых обязательств</li>
                <li>Защиты прав и интересов Театра</li>
            </ul>
        </div>

        <div class="privacy-section">
            <h2>4. Файлы cookie и аналитика</h2>
            
            <h3>4.1 Использование cookie</h3>
            <p>
                Наш сайт использует файлы cookie — небольшие текстовые файлы, которые сохраняются в вашем браузере 
                для обеспечения работы сайта и улучшения пользовательского опыта.
            </p>

            <h3>4.2 Типы используемых cookie</h3>
            <ul>
                <li><strong>Необходимые cookie:</strong> обеспечивают основные функции сайта</li>
                <li><strong>Аналитические cookie:</strong> помогают анализировать посещаемость сайта</li>
                <li><strong>Функциональные cookie:</strong> запоминают ваши предпочтения</li>
            </ul>

            <h3>4.3 Яндекс.Метрика</h3>
            <p>
                Мы используем сервис Яндекс.Метрика для анализа посещаемости сайта. Яндекс.Метрика собирает 
                анонимную информацию о посещениях, включая:
            </p>
            <ul>
                <li>Количество просмотров страниц</li>
                <li>Время пребывания на сайте</li>
                <li>Источники трафика</li>
                <li>Геолокацию посетителей (на уровне города)</li>
                <li>Техническую информацию об устройствах</li>
            </ul>
            <p>
                Подробнее о политике конфиденциальности Яндекс.Метрики: 
                <a href="https://yandex.ru/legal/confidential/" target="_blank">https://yandex.ru/legal/confidential/</a>
            </p>
        </div>

        <div class="privacy-section">
            <h2>5. Передача данных третьим лицам</h2>
            <p>
                Мы не передаем ваши персональные данные третьим лицам, за исключением случаев, 
                предусмотренных законодательством РФ или при получении вашего явного согласия.
            </p>
            <p>Данные могут быть переданы:</p>
            <ul>
                <li>Государственным органам по их официальному запросу</li>
                <li>Техническим партнерам для обеспечения работы сайта (с соблюдением конфиденциальности)</li>
                <li>При смене собственника бизнеса (с предварительным уведомлением)</li>
            </ul>
        </div>

        <div class="privacy-section">
            <h2>6. Хранение и защита данных</h2>
            
            <h3>6.1 Сроки хранения</h3>
            <p>Мы храним ваши персональные данные:</p>
            <ul>
                <li>До достижения целей обработки</li>
                <li>В течение сроков, установленных законодательством</li>
                <li>До вашего обращения с просьбой об удалении данных</li>
            </ul>

            <h3>6.2 Меры защиты</h3>
            <p>Для защиты ваших данных мы применяем:</p>
            <ul>
                <li>Шифрование при передаче данных (SSL)</li>
                <li>Регулярное резервное копирование</li>
                <li>Ограничение доступа к данным</li>
                <li>Мониторинг безопасности систем</li>
            </ul>
        </div>

        <div class="privacy-section">
            <h2>7. Ваши права</h2>
            <p>В соответствии с законодательством РФ, вы имеете право:</p>
            <ul>
                <li>Получать информацию об обработке ваших персональных данных</li>
                <li>Требовать уточнения неточных данных</li>
                <li>Требовать удаления ваших персональных данных</li>
                <li>Отзывать согласие на обработку данных</li>
                <li>Обращаться в Роскомнадзор с жалобами</li>
            </ul>
        </div>

        <div class="privacy-section">
            <h2>8. Управление cookie</h2>
            <p>Вы можете управлять использованием файлов cookie:</p>
            
            <h3>8.1 Настройки браузера</h3>
            <ul>
                <li>Отключить все cookie в настройках браузера</li>
                <li>Удалить уже сохраненные cookie</li>
                <li>Настроить уведомления о новых cookie</li>
            </ul>

            <h3>8.2 Отказ от аналитики</h3>
            <p>
                Для отказа от Яндекс.Метрики используйте дополнение: 
                <a href="https://yandex.ru/support/metrica/general/opt-out.html" target="_blank">Отказ от Яндекс.Метрики</a>
            </p>
        </div>

        <div class="privacy-section">
            <h2>9. Обновление политики</h2>
            <p>
                Мы можем обновлять данную Политику конфиденциальности. О существенных изменениях мы уведомим 
                вас размещением уведомления на сайте или другими способами связи.
            </p>
            <p>
                Рекомендуем периодически просматривать эту страницу для ознакомления с актуальной версией Политики.
            </p>
        </div>

        <div class="contact-info">
            <h3>Контактная информация</h3>
            <p>По вопросам обработки персональных данных обращайтесь:</p>
            <p><strong>Театр танца "Столица"</strong></p>
            <p><strong>Email:</strong> info@stolitsa-dance.ru</p>
            <p><strong>Телефон:</strong> +7 (999) 930-36-60</p>
            <p><strong>Адрес:</strong> г. Москва</p>
            <p><strong>Время работы:</strong> Пн-Пт 10:00-20:00, Сб-Вс 10:00-18:00</p>
        </div>

    </div>
</section>

<?php require_once 'includes/footer.php'; ?>