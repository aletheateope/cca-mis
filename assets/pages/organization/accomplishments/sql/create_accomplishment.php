<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../../../../sql/conn.php';

    $user_id = $_SESSION['user_id'] ?? null;
    $month = $_POST['month'] ?? null;
    $year = $_POST['year'] ?? null;

    if (!$user_id || !$month || !$year) {
        echo json_encode(["success" => false, "error" => "Missing required fields."]);
        exit;
    }

    // Prepare and execute query
    $sql = "INSERT INTO accomplishment_report (organization_id, month, year) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(["success" => false, "error" => "SQL prepare failed: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("iii", $user_id, $month, $year);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "report_id" => $stmt->insert_id]);
    } else {
        echo json_encode(["success" => false, "error" => "Insert failed: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
