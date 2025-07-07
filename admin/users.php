<?php
session_start();
require '../includes/db.php';
require '../includes/header.php';

if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit;
}

// Handle actions
if (isset($_GET['ban'])) {
  $id = $_GET['ban'];
  $conn->prepare("UPDATE users SET status='banned' WHERE id=?")->execute([$id]);
} elseif (isset($_GET['activate'])) {
  $id = $_GET['activate'];
  $conn->prepare("UPDATE users SET status='active' WHERE id=?")->execute([$id]);
} elseif (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  // Check if user exists and is not admin before deleting
  $check = $conn->prepare("SELECT * FROM users WHERE id=? AND role='user'");
  $check->execute([$id]);
  if ($check->rowCount() > 0) {
    $conn->prepare("DELETE FROM users WHERE id=?")->execute([$id]);
  }
}

// Fetch all regular users
$stmt = $conn->query("SELECT * FROM users WHERE role='user' ORDER BY created_at DESC");
$users = $stmt->fetchAll();
?>

<h2>Manage Users</h2>
<table class="table table-bordered table-hover">
  <thead class="table-dark">
    <tr>
      <th>ID</th><th>Username</th><th>Email</th><th>Status</th><th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($users as $u): ?>
      <tr>
        <td><?= $u['id'] ?></td>
        <td><?= htmlspecialchars($u['username']) ?></td>
        <td><?= htmlspecialchars($u['email']) ?></td>
        <td>
          <?php if ($u['status'] === 'active'): ?>
            <span class="badge bg-success">Active</span>
          <?php else: ?>
            <span class="badge bg-danger">Banned</span>
          <?php endif; ?>
        </td>
        <td>
          <?php if ($u['status'] === 'active'): ?>
            <a href="?ban=<?= $u['id'] ?>" class="btn btn-sm btn-warning" onclick="return confirm('Are you sure you want to ban this user?');">Ban</a>
          <?php else: ?>
            <a href="?activate=<?= $u['id'] ?>" class="btn btn-sm btn-success" onclick="return confirm('Reactivate this user?');">Activate</a>
          <?php endif; ?>
          <a href="?delete=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to permanently delete this user?');">Delete</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php require '../includes/footer.php'; ?>
