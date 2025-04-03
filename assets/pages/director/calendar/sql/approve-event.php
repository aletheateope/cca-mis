<?php
header("content-type: application/json");

require_once '../../../../sql/base-path.php';

require_once BASE_PATH . '/assets/sql/calendar/service-account.php';

require_once BASE_PATH . '/assets/sql/public_key.php';

use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;

$data = json_decode(file_get_contents("php://input"), true);

$client = googleClient();
$service = new Calendar($client);

require_once BASE_PATH . '/assets/sql/calendar/calendar-id.php';

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
                $count = 1;

                do {
                    $public_key = generatePublicKey();
                    $sql = "SELECT COUNT(*) FROM key_event WHERE public_key = ?";
                    $stmt2 = $conn->prepare($sql);
                    $stmt2->bind_param("s", $public_key);
                    $stmt2->execute();
                    $stmt2->bind_result($count);
                    $stmt2->fetch();
                    $stmt2->close();
                } while ($count > 0);


                $insert = $conn->prepare("INSERT INTO key_event (event_id, public_key) VALUES (?,?)");
                $insert->bind_param("is", $eventID, $public_key);
                $insert->execute();

            }

            
            $conn->commit();
            echo json_encode(["success" => true, "message" => "Event request approved successfully."]);
        } else {
            throw new Exception("Event request not found.");
        }
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
}
