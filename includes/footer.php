<!-- Enhanced Footer -->
<link rel="stylesheet" href="<?php echo url('assets/css/footer.css'); ?>">
<link rel="stylesheet" href="<?php echo url('assets/css/footer-bottom.css'); ?>">

<footer class="main-footer">
    <div class="footer-wave">
        <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25"></path>
            <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5"></path>
            <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z"></path>
        </svg>
    </div>
    
    <div class="footer-content">
        <div class="footer-container">
            <!-- About Section -->
            <div class="footer-section footer-about">
                <div class="footer-logo">
                    <img src="<?php echo url('assets/images/logo.png'); ?>" alt="FoodFusion">
                    <h3>FoodFusion</h3>
                </div>
                <p class="footer-description">
                    Your ultimate destination for culinary inspiration. Join thousands of food enthusiasts sharing recipes, tips, and cooking adventures.
                </p>
                <div class="footer-social">
                    <a href="#" class="social-link facebook" aria-label="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="social-link twitter" aria-label="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="social-link instagram" aria-label="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="social-link youtube" aria-label="YouTube">
                        <i class="fab fa-youtube"></i>
                    </a>
                    <a href="#" class="social-link pinterest" aria-label="Pinterest">
                        <i class="fab fa-pinterest-p"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="footer-section footer-links">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="<?php echo url('index.php'); ?>"><i class="fas fa-chevron-right"></i> Home</a></li>
                    <li><a href="<?php echo url('about.php'); ?>"><i class="fas fa-chevron-right"></i> About Us</a></li>
                    <li><a href="<?php echo url('recipes.php'); ?>"><i class="fas fa-chevron-right"></i> Recipes</a></li>
                    <li><a href="<?php echo url('community.php'); ?>"><i class="fas fa-chevron-right"></i> Community</a></li>
                    <li><a href="<?php echo url('contact.php'); ?>"><i class="fas fa-chevron-right"></i> Contact</a></li>
                </ul>
            </div>

            <!-- Resources -->
            <div class="footer-section footer-links">
                <h4>Resources</h4>
                <ul>
                    <li><a href="<?php echo url('culinary.php'); ?>"><i class="fas fa-chevron-right"></i> Culinary Resources</a></li>
                    <li><a href="<?php echo url('educational.php'); ?>"><i class="fas fa-chevron-right"></i> Educational Content</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Cooking Tips</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Video Tutorials</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Recipe Books</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="footer-section footer-contact">
                <h4>Get In Touch</h4>
                <ul class="contact-info">
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <span>123 Culinary Street<br>Food City, FC 12345</span>
                    </li>
                    <li>
                        <i class="fas fa-phone"></i>
                        <span>+1 (555) 123-4567</span>
                    </li>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <span>info@foodfusion.com</span>
                    </li>
                    <li>
                        <i class="fas fa-clock"></i>
                        <span>Mon - Fri: 9:00 AM - 6:00 PM</span>
                    </li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div class="footer-section footer-newsletter">
                <h4>Newsletter</h4>
                <p>Subscribe to get the latest recipes and cooking tips delivered to your inbox!</p>
                <form class="newsletter-form" id="newsletterForm">
                    <div class="input-group">
                        <input type="email" placeholder="Your email address" required>
                        <button type="submit">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
                <div class="newsletter-message" id="newsletterMessage"></div>
            </div>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="footer-bottom-content">
            <p class="copyright">
                &copy; 2025 <strong>FoodFusion</strong>. All rights reserved.
            </p>
            <div class="footer-bottom-links">
                <a href="#">Privacy Policy</a>
                <span class="separator">|</span>
                <a href="#">Terms of Service</a>
                <span class="separator">|</span>
                <a href="#">Cookie Policy</a>
            </div>
        </div>
    </div>

    <!-- Back to Top Button -->
    <button class="back-to-top" id="backToTop" aria-label="Back to top">
        <i class="fas fa-arrow-up"></i>
    </button>
</footer>

<script src="<?php echo url('assets/js/footer.js'); ?>"></script>
<script src="<?php echo url('assets/js/script.js'); ?>"></script>
<script src="<?php echo url('assets/js/main.js'); ?>"></script>
</body>
</html>
