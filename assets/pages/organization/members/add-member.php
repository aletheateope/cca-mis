<?php
$uploadDocumentDirectory = '../../../../uploads/student-document/';
$uploadProfileImgDirectory = '../../../../uploads/profile-img/';

if (!is_dir($uploadDocumentDirectory)) {
    mkdir($uploadDocumentDirectory, 0777, true);
}
if (!is_dir($uploadProfileImgDirectory)) {
    mkdir($uploadProfileImgDirectory, 0777, true);
}

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../../../sql/conn.php';

    include_once '../../../sql/plmun-program.php';

    $first_name = $_POST['first_name'] ?? null;
    $middle_name = $_POST['middle_name'] ?? null;
    $last_name = $_POST['last_name'] ?? null;
    $birthdate = $_POST['birthdate'] ?? null;
    $age = $_POST['age'] ?? null;
    $gender = $_POST['gender'];
    $mobile_number = $_POST['contact_number'] ?? null;
    $email = $_POST['email'] ?? null;
    $address = $_POST['address'] ?? null;

    $student_number = $_POST['student_number'] ?? null;
    $program = $_POST['program'] ?? null;
    $year_level = $_POST['year_level'] ?? null;

    $date_joined = $_POST['date_joined'] ?? null;
    $date_left = $_POST['date_left'] ?? null;
    $status = $_POST['status'] ?? null;
    $state = $_POST['state'] ?? null;

    $organization_id = $_SESSION['user_id'] ?? null;

    // if ($organization_id === null) {
    //     $response[] = 'Error: Unauthorized access or session expired.';
    //     echo json_encode($response);
    //     exit;
    // }

    $program_id = $programs[strtok($program, ' -')] ?? null;

    $conn->begin_transaction();
    try {
        $sql = 'INSERT INTO student (student_number, first_name, middle_name, last_name, birthdate, age, gender, mobile_number, email, address) VALUES (?,?,?,?,?,?,?,?,?,?)';
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception('Database error: ' . $conn->error);
        }
        $stmt->bind_param("isssssssss", $student_number, $first_name, $middle_name, $last_name, $birthdate, $age, $gender, $mobile_number, $email, $address);
        if (!$stmt->execute()) {
            throw new Exception('Failed to insert student data: ' . $stmt->error);
        }

        $sql2 = 'INSERT INTO student_academic_info (student_number, program_id, year_level) VALUES (?,?,?)';
        $stmt2 = $conn->prepare($sql2);
        if (!$stmt2) {
            throw new Exception('Database error: ' . $conn->error);
        }
        $stmt2->bind_param("iss", $student_number, $program_id, $year_level);
        if (!$stmt2->execute()) {
            throw new Exception('Failed to insert academic information: ' . $stmt2->error);
        }

        $sql3 = 'INSERT INTO student_organization (student_number, organization_id, date_joined, date_left, status, state) VALUES (?,?,?,?,?,?)';
        $stmt3 = $conn->prepare($sql3);
        if (!$stmt3) {
            throw new Exception('Database error: ' . $conn->error);
        }
        $stmt3->bind_param("isssss", $student_number, $organization_id, $date_joined, $date_left, $status, $state);
        if (!$stmt3->execute()) {
            throw new Exception('Failed to insert student organization data: ' . $stmt3->error);
        }

        //STUDENT PROFILE IMAGE
        if (isset($_FILES['profile_img']) && $_FILES['profile_img']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['profile_img']['error'] == UPLOAD_ERR_OK) {
                $profileImgName = basename($_FILES['profile_img']['name']);
                $profileImgDestination = $uploadProfileImgDirectory . $profileImgName;
                $sqlProfileImgPath = '/cca/uploads/profile-img/' . $profileImgName;

                if (move_uploaded_file($_FILES['profile_img']['tmp_name'], $profileImgDestination)) {
                    // Update the student table with the profile image path
                    $stmt4 = $conn->prepare("INSERT INTO profile_image (student_number, user_id, path, date_uploaded) VALUES (?, NULL, ?, NOW())");
                    if (!$stmt4) {
                        throw new Exception('Database error: ' . $conn->error);
                    }
                    $stmt4->bind_param("is", $student_number, $sqlProfileImgPath);
                    if (!$stmt4->execute()) {
                        throw new Exception('Failed to update student profile image: ' . $stmt4->error);
                    }
                } else {
                    throw new Exception('Failed to upload profile image: ' . $profileImgName);
                }
            } else {
                throw new Exception('Upload error occurred.');
            }
        }




        // STUDENT DOCUMENT
        if (isset($_FILES['files']) && $_FILES['files']['error'][0] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['files']['error'][0] == UPLOAD_ERR_OK) {
                foreach ($_FILES['files']['tmp_name'] as $key => $tmpName) {
                    $fileName = basename($_FILES['files']['name'][$key]);
                    $destination = $uploadDocumentDirectory . $fileName;
                    $sqlFilePath = '/cca/uploads/student-document/' . $fileName;

                    if (move_uploaded_file($tmpName, $destination)) {
                        // Insert the file path and date into the student_document table
                        $stmt5 = $conn->prepare("INSERT INTO student_document (student_number, path, date_uploaded) VALUES (?, ?, NOW())");
                        if (!$stmt5) {
                            throw new Exception('Database error: ' . $conn->error);
                        }
                        $stmt5->bind_param("is", $student_number, $sqlFilePath);
                        if (!$stmt5->execute()) {
                            throw new Exception('Failed to insert document into database: ' . $stmt5->error);
                        }
                    } else {
                        throw new Exception('Failed to upload file: ' . $fileName);
                    }
                }
            } else {
                throw new Exception('Upload error occurred.');
            }
        }


        $conn->commit();
        $response[] = 'Success: Student registered successfully with uploaded documents.';
    } catch (Exception $e) {
        $conn->rollback();
        $response[] = 'Error: ' . $e->getMessage();
    }
}

echo json_encode($response);
