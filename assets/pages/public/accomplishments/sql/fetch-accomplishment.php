<?php
header('Content-Type: application/json');

require_once '../../../../sql/base-path.php';

require_once BASE_PATH . '/assets/sql/conn.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if(!isset($data['month'], $data['year'], $data['organizationID'])) {
    echo json_encode(["error" => "Missing parameters"]);
    exit;
}

$month = $data['month'];
$year = $data['year'];
$organizationID = $data['organizationID'];

$stmt = $conn->prepare("SELECT COALESCE(ec.title, ac.title) AS title,
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
                            ON activity_remark.remark_id = ac.remark
                        LEFT JOIN event_calendar ec
                            ON ec.event_id = ac.event_id

                        WHERE ar.organization_id = ? AND ar.month = ? AND ar.year = ?
                        ORDER BY start_date ASC");

$stmt->bind_param("iii", $organizationID, $month, $year);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
