<?php
header("Content-Type: application/json");

require_once '../../../../sql/base_path.php';
require_once BASE_PATH . '/assets/sql/conn.php';
require_once BASE_PATH . '/assets/sql/google_calendar/service_account.php';

use Google\Service\Calendar;

$data = json_decode(file_get_contents("php://input"), true);

if(!isset($data["publicKey"])) {
    echo json_encode([
        "success" => false,
        "message" => "Public key is required"
    ]);
    exit;
}

$public_key = $data["publicKey"];
$conn->begin_transaction();

try {
    // 1. Get user info (user_id, google_calendar_id)
    $stmt = $conn->prepare("SELECT user_id, role FROM account WHERE public_key = ?");
    $stmt->bind_param("s", $public_key);
    if (!$stmt->execute()) {
        throw new Exception("Failed to fetch user");
    }
    $stmt->bind_result($user_id, $role);
    if(!$stmt->fetch()) {
        throw new Exception("User not found");
    }
    $stmt->close();

    if ($role === "Organization") {
        $stmt = $conn->prepare("SELECT google_calendar_id FROM account_organization WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        if (!$stmt->execute()) {
            throw new Exception("Failed to fetch organization calendar");
        }
        $stmt->bind_result($google_calendar_id);
        if(!$stmt->fetch()) {
            throw new Exception("Organization calendar not found");
        }
        $stmt->close();
    
        if(!empty($google_calendar_id)) {
            $client = googleClient();
            $service = new Calendar($client);

            try {
                $service->calendars->delete($google_calendar_id);
            } catch(Exception $e) {
                throw new Exception("Failed to delete Google Calendar");
            }
        }
    }
    // 3. Delete user record from DB
    $stmt = $conn->prepare("DELETE FROM account WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    if($stmt->affected_rows === 0) {
        throw new Exception("Failed to delete user");
    }

    $conn->commit();

    echo json_encode(["success" => true]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(["success" => false, "message" => 'Operation failed. Please try again.']);
}

exit;
