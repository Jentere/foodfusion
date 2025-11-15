<?php
/**
 * Login Page - FoodFusion
 * User authentication - Works with both popup and standalone page
 */
session_start();
require_once('../includes/paths.php');
include('../includes/db.php');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check if account is locked
    $lock_time = strtotime("-3 minutes");
    $stmt = $conn->prepare("SELECT COUNT(*) FROM login_attempts WHERE email = ? AND attempt_time > FROM_UNIXTIME(?)");
    $stmt->bind_param("si", $email, $lock_time);
    $stmt->execute();
    $stmt->bind_result($attempts);
    $stmt->fetch();
    $stmt->close();

    if ($attempts >= 3) {
        $response['message'] = 'Account locked. Try again after 3 minutes.';
    } else {
        // Check user credentials
        $stmt = $conn->prepare("SELECT user_id, password, first_name, last_name FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($user_id, $hashed_password, $first_name, $last_name);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_name'] = $first_name . ' ' . $last_name;
                $response['success'] = true;
                $response['message'] = 'Login successful! Redirecting...';
                // Clear failed attempts
                $stmtClear = $conn->prepare("DELETE FROM login_attempts WHERE email = ?");
                $stmtClear->bind_param("s", $email);
                $stmtClear->execute();
                $stmtClear->close();
            } else {
                // Wrong password, log attempt
                $stmtInsert = $conn->prepare("INSERT INTO login_attempts (email) VALUES (?)");
                $stmtInsert->bind_param("s", $email);
                $stmtInsert->execute();
                $response['message'] = 'Incorrect password.';
            }
        } else {
            $response['message'] = 'Email not found.';
        }

        $stmt->close();
    }

    // Always return JSON for POST requests
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FoodFusion</title>
    <link rel="stylesheet" href="<?php echo url('assets/css/auth.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="auth-page">
        <!-- Left Side - Image/Branding -->
        <div class="auth-visual">
            <div class="visual-overlay"></div>
            <div class="visual-content">
                <a href="<?php echo url('index.php'); ?>" class="logo-link">
                    <img src="<?php echo url('assets/images/logo.png'); ?>" alt="FoodFusion Logo" class="logo">
                </a>
                <h1>Welcome Back</h1>
                <p>Sign in to continue your culinary journey</p>
                <div class="visual-features">
                    <div class="feature">
                        <i class="fas fa-utensils"></i>
                        <span>10K+ Recipes</span>
                    </div>
                    <div class="feature">
                        <i class="fas fa-users"></i>
                        <span>50K+ Members</span>
                    </div>
                    <div class="feature">
                        <i class="fas fa-globe"></i>
                        <span>100+ Countries</span>
                    </div>
                    <div class="feature">
                        <i class="fas fa-heart"></i>
                        <span>Free to Join</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="auth-form-container">
            <div class="auth-form-wrapper">
                
                <div class="form-header">
                    <h2>Sign In</h2>
                    <p>Access your account</p>
                </div>

                <!-- Alert Messages -->
                <div id="alertContainer"></div>

                <form id="loginForm" method="POST" action="login.php" class="auth-form">
                    
                    <div class="form-group">
                        <label for="email">
                            <i class="fas fa-envelope"></i>
                            Email Address
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            placeholder="Enter your email" 
                            required
                            autocomplete="email">
                    </div>

                    <div class="form-group">
                        <label for="password">
                            <i class="fas fa-lock"></i>
                            Password
                        </label>
                        <div class="password-input-wrapper">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                placeholder="Enter your password" 
                                required
                                autocomplete="current-password">
                            <button type="button" class="toggle-password" onclick="togglePassword()">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-options">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember">
                            <span class="checkmark"></span>
                            <span class="checkbox-text">Remember me</span>
                        </label>
                        <a href="<?php echo url('auth/forgot_password.php'); ?>" class="forgot-link">Forgot Password?</a>
                    </div>

                    <button type="submit" class="submit-btn" id="submitBtn">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Sign In</span>
                    </button>

                </form>

                <div class="divider">
                    <span>or continue with</span>
                </div>

                <div class="social-login">
                    <button class="social-btn google">
                        <i class="fab fa-google"></i>
                        <span>Google</span>
                    </button>
                    <button class="social-btn facebook">
                        <i class="fab fa-facebook-f"></i>
                        <span>Facebook</span>
                    </button>
                </div>

                <div class="form-footer">
                    <p>Don't have an account? <a href="register.php">Sign up</a></p>
                </div>

                <div class="back-home">
                    <a href="../index.php">
                        <i class="fas fa-arrow-left"></i>
                        Back to Home
                    </a>
                </div>

            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
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

        // Handle form submission
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const originalContent = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Signing In...</span>';
            
            const formData = new FormData(this);
            
            fetch('login.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const alertContainer = document.getElementById('alertContainer');
                
                if (data.success) {
                    alertContainer.innerHTML = `
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <span>${data.message}</span>
                        </div>
                    `;
                    
                    // Redirect after 1 second
                    setTimeout(() => {
                        window.location.href = '../index.php';
                    }, 1000);
                } else {
                    alertContainer.innerHTML = `
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>${data.message}</span>
                        </div>
                    `;
                    
                    // Reset button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalContent;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const alertContainer = document.getElementById('alertContainer');
                alertContainer.innerHTML = `
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>An error occurred. Please try again.</span>
                    </div>
                `;
                
                // Reset button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalContent;
            });
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.animation = 'slideUp 0.3s ease-out forwards';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);
    </script>
</body>
</html>
