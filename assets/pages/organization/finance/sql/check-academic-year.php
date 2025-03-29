<?php
require_once '../../../../sql/base-path.php';

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once BASE_PATH . '/assets/sql/conn.php';

    $startYear = $_POST['startYear'];
    $endYear = $_POST['endYear'];
    $academicYear = $startYear . "-" . $endYear;

    // Check if the academic year exists
    $query = "SELECT COUNT(*) AS count FROM financial_statement_report WHERE academic_year = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $academicYear);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        $sql = "SELECT month
                FROM financial_statement fs
                INNER JOIN financial_statement_report fsr
                    ON fsr.statement_id = fs.statement_id
                WHERE academic_year = ?
                ORDER BY fs.statement_id DESC
                LIMIT 1";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $academicYear);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            $latestMonth = $row['month'];
            $nextMonth = ($latestMonth % 12) + 1; // Get the next month
        } else {
            $nextMonth = 1; // Default to January if no records found
        }

        echo json_encode(["exists" => true, "nextMonth" => $nextMonth]);
    } else {
        echo json_encode(["exists" => false]);
    }

    $stmt->close();
    $conn->close();
}
