<?php
require_once BASE_PATH . '/assets/sql/conn.php';

$organization_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT public_key, first_name, last_name, status, state, date_joined
                        FROM student
                        INNER JOIN student_organization so
                            ON so.student_number = student.student_number
                        INNER JOIN key_student ks
                            ON ks.student_number = student.student_number
                        WHERE organization = ? AND state = 'Active'
                        ORDER BY first_name ASC, last_name ASC");

$stmt->bind_param("i", $organization_id);
$stmt->execute();
$active_members = $stmt->get_result();

$count = $active_members->num_rows;

$stmt->close();
