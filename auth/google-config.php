<?php
/**
 * Google OAuth Configuration
 * 
 * To set up Google OAuth:
 * 1. Go to https://console.cloud.google.com/
 * 2. Create a new project or select existing one
 * 3. Enable Google+ API
 * 4. Go to Credentials > Create Credentials > OAuth 2.0 Client ID
 * 5. Set Authorized JavaScript origins: http://localhost, http://yourdomain.com
 * 6. Set Authorized redirect URIs: http://localhost/foodfusion/auth/google-callback.php
 * 7. Copy your Client ID and Client Secret below
 */

// Google OAuth Configuration
define('GOOGLE_CLIENT_ID', 'YOUR_GOOGLE_CLIENT_ID_HERE');
define('GOOGLE_CLIENT_SECRET', 'YOUR_GOOGLE_CLIENT_SECRET_HERE');

// Auto-detect redirect URI based on current environment
require_once(__DIR__ . '/../includes/paths.php');
define('GOOGLE_REDIRECT_URI', SITE_URL . '/auth/google-callback.php');

// Google OAuth URLs
define('GOOGLE_AUTH_URL', 'https://accounts.google.com/o/oauth2/v2/auth');
define('GOOGLE_TOKEN_URL', 'https://oauth2.googleapis.com/token');
define('GOOGLE_USER_INFO_URL', 'https://www.googleapis.com/oauth2/v2/userinfo');

// Scopes
define('GOOGLE_SCOPES', 'email profile');
?>
