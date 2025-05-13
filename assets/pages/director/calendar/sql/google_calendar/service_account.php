<?php
// require_once '../base-path.php';
require_once BASE_PATH . '/vendor/autoload.php';

use Google\Service\Calendar;

function googleClient()
{
    $serviceAccountPath = getenv('GOOGLE_SERVICE_ACCOUNT');
    
    $client = new Google_Client();
    $client->setAuthConfig($serviceAccountPath);
    $client->setScopes(Calendar::CALENDAR);

    return $client;
}
