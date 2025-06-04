<?php
header("content-type: application/json");

require_once '../../../../sql/base_path.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Invalid request method."]);
    exit;
}

require_once BASE_PATH . '/assets/sql/conn.php';

$input = json_decode(file_get_contents('php://input'), true);

if(!isset($input['id'])) {
    echo json_encode(["success" => false, "message" => "Missing required fields."]);
    exit;
}

$public_key = $input['id'];

$stmt = $conn->prepare("UPDATE event_budget_request ebr
                        INNER JOIN key_event ke
                            ON ke.event_id = ebr.event_id
                        SET ebr.status = 'Rejected'
                        WHERE ke.public_key = ?");
$stmt->bind_param("s", $public_key);

if (!$stmt->execute()) {
    echo json_encode(["success" => false, "message" => "Failed to reject budget request."]);
    exit;
}

echo json_encode(["success" => true, "message" => "Budget request rejected successfully."]);
