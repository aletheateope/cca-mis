<?php
require_once '../../../../sql/base-path.php';

header('Content-Type: application/json');

session_start();

require_once BASE_PATH . '/assets/sql/conn.php';

$statement_report_id = $_SESSION['statement_report_id'];
$user_id = $_SESSION['user_id'];

$response = ['count' => 0, 'startingFund' => null];

if ($statement_report_id && $user_id) {
    $sql = "SELECT academic_year FROM financial_statement_report WHERE report_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $statement_report_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $academic_year = $row['academic_year'];

        $sql2 = "SELECT COUNT(*) AS count FROM financial_statement_report WHERE organization_id = ? AND academic_year = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("is", $user_id, $academic_year);
        $stmt2->execute();
        $result2 = $stmt2->get_result();

        if ($result2->num_rows > 0) {
            $row2 = $result2->fetch_assoc();
            $response['count'] = $row2['count'];

            if ($response['count'] > 1) {
                $sql3 = "SELECT statement_id
                        FROM financial_statement_report
                        WHERE academic_year = ? AND organization_id = ?
                        ORDER BY statement_id DESC
                        LIMIT 1 OFFSET 1";
                $stmt3 = $conn->prepare($sql3);
                $stmt3->bind_param("si", $academic_year, $user_id);
                $stmt3->execute();
                $result3 = $stmt3->get_result();
                if ($result3->num_rows > 0) {
                    $row3 = $result3->fetch_assoc();
                    $statement_id = $row3['statement_id'];

                    $sql4 = "SELECT final_funding FROM financial_statement WHERE statement_id = ?";
                    $stmt4 = $conn->prepare($sql4);
                    $stmt4->bind_param("i", $statement_id);
                    $stmt4->execute();
                    $result4 = $stmt4->get_result();
                    if ($result4->num_rows > 0) {
                        $row4 = $result4->fetch_assoc();
                        $response['startingFund'] = $row4['final_funding'];
                    }
                }
            }
        }
    }
}
echo json_encode($response);
