<?php
require_once '../../../../sql/base_path.php';

header('Content-Type: application/json');

session_start();

require_once BASE_PATH . '/assets/sql/conn.php';

$statement_report_id = $_SESSION['statement_report_id'];
$user_id = $_SESSION['user_id'];

$response = ['count' => 0, 'startingFund' => null];

if ($statement_report_id && $user_id) {
    $stmt = $conn->prepare("SELECT academic_year FROM financial_statement_report WHERE report_id = ?");
    $stmt->bind_param("i", $statement_report_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $academic_year = $row['academic_year'];

        $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM financial_statement_report WHERE organization_id = ? AND academic_year = ?");
        $stmt->bind_param("is", $user_id, $academic_year);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $response['count'] = $row['count'];

            if ($response['count'] > 1) {
                $stmt = $conn->prepare(
                    "SELECT statement_id
                        FROM financial_statement_report
                        WHERE academic_year = ? AND organization_id = ?
                        ORDER BY statement_id DESC LIMIT 1 OFFSET 1"
                );
                $stmt->bind_param("si", $academic_year, $user_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($row = $result->fetch_assoc()) {
                    $statement_id = $row['statement_id'];

                    $stmt = $conn->prepare("SELECT final_funding
                                            FROM financial_statement
                                            WHERE statement_id = ?");
                    $stmt->bind_param("i", $statement_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($row = $result->fetch_assoc()) {
                        $response['startingFund'] = $row['final_funding'];
                    }
                }
            }
        }
    }
}

echo json_encode($response);
