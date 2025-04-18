<?php
header("Content-Type: application/json");

require_once "../../../../sql/base-path.php";

require_once BASE_PATH . '/assets/sql/conn.php';

session_start();

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['public_key'])) {
    echo json_encode(["error" => "Missing public key"]);
    exit;
}

$public_key = $data["public_key"];

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$organization_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT student_number FROM key_student WHERE public_key = ?");
$stmt->bind_param("s", $public_key);
$stmt->execute();
$stmt->bind_result($student_number);
$stmt->fetch();

if (!$student_number) {
    echo json_encode(["error" => "Member not found"]);
    exit;
}

$stmt->close();

$stmt = $conn->prepare("SELECT first_name, middle_name, last_name, birthdate, age, gender, mobile_number, email, address, s.student_number, abbreviation AS course, year_level, status, state, date_joined, date_left
                        FROM student s
                        INNER JOIN student_organization so
                            ON so.student_number = s.student_number
                        INNER JOIN student_academic_info sci
                            ON sci.student_number = s.student_number
                        INNER JOIN program p
                            ON p.program_id = sci.program_id
                        INNER JOIN program_course pc
                            ON pc.course_id = p.course_id
                        WHERE s.student_number = ? AND organization_id = ?");

$stmt->bind_param("ii", $student_number, $organization_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode($row);
} else {
    echo json_encode(["error" => "Member not found"]);
}

$stmt->close();
$conn->close();
