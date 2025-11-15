<?php 
require_once('includes/paths.php');
include('includes/header.php');
include('includes/video-player.php');
?>

<!-- Hero Section -->
<section class="culinary-hero-new">
    <div class="hero-bg-pattern"></div>
    <div class="hero-container">
        <div class="hero-left">
            <span class="hero-label">
                <i class="fas fa-fire"></i>
                Culinary Resources Hub
            </span>
            <h1 class="hero-heading">
                Your Gateway to
                <span class="hero-highlight">Culinary Excellence</span>
            </h1>
            <p class="hero-text">
                Discover a world of flavors with our extensive collection of recipe books, 
                step-by-step video tutorials, and expert cooking guides. From beginner basics 
                to advanced techniques, we've got everything you need to elevate your cooking.
            </p>
            <div class="hero-features">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div class="feature-info">
                        <h4>15+ Recipe Books</h4>
                        <p>Downloadable PDFs</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-play-circle"></i>
                    </div>
                    <div class="feature-info">
                        <h4>50+ Video Guides</h4>
                        <p>HD Quality Tutorials</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <div class="feature-info">
                        <h4>1000+ Recipes</h4>
                        <p>From Around the World</p>
                    </div>
                </div>
            </div>
            <div class="hero-actions">
                <a href="#recipe-books" class="btn-hero-primary">
                    <i class="fas fa-download"></i>
                    Browse Resources
                </a>
                <a href="#video-tutorials" class="btn-hero-secondary">
                    <i class="fas fa-play"></i>
                    Watch Tutorials
                </a>
            </div>
        </div>
        <div class="hero-right">
            <div class="hero-image-wrapper">
                <div class="hero-image-card">
                    <img src="<?php echo url('assets/images/recipe1.jpg'); ?>" alt="Cooking" class="hero-img">
                    <div class="hero-badge-float badge-1">
                        <i class="fas fa-star"></i>
                        <span>Premium Content</span>
                    </div>
                </div>
                <div class="hero-stats-card">
                    <div class="stat-item-new">
                        <i class="fas fa-users"></i>
                        <div>
                            <strong>10K+</strong>
                            <span>Active Users</span>
                        </div>
                    </div>
                    <div class="stat-item-new">
                        <i class="fas fa-heart"></i>
                        <div>
                            <strong>50K+</strong>
                            <span>Downloads</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Navigation -->
<section class="quick-nav">
    <div class="container">
        <div class="nav-cards">
            <a href="#recipe-books" class="nav-card">
                <i class="fas fa-book-open"></i>
                <h3>Recipe Books</h3>
                <p>Downloadable PDFs</p>
            </a>
            <a href="#video-tutorials" class="nav-card">
                <i class="fas fa-play-circle"></i>
                <h3>Video Tutorials</h3>
                <p>Step-by-step guides</p>
            </a>
            <a href="#cooking-tips" class="nav-card">
                <i class="fas fa-lightbulb"></i>
                <h3>Cooking Tips</h3>
                <p>Expert advice</p>
            </a>
            <a href="#resources" class="nav-card">
                <i class="fas fa-download"></i>
                <h3>Resources</h3>
                <p>Additional materials</p>
            </a>
        </div>
    </div>
</section>

<!-- Recipe Books Section -->
<section id="recipe-books" class="recipe-books-section">
    <div class="container">
        <div class="section-header">
            <div class="section-badge">
                <i class="fas fa-book"></i>
                <span>Digital Library</span>
            </div>
            <h2 class="section-title">Recipe Books Collection</h2>
            <p class="section-description">Download our curated collection of recipe books and start cooking today</p>
        </div>

        <div class="books-grid">
            <div class="book-card">
                <div class="book-cover">
                    <img src="<?php echo url('assets/images/tip1.jpg'); ?>" alt="Easy Recipes for One or Two">
                    <div class="book-overlay">
                        <a href="<?php echo url('resources/easy-recipes-for-one-or-two.pdf'); ?>" download class="btn-download">
                            <i class="fas fa-download"></i>
                            Download
                        </a>
                    </div>
                    <div class="book-badge">PDF</div>
                </div>
                <div class="book-info">
                    <h3>Easy Recipes for One or Two</h3>
                    <p>Perfect for small households with delicious and simple recipes</p>
                    <div class="book-meta">
                        <span><i class="fas fa-file-pdf"></i> 2.5 MB</span>
                        <span><i class="fas fa-utensils"></i> 50+ Recipes</span>
                    </div>
                </div>
            </div>

            <div class="book-card">
                <div class="book-cover">
                    <img src="<?php echo url('assets/images/recipe2.jpg'); ?>" alt="25 Ingredients, 50 Meals">
                    <div class="book-overlay">
                        <a href="<?php echo url('resources/25-Ingredients-50-Meals-Print-Friendly-Final-1.pdf'); ?>" download class="btn-download">
                            <i class="fas fa-download"></i>
                            Download
                        </a>
                    </div>
                    <div class="book-badge">PDF</div>
                </div>
                <div class="book-info">
                    <h3>25 Ingredients, 50 Meals</h3>
                    <p>Create diverse meals with minimal ingredients</p>
                    <div class="book-meta">
                        <span><i class="fas fa-file-pdf"></i> 3.2 MB</span>
                        <span><i class="fas fa-utensils"></i> 50 Recipes</span>
                    </div>
                </div>
            </div>

            <div class="book-card">
                <div class="book-cover">
                    <img src="<?php echo url('assets/images/recipe6.jpg'); ?>" alt="International Recipes">
                    <div class="book-overlay">
                        <a href="<?php echo url('resources/nobilia-international-recipes-EN.pdf'); ?>" download class="btn-download">
                            <i class="fas fa-download"></i>
                            Download
                        </a>
                    </div>
                    <div class="book-badge">PDF</div>
                </div>
                <div class="book-info">
                    <h3>International Recipes</h3>
                    <p>Explore global cuisines with authentic recipes</p>
                    <div class="book-meta">
                        <span><i class="fas fa-file-pdf"></i> 4.1 MB</span>
                        <span><i class="fas fa-globe"></i> 30+ Countries</span>
                    </div>
                </div>
            </div>

            <div class="book-card">
                <div class="book-cover">
                    <img src="<?php echo url('assets/images/recipe4.jpg'); ?>" alt="Recipe Book 1">
                    <div class="book-overlay">
                        <a href="<?php echo url('resources/recipe-book-1.zp210082.pdf'); ?>" download class="btn-download">
                            <i class="fas fa-download"></i>
                            Download
                        </a>
                    </div>
                    <div class="book-badge">PDF</div>
                </div>
                <div class="book-info">
                    <h3>Classic Recipe Collection</h3>
                    <p>Traditional and modern recipes combined</p>
                    <div class="book-meta">
                        <span><i class="fas fa-file-pdf"></i> 5.8 MB</span>
                        <span><i class="fas fa-utensils"></i> 75+ Recipes</span>
                    </div>
                </div>
            </div>

            <div class="book-card">
                <div class="book-cover">
                    <img src="<?php echo url('assets/images/recipe5.jpg'); ?>" alt="Recipe Book 2">
                    <div class="book-overlay">
                        <a href="<?php echo url('resources/Recipe-book_2.pdf'); ?>" download class="btn-download">
                            <i class="fas fa-download"></i>
                            Download
                        </a>
                    </div>
                    <div class="book-badge">PDF</div>
                </div>
                <div class="book-info">
                    <h3>Modern Cooking Guide</h3>
                    <p>Contemporary recipes for today's kitchen</p>
                    <div class="book-meta">
                        <span><i class="fas fa-file-pdf"></i> 4.5 MB</span>
                        <span><i class="fas fa-utensils"></i> 60+ Recipes</span>
                    </div>
                </div>
            </div>

            <div class="book-card">
                <div class="book-cover">
                    <img src="<?php echo url('assets/images/recipe7.jpg'); ?>" alt="Complete Cookbook">
                    <div class="book-overlay">
                        <a href="<?php echo url('resources/cookbook.pdf'); ?>" download class="btn-download">
                            <i class="fas fa-download"></i>
                            Download
                        </a>
                    </div>
                    <div class="book-badge featured">Premium</div>
                </div>
                <div class="book-info">
                    <h3>Complete Cookbook</h3>
                    <p>Comprehensive collection of recipes and techniques</p>
                    <div class="book-meta">
                        <span><i class="fas fa-file-pdf"></i> 12.3 MB</span>
                        <span><i class="fas fa-utensils"></i> 200+ Recipes</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Video Tutorials Section -->
<section id="video-tutorials" class="video-section">
    <div class="container">
        <div class="section-header">
            <div class="section-badge">
                <i class="fas fa-video"></i>
                <span>Learn by Watching</span>
            </div>
            <h2 class="section-title">Video Tutorials</h2>
            <p class="section-description">Master cooking techniques with our step-by-step video guides</p>
        </div>

        <div class="videos-grid">
            <?php
            $videos = [
                [
                    'video_path' => 'resources/video2.mp4',
                    'thumbnail_path' => 'assets/images/video1.jpg',
                    'title' => 'Cooking Basics',
                    'description' => 'Essential cooking techniques for beginners',
                    'duration' => 300
                ],
                [
                    'video_path' => 'resources/HOW_TO_MAKE_MEAT_PIE.mp4',
                    'thumbnail_path' => 'assets/images/video2.jpg',
                    'title' => 'How to Make Meat Pie',
                    'description' => 'Step-by-step guide to making perfect meat pies',
                    'duration' => 600
                ],
                [
                    'video_path' => 'resources/video3.mp4',
                    'thumbnail_path' => 'assets/images/video3.jpg',
                    'title' => 'Advanced Techniques',
                    'description' => 'Master professional cooking methods',
                    'duration' => 900
                ],
                [
                    'video_path' => 'resources/AIR FRYER CHICKEN TIKKA RESTAURANT STYLE CHICKEN TIKKA IN AIR FRYER.mp4',
                    'thumbnail_path' => 'assets/images/video4.jpg',
                    'title' => 'Air Fryer Chicken Tikka',
                    'description' => 'Restaurant style chicken tikka in air fryer',
                    'duration' => 450
                ],
                [
                    'video_path' => 'resources/video5.mp4',
                    'thumbnail_path' => 'assets/images/video5.jpg',
                    'title' => 'Recipe Techniques',
                    'description' => 'Learn professional recipe techniques',
                    'duration' => 720
                ],
                [
                    'video_path' => 'resources/video6.mp4',
                    'thumbnail_path' => 'assets/images/video6.jpg',
                    'title' => 'Cooking Masterclass',
                    'description' => 'Advanced cooking techniques and tips',
                    'duration' => 1200
                ]
            ];

            foreach ($videos as $index => $video):
            ?>
            <div class="video-card" style="--card-index: <?php echo $index; ?>">
                <div class="video-thumbnail">
                    <?php echo renderVideoPlayer($video); ?>
                    <div class="video-duration">
                        <i class="fas fa-clock"></i>
                        <?php echo gmdate("i:s", $video['duration']); ?>
                    </div>
                </div>
                <div class="video-info">
                    <h3><?php echo htmlspecialchars($video['title']); ?></h3>
                    <p><?php echo htmlspecialchars($video['description']); ?></p>
                    <div class="video-actions">
                        <a href="<?php echo url(htmlspecialchars($video['video_path'])); ?>" download class="btn-download-video">
                            <i class="fas fa-download"></i>
                            Download
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Cooking Tips Section -->
<section id="cooking-tips" class="tips-section">
    <div class="container">
        <div class="section-header">
            <div class="section-badge">
                <i class="fas fa-lightbulb"></i>
                <span>Expert Advice</span>
            </div>
            <h2 class="section-title">Cooking Tips & Tricks</h2>
            <p class="section-description">Elevate your cooking with these professional tips</p>
        </div>

        <div class="tips-grid">
            <div class="tip-card">
                <div class="tip-icon">
                    <i class="fas fa-thermometer-half"></i>
                </div>
                <h3>Temperature Control</h3>
                <p>Master the art of heat management for perfect cooking results every time</p>
            </div>
            <div class="tip-card">
                <div class="tip-icon">
                    <i class="fas fa-cut"></i>
                </div>
                <h3>Knife Skills</h3>
                <p>Learn proper cutting techniques to improve efficiency and safety in the kitchen</p>
            </div>
            <div class="tip-card">
                <div class="tip-icon">
                    <i class="fas fa-pepper-hot"></i>
                </div>
                <h3>Seasoning Secrets</h3>
                <p>Discover how to balance flavors and create memorable dishes</p>
            </div>
            <div class="tip-card">
                <div class="tip-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3>Timing Techniques</h3>
                <p>Perfect your timing to ensure all components are ready simultaneously</p>
            </div>
            <div class="tip-card">
                <div class="tip-icon">
                    <i class="fas fa-blender"></i>
                </div>
                <h3>Tool Mastery</h3>
                <p>Use kitchen tools effectively to enhance your cooking experience</p>
            </div>
            <div class="tip-card">
                <div class="tip-icon">
                    <i class="fas fa-leaf"></i>
                </div>
                <h3>Fresh Ingredients</h3>
                <p>Select and store ingredients properly for maximum flavor and nutrition</p>
            </div>
        </div>
    </div>
</section>

<!-- Additional Resources Section -->
<section id="resources" class="resources-section">
    <div class="container">
        <div class="section-header">
            <div class="section-badge">
                <i class="fas fa-folder-open"></i>
                <span>More Resources</span>
            </div>
            <h2 class="section-title">Additional Resources</h2>
            <p class="section-description">Expand your culinary knowledge with these extra materials</p>
        </div>

        <div class="resources-grid">
            <div class="resource-card">
                <div class="resource-image">
                    <img src="<?php echo url('assets/images/tip5.jpg'); ?>" alt="Complete Cookbook">
                    <div class="resource-overlay">
                        <a href="<?php echo url('resources/cookbook.pdf'); ?>" download class="btn-resource">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                </div>
                <div class="resource-content">
                    <h3>Complete Cookbook</h3>
                    <p>Comprehensive collection of recipes and techniques</p>
                    <a href="<?php echo url('resources/cookbook.pdf'); ?>" download class="download-link">
                        <i class="fas fa-file-pdf"></i>
                        Download PDF
                    </a>
                </div>
            </div>

            <div class="resource-card">
                <div class="resource-image">
                    <img src="<?php echo url('assets/images/tip7.jpg'); ?>" alt="Cooking for All">
                    <div class="resource-overlay">
                        <a href="<?php echo url('resources/Cooking-for-all-recipe-book1.pdf'); ?>" download class="btn-resource">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                </div>
                <div class="resource-content">
                    <h3>Cooking for All</h3>
                    <p>Inclusive recipes for every skill level</p>
                    <a href="<?php echo url('resources/Cooking-for-all-recipe-book1.pdf'); ?>" download class="download-link">
                        <i class="fas fa-file-pdf"></i>
                        Download PDF
                    </a>
                </div>
            </div>

            <div class="resource-card">
                <div class="resource-image">
                    <img src="<?php echo url('assets/images/tip6.jpg'); ?>" alt="Recipe Collection">
                    <div class="resource-overlay">
                        <a href="<?php echo url('resources/Recipe-Book.pdf'); ?>" download class="btn-resource">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                </div>
                <div class="resource-content">
                    <h3>Recipe Collection</h3>
                    <p>Diverse recipes for every occasion</p>
                    <a href="<?php echo url('resources/Recipe-Book.pdf'); ?>" download class="download-link">
                        <i class="fas fa-file-pdf"></i>
                        Download PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Ready to Start Your Culinary Journey?</h2>
            <p>Join our community and get access to exclusive recipes, tips, and cooking resources</p>
            <div class="cta-buttons">
                <?php if (!isset($_SESSION['user_id'])): ?>
                <button class="btn-cta-primary" onclick="window.modernNavigation.openModal('signup')">
                    <i class="fas fa-user-plus"></i>
                    Join Now
                </button>
                <?php else: ?>
                <a href="<?php echo url('recipes.php'); ?>" class="btn-cta-primary">
                    <i class="fas fa-book-open"></i>
                    Browse Recipes
                </a>
                <?php endif; ?>
                <a href="<?php echo url('community.php'); ?>" class="btn-cta-secondary">
                    <i class="fas fa-users"></i>
                    Join Community
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Culinary Specific JavaScript -->
<script src="<?php echo url('assets/js/culinary.js'); ?>"></script>

<?php include('includes/footer.php'); ?>
