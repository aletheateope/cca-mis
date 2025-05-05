<?php
require_once BASE_PATH . '/assets/sql/conn.php';

$stmt = $conn->prepare("SELECT DISTINCT academic_year FROM financial_statement_report ORDER BY academic_year DESC");
$stmt->execute();
$result_academic_year = $stmt->get_result();


$initial_year = null;
if ($result_academic_year->num_rows > 0) {
    $result_academic_year->data_seek(0); // Move internal pointer to first row
    $first_row = $result_academic_year->fetch_assoc();
    $initial_year = $first_row['academic_year'];
}

$result_academic_year->data_seek(0);

$result_org = null;
if ($initial_year) {
    $stmt_org = $conn->prepare("SELECT DISTINCT public_key, ao.name
                                FROM account_organization ao
                                INNER JOIN financial_statement_report frs
                                    ON frs.organization_id = ao.organization_id
                                INNER JOIN key_user ku
                                    ON ku.user_id = frs.organization_id
                                WHERE academic_year = ?");
    $stmt_org->bind_param("s", $initial_year);
    $stmt_org->execute();
    $result_org = $stmt_org->get_result();
}
