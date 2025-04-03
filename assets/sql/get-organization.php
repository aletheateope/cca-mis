<?php
session_start();

require_once 'conn.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT organization_id, name
        FROM account_organization
        WHERE organization_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row) {
    echo json_encode([
         "organization_id" => $row['organization_id'],
         "organization_name" => $row['name']
     ]);
} else {
    echo json_encode(["error" => "Organization not found"]);
}
