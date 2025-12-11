<?php
header("Content-Type: application/json");

require_once '../../../../sql/base_path.php';

require_once BASE_PATH . '/assets/sql/conn.php';

$data = json_decode(file_get_contents("php://input"), true);

if(!isset($data["publicKey"])) {
    echo json_encode([
        "success" => false,
        "message" => "Public key is required"
    ]);
    exit;
}

$public_key = $data["publicKey"];

$stmt = $conn->prepare("SELECT a.user_id FROM account a
                        INNER JOIN key_user ku
                            ON ku.user_id = a.user_id
                        WHERE ku.public_key = ?");

$stmt->bind_param("s", $public_key);

if(!$stmt->execute()) {
    echo json_encode([
        "success" => false,
        "message" => "Failed to execute query" . $stmt->error
    ]);
    exit;
}

$stmt->bind_result($user_id);

if(!$stmt->fetch()) {
    echo json_encode([
        "success" => false,
        "message" => "User not found"
    ]);
    exit;
}

$stmt->close();

$stmt = $conn->prepare("DELETE FROM account WHERE user_id = ?");

$stmt->bind_param("i", $user_id);

if(!$stmt->execute()) {
    echo json_encode([
        "success" => false,
        "message" => "Failed to execute query" . $stmt->error
    ]);
    exit;
}

echo json_encode([
    "success" => true,
]);

exit;
