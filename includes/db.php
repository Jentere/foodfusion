<?php
/**
 * Database Connection Handler
 * This file handles the database connection using the configuration from config.php
 */

// Prevent multiple inclusions
if (defined('DB_CONNECTION_LOADED')) {
    return;
}
define('DB_CONNECTION_LOADED', true);

// Check if we're on setup page, if so, don't try to connect
$current_script = basename($_SERVER['SCRIPT_NAME']);
if ($current_script === 'setup.php') {
    return;
}

// Include configuration
require_once __DIR__ . '/config.php';

// Create database connection
try {
    // Try connecting with mysqli
    $conn = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
        // If localhost fails, try 127.0.0.1 (common fix for shared hosting)
        if (DB_HOST === 'localhost') {
            $conn = @new mysqli('127.0.0.1', DB_USER, DB_PASS, DB_NAME);
            if ($conn->connect_error) {
                throw new Exception("Could not connect to database. Please check your database credentials in includes/config.php");
            }
        } else {
            throw new Exception("Database connection failed: " . $conn->connect_error);
        }
    }
    
    // Set charset to utf8mb4 for proper Unicode support
    if (!$conn->set_charset("utf8mb4")) {
        error_log("Failed to set charset: " . $conn->error);
    }
    
} catch (Exception $e) {
    // More helpful error message
    $error_msg = "Database connection error: " . $e->getMessage();
    
    // Add helpful hints for common issues
    if (strpos($e->getMessage(), 'No such file or directory') !== false) {
        $error_msg .= "<br><br><strong>Troubleshooting:</strong><br>";
        $error_msg .= "1. Check your database credentials in includes/config.php<br>";
        $error_msg .= "2. On shared hosting, you may need to use '127.0.0.1' instead of 'localhost'<br>";
        $error_msg .= "3. Verify your database exists in your hosting control panel<br>";
        $error_msg .= "4. Make sure your database user has proper permissions<br>";
    }
    
    die($error_msg);
}

/**
 * Function to safely close database connection
 */
if (!function_exists('closeConnection')) {
    function closeConnection() {
        global $conn;
        if ($conn) {
            $conn->close();
        }
    }
}

/**
 * Function to execute prepared statements safely
 */
if (!function_exists('executeQuery')) {
    function executeQuery($sql, $params = [], $types = '') {
        global $conn;
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            return ['success' => false, 'error' => $conn->error];
        }
        
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        $result = $stmt->execute();
        if (!$result) {
            return ['success' => false, 'error' => $stmt->error];
        }
        
        $data = $stmt->get_result();
        $stmt->close();
        
        return ['success' => true, 'data' => $data];
    }
}

/**
 * Function to get last insert ID
 */
if (!function_exists('getLastInsertId')) {
    function getLastInsertId() {
        global $conn;
        return $conn->insert_id;
    }
}

/**
 * Function to escape strings for SQL queries
 */
if (!function_exists('escapeString')) {
    function escapeString($string) {
        global $conn;
        return $conn->real_escape_string($string);
    }
}
?>