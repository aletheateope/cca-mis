<?php
header("Content-Type: application/json");

require_once '../../../../sql/base-path.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

require_once BASE_PATH . '/assets/sql/conn.php';

$data = json_decode(file_get_contents("php://input"), true);

$search_query = $data['query'] ?? '';
$organization = $data['organization'] ?? '';
$state_value = $data['state'] ?? 1;

$stateMap = [
    1 => null,
    2 => 'Active',
    3 => 'Inactive',
    4 => 'Exited',
    5 => 'Terminated',
];

$state = $stateMap[(int)$state_value] ?? null;

$sql = "SELECT ks.public_key, first_name, last_name, ao.name AS organization, status, state, date_joined
        FROM student
        INNER JOIN student_organization so
            ON so.student_number = student.student_number
        INNER JOIN account_organization ao
            ON ao.organization_id = so.organization
        INNER JOIN key_student ks
            ON ks.student_number = so.student_number
        INNER JOIN key_user ku
            ON ku.user_id = so.organization";

$conditions = [];
$params = [];
$types = '';

if ($state !== null) {
    $conditions[] = "state = ?";
    $params[] = $state;
    $types .= "s";
}

if (!empty($search_query)) {
    $conditions [] = "(
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

if (!empty($organization) && $organization !== '0') {
    $conditions[] = "ku.public_key = ?";
    $params[] = $organization;
    $types .= "s";
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " ORDER BY student.first_name ASC, student.last_name ASC";

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

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
