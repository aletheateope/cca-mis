<?php
header('Content-Type: application/json');

require_once '../../../../sql/base_path.php';

require_once BASE_PATH . '/assets/sql/conn.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$role = $_POST['role'] ?? null;
$first_name = $_POST['first_name'] ?? null;
$last_name = $_POST['last_name'] ?? null;
$email = $_POST['email'] ?? null;

if (empty($role) || empty($first_name) || empty($last_name) || empty($email)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

$stmt = $conn->prepare("SELECT email FROM account WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Email already exists']);
    exit;
}

$stmt->close();

$roles = [
    1 => "Director",
    2 => "VPSLD"
];

$role_name = $roles[$role] ?? null;

if (!$role_name) {
    echo json_encode(['success' => false, 'message' => 'Invalid role value']);
    exit;
}

$stmt = $conn->prepare("INSERT INTO account (email, role) VALUES (?, ?)");
$stmt->bind_param("ss", $email, $role_name);

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Failed to add user' . $conn->error]);
    exit;
}

$user_id = $conn->insert_id;

$stmt->close();

$stmt = $conn->prepare("INSERT INTO account_admin (admin_id, first_name, last_name, date_created) VALUES (?, ?, ?, NOW())");
$stmt->bind_param("iss", $user_id, $first_name, $last_name);

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Failed to add user' . $conn->error]);
    exit;
}

$stmt->close();

$stmt = $conn->prepare("INSERT INTO profile_image (user_id) VALUES (?)");
$stmt->bind_param("i", $user_id);

if($stmt->execute()) {
    $stmt->close();
    
    require_once BASE_PATH . '/assets/sql/public_key.php';

    $count = 1;

    do {
        $public_key = generatePublicKey();
    
        $stmtKey = $conn->prepare("SELECT COUNT(*) FROM key_user WHERE public_key = ?");
        $stmtKey->bind_param("s", $public_key);
        $stmtKey->execute();
        $stmtKey->bind_result($count);
        $stmtKey->fetch();
        $stmtKey->close();
    } while ($count > 0);

    $stmt = $conn->prepare("INSERT INTO key_user (user_id, public_key) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $public_key);

    if($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'data' => [
                'public_key' => $public_key,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'role' => $role,
            ]
        ]);
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add user' . $conn->error]);
        $stmt->close();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to add user' . $conn->error]);
    $stmt->close();
}
