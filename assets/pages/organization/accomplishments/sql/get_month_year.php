<?php
$report_id = $_SESSION['accomplishment_report_id'];

require_once BASE_PATH . '/assets/sql/conn.php';

$sql = $conn->prepare("SELECT date_month.month_name as month, year
                        FROM accomplishment_report
                        INNER JOIN date_month
                            ON date_month.month_id = accomplishment_report.month
                        WHERE report_id = ?");

$sql->bind_param("i", $report_id);
$sql->execute();
$sql->bind_result($month, $year);
$sql->fetch();

$sql->close();
