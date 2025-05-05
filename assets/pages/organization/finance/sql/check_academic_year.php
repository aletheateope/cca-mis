<?php
require_once '../../../../sql/base_path.php';

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();

    require_once BASE_PATH . '/assets/sql/conn.php';

    $user_id = $_SESSION['user_id'];

    $startYear = $_POST['startYear'];
    $endYear = $_POST['endYear'];

    $academicYear = $startYear . "-" . $endYear;

    // Check if the academic year exists
    $query = "SELECT 1
                FROM financial_statement_report
                WHERE academic_year = ? AND organization_id = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $academicYear, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $sql = "SELECT month
                FROM financial_statement fs
                INNER JOIN financial_statement_report fsr
                    ON fsr.statement_id = fs.statement_id
                WHERE academic_year = ? AND organization_id = ?
                ORDER BY fs.statement_id DESC
                LIMIT 1";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $academicYear, $user_id);
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
