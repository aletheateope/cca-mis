<?php
//require_once '../../../sql/session_check.php';
//check_role('Organization');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance</title>

    <?php require_once '../../../components/header-links.php'?>
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
                        <h1>Finance</h1>
                        <button type="button" class="btn btn-primary add-member-btn" data-bs-toggle="modal"
                            data-bs-target="#addMemberModal">
                            <span><i class="bi bi-plus add-button"></i> Add Record</span>
                        </button>
                    </div>
                </div>
                <div class="row page-body">
                    <div class="col">
                        <div class="row content">
                            <div class="col">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once '../../../components/footer-links.php' ?>
</body>

</html>