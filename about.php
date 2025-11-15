<?php 
/**
 * About Page - FoodFusion
 * A modern, visually appealing showcase of our story, mission, and team
 */
require_once('includes/paths.php');
include('includes/header.php'); 
?>

<!-- About Page Specific CSS -->
<link rel="stylesheet" href="<?php echo url('assets/css/about.css'); ?>">

<!-- Hero Section with Parallax Effect -->
<!-- <section class="about-hero">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <span class="hero-badge">Est. 2020</span>
        <h1 class="hero-title">
            <span class="title-line">Bringing People</span>
            <span class="title-line">Together Through</span>
            <span class="title-line highlight">Food</span>
        </h1>
        <p class="hero-subtitle">A global community celebrating culinary diversity and innovation</p>
        <div class="hero-scroll">
            <span>Scroll to explore</span>
            <i class="fas fa-arrow-down"></i>
        </div>
    </div>
</section> -->

<!-- Main Content -->
<main class="about-main">
    
    <!-- Story Section -->
    <section class="story-section">
        <div class="container">
            <div class="story-content">
                <div class="story-text">
                    <span class="section-label">Our Story</span>
                    <h2>From Humble Beginnings to Global Community</h2>
                    <p class="lead">
                        In 2020, during a time when the world needed connection more than ever, 
                        FoodFusion was born from a simple kitchen and a powerful vision.
                    </p>
                    <p>
                        What started as a small blog sharing cherished family recipes has evolved into 
                        a thriving global community of over 50,000 food enthusiasts. We've created a 
                        space where culinary traditions meet modern innovation, where home cooks become 
                        confident chefs, and where every meal tells a story.
                    </p>
                    <p>
                        Today, FoodFusion represents more than recipes—it's a movement celebrating the 
                        universal language of food that transcends borders, cultures, and generations.
                    </p>
                </div>
                <div class="story-image">
                    <img src="<?php echo url('assets/images/about-hero.jpg'); ?>" alt="FoodFusion Community">
                    <div class="image-badge">
                        <span class="badge-number">50K+</span>
                        <span class="badge-text">Community Members</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
<!--    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="stat-number" data-target="50000">0</h3>
                    <p class="stat-label">Active Members</p>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <h3 class="stat-number" data-target="10000">0</h3>
                    <p class="stat-label">Recipes Shared</p>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-globe-americas"></i>
                    </div>
                    <h3 class="stat-number" data-target="100">0</h3>
                    <p class="stat-label">Countries</p>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <h3 class="stat-number" data-target="50">0</h3>
                    <p class="stat-label">Awards Won</p>
                </div>
            </div>
        </div>
    </section> -->

    <!-- Mission & Values Section -->
    <section class="mission-section">
        <div class="container">
            <div class="section-header">
                <span class="section-label">Our Mission</span>
                <h2>Inspiring Culinary Creativity Worldwide</h2>
                <p class="section-description">
                    We believe food is more than sustenance—it's a universal language that brings 
                    people together. Our mission is to make cooking accessible, enjoyable, and 
                    meaningful for everyone.
                </p>
            </div>

            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-globe"></i>
                    </div>
                    <h3>Celebrate Diversity</h3>
                    <p>Showcasing culinary traditions from every corner of the world</p>
                </div>

                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3>Empower Learning</h3>
                    <p>Making cooking techniques accessible to all skill levels</p>
                </div>

                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Build Community</h3>
                    <p>Creating meaningful connections through shared culinary experiences</p>
                </div>

                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h3>Promote Sustainability</h3>
                    <p>Advocating for eco-friendly and responsible cooking practices</p>
                </div>

                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h3>Encourage Innovation</h3>
                    <p>Blending traditional wisdom with modern culinary techniques</p>
                </div>

                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-smile"></i>
                    </div>
                    <h3>Spread Joy</h3>
                    <p>Sharing the happiness that comes from cooking and eating together</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team-section">
        <div class="container">
            <div class="section-header">
                <span class="section-label">Meet The Team</span>
                <h2>The Passionate Minds Behind FoodFusion</h2>
                <p class="section-description">
                    Our diverse team brings together culinary expertise, nutritional science, 
                    and a shared passion for making cooking accessible to everyone.
                </p>
            </div>

            <div class="team-grid">
                <!-- Team Member 1 -->
                <div class="team-card">
                    <div class="team-image">
                        <img src="<?php echo url('assets/images/team/alinafe.jpg'); ?>" alt="Alinafe Chinyama">
                        <div class="team-overlay">
                            <div class="team-social">
                                <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
                                <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                                <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="team-info">
                        <span class="team-role">Founder & Executive Chef</span>
                        <h3 class="team-name">Alinafe Chinyama</h3>
                        <p class="team-bio">
                            Visionary leader passionate about bringing people together through food. 
                            Her innovative approach has inspired thousands of home cooks worldwide.
                        </p>
                        <div class="team-skills">
                            <span>Culinary Innovation</span>
                            <span>Leadership</span>
                            <span>Recipe Development</span>
                        </div>
                    </div>
                </div>

                <!-- Team Member 2 -->
                <div class="team-card">
                    <div class="team-image">
                        <img src="<?php echo url('assets/images/team/horace.jpg'); ?>" alt="Horace Chipembere">
                        <div class="team-overlay">
                            <div class="team-social">
                                <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
                                <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                                <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="team-info">
                        <span class="team-role">Head of Recipe Development</span>
                        <h3 class="team-name">Horace Chipembere</h3>
                        <p class="team-bio">
                            Multicultural culinary expert specializing in adapting traditional recipes 
                            for modern dietary needs and diverse palates.
                        </p>
                        <div class="team-skills">
                            <span>Recipe Innovation</span>
                            <span>Cultural Fusion</span>
                            <span>Food Science</span>
                        </div>
                    </div>
                </div>

                <!-- Team Member 3 -->
                <div class="team-card">
                    <div class="team-image">
                        <img src="<?php echo url('assets/images/team/felistus.jpg'); ?>" alt="Dr Felistus Phiri">
                        <div class="team-overlay">
                            <div class="team-social">
                                <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
                                <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                                <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="team-info">
                        <span class="team-role">Nutrition Specialist</span>
                        <h3 class="team-name">Dr Felistus Phiri</h3>
                        <p class="team-bio">
                            Ph.D. in Nutritional Sciences ensuring our recipes are both delicious 
                            and nutritionally balanced for optimal health.
                        </p>
                        <div class="team-skills">
                            <span>Nutritional Science</span>
                            <span>Health Optimization</span>
                            <span>Research</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <div class="container">
            <div class="section-header">
                <span class="section-label">Testimonials</span>
                <h2>What Our Community Says</h2>
            </div>

            <div class="testimonials-slider">
                <div class="testimonial-card">
                    <div class="testimonial-quote">
                        <i class="fas fa-quote-left"></i>
                    </div>
                    <p class="testimonial-text">
                        "FoodFusion has transformed my cooking journey. The community support and 
                        diverse recipes have made me a confident home chef!"
                    </p>
                    <div class="testimonial-author">
                        <img src="<?php echo url('assets/images/testimonials/user1.jpg'); ?>" alt="Sarah Johnson">
                        <div>
                            <h4>Sarah Johnson</h4>
                            <p>Home Chef</p>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="testimonial-quote">
                        <i class="fas fa-quote-left"></i>
                    </div>
                    <p class="testimonial-text">
                        "I love how FoodFusion brings together people from different cultures through 
                        food. It's more than just recipes—it's a community!"
                    </p>
                    <div class="testimonial-author">
                        <img src="<?php echo url('assets/images/testimonials/user2.jpg'); ?>" alt="Michael Chen">
                        <div>
                            <h4>Michael Chen</h4>
                            <p>Food Blogger</p>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="testimonial-quote">
                        <i class="fas fa-quote-left"></i>
                    </div>
                    <p class="testimonial-text">
                        "The educational resources and community support have helped me grow as a chef. 
                        Thank you, FoodFusion!"
                    </p>
                    <div class="testimonial-author">
                        <img src="<?php echo url('assets/images/testimonials/user3.jpg'); ?>" alt="Emma Rodriguez">
                        <div>
                            <h4>Emma Rodriguez</h4>
                            <p>Culinary Student</p>
                        </div>
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
                <p>Join thousands of food enthusiasts in our vibrant community</p>
                <div class="cta-buttons">
                    <a href="auth/register.php" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i>
                        Join FoodFusion
                    </a>
                    <a href="recipes.php" class="btn btn-secondary">
                        <i class="fas fa-book-open"></i>
                        Explore Recipes
                    </a>
                </div>
            </div>
        </div>
    </section>
    
</main>

<!-- Counter Animation Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animated Counter
    const counters = document.querySelectorAll('.stat-number');
    const speed = 200;

    const animateCounter = (counter) => {
        const target = +counter.getAttribute('data-target');
        const increment = target / speed;
        let count = 0;

        const updateCount = () => {
            count += increment;
            if (count < target) {
                counter.innerText = Math.ceil(count).toLocaleString();
                setTimeout(updateCount, 10);
            } else {
                counter.innerText = target.toLocaleString() + '+';
            }
        };
        updateCount();
    };

    // Intersection Observer for counter animation
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                animateCounter(counter);
                observer.unobserve(counter);
            }
        });
    }, { threshold: 0.5 });

    counters.forEach(counter => observer.observe(counter));
});
</script>

<?php include('includes/footer.php'); ?>
