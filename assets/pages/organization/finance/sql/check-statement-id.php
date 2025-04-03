<?php
if (!isset($_SESSION['statement_report_id'])) {
    header("Location: finance-page.php");
    exit();
}
