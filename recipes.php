<?php 
require_once('includes/paths.php');
include('includes/header.php'); 
include('includes/db.php');
?>
<link rel="stylesheet" href="<?php echo url('assets/css/recipes.css'); ?>">

<?php 
// Get search and filter parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$cuisine = isset($_GET['cuisine']) ? $_GET['cuisine'] : '';
$diet = isset($_GET['diet']) ? $_GET['diet'] : '';
$difficulty = isset($_GET['difficulty']) ? $_GET['difficulty'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// Pagination
$limit = 12;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Build query
$conditions = [];
$params = [];
$types = '';

if (!empty($search)) {
    $conditions[] = "(title LIKE ? OR description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $types .= "ss";
}

if (!empty($cuisine)) {
    $conditions[] = "cuisine_type = ?";
    $params[] = $cuisine;
    $types .= "s";
}

if (!empty($diet)) {
    $conditions[] = "dietary_preference = ?";
    $params[] = $diet;
    $types .= "s";
}

if (!empty($difficulty)) {
    $conditions[] = "difficulty = ?";
    $params[] = $difficulty;
    $types .= "s";
}

$whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";

// Sort options
$sortOptions = [
    'newest' => 'created_at DESC',
    'popular' => 'views DESC',
    'title' => 'title ASC'
];
$orderBy = $sortOptions[$sort] ?? 'created_at DESC';

// Get total count
$countQuery = "SELECT COUNT(*) AS total FROM recipes $whereClause";
$stmt = $conn->prepare($countQuery);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$totalRecipes = $stmt->get_result()->fetch_assoc()['total'];
$totalPages = ceil($totalRecipes / $limit);

// Get recipes
$query = "SELECT * FROM recipes $whereClause ORDER BY $orderBy LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query);
$bindTypes = !empty($types) ? $types . "ii" : "ii";
$bindParams = !empty($params) ? array_merge($params, [$limit, $offset]) : [$limit, $offset];
$stmt->bind_param($bindTypes, ...$bindParams);
$stmt->execute();
$recipes = $stmt->get_result();
?>

<!-- Hero Section -->
<div class="recipes-hero-section">
    <div class="hero-background">
        <div class="hero-overlay"></div>
    </div>
    <div class="hero-container">
        <div class="hero-content-wrapper">
            <span class="hero-badge">
                <i class="fas fa-fire"></i> Trending Recipes
            </span>
            <h1 class="hero-title">Discover Your Next Favorite Dish</h1>
            <p class="hero-description">
                Explore thousands of delicious recipes from around the world. 
                From quick weeknight dinners to impressive weekend feasts.
            </p>
            <div class="hero-search-box">
                <form method="GET" action="recipes.php">
                    <div class="search-input-wrapper">
                        <i class="fas fa-search"></i>
                        <input 
                            type="text" 
                            name="search" 
                            placeholder="Search for recipes, ingredients, or cuisines..." 
                            value="<?php echo htmlspecialchars($search); ?>"
                        >
                        <button type="submit" class="search-btn">Search</button>
                    </div>
                </form>
            </div>
            <div class="hero-stats-row">
                <div class="stat-box">
                    <div class="stat-number"><?php echo number_format($totalRecipes); ?>+</div>
                    <div class="stat-label">Recipes</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Cuisines</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">1M+</div>
                    <div class="stat-label">Happy Cooks</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Bar -->
<div class="filter-bar-section">
    <div class="filter-container">
        <form method="GET" action="recipes.php" id="filterForm">
            <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
            
            <div class="filter-wrapper">
                <div class="filter-left">
                    <div class="filter-item">
                        <label><i class="fas fa-globe-americas"></i> Cuisine</label>
                        <select name="cuisine" onchange="document.getElementById('filterForm').submit()">
                            <option value="">All Cuisines</option>
                            <option value="Italian" <?php echo $cuisine === 'Italian' ? 'selected' : ''; ?>>Italian</option>
                            <option value="Asian" <?php echo $cuisine === 'Asian' ? 'selected' : ''; ?>>Asian</option>
                            <option value="Mexican" <?php echo $cuisine === 'Mexican' ? 'selected' : ''; ?>>Mexican</option>
                            <option value="Indian" <?php echo $cuisine === 'Indian' ? 'selected' : ''; ?>>Indian</option>
                            <option value="Mediterranean" <?php echo $cuisine === 'Mediterranean' ? 'selected' : ''; ?>>Mediterranean</option>
                            <option value="American" <?php echo $cuisine === 'American' ? 'selected' : ''; ?>>American</option>
                            <option value="French" <?php echo $cuisine === 'French' ? 'selected' : ''; ?>>French</option>
                            <option value="Chinese" <?php echo $cuisine === 'Chinese' ? 'selected' : ''; ?>>Chinese</option>
                        </select>
                    </div>

                    <div class="filter-item">
                        <label><i class="fas fa-leaf"></i> Diet</label>
                        <select name="diet" onchange="document.getElementById('filterForm').submit()">
                            <option value="">All Diets</option>
                            <option value="Vegan" <?php echo $diet === 'Vegan' ? 'selected' : ''; ?>>Vegan</option>
                            <option value="Vegetarian" <?php echo $diet === 'Vegetarian' ? 'selected' : ''; ?>>Vegetarian</option>
                            <option value="Gluten-Free" <?php echo $diet === 'Gluten-Free' ? 'selected' : ''; ?>>Gluten-Free</option>
                            <option value="Keto" <?php echo $diet === 'Keto' ? 'selected' : ''; ?>>Keto</option>
                            <option value="Paleo" <?php echo $diet === 'Paleo' ? 'selected' : ''; ?>>Paleo</option>
                        </select>
                    </div>

                    <div class="filter-item">
                        <label><i class="fas fa-chart-line"></i> Level</label>
                        <select name="difficulty" onchange="document.getElementById('filterForm').submit()">
                            <option value="">All Levels</option>
                            <option value="Easy" <?php echo $difficulty === 'Easy' ? 'selected' : ''; ?>>Easy</option>
                            <option value="Medium" <?php echo $difficulty === 'Medium' ? 'selected' : ''; ?>>Medium</option>
                            <option value="Hard" <?php echo $difficulty === 'Hard' ? 'selected' : ''; ?>>Hard</option>
                        </select>
                    </div>
                </div>

                <div class="filter-right">
                    <div class="filter-item">
                        <label><i class="fas fa-sort-amount-down"></i> Sort</label>
                        <select name="sort" onchange="document.getElementById('filterForm').submit()">
                            <option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>Latest</option>
                            <option value="popular" <?php echo $sort === 'popular' ? 'selected' : ''; ?>>Popular</option>
                            <option value="title" <?php echo $sort === 'title' ? 'selected' : ''; ?>>A-Z</option>
                        </select>
                    </div>

                    <?php if (!empty($cuisine) || !empty($diet) || !empty($difficulty)): ?>
                        <a href="<?php echo url('recipes.php?search=' . urlencode($search)); ?>" class="clear-filters-btn">
                            <i class="fas fa-times-circle"></i> Clear Filters
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Results Section -->
<div class="recipes-content-section">
    <div class="content-container">
        
        <!-- Results Header -->
        <div class="results-header">
            <div class="results-info">
                <h2>
                    <?php if (!empty($search)): ?>
                        Results for "<?php echo htmlspecialchars($search); ?>"
                    <?php elseif (!empty($cuisine) || !empty($diet) || !empty($difficulty)): ?>
                        Filtered Recipes
                    <?php else: ?>
                        All Recipes
                    <?php endif; ?>
                </h2>
                <p class="results-count">
                    <span class="count-highlight"><?php echo number_format($totalRecipes); ?></span> 
                    recipe<?php echo $totalRecipes != 1 ? 's' : ''; ?> found
                </p>
            </div>
        </div>

        <?php if ($recipes->num_rows > 0): ?>
            <!-- Recipe Grid -->
            <div class="recipes-grid">
                <?php while($recipe = $recipes->fetch_assoc()): ?>
                    <article class="recipe-card-modern">
                        <a href="<?php echo url('recipe.php?id=' . $recipe['recipe_id']); ?>" class="card-link-wrapper">
                            
                            <!-- Card Image -->
                            <div class="card-image-container">
                                <?php 
                                $imagePath = !empty($recipe['image']) ? 'assets/images/' . $recipe['image'] : 'assets/images/recipe.jpg';
                                ?>
                                <img src="<?php echo url(htmlspecialchars($imagePath)); ?>" 
                                     alt="<?php echo htmlspecialchars($recipe['title']); ?>"
                                     class="card-image"
                                     loading="lazy">
                                
                                <!-- Difficulty Badge -->
                                <?php if (!empty($recipe['difficulty'])): ?>
                                    <div class="difficulty-badge-modern badge-<?php echo strtolower($recipe['difficulty']); ?>">
                                        <?php echo htmlspecialchars($recipe['difficulty']); ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Hover Overlay -->
                                <div class="card-hover-overlay">
                                    <div class="overlay-content">
                                        <i class="fas fa-arrow-right"></i>
                                        <span>View Recipe</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="card-body-modern">
                                
                                <!-- Category Tags -->
                                <div class="card-tags-row">
                                    <?php if (!empty($recipe['cuisine_type'])): ?>
                                        <span class="tag-cuisine">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <?php echo htmlspecialchars($recipe['cuisine_type']); ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($recipe['dietary_preference'])): ?>
                                        <span class="tag-diet">
                                            <?php echo htmlspecialchars($recipe['dietary_preference']); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <!-- Title -->
                                <h3 class="card-title-modern">
                                    <?php echo htmlspecialchars($recipe['title']); ?>
                                </h3>

                                <!-- Description -->
                                <p class="card-description-modern">
                                    <?php 
                                    $desc = $recipe['description'] ?? 'Delicious recipe waiting for you to try!';
                                    echo htmlspecialchars(strlen($desc) > 90 ? substr($desc, 0, 90) . '...' : $desc); 
                                    ?>
                                </p>

                                <!-- Meta Info -->
                                <div class="card-meta-row">
                                    <?php if (!empty($recipe['prep_time'])): ?>
                                        <div class="meta-item-modern">
                                            <i class="fas fa-clock"></i>
                                            <span><?php echo htmlspecialchars($recipe['prep_time']); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="meta-item-modern">
                                        <i class="fas fa-eye"></i>
                                        <span><?php echo number_format($recipe['views'] ?? 0); ?> views</span>
                                    </div>
                                </div>

                            </div>
                        </a>
                    </article>
                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="pagination-modern">
                    <div class="pagination-wrapper">
                        
                        <?php if ($page > 1): ?>
                            <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>" 
                               class="pagination-btn-modern pagination-prev">
                                <i class="fas fa-chevron-left"></i>
                                <span>Previous</span>
                            </a>
                        <?php endif; ?>

                        <div class="pagination-numbers-modern">
                            <?php
                            $start = max(1, $page - 2);
                            $end = min($totalPages, $page + 2);
                            
                            if ($start > 1): ?>
                                <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => 1])); ?>" 
                                   class="page-number">1</a>
                                <?php if ($start > 2): ?>
                                    <span class="page-dots">...</span>
                                <?php endif; ?>
                            <?php endif; ?>
                            
                            <?php for ($i = $start; $i <= $end; $i++): ?>
                                <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>" 
                                   class="page-number <?php echo $i === $page ? 'active' : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>
                            
                            <?php if ($end < $totalPages): ?>
                                <?php if ($end < $totalPages - 1): ?>
                                    <span class="page-dots">...</span>
                                <?php endif; ?>
                                <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $totalPages])); ?>" 
                                   class="page-number"><?php echo $totalPages; ?></a>
                            <?php endif; ?>
                        </div>

                        <?php if ($page < $totalPages): ?>
                            <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>" 
                               class="pagination-btn-modern pagination-next">
                                <span>Next</span>
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>

                    </div>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <!-- No Results -->
            <div class="no-results-modern">
                <div class="no-results-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3>No Recipes Found</h3>
                <p>We couldn't find any recipes matching your search criteria.</p>
                <p class="suggestion">Try adjusting your filters or search terms.</p>
                <a href="<?php echo url('recipes.php'); ?>" class="btn-back-home">
                    <i class="fas fa-home"></i> View All Recipes
                </a>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php include('includes/footer.php'); ?>
