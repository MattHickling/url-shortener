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

<h2>Welcome <?php echo ucfirst(htmlspecialchars($currentUser)); ?>!</h2>

<form method="POST">
    What is the URL that you would like to shorten?: 
    <input type="text" name="original_url" required>
    <input type="submit" value="Shorten">
</form>

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
                    echo "Short URL: <a href='https://my-url/r.php?code=" . htmlspecialchars($result['short_code']) . "' target='_blank'>https://my-url/r.php?code=" . htmlspecialchars($result['short_code']) . "</a>";
                }

            }
        } else {
            echo '<p>Invalid URL. Please enter a valid URL.</p>';
        }
    }
}
?>


