<?php
require_once '../base_path.php';

require_once BASE_PATH . '/vendor/autoload.php';
require_once BASE_PATH . '/assets/sql/conn.php';

session_start();

require_once 'google_client.php';


// Authenticate the user
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token['access_token']);

    // Get user info
    $google_service = new Google\Service\Oauth2($client);
    $user_info = $google_service->userinfo->get();

    $email = $user_info->email;

    // Check if the email is in the database
    $stmt = $conn->prepare("SELECT user_id, role FROM account WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();


    if ($user) {
        // Store login state in session
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_email'] = $email;
        $_SESSION['user_role'] = $user['role'];



        if ($user['role'] == 'Director'|| $user['role'] == 'VPSLD') {
            $stmt = $conn->prepare("SELECT first_name, last_name, date_created FROM account_admin WHERE admin_id = ?");
            $stmt->bind_param("i", $user['user_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $admin = $result->fetch_assoc();

            // Store director-specific details in the session
            $_SESSION['first_name'] = $admin['first_name'];
            $_SESSION['last_name'] = $admin['last_name'];
            $_SESSION['date_created'] = $admin['date_created'];

            if ($user['role'] == 'Director') {
                header("Location: /cca/assets/pages/director/dashboard/dashboard_page.php");
            } elseif ($user['role'] == 'VPSLD') {
                header("Location: /cca/assets/pages/vpsld/dashboard/dashboard_page.php");
            }

        } elseif ($user['role'] == 'Organization') {
            $stmt = $conn->prepare("SELECT name FROM account_organization WHERE organization_id = ?");
            $stmt->bind_param("i", $user['user_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $organization = $result->fetch_assoc();

            // Store organization-specific details in the session
            $_SESSION['organization_name'] = $organization['name'];
            $_SESSION['date_created'] = $organization['date_created'];

            header("Location: /cca/assets/pages/organization/dashboard/dashboard_page.php");
        } else {
            echo "Invalid role!";
        }
    } else {
        // If email is not found in the database
        echo "Login failed! Your email is not registered.";
    }
}
