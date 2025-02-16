<?php //require_once '../../sql/session_check.php'?>

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
                            <span><i class="bi bi-plus"></i> Add Member</span>
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col page-content">
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
                        <i class="bi bi-x removeProfile"></i>
                        <a class="link" href="#" onclick="document.getElementById('inputProfile').click()">Add
                            Profile</a>
                        <input type="file" id="inputProfile" style="display: none;"
                            accept="image/png, image/jpeg, image/jpg" />
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col">
                                <h3>Personal Information</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <input type="text" class="form-control" id="inputFirstName">
                                <label class="form-label" for="inputFirstName">First Name</label>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" id="inputMiddleName">
                                <label class="form-label" for="inputMiddleName">Middle Name</label>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" id="inputLastName" />
                                <label class="form-label" for="inputLastName">Last Name</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="inputBirthday" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="inputBirthday" onchange="calculateAge()" />
                            </div>
                            <div class="col">
                                <label for="inputAge" class="form-label">Age</label>
                                <input type="number" class="form-control" id="inputAge" min="0" />
                            </div>
                            <div class="col">
                                <label for="inputGender" class="form-label">Gender</label>
                                <select class="form-select" id="inputGender">
                                    <option selected>Female</option>
                                    <option>Male</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label for="inputAddress" class="form-label">Address</label>
                                <input type="text" class="form-control" id="inputAddress">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label for="inputContactNumber" class="form-label">Contact Number</label>
                                <input type="text" class="form-control" id="inputContactNumber">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="nextButton" data-bs-target="#addMemberModal2"
                        data-bs-toggle="modal" disabled>Next</button>
                </div>
            </div>
        </div>
    </div>

    <div class="add-member-modal modal fade" id="addMemberModal2" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Add Member</h1>
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
                                <input type="number" class="form-control" id="inputStudentNumber">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="inputCourse" class="form-label">Course</label>
                                <select class="form-select" id="inputCourse">
                                    <option selected>BSA - Bachelor of Science in Accountancy</option>
                                    <option>BAComm - Bachelor of Arts in Communication</option>
                                    <option>BSPsy - Bachelor of Science in Psychology</option>
                                    <option>BSBA - Bachelor of Science in Business Administration</option>
                                    <option>BSCRIM - Bachelor of Science in Criminology</option>
                                    <option>BSCS - Bachelor of Science in Computer Science</option>
                                    <option>BSIT - Bachelor of Science in Information Technology</option>
                                    <option>ACT - Associate in Computer Technology</option>
                                    <option>MD - Doctor of Medicine</option>
                                    <option>BEED - Bachelor of Elementary Education</option>
                                    <option>BSEd - Bachelor of Secondary Education</option>
                                    <option>BPA - Bachelor of Public Administration</option>
                                    <option>BAPolSci - Bachelor of Arts in Political Science</option>
                                    <option>BSSW - Bachelor of Science in Social Work</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="inputYearLevel" class="form-label">Year Level</label>
                                <select class="form-select" id="inputYearLevel">
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
                                <select class="form-select" id="inputStatus">
                                    <option selected>Trainee</option>
                                    <option>Junior</option>
                                    <option>Senior</option>
                                    <option>Alumni</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="inputState" class="form-label">State</label>
                                <select class="form-select" id="inputState">
                                    <option selected>Active</option>
                                    <option>Inactive</option>
                                    <option>Exited</option>
                                    <option>Terminated</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 px-5">
                                <input type="file" id="inputStudentDocuments" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-target="#addMemberModal"
                        data-bs-toggle="modal">Previous</button>
                    <button type="button" class="btn btn-primary">Next</button>
                </div>
            </div>
        </div>
    </div>

    <?php require_once '../../../components/footer-links.php'?>

    <script src="/cca/assets/components/cleave.js"></script>

    <script src="/cca/assets/pages/organization/members/members-page.js"></script>

</body>

</html>