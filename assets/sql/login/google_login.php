<?php
// require_once 'assets/sql/base_path.php';

require_once BASE_PATH .'/vendor/autoload.php';

session_start();

require_once 'google_client.php';

$client->addScope("email");
$client->setPrompt('select_account');

$acceptLang = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'en';
$preferredLang = substr($acceptLang, 0, 2);


$login_url = $client->createAuthUrl() . '&hl=' . $preferredLang;
