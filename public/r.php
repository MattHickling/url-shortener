<?php
include '../config/bootstrap.php'; 

if (!isset($_GET['code'])) {
    die('No short code provided.');
}

$shortCode = $_GET['code'];

$stmt = $conn->prepare("SELECT original_url FROM urls WHERE short_code = ? LIMIT 1");
$stmt->bind_param("s", $shortCode);
$stmt->execute();
$stmt->bind_result($originalUrl);
$stmt->fetch();
$stmt->close();

if ($originalUrl) {
    header("Location: " . $originalUrl); 
    exit();
} else {
    echo "Short URL not found.";
}
