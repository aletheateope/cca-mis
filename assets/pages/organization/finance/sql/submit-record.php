<?php
require_once '../../../../sql/base-path.php';

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once BASE_PATH . '/assets/sql/conn.php';

    session_start();

    $user_id = $_SESSION['user_id'] ?? null;
    $statement_id = $_SESSION['statement_id'] ?? null;

    $startindFund = isset($_POST['startingFund']) ? str_replace(',', '', $_POST['startingFund']) : null;
    $weeklyContribution = isset($_POST['weeklyContribution']) ? str_replace(',', '', $_POST['weeklyContribution']) : null;
    $internalProjects = isset($_POST['internalProjects']) ? str_replace(',', '', $_POST['internalProjects']) : null;
    $externalProjects = isset($_POST['externalProjects']) ? str_replace(',', '', $_POST['externalProjects']) : null;
    $internalInitiativeFunding = isset($_POST['internalInitiativeFunding']) ? str_replace(',', '', $_POST['internalInitiativeFunding']) : null;
    $donationsSponsorships = isset($_POST['donationsSponsorships']) ? str_replace(',', '', $_POST['donationsSponsorships']) : null;
    $adviserCredit = isset($_POST['adviserCredit']) ? str_replace(',', '', $_POST['adviserCredit']) : null;
    $carriCredit = isset($_POST['carriCredit']) ? str_replace(',', '', $_POST['carriCredit']) : null;

    $totalCredit = isset($_POST['totalCredit']) ? str_replace(',', '', $_POST['totalCredit']) : null;
    $totalExpenses = isset($_POST['totalExpenses']) ? str_replace(',', '', $_POST['totalExpenses']) : null;

    $finalFunding = isset($totalCredit, $totalExpenses) ? ($totalCredit - $totalExpenses) : null;

    $sql = "UPDATE financial_statement SET 
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
            WHERE statement_id = ? AND organization_id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "dddddddddddii",
        $startindFund,
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
        $user_id
    );

    if ($stmt->execute()) {
        unset($_SESSION['statement_id']);
        echo json_encode(["success" => true, "message" => "Record successfully Submitted."]);

    } else {
        echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);

    }

    $stmt->close();
    $conn->close();
}
