<?php
require_once BASE_PATH . '/assets/sql/conn.php';

$stmt = $conn->prepare("SELECT pi.path AS profile, ao.name, COUNT(so.student_number) AS member_count
                        FROM account_organization ao
                        LEFT JOIN student_organization so
                            ON ao.organization_id = so.organization
                        INNER JOIN profile_image pi
                            ON ao.organization_id = pi.user_id
                        GROUP BY ao.name
                        ORDER BY ao.name ASC");

$stmt->execute();
$result_active_members = $stmt->get_result();

$stmt->close();
