<?php
header('Content-Type: application/json');

require_once '../../../../sql/base_path.php';
require_once BASE_PATH . '/assets/sql/dotenv.php';
require_once BASE_PATH . '/assets/sql/conn.php';

require_once BASE_PATH . '/assets/sql/google_calendar/service_account.php';
require_once BASE_PATH . '/assets/sql/public_key.php';

function addCalendarAcl($service, $calendarId, $email, $role)
{
    $rule = new Google_Service_Calendar_AclRule();
    $rule->setRole($role);

    $scope = new Google_Service_Calendar_AclRuleScope();
    $scope->setType('user');
    $scope->setValue($email);

    $rule->setScope($scope);
    $service->acl->insert($calendarId, $rule);
}


if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$role = $_POST['role'] ?? null;
$first_name = $_POST['first_name'] ?? null;
$last_name = $_POST['last_name'] ?? null;
$name = $_POST['name'] ?? null;
$email = $_POST['email'] ?? null;

if ($role == 3) {
    if (empty($name) || empty($email)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit;
    }
} else {
    if (empty($first_name) || empty($last_name) || empty($email)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit;
    }
}

$roles = [
    1 => "Director",
    2 => "VPSLD",
    3 => "Organization"
];

$role_name = $roles[$role] ?? null;

if (!$role_name) {
    echo json_encode(['success' => false, 'message' => 'Invalid role value']);
    exit;
}

$conn->begin_transaction();
try {
    $stmt = $conn->prepare("INSERT INTO account (email, role) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $role_name);
    try {
        $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() === 1062) {
            echo json_encode(['success' => false, 'message' => 'Email already exists']);
            exit;
        } else {
            throw $e;
        }
    }
    $user_id = $conn->insert_id;
    $stmt->close();

    if ($role == 3) {
        $stmt = $conn->prepare("INSERT INTO account_organization (user_id, name) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $name);
        $stmt->execute();
        $organization_id = $stmt->insert_id;
        $stmt->close();
    } else {
        $stmt = $conn->prepare("INSERT INTO account_admin (user_id, first_name, last_name, date_created) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iss", $user_id, $first_name, $last_name);
        $stmt->execute();
        $stmt->close();
    }

    $stmt = $conn->prepare("INSERT INTO profile_image (user_id) VALUES (?)");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    do {
        $public_key = generatePublicKey();
        $stmt = $conn->prepare("UPDATE account SET public_key = ? WHERE user_id = ?");
        $stmt->bind_param("si", $public_key, $user_id);
        try {
            $stmt->execute();
            $stmt->close();
            break;
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() === 1062) {
                continue;
            } else {
                throw $e;
            }
        }
    } while (true);


    $conn->commit();
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Failed to create user']);
    exit;
}

if ($role == 3) {
    try {
        $client = googleClient();
        $service = new Google_Service_Calendar($client);
        $calendar = new Google_Service_Calendar_Calendar();
        $calendar->setSummary($name);
        $calendar->setTimeZone('Asia/Manila');

        $createdCalendar = $service->calendars->insert($calendar);
        $calendarId = $createdCalendar->getId();

        $stmt = $conn->prepare("UPDATE account_organization SET google_calendar_id = ? WHERE organization_id = ?");
        $stmt->bind_param("si", $calendarId, $organization_id);
        if(!$stmt->execute()) {
            echo json_encode(['success' => false, 'message' => 'Failed to add user']);
            exit;
        }
        $stmt->close();

        try {
            addCalendarAcl($service, $calendarId, $_ENV['CALENDAR_GMAIL'], 'owner');
            addCalendarAcl($service, $calendarId, $email, 'reader');
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Failed to share calendar']);
            exit;
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Calendar creation failed' ]);
        exit;
    }
}

echo json_encode([
    'success' => true,
    'data' => [
        'public_key' => $public_key,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'name' => $name,
        'email' => $email,
        'role' => $role,
        'role_name' => $role_name
    ]
]);
