<?php
/**
 * FoodFusion Setup Script
 * This script handles the complete installation of the FoodFusion website
 * Run this file in your browser to set up the application from scratch
 */

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuration
$SETUP_VERSION = '1.0.0';
$MIN_PHP_VERSION = '7.4.0';

// Single-page installation
$error = '';
$success = '';
$installed = false;

/**
 * Check if setup is already completed
 */
function isSetupCompleted() {
    return file_exists(__DIR__ . '/setup.lock');
}

/**
 * Check system requirements
 */
function checkSystemRequirements() {
    global $MIN_PHP_VERSION;
    
    $requirements = [
        'php_version' => [
            'name' => 'PHP Version >= ' . $MIN_PHP_VERSION,
            'status' => version_compare(PHP_VERSION, $MIN_PHP_VERSION, '>='),
            'current' => PHP_VERSION
        ],
        'mysqli' => [
            'name' => 'MySQLi Extension',
            'status' => extension_loaded('mysqli'),
            'current' => extension_loaded('mysqli') ? 'Loaded' : 'Not loaded'
        ],
        'pdo' => [
            'name' => 'PDO Extension',
            'status' => extension_loaded('pdo'),
            'current' => extension_loaded('pdo') ? 'Loaded' : 'Not loaded'
        ],
        'gd' => [
            'name' => 'GD Extension (for image processing)',
            'status' => extension_loaded('gd'),
            'current' => extension_loaded('gd') ? 'Loaded' : 'Not loaded'
        ],
        'uploads_writable' => [
            'name' => 'Uploads Directory Writable',
            'status' => is_dir(__DIR__ . '/uploads') && is_writable(__DIR__ . '/uploads'),
            'current' => is_dir(__DIR__ . '/uploads') ? (is_writable(__DIR__ . '/uploads') ? 'Writable' : 'Not writable') : 'Directory not found'
        ],
        'resources_writable' => [
            'name' => 'Resources Directory Writable',
            'status' => is_dir(__DIR__ . '/resources') && is_writable(__DIR__ . '/resources'),
            'current' => is_dir(__DIR__ . '/resources') ? (is_writable(__DIR__ . '/resources') ? 'Writable' : 'Not writable') : 'Directory not found'
        ]
    ];
    
    return $requirements;
}

/**
 * Test database connection
 */
function testDatabaseConnection($host, $username, $password, $database = null) {
    try {
        $conn = new mysqli($host, $username, $password, $database);
        if ($conn->connect_error) {
            return ['success' => false, 'error' => $conn->connect_error];
        }
        $conn->close();
        return ['success' => true];
    } catch (Exception $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

/**
 * Create database if it doesn't exist
 */
function createDatabase($host, $username, $password, $database) {
    try {
        $conn = new mysqli($host, $username, $password);
        if ($conn->connect_error) {
            return ['success' => false, 'error' => $conn->connect_error];
        }
        
        $sql = "CREATE DATABASE IF NOT EXISTS `" . $conn->real_escape_string($database) . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
        if (!$conn->query($sql)) {
            return ['success' => false, 'error' => $conn->error];
        }
        
        $conn->close();
        return ['success' => true];
    } catch (Exception $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

/**
 * Create configuration file
 */
function createConfigFile($host, $username, $password, $database, $site_url) {
    $config_content = "<?php
// Database Configuration
define('DB_HOST', '" . addslashes($host) . "');
define('DB_USER', '" . addslashes($username) . "');
define('DB_PASS', '" . addslashes($password) . "');
define('DB_NAME', '" . addslashes($database) . "');

// Site Configuration
define('SITE_URL', '" . addslashes($site_url) . "');
define('UPLOADS_DIR', __DIR__ . '/../uploads');
define('RESOURCES_DIR', __DIR__ . '/../resources');

// Security Configuration
define('HASH_COST', 10);  // For password hashing
define('SESSION_LIFETIME', 3600);  // 1 hour
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_TIMEOUT', 900);  // 15 minutes

// Email Configuration (configure as needed)
define('SMTP_HOST', '');
define('SMTP_PORT', 587);
define('SMTP_USER', '');
define('SMTP_PASS', '');
define('MAIL_FROM', 'noreply@foodfusion.com');
?>";

    return file_put_contents(__DIR__ . '/includes/config.php', $config_content);
}

/**
 * Execute SQL file
 */
function executeSQLFile($conn, $filepath) {
    if (!file_exists($filepath)) {
        return ['success' => false, 'error' => 'SQL file not found: ' . $filepath];
    }
    
    $sql = file_get_contents($filepath);
    if ($sql === false) {
        return ['success' => false, 'error' => 'Could not read SQL file'];
    }
    
    // Split SQL into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    foreach ($statements as $statement) {
        if (empty($statement) || strpos($statement, '--') === 0) {
            continue;
        }
        
        if (!$conn->query($statement)) {
            return ['success' => false, 'error' => 'SQL Error: ' . $conn->error . ' in statement: ' . substr($statement, 0, 100) . '...'];
        }
    }
    
    return ['success' => true];
}

/**
 * Setup database tables and initial data - COMPLETELY REWRITTEN FOR MAXIMUM ROBUSTNESS
 */
function setupDatabase($host, $username, $password, $database) {
    $conn = null;
    try {
        // Establish connection with proper error handling
        $conn = new mysqli($host, $username, $password, $database);
        if ($conn->connect_error) {
            return ['success' => false, 'error' => 'Database connection failed: ' . $conn->connect_error];
        }
        
        // Set connection properties for reliability
        $conn->set_charset("utf8mb4");
        $conn->query("SET sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_DATE,NO_ZERO_IN_DATE,ERROR_FOR_DIVISION_BY_ZERO'");
        
        // Use transaction for atomic operations
        $conn->autocommit(false);
        
        // Phase 1: Clean slate - Remove all existing tables
        $cleanup_result = performDatabaseCleanup($conn);
        if (!$cleanup_result['success']) {
            $conn->rollback();
            return $cleanup_result;
        }
        
        // Phase 2: Create all tables with proper structure
        $creation_result = createDatabaseStructure($conn);
        if (!$creation_result['success']) {
            $conn->rollback();
            return $creation_result;
        }
        
        // Phase 3: Populate with initial data
        $data_result = populateInitialData($conn);
        if (!$data_result['success']) {
            $conn->rollback();
            return $data_result;
        }
        
        // Commit all changes if everything succeeded
        $conn->commit();
        $conn->autocommit(true);
        
        return ['success' => true, 'message' => 'Database setup completed successfully with all tables and data'];
        
    } catch (Exception $e) {
        if ($conn) {
            $conn->rollback();
        }
        return ['success' => false, 'error' => 'Critical setup error: ' . $e->getMessage()];
    } finally {
        if ($conn) {
            $conn->close();
        }
    }
}

/**
 * Phase 1: Clean up existing database structure
 */
function performDatabaseCleanup($conn) {
    try {
        // Disable foreign key checks for clean removal
        if (!$conn->query("SET FOREIGN_KEY_CHECKS = 0")) {
            return ['success' => false, 'error' => 'Failed to disable foreign key checks: ' . $conn->error];
        }
        
        // Tables in reverse dependency order (children first, parents last)
        $tables_to_drop = [
            'recipe_ratings',
            'password_resets', 
            'login_attempts',
            'contact_messages',
            'messages',
            'resources',
            'likes',
            'comments',
            'community_posts',
            'recipes',
            'users'
        ];
        
        // Drop each table if it exists
        foreach ($tables_to_drop as $table) {
            $drop_sql = "DROP TABLE IF EXISTS `{$table}`";
            if (!$conn->query($drop_sql)) {
                return ['success' => false, 'error' => "Failed to drop table '{$table}': " . $conn->error];
            }
        }
        
        // Re-enable foreign key checks
        if (!$conn->query("SET FOREIGN_KEY_CHECKS = 1")) {
            return ['success' => false, 'error' => 'Failed to re-enable foreign key checks: ' . $conn->error];
        }
        
        return ['success' => true];
        
    } catch (Exception $e) {
        return ['success' => false, 'error' => 'Database cleanup failed: ' . $e->getMessage()];
    }
}

/**
 * Phase 2: Create complete database structure
 */
function createDatabaseStructure($conn) {
    try {
        // Define all table creation statements with explicit structure
        $table_definitions = [
            'users' => "CREATE TABLE `users` (
                `user_id` int(11) NOT NULL AUTO_INCREMENT,
                `first_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
                `last_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
                `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
                `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`user_id`),
                UNIQUE KEY `email` (`email`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            
            'recipes' => "CREATE TABLE `recipes` (
                `recipe_id` int(11) NOT NULL AUTO_INCREMENT,
                `user_id` int(11) DEFAULT NULL,
                `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
                `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
                `cuisine_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `dietary_preference` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `difficulty` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `views` int(11) NOT NULL DEFAULT 0,
                PRIMARY KEY (`recipe_id`),
                KEY `fk_recipes_user` (`user_id`),
                CONSTRAINT `fk_recipes_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            
            'community_posts' => "CREATE TABLE `community_posts` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `user_id` int(11) NOT NULL,
                `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
                `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `category` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `difficulty` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `fk_posts_user` (`user_id`),
                CONSTRAINT `fk_posts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            
            'comments' => "CREATE TABLE `comments` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `post_id` int(11) NOT NULL,
                `user_id` int(11) NOT NULL,
                `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `fk_comments_post` (`post_id`),
                KEY `fk_comments_user` (`user_id`),
                CONSTRAINT `fk_comments_post` FOREIGN KEY (`post_id`) REFERENCES `community_posts` (`id`) ON DELETE CASCADE,
                CONSTRAINT `fk_comments_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            
            'likes' => "CREATE TABLE `likes` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `post_id` int(11) NOT NULL,
                `user_id` int(11) NOT NULL,
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `unique_like` (`post_id`,`user_id`),
                KEY `fk_likes_user` (`user_id`),
                CONSTRAINT `fk_likes_post` FOREIGN KEY (`post_id`) REFERENCES `community_posts` (`id`) ON DELETE CASCADE,
                CONSTRAINT `fk_likes_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            
            'resources' => "CREATE TABLE `resources` (
                `resource_id` int(11) NOT NULL AUTO_INCREMENT,
                `type` enum('culinary','educational') COLLATE utf8mb4_unicode_ci NOT NULL,
                `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `file_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
                `uploaded_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`resource_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            
            'messages' => "CREATE TABLE `messages` (
                `message_id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `message` text COLLATE utf8mb4_unicode_ci,
                `submitted_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`message_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            
            'login_attempts' => "CREATE TABLE `login_attempts` (
                `attempt_id` int(11) NOT NULL AUTO_INCREMENT,
                `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
                `attempt_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`attempt_id`),
                KEY `idx_email_time` (`email`, `attempt_time`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            
            'password_resets' => "CREATE TABLE `password_resets` (
                `reset_id` int(11) NOT NULL AUTO_INCREMENT,
                `user_id` int(11) NOT NULL,
                `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`reset_id`),
                KEY `fk_resets_user` (`user_id`),
                KEY `idx_token` (`token`),
                CONSTRAINT `fk_resets_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            
            'contact_messages' => "CREATE TABLE `contact_messages` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
                `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
                `subject` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
                `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
                `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `preferred_contact` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `newsletter` tinyint(1) DEFAULT '0',
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `idx_email` (`email`),
                KEY `idx_created` (`created_at`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            
            'recipe_ratings' => "CREATE TABLE `recipe_ratings` (
                `rating_id` int(11) NOT NULL AUTO_INCREMENT,
                `recipe_id` int(11) NOT NULL,
                `user_id` int(11) NOT NULL,
                `rating` decimal(2,1) NOT NULL,
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`rating_id`),
                UNIQUE KEY `unique_rating` (`recipe_id`,`user_id`),
                KEY `fk_ratings_user` (`user_id`),
                CONSTRAINT `fk_ratings_recipe` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`recipe_id`) ON DELETE CASCADE,
                CONSTRAINT `fk_ratings_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        ];
        
        // Create each table with detailed error reporting
        foreach ($table_definitions as $table_name => $create_sql) {
            if (!$conn->query($create_sql)) {
                return ['success' => false, 'error' => "Failed to create table '{$table_name}': " . $conn->error];
            }
        }
        
        return ['success' => true];
        
    } catch (Exception $e) {
        return ['success' => false, 'error' => 'Table creation failed: ' . $e->getMessage()];
    }
}

/**
 * Phase 3: Populate database with initial sample data
 */
function populateInitialData($conn) {
    try {
        // Comprehensive sample recipes with diverse content
        $sample_recipes = [
            ['Spicy Chicken Ramen', 'Hot and flavorful Asian ramen with tender chicken and bold spices.', 'Asian', 'Non-Vegetarian', 'Medium', 'recipe1.jpg'],
            ['Vegan Creamy Pasta', 'A dairy-free pasta dish with rich flavor and healthy vegetables.', 'Italian', 'Vegan', 'Easy', 'recipe2.jpg'],
            ['Beef Tacos', 'Traditional Mexican beef tacos with fresh toppings.', 'Mexican', 'Non-Vegetarian', 'Easy', 'recipe3.jpg'],
            ['Gluten-Free Pancakes', 'Fluffy pancakes made with gluten-free flour and almond milk.', 'American', 'Gluten-Free', 'Easy', 'recipe4.jpg'],
            ['Vegetarian Curry', 'A colorful Indian curry packed with vegetables and warm spices.', 'Indian', 'Vegetarian', 'Medium', 'recipe5.jpg'],
            ['Teriyaki Salmon', 'Japanese-inspired salmon glazed in sweet teriyaki sauce.', 'Asian', 'Non-Vegetarian', 'Medium', 'recipe6.jpg'],
            ['Quinoa Salad Bowl', 'A protein-packed vegan bowl with veggies, chickpeas, and lemon dressing.', 'Mediterranean', 'Vegan', 'Easy', 'recipe7.jpg'],
            ['Stuffed Bell Peppers', 'Bell peppers stuffed with rice, beans, and cheese.', 'Mexican', 'Vegetarian', 'Medium', 'recipe8.jpg'],
            ['Classic Margherita Pizza', 'Wood-fired pizza with fresh basil, mozzarella, and tomato.', 'Italian', 'Vegetarian', 'Medium', 'recipe9.jpg'],
            ['Pad Thai', 'Popular Thai stir-fried noodles with tamarind sauce and peanuts.', 'Asian', 'Non-Vegetarian', 'Hard', 'tip1.jpg'],
            ['Avocado Toast Deluxe', 'Avocado on multigrain bread with microgreens and chili flakes.', 'American', 'Vegan', 'Easy', 'recipes6.jpg']
        ];
        
        // Prepare statement with proper error handling
        $recipe_stmt = $conn->prepare("INSERT INTO `recipes` (`title`, `description`, `cuisine_type`, `dietary_preference`, `difficulty`, `image`) VALUES (?, ?, ?, ?, ?, ?)");
        if (!$recipe_stmt) {
            return ['success' => false, 'error' => 'Failed to prepare recipe insertion statement: ' . $conn->error];
        }
        
        // Insert each recipe with individual error checking
        foreach ($sample_recipes as $index => $recipe) {
            $recipe_stmt->bind_param("ssssss", $recipe[0], $recipe[1], $recipe[2], $recipe[3], $recipe[4], $recipe[5]);
            if (!$recipe_stmt->execute()) {
                $recipe_stmt->close();
                return ['success' => false, 'error' => "Failed to insert recipe #{$index} '{$recipe[0]}': " . $recipe_stmt->error];
            }
        }
        $recipe_stmt->close();
        
        return ['success' => true];
        
    } catch (Exception $e) {
        return ['success' => false, 'error' => 'Data population failed: ' . $e->getMessage()];
    }
}

/**
 * Create setup lock file
 */
function createSetupLock() {
    $lock_content = "Setup completed on: " . date('Y-m-d H:i:s') . "\n";
    $lock_content .= "Setup version: " . $GLOBALS['SETUP_VERSION'] . "\n";
    return file_put_contents(__DIR__ . '/setup.lock', $lock_content);
}

/**
 * Clean up old installation files
 */
function cleanupOldFiles() {
    // Remove seed.php if it exists (we don't need this anymore)
    $seed_file = __DIR__ . '/database/seed.php';
    if (file_exists($seed_file)) {
        unlink($seed_file);
    }
    
    // Note: We keep db.php as the site needs it for database connections
}

// Handle ONE-CLICK installation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isSetupCompleted()) {
    $host = trim($_POST['db_host'] ?? 'localhost');
    $username = trim($_POST['db_user'] ?? 'root');
    $password = $_POST['db_pass'] ?? '';
    $database = trim($_POST['db_name'] ?? 'foodfusion_db');
    $site_url = trim($_POST['site_url'] ?? 'http://localhost/foodfusion');
    
    try {
        // Validate inputs
        if (empty($host) || empty($username) || empty($database)) {
            throw new Exception('Please fill in all required fields.');
        }
        
        // Test connection
        $test_result = testDatabaseConnection($host, $username, $password);
        if (!$test_result['success']) {
            throw new Exception('Could not connect to MySQL: ' . $test_result['error']);
        }
        
        // Create database
        $create_result = createDatabase($host, $username, $password, $database);
        if (!$create_result['success']) {
            throw new Exception('Could not create database: ' . $create_result['error']);
        }
        
        // Setup database tables and data
        $setup_result = setupDatabase($host, $username, $password, $database);
        if (!$setup_result['success']) {
            throw new Exception('Database setup failed: ' . $setup_result['error']);
        }
        
        // Create config file
        if (!createConfigFile($host, $username, $password, $database, $site_url)) {
            throw new Exception('Could not create configuration file.');
        }
        
        // Clean up and create lock
        cleanupOldFiles();
        createSetupLock();
        
        $success = '✅ Installation Complete! FoodFusion is ready to use.';
        $installed = true;
        
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Check if already installed (only show error if not just completed installation)
if (isSetupCompleted() && !$installed) {
    $error = 'FoodFusion is already installed! Delete setup.lock file to reinstall.';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodFusion - Quick Setup</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .setup-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        
        .setup-header {
            background: #2c3e50;
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .setup-header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        
        .setup-header p {
            opacity: 0.8;
            font-size: 1.1em;
        }
        
        .setup-content {
            padding: 40px;
        }
        
        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 40px;
        }
        
        .step-item {
            display: flex;
            align-items: center;
            margin: 0 10px;
        }
        
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #ecf0f1;
            color: #7f8c8d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 10px;
        }
        
        .step-number.active {
            background: #3498db;
            color: white;
        }
        
        .step-number.completed {
            background: #27ae60;
            color: white;
        }
        
        .step-title {
            font-weight: 500;
            color: #2c3e50;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #2c3e50;
        }
        
        .form-group input[type="text"],
        .form-group input[type="password"],
        .form-group input[type="url"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #ecf0f1;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #3498db;
        }
        
        .btn {
            background: #3498db;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .btn:hover {
            background: #2980b9;
        }
        
        .btn-success {
            background: #27ae60;
        }
        
        .btn-success:hover {
            background: #229954;
        }
        
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .alert-error {
            background: #e74c3c;
            color: white;
        }
        
        .alert-success {
            background: #27ae60;
            color: white;
        }
        
        .requirements-list {
            list-style: none;
            margin: 20px 0;
        }
        
        .requirements-list li {
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .req-pass {
            background: #d5f4e6;
            color: #27ae60;
        }
        
        .req-fail {
            background: #fadbd8;
            color: #e74c3c;
        }
        
        .info-box {
            background: #ebf3fd;
            border: 1px solid #3498db;
            border-radius: 5px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .info-box h3 {
            color: #2980b9;
            margin-bottom: 10px;
        }
        
        .final-step {
            text-align: center;
            padding: 40px 20px;
        }
        
        .final-step .success-icon {
            font-size: 4em;
            color: #27ae60;
            margin-bottom: 20px;
        }
        
        .final-step h2 {
            color: #27ae60;
            margin-bottom: 20px;
        }
        
        .final-step .btn {
            margin: 10px;
        }
    </style>
</head>
<body>
    <div class="setup-container">
        <div class="setup-header">
            <h1>🍽️ FoodFusion</h1>
            <p>One-Click Installation</p>
        </div>
        
        <div class="setup-content">
            
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <strong>Error:</strong> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($installed): ?>
                <!-- Success Screen -->
                <div class="final-step">
                    <div class="success-icon">🎉</div>
                    <h2>Installation Complete!</h2>
                    <p>FoodFusion has been successfully installed and configured.</p>
                    
                    <div class="info-box">
                        <h3>✅ What was installed:</h3>
                        <ul style="text-align: left; display: inline-block;">
                            <li>✓ Database created and configured</li>
                            <li>✓ All 11 tables created with proper structure</li>
                            <li>✓ Sample recipes and data inserted</li>
                            <li>✓ User authentication system ready</li>
                            <li>✓ Community features enabled</li>
                            <li>✓ Configuration file generated</li>
                        </ul>
                    </div>
                    
                    <a href="index.php" class="btn btn-success">🏠 Go to Homepage</a>
                    <a href="auth/register.php" class="btn">👤 Create Account</a>
                </div>
            <?php else: ?>
                <!-- Installation Form -->
                <h2>Quick Installation</h2>
                <p>Fill in your database details and click "Install Now" - everything will be set up automatically!</p>
                
                <div class="info-box">
                    <h3>📝 Default Settings</h3>
                    <p><strong>Host:</strong> localhost | <strong>User:</strong> root | <strong>Database:</strong> foodfusion_db</p>
                    <small>Change these if your setup is different</small>
                </div>
                
                <form method="post">
                    <div class="form-group">
                        <label for="db_host">Database Host *</label>
                        <input type="text" id="db_host" name="db_host" value="localhost" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="db_user">Database Username *</label>
                        <input type="text" id="db_user" name="db_user" value="root" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="db_pass">Database Password</label>
                        <input type="password" id="db_pass" name="db_pass" placeholder="Leave empty if no password">
                    </div>
                    
                    <div class="form-group">
                        <label for="db_name">Database Name *</label>
                        <input type="text" id="db_name" name="db_name" value="foodfusion_db" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="site_url">Site URL *</label>
                        <input type="url" id="site_url" name="site_url" value="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']); ?>" required>
                    </div>
                    
                    <button type="submit" class="btn btn-success">🚀 Install Now (One Click!)</button>
                </form>
                
                <div class="info-box" style="margin-top: 20px;">
                    <h3>What will be installed:</h3>
                    <ul style="text-align: left;">
                        <li>✅ Create database automatically</li>
                        <li>✅ Set up all database tables</li>
                        <li>✅ Insert 10+ sample recipes</li>
                        <li>✅ Configure authentication system</li>
                        <li>✅ Enable community features</li>
                        <li>✅ Generate configuration file</li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>