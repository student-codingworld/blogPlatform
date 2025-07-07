<?php
session_start();
require '../includes/db.php';

$loginError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $pass = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($pass, $user['password'])) {
        if ($user['status'] === 'banned') {
            $loginError = "Your account has been banned by the admin.";
        } else {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: ../index.php");
            exit;
        }
    } else {
        $loginError = "Invalid login credentials.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - MyBlog</title>
  <link rel="icon" type="image/png" sizes="32x32" href="../uploads/favi.png">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

  <!-- Custom CSS -->
  <style>
    body {
      background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
                  url('https://images.unsplash.com/photo-1581093458798-5cf82d786a89?auto=format&fit=crop&w=1400&q=80') no-repeat center center/cover;
      font-family: 'Roboto', sans-serif;
      height: 100vh;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-container {
      background: rgba(255, 255, 255, 0.95);
      padding: 40px 30px;
      border-radius: 15px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
      width: 100%;
      max-width: 400px;
      animation: fadeIn 1s ease;
    }

    .login-container h2 {
      text-align: center;
      margin-bottom: 25px;
      font-weight: 700;
      color: #333;
    }

    .form-control:focus {
      border-color: #007bff;
      box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .btn-login {
      background-color: #007bff;
      color: white;
      font-weight: 600;
      transition: 0.3s;
    }

    .btn-login:hover {
      background-color: #0056b3;
    }

    .form-floating label {
      transition: 0.3s;
    }

    .form-floating input:focus + label,
    .form-floating input:not(:placeholder-shown) + label {
      transform: scale(0.9) translateY(-1.6rem);
      color: #007bff;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 576px) {
      .login-container {
        padding: 30px 20px;
      }
    }
  </style>
</head>
<body>

<div class="login-container">
  <h2><i class="bi bi-person-circle"></i> User Login</h2>

  <?php if (!empty($loginError)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($loginError) ?></div>
  <?php endif; ?>

  <form method="post" novalidate>
    <div class="form-floating mb-3">
      <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com" required>
      <label for="email">Email address</label>
    </div>

    <div class="form-floating mb-4">
      <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
      <label for="password">Password</label>
    </div>

    <button type="submit" class="btn btn-login w-100 py-2">Login</button>
    <div class="text-end mt-2">
  <a href="forgot_password.php" class="text-decoration-none">Forgot Password?</a>
</div>
  </form>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
