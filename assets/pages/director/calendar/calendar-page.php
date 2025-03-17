<?php include_once '../../../sql/base-path.php';?>

<?php
//require_once '../../sql/session_check.php';
//check_role('Director');
?>

<?php require_once 'sql/display-event-requests.php'?>

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
                    <!-- <div class="row event-approval">
                    </div> -->
                </div>
            </div>
            <div class="col-auto event-approval-panel">
                <div class="row title">
                    <div class="col">
                        <h3>Event Approvals</h3>
                    </div>
                </div>

                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <?php if (is_null($row['start_time']) && is_null($row['end_time'])) { ?>
                <div class="row event-request-card">
                    <div class="col">
                        <div class="row title">
                            <div class="col">
                                <img src="/cca/assets/img/blank-profile.png" alt="" class="image">
                                <h4><?php echo $row['organization']?>
                                </h4>
                            </div>
                            <div class="col-auto">
                                <h6>All Day</h6>
                            </div>
                        </div>
                        <div class="row body">
                            <div class="col">
                                <h4><?php echo $row['title']?>
                                </h4>
                                <p><?php echo $row['description']?>
                                </p>

                                <div class="row date">
                                    <div class="col">
                                        <h4>Start Date</h4>
                                        <p><?php echo date("F j, Y", strtotime($row['start_date']))?>
                                        </p>
                                    </div>
                                    <div class="col">
                                        <h4>End Date</h4>
                                        <p><?php echo date("F j, Y", strtotime($row['end_date']))?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row button-row">
                            <div class="col">
                                <button type="button" class="approve-btn no-style-btn"
                                    data-id="<?php echo $row['event_request_id']?>">Approve</button>
                            </div>
                            <div class="col">
                                <button type="button" class="reject-btn no-style-btn"
                                    data-id="<?php echo $row['event_request_id']?>">Reject</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } else { ?>
                <div class="row event-request-card">
                    <div class="col">
                        <div class="row title">
                            <div class="col">
                                <img src="/cca/assets/img/blank-profile.png" alt="" class="image">
                                <h4><?php echo $row['organization']?>
                                </h4>
                            </div>
                        </div>
                        <div class="row body">
                            <div class="col">
                                <h4><?php echo $row['title']?>
                                </h4>
                                <p><?php echo $row['description']?>
                                </p>

                                <div class="row date">
                                    <div class="col">
                                        <div class="row">
                                            <div class="col">
                                                <h4>Start Date</h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <p> <?php echo date("F j, Y", strtotime($row['start_date']))?>
                                                </p>
                                            </div>
                                            <div class="col">
                                                <p><?php echo date("g:i A", strtotime($row['start_time']))?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row date">
                                    <div class="col">
                                        <div class="row">
                                            <div class="col">
                                                <h4>End Date</h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <?php echo date("F j, Y", strtotime($row['end_date']));?>
                                            </div>
                                            <div class="col">
                                                <p><?php echo date("g:i A", strtotime($row['end_time']))?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row button-row">
                            <div class="col">
                                <button type="button" class="approve-btn no-style-btn"
                                    data-id="<?php echo $row['event_request_id']?>">Approve</button>
                            </div>
                            <div class="col">
                                <button type="button" class="reject-btn no-style-btn"
                                    data-id="<?php echo $row['event_request_id']?>">Reject</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php  }?>
                <?php } ?>
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