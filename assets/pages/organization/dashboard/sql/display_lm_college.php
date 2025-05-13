<?php
header("Content-Type: application/json");

require_once '../../../../sql/base_path.php';

require_once BASE_PATH . '/assets/sql/conn.php';

session_start();

$user_id = $_SESSION['user_id'];

$oneMonthAgo = strtotime("-1 month");
$month = date('m', $oneMonthAgo);

$stmt = $conn->prepare("SELECT count(*) AS member_count, pc.abbreviation AS college
                        FROM student_organization so
                        INNER JOIN student_academic_info sao
                            ON sao.student_number = so.student_number
                        INNER JOIN program p
                            ON p.program_id = sao.program_id
                        INNER JOIN program_college pc
                            ON pc.college_id = p.college_id
                        WHERE so.organization = ? AND MONTH(so.date_joined) = ? AND YEAR(so.date_joined) = YEAR(CURRENT_DATE()) AND so.state = 'Active'
                        GROUP BY college");

$stmt->bind_param("ii", $user_id, $month);

if(!$stmt->execute()) {
    echo json_encode([
        "success" => false,
        "message" => "Error executing statement"
    ]);
    exit;
}

$result = $stmt->get_result();
$memberCollege = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();

echo json_encode([
    "success" => true,
    "data" => $memberCollege
]);
