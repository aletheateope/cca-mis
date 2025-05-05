<?php
require_once '../../../../sql/base_path.php';

header("Content-Type: application/json");

$receiptDirectory = BASE_PATH . '/uploads/receipt/';

if (!is_dir($receiptDirectory)) {
    mkdir($receiptDirectory, 0777, true);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once BASE_PATH . '/assets/sql/conn.php';

    session_start();

    $user_id = $_SESSION['user_id'] ?? null;
    $statement_report_id = $_SESSION['statement_report_id'] ?? null;

    if (!$user_id || !$statement_report_id) {
        echo json_encode(["success" => false, "message" => "User not authenticated or report not selected."]);
        exit;
    }

    $sql = "SELECT statement_id FROM financial_statement_report WHERE report_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $statement_report_id);

    if (!$stmt->execute()) {
        echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
        exit;
    }

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        echo json_encode(["success" => false, "message" => "No matching statement found."]);
        exit;
    }

    $stmt->close();

    $statement_id = $row['statement_id'];

    $fields = [
    'startingFund', 'weeklyContribution', 'internalProjects', 'externalProjects',
    'internalInitiativeFunding', 'donationsSponsorships', 'adviserCredit', 'carriCredit',
    'totalCredit', 'totalExpenses'
    ];

    foreach ($fields as $field) {
        $$field = isset($_POST[$field]) ? floatval(str_replace(',', '', $_POST[$field])) : 0.0;
    }

    $finalFunding = $totalCredit - $totalExpenses;

    $sql2 = "UPDATE financial_statement SET 
                    date_updated = now(),
                    starting_fund = ?,
                    weekly_contribution = ?,
                    internal_projects = ?,
                    external_projects = ?,
                    initiative_funding = ?,
                    donations_sponsorships = ?,
                    adviser_credit = ?,
                    carri_credit = ?,
                    total_credit = ?,
                    total_expenses = ?,
                    final_funding = ?
                    WHERE statement_id = ?";
    $stmt = $conn->prepare($sql2);
    $stmt->bind_param(
        "dddddddddddi",
        $startingFund,
        $weeklyContribution,
        $internalProjects,
        $externalProjects,
        $internalInitiativeFunding,
        $donationsSponsorships,
        $adviserCredit,
        $carriCredit,
        $totalCredit,
        $totalExpenses,
        $finalFunding,
        $statement_id,
    );

    if (!$stmt->execute()) {
        echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
        exit;
    }
    $stmt->close();

    if (!empty($_FILES['receipt']['name'][0])) {
        foreach ($_FILES['receipt']['tmp_name'] as $key => $tmpName) {
            if ($_FILES['receipt']['error'][$key] === UPLOAD_ERR_OK) {
                $fileName = $_FILES['receipt']['name'][$key];
                $fileType = mime_content_type($tmpName);
            
                // Generate unique filename
                $uniqueFileName = time() . "_" . uniqid();
                $destination = $receiptDirectory . $uniqueFileName;
                $sqlFilePath = '/cca/uploads/receipt/' . $uniqueFileName;
            
                // Handle image files
                if (in_array($fileType, ['image/png', 'image/jpeg'])) {
                    // Convert image to JPG
                    $image = ($fileType === 'image/png') ? imagecreatefrompng($tmpName) : imagecreatefromjpeg($tmpName);
                    $destination .= ".jpg";
                    $sqlFilePath .= ".jpg";
                    $convertedFileName = pathinfo($fileName, PATHINFO_FILENAME) . ".jpg";
                    imagejpeg($image, $destination, 90); // Save as JPG with 90% quality
                    imagedestroy($image);
                } else {
                    // Keep original file extension for non-images
                    $destination .= "." . pathinfo($fileName, PATHINFO_EXTENSION);
                    $sqlFilePath .= "." . pathinfo($fileName, PATHINFO_EXTENSION);
                    $convertedFileName = $fileName;

                    move_uploaded_file($tmpName, $destination);
                }
            
                // INSERT INTO DATABASE
                $stmt = $conn->prepare("INSERT INTO financial_statement_receipt (statement_id, file_name, path, date_uploaded) VALUES (?, ?, ?, NOW())");
                $stmt->bind_param("iss", $statement_id, $convertedFileName, $sqlFilePath);
                if (!$stmt->execute()) {
                    echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
                    exit;
                }
                $stmt->close();
            }
        }
    }
    
    unset($_SESSION['statement_report_id']);
    echo json_encode(["success" => true, "message" => "Record successfully Submitted."]);

    $conn->close();

} else {
    echo json_encode(["success" => false, "message" => "No matching statement found."]);
}
