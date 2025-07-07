<?php
session_start();
require '../includes/db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $captchaAnswer = trim($_POST['captcha']);

    // Check CAPTCHA
    if (!isset($_SESSION['captcha']) || $captchaAnswer != $_SESSION['captcha']) {
        $message = "<div class='alert alert-danger'>Invalid CAPTCHA answer.</div>";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND email = ?");
        $stmt->execute([$username, $email]);
        $user = $stmt->fetch();

        if ($user) {
            $newPassword = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$"), 0, 10);
            $hashed = password_hash($newPassword, PASSWORD_DEFAULT);

            $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update->execute([$hashed, $user['id']]);

            $message = "Your new password is: <strong>$newPassword</strong><br><a href='login.php'>Click here to login</a>";
        } else {
            $message = "<div class='alert alert-danger'>No account found with that username and email.</div>";
        }
    }
}

// Generate CAPTCHA question
$num1 = rand(1, 10);
$num2 = rand(1, 10);
$_SESSION['captcha'] = $num1 + $num2;
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password - MyBlog</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f1f1f1;
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .forgot-container {
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
      max-width: 400px;
      width: 100%;
    }
    h3 {
      text-align: center;
      margin-bottom: 20px;
    }
    .btn-reset {
      background-color: #007bff;
      color: white;
      font-weight: bold;
    }
  </style>
</head>
<body>

<div class="forgot-container">
  <h3>🔐 Forgot Password</h3>
  
  <?php if ($message): ?>
    <div class="alert alert-info"><?= $message ?></div>
  <?php endif; ?>

  <form method="post">
  <div class="mb-3">
    <label class="form-label">Username</label>
    <input type="text" name="username" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Registered Email</label>
    <input type="email" name="email" class="form-control" required>
  </div>
  <div class="mb-4">
    <label class="form-label">What is <?= $num1 ?> + <?= $num2 ?> ?</label>
    <input type="text" name="captcha" class="form-control" placeholder="Answer" required>
  </div>
  <button type="submit" class="btn btn-reset w-100">Reset Password</button>
</form>

</div>

</body>
</html>
