<?php
require_once BASE_PATH . '/assets/sql/conn.php';

$stmt = $conn->prepare("SELECT public_key, name AS organization
                        FROM account_organization ao
                        INNER JOIN key_user ku
                            ON ku.user_id = ao.organization_id
                        ORDER BY ao.name ASC");

$stmt->execute();
$result_organizations = $stmt->get_result();

$stmt->close();
