<?php
//require_once '../../../sql/session_check.php';
//check_role('Organization');
?>

<?php include_once '../../../sql/temporary_session.php'?>

<?php require_once 'sql/display-accomplishment.php'?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accomplishments</title>

    <?php require_once '../../../components/header-links.php'?>

    <link rel="stylesheet" href="accomplishments-page.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <?php include '../../../components/sidebar/organization/sidebar.php' ?>
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
                                <div class="row">
                                    <div class="col-12 content-header">
                                        <h2><?php echo $year?>
                                        </h2>
                                        <button type="button" id="addActivityButton" data-bs-toggle="modal"
                                            data-bs-target="#addActivityModal">
                                            <i class="bi bi-plus-lg"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 content-list">
                                        <ul class="list-group">
                                            <?php foreach ($months as $month) { ?>
                                            <li class="list-group-item">
                                                <h4><?php echo $month['name']?>
                                                </h4>
                                                <button type="button" class="no-style-btn generatePDF"
                                                    data-month="<?php echo $month['id']?>"
                                                    data-year="<?php echo $year?>">
                                                    <i class="bi bi-file-earmark-arrow-down"></i>
                                                </button>
                                            </li>
                                            <?php } ?>
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
    </div>

    <form id="addAccomplishmentForm" enctype="multipart/form-data">
        <div class="modal fade add-activity-modal" id="addAccomplishmentModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Accomplishment Report</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
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
                        </div>
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
        <div class="modal fade add-activity-modal" id="addActivityModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Add Activity</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
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
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <?php require_once '../../../components/footer-links.php' ?>


    <!-- JSPDF -->
    <script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.4/jspdf.plugin.autotable.min.js"></script>

    <script src="accomplishments-page.js"></script>

    <script>
        console.log(
            "User ID:", <?php echo json_encode($_SESSION['user_id']); ?>
        );
    </script>
</body>

</html>