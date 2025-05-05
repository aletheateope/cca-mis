<?php
if (!isset($_GET['ref'])) {
    header("Location: accomplishments_page.php");
    exit;
}

$encodedRef = $_GET['ref'];

$public_key = base64_decode($encodedRef);

if (empty($public_key)) {
    header("Location: accomplishments_page.php");
    exit;
}

require_once BASE_PATH . '/assets/sql/conn.php';

if (!isset($_SESSION['accomplishment_report_id'])) {
    header("Location: accomplishments_page.php");
    exit();
}

$report_id = $_SESSION['accomplishment_report_id'];

$check = $conn->prepare("SELECT report_id, public_key FROM key_accomplishment_report WHERE report_id = ? AND public_key = ?");
$check->bind_param("is", $report_id, $public_key);
$check->execute();
$result = $check->get_result();

if ($result->num_rows == 0) {
    header("Location: accomplishments_page.php");
    exit;
}

$check->close();
