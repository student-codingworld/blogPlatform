<?php
require 'includes/db.php';
require 'includes/header.php';

// Search logic
$searchTerm = '';
$searchClause = '';
$params = [];

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
  $searchTerm = trim($_GET['search']);
  $searchClause = "WHERE posts.title LIKE :search OR posts.content LIKE :search";
  $params[':search'] = "%$searchTerm%";
}

// Prepare and execute query
$stmt = $conn->prepare("SELECT posts.*, categories.name AS category 
                        FROM posts 
                        LEFT JOIN categories ON posts.category_id = categories.id 
                        $searchClause 
                        ORDER BY created_at DESC");

$stmt->execute($params);
$posts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Blog</title>
  <link rel="icon" type="image/png" sizes="32x32" href="./uploads/favi.png">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <!-- AOS Animation CSS -->
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

  <!-- Custom CSS -->
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }

    h2 {
      font-weight: 600;
      color: #343a40;
      text-align: center;
    }

    .card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      border: none;
      border-radius: 15px;
      overflow: hidden;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }

    .category-badge {
      background-color: #6c757d;
      color: #fff;
      padding: 2px 8px;
      border-radius: 12px;
      font-size: 0.75rem;
      margin-right: 8px;
    }

    .views {
      font-size: 0.8rem;
      color: #6c757d;
    }

    @media (max-width: 576px) {
      .card-body h5 {
        font-size: 1.1rem;
      }
    }
  </style>
</head>
<body>

<div class="container py-5">
  <h2 class="mb-4">📝 Latest Blog Posts</h2>

  <!-- Search Form -->
  <form method="get" class="mb-5">
    <div class="input-group">
      <input type="text" name="search" class="form-control" placeholder="Search posts..." value="<?= htmlspecialchars($searchTerm) ?>">
      <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
    </div>
  </form>

  <!-- Blog Cards -->
  <div class="row g-4">
    <?php if (count($posts) > 0): ?>
      <?php foreach ($posts as $post): ?>
        <div class="col-md-6 col-lg-4" data-aos="fade-up">
          <div class="card h-100 shadow-sm">
            <div class="card-body">
              <h5><?= htmlspecialchars($post['title']) ?></h5>
              <p>
                <span class="category-badge"><?= htmlspecialchars($post['category']) ?></span>
                <span class="views"><i class="bi bi-eye"></i> <?= $post['views'] ?> views</span>
              </p>
              <p><?= substr(strip_tags($post['content']), 0, 100) ?>...</p>
              <a href="post.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-primary mt-2">Read More</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="col-12 text-center">
        <p class="text-muted">No blog posts found matching your search.</p>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- AOS Animation JS -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 800,
    once: true
  });
</script>
</body>
</html>

<?php require 'includes/footer.php'; ?>
