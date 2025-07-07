<?php
require 'includes/db.php';
require 'includes/header.php';

// Fetch categories for the dropdown
$catStmt = $conn->query("SELECT * FROM categories ORDER BY name ASC");
$categories = $catStmt->fetchAll();

// Prepare filters
$searchTerm = '';
$categoryId = '';
$conditions = [];
$params = [];

// If search term exists
if (!empty($_GET['search'])) {
  $searchTerm = trim($_GET['search']);
  $conditions[] = "(posts.title LIKE :search OR posts.content LIKE :search)";
  $params[':search'] = "%$searchTerm%";
}

// If category selected
if (!empty($_GET['category'])) {
  $categoryId = $_GET['category'];
  $conditions[] = "posts.category_id = :category_id";
  $params[':category_id'] = $categoryId;
}

// Build WHERE clause
$where = $conditions ? "WHERE " . implode(" AND ", $conditions) : "";

// Fetch filtered posts
$stmt = $conn->prepare("SELECT posts.*, categories.name AS category 
                        FROM posts 
                        LEFT JOIN categories ON posts.category_id = categories.id 
                        $where 
                        ORDER BY created_at DESC");
$stmt->execute($params);
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php
  $page_title = $page_title ?? "MyBlog - Explore the World with me";
$page_description = $page_description ?? "Welcome to MyBlog – your source for quality tutorials, coding tips, tech news and more scientific facts.";
?>
<link rel="icon" type="image/png" sizes="32x32" href="./uploads/favi.png">

<title><?= htmlspecialchars($page_title) ?></title>
<meta name="description" content="<?= htmlspecialchars($page_description) ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="index, follow">
<meta name="author" content="MyBlog Team">
<meta charset="UTF-8">

<!-- Open Graph / Facebook -->
<meta property="og:title" content="<?= htmlspecialchars($page_title) ?>">
<meta property="og:description" content="<?= htmlspecialchars($page_description) ?>">
<meta property="og:type" content="website">
<meta property="og:url" content="<?= "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>">
<meta property="og:image" content="/uploads/default-thumbnail.jpg">

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= htmlspecialchars($page_title) ?>">
<meta name="twitter:description" content="<?= htmlspecialchars($page_description) ?>">
<meta name="twitter:image" content="/uploads/default-thumbnail.jpg">

  <meta charset="UTF-8">
  <title></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap + AOS + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

  <!-- Custom Styling -->
  <style>
    body {
      background:whitesmoke;
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
  </style>
</head>
<body>

<div class="container py-5">
  <h2 class="mb-4">📝 Latest Blog Posts</h2>

  <!-- 🔍 Search + Filter Form -->
  <form method="get" class="mb-4">
    <div class="row g-2">
      <div class="col-md-6">
        <input type="text" name="search" class="form-control" placeholder="Search by title or content..." value="<?= htmlspecialchars($searchTerm) ?>">
      </div>
      <div class="col-md-4">
        <select name="category" class="form-select">
          <option value="">All Categories</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= $categoryId == $cat['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($cat['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Filter</button>
      </div>
    </div>
  </form>

  <!-- 🧾 Blog Posts -->
  <div class="row g-4 mt-3">
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
        <p class="text-muted">No blog posts found for the selected filter.</p>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Bootstrap + AOS Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({ duration: 800, once: true });
</script>

</body>
</html>

<?php require 'includes/footer.php'; ?>
