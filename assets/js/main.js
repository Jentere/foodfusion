/**
 * Modern Navigation System
 * Beautiful, Responsive & Interactive
 */

class ModernNavigation {
    constructor() {
        this.nav = document.getElementById('modernNav');
        this.mobileToggle = document.getElementById('mobileToggle');
        this.navLinks = document.getElementById('navLinks');
        this.mobileOverlay = document.getElementById('mobileOverlay');
        this.navAuth = document.getElementById('navAuth');
        
        // Modal elements
        this.loginModal = document.getElementById('loginModal');
        this.signupModal = document.getElementById('signupModal');
        this.loginBtn = document.getElementById('loginBtn');
        this.signupBtn = document.getElementById('signupBtn');
        this.closeLogin = document.getElementById('closeLogin');
        this.closeSignup = document.getElementById('closeSignup');
        this.loginBackdrop = document.getElementById('loginBackdrop');
        this.signupBackdrop = document.getElementById('signupBackdrop');
        
        // Form elements
        this.loginForm = document.getElementById('loginForm');
        this.signupForm = document.getElementById('signupForm');
        this.loginMessage = document.getElementById('loginMessage');
        this.signupMessage = document.getElementById('signupMessage');
        
        this.isMenuOpen = false;
        this.lastScrollY = 0;
        
        this.init();
    }
    
    init() {
        this.bindEvents();
        this.setActiveLink();
        this.handleScroll();
    }
    
    bindEvents() {
        // Mobile menu toggle
        if (this.mobileToggle) {
            this.mobileToggle.addEventListener('click', () => this.toggleMobileMenu());
        }
        
        // Close mobile menu when clicking overlay
        if (this.mobileOverlay) {
            this.mobileOverlay.addEventListener('click', () => this.closeMobileMenu());
        }
        
        // Close mobile menu when clicking nav links
        if (this.navLinks) {
            this.navLinks.addEventListener('click', (e) => {
                if (e.target.classList.contains('nav-link')) {
                    this.closeMobileMenu();
                }
            });
        }
        
        // Modal events
        if (this.loginBtn) {
            this.loginBtn.addEventListener('click', () => this.openModal('login'));
        }
        
        if (this.signupBtn) {
            this.signupBtn.addEventListener('click', () => this.openModal('signup'));
        }
        
        if (this.closeLogin) {
            this.closeLogin.addEventListener('click', () => this.closeModal('login'));
        }
        
        if (this.closeSignup) {
            this.closeSignup.addEventListener('click', () => this.closeModal('signup'));
        }
        
        if (this.loginBackdrop) {
            this.loginBackdrop.addEventListener('click', () => this.closeModal('login'));
        }
        
        if (this.signupBackdrop) {
            this.signupBackdrop.addEventListener('click', () => this.closeModal('signup'));
        }
        
        // Form submissions
        if (this.loginForm) {
            this.loginForm.addEventListener('submit', (e) => this.handleLogin(e));
        }
        
        if (this.signupForm) {
            this.signupForm.addEventListener('submit', (e) => this.handleSignup(e));
        }
        
        // Scroll events
        window.addEventListener('scroll', () => this.handleScroll());
        
        // Resize events
        window.addEventListener('resize', () => this.handleResize());
        
        // Keyboard events
        document.addEventListener('keydown', (e) => this.handleKeyboard(e));
        
        // Smooth scroll for anchor links
        document.addEventListener('click', (e) => {
            if (e.target.matches('a[href^="#"]')) {
                e.preventDefault();
                const target = document.querySelector(e.target.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    }
    
    toggleMobileMenu() {
        if (this.isMenuOpen) {
            this.closeMobileMenu();
        } else {
            this.openMobileMenu();
        }
    }
    
    openMobileMenu() {
        this.isMenuOpen = true;
        this.mobileToggle?.classList.add('active');
        this.navLinks?.classList.add('active');
        this.mobileOverlay?.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        // Add staggered animation to nav links
        const links = this.navLinks?.querySelectorAll('.nav-link');
        links?.forEach((link, index) => {
            link.style.transitionDelay = `${(index + 1) * 0.05}s`;
        });
    }
    
    closeMobileMenu() {
        this.isMenuOpen = false;
        this.mobileToggle?.classList.remove('active');
        this.navLinks?.classList.remove('active');
        this.mobileOverlay?.classList.remove('active');
        document.body.style.overflow = '';
        
        // Reset transition delays
        const links = this.navLinks?.querySelectorAll('.nav-link');
        links?.forEach((link) => {
            link.style.transitionDelay = '';
        });
    }
    
    openModal(type) {
        const modal = type === 'login' ? this.loginModal : this.signupModal;
        const form = type === 'login' ? this.loginForm : this.signupForm;
        const message = type === 'login' ? this.loginMessage : this.signupMessage;
        
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
            
            // Reset form and message
            if (form) form.reset();
            if (message) {
                message.classList.remove('show', 'success', 'error');
                message.textContent = '';
            }
            
            // Focus first input
            setTimeout(() => {
                const firstInput = form?.querySelector('input');
                if (firstInput) firstInput.focus();
            }, 300);
        }
        
        // Close mobile menu if open
        this.closeMobileMenu();
    }
    
    closeModal(type) {
        const modal = type === 'login' ? this.loginModal : this.signupModal;
        
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    }
    
    async handleLogin(e) {
        e.preventDefault();
        
        const formData = new FormData(this.loginForm);
        const submitBtn = this.loginForm.querySelector('.form-submit');
        const originalText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Signing In...</span>';
        submitBtn.disabled = true;
        
        try {
            // Use dynamic base path from PHP
            const basePath = window.BASE_PATH || '/';
            const response = await fetch(basePath + 'auth/login.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showMessage(this.loginMessage, result.message, 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                this.showMessage(this.loginMessage, result.message, 'error');
            }
        } catch (error) {
            this.showMessage(this.loginMessage, 'An error occurred. Please try again.', 'error');
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }
    
    async handleSignup(e) {
        e.preventDefault();
        
        const formData = new FormData(this.signupForm);
        const password = formData.get('password');
        const confirmPassword = formData.get('confirm_password');
        const submitBtn = this.signupForm.querySelector('.form-submit');
        const originalText = submitBtn.innerHTML;
        
        // Validate passwords match
        if (password !== confirmPassword) {
            this.showMessage(this.signupMessage, 'Passwords do not match!', 'error');
            return;
        }
        
        // Validate password strength
        if (password.length < 8) {
            this.showMessage(this.signupMessage, 'Password must be at least 8 characters long!', 'error');
            return;
        }
        
        // Check for password complexity
        const hasUpperCase = /[A-Z]/.test(password);
        const hasLowerCase = /[a-z]/.test(password);
        const hasNumber = /[0-9]/.test(password);
        
        if (!hasUpperCase || !hasLowerCase || !hasNumber) {
            this.showMessage(this.signupMessage, 'Password must contain uppercase, lowercase, and numbers!', 'error');
            return;
        }
        
        // Show loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Creating Account...</span>';
        submitBtn.disabled = true;
        
        try {
            // Use dynamic base path from PHP
            const basePath = window.BASE_PATH || '/';
            const response = await fetch(basePath + 'auth/register.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showMessage(this.signupMessage, result.message, 'success');
                setTimeout(() => {
                    this.closeModal('signup');
                    this.openModal('login');
                    this.showMessage(this.loginMessage, 'Account created! Please sign in.', 'success');
                }, 2000);
            } else {
                this.showMessage(this.signupMessage, result.message, 'error');
            }
        } catch (error) {
            this.showMessage(this.signupMessage, 'An error occurred. Please try again.', 'error');
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }
    
    showMessage(element, text, type) {
        if (element) {
            element.textContent = text;
            element.className = `form-message show ${type}`;
        }
    }
    
    setActiveLink() {
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.nav-link');
        
        navLinks.forEach(link => {
            const linkPath = new URL(link.href).pathname;
            if (linkPath === currentPath) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
    }
    
    handleScroll() {
        const currentScrollY = window.pageYOffset;
        
        // Add scrolled class for backdrop effect
        if (currentScrollY > 50) {
            this.nav?.classList.add('scrolled');
        } else {
            this.nav?.classList.remove('scrolled');
        }
        
        this.lastScrollY = currentScrollY;
    }
    
    handleResize() {
        // Close mobile menu on desktop
        if (window.innerWidth > 768 && this.isMenuOpen) {
            this.closeMobileMenu();
        }
    }
    
    handleKeyboard(e) {
        // Close modals with Escape key
        if (e.key === 'Escape') {
            if (this.loginModal?.classList.contains('active')) {
                this.closeModal('login');
            }
            if (this.signupModal?.classList.contains('active')) {
                this.closeModal('signup');
            }
            if (this.isMenuOpen) {
                this.closeMobileMenu();
            }
        }
    }
}

// Enhanced Features
class NavigationEnhancements {
    constructor() {
        this.init();
    }
    
    init() {
        this.addRippleEffect();
        this.addParallaxEffect();
        this.addIntersectionObserver();
        this.addPreloader();
    }
    
    addRippleEffect() {
        const buttons = document.querySelectorAll('.auth-btn, .form-submit, .joinBtn');
        
        buttons.forEach(button => {
            button.addEventListener('click', (e) => {
                const ripple = document.createElement('span');
                const rect = button.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.cssText = `
                    position: absolute;
                    width: ${size}px;
                    height: ${size}px;
                    left: ${x}px;
                    top: ${y}px;
                    background: rgba(255, 255, 255, 0.3);
                    border-radius: 50%;
                    transform: scale(0);
                    animation: ripple 0.6s ease-out;
                    pointer-events: none;
                `;
                
                button.style.position = 'relative';
                button.style.overflow = 'hidden';
                button.appendChild(ripple);
                
                setTimeout(() => ripple.remove(), 600);
            });
        });
        
        // Add ripple animation CSS
        if (!document.querySelector('#ripple-styles')) {
            const style = document.createElement('style');
            style.id = 'ripple-styles';
            style.textContent = `
                @keyframes ripple {
                    to {
                        transform: scale(2);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        }
    }
    
    addParallaxEffect() {
        const nav = document.querySelector('.modern-nav');
        
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;
            
            if (nav) {
                nav.style.transform = `translateY(${rate}px)`;
            }
        });
    }
    
    addIntersectionObserver() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        }, observerOptions);
        
        // Observe elements that should animate in
        const animateElements = document.querySelectorAll('.nav-link, .auth-btn');
        animateElements.forEach(el => observer.observe(el));
    }
    
    addPreloader() {
        // Add page transition loader
        const loader = document.createElement('div');
        loader.className = 'page-loader';
        loader.innerHTML = `
            <div class="loader-content">
                <div class="loader-spinner"></div>
                <p>Loading...</p>
            </div>
        `;
        
        const loaderStyles = `
            .page-loader {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 9999;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
            }
            
            .page-loader.active {
                opacity: 1;
                visibility: visible;
            }
            
            .loader-content {
                text-align: center;
            }
            
            .loader-spinner {
                width: 40px;
                height: 40px;
                border: 3px solid #f3f3f3;
                border-top: 3px solid var(--primary-color);
                border-radius: 50%;
                animation: spin 1s linear infinite;
                margin: 0 auto 1rem;
            }
            
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        `;
        
        if (!document.querySelector('#loader-styles')) {
            const style = document.createElement('style');
            style.id = 'loader-styles';
            style.textContent = loaderStyles;
            document.head.appendChild(style);
        }
        
        document.body.appendChild(loader);
        
        // Show loader on navigation
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                if (link.href && link.href !== window.location.href) {
                    loader.classList.add('active');
                }
            });
        });
        
        // Hide loader when page loads
        window.addEventListener('load', () => {
            loader.classList.remove('active');
        });
        
        // Hide loader when navigating back/forward (bfcache)
        window.addEventListener('pageshow', (event) => {
            if (event.persisted) {
                // Page was restored from bfcache
                loader.classList.remove('active');
            }
        });
        
        // Also hide on DOMContentLoaded as backup
        document.addEventListener('DOMContentLoaded', () => {
            loader.classList.remove('active');
        });
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    const navigation = new ModernNavigation();
    const enhancements = new NavigationEnhancements();
    
    // Make navigation globally accessible for Join Us button
    window.modernNavigation = navigation;
    
    // Handle "Join Us" button on homepage
    const joinBtn = document.querySelector('.joinBtn');
    if (joinBtn) {
        console.log('Join Us button found, attaching event listener');
        joinBtn.addEventListener('click', (e) => {
            e.preventDefault();
            console.log('Join Us button clicked');
            if (navigation && navigation.signupModal) {
                navigation.openModal('signup');
            } else {
                console.log('Modal not found, redirecting to register page');
                // Fallback to register page if modal not available
                const basePath = window.BASE_PATH || '/';
                window.location.href = basePath + 'auth/register.php';
            }
        });
    }
    // Note: Join Us button only exists on homepage, so it's normal if not found on other pages
    
    console.log('🚀 Modern Navigation System Loaded Successfully!');
});

// Service Worker for better performance (optional)
// Disabled until sw.js is created
/*
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        const basePath = window.BASE_PATH || '/';
        navigator.serviceWorker.register(basePath + 'sw.js')
            .then(registration => {
                console.log('SW registered: ', registration);
            })
            .catch(registrationError => {
                console.log('SW registration failed: ', registrationError);
            });
    });
}
*/
