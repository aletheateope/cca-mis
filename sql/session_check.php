<?php
session_start();

// Redirect to login page if not logged in

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: /index.php"); // Redirect to login page if not logged in
    exit();
}

// Access user details from session

// $user_id = $_SESSION['user_id'];
// $user_email = $_SESSION['user_email'];
// $user_role = $_SESSION['user_role'];
// $date_created = $_SESSION['date_created'];


// if ($user_role == 'director') {
//     $first_name = $_SESSION['first_name'];
//     $last_name = $_SESSION['last_name'];
// } elseif ($user_role == 'organization') {
//     // $organization_name = $_SESSION['name'];
// }
