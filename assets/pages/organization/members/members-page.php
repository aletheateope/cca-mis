<?php
//require_once '../../../sql/session_check.php';
//check_role('Organization');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Members</title>

    <?php require_once '../../../components/header-links.php' ?>

    <link rel="stylesheet" href="members-page.css">
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
                        <h1>Members</h1>
                        <button type="button" class="btn btn-primary add-member-btn" data-bs-toggle="modal"
                            data-bs-target="#addMemberModal">
                            <i class="bi bi-plus"></i> Add Member
                        </button>
                    </div>
                </div>
                <div class="page-body">
                    <div class="row">
                        <div class="col content">
                            <table class="table members-table">
                                <thead>
                                    <tr>
                                        <th>Box</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>State</th>
                                        <th>Date Joined</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>test</td>
                                        <td>test</td>
                                        <td>test</td>
                                        <td>test</td>
                                        <td>test</td>
                                    </tr>
                                </tbody>
                            </table>
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
                            <i class="bi bi-x removeProfile" id="removeProfile" onclick="removeProfile()"></i>
                            <a class="link" href="" id="addProfileButton">Add Profile</a>
                            <input type="file" name="profile_img" id="inputProfile" style="display: none;"
                                accept="image/png, image/jpeg" />
                        </div>
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col">
                                    <h3>Personal Information</h3>
                                </div>
                            </div>
                            <div class="row">
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
                                        onchange="calculateAge()" required />
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
                                    <input type="file" name="files[]" id="uploadStudentDocument"
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

    <?php require_once '../../../components/footer-links.php'?>

    <script src="members-page.js"></script>
</body>

</html>