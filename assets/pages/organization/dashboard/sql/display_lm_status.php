<?php
header("Content-Type: application/json");

require_once '../../../../sql/base_path.php';

require_once BASE_PATH . '/assets/sql/conn.php';

session_start();

$user_id = $_SESSION['user_id'];

$oneMonthAgo = strtotime("-1 month");
$month = date('m', $oneMonthAgo);

$stmt = $conn->prepare("SELECT
                            COUNT(CASE WHEN status = 'Trainee' AND MONTH(date_joined) = ? AND YEAR(date_joined) = YEAR(CURRENT_DATE()) THEN 1 END) AS trainee_count,
                            COUNT(CASE WHEN status = 'Junior' AND MONTH(date_joined) = ? AND YEAR(date_joined) = YEAR(CURRENT_DATE()) THEN 1 END) AS junior_count,
                            COUNT(CASE WHEN status = 'Senior' AND MONTH(date_joined) = ? AND YEAR(date_joined) = YEAR(CURRENT_DATE()) THEN 1 END) AS senior_count,
                            COUNT(CASE WHEN status = 'Alumni' AND MONTH(date_joined) = ? AND YEAR(date_joined) = YEAR(CURRENT_DATE()) THEN 1 END) AS alumni_count
                        FROM student_organization WHERE organization = ? AND state = 'Active'");

$stmt->bind_param("iiiii", $month, $month, $month, $month, $user_id);

if(!$stmt->execute()) {
    echo json_encode(["success" => false, "message" => "Failed to execute statement"]);
}

$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo json_encode(["success" => true, "result" => $row]);
