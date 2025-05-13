<?php require_once '../../../sql/base_path.php'?>

<?php
require_once BASE_PATH . '/assets/sql/session_check.php';
check_role('VPSLD');
?>

<?php
// include_once BASE_PATH . '/assets/sql/temporary_session.php'
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Calendar</title>

    <?php require_once BASE_PATH . '/assets/components/header_links.php' ?>

    <link rel="stylesheet" href="calendar-page.css">

</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <?php include_once BASE_PATH . '/assets/components/sidebar/vpsld/sidebar.php';?>
            </div>
            <div class="col main-content">
                <div class="row page-header">
                    <div class="col">
                        <h1>Calendar</h1>
                        <?php include_once BASE_PATH . '/assets/components/topbar/topbar.php'?>
                    </div>
                </div>
                <div class="row page-body">
                    <div class="col">
                        <div class="row container calendar-container">
                            <div class="col">
                                <div class="calendar" id='calendar'></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL -->
    <!-- Topbar -->
    <?php include_once BASE_PATH . '/assets/components/topbar/topbar_modal.php'?>

    <!-- FOOTER LINKS -->
    <?php require_once BASE_PATH . '/assets/components/footer_links.php';?>

    <!-- FULLCALENDAR -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

    <script src="calendar-page.js"></script>

    <script>
        console.log(
            "User ID:", <?php echo json_encode($_SESSION['user_id']); ?>
        )
    </script>
</body>

</html>