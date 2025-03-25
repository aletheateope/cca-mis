<?php
require_once '../../../../sql/base-path.php';

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once BASE_PATH . '/assets/sql/conn.php';
    
    session_start();

    $user_id = $_SESSION['user_id'] ?? null;
    $month = $_POST['month'] ?? null;
    $startYear = $_POST['startYear'] ?? null;
    $endYear = $_POST['endYear'] ?? null;

    
    if (!$user_id || !$month || !$startYear || !$endYear) {
        echo json_encode(["success" => false, "error" => "Missing required fields."]);
        exit;
    }

    $academic_year = $startYear . '-' . $endYear;

    $sql = "INSERT INTO financial_statement (organization_id, month, academic_year) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    $stmt->bind_param("iis", $user_id, $month, $academic_year);

    if ($stmt->execute()) {

        $_SESSION['statement_id'] = $stmt->insert_id;
       
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "Insert failed: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
