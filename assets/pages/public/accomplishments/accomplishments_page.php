<?php require_once '../../../sql/base_path.php'?>

<?php
session_start();
?>

<?php
// include_once BASE_PATH . '/assets/sql/temporary_session.php'
?>


<?php require_once 'sql/display_accomplishments.php'?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Accomplishments</title>

    <?php require_once BASE_PATH . '/assets/components/header_links.php' ?>

    <link rel="stylesheet" href="accomplishments-page.css" />
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
            <div class="col main-content">
                <div class="row page-header">
                    <div class="col">
                        <h1>Accomplishments</h1>
                        <?php include_once BASE_PATH . '/assets/components/topbar/topbar.php'?>
                    </div>
                </div>
                <div class="row page-body">
                    <div class="col">
                        <?php foreach ($accomplishments as $year => $months) { ?>
                        <div class="row container">
                            <div class="col">
                                <div class="row header">
                                    <div class="col-12">
                                        <h2><?php echo $year?></h2>
                                    </div>
                                </div>
                                <div class="row body">
                                    <div class="col-12">
                                        <?php foreach  ($months as $month => $organizations) {?>
                                        <?php $accordionId = "accordion-" . $year . "-" . $month;?>
                                        <?php $collapseId = "collapse-" . $year . "-" . $month;?>
                                        <div class="accordion accordion-accomplishments"
                                            id="<?php echo $accordionId?>">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#<?php echo $collapseId?>"
                                                        aria-expanded="false"
                                                        aria-controls="<?php echo $collapseId?>">
                                                        <span><?php echo $month?></span>
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
                                                                <div class="group">
                                                                    <p>
                                                                        <?php echo $organization['activity_count']?>
                                                                        <?php echo $organization['activity_count'] == 1 ? 'Activity' : 'Activities'?>
                                                                    </p>
                                                                    <button class="btn btn-outline-primary generatePDF"
                                                                        data-month="<?php echo $organization['month_id']?>"
                                                                        data-year="<?php echo $year?>">
                                                                        Generate Report
                                                                    </button>
                                                                </div>
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

    <!-- FOOTER LINKS -->
    <?php require_once BASE_PATH . '/assets/components/footer_links.php'; ?>

    <!-- JSPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/3.0.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.4/jspdf.plugin.autotable.min.js"></script>

    <script type="module" src="accomplishments-page.js"></script>

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