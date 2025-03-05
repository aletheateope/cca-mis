<?php
$activityGalleryDirectory = '../../../../../uploads/activity-gallery/';

if (!is_dir($activityGalleryDirectory)) {
    mkdir($activityGalleryDirectory, 0777, true);
}

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../../../../sql/conn.php';

    $title = $_POST['title'] ?? null;
    $description = $_POST['description'] ?? null;
    $location = $_POST['location'] ?? null;
    $event = $_POST['event'] ?? null;
    $start_date = $_POST['start_date'] ?? null;
    $end_date = $_POST['end_date'] ?? null;

    $target_participants = $_POST['target_participants'] ?? null;
    $actual_participants = $_POST['actual_participants'] ?? null;

    $student_numbers = $_POST["student_numbers"] ?? [];
    $recognitions = $_POST["recognition"] ?? [];

    $objective = !empty($_POST['objective']) ? $_POST['objective'] : null;
    $challenges_solutions = !empty($_POST['challenges_solution']) ? $_POST['challenges_solution'] : null;
    $lesson_learned = !empty($_POST['lesson_learned']) ? $_POST['lesson_learned'] : null;
    $suggestion = !empty($_POST['suggestion']) ? $_POST['suggestion'] : null;
    $budget_utilized = str_replace(',', '', $_POST['budget_utilized'] ?? null);
    $remark = $_POST['remark'] ?? null;

    $report_id = $_POST['report_id'] ?? null;

    $conn->begin_transaction();
    try {

        $sql1 = 'INSERT INTO activity_accomplishment (title, description, location, start_date, end_date, target_participants,
                                                        actual_participants, objective, challenges_solution, lesson_learned, suggestion,
                                                        budget_utilized, remark) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $stmt1 = $conn->prepare($sql1);
        if (!$stmt1) {
            throw new Exception('Database error: ' . $conn->error);
        }
        $stmt1->bind_param(
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
        if (!$stmt1->execute()) {
            throw new Exception('Failed to insert activity accomplishment data: ' . $stmt1->error);
        }

        $activity_id = $conn->insert_id;

        // Update accomplishment_report with activity_id
        $updateSql = 'UPDATE accomplishment_report SET activity_id = ? WHERE accomplishment_report_id = ?';
        $updateStmt = $conn->prepare($updateSql);
        if (!$updateStmt) {
            throw new Exception('Database error: ' . $conn->error);
        }
        $updateStmt->bind_param("ii", $activity_id, $report_id);
        if (!$updateStmt->execute()) {
            throw new Exception('Failed to update accomplishment_report: ' . $updateStmt->error);
        }
        
        $sql2 = 'INSERT INTO student_participation (activity_id, student_number, recognition) VALUES (?, ?, ?)';
        $stmt2 = $conn->prepare($sql2);
        if (!$stmt2) {
            throw new Exception('Database error: ' . $conn->error);
        }

        foreach ($student_numbers as $student_number) {
            // Check if the student has recognitions
            if (isset($recognitions[$student_number])) {
                foreach ($recognitions[$student_number] as $recognition_text) {
                    if (!empty(trim($recognition_text))) {
                        $stmt2->bind_param("iss", $activity_id, $student_number, $recognition_text);
                        if (!$stmt2->execute()) {
                            throw new Exception('Failed to insert student participation: ' . $stmt2->error);
                        }
                    }
                }
            } else {
                // If no recognition, insert NULL
                $recognition_text = null;
                $stmt2->bind_param("iss", $activity_id, $student_number, $recognition_text);
                if (!$stmt2->execute()) {
                    throw new Exception('Failed to insert student participation: ' . $stmt2->error);
                }
            }
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

                    $stmt4 = $conn->prepare("INSERT INTO activity_gallery (activity_id, path) VALUES (?, ?)");
                    if (!$stmt4) {
                        throw new Exception('Database error: ' . $conn->error);
                    }
                    $stmt4->bind_param("is", $activity_id, $sqlFilePath);
                    if (!$stmt4->execute()) {
                        $response[] = 'Failed to insert activity gallery: ' . $stmt4->error;
                    }
                } else {
                    $response[] = 'Upload error: ' . $_FILES['activity_gallery']['error'][$key];
                }
            }
        }

        $conn->commit();
        $response[] = 'Success: Activity Created';
    } catch (Exception $e) {
        $conn->rollback();
        $response[] = 'Error: ' . $e->getMessage();
    }
}
echo json_encode($response);
