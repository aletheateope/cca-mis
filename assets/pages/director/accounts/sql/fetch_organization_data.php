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

$stmt = $conn->prepare("SELECT public_key, a.email, ao.name
                        FROM account a
                        INNER JOIN account_organization ao
                            ON ao.user_id = a.user_id
                        WHERE public_key = ?");

$stmt->bind_param("s", $public_key);

if (!$stmt->execute()) {
    echo json_encode([
        "success" => false,
        "message" => "Failed to execute query"
    ]);
    exit;
}

$stmt->bind_result($public_key, $email, $name);

$stmt->fetch();

$stmt->close();

echo json_encode([
    "success" => true,
    "result" => [
        "public_key" => $public_key,
        "name" => $name,
        "email" => $email,
    ]
]);
