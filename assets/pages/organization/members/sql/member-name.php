<?php
header("Content-Type: application/json");

require_once '../../../../sql/base-path.php';

require_once BASE_PATH . '/assets/sql/conn.php';

$data = json_decode(file_get_contents("php://input"), true);


if (!isset($data['public_key'])) {
    echo json_encode(["error" => "Missing public key"]);
    exit();
}

$public_key = $data['public_key'];

$stmt = $conn->prepare("SELECT student_number FROM key_student WHERE public_key = ?");
$stmt->bind_param("s", $public_key);
$stmt->execute();
$stmt->bind_result($student_number);
$stmt->fetch();

$stmt->close();

if (!$student_number) {
    echo json_encode(["error" => "Student not found"]);
    exit();
}


$stmt = $conn->prepare("SELECT first_name, last_name FROM student WHERE student_number = ?");
$stmt->bind_param("s", $student_number);
$stmt->execute();
$stmt->bind_result($first_name, $last_name);
$stmt->fetch();

$stmt->close();

echo json_encode(["first_name" => $first_name, "last_name" => $last_name]);
