// ========== assets/js/main.js - Основной JavaScript ========== 
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Swiper for news
    if (document.querySelector('.news-slider')) {
        const newsSwiper = new Swiper('.news-slider', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
                1400: {
                    slidesPerView: 4,
                },
            },
        });
    }

    // Initialize Swiper for projects
    if (document.querySelector('.projects-slider')) {
        const projectsSwiper = new Swiper('.projects-slider', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            autoplay: {
                delay: 6000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            },
        });
    }

    // Mobile menu toggle
    // Mobile menu toggle - ОБНОВЛЕННАЯ ВЕРСИЯ
const menuBurger = document.querySelector('.menu-burger');
const mobileMenuOverlay = document.querySelector('.mobile-menu-overlay');
const mobileMenuClose = document.querySelector('.mobile-menu-close');
const submenuToggles = document.querySelectorAll('.submenu-toggle');

if (menuBurger && mobileMenuOverlay) {
    // Открытие мобильного меню
    function openMobileMenu() {
        mobileMenuOverlay.classList.add('active');
        menuBurger.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    // Клик по бургеру переключает меню (видимый «крестик» тоже закрывает)
    menuBurger.addEventListener('click', () => {
        if (mobileMenuOverlay.classList.contains('active')) {
            closeMobileMenu();
        } else {
            openMobileMenu();
        }
    });

    // Закрытие мобильного меню
    function closeMobileMenu() {
        mobileMenuOverlay.classList.remove('active');
        menuBurger.classList.remove('active');
        document.body.style.overflow = '';
        // Закрываем все подменю при закрытии основного меню
        document.querySelectorAll('.mobile-submenu').forEach(submenu => {
            submenu.classList.remove('active');
        });
    }

    // Закрытие по кнопке X
    if (mobileMenuClose) {
        mobileMenuClose.addEventListener('click', closeMobileMenu);
    }

    // Закрытие при клике на overlay
    mobileMenuOverlay.addEventListener('click', function(e) {
        if (e.target === mobileMenuOverlay) {
            closeMobileMenu();
        }
    });

    // Обработка подменю "Направления"
    submenuToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault(); // Предотвращаем переход по ссылке
            
            const submenu = this.closest('.mobile-submenu');
            const arrow = this.querySelector('.submenu-arrow');
            
            // Переключаем активное состояние подменю
            submenu.classList.toggle('active');
            
            // Анимируем стрелку
            if (submenu.classList.contains('active')) {
                arrow.style.transform = 'rotate(180deg)';
            } else {
                arrow.style.transform = 'rotate(0deg)';
            }
        });
    });

    // Закрытие меню при клике на обычные ссылки (не подменю)
    document.querySelectorAll('.mobile-menu-list a:not(.submenu-toggle)').forEach(link => {
        link.addEventListener('click', function() {
            // Задержка для плавного закрытия
            setTimeout(closeMobileMenu, 100);
        });
    });
}

    // Header scroll effect
    let lastScroll = 0;
    window.addEventListener('scroll', () => {
        const header = document.querySelector('.header');
        const currentScroll = window.pageYOffset;

        if (currentScroll > 100) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }

        lastScroll = currentScroll;
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Scroll reveal animation
    const reveals = document.querySelectorAll('.reveal');
    
    function reveal() {
        reveals.forEach(element => {
            const windowHeight = window.innerHeight;
            const elementTop = element.getBoundingClientRect().top;
            const elementVisible = 150;

            if (elementTop < windowHeight - elementVisible) {
                element.classList.add('active');
            }
        });
    }

    window.addEventListener('scroll', reveal);
    reveal(); // Initial check

});
// ========== JavaScript для улучшенных новостей и проектов ==========

document.addEventListener('DOMContentLoaded', function() {
    console.log('Improved news and projects module loading...');

    

    // Инициализация улучшенного слайдера новостей
    const newsSwiper = new Swiper('.news-slider', {
        slidesPerView: 'auto',
        spaceBetween: 30,
        freeMode: false,
        grabCursor: true,
        centeredSlides: false,
        
        // Показываем только 2 слайда, остальные за границей
        slidesOffsetBefore: 0,
        slidesOffsetAfter: 50,
        
        navigation: {
            nextEl: '.news-section .swiper-button-next',
            prevEl: '.news-section .swiper-button-prev',
        },
        
        pagination: {
            el: '.news-section .swiper-pagination',
            clickable: true,
            dynamicBullets: true,
        },
        
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
        },
        
        breakpoints: {
            320: {
                spaceBetween: 20,
                slidesOffsetBefore: 20,
            },
            768: {
                spaceBetween: 25,
                slidesOffsetBefore: 30,
            },
            1024: {
                spaceBetween: 30,
                slidesOffsetBefore: 0,
            }
        },
        
        on: {
            init: function() {
                console.log('News slider initialized');
                // Анимация появления карточек
                setTimeout(() => {
                    this.slides.forEach((slide, index) => {
                        slide.style.opacity = '0';
                        slide.style.transform = 'translateY(50px)';
                        setTimeout(() => {
                            slide.style.transition = 'all 0.6s ease';
                            slide.style.opacity = '1';
                            slide.style.transform = 'translateY(0)';
                        }, index * 100);
                    });
                }, 100);
            }
        }
    });

    
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
                    header.style.backgroundImage = `url(${data.image})`;
                    date.textContent = `${data.date} - ${data.category}`;
                    title.textContent = data.title;
                    text.innerHTML = data.content;
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
                    header.style.backgroundImage = `url(${data.image})`;
                    date.textContent = `${data.date} - ${data.status}`;
                    title.textContent = data.title;
                    text.innerHTML = data.content;
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

    // Анимации при скролле
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                if (entry.target.classList.contains('section-title')) {
                    const spans = entry.target.querySelectorAll('span');
                    spans.forEach((span, index) => {
                        setTimeout(() => {
                            span.style.animation = 'fadeInUp 0.6s ease forwards';
                        }, index * 100);
                    });
                }
            }
        });
    }, observerOptions);
    // Исправление стилей Swiper для корректной работы
    function fixSwiperStyles() {
        const style = document.createElement('style');
        style.textContent = `
            .swiper-wrapper {
                position: relative !important;
                width: 100% !important;
                z-index: 1 !important;
                display: flex !important;
                transition-property: transform !important;
                box-sizing: content-box !important;
                height: auto !important;
                transition-timing-function: ease !important;
            }
            
            .swiper-slide {
                flex-shrink: 0 !important;
                width: auto !important;
                height: auto !important;
                position: relative !important;
            }
            
            .news-slider .swiper-slide {
                margin-right: 2rem;
            }
            
            .projects-slider .swiper-slide {
                margin-right: 2rem;
            }
            
            /* Исправление для футера */
            .footer {
                position: relative !important;
                z-index: 1 !important;
                margin-top: auto !important;
            }
            
            body {
                display: flex !important;
                flex-direction: column !important;
                min-height: 100vh !important;
            }
            
            .main-content {
                flex: 1 !important;
            }
            
            /* Мобильное меню стили */
            .mobile-menu-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.8);
                z-index: 9999;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
            }
            
            .mobile-menu-overlay.active {
                opacity: 1;
                visibility: visible;
            }
            
            .mobile-menu {
                position: absolute;
                top: 0;
                right: 0;
                width: 300px;
                height: 100%;
                background: white;
                transform: translateX(100%);
                transition: transform 0.3s ease;
                overflow-y: auto;
            }
            
            .mobile-menu-overlay.active .mobile-menu {
                transform: translateX(0);
            }
            
            .mobile-menu-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 1rem;
                border-bottom: 1px solid #eee;
            }
            
            .mobile-logo {
                height: 40px;
            }
            
            .mobile-menu-close {
                background: none;
                border: none;
                font-size: 2rem;
                cursor: pointer;
                color: #666;
            }
            
            .mobile-menu-list {
                list-style: none;
                padding: 0;
                margin: 0;
            }
            
            .mobile-menu-list li {
                border-bottom: 1px solid #f0f0f0;
            }
            
            .mobile-menu-list a {
                display: block;
                padding: 1rem;
                color: #333;
                text-decoration: none;
                font-weight: 500;
            }
            
            .mobile-submenu-list {
                display: none;
                background: #f8f9fa;
                list-style: none;
                padding: 0;
                margin: 0;
            }
            
            .mobile-submenu-list a {
                padding-left: 2rem;
                font-size: 0.9rem;
                color: #666;
            }
            
            .submenu-arrow {
                float: right;
                transition: transform 0.3s ease;
            }
            
            .mobile-menu-contacts {
                padding: 2rem 1rem;
                border-top: 1px solid #eee;
                background: #f8f9fa;
            }
            
            .mobile-phone {
                display: block;
                font-size: 1.2rem;
                font-weight: 600;
                color: var(--blue-bright);
                text-decoration: none;
                margin-bottom: 1rem;
            }
            
            .mobile-social {
                display: flex;
                gap: 1rem;
            }
            
            .mobile-social a {
                font-size: 1.5rem;
                text-decoration: none;
            }
            
            /* Dropdown меню для десктопа */
            .nav-dropdown {
                position: relative;
            }
            
            .nav-dropdown-menu {
                position: absolute;
                top: 100%;
                left: 0;
                background: white;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                border-radius: 10px;
                padding: 1rem 0;
                min-width: 200px;
                opacity: 0;
                visibility: hidden;
                transform: translateY(10px);
                transition: all 0.3s ease;
                list-style: none;
                z-index: 1000;
            }
            
            .nav-dropdown:hover .nav-dropdown-menu {
                opacity: 1;
                visibility: visible;
                transform: translateY(0);
            }
            
            .nav-dropdown-menu li a {
                display: block;
                padding: 0.5rem 1rem;
                color: #333;
                text-decoration: none;
                transition: all 0.3s ease;
            }
            
            .nav-dropdown-menu li a:hover {
                background: var(--blue-light);
                color: var(--blue-bright);
            }
            
            @media (max-width: 991px) {
                .nav-menu {
                    display: none;
                }

                .menu-burger {
                    display: flex;
                    flex-direction: column;
                    cursor: pointer;
                    padding: 0.5rem;
                    /* Бургер всегда выше оверлея (9999), чтобы «крестик» был виден и кликабелен */
                    position: relative;
                    z-index: 10000;
                }

                .menu-burger span {
                    width: 25px;
                    height: 3px;
                    background: #333;
                    margin: 3px 0;
                    transition: 0.3s;
                }

                .menu-burger.active span:nth-child(1) {
                    transform: rotate(-45deg) translate(-5px, 6px);
                }

                .menu-burger.active span:nth-child(2) {
                    opacity: 0;
                }

                .menu-burger.active span:nth-child(3) {
                    transform: rotate(45deg) translate(-5px, -6px);
                }
            }

            @media (min-width: 992px) {
                .menu-burger {
                    display: none;
                }
            }
        `;
        document.head.appendChild(style);
    }

    // Применяем исправления стилей
    fixSwiperStyles();
    // Наблюдаем за заголовками секций
    document.querySelectorAll('.section-title').forEach(title => {
        observer.observe(title);
    });

    console.log('Improved news and projects module loaded successfully');
});
