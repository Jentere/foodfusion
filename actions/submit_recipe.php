<?php
/**
 * Submit Recipe Action - FoodFusion
 * Handles recipe submission to community cookbook
 */
session_start();
require_once('../includes/paths.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

require_once('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $errors = [];
    $user_id = $_SESSION['user_id'];
    
    // Validate and sanitize inputs
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $content = isset($_POST['content']) ? trim($_POST['content']) : '';
    $category = isset($_POST['category']) ? trim($_POST['category']) : '';
    $difficulty = isset($_POST['difficulty']) ? trim($_POST['difficulty']) : '';
    
    // Validation
    if (empty($title) || strlen($title) < 5) {
        $errors[] = 'Recipe title must be at least 5 characters long.';
    }
    
    if (empty($content) || strlen($content) < 50) {
        $errors[] = 'Recipe instructions must be at least 50 characters long.';
    }
    
    if (empty($category)) {
        $errors[] = 'Please select a category.';
    }
    
    if (empty($difficulty)) {
        $errors[] = 'Please select a difficulty level.';
    }
    
    // Handle image upload
    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        $file_type = $_FILES['image']['type'];
        $file_size = $_FILES['image']['size'];
        
        if (!in_array($file_type, $allowed_types)) {
            $errors[] = 'Invalid image type. Only JPG, PNG, GIF, and WEBP are allowed.';
        }
        
        if ($file_size > $max_size) {
            $errors[] = 'Image size must be less than 5MB.';
        }
        
        if (empty($errors)) {
            $upload_dir = '../uploads/';
            
            // Create uploads directory if it doesn't exist
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $new_filename = uniqid() . '_' . time() . '.' . $file_extension;
            $upload_path = $upload_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                $image_path = 'uploads/' . $new_filename;
            } else {
                $errors[] = 'Failed to upload image.';
            }
        }
    }
    
    // If there are errors, redirect back with error message
    if (!empty($errors)) {
        $_SESSION['recipe_errors'] = $errors;
        $_SESSION['recipe_form_data'] = $_POST;
        header('Location: ../community.php#shareRecipeForm');
        exit();
    }
    
    // Insert recipe into database
    try {
        $stmt = $conn->prepare("INSERT INTO community_posts (user_id, title, content, category, difficulty, image_path, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        
        if (!$stmt) {
            throw new Exception('Database error: ' . $conn->error);
        }
        
        $stmt->bind_param("isssss", $user_id, $title, $content, $category, $difficulty, $image_path);
        
        if ($stmt->execute()) {
            $_SESSION['recipe_success'] = 'Your recipe has been shared successfully!';
            $stmt->close();
            header('Location: ../community.php?success=1');
            exit();
        } else {
            throw new Exception('Failed to submit recipe: ' . $stmt->error);
        }
        
    } catch (Exception $e) {
        error_log('Recipe submission error: ' . $e->getMessage());
        $_SESSION['recipe_errors'] = ['An error occurred while submitting your recipe. Please try again.'];
        header('Location: ../community.php#shareRecipeForm');
        exit();
    }
    
} else {
    header('Location: ../community.php');
    exit();
}
?>
