<?php
session_start();
if (isset($_POST['nextMonth'])) {
    $_SESSION['nextMonth'] = $_POST['nextMonth'];
    echo json_encode(['success' => true, 'nextMonth' => $_SESSION['nextMonth']]);
} else {
    echo json_encode(['success' => false, 'error' => 'No month provided']);
}
