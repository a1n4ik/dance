/**
 * ========== cookie-consent.js - Уведомление о cookie ==========
 * Скрипт для уведомления о использовании cookie на сайте театра танца "Столица"
 * Добавить в footer.php перед закрывающим тегом </body>
 */

(function() {
    'use strict';

    // Конфигурация
    const CONFIG = {
        cookieName: 'stolitsa_cookie_consent',
        cookieExpireDays: 365,
        animationDuration: 300,
        autoHideDelay: null, // Не скрывать автоматически
        position: 'bottom', // bottom, top
        theme: 'light' // light, dark
    };

    // Тексты уведомлений
    const TEXTS = {
        title: 'Мы используем cookie',
        message: 'Наш сайт использует файлы cookie для анализа посещаемости через Яндекс.Метрику и улучшения пользовательского опыта. Продолжая использовать сайт, вы соглашаетесь с этим.',
        acceptButton: 'Принять',
        declineButton: 'Отклонить',
        settingsButton: 'Настройки',
        policyLink: 'Политика конфиденциальности',
        policyUrl: '/privacy.php'
    };

    // Проверяем, нужно ли показывать уведомление
    function shouldShowConsent() {
        return !getCookie(CONFIG.cookieName);
    }

    // Получение cookie
    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) {
            return parts.pop().split(';').shift();
        }
        return null;
    }

    // Установка cookie
    function setCookie(name, value, days) {
        const expires = new Date();
        expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
        document.cookie = `${name}=${value};expires=${expires.toUTCString()};path=/;SameSite=Lax`;
    }

    // Создание HTML уведомления
    function createConsentHTML() {
        return `
            <div id="cookie-consent" class="cookie-consent cookie-consent--${CONFIG.position} cookie-consent--${CONFIG.theme}">
                <div class="cookie-consent__container">
                    <div class="cookie-consent__content">
                        <div class="cookie-consent__text">
                            <h3 class="cookie-consent__title">${TEXTS.title}</h3>
                            <p class="cookie-consent__message">
                                ${TEXTS.message}
                                <a href="${TEXTS.policyUrl}" class="cookie-consent__policy-link" target="_blank">
                                    ${TEXTS.policyLink}
                                </a>
                            </p>
                        </div>
                        <div class="cookie-consent__buttons">
                            <button class="cookie-consent__btn cookie-consent__btn--accept" id="cookie-accept">
                                ${TEXTS.acceptButton}
                            </button>
                            <button class="cookie-consent__btn cookie-consent__btn--decline" id="cookie-decline">
                                ${TEXTS.declineButton}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    // CSS стили
    function injectStyles() {
        const css = `
            .cookie-consent {
                position: fixed;
                left: 0;
                right: 0;
                background: rgba(0, 0, 0, 0.95);
                backdrop-filter: blur(10px);
                color: white;
                z-index: 10000;
                transform: translateY(100%);
                transition: transform ${CONFIG.animationDuration}ms ease-in-out;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                box-shadow: 0 -5px 20px rgba(0, 0, 0, 0.3);
            }

            .cookie-consent--bottom {
                bottom: 0;
            }

            .cookie-consent--top {
                top: 0;
                transform: translateY(-100%);
                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
            }

            .cookie-consent--light {
                background: rgba(255, 255, 255, 0.98);
                color: #333;
                box-shadow: 0 -5px 20px rgba(0, 0, 0, 0.1);
            }

            .cookie-consent--light.cookie-consent--top {
                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            }

            .cookie-consent.show {
                transform: translateY(0);
            }

            .cookie-consent__container {
                max-width: 1400px;
                margin: 0 auto;
                padding: 0 2rem;
            }

            .cookie-consent__content {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 2rem;
                padding: 1.5rem 0;
                min-height: 80px;
            }

            .cookie-consent__text {
                flex: 1;
                max-width: 70%;
            }

            .cookie-consent__title {
                font-size: 1.1rem;
                font-weight: 600;
                margin: 0 0 0.5rem 0;
                color: inherit;
            }

            .cookie-consent__message {
                font-size: 0.9rem;
                line-height: 1.5;
                margin: 0;
                opacity: 0.9;
            }

            .cookie-consent__policy-link {
                color: #667eea;
                text-decoration: underline;
                font-weight: 500;
            }

            .cookie-consent--light .cookie-consent__policy-link {
                color: #764ba2;
            }

            .cookie-consent__policy-link:hover {
                opacity: 0.8;
            }

            .cookie-consent__buttons {
                display: flex;
                gap: 1rem;
                align-items: center;
                flex-shrink: 0;
            }

            .cookie-consent__btn {
                padding: 0.75rem 1.5rem;
                border: none;
                border-radius: 25px;
                font-size: 0.9rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.2s ease;
                min-width: 100px;
                text-align: center;
            }

            .cookie-consent__btn--accept {
                background: #667eea;
                color: white;
            }

            .cookie-consent__btn--accept:hover {
                background: #5a67d8;
                transform: translateY(-1px);
            }

            .cookie-consent__btn--decline {
                background: transparent;
                color: white;
                border: 1px solid rgba(255, 255, 255, 0.3);
            }

            .cookie-consent--light .cookie-consent__btn--decline {
                color: #666;
                border-color: rgba(0, 0, 0, 0.2);
            }

            .cookie-consent__btn--decline:hover {
                background: rgba(255, 255, 255, 0.1);
                border-color: rgba(255, 255, 255, 0.5);
            }

            .cookie-consent--light .cookie-consent__btn--decline:hover {
                background: rgba(0, 0, 0, 0.05);
                border-color: rgba(0, 0, 0, 0.3);
            }

            /* Адаптивность */
            @media (max-width: 768px) {
                .cookie-consent__container {
                    padding: 0 1rem;
                }

                .cookie-consent__content {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 1rem;
                    padding: 1rem 0;
                    text-align: left;
                }

                .cookie-consent__text {
                    max-width: 100%;
                }

                .cookie-consent__buttons {
                    width: 100%;
                    justify-content: center;
                    flex-wrap: wrap;
                    gap: 0.8rem;
                }

                .cookie-consent__btn {
                    flex: 1;
                    min-width: 120px;
                }

                .cookie-consent__title {
                    font-size: 1rem;
                }

                .cookie-consent__message {
                    font-size: 0.85rem;
                }
            }

            @media (max-width: 480px) {
                .cookie-consent__buttons {
                    flex-direction: column;
                    gap: 0.8rem;
                }

                .cookie-consent__btn {
                    width: 100%;
                    min-width: auto;
                }
            }

            /* Анимация появления */
            @keyframes fadeIn {
                from {
                    opacity: 0;
                }
                to {
                    opacity: 1;
                }
            }

            .cookie-consent {
                animation: fadeIn 0.3s ease-out;
            }
        `;

        const styleSheet = document.createElement('style');
        styleSheet.textContent = css;
        document.head.appendChild(styleSheet);
    }

    // Показать уведомление
    function showConsent() {
        // Вставляем HTML
        document.body.insertAdjacentHTML('beforeend', createConsentHTML());
        
        const consentElement = document.getElementById('cookie-consent');
        
        // Показываем с анимацией
        requestAnimationFrame(() => {
            consentElement.classList.add('show');
        });

        // Обработчики событий
        setupEventListeners();
    }

    // Скрыть уведомление
    function hideConsent() {
        const consentElement = document.getElementById('cookie-consent');
        if (consentElement) {
            consentElement.classList.remove('show');
            
            setTimeout(() => {
                consentElement.remove();
            }, CONFIG.animationDuration);
        }
    }

    // Принять cookie
    function acceptCookies() {
        setCookie(CONFIG.cookieName, 'accepted', CONFIG.cookieExpireDays);
        enableAnalytics();
        hideConsent();
        
        // Отправляем событие для аналитики
        if (window.ym) {
            ym(window.yaCounter, 'reachGoal', 'cookie_accepted');
        }
    }

    // Отклонить cookie
    function declineCookies() {
        setCookie(CONFIG.cookieName, 'declined', CONFIG.cookieExpireDays);
        disableAnalytics();
        hideConsent();

        // Отправляем событие для аналитики (если разрешено)
        if (window.ym) {
            ym(window.yaCounter, 'reachGoal', 'cookie_declined');
        }
    }

    // Включить аналитику
    function enableAnalytics() {
        // Если Яндекс.Метрика еще не инициализирована
        if (!window.ym && window.yaCounterCode) {
            // Выполняем отложенный код Яндекс.Метрики
            eval(window.yaCounterCode);
        }
        
        // Устанавливаем флаг разрешения аналитики
        localStorage.setItem('analytics_enabled', 'true');
    }

    // Отключить аналитику
    function disableAnalytics() {
        // Удаляем cookie Яндекс.Метрики
        const ymCookies = ['_ym_uid', '_ym_d', '_ym_isad', '_ym_visorc'];
        ymCookies.forEach(cookieName => {
            document.cookie = `${cookieName}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
            document.cookie = `${cookieName}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=.${window.location.hostname};`;
        });

        // Устанавливаем флаг запрета аналитики
        localStorage.setItem('analytics_enabled', 'false');
        
        // Если Метрика уже загружена, отправляем команду на отключение
        if (window.ym && window.yaCounter) {
            ym(window.yaCounter, 'userParams', { disable_tracking: true });
        }
    }

    // Настройка обработчиков событий
    function setupEventListeners() {
        const acceptBtn = document.getElementById('cookie-accept');
        const declineBtn = document.getElementById('cookie-decline');

        if (acceptBtn) {
            acceptBtn.addEventListener('click', acceptCookies);
        }

        if (declineBtn) {
            declineBtn.addEventListener('click', declineCookies);
        }

        // Закрытие по Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && document.getElementById('cookie-consent')) {
                declineCookies();
            }
        });
    }

    // Проверка статуса согласия
    function getConsentStatus() {
        const consent = getCookie(CONFIG.cookieName);
        return {
            given: !!consent,
            accepted: consent === 'accepted',
            declined: consent === 'declined'
        };
    }

    // Инициализация
    function init() {
        // Вставляем стили
        injectStyles();

        // Показываем уведомление если нужно
        if (shouldShowConsent()) {
            // Небольшая задержка для загрузки страницы
            setTimeout(showConsent, 1000);
        } else {
            // Если согласие уже дано, включаем аналитику
            const status = getConsentStatus();
            if (status.accepted) {
                enableAnalytics();
            }
        }
    }

    // Публичный API
    window.CookieConsent = {
        show: showConsent,
        hide: hideConsent,
        accept: acceptCookies,
        decline: declineCookies,
        getStatus: getConsentStatus,
        reset: function() {
            document.cookie = `${CONFIG.cookieName}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
            location.reload();
        }
    };

    // Запуск при загрузке DOM
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();