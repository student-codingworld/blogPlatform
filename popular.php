<?php
require 'includes/db.php';
$stmt = $conn->query("SELECT * FROM posts ORDER BY views DESC LIMIT 5");
$popular = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Popular Posts</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
  <div class="container mt-4">
    <h2>Most Popular Posts</h2>
    <?php foreach ($popular as $post): ?>
      <div class="card mt-3">
        <div class="card-body">
          <h5><?= htmlspecialchars($post['title']) ?></h5>
          <p>Views: <?= $post['views'] ?></p>
          <a href="post.php?id=<?= $post['id'] ?>" class="btn btn-primary">Read More</a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</body>
</html>
