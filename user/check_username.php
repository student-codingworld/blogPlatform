<?php
require '../includes/db.php';

if (isset($_GET['username'])) {
    $username = trim($_GET['username']);
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);

    if ($stmt->rowCount() > 0) {
        echo "<span class='text-danger'>Username already taken</span>";
    } else {
        echo "<span class='text-success'>Username available</span>";
    }
}
?>
