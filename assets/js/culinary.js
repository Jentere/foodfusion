// Culinary Page JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll for navigation cards
    initSmoothScroll();
    
    // Animate elements on scroll
    initScrollAnimations();
    
    // Track downloads
    trackDownloads();
    
    // Add loading states
    initLoadingStates();
});

// Smooth Scroll
function initSmoothScroll() {
    const navCards = document.querySelectorAll('.nav-card');
    
    navCards.forEach(card => {
        card.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            if (href.startsWith('#')) {
                e.preventDefault();
                const target = document.querySelector(href);
                
                if (target) {
                    const offset = 100; // Account for sticky nav
                    const targetPosition = target.offsetTop - offset;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            }
        });
    });
}

// Scroll Animations
function initScrollAnimations() {
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
    
    // Observe cards
    const cards = document.querySelectorAll('.book-card, .video-card, .resource-card, .tip-card');
    cards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
}

// Track Downloads
function trackDownloads() {
    const downloadButtons = document.querySelectorAll('.btn-download, .btn-download-video, .download-link');
    
    downloadButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const resourceName = this.closest('.book-card, .video-card, .resource-card')
                ?.querySelector('h3')?.textContent || 'Unknown Resource';
            
            // Log download (you can send this to analytics)
            console.log('Download initiated:', resourceName);
            
            // Show success message
            showDownloadMessage(this);
            
            // Track with analytics if available
            if (typeof gtag !== 'undefined') {
                gtag('event', 'download', {
                    'event_category': 'Resources',
                    'event_label': resourceName
                });
            }
        });
    });
}

// Show Download Message
function showDownloadMessage(button) {
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-check"></i> Downloaded!';
    button.style.pointerEvents = 'none';
    
    setTimeout(() => {
        button.innerHTML = originalText;
        button.style.pointerEvents = 'auto';
    }, 2000);
}

// Loading States
function initLoadingStates() {
    const images = document.querySelectorAll('.book-cover img, .video-thumbnail img, .resource-image img');
    
    images.forEach(img => {
        if (!img.complete) {
            img.style.opacity = '0';
            
            img.addEventListener('load', function() {
                this.style.transition = 'opacity 0.3s ease';
                this.style.opacity = '1';
            });
        }
    });
}

// Scroll to top on page load
window.addEventListener('load', () => {
    if (window.location.hash) {
        setTimeout(() => {
            const target = document.querySelector(window.location.hash);
            if (target) {
                const offset = 100;
                const targetPosition = target.offsetTop - offset;
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        }, 100);
    }
});

// Add parallax effect to hero
window.addEventListener('scroll', () => {
    const scrolled = window.pageYOffset;
    const hero = document.querySelector('.culinary-hero');
    
    if (hero && scrolled < window.innerHeight) {
        const heroContent = hero.querySelector('.hero-content');
        if (heroContent) {
            heroContent.style.transform = `translateY(${scrolled * 0.5}px)`;
            heroContent.style.opacity = 1 - (scrolled / window.innerHeight);
        }
    }
});

// Navigation card active state on click
document.querySelectorAll('.nav-card').forEach(card => {
    card.addEventListener('click', function() {
        // Remove active from all cards
        document.querySelectorAll('.nav-card').forEach(c => c.classList.remove('active'));
        // Add active to clicked card
        this.classList.add('active');
        
        // Remove active after animation completes
        setTimeout(() => {
            this.classList.remove('active');
        }, 1000);
    });
});

// Add active state styles
const style = document.createElement('style');
style.textContent = `
    .nav-card.active {
        background: linear-gradient(135deg, #e76f51 0%, #f4a261 100%);
        color: white;
        border-color: #e76f51;
        transform: translateY(-5px);
    }
    
    .nav-card.active h3,
    .nav-card.active p {
        color: white;
    }
    
    .nav-card.active i {
        color: white;
    }
`;
document.head.appendChild(style);

// Video player enhancements
document.querySelectorAll('.video-thumbnail').forEach(thumbnail => {
    const video = thumbnail.querySelector('video');
    
    if (video) {
        // Pause video when out of view
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting && video) {
                    video.pause();
                }
            });
        }, { threshold: 0.5 });
        
        observer.observe(thumbnail);
    }
});

// Add hover effect to book cards
document.querySelectorAll('.book-card').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.zIndex = '10';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.zIndex = '1';
    });
});

// Lazy load images
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    imageObserver.unobserve(img);
                }
            }
        });
    });
    
    document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
}

// Print functionality
function printResource(resourceName) {
    window.print();
    console.log('Print initiated for:', resourceName);
}

// Share functionality
function shareResource(resourceName, resourceUrl) {
    if (navigator.share) {
        navigator.share({
            title: resourceName,
            text: `Check out this resource: ${resourceName}`,
            url: resourceUrl
        }).then(() => {
            console.log('Shared successfully');
        }).catch((error) => {
            console.log('Error sharing:', error);
        });
    } else {
        // Fallback: Copy to clipboard
        navigator.clipboard.writeText(window.location.href + resourceUrl).then(() => {
            alert('Link copied to clipboard!');
        });
    }
}

// Add keyboard navigation
document.addEventListener('keydown', (e) => {
    // Press 'D' to scroll to downloads
    if (e.key === 'd' || e.key === 'D') {
        const recipeBooksSection = document.getElementById('recipe-books');
        if (recipeBooksSection) {
            recipeBooksSection.scrollIntoView({ behavior: 'smooth' });
        }
    }
    
    // Press 'V' to scroll to videos
    if (e.key === 'v' || e.key === 'V') {
        const videoSection = document.getElementById('video-tutorials');
        if (videoSection) {
            videoSection.scrollIntoView({ behavior: 'smooth' });
        }
    }
});

console.log('🍳 Culinary Page Loaded Successfully!');
