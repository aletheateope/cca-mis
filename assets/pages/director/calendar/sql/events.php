<?php
require_once '../../../../sql/base-path.php';

header('Content-Type: application/json');

require_once BASE_PATH . '/assets/sql/conn.php';

$sql = 'SELECT event_id, title, start_date, end_date, start_time, end_time,  COALESCE(name,role) AS scheduled_by, event_calendar.user_id, account.role
        FROM event_calendar
        LEFT JOIN account_organization 
            ON account_organization.organization_id = event_calendar.user_id 
        LEFT JOIN account 
            ON account.user_id = event_calendar.user_id';
$result = $conn->query($sql);

$events = [];
while ($row = $result->fetch_assoc()) {
    $backgroundColor = null;
    $borderColor = null;

    if ($row['role'] == 'Director') {
        $backgroundColor = '#2d642b';
    } elseif ($row['role'] == 'Organization') {
        $orgBackgroundColors = [
            1 => '#000000', // BLCK MVMNT
            2 => '#4451A4', // Chorale
            3 => '#E97536', // Dulangsining
            4 => '#DDCA4C', // Euphoria
            5 => '#E4AC45', // FDC
            6 => '#785943', // Kultura Teknika
        ];
        $backgroundColor = $orgBackgroundColors[$row['user_id']] ?? null;
    }

    if ($backgroundColor) {
        $borderColor = $backgroundColor; // Use the same color as background
    }


    $event = [
        'id' => $row['event_id'],
        'title' => $row['title'],
        'scheduled_by' => $row['scheduled_by'],
        'textColor' => '#ffffff',
    ];

    if ($backgroundColor) {
        $event['backgroundColor'] = $backgroundColor;
        $event['borderColor'] = $borderColor;
    }


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
