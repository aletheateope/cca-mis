<?php
header('Content-Type: application/json');

require_once '../../../../sql/base_path.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

require_once BASE_PATH . '/assets/sql/conn.php';

$input = json_decode(file_get_contents('php://input'), true);

if(!isset($input['id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing public key']);
    exit;
}

$public_key = $input['id'];

$stmt = $conn->prepare("SELECT title, ao.name AS organization, amount_requested
                        FROM event_calendar ec
                        INNER JOIN account_organization ao
                            ON ao.organization_id = ec.user_id
                        INNER JOIN event_budget_request ebr
                            ON ebr.event_id = ec.event_id
                        INNER JOIN key_event ke
                            ON ke.event_id = ec.event_id
                        WHERE ke.public_key = ?");

$stmt->bind_param('s', $public_key);

if(!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Not Found']);
    exit;
}
$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo json_encode([
    'success' => true,
    'title' => $row['title'],
    'organization' => $row['organization'],
    'amount_requested' => $row['amount_requested']
]);

exit;
