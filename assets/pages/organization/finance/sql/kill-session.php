<?php
require_once '../../../../sql/base-path.php';

session_start();

require_once BASE_PATH . '/assets/sql/conn.php';

if (isset($_SESSION['statement_report_id'])) {
    $statement_report_id = $_SESSION['statement_report_id'];

    $sql = "SELECT statement_id FROM financial_statement_report WHERE report_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $statement_report_id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $statement_id = $row['statement_id'];

            if(!empty($statement_id)) {
                $sql2 = "DELETE FROM financial_statement WHERE statement_id = ?";
                $stmt2 = $conn->prepare($sql2);
                $stmt2->bind_param("i", $statement_id);
                $stmt2->execute();
                $stmt2->close();
            }
        }
    }
    $stmt->close();

    // Unset the session variable
    unset($_SESSION['statement_report_id']);
    session_write_close(); // Ensure session updates are saved

    exit;
}
