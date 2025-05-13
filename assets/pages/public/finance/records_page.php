<?php require_once '../../../sql/base_path.php'?>

<?php
session_start();
?>

<?php
// include_once BASE_PATH . '/assets/sql/temporary_session.php'
?>

<?php require_once 'sql/display_records.php'?>

<?php require_once 'sql/display_available_report.php'?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Records</title>

    <?php require_once BASE_PATH . '/assets/components/header_links.php' ?>

    <link rel="stylesheet" href="records-page.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <?php if ($_SESSION['user_role'] === 'Organization') {
                    include_once BASE_PATH . '/assets/components/sidebar/organization/sidebar.php';
                } elseif ($_SESSION['user_role'] === 'Director') {
                    include_once BASE_PATH . '/assets/components/sidebar/director/sidebar.php';
                } elseif ($_SESSION['user_role'] === 'VPSLD') {
                    include_once BASE_PATH . '/assets/components/sidebar/vpsld/sidebar.php';
                }?>
            </div>
            <main class="col main-content">
                <div class="row page-header">
                    <div class="col">
                        <h1>Records</h1>
                        <?php include_once BASE_PATH . '/assets/components/topbar/topbar.php'?>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generateReportModal">
                            Generate Report
                        </button>
                    </div>
                </div>
                <div class="row page-body">
                    <div class="col">
                        <?php foreach ($records as $academic_year => $months) {?>
                        <section class="row container">
                            <div class="col">
                                <div class="row header">
                                    <div class="col-12">
                                        <h2><?php echo $academic_year?>
                                        </h2>
                                    </div>
                                </div>
                                <div class="row body">
                                    <div class="col-12">
                                        <?php foreach ($months as $month => $organizations) {?>
                                        <?php $accordionId = "accordion-" . $academic_year . "-" . $month;?>
                                        <?php $collapseId = "collapse-" . $academic_year . "-" . $month;?>
                                        <?php $month_id = $organizations[0]['month_id'];?>
                                        <?php $year = $organizations[0]['year'];?>
                                        <div class="accordion accordion-finance"
                                            id="<?php echo $accordionId?>"
                                            data-month="<?php echo $month_id ?>"
                                            data-year="<?php echo $year ?>">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#<?php echo $collapseId?>"
                                                        aria-expanded="false"
                                                        aria-controls="<?php echo $collapseId?>">
                                                        <span><?php echo $month . ", " . $organizations[0]['year'];?></span>
                                                        <span>
                                                            <?php echo count($organizations)?>
                                                            Organization<?php echo count($organizations) > 1 ? 's' : '';?>
                                                        </span>
                                                    </button>
                                                </h2>
                                                <div id="<?php echo $collapseId?>"
                                                    class="accordion-collapse collapse"
                                                    data-bs-parent="#<?php echo $accordionId?>">
                                                    <div class="accordion-body">
                                                        <ul class="list-group">
                                                            <?php foreach ($organizations as $organization) {?>
                                                            <li class="list-group-item"
                                                                data-id="<?php echo $organization['public_key']?>">
                                                                <?php echo $organization['organization']?>
                                                                <button class="no-style-btn fetchRecordSum"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#statementSummaryModal">
                                                                    <i class="bi bi-image"></i>
                                                                </button>
                                                            </li>
                                                            <?php }?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php }?>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <?php }?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- MODAL -->
    <!-- Topbar -->
    <?php include_once BASE_PATH . '/assets/components/topbar/topbar_modal.php'?>

    <!-- GENERATE REPORT MODAL -->
    <div class="modal fade generate-report-modal" id="generateReportModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Financial Statement Report</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col">
                                <label for="selectAcademicYear" class="form-label">Academic Year</label>
                                <select class="form-select" id="selectAcademicYear">
                                    <?php $first = true?>
                                    <?php while ($row_academic_year = $result_academic_year->fetch_assoc()) { ?>
                                    <option <?php if ($first) {
                                        echo 'selected';
                                        $first = false;
                                    }?>
                                        value="<?php echo $row_academic_year['academic_year']?>">
                                        <?php echo $row_academic_year['academic_year']?>
                                    </option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <h3>Organization</h3>
                                <ul class="list-group organization-list">
                                    <?php $index = 1?>
                                    <?php if  ($result_org && $result_org->num_rows > 0) {?>
                                    <?php while ($row_org = $result_org->fetch_assoc()) { ?>
                                    <?php $org_id = "organization-" . $index?>
                                    <li class="list-group-item">
                                        <div class="form-check">
                                            <label class="form-check-label"
                                                for="<?php echo $org_id?>">
                                                <?php echo htmlspecialchars($row_org['name'])?>
                                            </label>
                                            <input class="form-check-input organization-checkbox" type="checkbox"
                                                value="<?php echo $row_org['public_key']?>"
                                                id="<?php echo $org_id?>">
                                        </div>
                                    </li>
                                    <?php $index++?>
                                    <?php }?>
                                    <?php } else {?>
                                    <li class="list-group-item">No organizations found for this year.</li>
                                    <?php }?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="generatePDF">Generate</button>
                </div>
            </div>
        </div>
    </div>

    <!-- STATEMENT SUMMARY MODAL -->
    <div class="modal fade statement-summary-modal" id="statementSummaryModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Financial Statement</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="containter-fluid">
                        <div class="row financial-statement" id="capture">
                            <div class="col">
                                <div class="row header">
                                    <div class="col">
                                        <div class="row">
                                            <div class="col">
                                                <h4>Report Updated As Of: <span id="date">MM/DD/YYYY</span></h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <h4>FINANCIAL STATEMENT <span class="academicYear">YYYY-YYYY</span></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td class="text-end">AY <span class="academicYear">YYYY-YYYY</span> Starting
                                                Fund</td>
                                            <td class="text-center"><span id="startingFund">0.00</span></td>
                                        </tr>
                                        <tr>
                                            <td>CREDIT FROM WEEKLY CONTRIBUTION</td>
                                            <td class="text-center"><span id="weeklyContribution">0.00</span></td>
                                        </tr>
                                        <tr>
                                            <td>REVENUE FROM INTERNAL PROJECTS</td>
                                            <td class="text-center"><span id="internalProjects">0.00</span></td>
                                        </tr>
                                        <tr>
                                            <td>REVENUE FROM EXTERNAL PROJECTS</td>
                                            <td class="text-center"><span id="externalProjects">0.00</span></td>
                                        </tr>
                                        <tr>
                                            <td>CREDIT FROM INTERNAL INTIATIVE FUNDING</td>
                                            <td class="text-center"><span id="internalInitiativeFunding">0.00</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>CREDIT FROM DONATIONS / SPONSORSHIPS</td>
                                            <td class="text-center"><span id="donationsSponsorships">0.00</span></td>
                                        </tr>
                                        <tr>
                                            <td>CREDIT FROM ADVISER</td>
                                            <td class="text-center"><span id="adviserCredit">0.00</span></td>
                                        </tr>
                                        <tr>
                                            <td>CREDIT FROM CARRI</td>
                                            <td class="text-center"><span id="carriCredit">0.00</span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-end">TOTAL CREDIT</td>
                                            <td class="text-center"><span class="totalCredit">0.00</span></td>
                                        </tr>
                                        <tr>
                                            <td>COST AND EXPENSES</td>
                                            <td class="text-center"><span class="totalExpenses">0.00</span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-end">TOTAL EXPENSES</td>
                                            <td class="text-center"><span class="totalExpenses">0.00</span></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td class="text-end">TOTAL CREDIT</td>
                                            <td class="text-center"><span class="totalCredit">0.00</span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-end">TOTAL EXPENSES</td>
                                            <td class="text-center"><span class="totalExpenses">0.00</span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-end">Final FUNDING Less All Expenses</td>
                                            <td class="text-center"><span id="finalFunding">0.00</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="download">Download</button>
                </div>
            </div>
        </div>
    </div>

    <?php require_once BASE_PATH . '/assets/components/footer_links.php'; ?>

    <!-- JSPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/3.0.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.4/jspdf.plugin.autotable.min.js"></script>

    <!-- HTML2CANVAS -->
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

    <script type="module" src="records-page.js"></script>
</body>

</html>