<?php
session_start();

unset($_SESSION['user_id']);

if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 6;
}

require_once BASE_PATH . '/assets/sql/conn.php';

$user = $_SESSION['user_id'];

$stmtUser = $conn->prepare("SELECT role FROM account WHERE user_id = ?");
$stmtUser->bind_param("i", $user);
$stmtUser->execute();
$stmtUser->bind_result($role);
$stmtUser->fetch();

$_SESSION['user_role'] = $role;

$stmtUser->close();
