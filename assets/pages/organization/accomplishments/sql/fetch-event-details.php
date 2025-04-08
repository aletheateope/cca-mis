<?php
header('Content-Type: application/json');

require_once '../../../../sql/base-path.php';

require_once BASE_PATH . '/assets/sql/conn.php';

if(!isset($_GET['public_key'])) {
    echo json_encode(["success" => false, "message" => "Missing public key"]);
    exit;
}

$public_key = $_GET['public_key'];

$stmt = $conn->prepare("SELECT event_id FROM key_event WHERE public_key = ?");
$stmt->bind_param("s", $public_key);
$stmt->execute();
$stmt->bind_result($event_id);
$stmt->fetch();

$stmt->close();

$stmt = $conn->prepare("SELECT title, description, location, start_date, end_date FROM event_calendar WHERE event_id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$stmt->bind_result($title, $description, $location, $start_date, $end_date);
$stmt->fetch();

$stmt->close();

echo json_encode([
    "success" => true,
    "event" => [
        "title" => $title,
        "description" => $description,
        "location" => $location,
        "start" => $start_date,
        "end" => $end_date
    ]
]);
