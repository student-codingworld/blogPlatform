<?php
session_start();
require '../includes/db.php';
if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit;
}

// Fetch categories for dropdown
$categories = $conn->query("SELECT * FROM categories")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = $_POST['title'];
  $content = $_POST['content'];
  $category_id = $_POST['category_id'];
  $user_id = 1; // Static user ID (update for real auth system)

  // Image upload
  $image = '';
  if (!empty($_FILES['image']['name'])) {
    $image = time() . "_" . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . $image);
  }

  $stmt = $conn->prepare("INSERT INTO posts (title, content, image, category_id, user_id) VALUES (?, ?, ?, ?, ?)");
  $stmt->execute([$title, $content, $image, $category_id, $user_id]);

  header("Location: posts.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add New Post</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }

    .post-form-card {
      background: #fff;
      border-radius: 15px;
      padding: 30px;
      box-shadow: 0 0 20px rgba(0,0,0,0.08);
      margin-top: 50px;
      animation: fadeIn 0.7s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    h2 {
      font-weight: 700;
      margin-bottom: 25px;
    }

    label {
      font-weight: 500;
    }

    .form-control:focus {
      border-color: #198754;
      box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
    }

    .btn-success {
      font-weight: 600;
    }

    .btn-secondary {
      font-weight: 600;
    }

    @media (max-width: 576px) {
      .post-form-card {
        padding: 20px;
      }
    }
  </style>
</head>
<body>

<div class="container">
  <div class="post-form-card mx-auto" style="max-width: 700px;">
    <h2><i class="bi bi-file-plus"></i> Add New Blog Post</h2>
    <form method="post" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="title" class="form-label">Post Title</label>
        <input type="text" name="title" id="title" class="form-control" placeholder="Enter title..." required>
      </div>

      <div class="mb-3">
        <label for="content" class="form-label">Content</label>
        <textarea name="content" id="content" class="form-control" rows="6" placeholder="Write something..." required></textarea>
      </div>

      <div class="mb-3">
        <label for="image" class="form-label">Upload Image</label>
        <input type="file" name="image" id="image" class="form-control">
      </div>

      <div class="mb-4">
        <label for="category" class="form-label">Select Category</label>
        <select name="category_id" id="category" class="form-select" required>
          <option value="">-- Choose a Category --</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-success"><i class="bi bi-upload"></i> Add Post</button>
        <a href="posts.php" class="btn btn-secondary"><i class="bi bi-arrow-left-circle"></i> Cancel</a>
      </div>
    </form>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
