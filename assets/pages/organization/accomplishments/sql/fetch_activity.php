<?php
header("Content-Type: application/json");

require_once '../../../../sql/base_path.php';

require_once BASE_PATH . '/assets/sql/conn.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['publicKey'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Public key is required'
    ]);
    exit;
}

$publicKey = $input['publicKey'];

session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'User not logged in'
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT activity_id FROM key_activity WHERE public_key = ?");
$stmt->bind_param("s", $publicKey);
$stmt->execute();
$stmt->bind_result($activity_id);
$stmt->fetch();
$stmt->close();

if (!$activity_id) {
    echo json_encode([
        'success' => false,
        'message' => 'Activity not found'
    ]);
    exit;
}

$stmt = $conn->prepare("SELECT 1 FROM accomplishment_report ar WHERE ar.activity_id = ? AND ar.organization_id = ?");
$stmt->bind_param("ii", $activity_id, $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Activity not found'
    ]);
    exit;
}

$stmt->close();

$stmt = $conn->prepare("SELECT 
                        COALESCE(ec.title, aa.title) AS title,
                        COALESCE(ec.description, aa.description) AS description,
                        COALESCE(ec.location, aa.location) AS location,
                        COALESCE(ec.start_date, aa.start_date) AS start_date,
                        COALESCE(ec.end_date, aa.end_date) AS end_date,
                        ec.start_time, ec.end_time,
                        aa.target_participants, aa.actual_participants, aa.objectives, aa.challenges_solutions, aa.lesson_learned, aa.suggestions,
                        aa.budget_utilized, remarks
			            FROM activity_accomplishment aa
                        LEFT JOIN event_calendar ec
                            ON ec.event_id = aa.event_id
                        WHERE aa.activity_id = ?");
$stmt->bind_param("i", $activity_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

$stmt->close();

$stmt = $conn->prepare("SELECT ag.path AS image
                        FROM activity_gallery ag
                        WHERE ag.activity_id = ?");
$stmt->bind_param("i", $activity_id);
$stmt->execute();
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
