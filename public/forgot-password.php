<?php
session_start();
include '../config/bootstrap.php';
require_once '../src/Auth/User.php';
require_once '../src/Auth/AuthService.php';

use Src\Auth\User;
use Src\Auth\AuthService;

$user = new User($conn);
$auth = new AuthService($user);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $auth->forgotPassword($_POST['email'], $_POST['new_password']);
    $message = $result['message'];
}

include '../templates/auth/forgot-password-template.php';
