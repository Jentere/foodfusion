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
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }
    
    // Set charset to utf8mb4 for proper Unicode support
    $conn->set_charset("utf8mb4");
    
} catch (Exception $e) {
    die("Database connection error: " . $e->getMessage());
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