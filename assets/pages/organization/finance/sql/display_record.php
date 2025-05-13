<?php
require_once BASE_PATH . '/assets/sql/conn.php';

$user_id = $_SESSION['user_id'];

$sql_years = "SELECT DISTINCT academic_year
    FROM financial_statement_report
    WHERE organization_id = ?
    ORDER BY academic_year DESC";
$stmt_years = $conn->prepare($sql_years);
$stmt_years->bind_param("i", $user_id);
$stmt_years->execute();
$result_years = $stmt_years->get_result();

$records=[];

while ($row_year = $result_years->fetch_assoc()) {
    $year = $row_year['academic_year'];

    $sql_months = "SELECT public_key, month, month_name, year
                    FROM date_month
                    INNER JOIN financial_statement fs
                        ON date_month.month_id = fs.month
                    INNER JOIN financial_statement_report frs
                        ON frs.statement_id = fs.statement_id
                    INNER JOIN key_statement ks
                        ON ks.statement_id = fs.statement_id
                    WHERE frs.organization_id = ? AND academic_year = ?
                    ORDER BY year ASC, month ASC";
    $stmt_months = $conn->prepare($sql_months);
    $stmt_months->bind_param("is", $user_id, $year);
    $stmt_months->execute();
    $result_months = $stmt_months->get_result();

    $months = [];

    while ($row_month = $result_months->fetch_assoc()) {
        $months[] = [
            'public_key' => $row_month['public_key'],
            'id' => $row_month['month'],
           'name' => $row_month['month_name'],
           'year' => $row_month['year']
       ];

    }
    $records[$year] = $months;
}
$stmt_years->close();
