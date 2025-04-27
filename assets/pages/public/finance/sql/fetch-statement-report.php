<?php
header("Content-Type: application/json");

require_once '../../../../sql/base-path.php';

require_once BASE_PATH . '/assets/sql/conn.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['academicYear']) || !isset($data['organizationID'])) {
    echo json_encode(["error" => "Missing parameters"]);
    exit;
}

$academic_year = $data['academicYear'];
$organization_id = $data['organizationID'];

try {
    $stmt = $conn->prepare("SELECT dm.month_name as month, year, starting_fund, total_credit, total_expenses, final_funding
                            FROM financial_statement fs
                            INNER JOIN financial_statement_report frs
                                ON frs.statement_id = fs.statement_id
                            INNER JOIN date_month dm
                                ON dm.month_id = fs.month
                            WHERE organization_id = ? AND academic_year = ?
                            ORDER BY YEAR ASC, month_id ASC");
    $stmt->bind_param("is", $organization_id, $academic_year);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(["error" => "No data found"]);
        exit;
    }

    $results = [];

    while ($row = $result->fetch_assoc()) {
        $results[] = $row;
    }

    echo json_encode(["data" => $results]);

} catch(Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
    exit;
}
