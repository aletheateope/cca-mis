<?php
header("Content-Type: application/json");

require_once "../../../../sql/base_path.php";

require_once BASE_PATH . '/assets/sql/conn.php';

session_start();

$organization_id = $_SESSION['user_id'];

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['public_key'])) {
    echo json_encode(["success" => false, "message" => "Missing public key"]);
    exit();
}

$public_key = $data['public_key'];

$stmt = $conn->prepare("SELECT ks.student_number 
                        FROM key_student ks
                        INNER JOIN student_organization so
                        ON so.student_number = ks.student_number
                        WHERE organization_id = ? AND public_key = ?");
$stmt->bind_param("is", $organization_id, $public_key);
$stmt->execute();
$stmt->bind_result($student_number);
$stmt->fetch();

$stmt->close();

if (!$student_number) {
    echo json_encode(["success" => false, "message" => "Student not found"]);
    exit();
}

$stmt = $conn->prepare("DELETE FROM student WHERE student_number = ?");
$stmt->bind_param("i", $student_number);
if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false , "message" => "Error deleting student: " . $stmt->error]);
}
