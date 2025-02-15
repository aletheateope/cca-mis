<?php //require_once '../../sql/session_check.php'?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>

    <?php require_once '../../components/header-links.php' ?>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <?php include '../../components/sidebar/organization/sidebar.php' ?>
            </div>
            <div class="col main-content">
                <h1>Hello World</h1>
                <ul>
                    <li>Item 1</li>
                    <li>Item 2</li>
                    <li>Item 3</li>
                </ul>
            </div>
        </div>
    </div>

    <?php require_once '../../components/footer-links.php'; ?>

</body>

</html>