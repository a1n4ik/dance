// ========== assets/js/improved-news.js - JavaScript для новостей и проектов ==========

document.addEventListener('DOMContentLoaded', function() {
    console.log('Improved news module loading...');

    // Данные для демонстрационных модальных окон
    const demoNewsData = {
        'demo1': {
            title: 'НОВОЕ И ЛУЧШЕЕ: ТЕАТР TODES ПОКАЖЕТ «ПРЕВЬЮ»',
            date: '5 июня 2025 - Театр танца',
            image: 'https://stolitsa-dance.ru/wp-content/uploads/2024/05/photo_2024-05-11_12-19-27.jpg',
            content: `
                <p>Театр танца "Столица" готовит грандиозную премьеру нового спектакля "Превью", который станет настоящим событием в мире хореографии.</p>
                
                <p>В постановке примут участие ведущие солисты театра Владимир и Ольга Журавлёвы, а также талантливые ученики всех возрастных групп. Спектакль объединит в себе элементы классического балета, народного танца и современной хореографии.</p>
                
                <p><strong>Особенности спектакля:</strong></p>
                <ul>
                    <li>Авторская хореография от Владимира и Ольги Журавлёвых</li>
                    <li>Участие детей от 4 до 18 лет</li>
                    <li>Уникальные костюмы и декорации</li>
                    <li>Живое музыкальное сопровождение</li>
                </ul>
                
                <p>Премьера состоится в Государственном театре наций. Билеты уже в продаже!</p>
            `
        },
        'demo2': {
            title: 'НОВЫЙ СЕЗОН – НОВЫЕ ТАНЦЫ!',
            date: '6 августа 2025 - Студии-школы',
            image: 'https://stolitsa-dance.ru/wp-content/uploads/2024/05/photo_2024-05-11_12-21-08.jpg',
            content: `
                <p>С началом нового учебного года театр танца "Столица" открывает двери для новых учеников!</p>
                
                <p>Мы предлагаем обучение по следующим направлениям:</p>
                <ul>
                    <li><strong>Классический танец</strong> - основа хореографического искусства</li>
                    <li><strong>Народный танец</strong> - изучение культуры разных народов</li>
                    <li><strong>Современная хореография</strong> - джаз, модерн, контемпорари</li>
                    <li><strong>Baby-балет</strong> - для малышей от 2 лет</li>
                    <li><strong>Партерная гимнастика</strong> - развитие гибкости</li>
                    <li><strong>Акробатика</strong> - физическая подготовка</li>
                </ul>
                
                <p>Запись на занятия уже открыта! Звоните по телефону +7 (999) 930-36-60 или оставляйте заявку на сайте.</p>
            `
        },
        'demo3': {
            title: 'ЛЕТНИЕ ТАНЦЕВАЛЬНЫЕ ИНТЕНСИВЫ',
            date: '25 июля 2025 - Интенсивы',
            image: 'https://stolitsa-dance.ru/wp-content/uploads/2022/09/jazz.jpg',
            content: `
                <p>Этим летом театр танца "Столица" проводит интенсивные курсы для всех желающих углубить свои знания в хореографии.</p>
                
                <p>Программа интенсивов включает:</p>
                <ul>
                    <li>Ежедневные занятия в течение 2 недель</li>
                    <li>Мастер-классы от приглашенных педагогов</li>
                    <li>Индивидуальные консультации</li>
                    <li>Итоговый концерт с участием всех студентов</li>
                </ul>
                
                <p>Интенсивы проходят в трех группах: начинающие (4-7 лет), продолжающие (8-14 лет) и продвинутые (15+ лет).</p>
                
                <p>Количество мест ограничено. Успейте записаться!</p>
            `
        },
        'demo4': {
            title: 'ОТКРЫТЫЕ МАСТЕР-КЛАССЫ',
            date: '15 мая 2025 - Мастер-классы',
            image: 'https://stolitsa-dance.ru/wp-content/uploads/2023/08/bb.jpeg',
            content: `
                <p>Театр танца "Столица" приглашает всех желающих на открытые мастер-классы от ведущих педагогов!</p>
                
                <p>В программе:</p>
                <ul>
                    <li><strong>10:00-11:30</strong> - Классический танец (Ольга Журавлёва)</li>
                    <li><strong>12:00-13:30</strong> - Народный танец (Владимир Журавлёв)</li>
                    <li><strong>14:00-15:30</strong> - Современная хореография (приглашенный педагог)</li>
                    <li><strong>16:00-17:00</strong> - Baby-балет для малышей</li>
                </ul>
                
                <p>Участие бесплатное! Приходите и откройте для себя удивительный мир танца.</p>
                
                <p>Адрес: м. Ломоносовский проспект. Предварительная запись обязательна.</p>
            `
        }
    };

    const demoProjectsData = {
        'demo1': {
            title: 'СПЕКТАКЛЬ "ЩЕЛКУНЧИК"',
            date: '15 декабря 2025 - Премьера',
            image: 'https://stolitsa-dance.ru/wp-content/uploads/2022/09/prob0.jpg',
            content: `
                <p>Новогодняя постановка классического балета П.И. Чайковского в авторской интерпретации театра танца "Столица".</p>
                
                <p>В спектакле принимают участие более 50 артистов всех возрастов - от 4 до 18 лет. Это уникальная возможность для юных танцоров выступить на профессиональной сцене.</p>
                
                <p><strong>Особенности постановки:</strong></p>
                <ul>
                    <li>Современная трактовка классического сюжета</li>
                    <li>Оригинальные костюмы и декорации</li>
                    <li>Симфонический оркестр</li>
                    <li>Участие профессиональных артистов</li>
                </ul>
                
                <p>Спектакль будет показан в Государственном театре наций в период новогодних праздников.</p>
            `
        },
        'demo2': {
            title: 'ГАСТРОЛЬНЫЙ ТУР ПО РОССИИ',
            date: 'Сентябрь 2025 - Активный',
            image: 'https://stolitsa-dance.ru/wp-content/uploads/2024/05/photo_2024-05-11_12-19-27.jpg',
            content: `
                <p>Театр танца "Столица" отправляется в большой гастрольный тур по городам России.</p>
                
                <p><strong>Города тура:</strong></p>
                <ul>
                    <li>Санкт-Петербург - Мариинский театр</li>
                    <li>Казань - Татарский государственный театр оперы и балета</li>
                    <li>Екатеринбург - Театр оперы и балета</li>
                    <li>Новосибирск - НОВАТ</li>
                    <li>Краснодар - Театр драмы</li>
                </ul>
                
                <p>В программе - лучшие номера театра, включая авторские постановки Владимира и Ольги Журавлёвых.</p>
                
                <p>Это отличная возможность для наших учеников получить опыт выступлений в лучших театрах страны.</p>
            `
        },
        'demo3': {
            title: 'КОНКУРС МОЛОДЫХ ТАЛАНТОВ',
            date: 'Ноябрь 2025 - Предстоящий',
            image: 'https://stolitsa-dance.ru/wp-content/uploads/2024/05/photo_2024-05-11_12-21-08.jpg',
            content: `
                <p>Ежегодный конкурс "Молодые таланты" призван выявить и поддержать самых одаренных юных танцоров.</p>
                
                <p><strong>Номинации конкурса:</strong></p>
                <ul>
                    <li>Классический танец (соло, дуэт)</li>
                    <li>Народный танец (соло, ансамбль)</li>
                    <li>Современная хореография</li>
                    <li>Импровизация</li>
                    <li>Лучшая постановка</li>
                </ul>
                
                <p>Жюри конкурса - известные хореографы, артисты балета и педагоги из разных городов России.</p>
                
                <p>Победители получают денежные призы, дипломы и возможность участия в профессиональных проектах театра.</p>
                
                <p>Заявки принимаются до 1 октября 2025 года.</p>
            `
        }
    };

    // Глобальные функции для модальных окон
    window.openNewsModal = function(newsId) {
        const modal = document.getElementById('newsModal');
        const header = document.getElementById('newsModalHeader');
        const date = document.getElementById('newsModalDate');
        const title = document.getElementById('newsModalTitle');
        const text = document.getElementById('newsModalText');
        
        if (typeof newsId === 'string' && demoNewsData[newsId]) {
            // Демонстрационные данные
            const newsData = demoNewsData[newsId];
            header.style.backgroundImage = `url(${newsData.image})`;
            date.textContent = newsData.date;
            title.textContent = newsData.title;
            text.innerHTML = newsData.content;
        } else {
            // Данные из БД (AJAX запрос)
            fetch(`/api/news.php?id=${newsId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        header.style.backgroundImage = `url(${data.data.image})`;
                        date.textContent = `${data.data.date} - ${data.data.category}`;
                        title.textContent = data.data.title;
                        text.innerHTML = data.data.content;
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error loading news:', error);
                    // Fallback к демо данным
                    const fallbackData = demoNewsData['demo1'];
                    header.style.backgroundImage = `url(${fallbackData.image})`;
                    date.textContent = fallbackData.date;
                    title.textContent = 'Новость не найдена';
                    text.innerHTML = '<p>К сожалению, содержимое новости не доступно.</p>';
                });
        }
        
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    };
    
    window.closeNewsModal = function() {
        const modal = document.getElementById('newsModal');
        modal.classList.remove('active');
        document.body.style.overflow = '';
    };
    
    window.openProjectModal = function(projectId) {
        const modal = document.getElementById('projectModal');
        const header = document.getElementById('projectModalHeader');
        const date = document.getElementById('projectModalDate');
        const title = document.getElementById('projectModalTitle');
        const text = document.getElementById('projectModalText');
        
        if (typeof projectId === 'string' && demoProjectsData[projectId]) {
            // Демонстрационные данные
            const projectData = demoProjectsData[projectId];
            header.style.backgroundImage = `url(${projectData.image})`;
            date.textContent = projectData.date;
            title.textContent = projectData.title;
            text.innerHTML = projectData.content;
        } else {
            // Данные из БД
            fetch(`/api/projects.php?id=${projectId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        header.style.backgroundImage = `url(${data.data.image})`;
                        date.textContent = `${data.data.date} - ${data.data.status}`;
                        title.textContent = data.data.title;
                        text.innerHTML = data.data.content;
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error loading project:', error);
                    const fallbackData = demoProjectsData['demo1'];
                    header.style.backgroundImage = `url(${fallbackData.image})`;
                    date.textContent = fallbackData.date;
                    title.textContent = 'Проект не найден';
                    text.innerHTML = '<p>К сожалению, содержимое проекта не доступно.</p>';
                });
        }
        
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    };
    
    window.closeProjectModal = function() {
        const modal = document.getElementById('projectModal');
        modal.classList.remove('active');
        document.body.style.overflow = '';
    };

    // Закрытие модальных окон при клике вне области контента
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('news-modal')) {
            if (e.target.id === 'newsModal') {
                closeNewsModal();
            } else if (e.target.id === 'projectModal') {
                closeProjectModal();
            }
        }
    });

    // Закрытие модальных окон по Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeNewsModal();
            closeProjectModal();
        }
    });

    console.log('Improved news module loaded successfully');
});