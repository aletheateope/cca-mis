<?php
require_once BASE_PATH . '/assets/sql/conn.php';

$user_id = $_SESSION['user_id'];

if(!isset($_GET['stud-num'])) {
    include BASE_PATH . '/assets/pages/access_denied.php';
    exit;
}

$student_number = $_GET['stud-num'];

$stmt = $conn->prepare("SELECT COUNT(*)
                        FROM student_organization
                        WHERE student_number = ? AND organization = ?");
$stmt->bind_param("ii", $student_number, $user_id);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();

$stmt->close();

if ($count == 0) {
    include BASE_PATH . '/assets/pages/access_denied.php';
    exit;
}

// PERSONAL INFORMATION
$stmt = $conn-> prepare("SELECT first_name, middle_name, last_name, birthdate, age, gender, mobile_number, email, address
                        FROM student
                        WHERE student_number = ?");
$stmt->bind_param("i", $student_number);
$stmt->execute();
$result_personal_information = $stmt->get_result();

$stmt->close();

// RECOGNITION
$stmt = $conn->prepare("SELECT recognition, COALESCE(ec.title, aa.title) AS event_title, COALESCE(ec.start_date, aa.start_date) AS date
                        FROM student_participation sp
                        INNER JOIN activity_accomplishment aa
                            ON aa.activity_id = sp.activity_id
                        LEFT JOIN event_calendar ec
                            ON ec.event_id = aa.event_id
                        WHERE sp.student_number = ? AND recognition IS NOT NULL
                        ORDER BY date DESC, event_title ASC, recognition ASC
                        LIMIT 10");
$stmt->bind_param("i", $student_number);
$stmt->execute();
$result_recognition = $stmt->get_result();

$stmt->close();

// ALL RECOGNITION
$stmt = $conn->prepare("SELECT COALESCE(ec.title, aa.title) AS event_title, recognition, COALESCE(ec.start_date, aa.start_date) AS date
                        FROM student_participation sp
                        INNER JOIN activity_accomplishment aa
                            ON aa.activity_id = sp.activity_id
                        LEFT JOIN event_calendar ec
                            ON ec.event_id = aa.event_id
                        WHERE student_number = ? AND recognition IS NOT NULL
                        ORDER BY date DESC, event_title ASC, recognition ASC");
$stmt->bind_param("i", $student_number);
$stmt->execute();
$result_all_recognition = $stmt->get_result();

$stmt->close();

// ACADEMIC INFORMATION
$stmt = $conn->prepare("SELECT student_number, pcollege.name AS college_name, pcollege.abbreviation AS college_abbreviation, pcourse.title AS course, year_level
                        FROM student_academic_info sai
                        INNER JOIN program p
                            ON p.program_id = sai.program_id
                        INNER JOIN program_college pcollege
                            ON pcollege.college_id = p.college_id
                        INNER JOIN program_course pcourse
                            ON pcourse.course_id = p.course_id
                        WHERE student_number = ?");
$stmt->bind_param("i", $student_number);
$stmt->execute();
$result_academic_information = $stmt->get_result();

$stmt->close();

// MEMBERSHIP INFORMATION
$stmt = $conn->prepare("SELECT ao.name AS organization, status, state, date_joined, date_left
                        FROM student_organization so
                        INNER JOIN account_organization ao
                            ON ao.organization_id = so.organization
                        WHERE student_number = ?");
$stmt->bind_param("i", $student_number);
$stmt->execute();
$result_membership_organization = $stmt->get_result();

$stmt->close();

// STUDENT DOCUMENTS
$stmt = $conn->prepare("SELECT public_key, file_name
                        FROM student_document sd
                        INNER JOIN key_student_document ksd
                            ON ksd.document_id = sd.document_id
                        WHERE student_number = ?");
$stmt->bind_param("i", $student_number);
$stmt->execute();
$result_documents = $stmt->get_result();

$stmt->close();

// STUDENT PARTICIPATION
$stmt = $conn->prepare("SELECT DISTINCT COALESCE(ec.title, aa.title) AS event_title, COALESCE(ec.start_date, aa.start_date) AS date
                        FROM student_participation sp
                        INNER JOIN activity_accomplishment aa
                            ON aa.activity_id = sp.activity_id
                        LEFT JOIN event_calendar ec
                            ON ec.event_id = aa.event_id
                        WHERE student_number = ?
                        ORDER BY date DESC");
$stmt->bind_param("i", $student_number);
$stmt->execute();
$result_participation = $stmt->get_result();

$stmt->close();
