<?php
/**
 * Submit Comment Action
 */
session_start();
require_once('../includes/paths.php');
require_once('../includes/db.php');

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to comment']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

$user_id = $_SESSION['user_id'];
$post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
$comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

// Validation
if ($post_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid post ID']);
    exit();
}

if (empty($comment)) {
    echo json_encode(['success' => false, 'message' => 'Comment cannot be empty']);
    exit();
}

if (strlen($comment) < 2) {
    echo json_encode(['success' => false, 'message' => 'Comment must be at least 2 characters']);
    exit();
}

if (strlen($comment) > 1000) {
    echo json_encode(['success' => false, 'message' => 'Comment is too long (max 1000 characters)']);
    exit();
}

// Check if post exists
$check_stmt = $conn->prepare("SELECT id FROM community_posts WHERE id = ?");
$check_stmt->bind_param("i", $post_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Post not found']);
    exit();
}
$check_stmt->close();

// Insert comment
try {
    $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, content, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iis", $post_id, $user_id, $comment);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Comment posted successfully'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to post comment'
        ]);
    }
    
    $stmt->close();
} catch (Exception $e) {
    error_log('Comment submission error: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred'
    ]);
}
?>
