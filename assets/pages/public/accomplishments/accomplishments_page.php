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

    <!-- SPLIDE.JS -->
    <link href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css" rel="stylesheet">

    <!-- FANCYBOX -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />

    <link rel="stylesheet" href="accomplishments-page.css" />
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <?php if ($_SESSION['user_role'] === 'Organization') {
                    include_once BASE_PATH . '/assets/components/sidebar/organization/org_sidebar.php';
                } elseif ($_SESSION['user_role'] === 'Director') {
                    include_once BASE_PATH . '/assets/components/sidebar/director/director_sidebar.php';
                } elseif ($_SESSION['user_role'] === 'VPSLD') {
                    include_once BASE_PATH . '/assets/components/sidebar/vpsld/vpsld_sidebar.php';
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
                        <div class="row container"
                            data-year="<?php echo $year?>">
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
                                            id="<?php echo $accordionId?>"
                                            data-month="<?php echo $organizations[0]['month_id'] ?>">
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
                                                                data-id="<?php echo $organization['public_key']?>"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#viewActivityModal">
                                                                <?php echo $organization['organization']?>
                                                                <div class="group">
                                                                    <p>
                                                                        <?php echo $organization['activity_count']?>
                                                                        <?php echo $organization['activity_count'] == 1 ? 'Activity' : 'Activities'?>
                                                                    </p>
                                                                    <button class="btn btn-outline-primary generatePDF"
                                                                        data-month="<?php echo $organization['month_id']?>"
                                                                        data-year="<?php echo $year?>"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#viewActivityModal">
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

    <!-- View Profile Modal -->
    <?php if ($_SESSION['user_role'] === 'Organization') {
        include_once BASE_PATH . '/assets/components/sidebar/org_modal.php';
    } elseif ($_SESSION['user_role'] === 'Director' || $_SESSION['user_role'] === 'VPSLD') {
        include_once BASE_PATH . '/assets/components/sidebar/admin_modal.php';
    }?>

    <!-- View Activity Details Modal -->
    <div class="modal fade view-activity-modal" id="viewActivityModal" tabindex="-1"
        aria-labelledby="viewActivityModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Activity Accomplishment</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="slider-container">
                            <div class="container-fluid slider-item activity-list-container">
                                <ul class="list-group">
                                </ul>
                            </div>
                            <div class="container-fluid slider-item activity-details">
                                <div class="row header">
                                    <div class="col">
                                        <h2>Activity Accomplishment</h2>
                                    </div>
                                    <div class="col-auto">
                                        <button class="no-style-btn" id="goBack">Go Back</button>
                                    </div>
                                </div>
                                <div class="container-fluid activity-gallery">
                                    <div class="splide">
                                        <div class="splide__track">
                                            <ul class="splide__list">
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="container-fluid body">
                                    <section class="container-fluid section">
                                        <h3>Activity Overview</h3>
                                        <div class="container-fluid wrapper">
                                            <div class="row">
                                                <div class="col">
                                                    <h5>Title</h5>
                                                    <p id="activityTitle">Test</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <h5>Description</h5>
                                                    <p id="activityDescription">Test</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <h5>Location</h5>
                                                    <p id="activityLocation">Test</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <h5>Date</h5>
                                                    <p id="activityDate">Test</p>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    <section class="container-fluid section">
                                        <h3>Participants</h3>
                                        <div class="container-fluid wrapper">
                                            <div class="row">
                                                <div class="col">
                                                    <h5>Target Number of Participants</h5>
                                                    <p id="activityTargetParticipants">Test</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <h5>Participating Members</h5>
                                                    <p id="activityParticipatingMembers">Test</p>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    <section class="container-fluid section">
                                        <h3>Budget</h3>
                                        <div class="container-fluid wrapper">
                                            <div class="row">
                                                <div class="col">
                                                    <h5>Budget Given</h5>
                                                    <p id="activityBudgetGiven">Test</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <h5>Budget Utilized</h5>
                                                    <p id="activityBudgetUtilized">Test</p>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    <section class="container-fluid section">
                                        <h3>Activity Insights</h3>
                                        <div class="container-fluid wrapper">
                                            <div class="row">
                                                <div class="col">
                                                    <h5>Objectives</h5>
                                                    <p id="activityObjectives">Test</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <h5>Challenges and Solutions</h5>
                                                    <p id="activityChallengesSolutions">Test</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <h5>Lesson Learned</h5>
                                                    <p id="activityLessonLearned">Test</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <h5>Suggestions</h5>
                                                    <p id="activitySuggestions">Test</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <h5>Remarks</h5>
                                                    <p id="activityRemarks">Test</p>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER LINKS -->
    <?php require_once BASE_PATH . '/assets/components/footer_links.php' ?>

    <!-- SPLIDE.JS -->
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>

    <!-- FANCYBOX -->
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>

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