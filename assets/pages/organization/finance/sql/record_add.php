<?php
header("Content-Type: application/json");

require_once '../../../../sql/base_path.php';

if($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit;
}

require_once BASE_PATH . '/assets/sql/conn.php';

require_once BASE_PATH . '/assets/sql/public_key.php';

session_start();

$user_id = $_SESSION['user_id'] ?? null;
$month = $_SESSION['month'] ?? null;
$startYear = $_POST['startYear'] ?? null;
$endYear = $_POST['endYear'] ?? null;

if (!$user_id || !$month || !$startYear || !$endYear) {
    echo json_encode(["success" => false, "message" => "Missing required fields."]);
    exit;
}

$academic_year = $startYear . '-' . $endYear;

$stmt = $conn->prepare("SELECT 1 
                        FROM financial_statement fs
                        INNER JOIN financial_statement_report frs
                        ON frs.statement_id = fs.statement_id
                        WHERE academic_year = ? AND organization_id = ? AND month = 12 LIMIT 1");
$stmt->bind_param("si", $academic_year, $user_id);

if(!$stmt->execute()) {
    echo json_encode(["success" => false, "message" => "Query Failed"]);
    exit;
}

$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row) {
    $year = $endYear;
} else {
    $year = $startYear;
}

$stmt->close();

$stmt = $conn->prepare("INSERT INTO financial_statement_report (organization_id, academic_year) VALUES (?, ?)");
$stmt->bind_param("is", $user_id, $academic_year);

if(!$stmt->execute()) {
    echo json_encode(["success" => false, "message" => "Query Failed"]);
    exit;
}

$_SESSION['statement_report_id'] = $stmt->insert_id;

$report_id = $_SESSION['statement_report_id'];

$stmt->close();

$count = 1;

do {
    $report_public_key = generatePublicKey();
    
    $stmtKey = $conn->prepare("SELECT COUNT(*) FROM key_statement_report WHERE public_key = ?");
    $stmtKey->bind_param("s", $report_public_key);
    $stmtKey->execute();
    $stmtKey->bind_result($count);
    $stmtKey->fetch();
    $stmtKey->close();
} while ($count > 0);

$stmt = $conn->prepare("INSERT INTO key_statement_report (report_id, public_key) VALUES (?, ?)");
$stmt->bind_param("is", $report_id, $report_public_key);

if(!$stmt->execute()) {
    echo json_encode(["success" => false, "message" => "Query Failed"]);
    exit;
}

$stmt->close();

$stmt = $conn->prepare("SELECT 1 
                        FROM financial_statement fs
                        INNER JOIN financial_statement_report frs
                        ON frs.statement_id = fs.statement_id
                        WHERE frs.organization_id = ? AND fs.month = ? AND fs.year = ? LIMIT 1");
$stmt->bind_param("iii", $user_id, $month, $year);

if(!$stmt->execute()) {
    echo json_encode(["success" => false, "message" => "Query Failed"]);
    exit;
}

$stmt->store_result();

if($stmt->num_rows() > 0) {
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM financial_statement_report WHERE report_id = ?");
    $stmt->bind_param("i", $report_id);
    $stmt->execute();
    $stmt->close();
    
    echo json_encode(["success" => false, "message" => "Record already exists."]);
    exit;
}

$stmt->close();

$stmt = $conn->prepare("INSERT INTO financial_statement (month, year) VALUES (?, ?)");
$stmt->bind_param("ii", $month, $year);

if(!$stmt->execute()) {
    echo json_encode(["success" => false, "message" => "Query Failed"]);
    exit;
}

$statement_id = $stmt->insert_id;
unset($_SESSION['month']);

$stmt -> close();

$stmt = $conn->prepare("UPDATE financial_statement_report SET statement_id = ? WHERE report_id = ?");
$stmt->bind_param("ii", $statement_id, $report_id);

if(!$stmt->execute()) {
    echo json_encode(["success" => false, "message" => "Query Failed"]);
    exit;
}

$stmt->close();

$count = 1;

do {
    $statement_public_key = generatePublicKey();
    
    $stmtKey = $conn->prepare("SELECT COUNT(*) FROM key_statement WHERE public_key = ?");
    $stmtKey->bind_param("s", $statement_public_key);
    $stmtKey->execute();
    $stmtKey->bind_result($count);
    $stmtKey->fetch();
    $stmtKey->close();
} while ($count > 0);

$stmt = $conn->prepare("INSERT INTO key_statement (statement_id, public_key) VALUES (?, ?)");
$stmt->bind_param("is", $statement_id, $statement_public_key);

if (!$stmt->execute()) {
    echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
    exit;
}

echo json_encode(["success" => true, "ref" => $report_public_key]);

$stmt->close();
$conn->close();
