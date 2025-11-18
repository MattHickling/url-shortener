<?php
namespace Src\Auth;
session_start();
include '../config/bootstrap.php';
require_once '../src/Auth/AuthService.php';

use Src\Auth\AuthService;
$auth = new AuthService();
$error = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $error = $auth->login($_POST['email'], $_POST['password']);
}

include '../templates/auth/login.php';