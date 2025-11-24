<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

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
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <div>
            What is the URL that you would like to shorten?: 
        </div>
        <input type="text" name="original_url" class="form-control mt-2 mb-4"  required>
        <input type="submit" class="btn btn-primary form-control"  value="Submit">
    </form>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

     if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        echo '<p class="text-center text-danger mt-3">Invalid request. Please try again.</p>';
        exit();
    }
    unset($_SESSION['csrf_token']);

    if (isset($_POST['original_url'])) {
        $originalUrl = trim($_POST['original_url']);

        if (filter_var($originalUrl, FILTER_VALIDATE_URL)) {
            $result = $urlService->create($userId, $originalUrl);

            if (!empty($result['message']) || !empty($result['short_code'])) {
                echo '<div class="d-flex flex-column justify-content-center align-items-center mt-5">';

                if (!empty($result['message'])) {
                    echo '<p class="display-6 text-success text-center mb-3">' . htmlspecialchars($result['message']) . '</p>';
                }

                // LOCAL OR LIVE
                if (!empty($result['short_code'])) {
                    $shortUrl = ($_ENV['APP_ENV'] == 'local')
                        ? "http://localhost:8888/url-shortener/public/r.php?code=" . htmlspecialchars($result['short_code'])
                        : "https://matthewhickling.co.uk/r.php?code=" . htmlspecialchars($result['short_code']);

                    echo '<p class="h4 text-center"><a href="' . $shortUrl . '" target="_blank">' . $shortUrl . '</a></p>';
                }

                echo '</div>';
            }

        } else {
            echo '<p class="text-center text-danger mt-3">Invalid URL. Please enter a valid URL.</p>';
        }
    }
}

?>
<script src="../assets/js/bootstrap.bundle.min.js"></script>


