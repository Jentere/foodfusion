<?php
/**
 * Contact Page - FoodFusion
 * Get in touch with our team
 */
session_start();
require_once('includes/paths.php');
include('includes/header.php'); 
include('includes/db.php'); 

$success_message = '';
$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input (using modern approach instead of deprecated FILTER_SANITIZE_STRING)
    $name = isset($_POST['name']) ? trim(htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8')) : '';
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $subject = isset($_POST['subject']) ? trim(htmlspecialchars($_POST['subject'], ENT_QUOTES, 'UTF-8')) : '';
    $message = isset($_POST['message']) ? trim(htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8')) : '';
    $phone = isset($_POST['phone']) ? trim(htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8')) : '';
    $preferred_contact = isset($_POST['preferred_contact']) ? trim(htmlspecialchars($_POST['preferred_contact'], ENT_QUOTES, 'UTF-8')) : '';
    $newsletter = isset($_POST['newsletter']) ? 1 : 0;

    // Validate required fields
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error_message = "Please fill in all required fields.";
    } else {
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message, phone, preferred_contact, newsletter) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssi", $name, $email, $subject, $message, $phone, $preferred_contact, $newsletter);
        
        if ($stmt->execute()) {
            $success_message = "Thank you for your message! We'll get back to you soon.";
            // Clear form data
            $name = $email = $subject = $message = $phone = $preferred_contact = '';
            $newsletter = 0;
        } else {
            $error_message = "Failed to send message. Please try again later.";
        }
        $stmt->close();
    }
}

// Check for success message in session
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
?>

<!-- Contact Page Specific CSS -->
<link rel="stylesheet" href="<?php echo url('assets/css/contact.css'); ?>">

<!-- Hero Section -->
<section class="contact-hero">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <span class="hero-badge">Get In Touch</span>
        <h1>Contact Us</h1>
        <p>Have a question, suggestion, or just want to say hello? We'd love to hear from you!</p>
    </div>
</section>

<!-- Main Content -->
<main class="contact-main">
    
    <!-- Quick Contact Info -->
    <section class="quick-contact">
        <div class="container">
            <div class="contact-cards">
                <div class="contact-card">
                    <div class="card-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3>Visit Us</h3>
                    <p>123 Food Street</p>
                    <p>Culinary District, City 12345</p>
                </div>

                <div class="contact-card">
                    <div class="card-icon">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <h3>Call Us</h3>
                    <p><a href="tel:+15551234567">+1 (555) 123-4567</a></p>
                    <p class="hours">Mon-Fri: 9am-5pm</p>
                </div>

                <div class="contact-card">
                    <div class="card-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3>Email Us</h3>
                    <p><a href="mailto:info@foodfusion.com">info@foodfusion.com</a></p>
                    <p><a href="mailto:support@foodfusion.com">support@foodfusion.com</a></p>
                </div>

                <div class="contact-card">
                    <div class="card-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3>Response Time</h3>
                    <p>Within 24 hours</p>
                    <p class="hours">on business days</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="contact-form-section">
        <div class="container">
            <div class="form-wrapper">
                
                <!-- Form Info Side -->
                <div class="form-info">
                    <h2>Send Us a Message</h2>
                    <p>Fill out the form and our team will get back to you within 24 hours.</p>
                    
                    <div class="info-features">
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Quick response time</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Professional support</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Friendly team</span>
                        </div>
                    </div>

                    <div class="social-connect">
                        <h3>Connect With Us</h3>
                        <div class="social-links">
                            <a href="#" class="social-link" aria-label="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="social-link" aria-label="Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="social-link" aria-label="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="social-link" aria-label="LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="form-container">
                    
                    <?php if ($error_message): ?>
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i>
                            <span><?php echo htmlspecialchars($error_message); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($success_message): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <span><?php echo htmlspecialchars($success_message); ?></span>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="contact.php" class="contact-form" id="contactForm">
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">
                                    <i class="fas fa-user"></i>
                                    Full Name <span class="required">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    placeholder="John Doe" 
                                    required 
                                    value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>">
                            </div>

                            <div class="form-group">
                                <label for="email">
                                    <i class="fas fa-envelope"></i>
                                    Email Address <span class="required">*</span>
                                </label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    placeholder="john@example.com" 
                                    required
                                    value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="phone">
                                    <i class="fas fa-phone"></i>
                                    Phone Number
                                </label>
                                <input 
                                    type="tel" 
                                    id="phone" 
                                    name="phone" 
                                    placeholder="+1 (555) 123-4567"
                                    value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>">
                            </div>

                            <div class="form-group">
                                <label for="subject">
                                    <i class="fas fa-tag"></i>
                                    Subject <span class="required">*</span>
                                </label>
                                <select id="subject" name="subject" required>
                                    <option value="">Choose a subject</option>
                                    <option value="General Inquiry" <?php echo (isset($subject) && $subject == 'General Inquiry') ? 'selected' : ''; ?>>General Inquiry</option>
                                    <option value="Recipe Suggestion" <?php echo (isset($subject) && $subject == 'Recipe Suggestion') ? 'selected' : ''; ?>>Recipe Suggestion</option>
                                    <option value="Technical Support" <?php echo (isset($subject) && $subject == 'Technical Support') ? 'selected' : ''; ?>>Technical Support</option>
                                    <option value="Partnership" <?php echo (isset($subject) && $subject == 'Partnership') ? 'selected' : ''; ?>>Partnership</option>
                                    <option value="Feedback" <?php echo (isset($subject) && $subject == 'Feedback') ? 'selected' : ''; ?>>Feedback</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="message">
                                <i class="fas fa-comment-dots"></i>
                                Your Message <span class="required">*</span>
                            </label>
                            <textarea 
                                id="message" 
                                name="message" 
                                rows="6" 
                                placeholder="Tell us what's on your mind..." 
                                required><?php echo isset($message) ? htmlspecialchars($message) : ''; ?></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="preferred_contact">
                                    <i class="fas fa-comments"></i>
                                    Preferred Contact Method
                                </label>
                                <select id="preferred_contact" name="preferred_contact">
                                    <option value="email" <?php echo (isset($preferred_contact) && $preferred_contact == 'email') ? 'selected' : ''; ?>>Email</option>
                                    <option value="phone" <?php echo (isset($preferred_contact) && $preferred_contact == 'phone') ? 'selected' : ''; ?>>Phone</option>
                                </select>
                            </div>

                            <div class="form-group checkbox-wrapper">
                                <label class="checkbox-label">
                                    <input 
                                        type="checkbox" 
                                        id="newsletter" 
                                        name="newsletter" 
                                        <?php echo (isset($newsletter) && $newsletter == 1) ? 'checked' : ''; ?>>
                                    <span class="checkmark"></span>
                                    <span class="checkbox-text">Subscribe to our newsletter for recipes and tips</span>
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="submit-btn">
                            <i class="fas fa-paper-plane"></i>
                            <span>Send Message</span>
                        </button>
                        
                    </form>
                </div>

            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <div class="section-header">
                <h2>Frequently Asked Questions</h2>
                <p>Quick answers to common questions</p>
            </div>

            <div class="faq-grid">
                <div class="faq-item">
                    <h3><i class="fas fa-question-circle"></i> How quickly will I get a response?</h3>
                    <p>We typically respond within 24 hours on business days. For urgent matters, please call us directly.</p>
                </div>

                <div class="faq-item">
                    <h3><i class="fas fa-question-circle"></i> Can I submit my own recipes?</h3>
                    <p>Absolutely! We love featuring community recipes. Use the "Recipe Suggestion" subject when contacting us.</p>
                </div>

                <div class="faq-item">
                    <h3><i class="fas fa-question-circle"></i> Do you offer cooking classes?</h3>
                    <p>Yes! Contact us for information about our upcoming cooking classes and workshops.</p>
                </div>

                <div class="faq-item">
                    <h3><i class="fas fa-question-circle"></i> How can I partner with FoodFusion?</h3>
                    <p>We're always open to partnerships! Select "Partnership" as your subject and tell us about your proposal.</p>
                </div>
            </div>
        </div>
    </section>

</main>

<?php include('includes/footer.php'); ?>
