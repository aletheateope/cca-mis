<?php require_once '../../../sql/base_path.php'?>

<?php
require_once BASE_PATH . '/assets/sql/session_check.php';
check_role('Organization');
?>

<?php
// include_once BASE_PATH . '/assets/sql/temporary_session.php'
?>

<?php require_once 'sql/display_accomplishments.php'?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Accomplishments</title>

    <?php require_once BASE_PATH . '/assets/components/header_links.php'?>

    <!-- FANCYBOX -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />

    <link rel="stylesheet" href="my-accomplishments-page.css">
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
                        <h1>My Activities</h1>
                        <?php include_once BASE_PATH . '/assets/components/topbar/topbar.php'?>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#addAccomplishmentModal">
                            <i class="bi bi-plus"></i>Add Accomplishment
                        </button>
                    </div>
                </div>
                <div class="row page-body">
                    <div class="col">
                        <?php foreach ($accomplishments as $year => $months) { ?>
                        <section class="row container">
                            <div class="col">
                                <div class="row header">
                                    <div class="col-12">
                                        <h2><?php echo $year?></h2>
                                        <button class="no-style-btn" id="addActivityButton" data-bs-toggle="modal"
                                            data-bs-target="#addActivityModal">
                                            <i class="bi bi-plus-lg"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="row body">
                                    <div class="col-12">
                                        <?php foreach ($months as $month) { ?>
                                        <?php $uniqueID = "collapse" . $year . "-" . $month['id'];?>
                                        <div class="accordion accordion-accomplishments">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <div class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#<?php echo $uniqueID?>">
                                                        <span><?php echo $month['name'];?></span>
                                                        <div class="action-group">
                                                            <div class="events">
                                                                <p>
                                                                    <?php $eventCount = count($month['events']);?>
                                                                    <?php echo $eventCount . ($eventCount == 1 ? ' Activity' : ' Activities');?>
                                                                </p>
                                                                <button class="addEvent no-style-btn">
                                                                    <i class="bi bi-plus"></i>
                                                                </button>
                                                            </div>
                                                            <button class="generatePDF no-style-btn"
                                                                data-month="<?php echo $month['id']?>"
                                                                data-year="<?php echo $year?>">
                                                                <i class="bi bi-filetype-pdf"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </h2>
                                                <div id="<?php echo $uniqueID;?>"
                                                    class="accordion-collapse collapse">
                                                    <div class="accordion-body">
                                                        <ul class="list-group">
                                                            <?php foreach ($month['events'] as $event) {?>
                                                            <li class="list-group-item"
                                                                data-id="<?php echo $event['public_key']?>"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#viewActivityModal">
                                                                <h4><?php echo $event['title']?>
                                                                </h4>
                                                                <div class="action-group">
                                                                    <button class="no-style-btn edit-btn"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#viewActivityModal">
                                                                        <i class="bi bi-pencil-square"></i>
                                                                    </button>
                                                                    <button class="no-style-btn delete-btn"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#viewActivityModal">
                                                                        <i class="bi bi-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </li>
                                                            <?php }?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </section>
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

    <!-- Add Accomplishment Modal -->
    <form id="addAccomplishmentForm" enctype="multipart/form-data">
        <div class="modal fade" id="addAccomplishmentModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Accomplishment Report</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body center-modal-body">
                        <label for="month" class="form-label">Month</label>
                        <select name="month" class="form-select" id="month">
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
                        <label for="inputYear" class="form-label">Year</label>
                        <input type="text" name="year" class="form-control" id="inputYear">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Add Activity Modal -->
    <form id="addActivityForm" enctype="multipart/form-data">
        <div class="modal fade" id="addActivityModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Add Activity</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body center-modal-body">
                        <label for="month" class="form-label">Month</label>
                        <select name="month" class="form-select" id="month">
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
                        <label for="year" class="form-label">Year</label>
                        <input type="text" name="year" class="form-control" id="year" readonly>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

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
                    <div class="container-fluid activity-details">
                        <section class="container-fluid section">
                            <div class="row">
                                <div class="col">
                                    <h3>Activity Overview</h3>
                                </div>
                            </div>
                            <div class="container-fluid wrapper">
                                <div class="row">
                                    <div class="col">
                                        <h5>Title</h5>
                                        <p id="activityTitle">---</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <h5>Description</h5>
                                        <p id="activityDescription">---</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <h5>Location</h5>
                                        <p id="activityLocation">---</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <h5>Date</h5>
                                        <p id="activityDate">---</p>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <section class="container-fluid section">
                            <div class="row">
                                <div class="col">
                                    <h3>Participants</h3>
                                </div>
                            </div>
                            <div class="container-fluid wrapper">
                                <div class="row">
                                    <div class="col">
                                        <h5>Target Number of Participants</h5>
                                        <p id="activityTargetParticipants">---</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <h5>Participating Members</h5>
                                        <p id="activityParticipatingMembers">
                                            --- <button class="no-style-btn view-list-btn">[View List]</button>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <section class="container-fluid section">
                            <div class="row">
                                <div class="col">
                                    <h3>Activity Insights</h3>
                                </div>
                            </div>
                            <div class="container-fluid wrapper">
                                <div class="row">
                                    <div class="col">
                                        <h5>Objectives</h5>
                                        <p id="activityObjectives">---</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <h5>Challenges and Solutions</h5>
                                        <p id="activityChallengesSolutions">---</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <h5>Lesson Learned</h5>
                                        <p id="activityLessonLearned">---</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <h5>Suggestions</h5>
                                        <p id="activitySuggestions">---</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <h5>Remarks</h5>
                                        <p id="activityRemarks">---</p>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <section class="container-fluid section">
                            <div class="row">
                                <div class="col">
                                    <h3>Budget</h3>
                                </div>
                            </div>
                            <div class="container-fluid wrapper">
                                <div class="row">
                                    <div class="col">
                                        <h5>Budget Given</h5>
                                        <p id="activityBudgetGiven">---</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <h5>Budget Utilized</h5>
                                        <p id="activityBudgetUtilized">---</p>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <section class="container-fluid section">
                            <div class="accordion gallery-accordion" id="activityGalleryAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseGallery"
                                            aria-expanded="true" aria-controls="collapseGallery">
                                            Gallery
                                        </button>
                                    </h2>
                                    <div id="collapseGallery" class="accordion-collapse collapse"
                                        data-bs-parent="#activityGalleryAccordion">
                                        <div class="accordion-body">
                                            <div class="gallery-container activity-gallery">
                                            </div>
                                        </div>
                                    </div>
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

    <!-- FOOTER LINKS -->
    <?php require_once BASE_PATH . '/assets/components/footer_links.php' ?>

    <!-- JSPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/3.0.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.4/jspdf.plugin.autotable.min.js"></script>

    <!-- FANCYBOX -->
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>

    <script type="module" src="my-accomplishments-page.js"></script>

    <script>
        console.log(
            "User ID:", <?php echo json_encode($_SESSION['user_id']); ?>
        );
    </script>
</body>

</html>