<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}
include '../config/bootstrap.php';
include '../templates/header.php';

use Src\Auth\User;
use Src\Auth\AuthService;
$email = $_SESSION['email'];
$user = new User($conn);
$currentUser = $user->getUsername($email);
// print_r($currentUser);

?>
<h2>Welcome <?php echo ucfirst($currentUser); ?>!</h2>