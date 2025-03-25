<?php
require_once '../../../../sql/base-path.php';

header('Content-Type: application/json');

session_start();

require_once BASE_PATH . '/assets/sql/conn.php';

$statement_id = $_SESSION['statement_id'];
$user_id = $_SESSION['user_id'];

$response = ['count' => 0];

if ($statement_id && $user_id) {
    $sql = "SELECT academic_year FROM financial_statement WHERE statement_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $statement_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $academic_year = $row['academic_year'];

        $sql2 = "SELECT COUNT(*) AS count FROM financial_statement WHERE organization_id = ? AND academic_year = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("is", $user_id, $academic_year);
        $stmt2->execute();
        $result2 = $stmt2->get_result();

        if ($result2->num_rows > 0) {
            $row2 = $result2->fetch_assoc();
            $response['count'] = $row2['count'];
        }
    }
}

echo json_encode($response);
