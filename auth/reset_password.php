<!-- filepath: c:\xampp\htdocs\foodfusion\auth\reset_password.php -->
<?php
include('../includes/db.php');

$response = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Verify the token
    $stmt = $conn->prepare("SELECT user_id FROM password_resets WHERE token = ? AND created_at > NOW() - INTERVAL 1 HOUR");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id);
        $stmt->fetch();

        // Update the user's password
        $stmtUpdate = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
        $stmtUpdate->bind_param("si", $newPassword, $user_id);
        $stmtUpdate->execute();

        // Delete the token
        $stmtDelete = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
        $stmtDelete->bind_param("s", $token);
        $stmtDelete->execute();

        require_once('../includes/paths.php');
        $loginUrl = url('auth/login.php');
        $response = "<div class='success-message'>Password reset successful. You can now <a href='$loginUrl'>log in</a>.</div>";
    } else {
        $response = "<div class='error-message'>Invalid or expired token. Please try again.</div>";
    }

    $stmt->close();
    $conn->close();
} else {
    // Display the reset password form
    if (isset($_GET['token'])) {
        $token = $_GET['token'];
        $response = '<form method="POST" class="reset-password-form">
                        <input type="hidden" name="token" value="' . htmlspecialchars($token) . '">
                        <input type="password" name="password" placeholder="New Password" required>
                        <button type="submit" class="btn-submit">Reset Password</button>
                     </form>';
    } else {
        $response = "<div class='error-message'>Invalid request.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <?php require_once('../includes/paths.php'); ?>
    <link rel="stylesheet" href="<?php echo url('assets/css/style.css'); ?>">
</head>
<body>
    <div class="reset-password-container">
        <h1>Reset Password</h1>
        <p class="instructions">Enter your new password below to reset your account password.</p>
        <?= $response ?>
    </div>
</body>
</html>