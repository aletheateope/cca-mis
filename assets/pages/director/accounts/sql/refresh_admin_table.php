<?php
header("Content-Type: application/json");

require_once '../../../../sql/base_path.php';

require_once BASE_PATH . '/assets/sql/conn.php';

session_start();

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT public_key, first_name, last_name, email, role
                        FROM account a
                        INNER JOIN account_admin aa
                            ON aa.admin_id = a.user_id
                        INNER JOIN key_user ku
                            ON ku.user_id = a.user_id
                        WHERE a.user_id != ?
                        ORDER BY first_name ASC, last_name ASC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result_admin = $stmt->get_result();

$stmt->close();

$admins = [];

while ($row = $result_admin->fetch_assoc()) {
    $admins[] = $row;
}

echo json_encode($admins);
