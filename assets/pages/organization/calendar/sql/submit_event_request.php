<?php
require_once '../../../../sql/base_path.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once BASE_PATH . '/assets/sql/conn.php';

    session_start();

    $title = $_POST['title'] ?? null;
    $description = $_POST['description'] ?? null;
    $location = $_POST['location'] ?? null;
    $start_date = $_POST['start_date'] ?? null;
    $end_date = $_POST['end_date'] ?? null;
    $start_time = !empty($_POST['start_time']) ? $_POST['start_time'] : null;
    $end_time = !empty($_POST['end_time']) ? $_POST['end_time'] : null;

    $organization_id = $_SESSION['user_id'] ?? null;

    $sql = "INSERT INTO event_request (organization_id, title, description, location, start_date, end_date, start_time, end_time, date_requested) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssss", $organization_id, $title, $description, $location, $start_date, $end_date, $start_time, $end_time);

    if ($stmt->execute()) {
        $event_id_request_id = $conn->insert_id;

        $sql2 = "INSERT INTO event_request_status (event_request_id, status) VALUES (?, 'Pending')";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("i", $event_id_request_id);

        if ($stmt2->execute()) {

            require_once BASE_PATH . '/assets/sql/event_key.php';

            $sql4 = "INSERT INTO key_event (event_request_id, public_key) VALUES (?, ?)";
            $stmt4 = $conn->prepare($sql4);
            $stmt4->bind_param("is", $event_id_request_id, $public_key);

            if ($stmt4->execute()) {
                $response = ["success" => true, "message" => "Event request submitted successfully."];
            } else {
                $response = ["success" => false, "message" => "Database error (key_event): " . $stmt4->error];
            }

            $stmt4->close();

        } else {
            $response = ["success" => false, "message" => "Database error (event_request_status): " . $stmt2->error];
        }

        $stmt2->close();
        
    } else {
        $response = ["success" => false, "message" => "Database error (event_request): " . $stmt->error];

    }

    $stmt->close();
    echo json_encode($response);
}

$conn->close();
