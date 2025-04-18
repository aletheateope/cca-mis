<?php
header("Content-Type: application/json");

require_once '../../../../sql/base-path.php';

$activityGalleryDirectory = BASE_PATH . '/uploads/activity-gallery/';

if (!is_dir($activityGalleryDirectory)) {
    mkdir($activityGalleryDirectory, 0777, true);
}

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "error" => "Invalid request method."]);
    exit;
}

session_start();

require_once BASE_PATH . '/assets/sql/conn.php';


$report_id = $_SESSION['accomplishment_report_id'];

$event = $_POST['event'] ?? null;

$title = $_POST['title'] ?? null;
$description = $_POST['description'] ?? null;
$location = $_POST['location'] ?? null;
$start_date = $_POST['start_date'] ?? null;
$end_date = $_POST['end_date'] ?? null;

$target_participants = $_POST['target_participants'] ?? null;
$actual_participants = $_POST['actual_participants'] ?? null;

$public_keys = $_POST["public_keys"] ?? [];
$recognitions = $_POST["recognition"] ?? [];

$objective = !empty($_POST['objective']) ? $_POST['objective'] : null;
$challenges_solutions = !empty($_POST['challenges_solution']) ? $_POST['challenges_solution'] : null;
$lesson_learned = !empty($_POST['lesson_learned']) ? $_POST['lesson_learned'] : null;
$suggestion = !empty($_POST['suggestion']) ? $_POST['suggestion'] : null;
$budget_utilized = str_replace(',', '', $_POST['budget_utilized'] ?? null);
$remark = $_POST['remark'] ?? null;


if ($event == 0) {
    $stmt = $conn->prepare("INSERT INTO activity_accomplishment (title, description, location, start_date, end_date, target_participants,
                                                        actual_participants, objective, challenges_solution, lesson_learned, suggestion,
                                                        budget_utilized, remark) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "sssssiissssdi",
        $title,
        $description,
        $location,
        $start_date,
        $end_date,
        $target_participants,
        $actual_participants,
        $objective,
        $challenges_solutions,
        $lesson_learned,
        $suggestion,
        $budget_utilized,
        $remark
    );

    if (!$stmt->execute()) {
        echo json_encode(["success" => false, "message" => "Failed to insert activity accomplishment: " . $stmt->error]);
        exit;
    }

    $activity_id = $stmt->insert_id;
    
    $stmt->close();
} else {
    $stmt = $conn->prepare("SELECT event_id FROM key_event WHERE public_key = ?");
    $stmt->bind_param("s", $event);
    $stmt->execute();
    $stmt->bind_result($event_id);
    $stmt->fetch();

    $stmt->close();

    if(!$event_id) {
        echo json_encode(["success" => false, "message" => "Event not found."]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO activity_accomplishment 
                            (event_id, target_participants, actual_participants, objective, challenges_solution, lesson_learned, suggestion, budget_utilized, remark)
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "iiissssdi",
        $event_id,
        $target_participants,
        $actual_participants,
        $objective,
        $challenges_solutions,
        $lesson_learned,
        $suggestion,
        $budget_utilized,
        $remark
    );

    if (!$stmt->execute()) {
        echo json_encode(["success" => false, "message" => "Failed to insert activity accomplishment: " . $stmt->error]);
        exit;
    }
    
    $activity_id = $stmt->insert_id;

    $stmt->close();
}

require_once BASE_PATH . '/assets/sql/public-key.php';

$activityKeyCount = 1;

do {
    $insert_public_key = generatePublicKey();
            
    $stmtKey = $conn->prepare("SELECT COUNT(*) FROM key_activity WHERE public_key = ?");
    $stmtKey->bind_param("s", $insert_public_key);
    $stmtKey->execute();
    $stmtKey->bind_result($activityKeyCount);
    $stmtKey->fetch();
    $stmtKey->close();
} while ($activityKeyCount > 0);
        

$stmt2 = $conn->prepare("INSERT INTO key_activity (activity_id, public_key) VALUES (?, ?)");
$stmt2->bind_param("is", $activity_id, $insert_public_key);

if (!$stmt2->execute()) {
    echo json_encode(["success" => false, "message" => "Failed to insert public key: " . $stmt2->error]);
    exit;
}

$stmt2->close();

// Update accomplishment_report with activity_id
$stmt3 = $conn->prepare('UPDATE accomplishment_report SET activity_id = ? WHERE report_id = ?');
$stmt3->bind_param("ii", $activity_id, $report_id);

if (!$stmt3->execute()) {
    echo json_encode(["success" => false, "message" => "Failed to update accomplishment_report: " . $stmt3->error]);
    exit;
}

$stmt3->close();

foreach ($public_keys as $public_key) {
    $sql = $conn->prepare("SELECT student_number FROM key_student WHERE public_key = ?");
    $sql->bind_param("s", $public_key);

    if (!$sql->execute()) {
        echo json_encode(["success" => false, "message" => "Failed to fetch student number: " . $sql->error]);
        exit;
    }

    $sql->bind_result($student_number);
    $sql->fetch();
    $sql->close();

    $stmt4 = $conn->prepare("INSERT INTO student_participation (activity_id, student_number, recognition) VALUES (?, ?, ?)");

    // Check if the student has recognitions
    if (isset($recognitions[$public_key])) {
        foreach ($recognitions[$public_key] as $recognition_text) {
            if (!empty(trim($recognition_text))) {
                $stmt4->bind_param("iis", $activity_id, $student_number, $recognition_text);

                if ($stmt4->execute()) {
                    $participation_id = $stmt4->insert_id;

                    $participationKeyCount = 1;

                    do {
                        $public_key = generatePublicKey();
    
                        $stmtKey = $conn->prepare("SELECT COUNT(*) FROM key_student_participation WHERE public_key = ?");
                        $stmtKey->bind_param("s", $public_key);
                        $stmtKey->execute();
                        $stmtKey->bind_result($participationKeyCount);
                        $stmtKey->fetch();
                        $stmtKey->close();
                    } while ($participationKeyCount > 0);


                    $stmt6 = $conn->prepare("INSERT INTO key_student_participation (participation_id, public_key) VALUES (?, ?)");
                    $stmt6->bind_param("is", $participation_id, $public_key);

                    if (!$stmt6->execute()) {
                        echo json_encode(["success" => false, "message" => "Failed to insert public key: " . $stmt6->error]);
                        exit;
                    }

                    $stmt6->close();
                }
            }
        }
    } else {
        // If no recognition, insert NULL
        $recognition_text = null;

        $stmt4->bind_param("iis", $activity_id, $student_number, $recognition_text);


        if ($stmt4->execute()) {
            $participation_id = $stmt4->insert_id;

            $participationKeyCount = 1;

            do {
                $public_key = generatePublicKey();
    
                $stmtKey = $conn->prepare("SELECT COUNT(*) FROM key_student_participation WHERE public_key = ?");
                $stmtKey->bind_param("s", $public_key);
                $stmtKey->execute();
                $stmtKey->bind_result($participationKeyCount);
                $stmtKey->fetch();
                $stmtKey->close();
            } while ($participationKeyCount > 0);


            $stmt6 = $conn->prepare("INSERT INTO key_student_participation (participation_id, public_key) VALUES (?, ?)");
            $stmt6->bind_param("is", $participation_id, $public_key);

            if (!$stmt6->execute()) {
                echo json_encode(["success" => false, "message" => "Failed to insert public key: " . $stmt6->error]);
                exit;
            }

            $stmt6->close();

        }
    }
    $stmt4->close();
}

if (!empty($_FILES['activity_gallery']['name'][0])) {
    foreach ($_FILES['activity_gallery']['tmp_name'] as $key => $tmpName) {
        if ($_FILES['activity_gallery']['error'][$key] === UPLOAD_ERR_OK) {
            $fileName = $_FILES['activity_gallery']['name'][$key];
            $fileType = mime_content_type($tmpName);

            $uniqueFileName = time() . "_" . uniqid();
            $destination = $activityGalleryDirectory . $uniqueFileName;
            $sqlFilePath = '/cca/uploads/activity-gallery/' . $uniqueFileName;

            if (in_array($fileType, ['image/png', 'image/jpeg'])) {
                $image = ($fileType === 'image/png') ? imagecreatefrompng($tmpName) : imagecreatefromjpeg($tmpName);
                $destination .= ".jpg";
                $sqlFilePath .= ".jpg";
                imagejpeg($image, $destination, 90); // Save as JPG with 90% quality
                imagedestroy($image);
            } else {
                $destination .= "." . pathinfo($fileName, PATHINFO_EXTENSION);
                $sqlFilePath .= "." . pathinfo($fileName, PATHINFO_EXTENSION);
                move_uploaded_file($tmpName, $destination);
            }

            $stmt5 = $conn->prepare("INSERT INTO activity_gallery (activity_id, path) VALUES (?, ?)");
            $stmt5->bind_param("is", $activity_id, $sqlFilePath);

            if (!$stmt5->execute()) {
                echo json_encode(["success" => false, "message" => "Failed to insert activity gallery: " . $stmt5->error]);
                exit;
            }
        } else {
            echo json_encode(["error" => "Error uploading file: " . $_FILES['activity_gallery']['error'][$key]]);
            exit;
        }
    }
}

echo json_encode(["success" => true, "message" => "Activity submitted successfully."]);
exit;
