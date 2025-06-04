<?php
require_once BASE_PATH . '/assets/sql/conn.php';

$stmt = $conn->prepare("SELECT ke.public_key, ec.title, request_letter_path AS path, amount_requested
                        FROM event_budget_request ebr
                        INNER JOIN event_calendar ec
                            ON ec.event_id = ebr.event_id
                        INNER JOIN key_event ke
                            ON ke.event_id = ec.event_id
WHERE ebr.status = 'Pending'");

if(!$stmt->execute()) {
    echo "<script>console.log('Error executing query');</script>";
    exit;
}

$result = $stmt->get_result();

$conn->close();
