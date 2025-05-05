<?php require_once '../../../sql/base_path.php'?>

<?php
// require_once BASE_PATH . '/assets/sql/session_check.php';
// check_role('Organization');
?>

<?php include_once BASE_PATH . '/assets/sql/temporary_session.php';?>

<?php require_once 'sql/member_details.php'?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Member</title>

    <?php require_once BASE_PATH . '/assets/components/header_links.php' ?>

    <!-- SPLIDE.JS -->
    <link href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css" rel="stylesheet">

    <link rel="stylesheet" href="member-page.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <?php include BASE_PATH . '/assets/components/sidebar/organization/sidebar.php' ?>
            </div>
            <main class="col main-content">
                <div class="row page-header">
                    <div class="col">
                        <h1>Member Details</h1>
                        <?php include BASE_PATH . '/assets/components/topbar/topbar.php'?>
                        <a href="members_page.php" class="back-button">Go Back</a>
                    </div>
                </div>
                <div class="row page-body">
                    <div class="col">
                        <?php while ($personal_info_row = $result_personal_information->fetch_assoc()) { ?>
                        <div class="row">
                            <section class="col-auto container profile-card">
                                <div class="row profile">
                                    <div class="col">
                                        <img src="/cca/assets/img/blank-profile.png" alt="Profile Picture">
                                        <h3><?php echo $personal_info_row['first_name'] . ' ' . $personal_info_row['middle_name'] . ' ' . $personal_info_row['last_name']?>
                                        </h3>
                                    </div>
                                </div>
                                <div class="row recognition">
                                    <div class="col">
                                        <div class="splide">
                                            <div class="splide__track">
                                                <ul class="splide__list">
                                                    <?php while ($recognition_row = $result_recognition->fetch_assoc()) {?>
                                                    <li class="splide__slide">
                                                        <div class="row recognition-slider">
                                                            <div class="col">
                                                                <h4>"<?php echo $recognition_row['recognition']?>"
                                                                </h4>
                                                                <p><?php echo $recognition_row['event_title']?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <?php }?>
                                                </ul>
                                            </div>
                                        </div>
                                        <button class="no-style-btn" data-bs-toggle="modal"
                                            data-bs-target="#recognitionModal">View All Recognitions
                                        </button>
                                    </div>
                                </div>
                            </section>

                            <section class="col container">
                                <div class="header">
                                    <h3>Personal Information</h3>
                                    <button class="no-style-btn edit-btn"><i class="bi bi-pencil-square"></i></button>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <h5>Age</h5>
                                        <p><?php echo $personal_info_row['age']?>
                                            Years Old</p>
                                    </div>
                                    <div class="col">
                                        <h5>Date of Birth</h5>
                                        <p><?php echo date("F d, Y", strtotime($personal_info_row['birthdate'])); ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <h5>Gender</h5>
                                        <p><?php echo $personal_info_row['gender']?>
                                        </p>
                                    </div>
                                    <div class="col">
                                        <h5>Contact Number</h5>
                                        <p><?php echo $personal_info_row['mobile_number']?>
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <h5>Email</h5>
                                        <p><?php echo $personal_info_row['email']?>
                                        </p>
                                    </div>
                                    <div class="col">
                                        <h5>Address</h5>
                                        <p><?php echo $personal_info_row['address']?>
                                        </p>
                                    </div>
                                </div>
                            </section>
                        </div>
                        <?php }?>

                        <?php while ($academic_info_row = $result_academic_information->fetch_assoc()) {?>
                        <section class="row container">
                            <div class="col">
                                <div class="header">
                                    <h3>Academic Information</h3>
                                    <button class="no-style-btn edit-btn"><i class="bi bi-pencil-square"></i></button>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <h5>Student Number</h5>
                                        <p><?php echo $academic_info_row['student_number']?>
                                        </p>
                                    </div>
                                    <div class="col">
                                        <h5>College</h5>
                                        <p
                                            title="<?php echo $academic_info_row['college_name']?>">
                                            <?php echo $academic_info_row['college_abbreviation']?>
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <h5>Course</h5>
                                        <p><?php echo preg_replace('/.*\s(of|in)\s/', '', $academic_info_row['course']); ?>
                                        </p>
                                    </div>
                                    <div class="col">
                                        <h5>Year Level</h5>
                                        <p>4th Year</p>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <?php }?>

                        <?php while ($membership_organization_row = $result_membership_organization->fetch_assoc()) {?>
                        <section class="row container">
                            <div class="col">
                                <div class="header">
                                    <h3>Membership Information</h3>
                                    <button class="no-style-btn edit-btn"><i class="bi bi-pencil-square"></i></button>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <h5>Organization</h5>
                                        <p><?php echo $membership_organization_row['organization']?>
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <h5>Status</h5>
                                        <p><?php echo $membership_organization_row['status']?>
                                        </p>
                                    </div>
                                    <div class="col">
                                        <h5>State</h5>
                                        <p><?php echo $membership_organization_row['state']?>
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <h5>Date Joined</h5>
                                        <p><?php echo date("F d, Y", strtotime($membership_organization_row['date_joined']))?>
                                        </p>
                                    </div>
                                    <div class="col">
                                        <h5>Date Left</h5>
                                        <p>
                                            <?php
                                                echo $membership_organization_row['date_left']
                                                ? date("F d, Y", strtotime($membership_organization_row['date_left']))
                                                : '----';?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <?php }?>

                        <section class="row container">
                            <div class="col">
                                <div class="header">
                                    <h3>Documents</h3>
                                    <button class="no-style-btn"><i class="bi bi-plus-lg"></i></button>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="allDocument">
                                                            <label class="form-check-label" for="allDocument">
                                                                Name
                                                            </label>
                                                        </div>
                                                    </th>
                                                    <th class="text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if ($result_documents->num_rows > 0) { ?>
                                                <?php while ($document_row = $result_documents->fetch_assoc()) {?>
                                                <tr>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" id="doc-1">
                                                            <label class="form-check-label" for="doc-1">
                                                                <?php echo $document_row['file_name']?>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="action-group">
                                                            <button class="no-style-btn edit-btn">
                                                                <i class="bi bi-pencil-square"></i>
                                                            </button>
                                                            <button class="no-style-btn delete-btn">
                                                                <i class="bi bi-trash-fill"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php }?>
                                                <?php } else {?>
                                                <tr>
                                                    <td colspan="2" class="text-center"
                                                        style="color: var(--inactive-text-dark);">No rows</td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section class="row container participation">
                            <div class="col">
                                <div class="header">
                                    <h3>Participation History</h3>
                                    <button class="no-style-btn"><i class="bi bi-plus-lg"></i></button>
                                </div>
                                <div class="row participation-table">
                                    <div class="col">
                                        <table class="table" <?php if ($result_documents->num_rows == 0) {
                                            echo 'style="height: 100%;"';
                                        } ?>>
                                            <thead>
                                                <tr>
                                                    <th>Event Title</th>
                                                    <th>Date</th>
                                                    <th class="text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if ($result_participation->num_rows > 0) { ?>
                                                <?php while ($participation_row = $result_participation->fetch_assoc()) {?>
                                                <tr>
                                                    <td><?php echo $participation_row['event_title']?>
                                                    </td>
                                                    <td><?php echo date("F d, Y", strtotime($participation_row['date']))?>
                                                    </td>
                                                    <td>
                                                        <div class="action-group">
                                                            <button class="no-style-btn delete-btn">
                                                                <i class="bi bi-trash-fill"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php }?>
                                                <?php } else {?>
                                                <tr>
                                                    <td colspan="3" class="text-center align-middle"
                                                        style="color: var(--inactive-text-dark);">No rows
                                                    </td>
                                                </tr>
                                                <?php }?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
                <div class="row page-footer">
                    <div class="col">
                        <button class="no-style-btn delete-btn">Delete Member</button>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- MODALS -->
    <!-- VIEW ALL RECOGNITION -->
    <div class="modal fade" id="recognitionModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Recognitions</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Event Title</th>
                                <th>Recognition</th>
                                <th>Date</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result_all_recognition->num_rows > 0) {?>
                            <?php while ($all_recognition_row = $result_all_recognition->fetch_assoc()) {?>
                            <tr>
                                <td><?php echo $all_recognition_row['event_title']?>
                                </td>
                                <td><?php echo $all_recognition_row['recognition']?>
                                </td>
                                <td><?php echo date("F d, Y", strtotime($all_recognition_row['date']))?>
                                </td>
                                <td>
                                    <div class="action-group">
                                        <button class="no-style-btn edit-btn">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button class="no-style-btn delete-btn">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php }?>
                            <?php } else {?>
                            <tr>
                                <td colspan="4" class="text-center" style="color: var(--inactive-text-dark);">No rows
                                </td>
                            </tr>
                            <?php }?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>




    <?php require_once BASE_PATH . '/assets/components/footer_links.php'; ?>

    <!-- SPLIDE.JS -->
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>

    <script src="member-page.js"></script>

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