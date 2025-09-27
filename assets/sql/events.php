<?php
require_once 'base_path.php';

header('Content-Type: application/json');

require_once BASE_PATH . '/assets/sql/conn.php';

$sql = 'SELECT public_key, title, description, location, start_date, end_date, start_time, end_time, budget_given,
        COALESCE(ao.name, a.role) AS scheduled_by, ec.user_id, a.role
        FROM event_calendar ec
        LEFT JOIN account_organization ao
            ON ao.organization_id = ec.user_id 
        LEFT JOIN account a
            ON a.user_id = ec.user_id
        INNER JOIN key_event ke
            ON ke.event_id = ec.event_id';
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
        'id' => $row['public_key'],
        'title' => $row['title'],
        'textColor' => '#ffffff',
        
        'extendedProps' => [
            'description' => $row['description'],
            'location' => $row['location'],
            'budget' => $row['budget_given'],

            'scheduled_by' => $row['scheduled_by'],
        ],

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
