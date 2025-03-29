<?php
require_once BASE_PATH . '/assets/sql/conn.php';

if (isset($_SESSION['statement_report_id'])) {
    $statement_report_id = $_SESSION['statement_report_id'];

    // Fetch academic year and month
    $sql = "SELECT academic_year, month_name as month
            FROM financial_statement_report frs
            INNER JOIN financial_statement fs
                ON frs.statement_id = fs.statement_id
            INNER JOIN date_month dm
                ON dm.month_id = fs.month
            WHERE frs.report_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $statement_report_id);
    $stmt->execute();
    $stmt->bind_result($academicYear, $month);
    $stmt->fetch();
    $stmt->close();
} else {
    $academicYear = "N/A";
    $month = "N/A";
}
