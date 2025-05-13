<?php
header("Content-Type: application/json");

require_once '../../../../sql/base_path.php';

require_once BASE_PATH . '/assets/sql/conn.php';

session_start();

$user_id = $_SESSION['user_id'];

$oneMonthAgo = strtotime("-1 month");
$month = date('m', $oneMonthAgo);

$stmt = $conn->prepare("SELECT
                        COUNT(CASE WHEN gender = 'Male' AND MONTH(date_joined) = ? AND YEAR(date_joined) = YEAR(CURRENT_DATE()) THEN 1 END) AS male,
                        COUNT(CASE WHEN gender = 'Female' AND MONTH(date_joined) = ? AND YEAR(date_joined) = YEAR(CURRENT_DATE()) THEN 1 END) AS female,
                        COUNT(CASE WHEN gender = 'LGBTQ' AND MONTH(date_joined) = ? AND YEAR(date_joined) = YEAR(CURRENT_DATE()) THEN 1 END) AS lgbt
                        FROM student
                        INNER JOIN student_organization so
                            ON so.student_number = student.student_number
                        WHERE so.organization = ? AND state = 'Active'
                        ");

$stmt->bind_param("iiii", $month, $month, $month, $user_id);

if(!$stmt->execute()) {
    echo json_encode(["success" => false, "message" => "Failed to execute statement"]);
}

$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo json_encode(["success" => true, "result" => $row]);
