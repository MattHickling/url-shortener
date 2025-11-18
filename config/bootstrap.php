<?php

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$HOSTNAME = env('DB_HOST');
$USERNAME = env('DB_USERNAME');
$PASSWORD = env('DB_PASSWORD');
$DB_NAME = env('DB_DATABASE');

$conn = mysqli_connect("$HOSTNAME", "$USERNAME", "$PASSWORD", "$DB_NAME"); 

if(!$conn){
    die(mysqli_error($conn));
} 

?>