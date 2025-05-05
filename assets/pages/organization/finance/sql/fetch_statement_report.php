<?php
require_once '../../../../sql/base_path.php';

header("Content-Type: application/json");

require_once BASE_PATH . '/assets/sql/conn.php';

session_start();

$user_id = $_SESSION['user_id'];
$academicYear = $_GET['academicYear'];

$sql = "SELECT dm.month_name as month, year, starting_fund, total_credit, total_expenses, final_funding
        FROM financial_statement fs
        INNER JOIN financial_statement_report frs
            ON frs.statement_id = fs.statement_id
        INNER JOIN date_month dm
        	ON dm.month_id = fs.month
        WHERE organization_id = ? AND academic_year = ?
        ORDER BY frs.statement_id ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param('is', $user_id, $academicYear);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
