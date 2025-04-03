<?php
header("Content-Type: application/json");

require_once '../../../../sql/base-path.php';

require_once BASE_PATH . '/assets/sql/conn.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

if (!isset($_GET["month"]) || !isset($_GET["year"])) {
    echo json_encode(["error" => "Invalid parameters"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$month = (int) $_GET['month'];
$year = (int) $_GET['year'];

$sql = "SELECT academic_year, date_updated, starting_fund, weekly_contribution, internal_projects, external_projects, initiative_funding, donations_sponsorships, adviser_credit, carri_credit, total_credit, total_expenses, final_funding
        FROM financial_statement fs
        INNER JOIN financial_statement_report frs
            ON frs.statement_id = fs.statement_id
        WHERE organization_id = ? AND month = ? AND year = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $user_id, $month, $year);
$stmt->execute();
$result = $stmt->get_result();

$data = $result->fetch_assoc();

echo json_encode($data ?: ["error" => "No Data Available"]);
exit;
