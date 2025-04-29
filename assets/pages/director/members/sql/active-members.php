<?php
require_once BASE_PATH . '/assets/sql/conn.php';

$stmt = $conn->prepare("SELECT public_key, first_name, last_name, ao.name AS organization, status, state, date_joined
                        FROM student
                        INNER JOIN student_organization so
                            ON so.student_number = student.student_number
                        INNER JOIN account_organization ao
                            ON ao.organization_id = so.organization
                        INNER JOIN key_student ks
                            ON ks.student_number = student.student_number
                        WHERE state = 'Active' AND organization = 1
                        ORDER BY first_name ASC, last_name ASC");

$stmt->execute();
$active_members = $stmt->get_result();

$count = $active_members->num_rows;

$stmt->close();
