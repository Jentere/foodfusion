<?php
/**
 * Register Page - FoodFusion
 * User registration - Works with both popup and standalone page
 */

// Start output buffering to prevent any accidental output
ob_start();

session_start();

// Suppress errors in production (they'll be logged instead)
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

$response = ['success' => false, 'message' => '', 'errors' => []];

// Handle POST request (both AJAX and regular form submission)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Clear any output buffer
    ob_clean();
    
    try {
        // Include database connection
        require_once('../includes/db.php');
        
        // Check if database connection exists
        if (!isset($conn) || $conn->connect_error) {
            throw new Exception('Database connection failed. Please try again later.');
        }
        
        // Initialize error array
        $errors = [];
        
        // Sanitize and validate input
        $first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
        $last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
        $email = isset($_POST['email']) ? trim(strtolower($_POST['email'])) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
        $terms = isset($_POST['terms']);

        // Validate first name
        if (empty($first_name)) {
            $errors[] = 'First name is required.';
        } elseif (strlen($first_name) < 2 || strlen($first_name) > 50) {
            $errors[] = 'First name must be between 2 and 50 characters.';
        } elseif (!preg_match('/^[a-zA-Z\s\'-]+$/', $first_name)) {
            $errors[] = 'First name can only contain letters, spaces, hyphens, and apostrophes.';
        }

        // Validate last name
        if (empty($last_name)) {
            $errors[] = 'Last name is required.';
        } elseif (strlen($last_name) < 2 || strlen($last_name) > 50) {
            $errors[] = 'Last name must be between 2 and 50 characters.';
        } elseif (!preg_match('/^[a-zA-Z\s\'-]+$/', $last_name)) {
            $errors[] = 'Last name can only contain letters, spaces, hyphens, and apostrophes.';
        }

        // Validate email
        if (empty($email)) {
            $errors[] = 'Email address is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        } elseif (strlen($email) > 100) {
            $errors[] = 'Email address is too long (maximum 100 characters).';
        } else {
            // Check if email already exists
            $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
            if ($stmt) {
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->store_result();
                
                if ($stmt->num_rows > 0) {
                    $errors[] = 'This email address is already registered. Please use a different email or try logging in.';
                }
                $stmt->close();
            } else {
                $errors[] = 'Database error: Unable to check email availability.';
            }
        }

        // Validate password
        if (empty($password)) {
            $errors[] = 'Password is required.';
        } else {
            $password_errors = [];
            
            if (strlen($password) < 8) {
                $password_errors[] = 'at least 8 characters';
            }
            if (!preg_match('/[A-Z]/', $password)) {
                $password_errors[] = 'one uppercase letter';
            }
            if (!preg_match('/[a-z]/', $password)) {
                $password_errors[] = 'one lowercase letter';
            }
            if (!preg_match('/[0-9]/', $password)) {
                $password_errors[] = 'one number';
            }
            if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/', $password)) {
                $password_errors[] = 'one special character';
            }
            
            if (!empty($password_errors)) {
                $errors[] = 'Password must contain ' . implode(', ', $password_errors) . '.';
            }
        }

        // Validate password confirmation (only if confirm_password is provided)
        if (!empty($confirm_password)) {
            if ($password !== $confirm_password) {
                $errors[] = 'Passwords do not match.';
            }
        }

        // Validate terms acceptance (only if terms is expected)
        if (isset($_POST['terms']) && !$terms) {
            $errors[] = 'You must agree to the Terms of Service and Privacy Policy.';
        }

        // If there are validation errors, return them
        if (!empty($errors)) {
            $response['message'] = 'Please correct the following errors:';
            $response['errors'] = $errors;
        } else {
            // All validations passed, proceed with registration
            
            // Hash password using bcrypt
            $hashed_password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
            
            if ($hashed_password === false) {
                throw new Exception('Failed to hash password.');
            }

            // Insert new user into database
            $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, created_at) VALUES (?, ?, ?, ?, NOW())");
            
            if (!$stmt) {
                throw new Exception('Database error: ' . $conn->error);
            }

            $stmt->bind_param("ssss", $first_name, $last_name, $email, $hashed_password);
            
            if ($stmt->execute()) {
                $user_id = $stmt->insert_id;
                $stmt->close();
                
                // Registration successful
                $response['success'] = true;
                $response['message'] = 'Registration successful! Redirecting to login...';
                $response['user_id'] = $user_id;
                
                // Log the registration (optional)
                error_log("New user registered: ID=$user_id, Email=$email");
                
            } else {
                throw new Exception('Failed to create user account: ' . $stmt->error);
            }
        }
        
    } catch (Exception $e) {
        $response['success'] = false;
        $response['message'] = 'Registration failed. Please try again later.';
        $response['errors'] = ['An unexpected error occurred. Please contact support if the problem persists.'];
        error_log("Registration error: " . $e->getMessage());
    }

    // Clear output buffer and send JSON response
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// If not POST request, show the registration form
ob_end_clean();
require_once('../includes/paths.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - FoodFusion</title>
    <link rel="stylesheet" href="<?php echo url('assets/css/auth.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="auth-page register-layout">
        <!-- Top Banner - Branding -->
        <div class="auth-visual">
            <div class="visual-overlay"></div>
            <div class="visual-content">
                <a href="<?php echo url('index.php'); ?>" class="logo-link">
                    <img src="<?php echo url('assets/images/logo.png'); ?>" alt="FoodFusion Logo" class="logo">
                </a>
                <h1>Join FoodFusion</h1>
                <p>Start your culinary journey today</p>
                
                <!-- Password Requirements -->
                <div class="password-requirements">
                    <p class="requirements-title">Password must contain:</p>
                    <ul class="requirements-list">
                        <li id="req-length"><i class="fas fa-circle"></i> At least 8 characters</li>
                        <li id="req-uppercase"><i class="fas fa-circle"></i> One uppercase letter</li>
                        <li id="req-lowercase"><i class="fas fa-circle"></i> One lowercase letter</li>
                        <li id="req-number"><i class="fas fa-circle"></i> One number</li>
                        <li id="req-special"><i class="fas fa-circle"></i> One special character</li>
                    </ul>
                </div>
                
                <!-- Social Login -->
                <div class="divider">
                    <span>or sign up with</span>
                </div>
                
                <div class="social-login">
                    <button class="social-btn google" type="button">
                        <i class="fab fa-google"></i>
                        <span>Google</span>
                    </button>
                    <button class="social-btn facebook" type="button">
                        <i class="fab fa-facebook-f"></i>
                        <span>Facebook</span>
                    </button>
                </div>
                
                <!-- Footer -->
                <div class="form-footer">
                    <p>Already have an account? <a href="<?php echo url('auth/login.php'); ?>">Sign in</a></p>
                </div>
            </div>
        </div>

        <!-- Right Side - Register Form -->
        <div class="auth-form-container">
            <div class="auth-form-wrapper">
                
                <div class="form-header">
                    <h2>Create Account</h2>
                    
                </div>

                <!-- Alert Messages -->
                <div id="alertContainer"></div>

                <form id="registerForm" method="POST" action="register.php" class="auth-form">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">
                                <i class="fas fa-user"></i>
                                First Name
                            </label>
                            <input 
                                type="text" 
                                id="first_name" 
                                name="first_name" 
                                placeholder="John" 
                                required
                                autocomplete="given-name"
                                maxlength="50">
                        </div>

                        <div class="form-group">
                            <label for="last_name">
                                <i class="fas fa-user"></i>
                                Last Name
                            </label>
                            <input 
                                type="text" 
                                id="last_name" 
                                name="last_name" 
                                placeholder="Doe" 
                                required
                                autocomplete="family-name"
                                maxlength="50">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">
                            <i class="fas fa-envelope"></i>
                            Email Address
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            placeholder="john@example.com" 
                            required
                            autocomplete="email"
                            maxlength="100">
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
                                placeholder="Create a strong password" 
                                required
                                autocomplete="new-password">
                            <button type="button" class="toggle-password" onclick="togglePassword('password', 'toggleIcon1')">
                                <i class="fas fa-eye" id="toggleIcon1"></i>
                            </button>
                        </div>
                        <div class="password-strength" id="passwordStrength"></div>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">
                            <i class="fas fa-lock"></i>
                            Confirm Password
                        </label>
                        <div class="password-input-wrapper">
                            <input 
                                type="password" 
                                id="confirm_password" 
                                name="confirm_password" 
                                placeholder="Re-enter your password" 
                                required
                                autocomplete="new-password">
                            <button type="button" class="toggle-password" onclick="togglePassword('confirm_password', 'toggleIcon2')">
                                <i class="fas fa-eye" id="toggleIcon2"></i>
                            </button>
                        </div>
                    </div>

                    <div class="checkbox-wrapper">
                        <label class="checkbox-label">
                            <input type="checkbox" id="terms" name="terms" required>
                            <span class="checkbox-text">
                                I agree to <a href="#" class="terms-link">Terms</a> & <a href="#" class="terms-link">Privacy</a>
                            </span>
                        </label>
                    </div>

                    <button type="submit" class="submit-btn" id="submitBtn">
                        <i class="fas fa-user-plus"></i>
                        <span>Create Account</span>
                    </button>

                </form>

                <div class="back-home">
                    <a href="<?php echo url('index.php'); ?>">
                        <i class="fas fa-arrow-left"></i>
                        Back to Home
                    </a>
                </div>

            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(iconId);
            
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

        // Password strength checker
        const passwordInput = document.getElementById('password');
        const strengthDiv = document.getElementById('passwordStrength');

        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            
            // Check requirements
            const hasLength = password.length >= 8;
            const hasUppercase = /[A-Z]/.test(password);
            const hasLowercase = /[a-z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            const hasSpecial = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);
            
            // Update requirement indicators
            updateRequirement('req-length', hasLength);
            updateRequirement('req-uppercase', hasUppercase);
            updateRequirement('req-lowercase', hasLowercase);
            updateRequirement('req-number', hasNumber);
            updateRequirement('req-special', hasSpecial);
            
            // Calculate strength
            if (hasLength) strength++;
            if (hasUppercase) strength++;
            if (hasLowercase) strength++;
            if (hasNumber) strength++;
            if (hasSpecial) strength++;
            
            // Display strength
            if (password.length === 0) {
                strengthDiv.innerHTML = '';
            } else if (strength <= 2) {
                strengthDiv.innerHTML = '<span class="strength-weak">Weak password</span>';
            } else if (strength <= 4) {
                strengthDiv.innerHTML = '<span class="strength-medium">Medium password</span>';
            } else {
                strengthDiv.innerHTML = '<span class="strength-strong">Strong password</span>';
            }
        });

        function updateRequirement(id, met) {
            const element = document.getElementById(id);
            if (met) {
                element.classList.add('met');
                element.querySelector('i').classList.remove('fa-circle');
                element.querySelector('i').classList.add('fa-check-circle');
            } else {
                element.classList.remove('met');
                element.querySelector('i').classList.remove('fa-check-circle');
                element.querySelector('i').classList.add('fa-circle');
            }
        }

        // Handle form submission
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const originalContent = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Creating Account...</span>';
            
            const formData = new FormData(this);
            
            fetch('register.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                // Check if response is JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Server returned non-JSON response. Please check server configuration.');
                }
                return response.json();
            })
            .then(data => {
                const alertContainer = document.getElementById('alertContainer');
                
                if (data.success) {
                    alertContainer.innerHTML = `
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <span>${data.message}</span>
                        </div>
                    `;
                    
                    // Redirect after 2 seconds
                    setTimeout(() => {
                        window.location.href = '<?php echo url('auth/login.php'); ?>';
                    }, 2000);
                } else {
                    // Display errors
                    let errorHtml = '<div class="alert alert-error"><i class="fas fa-exclamation-circle"></i><div>';
                    
                    if (data.message) {
                        errorHtml += `<strong>${data.message}</strong>`;
                    }
                    
                    if (data.errors && data.errors.length > 0) {
                        errorHtml += '<ul style="margin: 0.5rem 0 0 1.5rem; padding: 0;">';
                        data.errors.forEach(error => {
                            errorHtml += `<li>${error}</li>`;
                        });
                        errorHtml += '</ul>';
                    }
                    
                    errorHtml += '</div></div>';
                    alertContainer.innerHTML = errorHtml;
                    
                    // Reset button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalContent;
                    
                    // Scroll to top to show errors
                    alertContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const alertContainer = document.getElementById('alertContainer');
                alertContainer.innerHTML = `
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>${error.message || 'An error occurred. Please try again.'}</span>
                    </div>
                `;
                
                // Reset button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalContent;
            });
        });

        // Auto-hide success alerts after 5 seconds
        setTimeout(() => {
            const successAlerts = document.querySelectorAll('.alert-success');
            successAlerts.forEach(alert => {
                alert.style.animation = 'slideUp 0.3s ease-out forwards';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);
    </script>
</body>
</html>
