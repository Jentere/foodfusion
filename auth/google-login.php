<?php
/**
 * Google OAuth Login Initiator
 * This file redirects users to Google's OAuth consent screen
 */

session_start();
require_once 'google-config.php';

// Generate and store state token for CSRF protection
$state = bin2hex(random_bytes(16));
$_SESSION['google_oauth_state'] = $state;

// Build the Google OAuth URL
$params = [
    'client_id' => GOOGLE_CLIENT_ID,
    'redirect_uri' => GOOGLE_REDIRECT_URI,
    'response_type' => 'code',
    'scope' => GOOGLE_SCOPES,
    'state' => $state,
    'access_type' => 'online',
    'prompt' => 'select_account'
];

$authUrl = GOOGLE_AUTH_URL . '?' . http_build_query($params);

// Redirect to Google
header('Location: ' . $authUrl);
exit();
?>
