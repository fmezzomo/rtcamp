document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.slide');
    const transitionEffect = document.getElementById('transition-effect');
    let currentIndex = 0;

    // Show the first slide
    if (slides.length > 0) {
        slides[currentIndex].style.display = 'block';
    }

    function changeSlide(direction) {
        if (transitionEffect) {
            slides[currentIndex].style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            slides[currentIndex].style.opacity = 0;
            slides[currentIndex].style.transform = direction === 1 ? 'translateX(-100%)' : 'translateX(100%)';
            previousIndex = currentIndex;
        
            currentIndex = (currentIndex + direction + slides.length) % slides.length;
        
            setTimeout(() => {
                setTimeout(() => {
                    slides[currentIndex].style.display = 'block';
                    slides[currentIndex].style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    slides[currentIndex].style.opacity = 1;
                    slides[currentIndex].style.transform = 'translateX(0)';

                    slides[previousIndex].style.display = 'none';
                }, 500);
            }, 50);
        } else {
            slides[currentIndex].style.display = 'none';
            currentIndex = (currentIndex + direction + slides.length) % slides.length;
            slides[currentIndex].style.display = 'block';
        }
    }
    

    const nextButton = document.querySelector('.next');
    const prevButton = document.querySelector('.prev');
    
    if (nextButton && prevButton) {
        nextButton.addEventListener('click', () => changeSlide(1));
        prevButton.addEventListener('click', () => changeSlide(-1));
    }

    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowRight') changeSlide(1);
        if (e.key === 'ArrowLeft') changeSlide(-1);
    });

    // Mobile navigation
    let touchStartX = 0;
    let touchEndX = 0;

    function handleGesture() {
        if (touchEndX < touchStartX) changeSlide(1); // Swipe left
        if (touchEndX > touchStartX) changeSlide(-1); // Swipe right
    }

    document.addEventListener('touchstart', (event) => {
        touchStartX = event.changedTouches[0].screenX;
    });

    document.addEventListener('touchend', (event) => {
        touchEndX = event.changedTouches[0].screenX;
        handleGesture();
    });

    const urlInput = document.getElementById('url-input');
    const changeButton = document.getElementById('change-slideshow');

    if (changeButton) {
        changeButton.addEventListener('click', function() {
            const newUrl = urlInput.value.trim();
            if (newUrl) {
                window.location.search = '?url=' + encodeURIComponent(newUrl);
            }
        });
    }
});