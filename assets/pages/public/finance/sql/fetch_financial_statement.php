<?php
header("Content-Type: application/json");

require_once '../../../../sql/base_path.php';

require_once BASE_PATH . '/assets/sql/conn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['year'], $input['month'], $input['organization'])) {
    echo json_encode(['success' => false, 'message' => 'Missing or invalid parameters']);
    exit;
}

$year = $input['year'];
$month = $input['month'];
$public_key = $input['organization'];

$stmt = $conn->prepare("SELECT academic_year, date_updated, starting_fund, weekly_contribution, internal_projects, external_projects, initiative_funding,
                                donations_sponsorships, adviser_credit, carri_credit, total_credit, total_expenses, final_funding
                        FROM financial_statement fs
                        INNER JOIN financial_statement_report fsr
                            ON fsr.statement_id = fs.statement_id
                        INNER JOIN key_user ku
                            ON ku.user_id = fsr.organization_id
                        WHERE public_key = ? AND month = ? AND year = ?");

$stmt->bind_param("sii", $public_key, $month, $year);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'No data found']);
    exit;
}

$data = $result->fetch_assoc();

echo json_encode(['success' => true, 'data' => $data]);
