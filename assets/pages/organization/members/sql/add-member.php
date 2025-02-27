<?php
$documentDirectory = '../../../../../uploads/student-document/';
$profileImgDirectory = '../../../../../uploads/profile-img/';

if (!is_dir($documentDirectory)) {
    mkdir($documentDirectory, 0777, true);
}
if (!is_dir($profileImgDirectory)) {
    mkdir($profileImgDirectory, 0777, true);
}

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../../../../sql/conn.php';

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
    
    require_once '../../../../sql/plmun-program.php';


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
                $profileImgName = time() . "_" . uniqid() . ".jpg";
                $profileImgDestination = $profileImgDirectory . $profileImgName;
                $sqlProfileImgPath = '/cca/uploads/profile-img/' . $profileImgName;
        
                $imageTmpPath = $_FILES['profile_img']['tmp_name'];
                $imageType = exif_imagetype($imageTmpPath);
        
                if ($imageType == IMAGETYPE_PNG || $imageType == IMAGETYPE_JPEG) {
                    $image = ($imageType == IMAGETYPE_PNG) ? imagecreatefrompng($imageTmpPath) : imagecreatefromjpeg($imageTmpPath);
                    imagejpeg($image, $profileImgDestination, 90);
                    imagedestroy($image);
                } else {
                    move_uploaded_file($imageTmpPath, $profileImgDestination);
                }
        
                $stmt4 = $conn->prepare("INSERT INTO profile_image (student_number, user_id, path, date_uploaded) VALUES (?, NULL, ?, NOW())");
                $stmt4->bind_param("is", $student_number, $sqlProfileImgPath);
                $stmt4->execute();
            }
        } else {
            $stmt4 = $conn->prepare("INSERT INTO profile_image (student_number, user_id, path, date_uploaded) VALUES (?, NULL, NULL, NULL)");
            $stmt4->bind_param("i", $student_number);
            $stmt4->execute();
        }

    
        // STUDENT DOCUMENT
        if (!empty($_FILES['files']['name'][0])) {
            foreach ($_FILES['files']['tmp_name'] as $key => $tmpName) {
                if ($_FILES['files']['error'][$key] === UPLOAD_ERR_OK) {
                    $fileName = $_FILES['files']['name'][$key];
                    $fileType = mime_content_type($tmpName);
            
                    // Generate unique file name
                    $uniqueFileName = time() . "_" . uniqid();
                    $destination = $documentDirectory . $uniqueFileName;
                    $sqlFilePath = '/cca/uploads/student-document/' . $uniqueFileName;
            
                    if (in_array($fileType, ['image/png', 'image/jpeg'])) {
                        // Convert image to JPG
                        $image = ($fileType === 'image/png') ? imagecreatefrompng($tmpName) : imagecreatefromjpeg($tmpName);
                        $destination .= ".jpg";
                        $sqlFilePath .= ".jpg";
                        $convertedFileName = pathinfo($fileName, PATHINFO_FILENAME) . ".jpg";
                        imagejpeg($image, $destination, 90); // Save as JPG with 90% quality
                        imagedestroy($image);
                    } else {
                        // Keep original file extension for non-images
                        $destination .= "." . pathinfo($fileName, PATHINFO_EXTENSION);
                        $sqlFilePath .= "." . pathinfo($fileName, PATHINFO_EXTENSION);
                        $convertedFileName = $fileName;

                        move_uploaded_file($tmpName, $destination);
                    }
            
                    // Insert file data into database
                    $stmt5 = $conn->prepare("INSERT INTO student_document (student_number, file_name, path, date_uploaded) VALUES (?, ?, ?, NOW())");
                    if (!$stmt5) {
                        throw new Exception('Database error: ' . $conn->error);
                    }
                    $stmt5->bind_param("iss", $student_number, $convertedFileName, $sqlFilePath);
                    if (!$stmt5->execute()) {
                        throw new Exception('Failed to insert document into database: ' . $stmt5->error);
                    }
                } else {
                    throw new Exception('Upload error occurred for file: ' . $_FILES['files']['name'][$key]);
                }
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
