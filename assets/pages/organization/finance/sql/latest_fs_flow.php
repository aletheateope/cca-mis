<?php
header("Content-Type: application/json");

require_once "../../../../sql/base_path.php";

require_once BASE_PATH . '/assets/sql/conn.php';

session_start();

$organization_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT total_credit, total_expenses, final_funding
                        FROM financial_statement fs
                        INNER JOIN financial_statement_report frs
                            ON frs.statement_id = fs.statement_id
                        WHERE organization_id = ?
                        ORDER BY frs.academic_year DESC, fs.year DESC, fs.month DESC LIMIT 1");
$stmt->bind_param("i", $organization_id);

if(!$stmt->execute()) {
    echo json_encode(["success" => false, "error" => "Error executing SQL"]);
    exit;
}

$stmt->bind_result($total_credit, $total_expenses, $final_funding);

if(!$stmt->fetch()) {
    $total_credit = 0;
    $total_expenses = 0;
    $final_funding = 0;
}

if ($total_credit > 0) {
    $total_credit_percentage = 100;
    $expenses_percentage = number_format(($total_expenses / $total_credit) * 100, 2);
    $final_funding_percentage = number_format(($final_funding / $total_credit) * 100, 2);
} else {
    $total_credit_percentage = 0;
    $expenses_percentage = 0;
    $final_funding_percentage = 0;
}

echo json_encode([
    "success" => true,
    "totalCredit" => $total_credit_percentage,
    "expenses" => $expenses_percentage,
    "finalFunding" => $final_funding_percentage,
]);

$stmt->close();
