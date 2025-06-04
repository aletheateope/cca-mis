<?php require_once '../../../sql/base_path.php'?>

<?php
require_once BASE_PATH . '/assets/sql/session_check.php';
check_role('Organization');
?>

<?php
// include BASE_PATH . '/assets/sql/temporary_session.php'
?>

<?php require_once 'sql/display_record.php'?>

<?php require_once 'sql/latest_statement.php'?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Records</title>

    <?php require_once BASE_PATH . '/assets/components/header_links.php'?>

    <!-- FANCYBOX -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />

    <link rel="stylesheet" href="my-records-page.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <?php include_once BASE_PATH . '/assets/components/sidebar/organization/org_sidebar.php' ?>
            </div>
            <div class="col main-content">
                <div class="row page-header">
                    <div class="col">
                        <h1>My Records</h1>
                        <?php include_once BASE_PATH . '/assets/components/topbar/topbar.php'?>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#addRecordModal">
                            <i class="bi bi-plus add-button"></i>
                            Add Record
                        </button>
                    </div>
                </div>
                <div class="row page-body">
                    <div class="col">
                        <!-- FINANCIAL SUMMARY -->
                        <div class="row finance-overview">
                            <div class="col container">
                                <h3><?php echo $month . ", " . $year?>
                                </h3>
                                <table class="table table-inflow">
                                    <tbody>
                                        <tr>
                                            <td>Starting Fund</td>
                                            <td><?php echo number_format($starting_fund, 2)?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Credit From Weekly Contribution</td>
                                            <td><?php echo number_format($weekly_contribution, 2)?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Revenue From Internal Projects</td>
                                            <td><?php echo number_format($internal_projects, 2)?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Revenue From External Projects</td>
                                            <td><?php echo number_format($external_projects, 2)?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Credit for Internal Initiative Funding</td>
                                            <td><?php echo number_format($initiative_funding, 2)?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Credit From Donations/Sponsorships</td>
                                            <td><?php echo number_format($donations_sponsorships, 2)?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Credit From Adviser</td>
                                            <td><?php echo number_format($adviser_credit, 2)?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Credit From Carri</td>
                                            <td><?php echo number_format($carri_credit, 2)?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Total Credit</td>
                                            <td><?php echo number_format($total_credit, 2)?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col">
                                <div class="row container">
                                    <div class="col">
                                        <h3>Latest Recorded Financial Status</h3>
                                        <h4>
                                            As of
                                            <?php echo $month . ", " . $year?>
                                        </h4>
                                        <table class="table table-balance-summary">
                                            <tbody>
                                                <tr>
                                                    <td>Total Credit</td>
                                                    <td><?php echo number_format($total_credit, 2)?>
                                                    </td>
                                                    <?php displayPercentageRow($total_credit_percentage)?>
                                                </tr>
                                                <tr>
                                                    <td>Total Expenses</td>
                                                    <td><?php echo number_format($total_expenses, 2)?>
                                                    </td>
                                                    <?php displayPercentageRow($total_expenses_percentage, true)?>
                                                </tr>
                                                <tr>
                                                    <td>Final Balance</td>
                                                    <td><?php echo number_format($final_funding, 2)?>
                                                    </td>
                                                    <?php displayPercentageRow($final_funding_percentage)?>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <?php echo $compare_heading?>
                                    </div>
                                </div>
                                <div class="row container">
                                    <div class="col">
                                        <h3>Financial Flow</h3>
                                        <div class="row chart-container">
                                            <div class="col">
                                                <canvas class="horizontal-waterfall-chart"
                                                    id="horizontalWaterfall"></canvas>
                                            </div>
                                        </div>
                                        <p id="fundsLeft">46.51% of Funds Left</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- FINANCIAL RECORDS -->
                        <?php foreach ($records as $year => $months) { ?>
                        <div class="row container financial-records">
                            <div class="col">
                                <div class="row header">
                                    <div class="col">
                                        <h3><?php echo $year?></h3>
                                        <button class="btn btn-primary generatePDF"
                                            data-year="<?php echo $year?>">Generate
                                            Report</button>
                                    </div>
                                </div>

                                <ul class="list-group">
                                    <?php foreach ($months as $month) { ?>
                                    <li class="list-group-item" data-bs-toggle="modal" data-bs-target="#viewRecordModal"
                                        data-id="<?php echo $month['public_key']?>">
                                        <h4><?php echo $month['name'] . ", " . $month['year'] ?>
                                        </h4>
                                        <button class="no-style-btn generateIMG" data-bs-toggle="modal"
                                            data-bs-target="#statementSummaryModal"
                                            data-month="<?php echo $month['id']?>"
                                            data-year="<?php echo $month['year']?>">
                                            <i class="bi bi-image"></i>
                                        </button>
                                    </li>
                                    <?php }?>
                                </ul>
                            </div>
                        </div>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL -->
    <!-- Topbar -->
    <?php include_once BASE_PATH . '/assets/components/topbar/topbar_modal.php'?>

    <!-- View Profile Modal -->
    <?php include_once BASE_PATH . '/assets/components/sidebar/org_modal.php'; ?>

    <!-- Add Record Modal -->
    <form id="addRecordForm" enctype="multipart/form-data">
        <div class="modal fade add-record-modal" id="addRecordModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Financial Record</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body center-modal-body" id="addRecordModalBody">
                        <label for="inputStartYear" class="form-label">Academic Year</label>
                        <div class="container-fluid">
                            <div class="row academic-year-row">
                                <div class="col">
                                    <input type="text" name="startYear" class="form-control" id="inputStartYear">
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-dash-lg"></i>
                                </div>
                                <div class="col">
                                    <input type="text" name="endYear" class="form-control" id="inputEndYear">
                                </div>
                            </div>
                        </div>
                        <label for="month" class="form-label">For the Month of</label>
                        <select class="form-select" id="month">
                            <option value="1" selected>January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- VIEW RECORD -->
    <div class="modal fade view-record-modal" id="viewRecordModal" tabindex="-1" aria-labelledby="viewRecordModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Financial Record</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <section class="container-fluid section">
                            <div class="row">
                                <div class="col">
                                    <h3>Statement</h3>
                                </div>
                            </div>
                            <div class="container-fluid">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td colspan="2" class="text-center" id="recordMonthYear">Month, Year</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="text-center">Update As Of: <span
                                                    id="recordDate">Month Day, Year</span></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"></td>
                                        </tr>
                                        <tr>
                                            <th colspan="2" class="text-center">Credit and Revenues</th>
                                        </tr>
                                        <tr>
                                            <td class="text-end">Starting Fund</td>
                                            <td id="recordStartingFund">0.00</td>
                                        </tr>
                                        <tr>
                                            <td>Credit From Weekly Contribution</td>
                                            <td id="recordWeeklyContribution">0.00</td>
                                        </tr>
                                        <tr>
                                            <td>Revenue From Internal Projects</td>
                                            <td id="recordInternalProjects">0.00</td>
                                        </tr>
                                        <tr>
                                            <td>Revenue From External Projects</td>
                                            <td id="recordExternalProjects">0.00</td>
                                        </tr>
                                        <tr>
                                            <td>Credit for Internal Initiative Funding</td>
                                            <td id="recordInternalInitiativeFunding">0.00</td>
                                        </tr>
                                        <tr>
                                            <td>Credit From Donations/Sponsorships</td>
                                            <td id="recordDonationsSponsorships">0.00</td>
                                        </tr>
                                        <tr>
                                            <td>Credit From Adviser</td>
                                            <td id="recordAdviserCredit">0.00</td>
                                        </tr>
                                        <tr>
                                            <td>Credit From Carri</td>
                                            <td id="recordCarriCredit">0.00</td>
                                        </tr>
                                        <tr>
                                            <td class="text-end">Total Credit</td>
                                            <td class="recordTotalCredit">0.00</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"></td>
                                        </tr>
                                        <tr>
                                            <th colspan="2" class="text-center">Cost and Expenses</th>
                                        </tr>
                                        <tr>
                                            <td>Cost and Expenses</td>
                                            <td class="recordTotalExpenses">0.00</td>
                                        </tr>
                                        <tr>
                                            <td class="text-end">Total Expenses</td>
                                            <td class="recordTotalExpenses">0.00</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"></td>
                                        </tr>
                                        <tr>
                                            <th colspan="2" class="text-center">Summary</th>
                                        </tr>
                                        <tr>
                                            <td class="text-end">Total Credit</td>
                                            <td class="recordTotalCredit">0.00</td>
                                        </tr>
                                        <tr>
                                            <td class="text-end">Total Expenses</td>
                                            <td class="recordTotalExpenses">0.00</td>
                                        </tr>
                                        <tr>
                                            <td class="text-end">Final Funding Less All Expenses</td>
                                            <td id="recordFinalFunding">0.00</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </section>
                        <section class="container-fluid section">
                            <div class="row">
                                <div class="col">
                                    <h3>Receipts</h3>
                                </div>
                            </div>
                            <div class="container-fluid gallery-container receipts-gallery">
                            </div>
                        </section>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- HTML2CANVAS -->
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

    <!-- FOOTER LINKS -->
    <?php require_once BASE_PATH . '/assets/components/footer_links.php' ?>

    <!-- CHART.JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>

    <!-- JSPDF -->
    <script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.4/jspdf.plugin.autotable.min.js"></script>

    <!-- FANCYBOX -->
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>

    <!-- HTML2CANVAS -->
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

    <script type="module" src="my-records-page.js"></script>

    <script>
        var sessionID =
            "<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'undefined'; ?>";

        var sessionRole =
            "<?php echo isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'undefined'; ?>";

        console.log("User ID: " + sessionID);
        console.log("User Role: " + sessionRole);
    </script>
</body>

</html>