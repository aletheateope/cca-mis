<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../../../../sql/conn.php';

    $title = $_POST['title'] ?? null;
    $description = $_POST['description'] ?? null;
    $location = $_POST['location'] ?? null;
    $start_date = $_POST['start_date'] ?? null;
    $end_date = $_POST['end_date'] ?? null;
    $start_time = !empty($_POST['start_time']) ? $_POST['start_time'] : null;
    $end_time = !empty($_POST['end_time']) ? $_POST['end_time'] : null;

    $organization_id = $_SESSION['user_id'] ?? null;

    $sql = "INSERT INTO event_request (organization_id, title, description, location, start_date, end_date, start_time, end_time) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssss", $organization_id, $title, $description, $location, $start_date, $end_date, $start_time, $end_time);

    if ($stmt->execute()) {
        $event_id = $conn->insert_id; // Get the last inserted ID after successful execution

        // Prepare second query
        $sql2 = "INSERT INTO event_request_status (event_request_id, status) VALUES (?, 'Pending')";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("i", $event_id);

        if ($stmt2->execute()) {
            $response = ["status" => "success", "message" => "Event request submitted"];
        } else {
            $response = ["status" => "error", "message" => "Database error (event_request_status): " . $stmt2->error];
        }

        $stmt2->close();
    } else {
        $response = ["status" => "error", "message" => "Database error (event_request): " . $stmt->error];
    }

    $stmt->close();
    echo json_encode($response);
}

$conn->close();
