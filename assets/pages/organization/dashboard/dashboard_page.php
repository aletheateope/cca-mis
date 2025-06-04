<?php require_once '../../../sql/base_path.php'?>

<?php
require_once BASE_PATH . '/assets/sql/session_check.php';
check_role('Organization');
?>

<?php
// include_once BASE_PATH . '/assets/sql/temporary_session.php'
?>

<?php require_once 'sql/display_members.php'?>
<?php require_once 'sql/display_last_month.php'?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>

    <?php require_once BASE_PATH . '/assets/components/header_links.php' ?>

    <link rel="stylesheet" href="dashboard-page.css">
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
                        <h1>Dashboard</h1>
                        <?php include_once BASE_PATH . '/assets/components/topbar/topbar.php'?>
                    </div>
                </div>
                <div class="row page-body">
                    <div class="col">
                        <div class="container-fluid wrapper">
                            <h3>Members</h3>
                            <div class="row member-state">
                                <div class="col container active-state">
                                    <h2>Active</h2>
                                    <i class="bi bi-person-fill"></i>
                                    <div class="group">
                                        <p><?php echo $active_members ?>
                                        </p>
                                        <?php if ($members_added > 0) { ?>
                                        <h5><?php echo $members_added ?>
                                            added this month.</h5>
                                        <?php } else { ?>
                                        <h5>No members added this month.</h5>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="col container inactive-state">
                                    <h2>Inactive</h2>
                                    <i class="bi bi-person-fill"></i>
                                    <p><?php echo $inactive_members ?>
                                    </p>
                                </div>
                                <div class="col container exited-state">
                                    <h2>Exited</h2>
                                    <i class="bi bi-person-fill"></i>
                                    <p><?php echo $exited_members ?>
                                    </p>
                                </div>
                                <div class="col container terminated-state">
                                    <h2>Terminated</h2>
                                    <i class="bi bi-person-fill-x"></i>
                                    <p><?php echo $terminated_members ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="container-fluid wrapper">
                            <h3>Last Month
                                (<?php echo $last_month_name?>)</h3>
                            <div class="container-fluid member-last-month">
                                <div class="row">
                                    <div class="col-auto container member-state-lm">
                                        <div class="member-state-chart">
                                            <canvas id="memberStateChart"></canvas>
                                        </div>
                                        <button class="no-style-btn save-report-btn">Save Report</button>
                                    </div>
                                    <div class="col">
                                        <div class="row">
                                            <div class="col container">
                                                <div class="pie-chart">
                                                    <canvas id="memberStatusChart"></canvas>
                                                </div>
                                                <button class="no-style-btn save-report-btn">Save Report</button>
                                            </div>
                                            <div class="col container">
                                                <div class="pie-chart">
                                                    <canvas id="memberGenderChart"></canvas>
                                                </div>
                                                <button class="no-style-btn save-report-btn">Save Report</button>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col container">
                                                <div>
                                                    <canvas id="memberCollegeChart"></canvas>
                                                </div>
                                                <button class="no-style-btn save-report-btn">Save Report</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="save-report-container">
        <div class="container-fluid report-chart" id="captureChart">
            <canvas id="reportChart"></canvas>
        </div>
        <div class="container-fluid report-table-container">
            <div class="container-fluid report-table" id="reportTable">
            </div>
        </div>
    </div>


    <!-- MODAL -->
    <!-- Topbar -->
    <?php include_once BASE_PATH . '/assets/components/topbar/topbar_modal.php'?>

    <!-- Profile Modal -->
    <?php include_once BASE_PATH . '/assets/components/sidebar/org_modal.php'?>

    <!-- FOOTER LINKS -->
    <?php require_once BASE_PATH . '/assets/components/footer_links.php'; ?>

    <!-- CHART.JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

    <!-- HTML2CANVAS -->
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

    <script type="module" src="dashboard-page.js"></script>

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