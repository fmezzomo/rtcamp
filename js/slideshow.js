document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.slide');
    let currentIndex = 0;

    // Show the first slide
    if (slides.length > 0) {
        slides[currentIndex].style.display = 'block';
    }
});