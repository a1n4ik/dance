// ========== RESPONSIVE.JS - JavaScript для адаптивного функционала ========== 

document.addEventListener('DOMContentLoaded', function() {
    
    // ========== МОБИЛЬНОЕ МЕНЮ ==========
    function initMobileMenu() {
        // Создаем мобильное меню если его нет
        if (!document.querySelector('.mobile-menu-overlay')) {
            createMobileMenu();
        }
        
        const burger = document.querySelector('.menu-burger');
        const overlay = document.querySelector('.mobile-menu-overlay');
        const closeBtn = document.querySelector('.mobile-menu-close');
        
        if (burger && overlay) {
            // Открытие меню
            burger.addEventListener('click', function() {
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
            
            // Закрытие меню
            if (closeBtn) {
                closeBtn.addEventListener('click', closeMobileMenu);
            }
            
            // Закрытие по клику на overlay
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) {
                    closeMobileMenu();
                }
            });
            
            // Закрытие по Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && overlay.classList.contains('active')) {
                    closeMobileMenu();
                }
            });
            
            // Закрытие при клике на ссылку
            const mobileLinks = overlay.querySelectorAll('.mobile-menu-list a');
            mobileLinks.forEach(link => {
                link.addEventListener('click', closeMobileMenu);
            });
        }
        
        function closeMobileMenu() {
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    }
    
    // Создание мобильного меню
    function createMobileMenu() {
        const navMenu = document.querySelector('.nav-menu');
        if (!navMenu) return;
        
        const menuItems = navMenu.querySelectorAll('a');
        
        const mobileMenuHTML = `
            <div class="mobile-menu-overlay">
                <div class="mobile-menu">
                    <div class="mobile-menu-header">
                        <img src="https://stolitsa-dance.ru/wp-content/uploads/2025/07/logo_new.png" alt="Столица" class="mobile-logo">
                        <button class="mobile-menu-close" aria-label="Закрыть меню">&times;</button>
                    </div>
                    <ul class="mobile-menu-list">
                        ${Array.from(menuItems).map(item => `
                            <li><a href="${item.href}">${item.textContent}</a></li>
                        `).join('')}
                    </ul>
                    <div class="mobile-menu-contacts">
                        <a href="tel:+79999303660" class="mobile-phone">+7 (999) 930-36-60</a>
                        <div class="mobile-social">
                            <a href="https://instagram.com/stolitsa_dance" target="_blank" rel="noopener" aria-label="Instagram">📷</a>
                            <a href="https://t.me/stolitsa_dance" target="_blank" rel="noopener" aria-label="Telegram">📱</a>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', mobileMenuHTML);
    }
    
    // ========== КНОПКА "НАВЕРХ" ==========
    function initScrollToTop() {
        let scrollTopBtn = document.querySelector('.scroll-to-top');
        
        // Создаем кнопку если её нет
        if (!scrollTopBtn) {
            scrollTopBtn = document.createElement('button');
            scrollTopBtn.className = 'scroll-to-top';
            scrollTopBtn.setAttribute('aria-label', 'Прокрутить наверх');
            scrollTopBtn.innerHTML = `
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 15l-6-6-6 6"/>
                </svg>
            `;
            document.body.appendChild(scrollTopBtn);
        }
        
        // Показ/скрытие кнопки при скролле
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                scrollTopBtn.classList.add('show');
            } else {
                scrollTopBtn.classList.remove('show');
            }
        });
        
        // Плавная прокрутка наверх
        scrollTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
    
    // ========== АДАПТИВНЫЕ ТАБЛИЦЫ ==========
    function initResponsiveTables() {
        const tables = document.querySelectorAll('table');
        
        tables.forEach(table => {
            if (!table.closest('.table-responsive')) {
                const wrapper = document.createElement('div');
                wrapper.className = 'table-responsive';
                table.parentNode.insertBefore(wrapper, table);
                wrapper.appendChild(table);
            }
        });
    }
    
    // ========== ОПТИМИЗАЦИЯ ИЗОБРАЖЕНИЙ ==========
    function initImageOptimization() {
        const images = document.querySelectorAll('img');
        
        images.forEach(img => {
            // Добавляем lazy loading для производительности
            if (!img.hasAttribute('loading')) {
                img.setAttribute('loading', 'lazy');
            }
            
            // Добавляем alt если отсутствует для доступности
            if (!img.hasAttribute('alt')) {
                img.setAttribute('alt', '');
            }
        });
    }
    
    // ========== АДАПТИВНЫЕ СЛАЙДЕРЫ ==========
    function initResponsiveSliders() {
        // Если используется Swiper
        if (window.Swiper) {
            // Новости слайдер
            const newsSlider = document.querySelector('.news-slider');
            if (newsSlider) {
                new Swiper(newsSlider, {
                    slidesPerView: 'auto',
                    spaceBetween: 20,
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    breakpoints: {
                        320: {
                            slidesPerView: 1.1,
                            spaceBetween: 10,
                        },
                        480: {
                            slidesPerView: 1.3,
                            spaceBetween: 15,
                        },
                        768: {
                            slidesPerView: 2,
                            spaceBetween: 20,
                        },
                        1024: {
                            slidesPerView: 3,
                            spaceBetween: 30,
                        },
                        1200: {
                            slidesPerView: 4,
                            spaceBetween: 30,
                        }
                    }
                });
            }
            
            // Проекты слайдер
            const projectsSlider = document.querySelector('.projects-slider');
            if (projectsSlider) {
                new Swiper(projectsSlider, {
                    slidesPerView: 'auto',
                    spaceBetween: 20,
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    breakpoints: {
                        320: {
                            slidesPerView: 1.1,
                            spaceBetween: 10,
                        },
                        480: {
                            slidesPerView: 1.3,
                            spaceBetween: 15,
                        },
                        768: {
                            slidesPerView: 2,
                            spaceBetween: 20,
                        },
                        1024: {
                            slidesPerView: 3,
                            spaceBetween: 30,
                        },
                        1200: {
                            slidesPerView: 4,
                            spaceBetween: 30,
                        }
                    }
                });
            }
        }
    }
    
    // ========== АДАПТИВНЫЙ HEADER ==========
    function initResponsiveHeader() {
        const header = document.querySelector('.header');
        if (!header) return;
        
        let lastScrollTop = 0;
        const headerHeight = header.offsetHeight;
        
        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            // Добавляем класс при скролле
            if (scrollTop > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
            
            // Скрытие header при скролле вниз на мобильных (опционально)
            if (window.innerWidth <= 768) {
                if (scrollTop > lastScrollTop && scrollTop > headerHeight) {
                    header.style.transform = `translateY(-${headerHeight}px)`;
                } else {
                    header.style.transform = 'translateY(0)';
                }
            }
            
            lastScrollTop = scrollTop;
        });
    }
    
    // ========== ОБРАБОТКА ИЗМЕНЕНИЯ РАЗМЕРА ОКНА ==========
    function initResizeHandler() {
        let resizeTimer;
        
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            
            resizeTimer = setTimeout(function() {
                // Закрываем мобильное меню при изменении размера
                const overlay = document.querySelector('.mobile-menu-overlay');
                if (overlay && overlay.classList.contains('active')) {
                    overlay.classList.remove('active');
                    document.body.style.overflow = '';
                }
                
                // Перерасчет высот для некоторых элементов
                updateViewportHeights();
                
            }, 250);
        });
    }
    
    // ========== ОБНОВЛЕНИЕ ВЫСОТ VIEWPORT ==========
    function updateViewportHeights() {
        // Фиксим проблему с vh на мобильных устройствах
        const vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty('--vh', `${vh}px`);
        
        // Обновляем элементы которые используют 100vh
        const fullHeightElements = document.querySelectorAll('.hero, .services');
        fullHeightElements.forEach(element => {
            if (window.innerWidth <= 768) {
                element.style.height = `calc(var(--vh, 1vh) * 100)`;
            }
        });
    }
    
    // ========== УЛУЧШЕНИЕ ДОСТУПНОСТИ ==========
    function initAccessibility() {
        // Добавляем обработку фокуса для клавиатурной навигации
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                document.body.classList.add('keyboard-navigation');
            }
        });
        
        document.addEventListener('mousedown', function() {
            document.body.classList.remove('keyboard-navigation');
        });
        
        // Улучшаем фокус для интерактивных элементов
        const interactiveElements = document.querySelectorAll('button, a, input, textarea, select');
        interactiveElements.forEach(element => {
            element.addEventListener('focus', function() {
                this.setAttribute('data-focused', 'true');
            });
            
            element.addEventListener('blur', function() {
                this.removeAttribute('data-focused');
            });
        });
    }
    
    // ========== ОПТИМИЗАЦИЯ ФОРМ ==========
    function initFormEnhancements() {
        const forms = document.querySelectorAll('form');
        
        forms.forEach(form => {
            const inputs = form.querySelectorAll('input, textarea');
            
            inputs.forEach(input => {
                // Автофокус на первое поле при открытии формы
                if (input.hasAttribute('autofocus') && window.innerWidth > 768) {
                    input.focus();
                }
                
                // Улучшенная валидация
                input.addEventListener('blur', function() {
                    if (this.value.trim() !== '') {
                        this.classList.add('has-value');
                    } else {
                        this.classList.remove('has-value');
                    }
                });
                
                // Инициализация для полей с уже заполненными значениями
                if (input.value.trim() !== '') {
                    input.classList.add('has-value');
                }
            });
        });
    }
    
    // ========== ОБРАБОТКА TOUCH СОБЫТИЙ ==========
    function initTouchHandling() {
        let touchStartY = 0;
        let touchEndY = 0;
        
        document.addEventListener('touchstart', function(e) {
            touchStartY = e.changedTouches[0].screenY;
        }, { passive: true });
        
        document.addEventListener('touchend', function(e) {
            touchEndY = e.changedTouches[0].screenY;
            handleSwipe();
        }, { passive: true });
        
        function handleSwipe() {
            const swipeThreshold = 50;
            const diff = touchStartY - touchEndY;
            
            // Свайп вверх - показать кнопку "наверх"
            if (diff > swipeThreshold) {
                const scrollBtn = document.querySelector('.scroll-to-top');
                if (scrollBtn && window.pageYOffset > 300) {
                    scrollBtn.classList.add('show');
                }
            }
        }
    }
    
    // ========== ПРОИЗВОДИТЕЛЬНОСТЬ ==========
    function initPerformanceOptimizations() {
        // Добавляем will-change для анимируемых элементов
        const animatedElements = document.querySelectorAll('.hero-content, .services-item, .news-card, .project-card');
        animatedElements.forEach(element => {
            element.style.willChange = 'transform, opacity';
        });
        
        // Убираем will-change после завершения анимации
        setTimeout(() => {
            animatedElements.forEach(element => {
                element.style.willChange = 'auto';
            });
        }, 3000);
        
        // Отложенная загрузка тяжелых элементов
        if ('IntersectionObserver' in window) {
            const lazyElements = document.querySelectorAll('.services-bg-image, .hero-bg');
            const lazyObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('lazy-loaded');
                        lazyObserver.unobserve(entry.target);
                    }
                });
            });
            
            lazyElements.forEach(element => {
                lazyObserver.observe(element);
            });
        }
    }
    
    // ========== ИНИЦИАЛИЗАЦИЯ ВСЕХ МОДУЛЕЙ ==========
    function initAll() {
        // Проверяем готовность DOM
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initAll);
            return;
        }
        
        try {
            updateViewportHeights();
            initMobileMenu();
            initScrollToTop();
            initResponsiveTables();
            initImageOptimization();
            initResponsiveSliders();
            initResponsiveHeader();
            initResizeHandler();
            initAccessibility();
            initFormEnhancements();
            initTouchHandling();
            initPerformanceOptimizations();
            
            console.log('🎭 Адаптивные скрипты для "Театра танца Столица" загружены');
        } catch (error) {
            console.error('Ошибка инициализации адаптивных скриптов:', error);
        }
    }
    
    // Запускаем инициализацию
    initAll();
    
    // ========== ДОПОЛНИТЕЛЬНЫЕ CSS СТИЛИ ДЛЯ ACCESSIBILITY ==========
    const accessibilityStyles = document.createElement('style');
    accessibilityStyles.textContent = `
        /* Улучшенная клавиатурная навигация */
        .keyboard-navigation *:focus {
            outline: 2px solid #03A9F4 !important;
            outline-offset: 2px !important;
        }
        
        /* Скрытие outline при мышной навигации */
        body:not(.keyboard-navigation) *:focus {
            outline: none !important;
        }
        
        /* Стили для полей с значениями */
        .form-input.has-value,
        .form-textarea.has-value {
            border-color: #03A9F4;
            background-color: rgba(3, 169, 244, 0.05);
        }
        
        /* Адаптивные таблицы */
        .table-responsive {
            overflow-x: auto;
            margin-bottom: 1rem;
            -webkit-overflow-scrolling: touch;
        }
        
        .table-responsive table {
            min-width: 100%;
            white-space: nowrap;
        }
        
        @media (max-width: 768px) {
            .table-responsive {
                border: 1px solid #ddd;
                border-radius: 8px;
            }
            
            .table-responsive table {
                border: none;
                font-size: 0.9rem;
            }
        }
        
        /* Плавная загрузка элементов */
        .lazy-loaded {
            transition: opacity 0.5s ease;
        }
        
        /* Оптимизация производительности */
        .hero-bg,
        .services-bg-image {
            backface-visibility: hidden;
            perspective: 1000px;
        }
        
        /* Фикс проблем с vh на мобильных */
        @supports (-webkit-touch-callout: none) {
            .hero,
            .services {
                height: -webkit-fill-available;
            }
        }
    `;
    
    document.head.appendChild(accessibilityStyles);
});

// ========== ГЛОБАЛЬНЫЕ УТИЛИТЫ ==========

// Debounce функция для оптимизации событий
function debounce(func, wait, immediate) {
    let timeout;
    return function executedFunction() {
        const context = this;
        const args = arguments;
        const later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        const callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
}

// Throttle функция для оптимизации скролла
function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// Экспорт для использования в других скриптах
window.ResponsiveUtils = {
    debounce,
    throttle,
    updateViewportHeights: function() {
        const vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty('--vh', `${vh}px`);
    }
};