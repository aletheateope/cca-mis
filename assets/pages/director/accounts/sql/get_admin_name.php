<?php
header("Content-Type: application/json");

require_once "../../../../sql/base_path.php";

require_once BASE_PATH . '/assets/sql/conn.php';

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method"
    ]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if(!isset($data["publicKey"])) {
    echo json_encode([
        "success" => false,
        "message" => "Public key is required"
    ]);
    exit;
}

$public_key = $data["publicKey"];

$stmt = $conn->prepare("SELECT first_name, last_name
                        FROM account_admin aa
                        INNER JOIN key_user ku
                            ON ku.user_id = aa.admin_id
                        WHERE ku.public_key = ?");

$stmt->bind_param("s", $public_key);

if(!$stmt->execute()) {
    echo json_encode([
        "success" => false,
        "message" => "Failed to execute query" . $stmt->error
    ]);
    exit;
}

$stmt->store_result();

if($stmt->num_rows == 0) {
    echo json_encode([
        "success" => false,
        "message" => "Admin not found"
    ]);
    exit;
}

$stmt->bind_result($first_name, $last_name);
$stmt->fetch();

echo json_encode([
    "success" => true,
    "firstName" => $first_name,
    "lastName" => $last_name
]);

exit;
