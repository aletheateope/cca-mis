<?php
//require_once '../../sql/session_check.php';
//check_role('Organization');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Activity</title>

    <?php require_once '../../../components/header-links.php' ?>

    <!-- SPLIDE.JS -->
    <link href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css" rel="stylesheet">

    <link rel="stylesheet" href="accomplishments-page.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <?php include '../../../components/sidebar/organization/sidebar.php' ?>
            </div>
            <div class="col main-content add-activity">
                <div class="row">
                    <div class="col page-header">
                        <h1>Add Activity</h1>
                        <a href="accomplishments-page.php" class="back-button">Go Back</a>
                    </div>
                </div>
                <div class="page-body">
                    <div class="row">
                        <div class="col content">
                            <div class="row">
                                <div class="col">
                                    <div class="splide splide-1">
                                        <div class="splide__track">
                                            <ul class="splide__list">
                                                <li class="splide__slide"><img src="/cca/assets/img/16-9Size.png"
                                                        alt="">
                                                </li>
                                                <li class="splide__slide"><img src="/cca/assets/img/16-9Size.png"
                                                        alt="">
                                                </li>
                                                <li class="splide__slide"><img src="/cca/assets/img/16-9Size.png"
                                                        alt="">
                                                </li>
                                                <li class="splide__slide"><img src="/cca/assets/img/16-9Size.png"
                                                        alt="">
                                                </li>
                                                <li class="splide__slide"><img src="/cca/assets/img/blank-profile.png"
                                                        alt="">
                                                </li>
                                                <li class="splide__slide"><img src="/cca/assets/img/16-9Size.png"
                                                        alt="">
                                                </li>
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
                                    <input type="file" name="profile_img" id="inputImage" style="display: none;"
                                        accept="image/png, image/jpeg, image/jpg" multiple />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col content">
                            <h3>Activity Details</h3>
                            <p>(Optional) Select Event from the Calendar</p>
                            <select class="form-select" name="">
                                <option selected value="1">---</option>
                                <option value=""></option>
                            </select>
                            <label for="inputTitle" class="form-label">Event Title</label>
                            <input type="text" class="form-control" id="inputTitle">
                            <label for="inputDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="inputDescription"></textarea>
                            <label for="inputLocation" class="form-label">Location</label>
                            <input type="text" class="form-control" id="inputLocation">
                            <div class="row gap">
                                <div class="col">
                                    <label for="inputStartDate" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="inputStartDate">
                                </div>
                                <div class="col">
                                    <label for="inputEndDate" class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="inputEndDate">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col content">
                            <h3>Attendance</h3>
                            <label for="inputTargetMembers" class="form-label">Target Number of Members</label>
                            <input type="number" class="form-control" id="inputTargetMembers">
                            <label for="inputMembersAttended" class="form-label">Members Attended</label>
                            <input type="number" class="form-control" id="inputMembersAttended"
                                placeholder="Select participants below to start counting..." readonly>
                            <h4>Participants</h4>
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col-auto">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    id="checkSelectAll">
                                                <label class="form-check-label" for="checkSelectAll">
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
                                            <div class="row">
                                                <div class="col-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value=""
                                                            id="memberName">
                                                        <label class="form-check-label" for="memberName">
                                                            Aubrey Evangelista
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value=""
                                                            id="memberName">
                                                        <label class="form-check-label" for="memberName">
                                                            Aubrey Evangelista
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value=""
                                                            id="memberName">
                                                        <label class="form-check-label" for="memberName">
                                                            Aubrey Evangelista
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value=""
                                                            id="memberName">
                                                        <label class="form-check-label" for="memberName">
                                                            Aubrey Evangelista
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value=""
                                                            id="memberName">
                                                        <label class="form-check-label" for="memberName">
                                                            Aubrey Evangelista
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value=""
                                                            id="memberName">
                                                        <label class="form-check-label" for="memberName">
                                                            Aubrey Evangelista
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value=""
                                                            id="memberName">
                                                        <label class="form-check-label" for="memberName">
                                                            Aubrey Evangelista
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col content">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once '../../../components/footer-links.php'; ?>

    <!-- SPLIDE.JS -->
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>

    <script src="accomplishments-page.js"></script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var splide = new Splide(".splide", {
                type: 'loop',
                perPage: 3,
                height: '12rem',
                gap: '1rem',
            });
            splide.mount();
        });
    </script>

</body>

</html>