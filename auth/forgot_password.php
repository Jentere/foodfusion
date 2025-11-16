<!-- filepath: c:\xampp\htdocs\foodfusion\auth\forgot_password.php -->
<?php
include('../includes/db.php');
$response = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);

    // Check if the email exists
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Generate a reset token
        $token = bin2hex(random_bytes(16));
        $stmt->bind_result($user_id);
        $stmt->fetch();

        // Save the token in the database
        $stmtInsert = $conn->prepare("INSERT INTO password_resets (user_id, token, created_at) VALUES (?, ?, NOW())");
        $stmtInsert->bind_param("is", $user_id, $token);
        $stmtInsert->execute();

        // Generate the reset link using SITE_URL
        require_once('../includes/paths.php');
        $resetLink = SITE_URL . '/auth/reset_password.php?token=' . $token;
        $response = "<div class='success-message'>
                        <p><strong>Success!</strong> A password reset link has been sent to your email:</p>
                        <a href='$resetLink' class='reset-link'>Click here to reset your password</a>
                    </div>";
    } else {
        $response = "<div class='error-message'>Email not found. Please try again.</div>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <?php require_once('../includes/paths.php'); ?>
    <link rel="stylesheet" href="<?php echo url('assets/css/style.css'); ?>">
</head>
<body>
    <div class="forgot-password-container">
        <h1>Forgot Password</h1>
        <p class="instructions">Enter your email address below, and we'll send you a link to reset your password.</p>
        <form method="POST" class="forgot-password-form">
            <input type="email" name="email" placeholder="Email Address" required>
            <button type="submit" class="btn-submit">Send Reset Link</button>
        </form>
        <?php if (!empty($response)): ?>
            <?= $response ?>
        <?php endif; ?>
    </div>
</body>
</html>