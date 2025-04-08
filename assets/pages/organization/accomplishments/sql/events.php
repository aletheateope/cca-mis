<?php
$organization_id = $_SESSION['user_id'];
$report_id = $_SESSION['accomplishment_report_id'];

require_once BASE_PATH . '/assets/sql/conn.php';

$stmt = $conn->prepare("SELECT month, year FROM accomplishment_report WHERE report_id = ?");
$stmt->bind_param("i", $report_id);
$stmt->execute();
$stmt->bind_result($date_month, $date_year);
$stmt->fetch();

$stmt->close();

$stmt = $conn->prepare("SELECT public_key, ec.title
                        FROM event_calendar ec
                        INNER JOIN key_event ke
                            ON ke.event_id = ec.event_id
                        LEFT JOIN activity_accomplishment ac
                            ON ac.event_id = ec.event_id
                        WHERE user_id = ?
                        AND MONTH(ec.start_date) = ?
                        AND YEAR(ec.start_date) = ?
                        AND ac.event_id IS NULL");

$stmt->bind_param("iii", $organization_id, $date_month, $date_year);
$stmt->execute();
$events = $stmt->get_result();
