<?php
header("content-type: application/json");

require_once '../../../../sql/base_path.php';

require_once 'google_calendar/service_account.php';

use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;

$data = json_decode(file_get_contents("php://input"), true);

$client = googleClient();
$service = new Calendar($client);

require_once 'google_calendar/calendar_id.php';

if(isset($data["id"])) {
    require_once BASE_PATH . '/assets/sql/conn.php';
    $publicKey = $data["id"];

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("SELECT er.*
                                FROM event_request er
                                INNER JOIN key_event ke
                                    ON ke.event_request_id = er.event_request_id
                                WHERE public_key = ?");
        $stmt->bind_param("s", $publicKey);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $calendar = $calendarID[$row['organization_id']] ?? $defaultCalendarID;

            $stmt = $conn->prepare("INSERT INTO event_calendar (event_request_id, user_id, title, description, location, start_date, end_date, start_time, end_time, cancelled)
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0)");
            $stmt->bind_param("iisssssss", $row['event_request_id'], $row['organization_id'], $row['title'], $row['description'], $row['location'], $row['start_date'], $row['end_date'], $row['start_time'], $row['end_time']);
            $stmt->execute();

            $eventID = $stmt->insert_id;

            $event = new Event([
                'summary' => $row['title'],
                'location' => $row['location'],
                'description' => $row['description'],
            ]);

            // With Time
            if ($row['start_time'] && $row['end_time']) {
                $event->setStart(new EventDateTime([
                    'dateTime' => $row['start_date'] . 'T' . $row['start_time'],
                    'timeZone' => 'Asia/Manila'
                ]));
                $event->setEnd(new EventDateTime([
                    'dateTime' => $row['end_date'] . 'T' . $row['end_time'],
                    'timeZone' => 'Asia/Manila'
                ]));
            } else {
                // All Day
                $event->setStart(new EventDateTime([
                    'date' => $row['start_date'],
                    'timeZone' => 'Asia/Manila'
                ]));
                $event->setEnd(new EventDateTime([
                    'date' => $row['end_date'],
                    'timeZone' => 'Asia/Manila'
                ]));
            }

            $event = $service->events->insert($calendar, $event);
            $googleEventId = $event->id;


            $stmt = $conn->prepare("UPDATE event_calendar SET google_event_id = ? WHERE event_request_id = ?");
            $stmt->bind_param("si", $googleEventId, $row['event_request_id']);
            $stmt->execute();

            
            $stmt = $conn->prepare("UPDATE event_request_status SET status = 'Approved' WHERE event_request_id = ?");
            $stmt->bind_param("i", $row['event_request_id']);
            $stmt->execute();
            
            $deleteStmt = $conn->prepare("DELETE FROM event_request WHERE event_request_id = ?");
            $deleteStmt->bind_param("i", $row['event_request_id']);
            if ($deleteStmt->execute()) {
                
                require_once BASE_PATH . '/assets/sql/event_key.php';

                $insert = $conn->prepare("INSERT INTO key_event (event_id, public_key) VALUES (?,?)");
                $insert->bind_param("is", $eventID, $public_key);
                $insert->execute();

            }

            $conn->commit();

            $colors = [
                1 => '#000000', // BLCK MVMNT
                2 => '#4451A4', // Chorale
                3 => '#E97536', // Dulangsining
                4 => '#DDCA4C', // Euphoria
                5 => '#E4AC45', // FDC
                6 => '#785943', // Kultura Teknika
            ];

            $backgroundColor = $colors[$row['organization_id']] ?? null;
            $borderColor = $backgroundColor;


            $sql = $conn ->prepare("SELECT name FROM account_organization WHERE organization_id = ?");
            $sql->bind_param("i", $row['organization_id']);
            $sql->execute();
            $sql->bind_result($scheduled_by);
            $sql->fetch();
            $sql->close();

            $hasTime = !empty($row['start_time']) && !empty($row['end_time']);

            $start = $hasTime
                ? $row['start_date'] . 'T' . $row['start_time']
                : $row['start_date'];

            $end = $hasTime
                ? $row['end_date'] . 'T' . $row['end_time']
                : $row['end_date'];

            echo json_encode([
                "success" => true,
                "event" => [
                    "id" => $public_key,
                    "title" => $row['title'],
                    "start" => $start,
                    "end" => $end,
                    "allDay" => !$hasTime,
                    'extendedProps' => [
                        'description' => $row['description'],
                        'location' => $row['location'],

                        'scheduled_by' => $scheduled_by,
                    ],
                    'textColor' => '#ffffff',
                    'backgroundColor' => $backgroundColor,
                    'borderColor' => $borderColor,
                ],
            ]);
        } else {
            throw new Exception("Event request not found.");
        }
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
}
