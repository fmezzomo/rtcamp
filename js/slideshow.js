document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.slide');
    let currentIndex = 0;

    // Show the first slide
    if (slides.length > 0) {
        slides[currentIndex].style.display = 'block';
    }

    function changeSlide(direction) {
        slides[currentIndex].style.display = 'none';
        currentIndex = (currentIndex + direction + slides.length) % slides.length;
        slides[currentIndex].style.display = 'block';
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