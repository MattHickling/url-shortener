<?php
session_start();
include '../config/bootstrap.php';
require_once '../src/Auth/User.php';

use Src\Auth\User;

$user = new User($conn);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $user->create($_POST['username'], $_POST['email'], $_POST['password']);
    $message = $result['message'];
}

include '../templates/auth/register-template.php';
