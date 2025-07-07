<?php

require 'includes/db.php';
require 'includes/header.php';


if (!isset($_GET['id'])) {
    echo "No post selected.";
    exit;
}

$id = $_GET['id'];

// Increment views
$conn->prepare("UPDATE posts SET views = views + 1 WHERE id = ?")->execute([$id]);

// Fetch post
$stmt = $conn->prepare("SELECT posts.*, categories.name AS category, users.username 
                        FROM posts 
                        JOIN categories ON posts.category_id = categories.id
                        JOIN users ON posts.user_id = users.id
                        WHERE posts.id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) {
    echo "Post not found.";
    exit;
}
// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
  $comment = trim($_POST['comment']);
  if (!empty($comment)) {
    $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, comment) VALUES (?, ?, ?)");
    $stmt->execute([$id, $_SESSION['user_id'], htmlspecialchars($comment)]);
  }
}

// Fetch comments
$stmt = $conn->prepare("SELECT comments.comment, comments.created_at, users.username 
                        FROM comments 
                        JOIN users ON comments.user_id = users.id 
                        WHERE comments.post_id = ? ORDER BY comments.created_at DESC");
$stmt->execute([$id]);
$comments = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
  <title><?= htmlspecialchars($post['title']) ?></title>
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
  <div class="container mt-5">
    <h2><?= htmlspecialchars($post['title']) ?></h2>
    <p><strong>By:</strong> <?= htmlspecialchars($post['username']) ?> | 
       <strong>Category:</strong> <?= htmlspecialchars($post['category']) ?> | 
       <strong>Views:</strong> <?= $post['views'] ?></p>
    <img src="uploads/<?= $post['image'] ?>" alt="" style="max-width:100%; height:auto;">
    <div class="mt-4"><?= $post['content'] ?></div>
    <a href="index.php" class="btn btn-secondary mt-4">← Back to Home</a>
  </div>
  <div class="mt-5">
  <h4>Leave a Comment</h4>
  <?php if (isset($_SESSION['user_id'])): ?>
    <form method="post">
      <textarea name="comment" class="form-control mb-2" rows="3" placeholder="Write your comment..." required></textarea>
      <button class="btn btn-primary">Post Comment</button>
    </form>
  <?php else: ?>
    <p class="text-danger">You must <a href="user/login.php">login</a> to comment.</p>
  <?php endif; ?>
</div>

<hr>

<h4>Comments (<?= count($comments) ?>)</h4>
<?php foreach ($comments as $c): ?>
  <div class="mb-3 border-bottom pb-2">
    <strong><?= htmlspecialchars($c['username']) ?></strong> 
    <small class="text-muted"><?= date('d M Y, h:i A', strtotime($c['created_at'])) ?></small>
    <p><?= nl2br(htmlspecialchars($c['comment'])) ?></p>
  </div>
</body>
</html>
<?php endforeach; ?>