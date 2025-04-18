<?php require_once '../../../sql/base-path.php'?>

<?php
//require_once '../../sql/session_check.php';
//check_role('Organization');
?>

<?php include_once '../../../sql/temporary_session.php'?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Calendar</title>

    <?php require_once BASE_PATH . '/assets/components/header-links.php' ?>

    <link rel="stylesheet" href="calendar-page.css">

</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <?php include BASE_PATH . '/assets/components/sidebar/organization/sidebar.php';?>
            </div>
            <div class="col main-content">
                <div class="row page-header">
                    <div class="col">
                        <h1>Calendar</h1>
                        <button class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#requestEventModal">Request Event</button>
                    </div>
                </div>
                <div class="row page-body">
                    <div class="col">
                        <div class="row container">
                            <div class="col">
                                <div class="calendar" id='calendar'></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="requestEventForm" enctype="multipart/form-data">
        <div class="modal fade request-event-modal" id="requestEventModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Request Event</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <label for="inputTitle" class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" id="inputTitle" required>

                                <label for="inputDescription" class="form-label">Description</label>
                                <textarea type="text" name="description" class="form-control" id="inputDescription"
                                    required></textarea>

                                <label for="inputLocation" class="form-label">Location</label>
                                <input type="text" name="location" class="form-control" id="inputLocation" required>

                                <div class="row row-gap">
                                    <div class="col">
                                        <label for="inputStartDate" class="form-label">Start Date</label>
                                        <input type="date" name="start_date" class="form-control" id="inputStartDate"
                                            required>
                                    </div>
                                    <div class="col">
                                        <label for="inputEndDate" class="form-label">End Date</label>
                                        <input type="date" name="end_date" class="form-control" id="inputEndDate"
                                            required>
                                    </div>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="allDay">
                                    <label class="form-check-label" for="allDay">
                                        All Day
                                    </label>
                                </div>

                                <div class="row row-gap" id="eventTime">
                                    <div class="col">
                                        <label for="inputStartTime" class="label-form">Start Time</label>
                                        <input type="time" name="start_time" class="form-control" id="inputStartTime">
                                    </div>
                                    <div class="col">
                                        <label for="inputEndTime" class="label-form">End Time</label>
                                        <input type="time" name="end_time" class="form-control" id="inputEndTime">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <?php require_once BASE_PATH . '/assets/components/footer-links.php';?>

    <!-- FULLCALENDAR -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

    <script type="module" src="calendar-page.js"></script>

    <script>
        console.log(
            "User ID:", <?php echo json_encode($_SESSION['user_id']); ?>
        )
    </script>
</body>

</html>