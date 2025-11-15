// Mobile menu toggle (legacy support)
const menuToggle = document.getElementById('menuToggle');
if (menuToggle) {
    menuToggle.addEventListener('click', function () {
        const navLinks = document.getElementById('navLinks');
        navLinks.classList.toggle('active');
    });
}

// Cookie Notice Handler
document.addEventListener('DOMContentLoaded', function() {
    const cookieNotice = document.getElementById('cookieNotice');
    const acceptCookiesBtn = document.getElementById('acceptCookies');
    
    // Check if user has already accepted cookies
    if (cookieNotice && !localStorage.getItem('cookiesAccepted')) {
        // Show cookie notice
        cookieNotice.style.display = 'flex';
        
        // Handle accept button click
        if (acceptCookiesBtn) {
            acceptCookiesBtn.addEventListener('click', function() {
                localStorage.setItem('cookiesAccepted', 'true');
                cookieNotice.style.display = 'none';
            });
        }
    } else if (cookieNotice) {
        // Hide cookie notice if already accepted
        cookieNotice.style.display = 'none';
    }
    
    // Carousel functionality
    const slides = document.querySelectorAll('.slide');
    const prevBtn = document.getElementById('prevSlide');
    const nextBtn = document.getElementById('nextSlide');
    let currentSlide = 0;
    
    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.remove('active');
            if (i === index) {
                slide.classList.add('active');
            }
        });
    }
    
    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            showSlide(currentSlide);
        });
    }
    
    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        });
    }
    
    // Auto-advance carousel every 5 seconds
    if (slides.length > 0) {
        setInterval(function() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }, 5000);
    }
});
