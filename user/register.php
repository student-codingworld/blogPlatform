<?php
require '../includes/db.php';


$registerSuccess = '';
$registerError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);

    if ($check->rowCount() > 0) {
        $registerError = "Email already exists. Please try another.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $password]);
        $registerSuccess = "Registered successfully. <a href='login.php'>Click here to Login</a>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - MyBlog</title>
  <link rel="icon" type="image/png" sizes="32x32" href="../uploads/favi.png">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
                  url('https://images.unsplash.com/photo-1603575448764-3e3eaf1d0d85?auto=format&fit=crop&w=1400&q=80') no-repeat center center/cover;
      font-family: 'Roboto', sans-serif;
      height: 100vh;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .register-container {
      background: rgba(255, 255, 255, 0.95);
      padding: 40px 30px;
      border-radius: 15px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
      width: 100%;
      max-width: 450px;
      animation: fadeIn 1s ease;
    }
    .form-floating input:focus + label,
    .form-floating input:not(:placeholder-shown) + label {
      transform: scale(0.9) translateY(-1.6rem);
      color: #198754;
    }
    .btn-register {
      background-color: #198754;
      color: white;
      font-weight: 600;
    }
    .btn-register:hover {
      background-color: #146c43;
    }
    .strength {
      font-weight: bold;
      font-size: 0.85rem;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to   { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

<div class="register-container">
  <h2><i class="bi bi-person-plus"></i> Register</h2>

  <?php if (!empty($registerError)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($registerError) ?></div>
  <?php endif; ?>

  <?php if (!empty($registerSuccess)): ?>
    <div class="alert alert-success"><?= $registerSuccess ?></div>
  <?php endif; ?>

  <form method="post" novalidate>
    <div class="form-floating mb-3">
      <input type="text" name="username" class="form-control" id="username" placeholder="Username" required>
      <label for="username">Username</label>
      <small id="user-status" class="text-danger d-block mt-1"></small>
    </div>

    <div class="form-floating mb-3">
      <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" required>
      <label for="email">Email address</label>
    </div>

    <div class="form-floating mb-1">
      <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
      <label for="password">Password</label>
    </div>
    <div class="mb-4">
      <span id="strengthMessage" class="strength text-muted"></span>
    </div>

    <button type="submit" class="btn btn-register w-100 py-2">Register</button>
  </form>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Password Strength
const password = document.getElementById("password");
const strengthMessage = document.getElementById("strengthMessage");

password.addEventListener("input", () => {
  const val = password.value;
  let strength = 0;
  if (val.length >= 8) strength++;
  if (/[A-Z]/.test(val)) strength++;
  if (/[0-9]/.test(val)) strength++;
  if (/[^A-Za-z0-9]/.test(val)) strength++;

  if (val.length === 0) {
    strengthMessage.textContent = "";
  } else if (strength <= 1) {
    strengthMessage.textContent = "Weak Password";
    strengthMessage.style.color = "red";
  } else if (strength === 2 || strength === 3) {
    strengthMessage.textContent = "Medium Strength";
    strengthMessage.style.color = "orange";
  } else {
    strengthMessage.textContent = "Strong Password";
    strengthMessage.style.color = "green";
  }
});

// AJAX Username Availability
document.getElementById("username").addEventListener("blur", function() {
  const username = this.value;
  if (username.length > 0) {
    fetch("check_username.php?username=" + encodeURIComponent(username))
      .then(res => res.text())
      .then(data => {
        document.getElementById("user-status").innerHTML = data;
      });
  } else {
    document.getElementById("user-status").textContent = '';
  }
});
</script>

</body>
</html>


