document.addEventListener('DOMContentLoaded', () => {
    const slides = document.querySelectorAll('.slide');
    let index = 0;

    function showSlide() {
        // Move slides to the left by applying the translateX transformation
        document.querySelector('.slider-wrapper').style.transform = `translateX(-${index * 100}%)`;
        index = (index + 1) % slides.length; // Move to the next slide
    }

    showSlide(); // Show the initial slide immediately
    setInterval(showSlide, 3000); 
    // Change slide every 3 seconds
});


// Pop - up
window.addEventListener('load', () => {
    // Get the pop-up element
    const popup = document.getElementById('popup');

    // Show the pop-up
    popup.style.display = 'block';

    // Hide the pop-up after 15 seconds
    setTimeout(() => {
        popup.style.display = 'none';
    }, 10000); // 15000 milliseconds = 15 seconds
});


