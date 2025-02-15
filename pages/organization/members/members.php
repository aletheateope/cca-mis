<?php //require_once '../../sql/session_check.php'?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Members</title>

    <?php require_once '../../../components/header-links.php' ?>

    <link rel="stylesheet" href="memberspage.css">
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
                            data-bs-target="#add-member-modal">
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

    <div class="add-member-modal modal fade" id="add-member-modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Member</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="profile-container">
                        <img class="profile-image" src="/cca/img/blank-profile.png" alt="profile">
                        <a class="link" href="">Add Profile</a>
                    </div>
                    <h4>Personal Information</h4>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-4">
                                <input type="text" class="form-control" id="inputFirstName">
                                <label class="form-label" for="inputFirstName">First Name</label>
                            </div>
                            <div class="col-4">
                                <input type="text" class="form-control" id="inputMiddleName">
                                <label class="form-label" for="inputMiddleName">Middle Name</label>
                            </div>
                            <div class="col-4">
                                <input type="text" class="form-control" id="inputLastName">
                                <label class="form-label" for="inputLastName">Last Name</label>
                            </div>

                            <div class="col-4">
                                <label for="inputBirthday" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="inputBirthday">
                            </div>
                            <div class="col-4">
                                <label for="inputAge" class="form-label">Age</label>
                                <input type="text" class="form-control" id="inputAge">
                            </div>
                            <div class="col-4">
                                <label for="inputGender" class="form-label">Gender</label>
                                <select class="form-select" id="inputGender">
                                    <option selected>Female</option>
                                    <option>Male</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="inputAddress" class="form-label">Address</label>
                                <input type="text" class="form-control" id="inputAddress">
                            </div>
                            <div class="col-12">
                                <label for="inputContactNumber" class="form-label">Contact Number</label>
                                <input type="text" class="form-control" id="inputContactNumber">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-bs-target="#add-member-modal2"
                        data-bs-toggle="modal">Next</button>
                </div>
            </div>
        </div>
    </div>

    <div class="add-member-modal2 modal fade" id="add-member-modal2" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Member</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <h3>Academic Information</h3>
                            <div class="col-12">
                                <label for="inputContactNumber" class="form-label">Contact Number</label>
                                <input type="text" class="form-control" id="inputContactNumber">
                            </div>
                            <div class="col-6">
                                <label for="inputGender" class="form-label">Gender</label>
                                <select class="form-select" id="inputGender">
                                    <option selected>Female</option>
                                    <option>Male</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="inputGender" class="form-label">Gender</label>
                                <select class="form-select" id="inputGender">
                                    <option selected>Female</option>
                                    <option>Male</option>
                                </select>
                            </div>
                            <h3>Membership Information</h3>
                            <div class="col-6">
                                <label for="inputGender" class="form-label">Gender</label>
                                <select class="form-select" id="inputGender">
                                    <option selected>Female</option>
                                    <option>Male</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="inputGender" class="form-label">Gender</label>
                                <select class="form-select" id="inputGender">
                                    <option selected>Female</option>
                                    <option>Male</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-target="#add-member-modal"
                        data-bs-toggle="modal">Previous</button>
                    <button type="button" class="btn btn-primary">Next</button>
                </div>
            </div>
        </div>
    </div>

    <?php require_once '../../../components/footer-links.php'?>
</body>

</html>