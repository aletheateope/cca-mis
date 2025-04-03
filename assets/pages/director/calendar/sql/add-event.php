<?php
require_once '../../../../sql/base-path.php';

require_once BASE_PATH . '/assets/sql/calendar/service-account.php';

session_start();

use Google\Service\Calendar;
use Google\Service\Calendar\Event;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once BASE_PATH . '/assets/sql/conn.php';

    $title = $_POST['title'] ?? null;
    $description = $_POST['description'] ?? null;
    $location = $_POST['location'] ?? null;
    $start_date = $_POST['start_date'] ?? null;
    $end_date = $_POST['end_date'] ?? null;
    $start_time = !empty($_POST['start_time']) ? $_POST['start_time'] : null;
    $end_time = !empty($_POST['end_time']) ? $_POST['end_time'] : null;

    $user_id = $_SESSION['user_id'] ?? null;

    $sql = "INSERT INTO event_calendar (user_id, title, description, location, start_date, end_date, start_time, end_time, cancelled) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssss", $user_id, $title, $description, $location, $start_date, $end_date, $start_time, $end_time);

    
    $response = [];

    if ($stmt->execute()) {
        $event_id = $stmt->insert_id;

        try {
            $client = googleClient();
            $service = new Calendar($client);
            $calendarId = 'b0fbd9a70cb6dc036145293bde72651550e437d3a0c304bd4e9c90428d829603@group.calendar.google.com';

            $eventData = [
                 'summary' => $title,
                 'location' => $location,
                 'description' => $description,
                 'start' => [
                     'timeZone' => 'Asia/Manila',
                 ],
                 'end' => [
                     'timeZone' => 'Asia/Manila',
                 ],
             ];

            if ($start_time && $end_time) {
                $eventData['start']['dateTime'] = "{$start_date}T{$start_time}:00";
                $eventData['end']['dateTime'] = "{$end_date}T{$end_time}:00";
            } else {
                $eventData['start']['date'] = $start_date;
                $eventData['end']['date'] = $end_date;
            }


            $event = new Event($eventData);
            $createdEvent = $service->events->insert($calendarId, $event);
            $google_event_id = $createdEvent->getId();

            $update_sql = "UPDATE event_calendar SET google_event_id = ? WHERE event_id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("si", $google_event_id, $event_id);
            $update_stmt->execute();
            $update_stmt->close();


            echo json_encode(["status" => "success", "message" => "Event request submitted & added to Google Calendar"]);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => "Google Calendar API error: " . $e->getMessage()]);
        }

    } else {
        $response = ["status" => "error", "message" => "Failed to add event: " . $stmt->error];
    }

    $stmt->close();
    $conn->close();
    echo json_encode($response);
}
