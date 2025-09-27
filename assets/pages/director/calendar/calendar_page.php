<?php include_once '../../../sql/base_path.php';?>

<?php
require_once BASE_PATH . '/assets/sql/session_check.php';
check_role('Director');
?>

<?php
// include BASE_PATH . '/assets/sql/temporary_session.php'
?>

<?php require_once 'sql/display_event_requests.php'?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Calendar</title>

    <?php require_once BASE_PATH . '/assets/components/header_links.php' ?>

    <!-- FILEPOND -->
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css"
        rel="stylesheet" />

    <link href="https://unpkg.com/filepond-plugin-pdf-preview/dist/filepond-plugin-pdf-preview.min.css"
        rel="stylesheet">

    <link rel="stylesheet" href="/cca/assets/components/media-query/calendar.css">

    <link rel="stylesheet" href="calendar-page.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <?php include_once BASE_PATH . '/assets/components/sidebar/director/director_sidebar.php' ?>
            </div>
            <div class="col main-content">
                <div class="row page-header">
                    <div class="col">
                        <h1>Calendar</h1>
                        <?php include_once BASE_PATH . '/assets/components/topbar/topbar.php'?>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEventModal">
                            Add Event
                        </button>
                    </div>
                </div>
                <div class="row page-body">
                    <div class="col">
                        <div class="row container calendar-container">
                            <div class="col">
                                <div class="calendar" id='calendar'></div>
                            </div>
                        </div>
                        <!-- <div class="row event-approval">
                    </div> -->
                    </div>
                </div>
            </div>

            <!-- EVENT APPROVAL PANEL -->
            <div
                class="col-auto event-approval-panel <?php echo $hasData ? 'show' : '' ?>">
                <div class="row title">
                    <div class="col">
                        <h3>Event Approvals</h3>
                    </div>
                </div>

                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <?php if (is_null($row['start_time']) && is_null($row['end_time'])) { ?>
                <!-- All Day -->
                <div class="row event-request-card"
                    data-id="<?php echo $row['public_key']?>">
                    <div class="col">
                        <div class="row title">
                            <div class="col">
                                <div class="row">
                                    <div class="col-auto profile-container">
                                        <img src="/cca/assets/img/blank-profile.png" alt="" class="image">
                                    </div>
                                    <div class="col">
                                        <h4><?php echo $row['organization']?>
                                        </h4>
                                        <h6><?php echo date("F j, Y", strtotime($row['date_requested']))?>
                                        </h6>
                                    </div>
                                </div>
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
                                <p><?php echo $row['location']?>
                                </p>

                                <div class="row date">
                                    <div class="col">
                                        <h5 class="inactive-text">Start Date</h5>
                                        <p><?php echo date("F j, Y", strtotime($row['start_date']))?>
                                        </p>
                                    </div>
                                    <div class="col">
                                        <h5 class="inactive-text">End Date</h5>
                                        <p><?php echo date("F j, Y", strtotime($row['end_date']))?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row button-row">
                            <div class="col">
                                <div class="wrapper-btn">
                                    <button class="approve-btn">Approve</button>
                                    <button class="approve-plus-btn" title="More"><i
                                            class="bi bi-three-dots-vertical"></i></button>
                                </div>
                            </div>
                            <div class="col">
                                <button class="reject-btn">Reject</button>
                            </div>
                            <div class="col-auto">
                                <button class="return-btn" title="Return Request" data-bs-toggle="modal"
                                    data-bs-target="#returnEventRequestModal">
                                    <i class="bi bi-reply-fill"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } else { ?>
                <!-- Normal -->
                <div class="row event-request-card"
                    data-id="<?php echo $row['public_key']?>">
                    <div class="col">
                        <div class="row title default">
                            <div class="col-auto profile-container">
                                <img src="/cca/assets/img/blank-profile.png" alt="" class="image">
                            </div>
                            <div class="col">
                                <h4><?php echo $row['organization']?>
                                </h4>
                                <h6><?php echo date("F j, Y", strtotime($row['date_requested']));?>
                                </h6>
                            </div>
                        </div>
                        <div class="row body">
                            <div class="col">
                                <h4><?php echo $row['title']?>
                                </h4>
                                <p><?php echo $row['description']?>
                                </p>
                                <p><?php echo $row['location']?>
                                </p>

                                <div class="row date">
                                    <div class="col">
                                        <div class="row">
                                            <div class="col">
                                                <h5 class="inactive-text">Start Date</h5>
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
                                                <h5 class="inactive-text">End Date</h5>
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
                                <div class="wrapper-btn">
                                    <button class="approve-btn">Approve</button>
                                    <button class="approve-plus-btn" title="More"><i
                                            class="bi bi-three-dots-vertical"></i></button>
                                </div>
                            </div>
                            <div class="col">
                                <button class="reject-btn">Reject</button>
                            </div>
                            <div class="col-auto">
                                <button class="return-btn" title="Return Request" data-bs-toggle="modal"
                                    data-bs-target="#returnEventRequestModal">
                                    <i class="bi bi-reply-fill"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php  }?>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- MODAL -->
    <!-- Topbar -->
    <?php include_once BASE_PATH . '/assets/components/topbar/topbar_modal.php'?>

    <!-- Add Event -->
    <form id="addEventForm" enctype="multipart/form-data">
        <div class="modal fade add-event-modal" id="addEventModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Add Event</h1>
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

    <!-- Request Budget Modal -->
    <form id="requestBudgetForm" enctype="multipart/form-data">
        <div class="modal fade request-budget-modal" id="requestBudgetModal" tabindex="-1"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Request Event Budget</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <input type="hidden" name="public_key" class="form-control"
                                id="eventRequestPublicKeyBudget">
                            <div class="container-fluid wrapper">
                                <div class="row">
                                    <div class="col">
                                        <h3 id="eventRequestTitleBudget">Event Request Title</h3>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <p>From: <span id="eventRequestOrganizationBudget">Organization</p>
                                    </div>
                                </div>
                            </div>
                            <div class="container-fluid">
                                <div class="container-fluid wrapper">
                                    <div class="row">
                                        <div class="col">
                                            <h4>Request Letter</h4>
                                        </div>
                                    </div>
                                    <div class="container-fluid">
                                        <input type="file" name="req_letter[]" id="letterUpload"
                                            accept="application/pdf" multiple />
                                    </div>
                                </div>
                                <div class="container-fluid wrapper">
                                    <div class="row">
                                        <div class="col">
                                            <label for="inputBudgetAmount" class="form-label">Amount</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <input type="text" name="budget" class="form-control" id="inputBudgetAmount"
                                                required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Send Request</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Return Event Request Modal -->
    <form id="returnEventRequestForm" enctype="multipart/form-data">
        <div class="modal fade return-event-request-modal" id="returnEventRequestModal" tabindex="-1"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Return Event Request</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <input type="hidden" name="public_key" class="form-control" id="eventRequestPublicKey">
                            <div class="container-fluid wrapper">
                                <div class="row">
                                    <div class="col">
                                        <h3 id="eventRequestTitle">Event Request Title</h3>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <p>From: <span id="eventRequestOrganization">Organization</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="container-fluid wrapper">
                                <div class="row">
                                    <div class="col">
                                        <label for="inputMessage" class="form-label">Message</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <textarea class="form-control" name="message" id="inputMessage"
                                            required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Send</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <?php require_once BASE_PATH . '/assets/components/footer_links.php' ?>

    <!-- FULLCALENDAR -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

    <!-- FILEPOND -->
    <script src="https://unpkg.com/filepond-plugin-pdf-preview/dist/filepond-plugin-pdf-preview.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js">
    </script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>

    <script type="module" src="calendar-page.js"></script>

    <script>
        var sessionID =
            "<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'undefined'; ?>";

        var sessionRole =
            "<?php echo isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'undefined'; ?>";

        console.log("User ID: " + sessionID);
        console.log("User Role: " + sessionRole);
    </script>
</body>

</html>