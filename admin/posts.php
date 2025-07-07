<?php
session_start();
$page_title = $post['title'] . " - MyBlog";
$page_description = substr(strip_tags($post['content']), 0, 150);
require '../includes/db.php';
if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit;
}

// Delete Post
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $conn->prepare("DELETE FROM posts WHERE id = ?")->execute([$id]);
  header("Location: posts.php");
  exit;
}

// Get All Posts
$stmt = $conn->query("SELECT posts.id, posts.title, categories.name as category 
                      FROM posts 
                      LEFT JOIN categories ON posts.category_id = categories.id 
                      ORDER BY posts.created_at DESC");
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Posts - Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }

    .container {
      padding-top: 40px;
    }

    h2 {
      font-weight: 700;
      color: #333;
    }

    .btn-add {
      font-weight: 600;
    }

    .table thead {
      background-color: #343a40;
      color: white;
    }

    .table-hover tbody tr:hover {
      background-color: #f1f1f1;
    }

    .action-btns .btn {
      margin-right: 5px;
    }

    .card-box {
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0,0,0,0.05);
      background: white;
    }

    @media (max-width: 576px) {
      .card-box {
        padding: 15px;
      }
    }
  </style>
</head>
<body>

<div class="container">
  <div class="card-box">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2><i class="bi bi-file-post-fill text-primary"></i> Manage Blog Posts</h2>
      <a href="add_post.php" class="btn btn-success btn-add"><i class="bi bi-plus-circle"></i> Add New Post</a>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead>
          <tr>
            <th>#ID</th>
            <th>Title</th>
            <th>Category</th>
            <th width="160">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($posts) > 0): ?>
            <?php foreach ($posts as $post): ?>
              <tr>
                <td><?= $post['id'] ?></td>
                <td><?= htmlspecialchars($post['title']) ?></td>
                <td><span class="badge bg-info"><?= htmlspecialchars($post['category']) ?></span></td>
                <td class="action-btns">
                  <a href="edit_post.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-primary">
                    <i class="bi bi-pencil-square"></i> Edit
                  </a>
                  <a href="posts.php?delete=<?= $post['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this post permanently?');">
                    <i class="bi bi-trash3-fill"></i> Delete
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="4" class="text-center text-muted">No posts available.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php require '../includes/footer.php'; ?>
