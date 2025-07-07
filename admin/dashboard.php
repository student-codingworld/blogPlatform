<?php
session_start();
if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit;
}
require '../includes/db.php';

// Fetch data
$totalPosts = $conn->query("SELECT COUNT(*) FROM posts")->fetchColumn();
$totalUsers = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalCategories = $conn->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$latestActivities = $conn->query("
  SELECT 'Post' AS type, title AS activity, created_at 
  FROM posts
  UNION
  SELECT 'User', username, created_at 
  FROM users
  ORDER BY created_at DESC
  LIMIT 5
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - MyBlog</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background-color: #f0f2f5;
      font-family: 'Segoe UI', sans-serif;
    }

    .sidebar {
      height: 100vh;
      background-color: #343a40;
      color: white;
      padding-top: 20px;
    }

    .sidebar a {
      color: #cfd8dc;
      display: block;
      padding: 12px 20px;
      text-decoration: none;
    }

    .sidebar a:hover, .sidebar a.active {
      background-color: #495057;
      color: #fff;
    }

    .content {
      padding: 30px;
    }

    .dashboard-card {
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.08);
      transition: transform 0.2s ease;
    }

    .dashboard-card:hover {
      transform: translateY(-3px);
    }

    .topbar {
      background-color: #fff;
      padding: 15px 20px;
      border-bottom: 1px solid #dee2e6;
    }

    @media (max-width: 768px) {
      .sidebar {
        height: auto;
      }
    }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-3 col-lg-2 sidebar">
      <h4 class="text-center"><i class="bi bi-shield-lock"></i> Admin</h4>
      <a href="dashboard.php" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>
      <a href="users.php"><i class="bi bi-people"></i> Manage Users</a>
      <a href="posts.php"><i class="bi bi-file-post"></i> Manage Posts</a>
      <a href="categories.php"><i class="bi bi-tags"></i> Manage Categories</a>
      <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="col-md-9 col-lg-10 content">
      <div class="topbar d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="bi bi-speedometer2"></i> Dashboard</h4>
        <span class="text-muted">Welcome, Admin</span>
      </div>

      <div class="row mt-4 g-4">
        <div class="col-md-4">
          <div class="card dashboard-card border-primary">
            <div class="card-body">
              <h5 class="card-title text-primary"><i class="bi bi-file-post"></i> Total Posts</h5>
              <p class="card-text fs-4 fw-bold"><?= $totalPosts ?></p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card dashboard-card border-warning">
            <div class="card-body">
              <h5 class="card-title text-warning"><i class="bi bi-people"></i> Total Users</h5>
              <p class="card-text fs-4 fw-bold"><?= $totalUsers ?></p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card dashboard-card border-success">
            <div class="card-body">
              <h5 class="card-title text-success"><i class="bi bi-tags"></i> Categories</h5>
              <p class="card-text fs-4 fw-bold"><?= $totalCategories ?></p>
            </div>
          </div>
        </div>
      </div>
      <!-- Latest Activities -->
<div class="mt-5">
  <h5><i class="bi bi-clock-history"></i> Recent Activities</h5>
  <ul class="list-group list-group-flush">
    <?php foreach ($latestActivities as $activity): ?>
      <li class="list-group-item">
        <i class="bi bi-activity text-secondary me-2"></i>
        <strong><?= htmlspecialchars($activity['type']) ?>:</strong>
        <?= htmlspecialchars($activity['activity']) ?> 
        <span class="text-muted float-end"><?= date('d M Y, h:i A', strtotime($activity['created_at'])) ?></span>
      </li>
    <?php endforeach; ?>
  </ul>
</div>

<!-- Chart Section -->
<div class="mt-5">
  <h5><i class="bi bi-pie-chart-fill"></i> Data Overview</h5>
  <canvas id="overviewChart" style="max-height: 300px;"></canvas>
</div>


      <div class="text-center mt-5 text-muted">
        <small>Admin Panel © <?= date('Y') ?> | Powered by MyBlog</small>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('overviewChart').getContext('2d');
new Chart(ctx, {
  type: 'pie',
  data: {
    labels: ['Posts', 'Users', 'Categories'],
    datasets: [{
      label: 'Platform Stats',
      data: [<?= $totalPosts ?>, <?= $totalUsers ?>, <?= $totalCategories ?>],
      backgroundColor: ['#0d6efd', '#ffc107', '#198754'],
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { position: 'bottom' }
    }
  }
});
</script>

</body>
</html>
