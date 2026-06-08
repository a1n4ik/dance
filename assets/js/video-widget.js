class VideoWidget {
    constructor() {
        this.widget = document.getElementById('videoWidget');
        this.container = document.getElementById('videoContainer');
        this.video = document.getElementById('widgetVideo');
        this.closeButton = document.getElementById('closeButton');
        this.whatsappButton = document.getElementById('whatsappButton');
        this.loadingSpinner = document.getElementById('loadingSpinner');
        this.isExpanded = false;
        this.isVideoLoaded = false;
        this.whatsappNumber = '+79154134347'; // Номер WhatsApp
        
        this.init();
    }

    init() {
        // Обработчики событий для кнопок
        this.closeButton.addEventListener('click', (e) => {
            e.stopPropagation();
            this.collapseWidget();
        });

        // Обработчик для кнопки WhatsApp
        if (this.whatsappButton) {
            this.whatsappButton.addEventListener('click', (e) => {
                e.stopPropagation();
                this.openWhatsApp();
            });
        }

        // Клик по контейнеру
        this.container.addEventListener('click', (e) => {
            // Если клик по кнопкам - не обрабатываем
            if (e.target === this.closeButton || e.target === this.whatsappButton || this.whatsappButton?.contains(e.target)) {
                return;
            }

            this.toggleWidget();
        });

        // Intersection Observer для lazy loading
        this.setupIntersectionObserver();
        
        // Обработка загрузки видео
        this.video.addEventListener('canplaythrough', () => {
            this.onVideoLoaded();
        });

        this.video.addEventListener('error', () => {
            this.onVideoError();
        });

        // Автовоспроизведение при наведении (только если видео загружено)
        this.container.addEventListener('mouseenter', () => {
            if (this.isVideoLoaded && !this.isExpanded) {
                this.video.play().catch(e => console.log('Autoplay prevented:', e));
            }
        });

        this.container.addEventListener('mouseleave', () => {
            if (!this.isExpanded) {
                this.video.pause();
            }
        });

        // Обработка изменения видимости страницы
        document.addEventListener('visibilitychange', () => {
            if (document.hidden && !this.isExpanded) {
                this.video.pause();
            } else if (!document.hidden && this.isVideoLoaded && !this.isExpanded) {
                this.video.play().catch(e => console.log('Autoplay prevented:', e));
            }
        });
    }

    setupIntersectionObserver() {
        // Проверяем поддержку IntersectionObserver
        if (!('IntersectionObserver' in window)) {
            // Для старых браузеров загружаем сразу
            setTimeout(() => this.loadVideo(), 1000);
            return;
        }

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.loadVideo();
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1
        });

        observer.observe(this.widget);
    }

    loadVideo() {
        // Принудительная загрузка видео
        this.video.load();
    }

    onVideoLoaded() {
        this.isVideoLoaded = true;
        this.loadingSpinner.style.display = 'none';
        
        // Показываем виджет с анимацией
        setTimeout(() => {
            this.widget.classList.add('loaded');
        }, 300);

        // Автостарт видео без звука
        this.video.play().catch(e => {
            console.log('Autoplay prevented:', e);
            // Если автовоспроизведение заблокировано, показываем кнопку play
            this.showPlayButton();
        });
    }

    onVideoError() {
        console.error('Ошибка загрузки видео');
        this.loadingSpinner.innerHTML = '⚠️';
        this.loadingSpinner.style.animation = 'none';
    }

    showPlayButton() {
        const playButton = this.container.querySelector('.play-button');
        if (playButton) {
            playButton.style.opacity = '1';
        }
    }

    toggleWidget() {
        if (this.isExpanded) {
            this.collapseWidget();
        } else {
            this.expandWidget();
        }
    }

    expandWidget() {
        this.isExpanded = true;
        this.container.classList.add('expanded');
        
        // Включаем звук
        this.video.muted = false;
        this.video.play().catch(e => console.log('Play error:', e));

        // Добавляем обработчик для закрытия по ESC
        this.escKeyHandler = this.handleEscKey.bind(this);
        document.addEventListener('keydown', this.escKeyHandler);

        // Отправляем событие для аналитики (если нужно)
        this.sendAnalytics('widget_expanded');
    }

    collapseWidget() {
        this.isExpanded = false;
        this.container.classList.remove('expanded');
        
        // Выключаем звук
        this.video.muted = true;

        // Убираем обработчик ESC
        if (this.escKeyHandler) {
            document.removeEventListener('keydown', this.escKeyHandler);
            this.escKeyHandler = null;
        }

        // Отправляем событие для аналитики (если нужно)
        this.sendAnalytics('widget_collapsed');
    }

    openWhatsApp() {
        const message = encodeURIComponent('Здравствуйте! Хочу записаться на занятие');
        const whatsappUrl = `https://wa.me/${this.whatsappNumber.replace(/[^0-9]/g, '')}?text=${message}`;
        
        // Открываем WhatsApp в новом окне
        window.open(whatsappUrl, '_blank');
        
        // Отправляем событие для аналитики
        this.sendAnalytics('whatsapp_clicked');
        
        // Опционально: закрываем виджет после клика
        setTimeout(() => {
            this.collapseWidget();
        }, 500);
    }

    handleEscKey(e) {
        if (e.key === 'Escape' && this.isExpanded) {
            this.collapseWidget();
        }
    }

    sendAnalytics(event) {
        // Отправка событий в Google Analytics, Yandex.Metrica и т.д.
        if (typeof gtag !== 'undefined') {
            gtag('event', 'video_widget', {
                'event_category': 'engagement',
                'event_label': event
            });
        }

        if (typeof ym !== 'undefined' && window.yandexMetricaId) {
            ym(window.yandexMetricaId, 'reachGoal', `video_widget_${event}`);
        }
    }

    // Публичные методы для управления виджетом
    show() {
        this.widget.style.display = 'block';
    }

    hide() {
        this.widget.style.display = 'none';
    }

    destroy() {
        // Очистка обработчиков событий
        if (this.escKeyHandler) {
            document.removeEventListener('keydown', this.escKeyHandler);
        }
        
        // Остановка видео
        this.video.pause();
        this.video.src = '';
        
        // Удаление элемента
        if (this.widget.parentNode) {
            this.widget.parentNode.removeChild(this.widget);
        }
    }
}

// Глобальная функция для инициализации (вызывается из HTML)
function initVideoWidget() {
    if (!window.videoWidgetInstance) {
        window.videoWidgetInstance = new VideoWidget();
    }
}

// Инициализация при загрузке DOM
document.addEventListener('DOMContentLoaded', function() {
    // Проверяем наличие виджета на странице
    const widget = document.getElementById('videoWidget');
    if (!widget) return;

    // Если видео уже загружено, инициализируем сразу
    const video = document.getElementById('widgetVideo');
    if (video && video.readyState >= 3) {
        initVideoWidget();
    }
});

// Экспорт для использования в модулях
if (typeof module !== 'undefined' && module.exports) {
    module.exports = VideoWidget;
}