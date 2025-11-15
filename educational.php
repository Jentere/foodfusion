<?php 
/**
 * Educational Resources Page - FoodFusion
 * Culinary learning materials, cookbooks, videos, and cooking tips
 */
require_once('includes/paths.php');
include('includes/header.php'); 
?>

<!-- Educational Page Specific CSS -->
<link rel="stylesheet" href="<?php echo url('assets/css/educational.css'); ?>">

<!-- Hero Section -->
<section class="educational-hero">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <span class="hero-badge">Learn & Grow</span>
        <h1>Educational Resources</h1>
        <p>Expand your culinary knowledge with our comprehensive collection of cookbooks, video tutorials, infographics, and expert cooking tips</p>
    </div>
</section>

<!-- Main Content -->
<main class="educational-main">
    
    <!-- Quick Stats -->
    <section class="quick-stats">
        <div class="container">
            <div class="stats-row">
                <div class="stat-box">
                    <i class="fas fa-book"></i>
                    <h3>9+</h3>
                    <p>Cookbooks</p>
                </div>
                <div class="stat-box">
                    <i class="fas fa-video"></i>
                    <h3>6+</h3>
                    <p>Video Tutorials</p>
                </div>
                <div class="stat-box">
                    <i class="fas fa-chart-line"></i>
                    <h3>7+</h3>
                    <p>Infographics</p>
                </div>
                <div class="stat-box">
                    <i class="fas fa-lightbulb"></i>
                    <h3>10+</h3>
                    <p>Cooking Tips</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Cookbooks Section -->
    <section class="resource-section cookbooks-section">
        <div class="container">
            <div class="section-header">
                <span class="section-label">Downloadable Cookbooks</span>
                <h2>Master the Art of Cooking</h2>
                <p>Download our curated collection of cookbooks covering various cuisines and skill levels</p>
            </div>

            <div class="resource-grid">
                <div class="resource-card">
                    <div class="resource-image">
                        <img src="<?php echo url('assets/images/resource.png'); ?>" alt="25 Ingredients 50 Meals">
                        <div class="resource-badge">PDF</div>
                    </div>
                    <div class="resource-content">
                        <h3>25 Ingredients, 50 Meals</h3>
                        <p>Learn to create 50 delicious meals using just 25 basic ingredients. Perfect for budget-conscious cooking.</p>
                        <a href="resources/25-Ingredients-50-Meals-Print-Friendly-Final-1.pdf" download class="download-btn">
                            <i class="fas fa-download"></i>
                            Download PDF
                        </a>
                    </div>
                </div>

                <div class="resource-card">
                    <div class="resource-image">
                        <img src="<?php echo url('assets/images/resource1.png'); ?>" alt="Complete Cookbook">
                        <div class="resource-badge">PDF</div>
                    </div>
                    <div class="resource-content">
                        <h3>Complete Cookbook</h3>
                        <p>A comprehensive guide to cooking essentials, techniques, and recipes for every occasion.</p>
                        <a href="resources/cookbook.pdf" download class="download-btn">
                            <i class="fas fa-download"></i>
                            Download PDF
                        </a>
                    </div>
                </div>

                <div class="resource-card">
                    <div class="resource-image">
                        <img src="<?php echo url('assets/images/resource2.png'); ?>" alt="Cooking for All">
                        <div class="resource-badge">PDF</div>
                    </div>
                    <div class="resource-content">
                        <h3>Cooking for All</h3>
                        <p>Inclusive recipes and techniques suitable for cooks of all skill levels and dietary needs.</p>
                        <a href="resources/Cooking-for-all-recipe-book1.pdf" download class="download-btn">
                            <i class="fas fa-download"></i>
                            Download PDF
                        </a>
                    </div>
                </div>

                <div class="resource-card">
                    <div class="resource-image">
                        <img src="<?php echo url('assets/images/resource3.webp'); ?>" alt="Easy Recipes for One or Two">
                        <div class="resource-badge">PDF</div>
                    </div>
                    <div class="resource-content">
                        <h3>Easy Recipes for One or Two</h3>
                        <p>Perfect portions and simple recipes designed for singles and couples.</p>
                        <a href="resources/easy-recipes-for-one-or-two.pdf" download class="download-btn">
                            <i class="fas fa-download"></i>
                            Download PDF
                        </a>
                    </div>
                </div>

                <div class="resource-card">
                    <div class="resource-image">
                        <img src="<?php echo url('assets/images/resource4.jpg'); ?>" alt="International Recipes">
                        <div class="resource-badge">PDF</div>
                    </div>
                    <div class="resource-content">
                        <h3>International Recipes</h3>
                        <p>Explore global cuisines with authentic recipes from around the world.</p>
                        <a href="resources/nobilia-international-recipes-EN.pdf" download class="download-btn">
                            <i class="fas fa-download"></i>
                            Download PDF
                        </a>
                    </div>
                </div>

                <div class="resource-card">
                    <div class="resource-image">
                        <img src="<?php echo url('assets/images/resource5.png'); ?>" alt="Recipe Book Collection">
                        <div class="resource-badge">PDF</div>
                    </div>
                    <div class="resource-content">
                        <h3>Recipe Book Collection</h3>
                        <p>A curated collection of tried-and-tested recipes from our community.</p>
                        <a href="resources/Recipe-Book.pdf" download class="download-btn">
                            <i class="fas fa-download"></i>
                            Download PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Video Tutorials Section -->
    <section class="resource-section videos-section">
        <div class="container">
            <div class="section-header">
                <span class="section-label">Video Tutorials</span>
                <h2>Learn by Watching</h2>
                <p>Step-by-step video guides to master essential cooking techniques</p>
            </div>

            <div class="video-grid">
                <div class="video-card" data-video="resources/video1.mp4">
                    <div class="video-thumbnail">
                        <img src="<?php echo url('assets/images/video1.jpg'); ?>" alt="Cooking Basics">
                        <div class="play-overlay">
                            <i class="fas fa-play"></i>
                        </div>
                        <span class="video-duration">15:30</span>
                    </div>
                    <div class="video-content">
                        <h3>Cooking Basics for Beginners</h3>
                        <p>Essential techniques every home cook should know</p>
                        <div class="video-actions">
                            <button class="play-btn" onclick="playVideo('resources/video1.mp4')">
                                <i class="fas fa-play"></i>
                                Watch Now
                            </button>
                            <a href="resources/video1.mp4" download class="download-btn-small">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="video-card" data-video="resources/video2.mp4">
                    <div class="video-thumbnail">
                        <img src="<?php echo url('assets/images/video2.jpg'); ?>" alt="Knife Skills">
                        <div class="play-overlay">
                            <i class="fas fa-play"></i>
                        </div>
                        <span class="video-duration">12:45</span>
                    </div>
                    <div class="video-content">
                        <h3>Professional Knife Skills</h3>
                        <p>Master cutting techniques like a professional chef</p>
                        <div class="video-actions">
                            <button class="play-btn" onclick="playVideo('resources/video2.mp4')">
                                <i class="fas fa-play"></i>
                                Watch Now
                            </button>
                            <a href="resources/video2.mp4" download class="download-btn-small">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="video-card" data-video="resources/video3.mp4">
                    <div class="video-thumbnail">
                        <img src="<?php echo url('assets/images/video3.jpg'); ?>" alt="Baking Fundamentals">
                        <div class="play-overlay">
                            <i class="fas fa-play"></i>
                        </div>
                        <span class="video-duration">20:15</span>
                    </div>
                    <div class="video-content">
                        <h3>Baking Fundamentals</h3>
                        <p>Learn the science and art of perfect baking</p>
                        <div class="video-actions">
                            <button class="play-btn" onclick="playVideo('resources/video3.mp4')">
                                <i class="fas fa-play"></i>
                                Watch Now
                            </button>
                            <a href="resources/video3.mp4" download class="download-btn-small">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="video-card" data-video="resources/video4.mp4">
                    <div class="video-thumbnail">
                        <img src="<?php echo url('assets/images/video4.jpg'); ?>" alt="Sauce Making">
                        <div class="play-overlay">
                            <i class="fas fa-play"></i>
                        </div>
                        <span class="video-duration">18:20</span>
                    </div>
                    <div class="video-content">
                        <h3>Classic Sauce Making</h3>
                        <p>Create restaurant-quality sauces at home</p>
                        <div class="video-actions">
                            <button class="play-btn" onclick="playVideo('resources/video4.mp4')">
                                <i class="fas fa-play"></i>
                                Watch Now
                            </button>
                            <a href="resources/video4.mp4" download class="download-btn-small">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="video-card" data-video="resources/video5.mp4">
                    <div class="video-thumbnail">
                        <img src="<?php echo url('assets/images/video5.jpg'); ?>" alt="Meat Preparation">
                        <div class="play-overlay">
                            <i class="fas fa-play"></i>
                        </div>
                        <span class="video-duration">16:40</span>
                    </div>
                    <div class="video-content">
                        <h3>Meat Preparation & Cooking</h3>
                        <p>Perfect techniques for cooking various meats</p>
                        <div class="video-actions">
                            <button class="play-btn" onclick="playVideo('resources/video5.mp4')">
                                <i class="fas fa-play"></i>
                                Watch Now
                            </button>
                            <a href="resources/video5.mp4" download class="download-btn-small">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="video-card" data-video="resources/video6.mp4">
                    <div class="video-thumbnail">
                        <img src="<?php echo url('assets/images/video6.jpg'); ?>" alt="Vegetable Techniques">
                        <div class="play-overlay">
                            <i class="fas fa-play"></i>
                        </div>
                        <span class="video-duration">14:55</span>
                    </div>
                    <div class="video-content">
                        <h3>Vegetable Cooking Techniques</h3>
                        <p>Bring out the best flavors in your vegetables</p>
                        <div class="video-actions">
                            <button class="play-btn" onclick="playVideo('resources/video6.mp4')">
                                <i class="fas fa-play"></i>
                                Watch Now
                            </button>
                            <a href="resources/video6.mp4" download class="download-btn-small">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Infographics Section -->
    <section class="resource-section infographics-section">
        <div class="container">
            <div class="section-header">
                <span class="section-label">Visual Guides</span>
                <h2>Cooking Infographics</h2>
                <p>Quick reference guides for essential cooking knowledge</p>
            </div>

            <div class="infographic-grid">
                <div class="infographic-card">
                    <img src="<?php echo url('assets/images/infographic.jpeg'); ?>" alt="Cooking Temperature Guide">
                    <div class="infographic-overlay">
                        <h3>Cooking Temperature Guide</h3>
                        <p>Safe internal temperatures for all foods</p>
                    </div>
                </div>

                <div class="infographic-card">
                    <img src="<?php echo url('assets/images/infographic1.jpeg'); ?>" alt="Knife Types">
                    <div class="infographic-overlay">
                        <h3>Essential Knife Types</h3>
                        <p>Know your kitchen knives and their uses</p>
                    </div>
                </div>

                <div class="infographic-card">
                    <img src="<?php echo url('assets/images/infographic2.jpeg'); ?>" alt="Spice Pairing">
                    <div class="infographic-overlay">
                        <h3>Spice Pairing Guide</h3>
                        <p>Perfect spice combinations for every dish</p>
                    </div>
                </div>

                <div class="infographic-card">
                    <img src="<?php echo url('assets/images/infographic3.jpeg'); ?>" alt="Cooking Methods">
                    <div class="infographic-overlay">
                        <h3>Cooking Methods Explained</h3>
                        <p>Understanding different cooking techniques</p>
                    </div>
                </div>

                <div class="infographic-card">
                    <img src="<?php echo url('assets/images/infographic4.jpeg'); ?>" alt="Meal Prep">
                    <div class="infographic-overlay">
                        <h3>Meal Prep Basics</h3>
                        <p>Efficient meal planning and preparation</p>
                    </div>
                </div>

                <div class="infographic-card">
                    <img src="<?php echo url('assets/images/infographic5.jpeg'); ?>" alt="Food Storage">
                    <div class="infographic-overlay">
                        <h3>Food Storage Guide</h3>
                        <p>Keep your ingredients fresh longer</p>
                    </div>
                </div>

                <div class="infographic-card">
                    <img src="<?php echo url('assets/images/infographic6.jpeg'); ?>" alt="Substitutions">
                    <div class="infographic-overlay">
                        <h3>Ingredient Substitutions</h3>
                        <p>Smart swaps for common ingredients</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Cooking Tips Section -->
    <section class="resource-section tips-section">
        <div class="container">
            <div class="section-header">
                <span class="section-label">Pro Tips</span>
                <h2>Expert Cooking Tips</h2>
                <p>Professional advice to elevate your cooking game</p>
            </div>

            <div class="tips-grid">
                <div class="tip-card">
                    <div class="tip-icon">
                        <i class="fas fa-fire"></i>
                    </div>
                    <h3>Temperature Control</h3>
                    <p>Master heat management for perfect results every time. Learn when to use high, medium, or low heat.</p>
                </div>

                <div class="tip-card">
                    <div class="tip-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3>Timing is Everything</h3>
                    <p>Understand cooking times and how to coordinate multiple dishes for seamless meal preparation.</p>
                </div>

                <div class="tip-card">
                    <div class="tip-icon">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                    <h3>Proper Measurements</h3>
                    <p>Accurate measuring techniques ensure consistent results, especially in baking.</p>
                </div>

                <div class="tip-card">
                    <div class="tip-icon">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h3>Fresh Ingredients</h3>
                    <p>Select and store ingredients properly to maximize flavor and nutritional value.</p>
                </div>

                <div class="tip-card">
                    <div class="tip-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <h3>Tool Selection</h3>
                    <p>Use the right tools for each task to make cooking easier and more efficient.</p>
                </div>

                <div class="tip-card">
                    <div class="tip-icon">
                        <i class="fas fa-pepper-hot"></i>
                    </div>
                    <h3>Seasoning Secrets</h3>
                    <p>Learn to layer flavors and season at the right moments for maximum impact.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Ready to Start Learning?</h2>
                <p>Join our community and get access to exclusive educational content</p>
                <div class="cta-buttons">
                    <a href="auth/register.php" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i>
                        Join FoodFusion
                    </a>
                    <a href="recipes.php" class="btn btn-secondary">
                        <i class="fas fa-book-open"></i>
                        Browse Recipes
                    </a>
                </div>
            </div>
        </div>
    </section>

</main>

<!-- Video Modal -->
<div id="videoModal" class="video-modal">
    <div class="video-modal-content">
        <button class="video-modal-close" onclick="closeVideo()">
            <i class="fas fa-times"></i>
        </button>
        <video id="modalVideo" controls>
            <source src="" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>
</div>

<!-- Video Player Script -->
<script>
function playVideo(videoSrc) {
    const modal = document.getElementById('videoModal');
    const video = document.getElementById('modalVideo');
    const source = video.querySelector('source');
    
    // Set video source
    source.src = videoSrc;
    
    // Determine video type
    if (videoSrc.endsWith('.webm')) {
        source.type = 'video/webm';
    } else {
        source.type = 'video/mp4';
    }
    
    // Load and play video
    video.load();
    modal.style.display = 'flex';
    
    // Play video after modal is shown
    setTimeout(() => {
        video.play();
    }, 100);
}

function closeVideo() {
    const modal = document.getElementById('videoModal');
    const video = document.getElementById('modalVideo');
    
    // Pause and reset video
    video.pause();
    video.currentTime = 0;
    
    // Hide modal
    modal.style.display = 'none';
}

// Close modal when clicking outside video
document.getElementById('videoModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeVideo();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeVideo();
    }
});

// Make video thumbnails clickable
document.querySelectorAll('.video-thumbnail').forEach(thumbnail => {
    thumbnail.addEventListener('click', function() {
        const videoCard = this.closest('.video-card');
        const videoSrc = videoCard.getAttribute('data-video');
        playVideo(videoSrc);
    });
});
</script>

<?php include('includes/footer.php'); ?>
