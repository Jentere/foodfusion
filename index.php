<?php
// Enable error reporting for debugging (disable in production)
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// Check if setup is required before any output
if (!file_exists(__DIR__ . '/setup.lock')) {
    header('Location: setup.php');
    exit('Setup required. Redirecting to setup page...');
}

// Check if config file exists
if (!file_exists(__DIR__ . '/includes/config.php')) {
    header('Location: setup.php');
    exit('Configuration file not found. Please run setup first.');
}

require_once('includes/paths.php');
include('includes/header.php'); 
include('includes/db.php');
?>

<!-- Link to Homepage Specific CSS -->
<link rel="stylesheet" href="<?php echo url('assets/css/homepage.css'); ?>">
<link rel="stylesheet" href="<?php echo url('assets/css/recipe-preview-popup.css'); ?>">

<!-- Base Path for JavaScript -->
<script>
    window.BASE_PATH = '<?php echo BASE_PATH; ?>';
    window.SITE_URL = '<?php echo SITE_URL; ?>';
</script>

<!-- Hero/Welcome Section -->
<section class="hero-section">
    <div class="hero-background">
        <div class="hero-overlay"></div>
        <div class="hero-particles">
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
        </div>
    </div>
    
    <div class="hero-container">
        <div class="hero-content">
            <div class="hero-badge animate-fade-in">
                <i class="fas fa-utensils"></i>
                <span>Welcome to FoodFusion</span>
            </div>
            
            <h1 class="hero-title animate-slide-up">
                Where Home Cooking Becomes a 
                <span class="gradient-text">Flavorful Adventure</span>
            </h1>
            
            <p class="hero-description animate-slide-up delay-1">
                Join a vibrant community of food lovers and bring your creativity to the kitchen. 
                Discover recipes, share your culinary masterpieces, and connect with passionate cooks worldwide.
            </p>
            
            <?php if (!isset($_SESSION['user_id'])): ?>
            <div class="hero-actions animate-slide-up delay-2">
                <button class="btn-primary join-us-btn" id="heroJoinBtn">
                    <span>Join Us</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
                <button class="btn-secondary explore-btn" onclick="document.querySelector('.news-feed-section').scrollIntoView({behavior: 'smooth'})">
                    <i class="fas fa-compass"></i>
                    <span>Explore Recipes</span>
                </button>
            </div>
            <?php else: ?>
            <div class="hero-actions animate-slide-up delay-2">
                <a href="<?php echo url('recipes.php'); ?>" class="btn-primary">
                    <span>Browse Recipes</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
                <a href="<?php echo url('community.php'); ?>" class="btn-secondary">
                    <i class="fas fa-users"></i>
                    <span>Join Community</span>
                </a>
            </div>
            <?php endif; ?>
            
            <!-- Mission Statement -->
            <div class="mission-box animate-slide-up delay-3">
                <div class="mission-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <div class="mission-content">
                    <h3>Our Mission</h3>
                    <p>To inspire and empower home cooks by providing a platform where culinary creativity meets community, making cooking accessible, enjoyable, and rewarding for everyone.</p>
                </div>
            </div>

            <!-- Stats -->
            <div class="hero-stats animate-fade-in delay-4">
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-book-open"></i></div>
                    <div class="stat-content">
                        <h4 class="counter" data-target="10000">0</h4>
                        <p>Recipes</p>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <div class="stat-content">
                        <h4 class="counter" data-target="50000">0</h4>
                        <p>Members</p>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-globe"></i></div>
                    <div class="stat-content">
                        <h4 class="counter" data-target="100">0</h4>
                        <p>Countries</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scroll Indicator -->
    <div class="scroll-indicator">
        <div class="mouse">
            <div class="wheel"></div>
        </div>
        <p>Scroll to explore</p>
    </div>
</section>

<!-- Featured Recipes / News Feed -->
<section class="news-feed-section">
    <div class="container">
        <div class="section-header">
            <div class="section-badge">
                <i class="fas fa-fire"></i>
                <span>Trending Now</span>
            </div>
            <h2 class="section-title">Featured Recipes & Culinary Trends</h2>
            <p class="section-description">Discover the most popular recipes loved by our community</p>
        </div>

        <div class="recipe-grid">
            <?php
            $recipes = [
                ['img' => 'recipe1.jpg', 'title' => 'Spicy Chicken Ramen', 'desc' => 'Hot, rich, and full of flavor. A FoodFusion favorite!', 'rating' => 4.8, 'count' => 120, 'time' => '30 min', 'difficulty' => 'Medium'],
                ['img' => 'recipe2.jpg', 'title' => 'Vegan Creamy Pasta', 'desc' => 'A healthy twist to your favorite creamy classic.', 'rating' => 4.5, 'count' => 85, 'time' => '25 min', 'difficulty' => 'Easy'],
                ['img' => 'pancakes.jpg', 'title' => 'Fluffy Pancakes', 'desc' => 'Perfect for breakfast or brunch.', 'rating' => 4.9, 'count' => 95, 'time' => '15 min', 'difficulty' => 'Easy'],
                ['img' => 'cookies.jpg', 'title' => 'Chocolate Chip Cookies', 'desc' => 'Classic cookies with gooey chocolate chips.', 'rating' => 4.7, 'count' => 150, 'time' => '20 min', 'difficulty' => 'Easy'],
                ['img' => 'mac_and_cheese.jpg', 'title' => 'Mac and Cheese', 'desc' => 'Rich and creamy comfort food.', 'rating' => 4.6, 'count' => 110, 'time' => '35 min', 'difficulty' => 'Medium'],
                ['img' => 'spaghetti.jpg', 'title' => 'Spaghetti Bolognese', 'desc' => 'Hearty Italian classic.', 'rating' => 4.9, 'count' => 200, 'time' => '45 min', 'difficulty' => 'Medium'],
                ['img' => 'nsima.jpg', 'title' => 'Traditional Nsima', 'desc' => 'Authentic Malawian dish.', 'rating' => 4.4, 'count' => 75, 'time' => '40 min', 'difficulty' => 'Medium'],
                ['img' => 'rice.jpg', 'title' => 'Perfect Rice', 'desc' => 'Simple and versatile staple.', 'rating' => 4.8, 'count' => 180, 'time' => '20 min', 'difficulty' => 'Easy'],
            ];

            foreach ($recipes as $index => $recipe):
            ?>
            <div class="recipe-card" style="--card-index: <?php echo $index; ?>">
                <div class="recipe-image">
                    <img src="<?php echo url('assets/images/' . $recipe['img']); ?>" alt="<?php echo $recipe['title']; ?>">
                    <div class="recipe-overlay">
                        <button class="btn-view">
                            <i class="fas fa-eye"></i>
                            View Recipe
                        </button>
                    </div>
                    <div class="recipe-badge"><?php echo $recipe['difficulty']; ?></div>
                </div>
                <div class="recipe-content">
                    <h3 class="recipe-title"><?php echo $recipe['title']; ?></h3>
                    <p class="recipe-description"><?php echo $recipe['desc']; ?></p>
                    
                    <div class="recipe-meta">
                        <div class="meta-item">
                            <i class="fas fa-clock"></i>
                            <span><?php echo $recipe['time']; ?></span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-star"></i>
                            <span><?php echo $recipe['rating']; ?></span>
                        </div>
                    </div>

                    <div class="recipe-footer">
                        <div class="rating-stars">
                            <?php
                            $fullStars = floor($recipe['rating']);
                            $hasHalfStar = ($recipe['rating'] - $fullStars) >= 0.5;
                            
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $fullStars) {
                                    echo '<i class="fas fa-star"></i>';
                                } elseif ($i == $fullStars + 1 && $hasHalfStar) {
                                    echo '<i class="fas fa-star-half-alt"></i>';
                                } else {
                                    echo '<i class="far fa-star"></i>';
                                }
                            }
                            ?>
                        </div>
                        <span class="rating-count">(<?php echo $recipe['count']; ?>)</span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="section-cta">
            <a href="<?php echo url('recipes.php'); ?>" class="btn-outline">
                <span>View All Recipes</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- Events Carousel -->
<section class="events-section">
    <div class="container">
        <div class="section-header">
            <div class="section-badge">
                <i class="fas fa-calendar-alt"></i>
                <span>What's Coming</span>
            </div>
            <h2 class="section-title">Upcoming Cooking Events</h2>
            <p class="section-description">Join our exciting culinary events and workshops</p>
        </div>

        <div class="events-carousel-wrapper">
            <div class="events-carousel" id="eventsCarousel">
                <div class="event-slide active">
                    <div class="event-card">
                        <div class="event-icon">
                            <i class="fas fa-birthday-cake"></i>
                        </div>
                        <div class="event-date">
                            <span class="day">01</span>
                            <span class="month">May</span>
                        </div>
                        <h3>Virtual Bake-Off Challenge</h3>
                        <p>Join our online baking challenge and win amazing gift cards! Show off your baking skills and compete with bakers worldwide.</p>
                        <div class="event-meta">
                            <span><i class="fas fa-clock"></i> 2:00 PM EST</span>
                            <span><i class="fas fa-users"></i> 500+ Registered</span>
                        </div>
                        <button class="btn-event">Register Now</button>
                    </div>
                </div>

                <div class="event-slide">
                    <div class="event-card">
                        <div class="event-icon">
                            <i class="fas fa-fire"></i>
                        </div>
                        <div class="event-date">
                            <span class="day">08</span>
                            <span class="month">May</span>
                        </div>
                        <h3>Live Grilling Masterclass</h3>
                        <p>Learn grilling secrets from top chefs. Free entry! Master the art of perfect grilling techniques and flavor combinations.</p>
                        <div class="event-meta">
                            <span><i class="fas fa-clock"></i> 6:00 PM EST</span>
                            <span><i class="fas fa-users"></i> 300+ Registered</span>
                        </div>
                        <button class="btn-event">Register Now</button>
                    </div>
                </div>

                <div class="event-slide">
                    <div class="event-card">
                        <div class="event-icon">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <div class="event-date">
                            <span class="day">15</span>
                            <span class="month">May</span>
                        </div>
                        <h3>Farm-to-Table Workshop</h3>
                        <p>Discover the journey of food from farm to table. Learn about sustainable cooking and fresh ingredient selection.</p>
                        <div class="event-meta">
                            <span><i class="fas fa-clock"></i> 10:00 AM EST</span>
                            <span><i class="fas fa-users"></i> 200+ Registered</span>
                        </div>
                        <button class="btn-event">Register Now</button>
                    </div>
                </div>
            </div>

            <div class="carousel-controls">
                <button class="carousel-btn prev" id="prevSlide">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <div class="carousel-dots" id="carouselDots"></div>
                <button class="carousel-btn next" id="nextSlide">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Join Us Popup Modal -->
<?php if (!isset($_SESSION['user_id'])): ?>
<link rel="stylesheet" href="<?php echo url('assets/css/register-popup.css'); ?>">

<div class="join-modal" id="joinModal">
    <div class="join-backdrop"></div>
    <div class="join-container">
        <button class="join-close" id="closeJoinModal">
            <i class="fas fa-times"></i>
        </button>
        
        <!-- Left Sidebar -->
        <div class="join-visual">
            <div class="join-visual-content">
                <div class="join-logo">
                    <img src="<?php echo url('assets/images/logo.png'); ?>" alt="FoodFusion">
                </div>
                <h2>Join FoodFusion</h2>
                <p>Start your culinary journey today</p>
                
                <!-- Password Requirements -->
                <div class="password-requirements-popup">
                    <p class="requirements-title">Password must contain:</p>
                    <ul class="requirements-list-popup">
                        <li id="popup-req-length"><i class="fas fa-circle"></i> At least 8 characters</li>
                        <li id="popup-req-uppercase"><i class="fas fa-circle"></i> One uppercase letter</li>
                        <li id="popup-req-lowercase"><i class="fas fa-circle"></i> One lowercase letter</li>
                        <li id="popup-req-number"><i class="fas fa-circle"></i> One number</li>
                        <li id="popup-req-special"><i class="fas fa-circle"></i> One special character</li>
                    </ul>
                </div>
                
                <!-- Social Login -->
                <div class="form-divider">
                    <span>or sign up with</span>
                </div>
                
                <div class="social-login-popup">
                    <button type="button" class="btn-google-signup" id="googleSignupBtn">
                        <svg width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.717v2.258h2.908c1.702-1.567 2.684-3.874 2.684-6.615z" fill="#4285F4"/>
                            <path d="M9.003 18c2.43 0 4.467-.806 5.956-2.18L12.05 13.56c-.806.54-1.836.86-3.047.86-2.344 0-4.328-1.584-5.036-3.711H.96v2.332C2.44 15.983 5.485 18 9.003 18z" fill="#34A853"/>
                            <path d="M3.964 10.712c-.18-.54-.282-1.117-.282-1.71 0-.593.102-1.17.282-1.71V4.96H.957C.347 6.175 0 7.55 0 9.002c0 1.452.348 2.827.957 4.042l3.007-2.332z" fill="#FBBC05"/>
                            <path d="M9.003 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.464.891 11.426 0 9.003 0 5.485 0 2.44 2.017.96 4.958L3.967 7.29c.708-2.127 2.692-3.71 5.036-3.71z" fill="#EA4335"/>
                        </svg>
                        <span>Google</span>
                    </button>
                    <button type="button" class="btn-facebook-signup">
                        <i class="fab fa-facebook-f"></i>
                        <span>Facebook</span>
                    </button>
                </div>
                
                <!-- Footer -->
                <div class="join-form-footer">
                    <p>Have an account? <a href="<?php echo url('auth/login.php'); ?>">Sign in</a></p>
                </div>
            </div>
        </div>

        <!-- Right Side - Form -->
        <div class="join-form-section">
            <div class="join-form-header">
                <h3>Create Account</h3>
            </div>

            <div class="join-message" id="joinMessage"></div>

            <form id="joinForm" class="join-form">
                <div class="form-group-join">
                    <label for="joinFirstName">
                        <i class="fas fa-user"></i>First Name
                    </label>
                    <input 
                        type="text" 
                        id="joinFirstName" 
                        name="first_name" 
                        placeholder="John" 
                        required
                        autocomplete="given-name"
                        maxlength="50">
                </div>
                
                <div class="form-group-join">
                    <label for="joinLastName">
                        <i class="fas fa-user"></i>Last Name
                    </label>
                    <input 
                        type="text" 
                        id="joinLastName" 
                        name="last_name" 
                        placeholder="Doe" 
                        required
                        autocomplete="family-name"
                        maxlength="50">
                </div>

                <div class="form-group-join">
                    <label for="joinEmail">
                        <i class="fas fa-envelope"></i>Email
                    </label>
                    <input 
                        type="email" 
                        id="joinEmail" 
                        name="email" 
                        placeholder="john@example.com" 
                        required
                        autocomplete="email"
                        maxlength="100">
                </div>

                <div class="form-group-join">
                    <label for="joinPassword">
                        <i class="fas fa-lock"></i>Password
                    </label>
                    <div class="password-wrapper">
                        <input 
                            type="password" 
                            id="joinPassword" 
                            name="password" 
                            placeholder="Create a strong password" 
                            required
                            autocomplete="new-password">
                        <button type="button" class="toggle-password-join" onclick="toggleJoinPassword()">
                            <i class="fas fa-eye" id="joinToggleIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group-join">
                    <label for="joinConfirmPassword">
                        <i class="fas fa-lock"></i>Confirm Password
                    </label>
                    <input 
                        type="password" 
                        id="joinConfirmPassword" 
                        name="confirm_password" 
                        placeholder="Re-enter your password" 
                        required
                        autocomplete="new-password">
                </div>

                <div class="checkbox-group">
                    <label class="checkbox-label-join">
                        <input type="checkbox" name="terms" required>
                        <span class="checkbox-text-join">I agree to <a href="#">Terms</a> & <a href="#">Privacy</a></span>
                    </label>
                </div>

                <button type="submit" class="btn-join-submit">
                    <i class="fas fa-user-plus"></i>
                    <span>Create Account</span>
                </button>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Cookie Consent -->
<div class="cookie-consent" id="cookieConsent">
    <div class="cookie-content">
        <div class="cookie-icon">
            <i class="fas fa-cookie-bite"></i>
        </div>
        <div class="cookie-text">
            <h4>We Value Your Privacy</h4>
            <p>We use cookies to enhance your browsing experience and analyze our traffic. By clicking "Accept All", you consent to our use of cookies. <a href="#">Learn more</a></p>
        </div>
        <div class="cookie-actions">
            <button class="btn-cookie-accept" id="acceptCookies">
                <i class="fas fa-check"></i>
                Accept All
            </button>
            <button class="btn-cookie-decline" id="declineCookies">
                Decline
            </button>
        </div>
    </div>
</div>

<!-- Homepage Specific JavaScript -->
<script src="<?php echo url('assets/js/homepage.js'); ?>?v=<?php echo time(); ?>"></script>
<script src="<?php echo url('assets/js/recipe-preview.js'); ?>?v=<?php echo time(); ?>"></script>

<?php include('includes/footer.php'); ?>
