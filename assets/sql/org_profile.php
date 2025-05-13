<?php
$user_id = $_SESSION['user_id'];

$imageMap = [
    1 => "plmun-blckmvmnt.jpg",
    2 => "plmun-chorale.jpg",
    3 => "plmun-dulangsining.jpg",
    4 => "plmun-euphoria.jpg",
    5 => "plmun-fdc.jpg",
    6 => "plmun-kultura_teknika.jpg",
];

if (isset($imageMap[$user_id])) {
    $imagePath = "/cca/assets/img/organization/" . $imageMap[$user_id];
} else {
    $imagePath = "/cca/assets/img/blank-profile.png";
}
