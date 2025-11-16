<?php
/**
 * Google OAuth Callback Handler
 * This file handles the response from Google after user authentication
 */

session_start();
require_once 'google-config.php';
require_once '../includes/db.php';
require_once '../includes/paths.php';

// Check for errors
if (isset($_GET['error'])) {
    $_SESSION['error_message'] = 'Google authentication failed: ' . htmlspecialchars($_GET['error']);
    redirect('index.php');
}

// Verify state token for CSRF protection
if (!isset($_GET['state']) || $_GET['state'] !== $_SESSION['google_oauth_state']) {
    $_SESSION['error_message'] = 'Invalid state parameter. Please try again.';
    redirect('index.php');
}

// Get authorization code
if (!isset($_GET['code'])) {
    $_SESSION['error_message'] = 'Authorization code not received.';
    redirect('index.php');
}

$code = $_GET['code'];

// Exchange authorization code for access token
$tokenParams = [
    'code' => $code,
    'client_id' => GOOGLE_CLIENT_ID,
    'client_secret' => GOOGLE_CLIENT_SECRET,
    'redirect_uri' => GOOGLE_REDIRECT_URI,
    'grant_type' => 'authorization_code'
];

$ch = curl_init(GOOGLE_TOKEN_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($tokenParams));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For local development only

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    $_SESSION['error_message'] = 'Failed to obtain access token from Google.';
    redirect('index.php');
}

$tokenData = json_decode($response, true);

if (!isset($tokenData['access_token'])) {
    $_SESSION['error_message'] = 'Access token not found in response.';
    redirect('index.php');
}

$accessToken = $tokenData['access_token'];

// Get user information from Google
$ch = curl_init(GOOGLE_USER_INFO_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $accessToken
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For local development only

$userInfoResponse = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    $_SESSION['error_message'] = 'Failed to retrieve user information from Google.';
    redirect('index.php');
}

$userInfo = json_decode($userInfoResponse, true);

if (!isset($userInfo['email'])) {
    $_SESSION['error_message'] = 'Email not provided by Google.';
    redirect('index.php');
}

// Extract user information
$googleId = $userInfo['id'];
$email = $userInfo['email'];
$firstName = isset($userInfo['given_name']) ? $userInfo['given_name'] : '';
$lastName = isset($userInfo['family_name']) ? $userInfo['family_name'] : '';
$profilePicture = isset($userInfo['picture']) ? $userInfo['picture'] : null;
$emailVerified = isset($userInfo['verified_email']) ? $userInfo['verified_email'] : false;

// Check if user already exists
$stmt = $conn->prepare("SELECT user_id, first_name, last_name FROM users WHERE email = ? OR google_id = ?");
$stmt->bind_param("ss", $email, $googleId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // User exists - log them in
    $user = $result->fetch_assoc();
    
    // Update Google ID if not set
    if (empty($user['google_id'])) {
        $updateStmt = $conn->prepare("UPDATE users SET google_id = ?, profile_picture = ? WHERE user_id = ?");
        $updateStmt->bind_param("ssi", $googleId, $profilePicture, $user['user_id']);
        $updateStmt->execute();
        $updateStmt->close();
    }
    
    // Set session variables
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['user_email'] = $email;
    $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
    $_SESSION['login_method'] = 'google';
    $_SESSION['success_message'] = 'Welcome back, ' . htmlspecialchars($user['first_name']) . '!';
    
    $stmt->close();
    redirect('index.php');
    
} else {
    // New user - create account
    $stmt->close();
    
    // Generate a random password (user won't need it for Google login)
    $randomPassword = bin2hex(random_bytes(16));
    $hashedPassword = password_hash($randomPassword, PASSWORD_DEFAULT);
    
    // Insert new user
    $insertStmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, google_id, profile_picture, email_verified, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    $insertStmt->bind_param("ssssssi", $firstName, $lastName, $email, $hashedPassword, $googleId, $profilePicture, $emailVerified);
    
    if ($insertStmt->execute()) {
        $userId = $insertStmt->insert_id;
        
        // Set session variables
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_name'] = $firstName . ' ' . $lastName;
        $_SESSION['login_method'] = 'google';
        $_SESSION['success_message'] = 'Welcome to FoodFusion, ' . htmlspecialchars($firstName) . '!';
        
        $insertStmt->close();
        redirect('index.php');
        
    } else {
        $_SESSION['error_message'] = 'Failed to create account. Please try again.';
        $insertStmt->close();
        redirect('index.php');
    }
}

$conn->close();
?>
