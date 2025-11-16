<?php
/**
 * Get Post Details with Comments
 */
session_start();
require_once('../includes/paths.php');
require_once('../includes/db.php');

header('Content-Type: application/json');

$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($post_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid post ID']);
    exit();
}

// Get post details
$stmt = $conn->prepare("SELECT c.*, u.first_name, u.last_name 
                        FROM community_posts c
                        JOIN users u ON c.user_id = u.user_id
                        WHERE c.id = ?");

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    exit();
}

$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Post not found']);
    exit();
}

$post = $result->fetch_assoc();
$stmt->close();

// Get comments
$comments_stmt = $conn->prepare("SELECT c.*, u.first_name, u.last_name 
                                 FROM comments c
                                 JOIN users u ON c.user_id = u.user_id
                                 WHERE c.post_id = ?
                                 ORDER BY c.created_at DESC");
$comments_stmt->bind_param("i", $post_id);
$comments_stmt->execute();
$comments_result = $comments_stmt->get_result();

// Build HTML
ob_start();
?>
<div class="post-detail">
    <?php if (!empty($post['image_path'])): ?>
        <div class="post-detail-image">
            <img src="<?php echo htmlspecialchars($post['image_path']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
        </div>
    <?php endif; ?>
    
    <div class="post-detail-header">
        <div class="post-author">
            <div class="author-avatar">
                <i class="fas fa-user-circle"></i>
            </div>
            <div class="author-info">
                <span class="author-name"><?php echo htmlspecialchars($post['first_name'] . ' ' . $post['last_name']); ?></span>
                <span class="post-date"><?php echo date('M d, Y \a\t g:i A', strtotime($post['created_at'])); ?></span>
            </div>
        </div>
        
        <div class="post-badges">
            <span class="badge badge-category"><?php echo ucfirst(htmlspecialchars($post['category'])); ?></span>
            <span class="badge badge-difficulty"><?php echo ucfirst(htmlspecialchars($post['difficulty'])); ?></span>
        </div>
    </div>
    
    <h2 class="post-detail-title"><?php echo htmlspecialchars($post['title']); ?></h2>
    <div class="post-detail-content">
        <?php echo nl2br(htmlspecialchars($post['content'])); ?>
    </div>
    
    <!-- Comments Section -->
    <div class="comments-section">
        <h3><i class="fas fa-comments"></i> Comments (<?php echo $comments_result->num_rows; ?>)</h3>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="comment-form">
                <textarea id="commentText" placeholder="Write a comment..."></textarea>
                <button onclick="submitComment(<?php echo $post_id; ?>)">
                    <i class="fas fa-paper-plane"></i> Post Comment
                </button>
            </div>
        <?php else: ?>
            <p style="text-align: center; color: #666; padding: 1rem;">
                <a href="<?php echo url('auth/login.php'); ?>" style="color: #e76f51;">Login</a> to leave a comment
            </p>
        <?php endif; ?>
        
        <div class="comments-list">
            <?php if ($comments_result->num_rows > 0): ?>
                <?php while($comment = $comments_result->fetch_assoc()): ?>
                    <div class="comment-item">
                        <div class="comment-author">
                            <div class="comment-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="comment-info">
                                <span class="comment-name"><?php echo htmlspecialchars($comment['first_name'] . ' ' . $comment['last_name']); ?></span>
                                <span class="comment-date"><?php echo date('M d, Y \a\t g:i A', strtotime($comment['created_at'])); ?></span>
                            </div>
                        </div>
                        <p class="comment-text"><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align: center; color: #999; padding: 2rem;">No comments yet. Be the first to comment!</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .post-detail-image {
        width: 100%;
        height: 300px;
        overflow: hidden;
        border-radius: 12px;
        margin-bottom: 1.5rem;
    }
    
    .post-detail-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .post-detail-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    
    .post-detail-title {
        font-size: 1.8rem;
        color: #2c3e50;
        margin-bottom: 1rem;
    }
    
    .post-detail-content {
        font-size: 1rem;
        line-height: 1.8;
        color: #555;
        margin-bottom: 2rem;
    }
</style>
<?php
$html = ob_get_clean();
$comments_stmt->close();

echo json_encode([
    'success' => true,
    'html' => $html
]);
?>
