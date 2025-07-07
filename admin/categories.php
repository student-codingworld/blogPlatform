<?php
session_start();
require '../includes/db.php';
if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit;
}

// Add category
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  if (!empty($name)) {
    $conn->prepare("INSERT INTO categories (name) VALUES (?)")->execute([$name]);
    header("Location: categories.php");
    exit;
  }
}

// Delete category
if (isset($_GET['delete'])) {
  $conn->prepare("DELETE FROM categories WHERE id = ?")->execute([$_GET['delete']]);
  header("Location: categories.php");
  exit;
}

$categories = $conn->query("SELECT * FROM categories ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Categories</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }

    .card-custom {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
      padding: 30px;
      margin-top: 40px;
      animation: fadeIn 0.7s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    h2 {
      font-weight: 700;
      margin-bottom: 25px;
    }

    .btn-success {
      font-weight: 600;
    }

    .table thead {
      background-color: #198754;
      color: white;
    }

    .btn-danger {
      font-weight: 500;
    }

    @media (max-width: 576px) {
      .card-custom {
        padding: 20px;
      }
    }
  </style>
</head>
<body>

<div class="container">
  <div class="card-custom">
    <h2><i class="bi bi-tags"></i> Manage Blog Categories</h2>

    <form method="post" class="mb-4">
      <div class="input-group">
        <input type="text" name="name" class="form-control" placeholder="Enter new category name" required>
        <button type="submit" class="btn btn-success"><i class="bi bi-plus-circle"></i> Add</button>
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-bordered align-middle">
        <thead>
          <tr>
            <th style="width: 80px;">ID</th>
            <th>Category Name</th>
            <th style="width: 120px;">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($categories) > 0): ?>
            <?php foreach ($categories as $cat): ?>
              <tr>
                <td><?= $cat['id'] ?></td>
                <td><?= htmlspecialchars($cat['name']) ?></td>
                <td>
                  <a href="?delete=<?= $cat['id'] ?>" class="btn btn-sm btn-danger"
                     onclick="return confirm('Are you sure you want to delete this category?');">
                    <i class="bi bi-trash"></i> Delete
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="3" class="text-center text-muted">No categories available.</td>
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
