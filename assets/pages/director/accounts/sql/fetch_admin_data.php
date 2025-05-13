<?php
header("Content-Type: application/json");

require_once '../../../../sql/base_path.php';

require_once BASE_PATH . '/assets/sql/conn.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method"
    ]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$public_key = $data['publicKey'];

$stmt = $conn->prepare("SELECT a.email, a.role, aa.first_name, aa.last_name
                        FROM account a
                        INNER JOIN account_admin aa
                            ON aa.admin_id = a.user_id
                        INNER JOIN key_user ku
                            ON ku.user_id = a.user_id
                        WHERE ku.public_key = ?");

$stmt->bind_param("s", $public_key);

if (!$stmt->execute()) {
    echo json_encode([
        "success" => false,
        "message" => "Failed to execute query"
    ]);
    exit;
}

$stmt->bind_result($email, $role_name, $firstName, $lastName);

$roles = [
    "Director" => "1",
    "VPSLD" => "2",
];

$stmt->fetch();

$stmt->close();

echo json_encode([
    "success" => true,
    "result" => [
        "role" => $roles[$role_name],
        "first_name" => $firstName,
        "last_name" => $lastName,
        "email" => $email,
    ]
]);
