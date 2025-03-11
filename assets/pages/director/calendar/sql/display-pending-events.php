<?php
require_once '../../../sql/base-path.php';

require_once BASE_PATH . '/assets/sql/conn.php';

$sql = "SELECT account_organization.name as organization, title, location, start_date, end_date
        FROM event_request
        INNER JOIN account_organization
            ON account_organization.organization_id = event_request.organization_id
        INNER JOIN event_status
            ON event_status.event_id = event_request.event_id
        WHERE event_status.status = 'Pending'";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$stmt->close();
$conn->close();
