<?php
header('Content-Type: application/json');

require_once '../../../../sql/base_path.php';

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

$stmt = $conn->prepare("SELECT title, description, location, start_date, end_date, budget_given FROM event_calendar WHERE event_id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$stmt->close();

echo json_encode([
    "success" => true,
    "event" => [
        "title" => $row["title"],
        "description" => $row["description"],
        "location" => $row["location"],
        "start" => $row["start_date"],
        "end" => $row["end_date"],
        "budgetGiven" => $row["budget_given"]
    ]
]);
