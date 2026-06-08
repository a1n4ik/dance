// ========== assets/js/services-interactive.js - Для инвертированной логики ==========

document.addEventListener('DOMContentLoaded', function() {
    console.log('Services interactive script loaded');
    
    const serviceItems = document.querySelectorAll('.services-item');
    const bgImages = document.querySelectorAll('.services-bg-image');
    let currentActive = 0;

    // Проверяем что элементы найдены
    console.log('Service items found:', serviceItems.length);
    console.log('Background images found:', bgImages.length);

    if (serviceItems.length === 0 || bgImages.length === 0) {
        console.warn('Services elements not found!');
        return;
    }

    // Функция для смены фонового изображения
    function changeBgImage(index) {
        console.log('Changing background to index:', index);
        
        // Убираем активный класс со всех фонов
        bgImages.forEach((bg, i) => {
            if (i === index) {
                bg.classList.add('active');
                bg.style.opacity = '1';
            } else {
                bg.classList.remove('active');
                bg.style.opacity = '0';
            }
        });
        
        // Убираем active класс со всех элементов
        serviceItems.forEach((item, i) => {
            if (i === index) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });
        
        currentActive = index;
    }

    // Предзагрузка изображений для плавности
    function preloadImages() {
        const imageUrls = [
            'https://stolitsa-dance.ru/wp-content/uploads/2024/05/photo_2024-05-11_12-19-27.jpg',
            'https://stolitsa-dance.ru/wp-content/uploads/2024/05/photo_2024-05-11_12-21-08.jpg',
            'https://stolitsa-dance.ru/wp-content/uploads/2022/09/jazz.jpg',
            'https://stolitsa-dance.ru/wp-content/uploads/2023/08/bb.jpeg',
            'https://images.unsplash.com/photo-1508807526345-15e9b5f4eaff?w=1200',
            'https://stolitsa-dance.ru/wp-content/uploads/2022/09/prob0.jpg'
        ];

        imageUrls.forEach(url => {
            const img = new Image();
            img.src = url;
        });
        
        console.log('Images preloaded:', imageUrls.length);
    }

    // Обработчики событий для каждого элемента
    serviceItems.forEach((item, index) => {
        console.log('Adding event listeners for item', index);
        
        // При наведении мыши
        item.addEventListener('mouseenter', () => {
            console.log('Mouse enter on item', index);
            changeBgImage(index);
        });

        // Эффект при клике - убираем transform чтобы не конфликтовать с CSS
        item.addEventListener('click', function(e) {
            console.log('Click on item', index);
            // Легкий эффект пульса через изменение border
            this.style.borderColor = 'rgba(255, 255, 255, 0.8)';
            setTimeout(() => {
                this.style.borderColor = '';
            }, 200);
        });

        // Обработка фокуса для доступности
        item.addEventListener('focus', () => {
            console.log('Focus on item', index);
            changeBgImage(index);
        });
    });

    // Обработка изменения размера окна
    window.addEventListener('resize', () => {
        console.log('Window resized');
        bgImages.forEach(bg => {
            bg.style.transition = 'none';
            setTimeout(() => {
                bg.style.transition = 'opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1)';
            }, 100);
        });
    });

    // Поддержка клавиатуры для доступности
    document.addEventListener('keydown', (e) => {
        if (e.target.classList.contains('services-item')) {
            if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
                e.preventDefault();
                const currentIndex = Array.from(serviceItems).indexOf(e.target);
                const nextIndex = (currentIndex + 1) % serviceItems.length;
                serviceItems[nextIndex].focus();
                changeBgImage(nextIndex);
            } else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
                e.preventDefault();
                const currentIndex = Array.from(serviceItems).indexOf(e.target);
                const prevIndex = currentIndex === 0 ? serviceItems.length - 1 : currentIndex - 1;
                serviceItems[prevIndex].focus();
                changeBgImage(prevIndex);
            }
        }
    });

    // Инициализация
    console.log('Initializing services interactive...');
    
    // Предзагружаем изображения
    preloadImages();
    
    // Устанавливаем первый элемент как активный
    if (serviceItems.length > 0 && bgImages.length > 0) {
        changeBgImage(0);
        console.log('Set initial active item to index 0');
    }

    console.log('Services interactive initialized successfully - inverted logic');
});