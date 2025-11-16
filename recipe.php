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

// Get recipe details with ratings
$user_id = $_SESSION['user_id'] ?? 0;
$stmt = $conn->prepare("SELECT r.*, 
                        COALESCE(AVG(rt.rating), 0) as avg_rating,
                        COUNT(DISTINCT rt.rating_id) as rating_count,
                        MAX(CASE WHEN rt.user_id = ? THEN rt.rating END) as user_rating
                        FROM recipes r
                        LEFT JOIN recipe_ratings rt ON r.recipe_id = rt.recipe_id
                        WHERE r.recipe_id = ?
                        GROUP BY r.recipe_id");
$stmt->bind_param("ii", $user_id, $recipe_id);
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

            <!-- Rating Section -->
            <div class="recipe-rating-section">
                <div class="rating-display">
                    <div class="rating-summary">
                        <div class="rating-number"><?php echo round($recipe['avg_rating'], 1); ?></div>
                        <div class="rating-stars-large">
                            <?php 
                            $avg_rating = $recipe['avg_rating'];
                            $fullStars = floor($avg_rating);
                            $hasHalfStar = ($avg_rating - $fullStars) >= 0.5;
                            
                            for ($i = 1; $i <= 5; $i++):
                                if ($i <= $fullStars): ?>
                                    <i class="fas fa-star"></i>
                                <?php elseif ($i == $fullStars + 1 && $hasHalfStar): ?>
                                    <i class="fas fa-star-half-alt"></i>
                                <?php else: ?>
                                    <i class="far fa-star"></i>
                                <?php endif;
                            endfor; ?>
                        </div>
                        <div class="rating-count"><?php echo $recipe['rating_count']; ?> <?php echo $recipe['rating_count'] == 1 ? 'rating' : 'ratings'; ?></div>
                    </div>
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="rating-input">
                            <p class="rating-prompt">Rate this recipe:</p>
                            <div class="star-rating" id="starRating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="far fa-star rating-star" 
                                       data-rating="<?php echo $i; ?>"
                                       onclick="rateRecipe(<?php echo $recipe_id; ?>, <?php echo $i; ?>)"></i>
                                <?php endfor; ?>
                            </div>
                            <?php if (!empty($recipe['user_rating'])): ?>
                                <p class="user-rating-text">Your rating: <?php echo $recipe['user_rating']; ?> stars</p>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="rating-login-prompt">
                            <a href="<?php echo url('auth/login.php'); ?>" class="btn-login-rate">
                                <i class="fas fa-sign-in-alt"></i> Login to rate
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
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

<script>
// Rating functionality
function rateRecipe(recipeId, rating) {
    const basePath = window.BASE_PATH || '/';
    
    fetch(basePath + 'actions/rate_recipe.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `recipe_id=${recipeId}&rating=${rating}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the rating display
            document.querySelector('.rating-number').textContent = data.avg_rating;
            document.querySelector('.rating-count').textContent = `${data.rating_count} ${data.rating_count === 1 ? 'rating' : 'ratings'}`;
            
            // Update the stars display
            updateStarsDisplay(data.avg_rating);
            
            // Update user rating stars
            updateUserRatingStars(rating);
            
            // Show success message
            showRatingMessage('success', data.message);
        } else {
            showRatingMessage('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showRatingMessage('error', 'An error occurred while submitting your rating');
    });
}

// Update the large stars display
function updateStarsDisplay(avgRating) {
    const starsContainer = document.querySelector('.rating-stars-large');
    const fullStars = Math.floor(avgRating);
    const hasHalfStar = (avgRating - fullStars) >= 0.5;
    
    let starsHTML = '';
    for (let i = 1; i <= 5; i++) {
        if (i <= fullStars) {
            starsHTML += '<i class="fas fa-star"></i>';
        } else if (i === fullStars + 1 && hasHalfStar) {
            starsHTML += '<i class="fas fa-star-half-alt"></i>';
        } else {
            starsHTML += '<i class="far fa-star"></i>';
        }
    }
    starsContainer.innerHTML = starsHTML;
}

// Update user rating stars (interactive stars)
function updateUserRatingStars(rating) {
    const stars = document.querySelectorAll('.rating-star');
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.remove('far');
            star.classList.add('fas');
        } else {
            star.classList.remove('fas');
            star.classList.add('far');
        }
    });
    
    // Update or create user rating text
    let userRatingText = document.querySelector('.user-rating-text');
    if (!userRatingText) {
        userRatingText = document.createElement('p');
        userRatingText.className = 'user-rating-text';
        document.querySelector('.rating-input').appendChild(userRatingText);
    }
    userRatingText.textContent = `Your rating: ${rating} stars`;
}

// Show rating message
function showRatingMessage(type, message) {
    // Remove existing message if any
    const existingMsg = document.querySelector('.rating-message');
    if (existingMsg) {
        existingMsg.remove();
    }
    
    // Create new message
    const messageDiv = document.createElement('div');
    messageDiv.className = `rating-message rating-message-${type}`;
    messageDiv.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        <span>${message}</span>
    `;
    
    // Insert after rating input
    const ratingInput = document.querySelector('.rating-input');
    if (ratingInput) {
        ratingInput.parentNode.insertBefore(messageDiv, ratingInput.nextSibling);
    }
    
    // Auto-remove after 3 seconds
    setTimeout(() => {
        messageDiv.style.animation = 'fadeOut 0.3s ease-out';
        setTimeout(() => messageDiv.remove(), 300);
    }, 3000);
}

// Hover effect for rating stars
document.addEventListener('DOMContentLoaded', function() {
    const ratingStars = document.querySelectorAll('.rating-star');
    
    ratingStars.forEach((star, index) => {
        star.addEventListener('mouseenter', function() {
            highlightStars(index + 1);
        });
        
        star.addEventListener('mouseleave', function() {
            resetStars();
        });
    });
    
    function highlightStars(count) {
        ratingStars.forEach((star, index) => {
            if (index < count) {
                star.classList.remove('far');
                star.classList.add('fas');
                star.style.color = '#ffc107';
            } else {
                star.classList.remove('fas');
                star.classList.add('far');
                star.style.color = '';
            }
        });
    }
    
    function resetStars() {
        // Reset to current user rating or empty
        const userRatingText = document.querySelector('.user-rating-text');
        if (userRatingText) {
            const currentRating = parseInt(userRatingText.textContent.match(/\d+/)[0]);
            updateUserRatingStars(currentRating);
        } else {
            ratingStars.forEach(star => {
                star.classList.remove('fas');
                star.classList.add('far');
                star.style.color = '';
            });
        }
    }
});
</script>

<style>
/* Rating message styles */
.rating-message {
    margin-top: 1rem;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    animation: fadeIn 0.3s ease-in;
}

.rating-message-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.rating-message-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.rating-star {
    cursor: pointer;
    font-size: 1.5rem;
    color: #ddd;
    transition: all 0.2s ease;
}

.rating-star:hover {
    transform: scale(1.2);
}

.rating-star.fas {
    color: #ffc107;
}

.user-rating-text {
    margin-top: 0.5rem;
    font-size: 0.9rem;
    color: #666;
    font-style: italic;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeOut {
    from {
        opacity: 1;
        transform: translateY(0);
    }
    to {
        opacity: 0;
        transform: translateY(-10px);
    }
}
</style>

<?php include('includes/footer.php'); ?>
