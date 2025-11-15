<?php
/**
 * Toggle Like Action - FoodFusion
 * Handles liking/unliking community posts
 */
session_start();
require_once('../includes/paths.php');

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to like posts']);
    exit();
}

require_once('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $user_id = $_SESSION['user_id'];
    $post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
    
    if ($post_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid post ID']);
        exit();
    }
    
    try {
        // Check if user already liked this post
        $check_stmt = $conn->prepare("SELECT id FROM likes WHERE post_id = ? AND user_id = ?");
        $check_stmt->bind_param("ii", $post_id, $user_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Unlike - remove the like
            $delete_stmt = $conn->prepare("DELETE FROM likes WHERE post_id = ? AND user_id = ?");
            $delete_stmt->bind_param("ii", $post_id, $user_id);
            
            if ($delete_stmt->execute()) {
                echo json_encode(['success' => true, 'action' => 'unliked']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to unlike post']);
            }
            $delete_stmt->close();
            
        } else {
            // Like - add the like
            $insert_stmt = $conn->prepare("INSERT INTO likes (post_id, user_id, created_at) VALUES (?, ?, NOW())");
            $insert_stmt->bind_param("ii", $post_id, $user_id);
            
            if ($insert_stmt->execute()) {
                echo json_encode(['success' => true, 'action' => 'liked']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to like post']);
            }
            $insert_stmt->close();
        }
        
        $check_stmt->close();
        
    } catch (Exception $e) {
        error_log('Toggle like error: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'An error occurred']);
    }
    
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
