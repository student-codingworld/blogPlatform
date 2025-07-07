<?php
$host = 'localhost';
$db   = 'blogsite';
$user = 'root';
$pass = ''; // Or your XAMPP password
$charset = 'utf8mb4';

try {
  $conn = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "DB Connection failed: " . $e->getMessage();
  exit;
}
?>
