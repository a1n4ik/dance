// ========== ИСПРАВЛЕНИЕ ОШИБКИ СЛАЙДЕРОВ в sliders.js ==========

// Замените инициализацию слайдеров в sliders.js на эту версию:

document.addEventListener('DOMContentLoaded', function() {
    console.log('News and Projects sliders initializing...');
    
    // Ждем полной загрузки Swiper
    function waitForSwiper(callback) {
        if (typeof Swiper !== 'undefined') {
            callback();
        } else {
            setTimeout(() => waitForSwiper(callback), 100);
        }
    }
    
    waitForSwiper(function() {
        initializeSliders();
    });
    
    function initializeSliders() {
        // ========== НОВОСТИ СЛАЙДЕР ==========
        const newsSliderContainer = document.querySelector('.news-slider');
        if (newsSliderContainer && !newsSliderContainer.swiper) {
            // Проверяем что есть слайды
            const newsSlides = newsSliderContainer.querySelectorAll('.swiper-slide');

            if (newsSlides.length > 0) {
                try {
                    window.newsSwiper = new Swiper(newsSliderContainer, {
                        // Основные настройки
                        slidesPerView: 'auto',
                        spaceBetween: 30,
                        centeredSlides: false,
                        
                        // Навигация
                        navigation: {
                            nextEl: '.news-section .swiper-button-next',
                            prevEl: '.news-section .swiper-button-prev',
                        },
                        
                        // Пагинация
                        pagination: {
                            el: '.news-section .swiper-pagination',
                            clickable: true,
                            dynamicBullets: true,
                        },
                        
                        // Адаптивность
                        // slidesPerView: 'auto' — ширину слайдов задаёт CSS (фиксированные
                        // 740px/600px/90vw). Числовые значения конфликтовали с !important-шириной
                        // в CSS и приводили к «разъезжающейся» раскладке.
                        breakpoints: {
                            320: {
                                slidesPerView: 'auto',
                                spaceBetween: 15,
                                centeredSlides: true,
                            },
                            768: {
                                slidesPerView: 'auto',
                                spaceBetween: 25,
                                centeredSlides: false,
                            },
                            1200: {
                                slidesPerView: 'auto',
                                spaceBetween: 40,
                                centeredSlides: false,
                            }
                        },
                        
                        // События
                        on: {
                            init: function() {
                                console.log('✅ News slider initialized');
                            },
                            error: function(swiper, error) {
                                console.error('❌ News slider error:', error);
                            }
                        }
                    });
                    
                } catch (error) {
                    console.error('Error initializing news slider:', error);
                    fallbackNewsSlider();
                }
            } else {
                console.warn('No news slides found');
            }
        }
        
        // ========== ПРОЕКТЫ СЛАЙДЕР ==========
        const projectsSliderContainer = document.querySelector('.projects-slider');
        if (projectsSliderContainer && !projectsSliderContainer.swiper) {
            // Проверяем что есть слайды
            const projectSlides = projectsSliderContainer.querySelectorAll('.swiper-slide');

            if (projectSlides.length > 0) {
                try {
                    window.projectsSwiper = new Swiper(projectsSliderContainer, {
                        // Основные настройки
                        slidesPerView: 'auto',
                        spaceBetween: 30,
                        centeredSlides: false,
                        
                        // Навигация
                        navigation: {
                            nextEl: '.projects-section .swiper-button-next',
                            prevEl: '.projects-section .swiper-button-prev',
                        },
                        
                        // Пагинация
                        pagination: {
                            el: '.projects-section .swiper-pagination',
                            clickable: true,
                            dynamicBullets: true,
                        },
                        
                        // Адаптивность
                        // slidesPerView: 'auto' — ширину слайдов задаёт CSS (фиксированные
                        // 740px/600px/90vw). Числовые значения конфликтовали с !important-шириной
                        // в CSS и приводили к «разъезжающейся» раскладке.
                        breakpoints: {
                            320: {
                                slidesPerView: 'auto',
                                spaceBetween: 15,
                                centeredSlides: true,
                            },
                            768: {
                                slidesPerView: 'auto',
                                spaceBetween: 25,
                                centeredSlides: false,
                            },
                            1200: {
                                slidesPerView: 'auto',
                                spaceBetween: 40,
                                centeredSlides: false,
                            }
                        },
                        
                        // События
                        on: {
                            init: function() {
                                console.log('✅ Projects slider initialized');
                            },
                            error: function(swiper, error) {
                                console.error('❌ Projects slider error:', error);
                            }
                        }
                    });
                    
                } catch (error) {
                    console.error('Error initializing projects slider:', error);
                    fallbackProjectsSlider();
                }
            } else {
                console.warn('No project slides found');
            }
        }
        
        console.log('✅ News and Projects sliders initialized successfully');
    }
    
    // ========== FALLBACK ДЛЯ НОВОСТЕЙ ==========
    function fallbackNewsSlider() {
        const newsContainer = document.querySelector('.news-slider');
        if (newsContainer) {
            newsContainer.style.overflow = 'hidden';
            newsContainer.style.whiteSpace = 'nowrap';
            
            const slides = newsContainer.querySelectorAll('.swiper-slide');
            slides.forEach((slide, index) => {
                slide.style.display = 'inline-block';
                slide.style.width = '300px';
                slide.style.marginRight = '20px';
                slide.style.verticalAlign = 'top';
                slide.style.whiteSpace = 'normal';
            });
            
            // Добавляем горизонтальную прокрутку
            newsContainer.style.overflowX = 'auto';
            newsContainer.style.paddingBottom = '10px';
            
            console.log('📱 News fallback slider activated');
        }
    }
    
    // ========== FALLBACK ДЛЯ ПРОЕКТОВ ==========
    function fallbackProjectsSlider() {
        const projectsContainer = document.querySelector('.projects-slider');
        if (projectsContainer) {
            projectsContainer.style.overflow = 'hidden';
            projectsContainer.style.whiteSpace = 'nowrap';
            
            const slides = projectsContainer.querySelectorAll('.swiper-slide');
            slides.forEach((slide, index) => {
                slide.style.display = 'inline-block';
                slide.style.width = '300px';
                slide.style.marginRight = '20px';
                slide.style.verticalAlign = 'top';
                slide.style.whiteSpace = 'normal';
            });
            
            // Добавляем горизонтальную прокрутку
            projectsContainer.style.overflowX = 'auto';
            projectsContainer.style.paddingBottom = '10px';
            
            console.log('📱 Projects fallback slider activated');
        }
    }
});

// ========== ДОПОЛНИТЕЛЬНЫЕ ИСПРАВЛЕНИЯ ==========

// Исправление для мобильных устройств
if (window.innerWidth <= 768) {
    document.addEventListener('DOMContentLoaded', function() {
        // Добавляем touch-friendly стили
        const style = document.createElement('style');
        style.textContent = `
            .swiper-slide {
                -webkit-transform: translateZ(0);
                transform: translateZ(0);
            }
            
            .news-slider,
            .projects-slider {
                -webkit-overflow-scrolling: touch;
                scroll-behavior: smooth;
            }
            
            .swiper-wrapper {
                align-items: stretch !important;
            }
            
            /* Исправление для мобильной прокрутки */
            @media (max-width: 768px) {
                .swiper-container {
                    overflow: visible;
                }
                
                .swiper-slide {
                    height: auto !important;
                }
            }
        `;
        document.head.appendChild(style);
    });
}

// ========== ПРОВЕРКА И ПЕРЕИНИЦИАЛИЗАЦИЯ ==========

// Функция для переинициализации слайдеров при ошибках
window.reinitializeSliders = function() {
    console.log('🔄 Переинициализация слайдеров...');
    
    // Удаляем существующие экземпляры
    if (window.newsSwiper) {
        window.newsSwiper.destroy(true, true);
        window.newsSwiper = null;
    }
    
    if (window.projectsSwiper) {
        window.projectsSwiper.destroy(true, true);
        window.projectsSwiper = null;
    }
    
    // Заново инициализируем через 500ms
    setTimeout(() => {
        if (typeof Swiper !== 'undefined') {
            initializeSliders();
        } else {
            console.error('Swiper library not loaded');
        }
    }, 500);
};

// При изменении размера окна Swiper пересчитывает раскладку сам — НЕ пересоздаём
// слайдер (это вызывало мерцание и «прыжки», т.к. на мобильных адресная строка
// постоянно меняет высоту и генерирует resize). Достаточно update().
let resizeTimer;
window.addEventListener('resize', function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
        if (window.newsSwiper && typeof window.newsSwiper.update === 'function') {
            window.newsSwiper.update();
        }
        if (window.projectsSwiper && typeof window.projectsSwiper.update === 'function') {
            window.projectsSwiper.update();
        }
    }, 250);
});