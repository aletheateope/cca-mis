<?php
header("Content-Type: application/json");

require_once '../../../../sql/base_path.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['month'], $data['year'], $data['organization'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing parameters'
    ]);
    exit;
}

$month = $data['month'];
$year = $data['year'];
$organization = $data['organization'];

require_once BASE_PATH . '/assets/sql/conn.php';

$stmt = $conn->prepare("SELECT user_id FROM key_user WHERE public_key = ?");
$stmt->bind_param("s", $organization);

if (!$stmt->execute()) {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to execute statement'
    ]);
    exit;
}

$stmt->bind_result($organization_id);
$stmt->fetch();

$stmt->close();

if (!$organization_id) {
    echo json_encode([
        'success' => false,
        'message' => 'Organization not found'
    ]);
    exit;
}

$stmt = $conn->prepare("SELECT public_key, COALESCE(ec.title, aa.title) AS title
                        FROM accomplishment_report ar
                        INNER JOIN activity_accomplishment aa
                            ON ar.activity_id = aa.activity_id
                        LEFT JOIN event_calendar ec
                            ON ec.event_id = aa.event_id
                        INNER JOIN key_activity ka
                            ON ka.activity_id = aa.activity_id
                        WHERE ar.organization_id = ? AND ar.month = ? AND ar.year = ?");
$stmt->bind_param("iii", $organization_id, $month, $year);

if (!$stmt->execute()) {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to execute statement'
    ]);
    exit;
}

$result = $stmt->get_result();
$activities = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();

echo json_encode([
    'success' => true,
    'activities' => $activities
]);
