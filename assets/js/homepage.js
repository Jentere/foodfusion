// Homepage JavaScript

document.addEventListener('DOMContentLoaded', function () {
    // Counter Animation
    animateCounters();

    // Events Carousel
    initEventsCarousel();

    // Join Modal
    initJoinModal();

    // Cookie Consent
    initCookieConsent();

    // Password Strength Checker
    initPasswordStrength();

    // Google Signup
    initGoogleSignup();

    // Smooth Scroll
    initSmoothScroll();
});

// Counter Animation
function animateCounters() {
    const counters = document.querySelectorAll('.counter');
    const speed = 200; // Animation speed

    const observerOptions = {
        threshold: 0.5
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const target = +counter.getAttribute('data-target');
                const increment = target / speed;

                const updateCount = () => {
                    const count = +counter.innerText.replace(/[^0-9]/g, '');

                    if (count < target) {
                        counter.innerText = Math.ceil(count + increment).toLocaleString() + '+';
                        setTimeout(updateCount, 10);
                    } else {
                        counter.innerText = target.toLocaleString() + '+';
                    }
                };

                updateCount();
                observer.unobserve(counter);
            }
        });
    }, observerOptions);

    counters.forEach(counter => observer.observe(counter));
}

// Events Carousel
function initEventsCarousel() {
    const carousel = document.getElementById('eventsCarousel');
    if (!carousel) return;

    const slides = carousel.querySelectorAll('.event-slide');
    const dotsContainer = document.getElementById('carouselDots');
    const prevBtn = document.getElementById('prevSlide');
    const nextBtn = document.getElementById('nextSlide');

    let currentSlide = 0;

    // Create dots
    slides.forEach((_, index) => {
        const dot = document.createElement('div');
        dot.classList.add('carousel-dot');
        if (index === 0) dot.classList.add('active');
        dot.addEventListener('click', () => goToSlide(index));
        dotsContainer.appendChild(dot);
    });

    const dots = dotsContainer.querySelectorAll('.carousel-dot');

    function goToSlide(n) {
        slides[currentSlide].classList.remove('active');
        dots[currentSlide].classList.remove('active');

        currentSlide = (n + slides.length) % slides.length;

        slides[currentSlide].classList.add('active');
        dots[currentSlide].classList.add('active');
    }

    function nextSlide() {
        goToSlide(currentSlide + 1);
    }

    function prevSlide() {
        goToSlide(currentSlide - 1);
    }

    prevBtn.addEventListener('click', prevSlide);
    nextBtn.addEventListener('click', nextSlide);

    // Auto-advance carousel
    setInterval(nextSlide, 5000);
}

// Join Modal
function initJoinModal() {
    const modal = document.getElementById('joinModal');
    if (!modal) return;

    const openBtns = document.querySelectorAll('.join-us-btn, #heroJoinBtn');
    const closeBtn = document.getElementById('closeJoinModal');
    const backdrop = modal.querySelector('.join-backdrop');

    openBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    });

    function closeModal() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    closeBtn.addEventListener('click', closeModal);
    backdrop.addEventListener('click', closeModal);

    // Handle form submission
    const joinForm = document.getElementById('joinForm');
    const joinMessage = document.getElementById('joinMessage');

    joinForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const submitBtn = joinForm.querySelector('.btn-join-submit');
        const originalBtnContent = submitBtn.innerHTML;

        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Creating Account...</span>';

        const formData = new FormData(joinForm);

        try {
            const response = await fetch('/foodfusion/auth/register.php', {
                method: 'POST',
                body: formData
            });

            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Server returned non-JSON response. Please check server configuration.');
            }

            const data = await response.json();

            if (data.success) {
                console.log('Registration successful!', data);
                showMessage('Registration successful! Redirecting to login...', 'success');

                // Reset form
                joinForm.reset();

                // Redirect after 1.5 seconds
                const redirectTimer = setTimeout(() => {
                    console.log('Attempting redirect to login page...');
                    try {
                        window.location.replace('/foodfusion/auth/login.php');
                        console.log('Redirect initiated');
                    } catch (error) {
                        console.error('Redirect failed:', error);
                        // Fallback redirect method
                        window.location.href = '/foodfusion/auth/login.php';
                    }
                }, 1500);
                console.log('Redirect timer set:', redirectTimer);
            } else {
                // Display errors
                let errorMessage = data.message || 'Registration failed. Please try again.';

                if (data.errors && data.errors.length > 0) {
                    errorMessage += '<ul style="margin: 0.5rem 0 0 1.5rem; padding: 0; text-align: left;">';
                    data.errors.forEach(error => {
                        errorMessage += `<li>${error}</li>`;
                    });
                    errorMessage += '</ul>';
                }

                showMessage(errorMessage, 'error');

                // Reset button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnContent;
            }
        } catch (error) {
            console.error('Error:', error);
            showMessage(error.message || 'An error occurred. Please try again.', 'error');

            // Reset button
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnContent;
        }
    });

    function showMessage(message, type) {
        joinMessage.innerHTML = message;
        joinMessage.className = 'join-message ' + type;
        joinMessage.style.display = 'block';

        // Scroll message into view
        joinMessage.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

        // Auto-hide success messages after 5 seconds
        if (type === 'success') {
            setTimeout(() => {
                joinMessage.style.display = 'none';
            }, 5000);
        }
    }
}

// Password Toggle
function toggleJoinPassword() {
    const passwordInput = document.getElementById('joinPassword');
    const toggleIcon = document.getElementById('joinToggleIcon');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

// Password Strength Checker
function initPasswordStrength() {
    const passwordInput = document.getElementById('joinPassword');
    if (!passwordInput) return;

    const strengthBar = document.querySelector('.strength-bar');
    const strengthText = document.querySelector('.strength-text');

    passwordInput.addEventListener('input', () => {
        const password = passwordInput.value;
        const strength = calculatePasswordStrength(password);

        // Update bar
        if (strengthBar) {
            strengthBar.style.setProperty('--strength', strength.percentage + '%');
            strengthBar.style.background = strength.color;
        }

        // Update text
        if (strengthText) {
            strengthText.textContent = strength.text;
            strengthText.style.color = strength.color;
        }

        // Update password requirements
        updatePasswordRequirements(password);
    });
}

// Update password requirements indicators
function updatePasswordRequirements(password) {
    const requirements = {
        'popup-req-length': password.length >= 8,
        'popup-req-uppercase': /[A-Z]/.test(password),
        'popup-req-lowercase': /[a-z]/.test(password),
        'popup-req-number': /[0-9]/.test(password),
        'popup-req-special': /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
    };

    for (const [id, met] of Object.entries(requirements)) {
        const element = document.getElementById(id);
        if (element) {
            if (met) {
                element.classList.add('met');
                const icon = element.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-circle');
                    icon.classList.add('fa-check-circle');
                }
            } else {
                element.classList.remove('met');
                const icon = element.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-check-circle');
                    icon.classList.add('fa-circle');
                }
            }
        }
    }
}

function calculatePasswordStrength(password) {
    let strength = 0;

    if (password.length >= 8) strength += 25;
    if (password.length >= 12) strength += 25;
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 25;
    if (/[0-9]/.test(password)) strength += 12.5;
    if (/[^a-zA-Z0-9]/.test(password)) strength += 12.5;

    let text = '';
    let color = '';

    if (strength < 25) {
        text = 'Weak';
        color = '#dc3545';
    } else if (strength < 50) {
        text = 'Fair';
        color = '#ffc107';
    } else if (strength < 75) {
        text = 'Good';
        color = '#17a2b8';
    } else {
        text = 'Strong';
        color = '#28a745';
    }

    return {
        percentage: strength,
        text: text,
        color: color
    };
}

// Cookie Consent
function initCookieConsent() {
    const cookieConsent = document.getElementById('cookieConsent');
    if (!cookieConsent) return;

    const acceptBtn = document.getElementById('acceptCookies');
    const declineBtn = document.getElementById('declineCookies');

    // Check if user has already made a choice
    if (!localStorage.getItem('cookieConsent')) {
        setTimeout(() => {
            cookieConsent.classList.add('show');
        }, 2000);
    }

    acceptBtn.addEventListener('click', () => {
        localStorage.setItem('cookieConsent', 'accepted');
        cookieConsent.classList.remove('show');
    });

    declineBtn.addEventListener('click', () => {
        localStorage.setItem('cookieConsent', 'declined');
        cookieConsent.classList.remove('show');
    });
}

// Google Signup
function initGoogleSignup() {
    const googleBtn = document.getElementById('googleSignupBtn');
    if (!googleBtn) return;

    googleBtn.addEventListener('click', () => {
        // Redirect to Google OAuth login
        window.location.href = '/foodfusion/auth/google-login.php';
    });
}

// Smooth Scroll
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href === '#') return;

            e.preventDefault();
            const target = document.querySelector(href);

            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Intersection Observer for animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observe recipe cards
document.querySelectorAll('.recipe-card').forEach(card => {
    observer.observe(card);
});

// Parallax effect for hero section
window.addEventListener('scroll', () => {
    const scrolled = window.pageYOffset;
    const heroContent = document.querySelector('.hero-content');

    if (heroContent && scrolled < window.innerHeight) {
        heroContent.style.transform = `translateY(${scrolled * 0.5}px)`;
        heroContent.style.opacity = 1 - (scrolled / window.innerHeight);
    }
});

// Add loading animation
window.addEventListener('load', () => {
    document.body.classList.add('loaded');
});
