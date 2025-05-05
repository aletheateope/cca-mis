<?php
require_once BASE_PATH . '/assets/sql/public_key.php';

$count = 1;

do {
    $public_key = generatePublicKey();
    
    $key = "SELECT COUNT(*) FROM key_event WHERE public_key = ?";
    $stmtKey = $conn->prepare($key);
    $stmtKey->bind_param("s", $public_key);
    $stmtKey->execute();
    $stmtKey->bind_result($count);
    $stmtKey->fetch();
    $stmtKey->close();
} while ($count > 0);
