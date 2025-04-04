<?php
header("Content-Type: application/json");

require_once '../../../../sql/base-path.php';

require_once BASE_PATH . '/assets/sql/conn.php';

$data = json_decode(file_get_contents("php://input"), true);

if(!isset($data['id'])) {
    echo json_encode(["error" => "Missing id"]);
    exit;
}

$publicKey = strval($data["id"]);

$sql = "SELECT event_request_id FROM key_event WHERE public_key = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $publicKey);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if (!$result) {
    echo json_encode(["error" => "Invalid public key"]);
    exit;
}

$requestEventID = $result['event_request_id'];

if(!$requestEventID) {
    echo json_encode(["error" => "Invalid public key"]);
    exit;
}

$sql = "SELECT title FROM event_request WHERE event_request_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $requestEventID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(["title" => $row['title']]);
} else {
    echo json_encode(["error" => "Event not found"]);
}

$stmt->close();
$conn->close();
