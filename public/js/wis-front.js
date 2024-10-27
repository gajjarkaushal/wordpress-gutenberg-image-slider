// banner-slider.js
document.addEventListener('DOMContentLoaded', () => {
    let slideIndex = 0;

    function showSlide(index) {
        const slides = document.querySelectorAll('.slide');

        if (index >= slides.length) {
            slideIndex = 0; // Loop back to first slide
        } else if (index < 0) {
            slideIndex = slides.length - 1; // Go to last slide
        } else {
            slideIndex = index;
        }

        slides.forEach((slide, i) => {
            slide.style.display = (i === slideIndex) ? 'block' : 'none'; // Show current slide
        });
    }

    function changeSlide(n) {
        showSlide(slideIndex + n);
    }

    // Auto slide every 5 seconds
    setInterval(() => {
        changeSlide(1);
    }, 5000);

    // Show the first slide
    showSlide(slideIndex);

    // Add event listeners for navigation buttons
    const prevButton = document.querySelector('.prev');
    const nextButton = document.querySelector('.next');

    prevButton.addEventListener('click', () => changeSlide(-1));
    nextButton.addEventListener('click', () => changeSlide(1));
});
