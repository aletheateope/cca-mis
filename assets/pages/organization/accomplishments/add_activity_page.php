<?php require_once '../../../sql/base_path.php'?>

<?php
//require_once '../../sql/session_check.php';
//check_role('Organization');
?>

<?php session_start();?>

<?php require_once 'sql/check_report_id.php'?>

<?php require_once 'sql/get_month_year.php'?>

<?php require_once 'sql/events.php'?>

<?php require_once 'sql/active_members.php'?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Activity</title>

    <?php require_once BASE_PATH . '/assets/components/header_links.php' ?>

    <!-- SPLIDE.JS -->
    <link href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css" rel="stylesheet">

    <link rel="stylesheet" href="/cca/assets/components/add-page.css">

    <link rel="stylesheet" href="add-activity-page.css">

    <link rel="stylesheet" href="/cca/assets/components/media-query.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <?php include BASE_PATH . '/assets/components/sidebar/organization/sidebar.php' ?>
            </div>
            <div class="col main-content">
                <div class="row page-header">
                    <div class="col">
                        <div class="row">
                            <div class="col">
                                <h1>Add Activity</h1>
                                <?php include BASE_PATH . '/assets/components/topbar/topbar.php'?>
                                <a href="my_accomplishments_page.php" class="back-button">Go Back</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <h3>For <?= $month?>,
                                    <?= $year?>
                                </h3>
                            </div>
                        </div>

                    </div>
                </div>
                <form id="submitActivityForm" enctype="multipart/form-data">
                    <div class="row page-body">
                        <div class="col">
                            <div class="row container gallery-section">
                                <div class="col">
                                    <div class="row">
                                        <div class="col">
                                            <h3>Gallery</h3>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="splide">
                                                <div class="splide__track">
                                                    <ul class="splide__list">
                                                        <!-- <li class="splide__slide"></li> -->
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <h5>Drag and Drop <br> or <br> <a href="" id="inputImageButton">Browse</a>
                                                Files
                                            </h5>
                                            <h6>Supports JPEG, JPG, PNG</h6>
                                            <input type="file" id="storeImgGallery" style="display: none;"
                                                accept="image/png, image/jpeg" multiple />
                                            <input type="file" name="activity_gallery[]" id="inputActivityGallery"
                                                style="display: none;" accept="image/png, image/jpeg" multiple />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row container">
                                <div class="col">
                                    <h3>Activity Details</h3>
                                    <p>(Optional) Select Event from the Calendar</p>
                                    <select class="form-select" name="event" id="selectEvent">
                                        <option selected value="0">None</option>
                                        <?php while ($row = mysqli_fetch_assoc($events)) {?>
                                        <option
                                            value="<?php echo $row['public_key']?>">
                                            <?php echo $row['title']?>
                                        </option>
                                        <?php }?>
                                    </select>
                                    <label for="inputTitle" class="form-label">Activity Title</label>
                                    <input type="text" name="title" class="form-control" id="inputTitle" required>

                                    <label for="inputDescription" class="form-label">Description</label>
                                    <textarea class="form-control" name="description" id="inputDescription"
                                        required></textarea>

                                    <label for="inputLocation" class="form-label">Location</label>
                                    <input type="text" name="location" class="form-control" id="inputLocation" required>

                                    <div class="row row-gap">
                                        <div class="col">
                                            <label for="inputStartDate" class="form-label">Start Date</label>
                                            <input type="date" name="start_date" class="form-control"
                                                id="inputStartDate">
                                        </div>
                                        <div class="col">
                                            <label for="inputEndDate" class="form-label">End Date</label>
                                            <input type="date" name="end_date" class="form-control" id="inputEndDate">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row container">
                                <div class="col">
                                    <h3>Attendance</h3>
                                    <label for="inputTargetMembers" class="form-label">Target Number of Members</label>
                                    <input type="number" name="target_participants" class="form-control"
                                        id="inputTargetMembers" required>

                                    <label for="inputMembersAttended" class="form-label">Members Attended</label>
                                    <input type="number" name="actual_participants" class="form-control"
                                        id="inputMembersAttended"
                                        placeholder="Select participants below to start counting..." readonly required>
                                </div>
                            </div>
                            <div class="row container">
                                <div class="col">
                                    <h3>Participants</h3>
                                    <div class="row participants">
                                        <div class="col">
                                            <div class="row header">
                                                <div class="col-auto">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value=""
                                                            id="checkboxSelectAll">
                                                        <label class="form-check-label" for="checkboxSelectAll">
                                                            Select All
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="input-group">
                                                        <span class="input-group-text no-border" id="basic-addon1"><i
                                                                class="bi bi-search"></i></span>
                                                        <input type="text" class="form-control no-border"
                                                            style="padding-left: 0;" placeholder="Search">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="row" id="membersContainer">
                                                        <?php $index = 1?>
                                                        <?php while ($row = mysqli_fetch_assoc($result)) {?>
                                                        <div class="col-4 member">
                                                            <div class="form-check">
                                                                <input type="checkbox"
                                                                    class="form-check-input member-checkbox"
                                                                    name="participants[]"
                                                                    value="<?php echo $row['public_key']; ?>"
                                                                    id="participant-<?php echo $index?>">
                                                                <label class="form-check-label"
                                                                    for="participant-<?php echo $index?>">
                                                                    <?php echo $row ['first_name']. ' ' . $row['last_name'];?>
                                                                </label>
                                                            </div>
                                                            <div class="add-recognition" id="addRecognition"><i
                                                                    class="bi bi-plus"></i></div>
                                                        </div>
                                                        <?php $index++;?>
                                                        <?php }?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row container recognition-container">
                                <div class="col">
                                    <h3>Recognition</h3>
                                    <div class="row row-gap recognition-row">
                                    </div>
                                </div>
                            </div>
                            <div class="row container">
                                <div class="col">
                                    <h3>Activity Insights</h3>
                                    <div class="row row-gap">
                                        <div class="col">
                                            <label for="inputEventObjective" class="form-label">Objective</label>
                                            <textarea name="objective" class="form-control"
                                                id="inputEventObjective"></textarea>
                                        </div>
                                        <div class="col">
                                            <label for="inputEventChallengesSolution" class="form-label">Challenges and
                                                Solution</label>
                                            <textarea name="challenges_solution" class="form-control"
                                                id="inputEventChallengesSolution"></textarea>
                                        </div>
                                    </div>
                                    <div class="row row-gap">
                                        <div class="col">
                                            <label for="inputEventLessonLearned" class="form-label">Lesson
                                                Learned</label>
                                            <textarea class="form-control" name="lesson_learned"
                                                id="inputEventLessonLearned"></textarea>
                                        </div>
                                        <div class="col">
                                            <label for="inputEventSuggestions" class="form-label">Suggestions</label>
                                            <textarea class="form-control" name="suggestion"
                                                id="inputEventSuggestions"></textarea>
                                        </div>
                                    </div>
                                    <label for="inputBudgetUtilized" class="form-label">Budget Utilized</label>
                                    <input type="text" name="budget_utilized" class="form-control"
                                        id="inputBudgetUtilized" placeholder="0" required>
                                </div>
                            </div>
                            <div class="row container">
                                <div class="col">
                                    <h3>Remarks</h3>
                                    <div class="row remarks-section">
                                        <div class="col">
                                            <div class="form-check form-check-inline">
                                                <input type="radio" name="remark" class="form-check-input"
                                                    id="radioAccomplished1" value="1" checked>
                                                <label class="form-check-label" for="radioAccomplished1">
                                                    Accomplished
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" name="remark" class="form-check-input"
                                                    id="radioAccomplished2" value="2">
                                                <label class="form-check-label" for="radioAccomplished2">
                                                    Accomplished but did not meet the target number of members.
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row submit-row">
                                <div class="col">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php require_once BASE_PATH . '/assets/components/footer_links.php'; ?>

    <!-- SPLIDE.JS -->
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>

    <script type="module" src="add-activity-page.js"></script>

    <script>
        console.log("User ID:", <?php echo json_encode($user_id); ?> );
        console.log(
            "Report ID:", <?= json_encode($report_id)?>
        )
    </script>

</body>

</html>