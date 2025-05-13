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
    <title>Dashboard</title>

    <?php require_once BASE_PATH . '/assets/components/header_links.php' ?>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <?php include_once BASE_PATH . '/assets/components/sidebar/vpsld/sidebar.php' ?>
            </div>
            <main class="col main-content">
                <div class="row page-header">
                    <div class="col">
                        <h1>Dashboard</h1>
                        <?php include_once BASE_PATH . '/assets/components/topbar/topbar.php'?>
                    </div>
                </div>
                <div class="row page-body">
                    <div class="col">
                        <section class="row container">
                            <div class="col"></div>
                        </section>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- MODAL -->
    <!-- Topbar -->
    <?php include_once BASE_PATH . '/assets/components/topbar/topbar_modal.php'?>

    <?php require_once BASE_PATH . '/assets/components/footer_links.php'; ?>
</body>

</html>