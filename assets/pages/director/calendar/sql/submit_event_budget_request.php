<?php
header("content-type: application/json");

require_once '../../../../sql/base_path.php';

$folder_directory = BASE_PATH . '/uploads/budget_request_letter/';

if (!is_dir($folder_directory)) {
    mkdir($folder_directory, 0755, true);
}

require_once 'google_calendar/service_account.php';

use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;

$client = googleClient();
$service = new Calendar($client);

require_once 'google_calendar/calendar_id.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}

require_once BASE_PATH . '/assets/sql/conn.php';


$public_key = $_POST['public_key'] ?? null;
$input_budget = $_POST['budget'] ?? null;

$budget = floatval(str_replace(',', '', $input_budget));

if (empty($public_key) || empty($input_budget)) {
    echo json_encode(["success" => false, "message" => "Missing required fields."]);
    exit;
}

$stmt = $conn->prepare("SELECT event_request_id FROM key_event WHERE public_key = ?");
$stmt->bind_param("s", $public_key);

if(!$stmt->execute()) {
    echo json_encode(["success" => false, "message" => "Invalid public key."]);
    exit;
}

$stmt->bind_result($event_request_id);
$stmt->fetch();

if(!$event_request_id) {
    echo json_encode(["success" => false, "message" => "Invalid public key."]);
    exit;
}

$stmt->close();

if (empty($_FILES['req_letter']['name'][0])) {
    echo json_encode(["success" => false, "message" => "Please upload a request letter."]);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM event_request WHERE event_request_id = ?");
$stmt->bind_param("i", $event_request_id);

if(!$stmt->execute()) {
    echo json_encode(["success" => false, "message" => "Error fetching event request."]);
    exit;
}

$result = $stmt->get_result();

$row = $result->fetch_assoc();

if (!$row) {
    echo json_encode(["success" => false, "message" => "Event request not found."]);
    exit;
}

$stmt->close();

$calendar = $calendarID[$row['organization_id']] ?? $defaultCalendarID;

$stmt = $conn->prepare("INSERT INTO event_calendar (event_request_id, user_id, title, description, location, start_date, end_date, start_time, end_time, cancelled)
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0)");
$stmt->bind_param("iisssssss", $event_request_id, $row['organization_id'], $row['title'], $row['description'], $row['location'], $row['start_date'], $row['end_date'], $row['start_time'], $row['end_time']);

if (!$stmt->execute()) {
    echo json_encode(["success" => false, "message" => "Error inserting into event_calendar: " . $stmt->error]);
    exit;
}

$event_id = $stmt->insert_id;

$stmt->close();

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
$stmt->bind_param("si", $googleEventId, $event_request_id);
$stmt->execute();

$stmt ->close();

$stmt = $conn->prepare("DELETE FROM event_request WHERE event_request_id = ?");
$stmt->bind_param("i", $event_request_id);
$stmt->execute();

$stmt->close();

$stmt = $conn->prepare("UPDATE event_request_status SET status = 'Approved' WHERE event_request_id = ?");
$stmt->bind_param("i", $event_request_id);

if (!$stmt->execute()) {
    echo json_encode(["success" => false, "message" => "Error updating event request status: " . $stmt->error]);
    exit;
}

$stmt->close();

require_once BASE_PATH . '/assets/sql/event_key.php';

$stmt = $conn->prepare("INSERT INTO key_event (event_id, public_key) VALUES (?, ?)");
$stmt->bind_param("is", $event_id, $public_key);

if (!$stmt->execute()) {
    echo json_encode(["success" => false, "message" => "Error inserting event key: " . $stmt->error]);
    exit;
}

$stmt->close();

$uploaded_files = [];

foreach ($_FILES['req_letter']['tmp_name'] as $index => $tmpName) {
    if ($_FILES['req_letter']['error'][$index] === UPLOAD_ERR_OK) {
        $originalName = basename($_FILES['req_letter']['name'][$index]);
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);

        if (strtolower($extension) !== 'pdf') {
            echo json_encode(["success" => false, "message" => "Only PDF files are allowed."]);
            exit;
        }

        $uniqueFileName = time() . "_" . uniqid() . "." . $extension;
        $destination = $folder_directory . $uniqueFileName;
        $sqlFilePath = '/cca/uploads/budget_request_letter/' . $uniqueFileName;

        if (move_uploaded_file($tmpName, $destination)) {
            $uploaded_files[] = $uniqueFileName;

            $stmt = $conn->prepare("INSERT INTO event_budget_request (event_id, request_letter_path, amount_requested, status) VALUES (?, ?, ?, 'Pending')");
            $stmt->bind_param("isi", $event_id, $sqlFilePath, $budget);

            if (!$stmt->execute()) {
                echo json_encode(["success" => false, "message" => "Database error: " . $stmt->error]);
                exit;
            }

            $stmt->close();
        } else {
            echo json_encode(["success" => false, "message" => "Failed to upload one or more files."]);
            exit;
        }
    } else {
        echo json_encode(["success" => false, "message" => "Error uploading file: " . $_FILES['req_letter']['name'][$index]]);
        exit;
    }
}

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

$stmt = $conn->prepare("SELECT name FROM account_organization WHERE organization_id = ?");
$stmt->bind_param("i", $row['organization_id']);
$stmt->execute();

$stmt->bind_result($scheduled_by);
$stmt->fetch();

$stmt->close();


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
