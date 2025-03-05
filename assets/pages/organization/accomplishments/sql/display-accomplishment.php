<?php
require_once '../../../sql/conn.php';

$user_id = $_SESSION['user_id'];

// Fetch distinct years first
$sql_years = "SELECT DISTINCT year 
                FROM accomplishment_report
                WHERE organization_id = ?
                ORDER BY year DESC";
$stmt_years = $conn->prepare($sql_years);
$stmt_years->bind_param("i", $user_id);
$stmt_years->execute();
$result_years = $stmt_years->get_result();

// Fetch months per year
$accomplishments = [];

while ($row_year = $result_years->fetch_assoc()) {
    $year = $row_year['year'];
    
    $sql_months = "SELECT DISTINCT date_month.month_id, month_name
                    FROM date_month
                    INNER JOIN accomplishment_report
                        ON date_month.month_id = accomplishment_report.month
                    WHERE organization_id = ? AND year = ? ORDER BY month ASC";
    $stmt_months = $conn->prepare($sql_months);
    $stmt_months->bind_param("ii", $user_id, $year);
    $stmt_months->execute();
    $result_months = $stmt_months->get_result();

    $months = [];
    while ($row_month = $result_months->fetch_assoc()) {
        $months[] = [
            'id' => $row_month['month_id'],
            'name' => $row_month['month_name']
        ];
    }

    $accomplishments[$year] = $months;
}

$stmt_years->close();
$conn->close();
