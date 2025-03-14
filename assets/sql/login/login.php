<?php
require_once 'assets/sql/base-path.php';

require_once BASE_PATH .'/vendor/autoload.php';

session_start();

$ngrok_url = 'https://5fe0-2001-4450-4737-cc00-f1c7-e92d-af47-5fca.ngrok-free.app'; // Change this every time ngrok restarts
$localhost_url = 'http://localhost/cca';

// Set up the Google Client
$client = new Google_Client();
$client->setClientId(getenv('GOOGLE_CLIENT_ID'));
$client->setClientSecret(getenv('GOOGLE_CLIENT_SECRET'));
// $client->setRedirectUri('http://localhost/cca/assets/sql/login/google_callback.php');

if ($_SERVER['HTTP_HOST'] === 'localhost') {
    $redirect_uri = "$localhost_url/assets/sql/login/google_callback.php";
} else {
    $redirect_uri = "$ngrok_url/cca/assets/sql/login/google_callback.php";
}

$client->setRedirectUri($redirect_uri);
$client->addScope("email");

$login_url = $client->createAuthUrl();
