<?php
require_once BASE_PATH . '/assets/sql/conn.php';

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT
                            COUNT(*) AS total_members,
                            COUNT(CASE WHEN MONTH(date_joined) = MONTH(CURRENT_DATE()) AND YEAR(date_joined) = YEAR(CURRENT_DATE()) THEN 1 END) AS members_added_this_month
                        FROM student_organization
                        WHERE state = 'Active' AND organization = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result_members = $stmt->get_result();
$row_members = $result_members->fetch_assoc();

$active_members = $row_members['total_members'];
$members_added = $row_members['members_added_this_month'];

$stmt->close();

$stmt = $conn->prepare("SELECT
                            COUNT(CASE WHEN state = 'Inactive' AND MONTH(date_joined) = MONTH(CURRENT_DATE())
                                AND YEAR(date_joined) = YEAR(CURRENT_DATE()) THEN 1 END) AS inactive_members,
                            COUNT(CASE WHEN state = 'Exited' AND MONTH(date_joined) = MONTH(CURRENT_DATE())
                                AND YEAR(date_joined) = YEAR(CURRENT_DATE()) THEN 1 END) AS exited_members,
                            COUNT(CASE WHEN state = 'Terminated' AND MONTH(date_joined) = MONTH(CURRENT_DATE())
                                AND YEAR(date_joined) = YEAR(CURRENT_DATE()) THEN 1 END) AS terminated_members
                        FROM student_organization
                        WHERE organization = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result_inactive = $stmt->get_result();
$row_inactive = $result_inactive->fetch_assoc();

$inactive_members = $row_inactive['inactive_members'];
$exited_members = $row_inactive['exited_members'];
$terminated_members = $row_inactive['terminated_members'];

$stmt->close();
