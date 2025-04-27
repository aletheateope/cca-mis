<?php
header("Content-Type: application/json");

require_once '../../../../sql/base-path.php';

session_start();

$documentDirectory = BASE_PATH . '/uploads/student-document/';
$profileImgDirectory = BASE_PATH . '/uploads/profile-img/';

if (!is_dir($documentDirectory)) {
    mkdir($documentDirectory, 0777, true);
}
if (!is_dir($profileImgDirectory)) {
    mkdir($profileImgDirectory, 0777, true);
}

require_once BASE_PATH . '/assets/sql/public-key.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once BASE_PATH . '/assets/sql/conn.php';

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
    
    require_once BASE_PATH . '/assets/sql/plmun-program.php';


    $conn->begin_transaction();
    try {
        $stmt1 = $conn->prepare("INSERT INTO student (student_number, first_name, middle_name, last_name, birthdate, age, gender, mobile_number, email, address) VALUES (?,?,?,?,?,?,?,?,?,?)");
        $stmt1->bind_param("isssssssss", $student_number, $first_name, $middle_name, $last_name, $birthdate, $age, $gender, $mobile_number, $email, $address);
        $stmt1->execute();

        $stmt2 = $conn->prepare("INSERT INTO student_academic_info (student_number, program_id, year_level) VALUES (?,?,?)");
        $stmt2->bind_param("iss", $student_number, $program_id, $year_level);
        $stmt2->execute();

        $stmt3 = $conn->prepare("INSERT INTO student_organization (student_number, organization, date_joined, date_left, status, state) VALUES (?,?,?,?,?,?)");
        $stmt3->bind_param("isssss", $student_number, $organization_id, $date_joined, $date_left, $status, $state);
        $stmt3->execute();

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
        if (!empty($_FILES['document']['name'][0])) {
            foreach ($_FILES['document']['tmp_name'] as $key => $tmpName) {
                if ($_FILES['document']['error'][$key] === UPLOAD_ERR_OK) {
                    $fileName = $_FILES['document']['name'][$key];
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
                    $stmt5 = $conn->prepare("INSERT INTO student_document (organization_id, student_number, file_name, path, date_uploaded) VALUES (?, ?, ?, ?, NOW())");
                    $stmt5->bind_param("iiss", $organization_id, $student_number, $convertedFileName, $sqlFilePath);
                    if($stmt5->execute()) {
                        $document_id = $stmt5->insert_id;

                        $dcoumentKeyCount = 1;

                        do {
                            $public_key = generatePublicKey();
    
                            $stmtKey = $conn->prepare("SELECT COUNT(*) FROM key_student_document WHERE public_key = ?");
                            $stmtKey->bind_param("s", $public_key);
                            $stmtKey->execute();
                            $stmtKey->bind_result($dcoumentKeyCount);
                            $stmtKey->fetch();
                            $stmtKey->close();
                        } while ($dcoumentKeyCount > 0);

                        $stmt7 = $conn->prepare("INSERT INTO key_student_document (document_id, public_key) VALUES (?, ?)");
                        $stmt7->bind_param("is", $document_id, $public_key);
                        $stmt7->execute();

                    }
                } else {
                    throw new Exception('Upload error occurred for file: ' . $_FILES['document']['name'][$key]);
                }
            }
        }

        $conn->commit();

        $studentKeyCount = 1;

        do {
            $public_key = generatePublicKey();
    
            $key = "SELECT COUNT(*) FROM key_student WHERE public_key = ?";
            $stmtKey = $conn->prepare($key);
            $stmtKey->bind_param("s", $public_key);
            $stmtKey->execute();
            $stmtKey->bind_result($studentKeyCount);
            $stmtKey->fetch();
            $stmtKey->close();
        } while ($studentKeyCount > 0);


        $stmt6 = $conn->prepare("INSERT INTO key_student (student_number, public_key) VALUES (?, ?)");
        $stmt6->bind_param("is", $student_number, $public_key);
        $stmt6->execute();

        echo json_encode([
           'success' => true,
      ]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode([
             'success' => false,
             'message' => 'Error: ' . $e->getMessage()
         ]);
    }
}
