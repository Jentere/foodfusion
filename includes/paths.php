<?php
/**
 * Path Helper System
 * 
 * Provides centralized path management for the FoodFusion application.
 * Automatically detects the deployment environment and generates correct URLs
 * for assets, pages, and resources.
 */

// Prevent direct access
if (!defined('INCLUDED')) {
    define('INCLUDED', true);
}

/**
 * Detect if the application is running on localhost
 * 
 * @return bool True if running on localhost, false otherwise
 */
function isLocalhost(): bool {
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    return in_array($host, ['localhost', '127.0.0.1', 'localhost:8080', '127.0.0.1:8080']);
}

/**
 * Get the base path for the application
 * 
 * @return string The base path (e.g., '/foodfusion/' or '/')
 */
function getBasePath(): string {
    if (isLocalhost()) {
        return '/foodfusion/';
    }
    return '/';
}

/**
 * Get the full site URL including protocol and domain
 * 
 * @return string The full site URL
 */
function getSiteUrl(): string {
    if (isLocalhost()) {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return $protocol . '://' . $host . '/foodfusion';
    }
    
    // Production URL
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'jameschinyamafoodfusion.ct.ws';
    return $protocol . '://' . $host;
}

/**
 * Generate a URL for an asset or page
 * 
 * Security: Prevents path traversal attacks by removing ../ sequences
 * 
 * @param string $path The relative path (e.g., 'assets/css/style.css')
 * @return string The full URL path
 */
function url(string $path): string {
    // Security: Remove any path traversal attempts
    $path = str_replace(['../', '..\\', '\\'], '', $path);
    
    // Remove leading slash if present to avoid double slashes
    $path = ltrim($path, '/');
    
    // Return base path + cleaned path
    return getBasePath() . $path;
}

/**
 * Generate an absolute file system path for an asset
 * 
 * @param string $path The relative path
 * @return string The absolute file system path
 */
function assetPath(string $path): string {
    // Security: Remove any path traversal attempts
    $path = str_replace(['../', '..\\', '\\'], '', $path);
    
    // Remove leading slash if present
    $path = ltrim($path, '/');
    
    // Return absolute path from document root
    return __DIR__ . '/../' . $path;
}

/**
 * Check if an asset file exists
 * 
 * @param string $path The relative path to check
 * @return bool True if file exists, false otherwise
 */
function assetExists(string $path): bool {
    return file_exists(assetPath($path));
}

/**
 * Generate a URL with query parameters
 * 
 * @param string $path The relative path
 * @param array $params Associative array of query parameters
 * @return string The full URL with query string
 */
function urlWithParams(string $path, array $params = []): string {
    $url = url($path);
    
    if (!empty($params)) {
        $queryString = http_build_query($params);
        $url .= '?' . $queryString;
    }
    
    return $url;
}

// Define constants for easy access
define('BASE_PATH', getBasePath());
define('SITE_URL', getSiteUrl());

/**
 * Redirect to a URL within the application
 * 
 * @param string $path The relative path to redirect to
 * @param int $statusCode HTTP status code (default: 302)
 * @return void
 */
function redirect(string $path, int $statusCode = 302): void {
    $url = url($path);
    header('Location: ' . $url, true, $statusCode);
    exit();
}

/**
 * Redirect to an external URL
 * 
 * @param string $url The full external URL
 * @param int $statusCode HTTP status code (default: 302)
 * @return void
 */
function redirectExternal(string $url, int $statusCode = 302): void {
    header('Location: ' . $url, true, $statusCode);
    exit();
}
