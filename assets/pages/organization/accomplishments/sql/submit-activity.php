<?php
$activityGalleryDirectory = '../../../../../uploads/activity-gallery';

if (!is_dir($activityGalleryDirectory)) {
    mkdir($activityGalleryDirectory, 0777, true);
}

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../../../../sql/conn.php';


}
