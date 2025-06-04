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

if (!isset($data['publicKey'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Public key is required'
    ]);
    exit;
}

$public_key = $data['publicKey'];

require_once BASE_PATH . '/assets/sql/conn.php';

$stmt = $conn->prepare("SELECT activity_id
                        FROM key_activity
                        WHERE public_key = ?");
$stmt->bind_param("s", $public_key);

if (!$stmt->execute()) {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to execute statement'
    ]);
    exit;
}

$stmt->bind_result($activity_id);
$stmt->fetch();
$stmt->close();

$stmt = $conn->prepare("SELECT 
                        COALESCE(ec.title, aa.title) AS title,
                        COALESCE(ec.description, aa.description) AS description,
                        COALESCE(ec.location, aa.location) AS location,
                        COALESCE(ec.start_date, aa.start_date) AS start_date,
                        COALESCE(ec.end_date, aa.end_date) AS end_date,
                        ec.start_time, ec.end_time,
                        ec.budget_given, aa.target_participants,
                        aa.actual_participants, aa.objectives, aa.challenges_solutions, aa.lesson_learned, aa.suggestions,
                        aa.budget_utilized, remarks
			            FROM activity_accomplishment aa
                        LEFT JOIN event_calendar ec
                            ON ec.event_id = aa.event_id
                        WHERE aa.activity_id = ?");
$stmt->bind_param("i", $activity_id);

if (!$stmt->execute()) {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to execute statement'
    ]);
    exit;
}

$result = $stmt->get_result()->fetch_assoc();

$stmt->close();

$stmt = $conn->prepare("SELECT ag.path AS image
                        FROM activity_gallery ag
                        WHERE ag.activity_id = ?");
$stmt->bind_param("i", $activity_id);

if (!$stmt->execute()) {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to execute statement'
    ]);
    exit;
}

$resultSet = $stmt->get_result();
$gallery = $resultSet->fetch_all(MYSQLI_ASSOC);

if (!empty($gallery)) {
    $gallery = array_column($gallery, 'image');
} else {
    $gallery = null;
}

$stmt->close();

echo json_encode([
    'success' => true,
    'result' => $result,
    'gallery' => $gallery
]);
