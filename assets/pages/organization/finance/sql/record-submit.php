<?php
require_once '../../../../sql/base-path.php';

header("Content-Type: application/json");


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once BASE_PATH . '/assets/sql/conn.php';

    session_start();

    $user_id = $_SESSION['user_id'] ?? null;
    $statement_report_id = $_SESSION['statement_report_id'] ?? null;

    if (!$user_id || !$statement_report_id) {
        echo json_encode(["success" => false, "message" => "User not authenticated or report not selected."]);
        exit;
    }

    $sql = "SELECT statement_id FROM financial_statement_report WHERE report_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $statement_report_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        echo json_encode(["success" => false, "message" => "No matching statement found."]);
        exit;
    }

    $statement_id = $row['statement_id'];

    $fields = [
    'startingFund', 'weeklyContribution', 'internalProjects', 'externalProjects',
    'internalInitiativeFunding', 'donationsSponsorships', 'adviserCredit', 'carriCredit',
    'totalCredit', 'totalExpenses'
    ];

    foreach ($fields as $field) {
        $$field = isset($_POST[$field]) ? floatval(str_replace(',', '', $_POST[$field])) : 0.0;
    }

    $finalFunding = $totalCredit - $totalExpenses;

    $sql2 = "UPDATE financial_statement SET 
                    date_updated = now(),
                    starting_fund = ?,
                    weekly_contribution = ?,
                    internal_projects = ?,
                    external_projects = ?,
                    initiative_funding = ?,
                    donations_sponsorships = ?,
                    adviser_credit = ?,
                    carri_credit = ?,
                    total_credit = ?,
                    total_expenses = ?,
                    final_funding = ?
                    WHERE statement_id = ?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param(
        "dddddddddddi",
        $startingFund,
        $weeklyContribution,
        $internalProjects,
        $externalProjects,
        $internalInitiativeFunding,
        $donationsSponsorships,
        $adviserCredit,
        $carriCredit,
        $totalCredit,
        $totalExpenses,
        $finalFunding,
        $statement_id,
    );
    if ($stmt2->execute()) {
        unset($_SESSION['statement_report_id']);
        echo json_encode(["success" => true, "message" => "Record successfully Submitted."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . $stmt2->error]);
    }
    $stmt->close();
    $stmt2->close();
    $conn->close();

} else {
    echo json_encode(["success" => false, "message" => "No matching statement found."]);
}
