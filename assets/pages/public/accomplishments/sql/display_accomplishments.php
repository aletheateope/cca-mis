<?php
require_once BASE_PATH . '/assets/sql/conn.php';

$accomplishments = [];

$sql = "SELECT public_key, year, month AS month_id, month_name AS month, ao.name AS organization_name, COUNT(ar.activity_id) AS activity_count
        FROM accomplishment_report ar
        INNER JOIN account_organization ao
            ON ar.organization_id = ao.organization_id
        INNER JOIN date_month dm
            ON dm.month_id = ar.month
        INNER JOIN key_user ku
            ON ku.user_id = ao.organization_id
        GROUP BY ar.year, ar.month, ao.name
        ORDER BY ar.year DESC, ar.month ASC, ao.name ASC";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $year = $row['year'];
    $month = $row['month'];
    $month_id = $row['month_id'];
    $organization = $row['organization_name'];
    $count = $row['activity_count'];
    $public_key = $row['public_key'];

    if (!isset($accomplishments[$year])) {
        $accomplishments[$year] = [];
    }
    if (!isset($accomplishments[$year][$month])) {
        $accomplishments[$year][$month] = [];
    }

    $accomplishments[$year][$month][] = [
        'month_id' => $month_id,
        'organization' => $organization,
        'activity_count' => $count,
        'public_key' => $public_key
    ];
}
