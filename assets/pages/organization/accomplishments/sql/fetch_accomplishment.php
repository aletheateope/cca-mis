<?php
session_start();

require_once '../../../../sql/conn.php';

$user_id = $_SESSION['user_id'];
$month = $_GET['month'];
$year = $_GET['year'];

$sql = 'SELECT COALESCE(ec.title, ac.title) AS title,
	    COALESCE(ec.start_date,ac.start_date) AS start_date,
        COALESCE (ec.end_date, ac.end_date) AS end_date,	
	    target_participants, actual_participants,
        budget_utilized, activity_remark.name as remark

        FROM accomplishment_report ar
        INNER JOIN date_month dm
            ON dm.month_id = ar.month
        INNER JOIN activity_accomplishment ac
            ON ac.activity_id = ar.activity_id
        INNER JOIN activity_remark
            ON activity_remark.remark_id = ac.remarks
	    LEFT JOIN event_calendar ec
		    ON ec.event_id = ac.event_id

        WHERE ar.organization_id = ? AND ar.month = ? AND ar.year = ?
        ORDER BY start_date ASC';

$stmt = $conn->prepare($sql);
$stmt->bind_param('iii', $user_id, $month, $year);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
