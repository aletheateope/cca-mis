<?php
require_once '../../../../sql/base-path.php';

require_once BASE_PATH . '/assets/sql/conn.php';

session_start();

$user_id = $_SESSION['user_id'];

$query = "SELECT 1 FROM financial_statement_report WHERE organization_id = ? LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();

$exists = $stmt->num_rows > 0;

echo json_encode(["exists" => $exists]);
