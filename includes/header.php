<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Blog Site</title>
  <link rel="icon" type="image/png" sizes="32x32" href="./uploads/favi.png">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

  <!-- Custom Hamburger CSS -->
  <style>
    .custom-toggler {
      border: none;
      background: none;
      padding: 0;
      width: 40px;
      height: 30px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      cursor: pointer;
    }

    .bar {
      height: 4px;
      width: 100%;
      background-color: #fff;
      border-radius: 5px;
      transition: 0.4s;
    }

    .custom-toggler.open .bar1 {
      transform: rotate(45deg) translate(5px, 5px);
    }

    .custom-toggler.open .bar2 {
      opacity: 0;
    }

    .custom-toggler.open .bar3 {
      transform: rotate(-45deg) translate(6px, -6px);
    }

    /* Optional: Smoother nav toggle */
    .navbar-collapse {
      transition: max-height 0.4s ease;
    }
  </style>
</head>
<body>

<!-- Navbar Start -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a href="index.php"><img src="./uploads/favi.png" alt=""></a>

    <!-- Custom Hamburger Icon -->
    <button class="custom-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" id="hamburgerToggle">
      <div class="bar bar1"></div>
      <div class="bar bar2"></div>
      <div class="bar bar3"></div>
    </button>

    <!-- Navbar Links -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <?php if (isset($_SESSION['user_id'])): ?>
          <li class="nav-item"><a class="nav-link" href="logout.php">Logout (<?= htmlspecialchars($_SESSION['username']) ?>)</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="index.php">Search</a></li>
          <li class="nav-item"><a class="nav-link" href="user/login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="user/register.php">Register</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<!-- Navbar End -->

<div class="container mt-4">
<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom Hamburger Toggle Script -->
<script>
  const toggler = document.getElementById('hamburgerToggle');
  toggler.addEventListener('click', function () {
    this.classList.toggle('open');
  });
</script>
