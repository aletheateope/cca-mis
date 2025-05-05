<?php
header("Content-Type: application/json");

require_once '../../../../sql/base_path.php';

require_once BASE_PATH . '/assets/sql/conn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode((["success" => false, "message" => "Invalid request method"]));
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if(!isset($data['academic_year'])) {
    echo json_encode(["success" => false, "message" => "Missing academic year"]);
    exit;
}

$academic_year = $data['academic_year'];

$stmt = $conn->prepare("SELECT DISTINCT public_key, ao.name
                                FROM account_organization ao
                                INNER JOIN financial_statement_report frs
                                    ON frs.organization_id = ao.organization_id
                                INNER JOIN key_user ku
                                    ON ku.user_id = frs.organization_id
                                WHERE academic_year = ?");
$stmt->bind_param("s", $academic_year);
$stmt->execute();
$result = $stmt->get_result();

$orgs = [];
while ($row = $result->fetch_assoc()) {
    $orgs[] = $row;
}

if (empty($orgs)) {
    echo json_encode(["success" => false, "message" => "No organizations found"]);
    exit;
}


echo json_encode(["success" => true, "organizations" => $orgs]);
