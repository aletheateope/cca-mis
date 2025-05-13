<?php require_once '../../../sql/base_path.php'?>

<?php
require_once BASE_PATH . '/assets/sql/session_check.php';
check_role(['Director', 'VPSLD']);
?>

<?php
//  include_once BASE_PATH . '/assets/sql/temporary_session.php'
?>

<?php require_once 'sql/organizations.php'?>

<?php require_once 'sql/active_members.php'?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Organization Members</title>

    <?php require_once BASE_PATH . '/assets/components/header_links.php' ?>

    <link rel="stylesheet" href="members-page.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <?php if ($_SESSION['user_role'] === 'Director') {
                    include_once BASE_PATH . '/assets/components/sidebar/director/sidebar.php';
                } elseif ($_SESSION['user_role'] === 'VPSLD') {
                    include_once BASE_PATH . '/assets/components/sidebar/vpsld/sidebar.php';
                }?>
            </div>
            <main class="col main-content">
                <div class="row page-header">
                    <div class="col">
                        <h1>Organization Members</h1>
                        <?php include_once BASE_PATH . '/assets/components/topbar/topbar.php'?>
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
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-funnel-fill"></i></i></span>
                                            <select class="form-select" id="selectMemberOrganization">
                                                <option value="0">All</option>
                                                <?php $first = true?>
                                                <?php while ($row_org = $result_organizations->fetch_assoc()) { ?>
                                                <option <?php if ($first) {
                                                    echo 'selected';
                                                    $first = false;
                                                }?>
                                                    value="<?php echo $row_org['public_key']?>">
                                                    <?php echo $row_org['organization']?>
                                                </option>
                                                <?php }?>
                                            </select>

                                            <select class="form-select" id="selectMemberState">
                                                <option value="1">All</option>
                                                <option selected value="2">Active</option>
                                                <option value="3">Inactive</option>
                                                <option value="4">Exited</option>
                                                <option value="5">Terminated</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row table-row">
                                    <div class="col">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Organization</th>
                                                    <th>Status</th>
                                                    <th>State</th>
                                                    <th>Date Joined</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while  ($row = mysqli_fetch_assoc($active_members)) {?>
                                                <tr
                                                    data-id="<?= $row['public_key']?>">
                                                    <td>
                                                        <?php echo $row ['first_name']. ' ' . $row['last_name']?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['organization']?>
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
                                                </tr>
                                                <?php }?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <h4>Total Number: <span id="totalMembers">
                                        <?php echo $count?>
                                    </span>
                                </h4>
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
                        <h4 id="memberName"></h4>
                    </div>
                </div>
                <div class="row details-container">
                    <div class="col">
                        <div class="row section">
                            <div class="col">
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
                                                <h6>Organization</h6>
                                                <p id="memberOrganization"></p>
                                            </div>
                                        </div>
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

    <?php include_once BASE_PATH . '/assets/components/topbar/topbar_modal.php'?>

    <?php require_once BASE_PATH . '/assets/components/footer_links.php'; ?>

    <script src="members-page.js"></script>

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