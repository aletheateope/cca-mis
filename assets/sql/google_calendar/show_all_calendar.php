<?php
require_once '../base_path.php';
require_once  'service_account.php';

use Google\Service\Calendar;

try {
    $client = googleClient();
    $service = new Calendar($client);
    $calendarList = $service->calendarList->listCalendarList();

    foreach ($calendarList->getItems() as $calendar) {
        echo  "Calendar Name:" . $calendar->getSummary() .  "<br>" . "ID:" . $calendar->getId() . "<br>";
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}

// To delete all non-primary calendars, uncomment the code below
// foreach ($calendarList->getItems() as $calendar) {
//     $calendarId = $calendar->getId();

//     if ($calendar->getPrimary()) {
//         continue;
//     }

//     try {
//         $service->calendars->delete($calendarId);
//         echo "Deleted calendar: " . $calendar->getSummary() . "<br>";
//     } catch (Exception $e) {
//         echo "Error deleting calendar " . $calendar->getSummary() . ": " . $e->getMessage() . "<br>";
//     }
// }
