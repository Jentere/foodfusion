<?php
/**
 * Rate Recipe Action
 */
session_start();
require_once('../includes/paths.php');
require_once('../includes/db.php');

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to rate recipes']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

$user_id = $_SESSION['user_id'];
$recipe_id = isset($_POST['recipe_id']) ? (int)$_POST['recipe_id'] : 0;
$rating = isset($_POST['rating']) ? (float)$_POST['rating'] : 0;

// Validation
if ($recipe_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid recipe ID']);
    exit();
}

if ($rating < 1 || $rating > 5) {
    echo json_encode(['success' => false, 'message' => 'Rating must be between 1 and 5']);
    exit();
}

// Check if recipe exists
$check_stmt = $conn->prepare("SELECT recipe_id FROM recipes WHERE recipe_id = ?");
$check_stmt->bind_param("i", $recipe_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Recipe not found']);
    exit();
}
$check_stmt->close();

// Check if user already rated this recipe
$existing_stmt = $conn->prepare("SELECT rating_id FROM recipe_ratings WHERE recipe_id = ? AND user_id = ?");
$existing_stmt->bind_param("ii", $recipe_id, $user_id);
$existing_stmt->execute();
$existing_result = $existing_stmt->get_result();

try {
    if ($existing_result->num_rows > 0) {
        // Update existing rating
        $stmt = $conn->prepare("UPDATE recipe_ratings SET rating = ? WHERE recipe_id = ? AND user_id = ?");
        $stmt->bind_param("dii", $rating, $recipe_id, $user_id);
        $message = 'Rating updated successfully';
    } else {
        // Insert new rating
        $stmt = $conn->prepare("INSERT INTO recipe_ratings (recipe_id, user_id, rating, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iid", $recipe_id, $user_id, $rating);
        $message = 'Rating submitted successfully';
    }
    
    if ($stmt->execute()) {
        // Get new average rating
        $avg_stmt = $conn->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as rating_count FROM recipe_ratings WHERE recipe_id = ?");
        $avg_stmt->bind_param("i", $recipe_id);
        $avg_stmt->execute();
        $avg_result = $avg_stmt->get_result();
        $avg_data = $avg_result->fetch_assoc();
        
        echo json_encode([
            'success' => true,
            'message' => $message,
            'avg_rating' => round($avg_data['avg_rating'], 1),
            'rating_count' => $avg_data['rating_count']
        ]);
        
        $avg_stmt->close();
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to submit rating'
        ]);
    }
    
    $stmt->close();
} catch (Exception $e) {
    error_log('Rating submission error: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred'
    ]);
}

$existing_stmt->close();
?>
