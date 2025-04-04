<?php
header("Content-Type: application/json");

require_once '../../../../sql/base-path.php';

require_once BASE_PATH . '/assets/sql/calendar/service-account.php';

require_once BASE_PATH . '/assets/sql/calendar/calendar-id.php';

require_once BASE_PATH . '/assets/sql/conn.php';

use Google\Service\Calendar;

$client = googleClient();
$service = new Calendar($client);

if ($_SERVER["REQUEST_METHOD"] !== "DELETE") {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);

if (!isset($input["eventId"]) || empty($input["eventId"])) {
    echo json_encode(["success" => false, "message" => "Event ID is required."]);
    exit;
}

$publicKey = strval($input["eventId"]);


$stmt = $conn->prepare("SELECT event_id FROM key_event WHERE public_key = ?");
$stmt->bind_param("s", $publicKey);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

$eventID = $result["event_id"];

$stmt = $conn->prepare("SELECT google_event_id, user_id FROM event_calendar WHERE event_id = ?");
$stmt->bind_param("i", $eventID);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

$googleEventID = $result["google_event_id"];
$userID = $result["user_id"];

$calendar = $calendarID[$userID] ?? $directorCalendarID;

try {
    $service->events->delete($calendar, $googleEventID);

    $stmt = $conn->prepare("DELETE FROM event_calendar WHERE event_id = ?");
    $stmt->bind_param("i", $eventID);
    $stmt->execute();

    echo json_encode(["success" => true, "message" => "Event deleted successfully."]);
} catch(Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
