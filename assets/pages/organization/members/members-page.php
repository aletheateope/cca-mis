<?php require_once '../../../sql/base-path.php'?>

<?php
//require_once BASE_PATH . '/assets/sql/session_check.php';
//check_role('Organization');
?>

<?php include_once BASE_PATH . '/assets/sql/temporary_session.php'?>

<?php require_once 'sql/active-members.php'?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Members</title>

    <?php require_once BASE_PATH . '/assets/components/header-links.php' ?>

    <!-- FILEPOND -->
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />

    <link rel="stylesheet" href="members-page.css">
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
                        <h1>Members</h1>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#addMemberModal">
                            <i class="bi bi-plus"></i> Add Member
                        </button>
                    </div>
                </div>
                <div class="row page-body">
                    <div class="col">
                        <section class="row container members">
                            <div class="col">
                                <div class="row header">
                                    <div class="col">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                                            <input type="text" class="form-control" placeholder="Search..."
                                                id="memberSearch">
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <select class="form-select" id="selectMemberState">
                                            <option value="1">All</option>
                                            <option selected value="2">Active</option>
                                            <option value="3">Inactive</option>
                                            <option value="4">Exited</option>
                                            <option value="5">Terminated</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row table-row">
                                    <div class="col">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" value=""
                                                                id="selectAll">
                                                            <label class="form-check-label" for="selectAll">
                                                                Name
                                                            </label>
                                                        </div>
                                                    </th>
                                                    <th>Status</th>
                                                    <th>State</th>
                                                    <th>Date Joined</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $member_index = 1?>
                                                <?php while  ($row = mysqli_fetch_assoc($active_members)) {?>
                                                <tr
                                                    data-id="<?= $row['public_key']?>">
                                                    <td>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="member-<?= $member_index?>">
                                                            <label class="form-check-label"
                                                                for="member-<?= $member_index?>">
                                                                <?php echo $row ['first_name']. ' ' . $row['last_name'];?>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['status']?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['state']?>
                                                    </td>
                                                    <td>
                                                        <?php echo date('F d, Y', strtotime($row['date_joined']))?>
                                                    </td>
                                                    <td class="actions-column">
                                                        <div class="actions">
                                                            <button class="no-style-btn edit-btn" title="Edit">
                                                                <i class="bi bi-pencil-square"></i>
                                                            </button>
                                                            <button class="no-style-btn delete-btn" title="Delete">
                                                                <i class="bi bi-trash-fill"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php $member_index++;?>
                                                <?php }?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <h4>Total Number: <span id="totalMembers">3942</span></h4>
                            </div>
                        </section>
                    </div>
                </div>
            </main>

            <div class="col-auto member-info-panel">
                <div class="header">
                    <h2>Member Information</h2>
                    <button class="no-style-btn" id="closePanelBtn" title="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="row profile-container">
                    <div class="col">
                        <img src="/cca/assets/img/blank-profile.png" alt="">
                        <a href class="change-profile">Change Profile</a>
                    </div>
                </div>
                <div class="row details-container">
                    <div class="col">
                        <div class="row section">
                            <div class="col">
                                <div class="row">
                                    <div class="col">
                                        <h6>Name</h6>
                                        <p id="memberName"></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <h6>Age</h6>
                                        <p id="memberAge"></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <h6>Date of Birth</h6>
                                        <p id="memberDob"></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <h6>Gender</h6>
                                        <p id="memberGender"></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <h6>Contact Number</h6>
                                        <p id="memberContact"></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <h6>Email</h6>
                                        <p id="memberEmail"></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <h6>Address</h6>
                                        <p id="memberAddress"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row section">
                            <div class="col">
                                <div class="row header">
                                    <div class="col">
                                        <h3>Academic Information</h3>
                                    </div>
                                </div>
                                <div class="row wrapper">
                                    <div class="col">
                                        <div class="row">
                                            <div class="col">
                                                <h6>Student Number</h6>
                                                <p id="memberStudentNumber"></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <h6>Course</h6>
                                                <p id="memberCourse"></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <h6>Year Level</h6>
                                                <p id="memberYearLevel"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row section">
                            <div class="col">
                                <div class="row header">
                                    <div class="col">
                                        <h3>Membership Information</h3>
                                    </div>
                                </div>
                                <div class="row wrapper">
                                    <div class="col">
                                        <div class="row">
                                            <div class="col">
                                                <h6>Status</h6>
                                                <p id="memberStatus"></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <h6>State</h6>
                                                <p id="memberState"></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <h6>Date Joined</h6>
                                                <p id="memberDateJoined"></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <h6>Date Left</h6>
                                                <p id="memberDateLeft"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row more-link">
                            <div class="col">
                                <a href="#" id="memberFullDetailsLink">See More Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <form id="addMemberForm" enctype="multipart/form-data">
        <div class="add-member-modal modal fade" id="addMemberModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Add Member</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="profile-container">
                            <img class="profile-image" src="/cca/assets/img/blank-profile.png" alt="profile"
                                id="blank-profile">
                            <a class="link removeProfile" href id="removeProfile">Remove Profile</a>
                            <a class="link" href id="addProfileButton">Add Profile</a>
                            <input type="file" name="profile_img" id="inputProfile" style="display: none;"
                                accept="image/png, image/jpeg" />
                        </div>
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col">
                                    <h3>Personal Information</h3>
                                </div>
                            </div>
                            <div class="row name">
                                <div class="col">
                                    <input name="first_name" type="text" class="form-control" id="inputFirstName"
                                        required>
                                    <label class="form-label" for="inputFirstName">First Name</label>
                                </div>
                                <div class="col">
                                    <input name="middle_name" type="text" class="form-control" id="inputMiddleName"
                                        required>
                                    <label class="form-label" for="inputMiddleName">Middle Name</label>
                                </div>
                                <div class="col">
                                    <input name="last_name" type="text" class="form-control" id="inputLastName"
                                        required />
                                    <label class="form-label" for="inputLastName">Last Name</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="inputBirthday" class="form-label">Date of Birth</label>
                                    <input type="date" name="birthdate" class="form-control" id="inputBirthday"
                                        required />
                                </div>
                                <div class="col">
                                    <label for="inputAge" class="form-label">Age</label>
                                    <input type="number" name="age" class="form-control" id="inputAge" min="0"
                                        required />
                                </div>
                                <div class="col">
                                    <label for="inputGender" class="form-label">Gender</label>
                                    <select name="gender" class="form-select" id="inputGender">
                                        <option selected>Female</option>
                                        <option>Male</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="inputContactNumber" class="form-label">Contact Number</label>
                                    <input type="text" name="contact_number" class="form-control"
                                        id="inputContactNumber" required>
                                </div>
                                <div class="col">
                                    <label for="inputEmail" class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" id="inputEmail" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label for="inputAddress" class="form-label">Address</label>
                                    <input type="text" name="address" class="form-control" id="inputAddress" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="nextButton" data-bs-target="#addMemberModal2"
                            data-bs-toggle="modal" disabled>Next</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="add-member-modal modal fade" id="addMemberModal2" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="memberName">Add Member</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col">
                                    <h3>Academic Information</h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label for="inputStudentNumber" class="form-label">Student Number</label>
                                    <input type="number" name="student_number" class="form-control"
                                        id="inputStudentNumber" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="inputCourse" class="form-label">Course</label>
                                    <select class="form-select" name="program" id="inputCourse">
                                        <option selected value="1">BSA - Bachelor of Science in Accountancy</option>
                                        <option value="2">BAComm - Bachelor of Arts in Communication</option>
                                        <option value="3">BSPsy - Bachelor of Science in Psychology</option>
                                        <option value="4">BSBA - Bachelor of Science in Business Administration</option>
                                        <option value="5">BSCRIM - Bachelor of Science in Criminology</option>
                                        <option value="6">BSCS - Bachelor of Science in Computer Science</option>
                                        <option value="7">BSIT - Bachelor of Science in Information Technology</option>
                                        <option value="8">ACT - Associate in Computer Technology</option>
                                        <option value="9">MD - Doctor of Medicine</option>
                                        <option value="10">BEED - Bachelor of Elementary Education</option>
                                        <option value="11">BSEd - Bachelor of Secondary Education</option>
                                        <option value="12">BPA - Bachelor of Public Administration</option>
                                        <option value="13">BAPolSci - Bachelor of Arts in Political Science</option>
                                        <option value="14">BSSW - Bachelor of Science in Social Work</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="inputYearLevel" class="form-label">Year Level</label>
                                    <select class="form-select" name="year_level" id="inputYearLevel">
                                        <option selected>1st Year</option>
                                        <option>2nd Year</option>
                                        <option>3rd Year</option>
                                        <option>4th Year</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <h3>Membership Information</h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="inputStatus" class="form-label">Status</label>
                                    <select class="form-select" name="status" id="inputStatus">
                                        <option selected>Trainee</option>
                                        <option>Junior</option>
                                        <option>Senior</option>
                                        <option>Alumni</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="inputState" class="form-label">State</label>
                                    <select class="form-select" name="state" id="inputState">
                                        <option selected>Active</option>
                                        <option>Inactive</option>
                                        <option>Exited</option>
                                        <option>Terminated</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="inputDateJoined" class="form-label">Date Joined</label>
                                    <input type="date" name="date_joined" class="form-control" id="inputDateJoined" />
                                </div>
                                <div class="col">
                                    <label for="inputDateLeft" class="form-label">Date Left</label>
                                    <input type="date" name="date_left" class="form-control" id="inputDateLeft"
                                        disabled />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 px-5">
                                    <input type="file" name="document[]" id="uploadStudentDocument"
                                        accept="image/png, image/jpeg, application/pdf" multiple />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-target="#addMemberModal"
                            data-bs-toggle="modal">Previous</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <?php require_once BASE_PATH . '/assets/components/footer-links.php'?>

    <!-- FILEPOND -->
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js">
    </script>
    <script src="https://unpkg.com/filepond-plugin-file-rename/dist/filepond-plugin-file-rename.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>

    <script type="module" src="members-page.js"></script>

    <script>
        console.log(
            "User ID:", <?php echo json_encode($_SESSION['user_id']); ?>
        );
    </script>
</body>

</html>