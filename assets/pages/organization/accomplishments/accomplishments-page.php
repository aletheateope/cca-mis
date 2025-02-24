<?php
//require_once '../../../sql/session_check.php';
//check_role('Organization');
?>

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
                <div class="row">
                    <div class="col page-header">
                        <h1>Accomplishments</h1>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#addActivityModal">
                            <i class="bi bi-plus"></i>Add Activity
                        </button>
                    </div>
                </div>
                <div class="page-body">
                    <div class="row">
                        <div class="col content">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade add-activity-modal" id="addActivityModal" tabindex="-1">
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
                            <select class="form-select">
                                <option selected>January</option>
                                <option>February</option>
                                <option>March</option>
                                <option>April</option>
                                <option>May</option>
                                <option>June</option>
                                <option>July</option>
                                <option>August</option>
                                <option>September</option>
                                <option>October</option>
                                <option>November</option>
                                <option>December</option>
                            </select>
                            <label for="inputYear" class="form-label">Year</label>
                            <input type="number" class="form-control" id="inputYear">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="add-activity-page.php">
                        <button type="button" class="btn btn-primary">Create</button>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php require_once '../../../components/footer-links.php' ?>

    <script>
        var cleave = new Cleave("#inputYear", {
            date: true,
            datePattern: ["Y"],
        });
    </script>
</body>

</html>