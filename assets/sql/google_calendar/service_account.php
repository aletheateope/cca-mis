<?php
// require_once '../base-path.php';
require_once BASE_PATH . '/vendor/autoload.php';
require_once BASE_PATH . '/assets/sql/dotenv.php';

use Google\Client;
use Google\Service\Calendar;

function googleClient()
{
    $serviceAccountPath = $_ENV['GOOGLE_SERVICE_ACCOUNT'];
    
    $client = new Client();
    $client->setAuthConfig($serviceAccountPath);
    $client->setScopes(Calendar::CALENDAR);

    return $client;
}
