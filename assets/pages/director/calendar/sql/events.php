<?php
require_once '../../../../sql/base-path.php';

header('Content-Type: application/json');

require_once BASE_PATH . '/assets/sql/conn.php';

$sql = 'SELECT event_id, title, start_date, end_date, start_time, end_time, COALESCE(name,role) AS scheduled_by
        FROM event_calendar
        LEFT JOIN account_organization 
            ON account_organization.organization_id = event_calendar.user_id 
        LEFT JOIN account 
            ON account.user_id = event_calendar.user_id';
$result = $conn->query($sql);

$events = [];
while ($row = $result->fetch_assoc()) {
    $event = [
        'id' => $row['event_id'],
        'title' => $row['title'],
        'scheduled_by' => $row['scheduled_by'],
    ];

    if (!empty($row['start_time']) && !empty($row['end_time'])) {
        $event['start'] = $row['start_date'] . 'T' . $row['start_time'];
        $event['end'] = $row['end_date'] . 'T' . $row['end_time'];
        $event['allDay'] = false;
    } else {
        $event['start'] = $row['start_date'];
        $event['end'] = $row['end_date'];
        $event['allDay'] = true;
    }
    $events[] = $event;
}

echo json_encode($events);
$conn->close();
