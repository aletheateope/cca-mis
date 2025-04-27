<?php
require_once '../../../sql/base-path.php';
require_once BASE_PATH . '/assets/sql/conn.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT public_key, first_name, last_name
        FROM student
        INNER JOIN student_organization
            ON student_organization.student_number = student.student_number
        INNER JOIN key_student
            ON key_student.student_number = student.student_number
        WHERE student_organization.state = 'Active' AND student_organization.organization = ?
        ORDER BY first_name ASC, last_name ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$stmt->close();
$conn->close();
