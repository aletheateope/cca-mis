<?php
require_once '../../../vendor/autoload.php';

use Google\Service\Calendar;

function getGoogleClient()
{
    $serviceAccountPath = getenv('GOOGLE_SERVICE_ACCOUNT');
    
    $client = new Google_Client();
    $client->setAuthConfig($serviceAccountPath);
    $client->setScopes(Calendar::CALENDAR);

    return $client;
}
