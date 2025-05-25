<?php
header("Content-Type: application/json");

require_once '../../../../sql/base_path.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method"
    ]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["organization"]) || !isset($data["month"])) {
    echo json_encode([
        "success" => false,
        "message" => "Missing required fields"
    ]);
    exit;
}

$organization = $data["organization"];
$month = (int) $data["month"];

if ($month < 1 || $month > 12) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid month value"
    ]);
    exit;
}

$month_name = [
    1 => "January",
    2 => "February",
    3 => "March",
    4 => "April",
    5 => "May",
    6 => "June",
    7 => "July",
    8 => "August",
    9 => "September",
    10 => "October",
    11 => "November",
    12 => "December"
];

require_once BASE_PATH . "/assets/sql/conn.php";

$stmt = $conn->prepare("SELECT ac.name
                        FROM account_organization ac
                        INNER JOIN key_user ku
                            ON ku.user_id = ac.organization_id
                        WHERE ku.public_key = ?");
$stmt->bind_param("s", $organization);

if (!$stmt->execute()) {
    echo json_encode([
        "success" => false,
        "message" => "Failed to execute statement"
    ]);
    exit;
}

$stmt->bind_result($organization_name);
$stmt->fetch();

$stmt->close();

echo json_encode([
    "success" => true,
    "organization" => $organization_name,
    "month" => $month_name[$month]
]);
