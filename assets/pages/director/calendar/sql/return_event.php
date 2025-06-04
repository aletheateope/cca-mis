<?php
header("Content-Type: application/json");

require_once '../../../../sql/base_path.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit;
}

require_once BASE_PATH . '/assets/sql/conn.php';

$public_key = $_POST['public_key'];
$message = $_POST['message'];

if (empty($public_key) || empty($message)) {
    echo json_encode(["success" => false, "message" => "All fields are required."]);
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

if (!$event_request_id) {
    echo json_encode(["success" => false, "message" => "Invalid public key."]);
    exit;
}

$stmt->close();

$stmt = $conn->prepare("UPDATE event_request_status SET status = 'Returned' WHERE event_request_id = ?");
$stmt->bind_param("i", $event_request_id);

if (!$stmt->execute()) {
    echo json_encode(["success" => false, "message" => "Failed to update event request status."]);
    exit;
}

$stmt->close();

$stmt = $conn->prepare("INSERT INTO event_request_returned (event_request_id, message) VALUES (?, ?)");
$stmt->bind_param("is", $event_request_id, $message);

if (!$stmt->execute()) {
    echo json_encode(["success" => false, "message" => "Failed to insert return message."]);
    exit;
}

$stmt->close();

echo json_encode(["success" => true, "message" => "Event request returned successfully."]);
exit;
