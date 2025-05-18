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
$year = $data['year'];
$month = $data['month'];

$stmt = $conn->prepare("SELECT fs.statement_id
                        FROM financial_statement fs
                        INNER JOIN financial_statement_report fsr
                            ON fsr.statement_id = fs.statement_id
                        INNER JOIN key_user ku
                            ON ku.user_id = fsr.organization_id
                        WHERE ku.public_key = ? AND fs.year = ? AND fs.month = ?");
$stmt->bind_param("sii", $publicKey, $year, $month);

if (!$stmt->execute()) {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to read record.',
    ]);
}
$stmt->bind_result($statement_id);
$stmt->fetch();
$stmt->close();

$stmt = $conn->prepare("SELECT dm.month_name AS month, year, date_updated, starting_fund, weekly_contribution, internal_projects, external_projects,
                        initiative_funding, donations_sponsorships, adviser_credit, carri_credit, total_credit, total_expenses, final_funding
                        FROM financial_statement fs
                        INNER JOIN date_month dm
                            ON dm.month_id = fs.month
                        WHERE fs.statement_id = ?");
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
