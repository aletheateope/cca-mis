<?php
header("Content-Type: application/json");

require_once '../../../../sql/base-path.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

require_once BASE_PATH . '/assets/sql/conn.php';

session_start();

$organization_id = $_SESSION['user_id'];

$data = json_decode(file_get_contents("php://input"), true);

$state_value = $data['state'] ?? 1;
$search_query = $data['query'] ?? '';


$stateMap = [
    1 => null,
    2 => 'Active',
    3 => 'Inactive',
    4 => 'Exited',
    5 => 'Terminated',
];

$state = $stateMap[(int)$state_value] ?? null;

$sql = "SELECT public_key, first_name, last_name, status, state, date_joined
        FROM student
        INNER JOIN student_organization so
            ON so.student_number = student.student_number
        INNER JOIN key_student ks
            ON ks.student_number = so.student_number
        WHERE organization_id = ?";

$params = [$organization_id];
$types = 'i';

if ($state !== null) {
    $sql .= " AND state = ?";
    $params[] = $state;
    $types .= "s";
}

if (!empty($search_query)) {
    $sql .= " AND (
        CONCAT(first_name, ' ', last_name) LIKE ? OR
        status LIKE ? OR
        state LIKE ?
    )";
    $searchTerm = '%' . $search_query . '%';
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= 'sss';
}

$sql .= " ORDER BY student.first_name ASC, student.last_name ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();

$result = $stmt->get_result();
$members = [];

while ($row = $result->fetch_assoc()) {
    $members[] = $row;
}

echo json_encode([
    "success" => true,
    "data" => $members
]);
