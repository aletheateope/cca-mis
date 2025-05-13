<?php
header("Content-Type: application/json");

require_once '../../../../sql/base_path.php';

require_once BASE_PATH . '/assets/sql/conn.php';

session_start();

$user_id = $_SESSION['user_id'];

$oneMonthAgo = strtotime("-1 month");
$month = date('m', $oneMonthAgo);

$stmt = $conn->prepare("SELECT
                            COUNT(CASE WHEN state = 'Active' AND MONTH(date_joined) = ? AND YEAR(date_joined) = YEAR(CURRENT_DATE()) THEN 1 END) AS active_count,
                            COUNT(CASE WHEN state = 'Inactive' AND MONTH(date_joined) = ? AND YEAR(date_joined) = YEAR(CURRENT_DATE()) THEN 1 END) AS inactive_count,
                            COUNT(CASE WHEN state = 'Exited' AND MONTH(date_left) = ? AND YEAR(date_left) = YEAR(CURRENT_DATE()) THEN 1 END) AS exited_count,
                            COUNT(CASE WHEN state = 'Terminated' AND MONTH(date_left) = ? AND YEAR(date_left) = YEAR(CURRENT_DATE()) THEN 1 END) AS terminated_count
                        FROM student_organization WHERE organization = ?");

$stmt->bind_param("iiiii", $month, $month, $month, $month, $user_id);

if(!$stmt->execute()) {
    echo json_encode(["success" => false, "message" => "Failed to execute statement"]);
}

$result = $stmt->get_result();
$row = $result->fetch_assoc();
echo json_encode(["success" => true, "result" => $row]);
