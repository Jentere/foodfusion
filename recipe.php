<?php 
require_once('includes/paths.php');
include('includes/header.php'); 
include('includes/db.php');

// Get recipe ID from URL
$recipe_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($recipe_id <= 0) {
    redirect('recipes.php');
    exit;
}

// Update view count (with error handling)
try {
    $updateViews = $conn->prepare("UPDATE recipes SET views = views + 1 WHERE recipe_id = ?");
    if ($updateViews) {
        $updateViews->bind_param("i", $recipe_id);
        $updateViews->execute();
    }
} catch (Exception $e) {
    // Views column might not exist yet - silently continue
    error_log("Views update failed: " . $e->getMessage());
}

// Get recipe details
$stmt = $conn->prepare("SELECT * FROM recipes WHERE recipe_id = ?");
$stmt->bind_param("i", $recipe_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    redirect('recipes.php');
    exit;
}

$recipe = $result->fetch_assoc();

// Get user info if recipe has a user_id
$author = null;
if (!empty($recipe['user_id'])) {
    $userStmt = $conn->prepare("SELECT username, email FROM users WHERE user_id = ?");
    $userStmt->bind_param("i", $recipe['user_id']);
    $userStmt->execute();
    $userResult = $userStmt->get_result();
    if ($userResult->num_rows > 0) {
        $author = $userResult->fetch_assoc();
    }
}
?>

<link rel="stylesheet" href="<?php echo url('assets/css/recipe-view.css'); ?>">

<!-- Recipe Hero Section -->
<div class="recipe-hero">
    <div class="recipe-hero-overlay"></div>
    <div class="recipe-hero-content">
        <div class="recipe-breadcrumb">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <span class="separator">/</span>
            <a href="recipes.php">Recipes</a>
            <span class="separator">/</span>
            <span class="current"><?php echo htmlspecialchars($recipe['title']); ?></span>
        </div>
        
        <h1 class="recipe-title"><?php echo htmlspecialchars($recipe['title']); ?></h1>
        
        <?php if (!empty($recipe['description'])): ?>
            <p class="recipe-subtitle"><?php echo htmlspecialchars($recipe['description']); ?></p>
        <?php endif; ?>
        
        <div class="recipe-meta-bar">
            <?php if (!empty($recipe['cuisine_type'])): ?>
                <div class="meta-badge">
                    <i class="fas fa-globe-americas"></i>
                    <span><?php echo htmlspecialchars($recipe['cuisine_type']); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($recipe['dietary_preference'])): ?>
                <div class="meta-badge">
                    <i class="fas fa-leaf"></i>
                    <span><?php echo htmlspecialchars($recipe['dietary_preference']); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($recipe['difficulty'])): ?>
                <div class="meta-badge">
                    <i class="fas fa-chart-line"></i>
                    <span><?php echo htmlspecialchars($recipe['difficulty']); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if (isset($recipe['views'])): ?>
                <div class="meta-badge">
                    <i class="fas fa-eye"></i>
                    <span><?php echo number_format($recipe['views']); ?> views</span>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Recipe Content -->
<div class="recipe-content-wrapper">
    <div class="recipe-container">
        
        <!-- Main Content -->
        <div class="recipe-main">
            
            <!-- Recipe Image -->
            <div class="recipe-image-section">
                <?php 
                $imagePath = !empty($recipe['image']) ? 'assets/images/' . $recipe['image'] : 'assets/images/recipe.jpg';
                ?>
                <img src="<?php echo url(htmlspecialchars($imagePath)); ?>" 
                     alt="<?php echo htmlspecialchars($recipe['title']); ?>"
                     class="recipe-main-image">
            </div>

            <!-- Quick Info Cards -->
            <div class="recipe-quick-info">
                <?php if (!empty($recipe['prep_time'])): ?>
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="info-details">
                            <span class="info-label">Prep Time</span>
                            <span class="info-value"><?php echo htmlspecialchars($recipe['prep_time']); ?></span>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($recipe['cook_time'])): ?>
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-fire"></i>
                        </div>
                        <div class="info-details">
                            <span class="info-label">Cook Time</span>
                            <span class="info-value"><?php echo htmlspecialchars($recipe['cook_time']); ?></span>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($recipe['servings'])): ?>
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="info-details">
                            <span class="info-label">Servings</span>
                            <span class="info-value"><?php echo htmlspecialchars($recipe['servings']); ?></span>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($recipe['difficulty'])): ?>
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-signal"></i>
                        </div>
                        <div class="info-details">
                            <span class="info-label">Difficulty</span>
                            <span class="info-value"><?php echo htmlspecialchars($recipe['difficulty']); ?></span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Ingredients Section -->
            <?php if (!empty($recipe['ingredients'])): ?>
                <div class="recipe-section">
                    <h2 class="section-title">
                        <i class="fas fa-list-ul"></i>
                        Ingredients
                    </h2>
                    <div class="ingredients-list">
                        <?php 
                        $ingredients = explode("\n", $recipe['ingredients']);
                        foreach ($ingredients as $ingredient): 
                            $ingredient = trim($ingredient);
                            if (!empty($ingredient)):
                        ?>
                            <div class="ingredient-item">
                                <i class="fas fa-check-circle"></i>
                                <span><?php echo htmlspecialchars($ingredient); ?></span>
                            </div>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Instructions Section -->
            <?php if (!empty($recipe['instructions'])): ?>
                <div class="recipe-section">
                    <h2 class="section-title">
                        <i class="fas fa-tasks"></i>
                        Instructions
                    </h2>
                    <div class="instructions-list">
                        <?php 
                        $instructions = explode("\n", $recipe['instructions']);
                        $step = 1;
                        foreach ($instructions as $instruction): 
                            $instruction = trim($instruction);
                            if (!empty($instruction)):
                        ?>
                            <div class="instruction-step">
                                <div class="step-number"><?php echo $step; ?></div>
                                <div class="step-content">
                                    <p><?php echo nl2br(htmlspecialchars($instruction)); ?></p>
                                </div>
                            </div>
                        <?php 
                            $step++;
                            endif;
                        endforeach; 
                        ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Nutritional Info -->
            <?php if (!empty($recipe['nutritional_info'])): ?>
                <div class="recipe-section">
                    <h2 class="section-title">
                        <i class="fas fa-heartbeat"></i>
                        Nutritional Information
                    </h2>
                    <div class="nutrition-info">
                        <p><?php echo nl2br(htmlspecialchars($recipe['nutritional_info'])); ?></p>
                    </div>
                </div>
            <?php endif; ?>

        </div>

        <!-- Sidebar -->
        <aside class="recipe-sidebar">
            
            <!-- Author Card -->
            <?php if ($author): ?>
                <div class="sidebar-card author-card">
                    <h3 class="card-title">Recipe by</h3>
                    <div class="author-info">
                        <div class="author-avatar">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div class="author-details">
                            <h4><?php echo htmlspecialchars($author['username']); ?></h4>
                            <p class="author-role">Home Chef</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Share Card -->
            <div class="sidebar-card share-card">
                <h3 class="card-title">Share Recipe</h3>
                <div class="share-buttons">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" 
                       target="_blank" class="share-btn facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>&text=<?php echo urlencode($recipe['title']); ?>" 
                       target="_blank" class="share-btn twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://pinterest.com/pin/create/button/?url=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>&description=<?php echo urlencode($recipe['title']); ?>" 
                       target="_blank" class="share-btn pinterest">
                        <i class="fab fa-pinterest-p"></i>
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode($recipe['title']); ?>&body=<?php echo urlencode('Check out this recipe: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" 
                       class="share-btn email">
                        <i class="fas fa-envelope"></i>
                    </a>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="sidebar-card action-card">
                <button class="action-btn print-btn" onclick="window.print()">
                    <i class="fas fa-print"></i>
                    <span>Print Recipe</span>
                </button>
                <button class="action-btn save-btn">
                    <i class="fas fa-bookmark"></i>
                    <span>Save Recipe</span>
                </button>
            </div>

            <!-- Related Info -->
            <div class="sidebar-card info-card">
                <h3 class="card-title">Recipe Details</h3>
                <div class="detail-list">
                    <div class="detail-item">
                        <span class="detail-label">Category:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($recipe['cuisine_type'] ?? 'General'); ?></span>
                    </div>
                    <?php if (!empty($recipe['dietary_preference'])): ?>
                        <div class="detail-item">
                            <span class="detail-label">Diet:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($recipe['dietary_preference']); ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="detail-item">
                        <span class="detail-label">Posted:</span>
                        <span class="detail-value"><?php echo date('M d, Y', strtotime($recipe['created_at'])); ?></span>
                    </div>
                </div>
            </div>

        </aside>

    </div>
</div>

<!-- Back to Recipes -->
<div class="back-to-recipes">
    <a href="recipes.php" class="back-btn">
        <i class="fas fa-arrow-left"></i>
        <span>Back to All Recipes</span>
    </a>
</div>

<?php include('includes/footer.php'); ?>
