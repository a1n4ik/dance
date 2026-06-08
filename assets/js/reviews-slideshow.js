
        let slideIndex = 0;
        let slideInterval;
        const slides = document.querySelectorAll('.slide');
        const indicators = document.querySelectorAll('.indicator');
        const progressBar = document.querySelector('.progress-bar');

        // Initialize slideshow
        function initSlideshow() {
            showSlide(slideIndex);
            startAutoSlide();
            
            // Show mobile navigation on small screens
            if (window.innerWidth <= 480) {
                document.querySelector('.nav-controls').style.display = 'block';
                document.querySelector('.prev').style.display = 'none';
                document.querySelector('.next').style.display = 'none';
            }
        }

        // Show specific slide
        function showSlide(index) {
            // Hide all slides
            slides.forEach(slide => slide.classList.remove('active'));
            indicators.forEach(indicator => indicator.classList.remove('active'));
            
            // Show current slide
            if (index >= slides.length) slideIndex = 0;
            if (index < 0) slideIndex = slides.length - 1;
            
            slides[slideIndex].classList.add('active');
            indicators[slideIndex].classList.add('active');
            
            // Reset progress bar animation
            progressBar.style.animation = 'none';
            setTimeout(() => {
                progressBar.style.animation = 'progress 6s linear infinite';
            }, 50);
        }

        // Change slide (next/previous)
        function changeSlide(direction) {
            slideIndex += direction;
            showSlide(slideIndex);
            restartAutoSlide();
        }

        // Go to specific slide
        function currentSlide(index) {
            slideIndex = index - 1;
            showSlide(slideIndex);
            restartAutoSlide();
        }

        // Auto slide functionality
        function nextSlide() {
            slideIndex++;
            showSlide(slideIndex);
        }

        function startAutoSlide() {
            slideInterval = setInterval(nextSlide, 6000); // Увеличил время до 6 секунд для просмотра трех отзывов
        }

        function stopAutoSlide() {
            clearInterval(slideInterval);
        }

        function restartAutoSlide() {
            stopAutoSlide();
            startAutoSlide();
        }

        // Pause on hover
        const slideshowContainer = document.getElementById('reviewsSlideshow');
        slideshowContainer.addEventListener('mouseenter', stopAutoSlide);
        slideshowContainer.addEventListener('mouseleave', startAutoSlide);

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') changeSlide(-1);
            if (e.key === 'ArrowRight') changeSlide(1);
        });

        // Touch/swipe support for mobile
        let touchStartX = 0;
        let touchEndX = 0;

        slideshowContainer.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        });

        slideshowContainer.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });

        function handleSwipe() {
            const swipeThreshold = 50;
            const diff = touchStartX - touchEndX;
            
            if (Math.abs(diff) > swipeThreshold) {
                if (diff > 0) {
                    changeSlide(1); // Swipe left - next slide
                } else {
                    changeSlide(-1); // Swipe right - previous slide
                }
            }
        }

        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth <= 480) {
                document.querySelector('.nav-controls').style.display = 'block';
                document.querySelector('.prev').style.display = 'none';
                document.querySelector('.next').style.display = 'none';
            } else {
                document.querySelector('.nav-controls').style.display = 'none';
                document.querySelector('.prev').style.display = 'flex';
                document.querySelector('.next').style.display = 'flex';
            }
        });

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', initSlideshow);

        // Handle visibility change (pause when tab is not active)
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                stopAutoSlide();
            } else {
                startAutoSlide();
            }
        });

        // Preload images for better performance
        function preloadImages() {
            const imageUrls = [
                'https://stolitsa-dance.ru/uploads/com/1.jpg',
                'https://stolitsa-dance.ru/uploads/com/2.jpg',
                'https://stolitsa-dance.ru/uploads/com/3.jpg',
                'https://stolitsa-dance.ru/uploads/com/4.jpg',
                'https://stolitsa-dance.ru/uploads/com/5.jpg',
                'https://stolitsa-dance.ru/uploads/com/6.jpg',
                'https://stolitsa-dance.ru/uploads/com/7.jpg',
                'https://stolitsa-dance.ru/uploads/com/8.jpg',
                'https://stolitsa-dance.ru/uploads/com/9.jpg'
            ];
            
            imageUrls.forEach(url => {
                const img = new Image();
                img.src = url;
            });
        }

        // Start preloading after page loads
        document.addEventListener('DOMContentLoaded', preloadImages);
