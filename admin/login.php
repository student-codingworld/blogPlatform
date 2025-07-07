<?php
session_start();
require '../includes/db.php'; // connects to DB using PDO

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admin_credentials WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin'] = true;
        $_SESSION['admin_username'] = $admin['username'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid admin credentials.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login - MyBlog</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Custom Styling -->
  <style>
    body {
      background: #0f2027;
      background: linear-gradient(to right, #2c5364, #203a43, #0f2027);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      font-family: 'Segoe UI', sans-serif;
    }

    .login-box {
      background: #fff;
      padding: 40px 30px;
      border-radius: 10px;
      box-shadow: 0 15px 30px rgba(0,0,0,0.3);
      width: 100%;
      max-width: 400px;
      animation: fadeIn 1s ease-in-out;
    }

    .login-box h2 {
      font-weight: bold;
      margin-bottom: 25px;
      text-align: center;
      color: #333;
    }

    .form-control:focus {
      border-color: #0d6efd;
      box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    .btn-primary {
      font-weight: 600;
    }

    .input-group-text {
      cursor: pointer;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to   { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

<div class="login-box">
  <h2><i class="bi bi-shield-lock"></i> Admin Login</h2>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="post">
    <div class="mb-3">
      <label class="form-label">Username</label>
      <input type="text" name="username" class="form-control" required placeholder="Enter admin username">
    </div>
    <div class="mb-3">
      <label class="form-label">Password</label>
      <div class="input-group">
        <input type="password" name="password" id="adminPass" class="form-control" required placeholder="Enter password">
        <span class="input-group-text" onclick="togglePass()"><i class="bi bi-eye-slash" id="eyeIcon"></i></span>
      </div>
    </div>
    <button type="submit" class="btn btn-primary w-100">Login</button>
  </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Toggle Password JS -->
<script>
  function togglePass() {
    const passInput = document.getElementById("adminPass");
    const icon = document.getElementById("eyeIcon");
    if (passInput.type === "password") {
      passInput.type = "text";
      icon.classList.remove("bi-eye-slash");
      icon.classList.add("bi-eye");
    } else {
      passInput.type = "password";
      icon.classList.remove("bi-eye");
      icon.classList.add("bi-eye-slash");
    }
  }
</script>
</body>
</html>
