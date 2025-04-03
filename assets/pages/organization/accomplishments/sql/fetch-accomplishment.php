<?php
session_start();

require_once '../../../../sql/conn.php';

$user_id = $_SESSION['user_id'];
$month = $_GET['month'];
$year = $_GET['year'];

$sql = 'SELECT title, start_date, end_date, target_participants, actual_participants,
        budget_utilized, activity_remark.name as remark

        FROM accomplishment_report
        INNER JOIN date_month
            ON date_month.month_id = accomplishment_report.month
        INNER JOIN activity_accomplishment
            ON activity_accomplishment.activity_id = accomplishment_report.activity_id
        INNER JOIN activity_remark
            ON activity_remark.remark_id = activity_accomplishment.remark

        WHERE accomplishment_report.organization_id = 6 AND accomplishment_report.month = 1 AND accomplishment_report.year = 2022
        ORDER BY activity_accomplishment.activity_id ASC';

$stmt = $conn->prepare($sql);
$stmt->bind_param('iii', $user_id, $month, $year);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
