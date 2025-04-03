<?php
require_once '../../../../sql/base-path.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["id"])) {
    echo json_encode(["success" => false, "message" => "Missing event ID"]);
    exit;
}

require_once BASE_PATH . '/assets/sql/conn.php';

$publicKey = $data["id"];

$stmt = $conn->prepare("SELECT event_request_id FROM key_event WHERE public_key = ?");
$stmt->bind_param("s", $publicKey);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$result) {
    echo json_encode(["success" => false, "message" => "Invalid public key"]);
    exit;
}

$event_request_id = $result["event_request_id"];

$stmt = $conn->prepare("UPDATE event_request_status SET status = 'Rejected' WHERE event_request_id = ?");
$stmt->bind_param("i", $event_request_id);
$success = $stmt->execute();
$stmt->close();
$conn->close();

if ($success) {
    echo json_encode(["success" => true, "message" => "Event has been rejected."]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to update event request status."]);
}

exit;
