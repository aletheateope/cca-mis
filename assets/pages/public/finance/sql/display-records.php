<?php

require_once BASE_PATH . '/assets/sql/conn.php';

$records = [];

$sql = "SELECT public_key, academic_year, month AS month_id, year, month_name AS month, ao.name AS organization_name
        FROM financial_statement_report fsr
        INNER JOIN financial_statement fs
            ON fs.statement_id = fsr.statement_id
        INNER JOIN account_organization ao
            ON ao.organization_id = fsr.organization_id
        INNER JOIN date_month dm
            ON dm.month_id = fs.month
        INNER JOIN key_user ku
            ON ku.user_id = ao.organization_id
        GROUP BY fsr.academic_year, fs.month , fs.year, ao.name
        ORDER BY fsr.academic_year DESC,  fs.year ASC, fs.month ASC, ao.name ASC";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $academic_year = $row['academic_year'];
    $month = $row['month'];
    $month_id = $row['month_id'];
    $year = $row['year'];
    $organization = $row['organization_name'];
    $public_key = $row['public_key'];

    if (!isset($records[$academic_year])) {
        $records[$academic_year] = [];
    }
    if (!isset($records[$academic_year][$month])) {
        $records[$academic_year][$month] = [];
    }

    $records[$academic_year][$month][] = [
        'year' => $year,
        'month_id' => $month_id,
        'organization' => $organization,
        'public_key' => $public_key
    ];
}
