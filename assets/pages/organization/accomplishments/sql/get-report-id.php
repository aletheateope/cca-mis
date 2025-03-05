<?php
require_once('../../../sql/conn.php');

if (!isset($_GET['report_id'])) {
    die("Invalid access.");
}

$report_id = $_GET['report_id'];
