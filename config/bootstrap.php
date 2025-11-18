<?php


require_once('../vendor/autoload.php');
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..'); 
$dotenv->load();

$HOSTNAME = $_ENV['DB_HOST'];
$USERNAME = $_ENV['DB_USERNAME'];
$PASSWORD = $_ENV['DB_PASSWORD'];
$DB_NAME = $_ENV['DB_DATABASE'];

$conn = mysqli_connect("$HOSTNAME", "$USERNAME", "$PASSWORD", "$DB_NAME"); 

if(!$conn){
    die(mysqli_error($conn));
} 

?>