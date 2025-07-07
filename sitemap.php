<?php
require 'includes/db.php';
header("Content-Type: application/xml; charset=utf-8");

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";

$stmt = $conn->query("SELECT id, title FROM posts");
while ($row = $stmt->fetch()) {
    $slug = strtolower(str_replace(' ', '-', $row['title']));
    echo "<url><loc>http://yourdomain.com/post.php?id={$row['id']}</loc></url>";
}

echo "</urlset>";
?>
