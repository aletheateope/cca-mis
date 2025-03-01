<?php
//require_once '../../sql/session_check.php';
//check_role('Organization');
?>

<?php require_once 'sql/active-members.php'?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Activity</title>

    <?php require_once '../../../components/header-links.php' ?>

    <!-- SPLIDE.JS -->
    <link href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css" rel="stylesheet">

    <link rel="stylesheet" href="add-activity-page.css">
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
                        <h1>Add Activity</h1>
                        <a href="accomplishments-page.php" class="back-button">Go Back</a>
                    </div>
                </div>
                <form id="submitActivityForm" enctype="multipart/form-data">
                    <div class="page-body">
                        <div class="row">
                            <div class="col content">
                                <div class="row">
                                    <div class="col">
                                        <h3>Gallery</h3>
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
                                        <h5>Drag and Drop <br> or <br> <a href="" id="inputImageButton">Browse</a> Files
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
                        <div class="row">
                            <div class="col content">
                                <h3>Activity Details</h3>
                                <p>(Optional) Select Event from the Calendar</p>
                                <select class="form-select" name="event">
                                    <option selected value="1">---</option>
                                    <option value=""></option>
                                </select>
                                <label for="inputTitle" class="form-label">Activity Title</label>
                                <input type="text" name="title" class="form-control" id="inputTitle">
                                <label for="inputDescription" class="form-label">Description</label>
                                <textarea class="form-control" name="description" id="inputDescription"></textarea>
                                <label for="inputLocation" class="form-label">Location</label>
                                <input type="text" name="location" class="form-control" id="inputLocation">
                                <div class="row row-gap">
                                    <div class="col col-gap">
                                        <label for="inputStartDate" class="form-label">Start Date</label>
                                        <input type="date" name="start_date" class="form-control" id="inputStartDate">
                                    </div>
                                    <div class="col col-gap">
                                        <label for="inputEndDate" class="form-label">End Date</label>
                                        <input type="date" name="end_date" class="form-control" id="inputEndDate">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col content">
                                <h3>Attendance</h3>
                                <label for="inputTargetMembers" class="form-label">Target Number of Members</label>
                                <input type="number" name="target_participants" class="form-control"
                                    id="inputTargetMembers">

                                <label for="inputMembersAttended" class="form-label">Members Attended</label>
                                <input type="number" name="participants" class="form-control" id="inputMembersAttended"
                                    placeholder="Select participants below to start counting..." readonly>

                                <h4>Participants</h4>
                                <div class="row">
                                    <div class="col">
                                        <div class="row">
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
                                                    <?php while ($row = mysqli_fetch_assoc($result)) {?>
                                                    <div class="col-4 member">
                                                        <div class="form-check">
                                                            <input class="form-check-input member-checkbox"
                                                                type="checkbox" value=""
                                                                id="student_number_<?php echo $row['student_number']?>">
                                                            <label class="form-check-label"
                                                                for="student_number_<?php echo $row['student_number']?>">
                                                                <?php echo $row ['first_name']. ' ' . $row['last_name'];?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <?php }?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col content">
                                <h3>Activity Insights</h3>
                                <div class="row row-gap">
                                    <div class="col col-gap">
                                        <label for="inputEventObjective" class="form-label">Objective</label>
                                        <textarea name="objective" class="form-control"
                                            id="inputEventObjective"></textarea>
                                    </div>
                                    <div class="col col-gap">
                                        <label for="inputEventChallengesSolution" class="form-label">Challenges and
                                            Solution</label>
                                        <textarea name="challenges_solution" class="form-control"
                                            id="inputEventChallengesSolution"></textarea>
                                    </div>
                                </div>
                                <div class="row row-gap">
                                    <div class="col col-gap">
                                        <label for="inputEventLessonLearned" class="form-label">Lesson Learned</label>
                                        <textarea class="form-control" name="lesson_learned"
                                            id="inputEventLessonLearned"></textarea>
                                    </div>
                                    <div class="col col-gap">
                                        <label for="inputEventSuggestions" class="form-label">Suggestions</label>
                                        <textarea class="form-control" name="suggestion"
                                            id="inputEventSuggestions"></textarea>
                                    </div>
                                </div>
                                <label for="inputBudgetUtilized" class="form-label">Budget Utilized</label>
                                <input type="text" name="budget_utilized" class="form-control" id="inputBudgetUtilized">
                                <h4>Remarks</h4>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-check form-check-inline">
                                            <input type="radio" name="remark" class="form-check-input"
                                                id="radioAccomplished1" checked>
                                            <label class="form-check-label" for="radioAccomplished1">
                                                Accomplished
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="radio" name="remark" class="form-check-input"
                                                id="radioAccomplished2">
                                            <label class="form-check-label" for="radioAccomplished2">
                                                Accomplished but did not meet the target number of members.
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php require_once '../../../components/footer-links.php'; ?>

    <!-- SPLIDE.JS -->
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>

    <script src="add-activity-page.js"></script>

    <script>
        // Output user_id to the console
        console.log("User ID:", <?php echo json_encode($user_id); ?> );
    </script>

</body>

</html>