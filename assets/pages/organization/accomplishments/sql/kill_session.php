<?php
require_once '../../../../sql/base_path.php';

session_start();

if(!isset($_SESSION['accomplishment_report_id'])) {
    exit;
}

$report_id = $_SESSION['accomplishment_report_id'];

require_once BASE_PATH . '/assets/sql/conn.php';

$sql = $conn->prepare("DELETE FROM accomplishment_report WHERE report_id = ?");
$sql->bind_param("i", $report_id);
$sql->execute();
$sql->close();

unset($_SESSION['accomplishment_report_id']);

header("Location: accomplishments_page.php");
exit;
