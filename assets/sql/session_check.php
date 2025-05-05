<?php
function check_role($required_role)
{
    session_start();
    
    // Check if the user is logged in
    
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        header("Location: /cca/index.php");
        exit();
    }

    // Check the role
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== $required_role) {
        header("Location: /cca/assets/pages/access_denied.php");
        exit();
    }
}
