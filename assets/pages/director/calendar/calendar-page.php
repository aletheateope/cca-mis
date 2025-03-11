<?php include_once '../../../sql/base-path.php';?>

<?php
//require_once '../../sql/session_check.php';
//check_role('Director');
?>

<?php require_once 'sql/display-pending-events.php'?>

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
                <?php include BASE_PATH . '/assets/components/sidebar/director/sidebar.php' ?>
            </div>
            <div class="col main-content">
                <div class="row page-header">
                    <div class="col">
                        <h1>Calendar</h1>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEventModal">
                            Add Event
                        </button>
                    </div>
                </div>
                <div class="page-body">
                    <div class="row content">
                        <div class="col">
                            <div class="calendar" id='calendar'></div>
                        </div>
                    </div>
                    <div class="row content">
                        <div class="col">
                            <h3>Event Approvals</h3>

                            <div class="row">
                                <div class="col">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Organization Name</th>
                                                <th scope="col">Event Title</th>
                                                <th scope="col">Location</th>
                                                <th scope="col">Start Date</th>
                                                <th scope="col">End Date</th>
                                                <th scope="col">
                                                    <div class="last-column">Action</div>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = mysqli_fetch_assoc($result)) {?>
                                            <tr>
                                                <td><?php echo $row['organization']?>
                                                </td>
                                                <td><?php echo $row['title'];?>
                                                </td>
                                                <td><?php echo $row['location'];?>
                                                </td>
                                                <td><?php echo $row['start_date'];?>
                                                </td>
                                                <td><?php echo $row['end_date'];?>
                                                </td>
                                                <td>
                                                    <div class="last-column">
                                                        <i class="bi bi-check-square-fill icon-approve"></i>
                                                        <i class="bi bi-x-square-fill icon-reject"></i>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php }?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="addEventForm" enctype="multipart/form-data">
        <div class="modal fade add-event-modal" id="addEventModal" tabindex="-1">
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
                                <input type="text" name="title" class="form-control" id="inputTitle">

                                <label for="inputDescription" class="form-label">Description</label>
                                <textarea type="text" name="description" class="form-control"
                                    id="inputDescription"></textarea>

                                <label for="inputLocation" class="form-label">Location</label>
                                <input type="text" name="location" class="form-control" id="inputLocation">

                                <div class="row row-gap">
                                    <div class="col">
                                        <label for="inputStartDate" class="form-label">Start Date</label>
                                        <input type="date" name="start_date" class="form-control" id="inputStartDate">
                                    </div>
                                    <div class="col">
                                        <label for="inputEndDate" class="form-label">End Date</label>
                                        <input type="date" name="end_date" class="form-control" id="inputEndDate">
                                    </div>
                                </div>

                                <div class="row row-gap">
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

    <?php require_once BASE_PATH . '/assets/components/footer-links.php' ?>

    <!-- FULLCALENDAR -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

    <script src="calendar-page.js"></script>
</body>

</html>