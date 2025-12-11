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

$public_key = $_POST['public_key'] ?? null;
$name = $_POST['name'] ?? null;
$email = $_POST['email'] ?? null;

$email = empty($email) ? null : $email;

if (empty($public_key) || empty($name)) {
    echo json_encode([
        "success" => false,
        "message" => "All fields are required"
    ]);
    exit;
}

$stmt = $conn->prepare("SELECT user_id
                        FROM account a
                        WHERE public_key = ? AND a.role = 'Organization'");
$stmt->bind_param("s", $public_key);
$stmt->execute();
$stmt->bind_result($user_id);
$stmt->fetch();

$stmt->close();

if (empty($user_id)) {
    echo json_encode([
        "success" => false,
        "message" => "Organization not found"
    ]);
    exit;
}

$stmt = $conn->prepare("UPDATE account SET email = ? WHERE user_id = ?");
$stmt->bind_param("si", $email, $user_id);
$stmt->execute();
$stmt->close();

$stmt = $conn->prepare("UPDATE account_organization SET name = ? WHERE organization_id = ?");
$stmt->bind_param("si", $name, $user_id);
$stmt->execute();
$stmt->close();

echo json_encode([
    "success" => true,
    "data" => [
        "public_key" => $public_key,
        "name" => $name,
        "email" => $email
    ]
]);
