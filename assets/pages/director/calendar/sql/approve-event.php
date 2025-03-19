<?php
require_once '../../../../sql/base-path.php';

$data = json_decode(file_get_contents("php://input"), true);

if(isset($data["id"])) {
    require_once BASE_PATH . '/assets/sql/conn.php';
    $eventRequestID = $data["id"];

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("SELECT * FROM event_request WHERE event_request_id = ?");
        $stmt->bind_param("i", $eventRequestID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $stmt = $conn->prepare("INSERT INTO event_calendar (event_request_id, user_id, title, description, location, start_date, end_date, start_time, end_time)
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iisssssss", $eventRequestID, $row['organization_id'], $row['title'], $row['description'], $row['location'], $row['start_date'], $row['end_date'], $row['start_time'], $row['end_time']);
            $stmt->execute();
            
            $stmt = $conn->prepare("UPDATE event_request_status SET status = 'Approved' WHERE event_request_id = ?");
            $stmt->bind_param("i", $eventRequestID);
            $stmt->execute();
            
            $stmt = $conn->prepare("DELETE FROM event_request WHERE event_request_id = ?");
            $stmt->bind_param("i", $eventRequestID);
            $stmt->execute();
            
            $conn->commit();
            echo json_encode(["success" => true, "message" => "Event request approved successfully."]);
        } else {
            throw new Exception("Event request not found.");
        }
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
}
