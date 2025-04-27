<?php
header("Content-Type: application/json");

require_once 'conn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['public_key'])) {
    echo json_encode(["error" => "Missing public key"]);
    exit;
}

$public_key = $data['public_key'];

$stmt = $conn->prepare("SELECT organization_id, name
                        FROM key_user ku
                        INNER JOIN account_organization ao
                            ON ku.user_id = ao.organization_id
                        WHERE public_key = ?");
$stmt->bind_param("s", $public_key);
$stmt->execute();
$stmt->bind_result($organization_id, $organization_name);
$stmt->fetch();

if ($organization_id && $organization_name) {
    echo json_encode([
        "organization_id" => $organization_id,
        "organization_name" => $organization_name
    ]);
} else {
    echo json_encode(["error" => "Organization not found or invalid public key"]);
}

$stmt->close();
$conn->close();
