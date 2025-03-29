<?php require_once '../../../sql/base-path.php'?>

<?php
//require_once '../../../sql/session_check.php';
//check_role('Organization');
?>

<?php include BASE_PATH . '/assets/sql/temporary_session.php'?>

<?php require_once 'sql/display-record.php'?>

<?php include 'sql/warning.php'?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance</title>

    <?php require_once BASE_PATH . '/assets/components/header-links.php'?>

    <link rel="stylesheet" href="finance-page.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <?php include BASE_PATH . '/assets/components/sidebar/organization/sidebar.php' ?>
            </div>
            <div class="col main-content">
                <div class="row page-header">
                    <div class="col">
                        <h1>Finance</h1>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#addRecordModal">
                            <span><i class="bi bi-plus add-button"></i> Add Record</span>
                        </button>
                    </div>
                </div>
                <div class="row page-body">
                    <div class="col">
                        <div class="row finance-overview">
                            <div class="col content">
                                <h3>March 2025</h3>
                                <table class="table table-inflow">
                                    <tbody>
                                        <tr>
                                            <td>Starting Fund</td>
                                            <td>Test</td>
                                        </tr>
                                        <tr>
                                            <td>Credit From Weekly Contribution</td>
                                            <td>Test</td>
                                        </tr>
                                        <tr>
                                            <td>Revenue From Internal Projects</td>
                                            <td>Test</td>
                                        </tr>
                                        <tr>
                                            <td>Revenue From External Projects</td>
                                            <td>Test</td>
                                        </tr>
                                        <tr>
                                            <td>Credit for Internal Initiative Funding</td>
                                            <td>Test</td>
                                        </tr>
                                        <tr>
                                            <td>Credit From Donations/Sponsorships</td>
                                            <td>Test</td>
                                        </tr>
                                        <tr>
                                            <td>Credit From Adviser</td>
                                            <td>Test</td>
                                        </tr>
                                        <tr>
                                            <td>Credit From Carri</td>
                                            <td>Test</td>
                                        </tr>
                                        <tr>
                                            <td>Total Credit</td>
                                            <td>Test</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col">
                                <div class="row content">
                                    <div class="col">
                                        <h3>Latest Recorded Financial Status</h3>
                                        <h4>As of March 2025</h4>
                                        <table class="table table-balance-summary">
                                            <thead>
                                                <tr>
                                                    <td>Total Credit</td>
                                                    <td>Test</td>
                                                </tr>
                                                <tr>
                                                    <td>Total Expenses</td>
                                                    <td>Test</td>
                                                </tr>
                                                <tr>
                                                    <td>Final Balance</td>
                                                    <td>Test</td>
                                                </tr>
                                            </thead>
                                        </table>
                                        <h5>Record Compared to <button class="no-style-btn">February 2025</button></h5>
                                    </div>
                                </div>
                                <div class="row content">
                                    <div class="col">
                                        <h3>Financial Flow</h3>
                                        <div class="row chart-container">
                                            <div class="col">
                                                <canvas class="horizontal-waterfall-chart"
                                                    id="horizontalWaterfall"></canvas>
                                            </div>
                                        </div>
                                        <p>20% of Funds Left</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php foreach ($records as $year => $months) { ?>
                        <div class="row content financial-records">
                            <div class="col">
                                <div class="row header">
                                    <div class="col">
                                        <h3><?php echo $year?></h3>
                                        <button class="btn btn-primary">Generate Report</button>
                                    </div>
                                </div>

                                <ul class="list-group">
                                    <?php foreach ($months as $month) { ?>
                                    <li class="list-group-item">
                                        <h4><?php echo $month['name']?>
                                        </h4>
                                        <button class="no-style-btn"><i class="bi bi-image"></i></button>
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

    <form id="addRecordForm" enctype="multipart/form-data">
        <div class="modal fade add-record-modal" id="addRecordModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Financial Record</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body center-modal-body">
                        <div class="row warning"
                            style="display: <?php echo ($recordCount > 0) ? 'flex' : 'none'; ?>;">
                            <div class="col-auto">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                            <div class="col">
                                <h6>Creating a new record makes the previous one uneditable. <br>
                                    Review and confirm before Proceeding</h6>
                            </div>
                        </div>
                        <label for="inputStartYear" class="form-label">Academic Year</label>
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
                        <label for="month" class="form-label">Month</label>
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

    <?php require_once BASE_PATH . '/assets/components/footer-links.php' ?>

    <!-- CHART.JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>

    <script src="finance-page.js"></script>

    <script>
        // Retrieve the session role from PHP
        var sessionID =
            "<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'undefined'; ?>";

        var sessionRole =
            "<?php echo isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'undefined'; ?>";

        console.log("User ID: " + sessionID);
        console.log("User Role: " + sessionRole);
    </script>
</body>

</html>