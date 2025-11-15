<?php 
/**
 * Community Cookbook Page - FoodFusion
 * A collaborative space for sharing recipes, cooking tips, and culinary experiences
 */
session_start();
require_once('includes/paths.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    redirect('auth/login.php');
    exit();
}

include('includes/header.php');
include('includes/db.php');

// Get current user info
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? 'User';

// Handle search and filter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$difficulty = isset($_GET['difficulty']) ? $_GET['difficulty'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// Build query conditions
$conditions = [];
$params = [];
$types = '';

if (!empty($search)) {
    $conditions[] = "(c.title LIKE ? OR c.content LIKE ?)";
    $searchParam = "%$search%";
    $params[] = $searchParam;
    $params[] = $searchParam;
    $types .= "ss";
}

if (!empty($category)) {
    $conditions[] = "c.category = ?";
    $params[] = $category;
    $types .= "s";
}

if (!empty($difficulty)) {
    $conditions[] = "c.difficulty = ?";
    $params[] = $difficulty;
    $types .= "s";
}

$whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";

// Sort options
$sortOptions = [
    'newest' => 'c.created_at DESC',
    'oldest' => 'c.created_at ASC',
    'popular' => 'like_count DESC'
];
$orderBy = $sortOptions[$sort] ?? 'c.created_at DESC';

// Pagination setup
$limit = 9;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Get total count for pagination
$countQuery = "SELECT COUNT(*) AS total FROM community_posts c $whereClause";
$stmt = $conn->prepare($countQuery);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$totalPosts = $stmt->get_result()->fetch_assoc()['total'];
$totalPages = ceil($totalPosts / $limit);
$stmt->close();

// Get posts with user info and counts
$query = "SELECT c.*, 
          u.first_name, 
          u.last_name,
          (SELECT COUNT(*) FROM comments WHERE post_id = c.id) as comment_count,
          (SELECT COUNT(*) FROM likes WHERE post_id = c.id) as like_count,
          (SELECT COUNT(*) FROM likes WHERE post_id = c.id AND user_id = ?) as user_liked
          FROM community_posts c 
          LEFT JOIN users u ON c.user_id = u.user_id 
          $whereClause
          ORDER BY $orderBy
          LIMIT ? OFFSET ?";

$allParams = array_merge([$user_id], $params, [$limit, $offset]);
$allTypes = "i" . $types . "ii";

$stmt = $conn->prepare($query);
$stmt->bind_param($allTypes, ...$allParams);
$stmt->execute();
$result = $stmt->get_result();
?>

<!-- Community Page Specific CSS -->
<link rel="stylesheet" href="<?php echo url('assets/css/community.css'); ?>">

<!-- Hero Section -->
<section class="community-hero">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1>Community Cookbook</h1>
        <p>Share your favorite recipes, cooking tips, and culinary experiences</p>
        <button class="btn-share-recipe" onclick="scrollToForm()">
            <i class="fas fa-plus-circle"></i>
            Share Your Recipe
        </button>
    </div>
</section>

<!-- Main Content -->
<main class="community-main">
    
    <!-- Search and Filter Section -->
    <section class="filter-section">
        <div class="container">
            <form method="GET" action="community.php" class="filter-form">
                <div class="search-wrapper">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" placeholder="Search recipes, ingredients, or cooking tips..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="search-btn">Search</button>
                </div>
                
                <div class="filter-options">
                    <select name="category" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        <option value="breakfast" <?php echo $category === 'breakfast' ? 'selected' : ''; ?>>Breakfast</option>
                        <option value="lunch" <?php echo $category === 'lunch' ? 'selected' : ''; ?>>Lunch</option>
                        <option value="dinner" <?php echo $category === 'dinner' ? 'selected' : ''; ?>>Dinner</option>
                        <option value="dessert" <?php echo $category === 'dessert' ? 'selected' : ''; ?>>Dessert</option>
                        <option value="snack" <?php echo $category === 'snack' ? 'selected' : ''; ?>>Snack</option>
                        <option value="beverage" <?php echo $category === 'beverage' ? 'selected' : ''; ?>>Beverage</option>
                    </select>

                    <select name="difficulty" onchange="this.form.submit()">
                        <option value="">All Difficulty Levels</option>
                        <option value="easy" <?php echo $difficulty === 'easy' ? 'selected' : ''; ?>>Easy</option>
                        <option value="medium" <?php echo $difficulty === 'medium' ? 'selected' : ''; ?>>Medium</option>
                        <option value="hard" <?php echo $difficulty === 'hard' ? 'selected' : ''; ?>>Hard</option>
                    </select>

                    <select name="sort" onchange="this.form.submit()">
                        <option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>Newest First</option>
                        <option value="oldest" <?php echo $sort === 'oldest' ? 'selected' : ''; ?>>Oldest First</option>
                        <option value="popular" <?php echo $sort === 'popular' ? 'selected' : ''; ?>>Most Popular</option>
                    </select>
                </div>
            </form>
        </div>
    </section>

    <!-- Share Recipe Form Section -->
    <section class="share-recipe-section" id="shareRecipeForm">
        <div class="container">
            <div class="form-header">
                <h2><i class="fas fa-utensils"></i> Share Your Recipe</h2>
                <p>Contribute to our community by sharing your favorite recipes and cooking tips</p>
            </div>

            <form method="POST" action="actions/submit_recipe.php" class="recipe-form" enctype="multipart/form-data" id="recipeForm">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="title">
                            <i class="fas fa-heading"></i>
                            Recipe Title <span class="required">*</span>
                        </label>
                        <input type="text" id="title" name="title" placeholder="e.g., Grandma's Chocolate Chip Cookies" required maxlength="100">
                    </div>

                    <div class="form-group">
                        <label for="category">
                            <i class="fas fa-list"></i>
                            Category <span class="required">*</span>
                        </label>
                        <select id="category" name="category" required>
                            <option value="">Select Category</option>
                            <option value="breakfast">Breakfast</option>
                            <option value="lunch">Lunch</option>
                            <option value="dinner">Dinner</option>
                            <option value="dessert">Dessert</option>
                            <option value="snack">Snack</option>
                            <option value="beverage">Beverage</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="difficulty">
                            <i class="fas fa-signal"></i>
                            Difficulty Level <span class="required">*</span>
                        </label>
                        <select id="difficulty" name="difficulty" required>
                            <option value="">Select Difficulty</option>
                            <option value="easy">Easy</option>
                            <option value="medium">Medium</option>
                            <option value="hard">Hard</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="image">
                            <i class="fas fa-image"></i>
                            Recipe Image
                        </label>
                        <div class="file-input-wrapper">
                            <input type="file" id="image" name="image" accept="image/*" onchange="displayFileName(this)">
                            <small>Max size: 5MB (JPG, PNG, GIF)</small>
                            <div class="file-name-display" id="fileNameDisplay">
                                <i class="fas fa-check-circle"></i>
                                <span id="fileName"></span>
                                <button type="button" class="remove-file" onclick="removeFile()" title="Remove file">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="content">
                        <i class="fas fa-file-alt"></i>
                        Recipe Instructions & Tips <span class="required">*</span>
                    </label>
                    <textarea id="content" name="content" rows="10" placeholder="Share your recipe instructions, ingredients, cooking tips, and any special notes..." required></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-paper-plane"></i>
                        <span>Share Recipe</span>
                    </button>
                    <button type="reset" class="reset-btn">
                        <i class="fas fa-redo"></i>
                        <span>Reset Form</span>
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Community Posts Section -->
    <section class="posts-section">
        <div class="container">
            <div class="section-header">
                <h2>Community Recipes</h2>
                <p>Discover amazing recipes shared by our community members</p>
            </div>

            <?php if ($result->num_rows > 0): ?>
                <div class="posts-grid">
                    <?php while($post = $result->fetch_assoc()): ?>
                        <article class="post-card">
                            <?php if (!empty($post['image_path'])): ?>
                                <div class="post-image">
                                    <img src="<?php echo htmlspecialchars($post['image_path']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                                    <div class="post-badges">
                                        <span class="badge badge-category"><?php echo ucfirst(htmlspecialchars($post['category'])); ?></span>
                                        <span class="badge badge-difficulty <?php echo htmlspecialchars($post['difficulty']); ?>">
                                            <?php echo ucfirst(htmlspecialchars($post['difficulty'])); ?>
                                        </span>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="post-image no-image">
                                    <i class="fas fa-utensils"></i>
                                    <div class="post-badges">
                                        <span class="badge badge-category"><?php echo ucfirst(htmlspecialchars($post['category'])); ?></span>
                                        <span class="badge badge-difficulty <?php echo htmlspecialchars($post['difficulty']); ?>">
                                            <?php echo ucfirst(htmlspecialchars($post['difficulty'])); ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="post-content">
                                <div class="post-author">
                                    <div class="author-avatar">
                                        <i class="fas fa-user-circle"></i>
                                    </div>
                                    <div class="author-info">
                                        <span class="author-name"><?php echo htmlspecialchars($post['first_name'] . ' ' . $post['last_name']); ?></span>
                                        <span class="post-date"><?php echo date('M d, Y', strtotime($post['created_at'])); ?></span>
                                    </div>
                                </div>

                                <h3 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h3>
                                <p class="post-excerpt">
                                    <?php 
                                    $content = htmlspecialchars($post['content']);
                                    echo strlen($content) > 150 ? substr($content, 0, 150) . '...' : $content;
                                    ?>
                                </p>
                                
                                <div class="post-footer">
                                    <div class="post-stats">
                                        <span class="stat">
                                            <i class="fas fa-heart"></i>
                                            <?php echo $post['like_count']; ?>
                                        </span>
                                        <span class="stat">
                                            <i class="fas fa-comment"></i>
                                            <?php echo $post['comment_count']; ?>
                                        </span>
                                    </div>
                                    <div class="post-actions">
                                        <button class="action-btn like-btn <?php echo $post['user_liked'] > 0 ? 'liked' : ''; ?>" 
                                                data-post-id="<?php echo $post['id']; ?>"
                                                onclick="toggleLike(<?php echo $post['id']; ?>)">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                        <button class="action-btn comment-btn" 
                                                data-post-id="<?php echo $post['id']; ?>"
                                                onclick="viewPost(<?php echo $post['id']; ?>)">
                                            <i class="fas fa-comment"></i>
                                        </button>
                                        <button class="action-btn share-btn" 
                                                data-post-id="<?php echo $post['id']; ?>"
                                                onclick="sharePost(<?php echo $post['id']; ?>)">
                                            <i class="fas fa-share-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?php echo $page-1; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>&difficulty=<?php echo urlencode($difficulty); ?>&sort=<?php echo urlencode($sort); ?>" class="pagination-btn">
                                <i class="fas fa-chevron-left"></i> Previous
                            </a>
                        <?php endif; ?>

                        <?php 
                        $start = max(1, $page - 2);
                        $end = min($totalPages, $page + 2);
                        
                        for ($i = $start; $i <= $end; $i++): 
                        ?>
                            <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>&difficulty=<?php echo urlencode($difficulty); ?>&sort=<?php echo urlencode($sort); ?>" 
                               class="pagination-btn <?php echo $i == $page ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                            <a href="?page=<?php echo $page+1; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>&difficulty=<?php echo urlencode($difficulty); ?>&sort=<?php echo urlencode($sort); ?>" class="pagination-btn">
                                Next <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="no-posts">
                    <i class="fas fa-utensils"></i>
                    <h3>No Recipes Found</h3>
                    <p>Be the first to share a recipe in this category!</p>
                    <button class="btn-share-recipe" onclick="scrollToForm()">
                        <i class="fas fa-plus-circle"></i>
                        Share Your Recipe
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </section>

</main>

<script>
// Scroll to form
function scrollToForm() {
    document.getElementById('shareRecipeForm').scrollIntoView({ behavior: 'smooth' });
    document.getElementById('title').focus();
}

// Toggle like
function toggleLike(postId) {
    fetch('actions/toggle_like.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'post_id=' + postId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

// View post (placeholder)
function viewPost(postId) {
    alert('View post functionality - Post ID: ' + postId);
    // Implement modal or redirect to post detail page
}

// Share post (placeholder)
function sharePost(postId) {
    if (navigator.share) {
        navigator.share({
            title: 'Check out this recipe!',
            url: window.location.href + '?post=' + postId
        });
    } else {
        alert('Share functionality - Post ID: ' + postId);
    }
}

// Display selected file name
function displayFileName(input) {
    const fileNameDisplay = document.getElementById('fileNameDisplay');
    const fileNameSpan = document.getElementById('fileName');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const fileName = file.name;
        const fileSize = (file.size / 1024 / 1024).toFixed(2); // Convert to MB
        
        fileNameSpan.textContent = `${fileName} (${fileSize} MB)`;
        fileNameDisplay.classList.add('show');
    } else {
        fileNameDisplay.classList.remove('show');
    }
}

// Remove selected file
function removeFile() {
    const fileInput = document.getElementById('image');
    const fileNameDisplay = document.getElementById('fileNameDisplay');
    
    fileInput.value = '';
    fileNameDisplay.classList.remove('show');
}

// Form validation
document.getElementById('recipeForm').addEventListener('submit', function(e) {
    const title = document.getElementById('title').value.trim();
    const content = document.getElementById('content').value.trim();
    
    if (title.length < 5) {
        e.preventDefault();
        alert('Recipe title must be at least 5 characters long.');
        return false;
    }
    
    if (content.length < 50) {
        e.preventDefault();
        alert('Recipe instructions must be at least 50 characters long.');
        return false;
    }
});

// Reset form also clears file display
document.querySelector('.reset-btn').addEventListener('click', function() {
    setTimeout(function() {
        removeFile();
    }, 10);
});
</script>

<?php 
$stmt->close();
include('includes/footer.php'); 
?>
