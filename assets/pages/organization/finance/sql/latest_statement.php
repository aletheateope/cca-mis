<?php
require_once BASE_PATH . '/assets/sql/conn.php';

$organization_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT fsr.academic_year, dm.month_name AS month, year, starting_fund, weekly_contribution, internal_projects, external_projects,
                        initiative_funding, donations_sponsorships, adviser_credit, carri_credit, total_credit, total_expenses, final_funding
                        FROM financial_statement fs
                        INNER JOIN financial_statement_report fsr 
                            ON fsr.statement_id = fs.statement_id
                        INNER JOIN date_month dm
                            ON fs.month = dm.month_id
                        WHERE organization_id = ?
                        ORDER BY fsr.academic_year DESC, fs.year DESC, fs.month DESC LIMIT 1");
$stmt->bind_param("i", $organization_id);

if(!$stmt->execute()) {
    echo "<script>console.log(" . json_encode("Error executing SQL") . ");</script>";
    exit;
}

$stmt->bind_result(
    $academic_year,
    $month,
    $year,
    $starting_fund,
    $weekly_contribution,
    $internal_projects,
    $external_projects,
    $initiative_funding,
    $donations_sponsorships,
    $adviser_credit,
    $carri_credit,
    $total_credit,
    $total_expenses,
    $final_funding
);

if (!$stmt->fetch()) {
    $month = "N/A";
    $year = "N/A";
    $starting_fund = 0;
    $weekly_contribution = 0;
    $internal_projects = 0;
    $external_projects = 0;
    $initiative_funding = 0;
    $donations_sponsorships = 0;
    $adviser_credit = 0;
    $carri_credit = 0;
    $total_credit = 0;
    $total_expenses = 0;
    $final_funding = 0;
}


$stmt->close();

$stmt = $conn->prepare("SELECT ks.public_key, dm.month_name AS month, year, total_credit, total_expenses, final_funding
                        FROM financial_statement fs
                        INNER JOIN financial_statement_report fsr
                            ON fsr.statement_id = fs.statement_id
                        INNER JOIN date_month dm
                            ON fs.month = dm.month_id
                        INNER JOIN key_statement ks
                            ON ks.statement_id = fs.statement_id
                        WHERE organization_id = ? AND fsr.academic_year = ?
                        ORDER BY fsr.academic_year DESC, fs.year DESC, fs.month DESC LIMIT 1 OFFSET 1");
$stmt->bind_param("ii", $organization_id, $academic_year);

if(!$stmt->execute()) {
    echo "<script>console.log(" . json_encode("Error executing SQL") . ");</script>";
    exit;
}

$stmt->bind_result($prev_public_key, $prev_month, $prev_year, $prev_total_credit, $prev_total_expenses, $prev_final_funding);

$compare_heading = "";

if ($stmt->fetch()) {
    $compare_heading = '<h5 id="prevRecord" data-bs-toggle="modal" data-bs-target="#viewRecordModal" data-id="'. $prev_public_key .'">Record Compared to <button class="no-style-btn">'. htmlspecialchars($prev_month . ', ' . $prev_year) .'</button></h5>';
} else {
    $prev_total_credit = 0;
    $prev_total_expenses = 0;
    $prev_final_funding = 0;
}

$stmt->close();

function calcPercentageChange($current, $previous)
{
    if ($previous == 0) {
        return null;
    }
    return (($current - $previous) / $previous) * 100;
}

$total_credit_percentage = calcPercentageChange($total_credit, $prev_total_credit);
$total_expenses_percentage = calcPercentageChange($total_expenses, $prev_total_expenses);
$final_funding_percentage = calcPercentageChange($final_funding, $prev_final_funding);

function formatPercentage($value)
{
    if ($value === null) {
        return "0%";
    }
    $sign = $value >= 0 ? "+" : "";
    
    if (abs($value) < 1) {
        // Small change, show two decimals
        return $sign . number_format($value, 2) . "%";
    } else {
        // Larger change, show as whole number
        return $sign . round($value) . "%";
    }
}



function displayPercentageRow($percentage, $reverse = false)
{
    if ($percentage === null || $percentage == 0) {
        echo '<td><i class="bi bi-dash"></i></td>';
        echo '<td>0%</td>';
        return;
    }

    // Determine if the change is positive or negative
    $isPositive = $percentage >= 0;

    // If reverse is true (e.g., for expenses), flip the logic
    if ($reverse) {
        $isPositive = !$isPositive;
    }

    $icon = $percentage < 0
        ? '<i class="bi bi-caret-down-fill"></i>'
        : '<i class="bi bi-caret-up-fill"></i>';

    $class = $isPositive ? 'success' : 'danger';

    echo '<td class="' . $class . '">' . $icon . '</td>';
    echo '<td class="' . $class . '">' . formatPercentage($percentage) . '</td>';
}
