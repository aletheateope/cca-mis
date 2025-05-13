<?php
header("Content-Type: application/json");

require_once '../../../../sql/base_path.php';

require_once BASE_PATH . '/assets/sql/conn.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method',
    ]);
    exit;
}

session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'User not logged in',
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];

$data = json_decode(file_get_contents('php://input'), true);

$publicKey = $data['publicKey'];

$stmt = $conn->prepare("SELECT ks.statement_id   
                        FROM key_statement ks 
                        INNER JOIN financial_statement_report frs
                            ON frs.statement_id = ks.statement_id
                        WHERE public_key = ? AND organization_id = ?");
$stmt->bind_param("si", $publicKey, $user_id);

if (!$stmt->execute()) {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to execute statement.',
    ]);
    exit;
}

$stmt->bind_result($statement_id);
$stmt->fetch();

if (!$statement_id) {
    echo json_encode([
        'success' => false,
        'message' => 'Record not found',
    ]);
    exit;
}

$stmt->close();

$stmt = $conn->prepare("SELECT month, year, date_updated, starting_fund, weekly_contribution, internal_projects, external_projects,
                        initiative_funding, donations_sponsorships, adviser_credit, carri_credit, total_credit, total_expenses, final_funding
                        FROM financial_statement
                        WHERE statement_id = ?");
$stmt->bind_param("i", $statement_id);

if (!$stmt->execute()) {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to read record.',
    ]);
    exit;
}

$result = $stmt->get_result();

$record = $result->fetch_assoc();

if (!$record) {
    echo json_encode([
        'success' => false,
        'message' => 'Record not found',
    ]);
    exit;
}

$stmt->close();

$stmt = $conn->prepare("SELECT file_name, path FROM financial_statement_receipt frs WHERE statement_id = ?");
$stmt->bind_param("i", $statement_id);

if (!$stmt->execute()) {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to read receipts.',
    ]);
    exit;
}

$result = $stmt->get_result();

$receipt = $result->fetch_all(MYSQLI_ASSOC);

if (empty($receipt)) {
    $receipt = null;
}

$conn->close();

echo json_encode([
    'success' => true,
    'result' => $record,
    'receipt' => $receipt,
]);

exit;
