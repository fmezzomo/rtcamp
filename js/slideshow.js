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

    document.querySelector('.next').addEventListener('click', () => changeSlide(1));
    document.querySelector('.prev').addEventListener('click', () => changeSlide(-1));

});