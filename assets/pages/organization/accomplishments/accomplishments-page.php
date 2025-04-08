<?php require_once '../../../sql/base-path.php'?>

<?php
//require_once '../../../sql/session_check.php';
//check_role('Organization');
?>

<?php include_once BASE_PATH . '/assets/sql/temporary_session.php'?>

<?php require_once 'sql/display-accomplishment.php'?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accomplishments</title>

    <?php require_once BASE_PATH . '/assets/components/header-links.php'?>

    <link rel="stylesheet" href="accomplishments-page.css">

    <!-- GOOGLE MATERIAL SYMBOLS -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=book_5" />
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
                        <h1>My Accomplishments</h1>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#addAccomplishmentModal">
                            <i class="bi bi-plus"></i>Add Accomplishment
                        </button>
                    </div>
                </div>
                <div class="row page-body">
                    <div class="col">
                        <?php foreach ($accomplishments as $year => $months) { ?>
                        <div class="row content">
                            <div class="col">
                                <div class="row content-header">
                                    <div class="col-12">
                                        <h2><?php echo $year?></h2>
                                        <button type="button" id="addActivityButton" data-bs-toggle="modal"
                                            data-bs-target="#addActivityModal">
                                            <i class="bi bi-plus-lg"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="row content-list">
                                    <div class="col-12">
                                        <?php foreach ($months as $monthIndex => $month) { ?>
                                        <?php $uniqueID="collapse".$year."-".$monthIndex?>
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
                                                            <button class="readEvents no-style-btn  ">
                                                                <span class="material-symbols-outlined">book_5</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </h2>
                                                <div id="<?php echo $uniqueID;?>"
                                                    class="accordion-collapse collapse">
                                                    <div class="accordion-body">
                                                        <ul class="list-group">
                                                            <?php foreach ($month['events'] as $event) {?>
                                                            <li class="list-group-item">
                                                                <h4><?php echo $event?>
                                                                </h4>
                                                                <div class="action-group">
                                                                    <button class="editEvent no-style-btn">
                                                                        <i class="bi bi-pencil-square"></i>
                                                                    </button>
                                                                    <button class="deleteEvent no-style-btn">
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
                        </div>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

    <?php require_once BASE_PATH . '/assets/components/footer-links.php' ?>


    <!-- JSPDF -->
    <script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.4/jspdf.plugin.autotable.min.js"></script>

    <script type="module" src="accomplishments-page.js"></script>

    <script>
        console.log(
            "User ID:", <?php echo json_encode($_SESSION['user_id']); ?>
        );
    </script>
</body>

</html>