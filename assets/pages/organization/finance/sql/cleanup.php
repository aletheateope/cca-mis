<?php
require_once '../../../../sql/base-path.php';

session_start();

require_once BASE_PATH . '/assets/sql/conn.php';

if (isset($_SESSION['statement_id'])) {
    $statement_id = $_SESSION['statement_id'];

    // Delete the incomplete statement from the database
    $sql = "DELETE FROM financial_statement WHERE statement_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $statement_id);
    $stmt->execute();
    $stmt->close();

    // Unset the session variable
    unset($_SESSION['statement_id']);
}

exit;
