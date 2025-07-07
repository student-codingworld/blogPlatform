<?php
require 'includes/db.php';
$term = $_GET['query'] ?? '';
$stmt = $conn->prepare("SELECT * FROM posts WHERE title LIKE ?");
$stmt->execute(["%$term%"]);
$results = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Search - <?= htmlspecialchars($term) ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
  <div class="container mt-4">
    <h3>Search results for: "<?= htmlspecialchars($term) ?>"</h3>
    <?php if (count($results) == 0): ?>
      <p>No results found.</p>
    <?php endif; ?>
    <?php foreach ($results as $post): ?>
      <div class="card mt-3">
        <div class="card-body">
          <h5><?= htmlspecialchars($post['title']) ?></h5>
          <p><?= substr(strip_tags($post['content']), 0, 100) ?>...</p>
          <a href="post.php?id=<?= $post['id'] ?>" class="btn btn-primary">Read More</a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</body>
</html>
