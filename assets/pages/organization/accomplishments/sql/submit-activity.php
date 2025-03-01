<?php
$activityGalleryDirectory = '../../../../../uploads/activity-gallery/';

if (!is_dir($activityGalleryDirectory)) {
    mkdir($activityGalleryDirectory, 0777, true);
}

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../../../../sql/conn.php';

    $conn->begin_transaction();

    $title = $_POST['title'] ?? null;

    try {

        $sql1 = 'INSERT INTO activity_accomplishment (title) VALUES (?)';
        $stmt1 = $conn->prepare($sql1);
        if (!$stmt1) {
            throw new Exception('Database error: ' . $conn->error);
        }
        $stmt1->bind_param("s", $title);
        if (!$stmt1->execute()) {
            throw new Exception('Failed to insert activity accomplishment data: ' . $stmt1->error);
        }

        $activity_id = $conn->insert_id;

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
                        throw new Exception('Failed to insert activity gallery: ' . $stmt4->error);
                    }
                } else {
                    throw new Exception('Upload error occurred for file: ' . $_FILES['activity_gallery']['name'][$key]);
                }
            }
        }

        $conn->commit();
        $response[] = 'Success: Activity Success';

    } catch (Exception $e) {
        $conn->rollback();
        $response[] = 'Error: ' . $e->getMessage();
    }
}
echo json_encode($response);
