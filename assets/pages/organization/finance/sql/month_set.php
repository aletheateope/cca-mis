<?php
session_start();
if (isset($_POST['month'])) {
    $_SESSION['month'] = $_POST['month'];
    echo json_encode(['success' => true, 'month' => $_SESSION['month']]);
} else {
    echo json_encode(['success' => false, 'error' => 'No month provided']);
}
