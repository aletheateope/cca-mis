<?php
header('Content-Type: application/json');

require_once '../../../../sql/base_path.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

require_once BASE_PATH . '/assets/sql/conn.php';

$public_key = $_POST['public_key'] ?? null;

if (empty($public_key)) {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['id'])) {
        echo json_encode(['success' => false, 'message' => 'Missing public key']);
        exit;
    }

    $public_key = $input['id'] ?? null;
}


$amount = null;

if (!empty($_POST['amount'])) {
    $amount = floatval(str_replace(',', '', $_POST['amount']));
}

$stmt = $conn->prepare("SELECT event_id FROM key_event WHERE public_key = ?");
$stmt->bind_param('s', $public_key);

if(!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Error executing query']);
    exit;
}

$stmt->bind_result($event_id);
$stmt->fetch();

$stmt->close();

if ($amount === null) {
    $stmt = $conn->prepare("SELECT amount_requested FROM event_budget_request WHERE event_id = ?");
    $stmt->bind_param('i', $event_id);

    if (!$stmt->execute()) {
        echo json_encode(['success' => false, 'message' => 'Error executing query']);
        exit;
    }

    $stmt->bind_result($amount);
    $stmt->fetch();
    $stmt->close();
}

$stmt = $conn->prepare("UPDATE event_calendar SET budget_given = ? WHERE event_id = ?");
$stmt->bind_param('di', $amount, $event_id);

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Error executing query']);
    exit;
}

$stmt->close();

$stmt = $conn->prepare("UPDATE event_budget_request SET status = 'Approved' WHERE event_id = ?");
$stmt->bind_param('i', $event_id);

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Error executing query']);
    exit;
}

$stmt->close();

echo json_encode(['success' => true, 'message' => 'Budget approved successfully']);
$conn->close();
