<?php
require_once '../../../../sql/base_path.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once BASE_PATH . '/assets/sql/conn.php';

    $user_id = $_SESSION['user_id'] ?? null;
    $month = $_POST['month'] ?? null;
    $year = $_POST['year'] ?? null;

    if (!$user_id || !$month || !$year) {
        echo json_encode(["success" => false, "error" => "Missing required fields."]);
        exit;
    }

    // Prepare and execute query
    $sql = "INSERT INTO accomplishment_report (organization_id, month, year) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(["success" => false, "error" => "SQL prepare failed: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("iii", $user_id, $month, $year);

    if ($stmt->execute()) {
        $report_id = $stmt->insert_id;

        require_once BASE_PATH . '/assets/sql/public_key.php';

        $count = 1;

        do {
            $public_key = generatePublicKey();
    
            $key = "SELECT COUNT(*) FROM key_accomplishment_report WHERE public_key = ?";
            $stmtKey = $conn->prepare($key);
            $stmtKey->bind_param("s", $public_key);
            $stmtKey->execute();
            $stmtKey->bind_result($count);
            $stmtKey->fetch();
            $stmtKey->close();
        } while ($count > 0);


        $sql = $conn->prepare("INSERT INTO key_accomplishment_report (report_id, public_key) VALUES (?, ?)");
        $sql->bind_param("is", $report_id, $public_key);
        $sql->execute();

        $_SESSION["accomplishment_report_id"] = $report_id;

        echo json_encode(["success" => true, "ref" => $public_key]);
    } else {
        echo json_encode(["success" => false, "error" => "Insert failed: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
