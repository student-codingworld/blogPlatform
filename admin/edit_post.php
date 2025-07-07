<?php
session_start();
require '../includes/db.php';
if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit;
}

$id = $_GET['id'];
$post = $conn->prepare("SELECT * FROM posts WHERE id = ?");
$post->execute([$id]);
$post = $post->fetch();

$categories = $conn->query("SELECT * FROM categories")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = $_POST['title'];
  $content = $_POST['content'];
  $category_id = $_POST['category_id'];

  // Update image if new uploaded
  if (!empty($_FILES['image']['name'])) {
    $image = time() . "_" . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . $image);
    $conn->prepare("UPDATE posts SET title=?, content=?, image=?, category_id=? WHERE id=?")
         ->execute([$title, $content, $image, $category_id, $id]);
  } else {
    $conn->prepare("UPDATE posts SET title=?, content=?, category_id=? WHERE id=?")
         ->execute([$title, $content, $category_id, $id]);
  }

  header("Location: posts.php");
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Post</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
  <h2>Edit Post</h2>
  <form method="post" enctype="multipart/form-data">
    <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" class="form-control mb-2" required>
    <textarea name="content" class="form-control mb-2" rows="5" required><?= htmlspecialchars($post['content']) ?></textarea>
    <p>Current Image: <strong><?= $post['image'] ?></strong></p>
    <input type="file" name="image" class="form-control mb-2">
    <select name="category_id" class="form-control mb-2" required>
      <?php foreach ($categories as $cat): ?>
        <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $post['category_id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($cat['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <button class="btn btn-primary">Update Post</button>
    <a href="posts.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>
</body>
</html>
