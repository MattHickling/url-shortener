<?php
require '../config/bootstrap.php';

use Src\Auth\Logout;

$logout = new Logout();
$logout->execute();
