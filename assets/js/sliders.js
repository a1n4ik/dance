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
        if (newsSliderContainer) {
            // Проверяем что есть слайды
            const newsSlides = newsSliderContainer.querySelectorAll('.swiper-slide');
            
            if (newsSlides.length > 0) {
                try {
                    const newsSwiper = new Swiper(newsSliderContainer, {
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
                        breakpoints: {
                            320: {
                                slidesPerView: 1.1,
                                spaceBetween: 15,
                                centeredSlides: true,
                            },
                            480: {
                                slidesPerView: 1.3,
                                spaceBetween: 20,
                                centeredSlides: false,
                            },
                            768: {
                                slidesPerView: 2,
                                spaceBetween: 25,
                            },
                            1024: {
                                slidesPerView: 3,
                                spaceBetween: 30,
                            },
                            1200: {
                                slidesPerView: 4,
                                spaceBetween: 30,
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
        if (projectsSliderContainer) {
            // Проверяем что есть слайды
            const projectSlides = projectsSliderContainer.querySelectorAll('.swiper-slide');
            
            if (projectSlides.length > 0) {
                try {
                    const projectsSwiper = new Swiper(projectsSliderContainer, {
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
                        breakpoints: {
                            320: {
                                slidesPerView: 1.1,
                                spaceBetween: 15,
                                centeredSlides: true,
                            },
                            480: {
                                slidesPerView: 1.3,
                                spaceBetween: 20,
                                centeredSlides: false,
                            },
                            768: {
                                slidesPerView: 2,
                                spaceBetween: 25,
                            },
                            1024: {
                                slidesPerView: 3,
                                spaceBetween: 30,
                            },
                            1200: {
                                slidesPerView: 4,
                                spaceBetween: 30,
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

// Автоматическая переинициализация при ошибках
window.addEventListener('error', function(e) {
    if (e.message && e.message.includes('querySelectorAll')) {
        console.warn('Обнаружена ошибка querySelectorAll, переинициализируем слайдеры');
        setTimeout(window.reinitializeSliders, 1000);
    }
});

// Переинициализация при изменении размера окна
let resizeTimer;
window.addEventListener('resize', function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
        if (window.innerWidth <= 768) {
            // На мобильных переинициализируем
            window.reinitializeSliders();
        }
    }, 500);
});