<?php
session_start();

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

include '../config/bootstrap.php';
include '../templates/header.php';

use Src\Url;
use Src\Auth\User;

$email = $_SESSION['email'];
$user = new User($conn);
$currentUser = $user->getUsername($email);

$urlService = new Url($conn);
$userData = $user->getByEmail($email); 
$userId = $userData['id'] ?? null;

?>
<link href="../assets/css/bootstrap.min.css" rel="stylesheet">

<h2>Welcome <?php echo ucfirst(htmlspecialchars($currentUser)); ?>!</h2>
<div>
    <form method="POST" class="center-block">
    <div>
        What is the URL that you would like to shorten?: 
    </div>
    <input type="text" name="original_url" class="form-control mt-2 mb-4"  required>
    <input type="submit" class="btn btn-primary" class="form-control"  value="Submit">
</form>
</div>





<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['original_url'])) {
        $originalUrl = trim($_POST['original_url']);

        if (filter_var($originalUrl, FILTER_VALIDATE_URL)) {
            $result = $urlService->create($userId, $originalUrl);
            echo '<p>' . htmlspecialchars($result['message']) . '</p>';
            if (!empty($result['short_code'])) {
                $result = $urlService->create($userId, $originalUrl);
                if ($result['success']) {
                    echo "<div class='h3'><class='text-success'>New URL:</class> <a href='http://localhost:8888/url-shortener/public/r.php?code=" . htmlspecialchars($result['short_code']) . "' target='_blank'>http://localhost:8888/url-shortener/public/r.php?code=" . htmlspecialchars($result['short_code']) . "</a></div>";
                    
                    //LIVE
                    // $baseUrl = "https://matthewhickling.co.uk/"; 
                    // $shortUrl = $baseUrl . "r.php?code=" . $result['short_code'];
                    // echo "<div class='h3'>Short URL: <a href='$shortUrl' target='_blank'>$shortUrl</a></div>";
                }
            }
        } else {
            echo '<p>Invalid URL. Please enter a valid URL.</p>';
        }
    }
}
?>
<script src="../assets/js/bootstrap.bundle.min.js"></script>


