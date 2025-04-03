<?php
require_once '../../../sql/base-path.php';

require_once BASE_PATH . '/assets/sql/conn.php';

$sql = "SELECT public_key, ao.name as organization, title, description, location, start_date, end_date, start_time, end_time, date_requested
        FROM event_request er
        INNER JOIN account_organization ao
            ON ao.organization_id = er.organization_id
        INNER JOIN event_request_status ers
            ON ers.event_request_id = er.event_request_id
        INNER JOIN key_event ke
            ON ke.event_request_id = er.event_request_id
        WHERE ers.status = 'Pending'
        ORDER BY date_requested ASC";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$stmt->close();
$conn->close();
