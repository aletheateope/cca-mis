<?php
require_once '../../../../sql/base-path.php';

header('Content-Type: application/json');


require_once BASE_PATH . '/assets/sql/conn.php';

$sql = 'SELECT event_id, account_organization.name, title, start_date, end_date, start_time, end_time
        FROM event_calendar
        INNER JOIN account_organization
            ON account_organization.organization_id = event_calendar.user_id
        INNER JOIN account
            ON account.account_id = event_calendar.user_id';
$result = $conn->query($sql);

$events = [];
while ($row = $result->fetch_assoc()) {
    $event = [
        'id' => $row['event_id'],
        'title' => $row['title'],
    ];

    
    if (!empty($row['start_time']) && !empty($row['end_time'])) {
        $event['start'] = $row['start_date'] . 'T' . $row['start_time'];
        $event['end'] = $row['end_date'] . 'T' . $row['end_time'];
        $event['allDay'] = false;
    } else {
        $event['start'] = $row['start_date'];
        $event['allDay'] = true;
    }
    $events[] = $event;
}

echo json_encode($events);
$conn->close();
