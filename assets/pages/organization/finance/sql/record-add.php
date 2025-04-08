<?php
header("Content-Type: application/json");

require_once '../../../../sql/base-path.php';


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once BASE_PATH . '/assets/sql/conn.php';
    
    session_start();

    $user_id = $_SESSION['user_id'] ?? null;
    $month = $_SESSION['month'] ?? null;
    $startYear = $_POST['startYear'] ?? null;
    $endYear = $_POST['endYear'] ?? null;

    if (!$user_id || !$month || !$startYear || !$endYear) {
        echo json_encode(["success" => false, "error" => "Missing required fields."]);
        exit;
    }

    $academic_year = $startYear . '-' . $endYear;
    
    $sqlCheck = "SELECT 1 
                    FROM financial_statement fs
                    INNER JOIN financial_statement_report frs
                    ON frs.statement_id = fs.statement_id
                    WHERE academic_year = ? AND organization_id = ? AND month = 12 LIMIT 1";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("si", $academic_year, $user_id);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
    $row = $resultCheck->fetch_assoc();

    if ($row) {
        $year = $endYear;
    } else {
        $year = $startYear;
    }


    $sql = "INSERT INTO financial_statement_report (organization_id, academic_year) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user_id, $academic_year);

    if ($stmt->execute()) {
        $_SESSION['statement_report_id'] = $stmt->insert_id;
        $stmt->close();

        require_once BASE_PATH . '/assets/sql/public-key.php';

        $count = 1;

        do {
            $public_key = generatePublicKey();
    
            $stmtKey = $conn->prepare("SELECT COUNT(*) FROM key_statement_report WHERE public_key = ?");
            $stmtKey->bind_param("s", $public_key);
            $stmtKey->execute();
            $stmtKey->bind_result($count);
            $stmtKey->fetch();
            $stmtKey->close();
        } while ($count > 0);


        $stmt = $conn->prepare("INSERT INTO key_statement_report (report_id, public_key) VALUES (?, ?)");
        $stmt->bind_param("is", $_SESSION['statement_report_id'], $public_key);
        $stmt->execute();

        $stmt->close();

        $sql2 = "INSERT INTO financial_statement (month, year) VALUES (?, ?)";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("ii", $month, $year);
        if ($stmt2->execute()) {
            $statement_id = $stmt2->insert_id;
            unset($_SESSION['month']);

            $sql3 = "UPDATE financial_statement_report SET statement_id = ? WHERE report_id = ?";
            $stmt3 = $conn->prepare($sql3);
            $stmt3->bind_param("ii", $statement_id, $_SESSION['statement_report_id']);
            if ($stmt3->execute()) {
                echo json_encode(["success" => true , "ref" => $public_key]);
            } else {
                echo json_encode(["success" => false, "error" => "Update failed: " . $stmt3->error]);
            }
            $stmt3->close();
        } else {
            echo json_encode(["success" => false, "error" => "Insert into financial_statement failed: " . $stmt2->error]);
        }
        $stmt2->close();
    } else {
        echo json_encode(["success" => false, "error" => "Insert into financial_statement_report failed: " . $stmt->error]);
        $stmt->close();
    }
    $conn->close();
}
