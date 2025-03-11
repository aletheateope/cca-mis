<?php
require_once 'assets/sql/base-path.php';

require_once BASE_PATH .'/vendor/autoload.php';

session_start();

// Set up the Google Client
$client = new Google_Client();
$client->setClientId(getenv('GOOGLE_CLIENT_ID'));
$client->setClientSecret(getenv('GOOGLE_CLIENT_SECRET'));
$client->setRedirectUri('http://localhost/cca/assets/sql/login/google_callback.php');
$client->addScope("email");

$login_url = $client->createAuthUrl();
