<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodFusion | Home</title>
    <link rel="stylesheet" href="<?php echo url('assets/css/style.css'); ?>">
    <link rel="stylesheet" href="<?php echo url('assets/css/navigation.css'); ?>">
    <?php
    // Load page-specific CSS
    $current_page = basename($_SERVER['PHP_SELF'], '.php');
    if (file_exists(__DIR__ . '/../assets/css/' . $current_page . '.css')) {
        echo '<link rel="stylesheet" href="' . url('assets/css/' . $current_page . '.css') . '">';
    }
    ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="<?php echo url('assets/js/video-player.js'); ?>" defer></script>
    <script src="<?php echo url('assets/js/main.js'); ?>" defer></script>
</head>
<body>

<!-- Modern Navigation System -->
<nav class="modern-nav" id="modernNav">
    <div class="nav-wrapper">
        <!-- Brand Logo -->
        <div class="nav-brand">
            <a href="<?php echo url('index.php'); ?>" class="brand-logo">
                <img src="<?php echo url('assets/images/logo.png'); ?>" alt="FoodFusion" class="logo-img">
            </a>
        </div>

        <!-- Desktop Navigation Links -->
        <div class="nav-links" id="navLinks">
            <a href="<?php echo url('index.php'); ?>" class="nav-link" data-text="Home">Home</a>
            <a href="<?php echo url('about.php'); ?>" class="nav-link" data-text="About">About</a>
            <a href="<?php echo url('recipes.php'); ?>" class="nav-link" data-text="Recipes">Recipes</a>
            <a href="<?php echo url('community.php'); ?>" class="nav-link" data-text="Community">Community</a>
            <a href="<?php echo url('culinary.php'); ?>" class="nav-link" data-text="Culinary">Culinary</a>
            <a href="<?php echo url('educational.php'); ?>" class="nav-link" data-text="Education">Education</a>
            <a href="<?php echo url('contact.php'); ?>" class="nav-link" data-text="Contact">Contact</a>
        </div>

        <!-- Authentication Section -->
        <div class="nav-auth" id="navAuth">
            <?php
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            // Check if setup is completed before trying to access database
            $setup_completed = file_exists(__DIR__ . '/../setup.lock');
            $config_exists = file_exists(__DIR__ . '/config.php');
            
            if (isset($_SESSION['user_id']) && $setup_completed && $config_exists) {
                try {
                    include('db.php');
                    $uid = $_SESSION['user_id'];
                    $stmt = $conn->prepare("SELECT first_name FROM users WHERE user_id = ?");
                    $stmt->bind_param("i", $uid);
                    $stmt->execute();
                    $stmt->bind_result($firstName);
                    $found = $stmt->fetch();
                    $stmt->close();
                    
                    // If user not found in database, clear the session
                    if (!$found) {
                        session_unset();
                        session_destroy();
                        header('Location: /foodfusion/index.php');
                        exit();
                    }

                    echo '<div class="user-section">
                            <div class="user-profile">
                                <div class="user-avatar">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <span class="user-name">Hi, ' . htmlspecialchars($firstName) . '</span>
                            </div>
                            <a href="' . url('logout.php') . '" class="auth-btn logout-btn">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </a>
                          </div>';
                } catch (Exception $e) {
                    // If database connection fails, show login buttons
                    echo '<div class="auth-buttons">
                            <a href="' . url('auth/login.php') . '" class="auth-btn login-btn">
                                <i class="fas fa-sign-in-alt"></i>
                                <span>Login</span>
                            </a>
                            <a href="' . url('auth/register.php') . '" class="auth-btn signup-btn">
                                <i class="fas fa-user-plus"></i>
                                <span>Sign Up</span>
                            </a>
                          </div>';
                }
            } else {
                echo '<div class="auth-buttons">
                        <a href="' . url('auth/login.php') . '" class="auth-btn login-btn">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Login</span>
                        </a>
                        <a href="' . url('auth/register.php') . '" class="auth-btn signup-btn">
                            <i class="fas fa-user-plus"></i>
                            <span>Sign Up</span>
                        </a>
                      </div>';
            }
            ?>
        </div>

        <!-- Mobile Menu Toggle -->
        <button class="mobile-toggle" id="mobileToggle" aria-label="Toggle Menu">
            <span class="toggle-line"></span>
            <span class="toggle-line"></span>
            <span class="toggle-line"></span>
        </button>
    </div>

    <!-- Mobile Menu Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>
</nav>

</body>
</html>
