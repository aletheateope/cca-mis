<?php
require_once '../../../../sql/base-path.php';

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once BASE_PATH . '/assets/sql/conn.php';

    session_start();

    $user_id = $_SESSION['user_id'] ?? null;

    if (!$user_id) {
        echo json_encode(["error" => "Unauthorized access."]);
        exit;
    }


    if (!isset($data['startYear']) || !isset($data['endYear'])) {
        throw new Exception("Invalid input.");
    }

    $startYear = intval($data['startYear']);
    $endYear = intval($data['endYear']);

    $academicYear = $startYear . '-' . $endYear;

    try {
        $sql = "SELECT COUNT(*) AS totalMonths
        FROM financial_statement fs
        INNER JOIN financial_statement_report frs
            ON frs.statement_id = fs.statement_id
        WHERE organization_id = ? AND academic_year = ? ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $user_id, $academicYear);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        echo json_encode(["totalMonths" => $result['totalMonths'] ?? 0]);
    } catch (Exception $e) {
        echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Invalid request method."]);
}
