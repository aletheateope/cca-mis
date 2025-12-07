<?php
require_once BASE_PATH . '/assets/sql/dotenv.php';

$localhost_url = 'http://localhost/cca';
$localhost3000_url = 'http://localhost:3000/cca';
$ngrok_url = 'https://9005-2001-4450-4737-cc00-58f7-6cd8-18b5-1049.ngrok-free.app'; // Change this every time ngrok restarts

// Set up the Google Client
$client = new Google_Client();
$client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
$client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
// $client->setRedirectUri('http://localhost/cca/assets/sql/login/google_callback.php');

if ($_SERVER['HTTP_HOST'] === 'localhost') {
    $redirect_uri = "$localhost_url/assets/sql/login/google_callback.php";
} elseif ($_SERVER['HTTP_HOST'] === 'localhost:3000') {
    $redirect_uri = "$localhost3000_url/assets/sql/login/google_callback.php";
} else {
    $redirect_uri = "$ngrok_url/cca/assets/sql/login/google_callback.php";
}

$client->setRedirectUri($redirect_uri);
