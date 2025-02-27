<?php
require_once '../../../sql/conn.php';
session_start();

session_destroy();

session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 6;
}


$user_id = $_SESSION['user_id'];

$sql = "select student.student_number, first_name, last_name
        from student
        inner join student_organization
            on student_organization.student_number = student.student_number
        where student_organization.state = 'Active' AND student_organization.organization_id = ?
        ORDER BY first_name ASC, last_name ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$stmt->close();
$conn->close();
