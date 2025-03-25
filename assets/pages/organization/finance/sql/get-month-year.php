<?php
require_once BASE_PATH . '/assets/sql/conn.php';

if (isset($_SESSION['statement_id'])) {
    $statement_id = $_SESSION['statement_id'];

    // Fetch academic year and month
    $sql = "SELECT academic_year, month_name as month
            FROM financial_statement
            INNER JOIN date_month
                ON date_month.month_id = financial_statement.month
            WHERE statement_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $statement_id);
    $stmt->execute();
    $stmt->bind_result($academicYear, $month);
    $stmt->fetch();
    $stmt->close();
} else {
    $academicYear = "N/A";
    $month = "N/A";
}
