<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'foodfusion_db');

// Site Configuration
define('SITE_URL', 'http://localhost/foodfusion');
define('UPLOADS_DIR', __DIR__ . '/../uploads');
define('RESOURCES_DIR', __DIR__ . '/../resources');

// Security Configuration
define('HASH_COST', 10);  // For password hashing
define('SESSION_LIFETIME', 3600);  // 1 hour
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_TIMEOUT', 900);  // 15 minutes

// Email Configuration (if needed)
define('SMTP_HOST', '');
define('SMTP_PORT', 587);
define('SMTP_USER', '');
define('SMTP_PASS', '');
define('MAIL_FROM', 'noreply@foodfusion.com');