<?php require_once '../../../sql/base_path.php'?>

<?php
//require_once '../../sql/session_check.php';
//check_role('Director');
?>

<?php require_once 'sql/display_data.php'?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>

    <?php require_once BASE_PATH . '/assets/components/header_links.php' ?>

    <link rel="stylesheet" href="dashboard-page.css">

</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto">
                <?php include BASE_PATH . '/assets/components/sidebar/director/sidebar.php' ?>
            </div>
            <div class="col main-content">
                <div class="row page-header">
                    <div class="col">
                        <h1>Dashboard</h1>
                        <?php include BASE_PATH . '/assets/components/topbar/topbar.php'?>
                    </div>
                    <!-- <div class="col-auto">
                        <?php include BASE_PATH . '/assets/components/topbar/topbar.php'?>
                </div> -->
            </div>
            <div class="container-fluid page-body">
                <section class="container-fluid wrapper active-members">
                    <div class="row header">
                        <div class="col">
                            <h2>Active Members</h2>
                        </div>
                    </div>
                    <div class="grid">
                        <?php while ($row_active_members = $result_active_members->fetch_assoc()) { ?>
                        <div class="container">
                            <div class="row">
                                <div class="col">
                                    <a
                                        href="../members/members_page.php?organization=<?php echo $row_active_members['name'] ?>">
                                        <img src="/cca/assets/img/blank-profile.png" alt="">
                                        <h3><?php echo $row_active_members['name'] ?>
                                        </h3>
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <i class="bi bi-people-fill"></i>
                                    <p><?php echo $row_active_members['member_count'] ?>
                                    </p>
                                </div>
                            </div>
                            <h5>No new members this month.</h5>
                        </div>
                        <?php } ?>
                    </div>
                </section>
                <section class="container-fluid wrapper events">
                    <div class="row header">
                        <div class="col">
                            <h2>Events</h2>
                        </div>
                    </div>
                    <div class="row body">
                        <div class="col events-tabs">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                        data-bs-target="#home-tab-pane" type="button" role="tab"
                                        aria-controls="home-tab-pane" aria-selected="true">Today</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab"
                                        data-bs-target="#profile-tab-pane" type="button" role="tab"
                                        aria-controls="profile-tab-pane" aria-selected="false">This Week</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="contact-tab" data-bs-toggle="tab"
                                        data-bs-target="#contact-tab-pane" type="button" role="tab"
                                        aria-controls="contact-tab-pane" aria-selected="false">Upcoming</button>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel"
                                    aria-labelledby="home-tab" tabindex="0">
                                    <div class="container-fluid">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Time</th>
                                                    <th>Title</th>
                                                    <th>Organization</th>
                                                    <th>Location</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel"
                                    aria-labelledby="profile-tab" tabindex="0">
                                    <div class="container-fluid">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Time</th>
                                                    <th>Title</th>
                                                    <th>Organization</th>
                                                    <th>Location</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel"
                                    aria-labelledby="contact-tab" tabindex="0">
                                    <div class="container-fluid">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Time</th>
                                                    <th>Title</th>
                                                    <th>Organization</th>
                                                    <th>Location</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                    <td>test</td>
                                                </tr>
                                                <tr>
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
                        <div class="col-auto event-approvals container">
                            <div class="container-fluid">
                                <div class="row header">
                                    <div class="col">
                                        <h3>Approvals</h3>
                                        <h5 class="text-center">
                                            You have a pending event that requires approval. Go to the <span><a
                                                    href="">Calendar page</a></span> to
                                            take action.
                                        </h5>
                                    </div>
                                </div>
                                <div class="row body">
                                    <div class="col">
                                        <div class="container-fluid event-request-card">
                                            <div class="row header">
                                                <div class="col-auto">
                                                    <img src="/cca/assets/img/blank-profile.png" alt="">
                                                </div>
                                                <div class="col">
                                                    <h4>Blck Mvmnt</h4>
                                                    <h5>March 05, 2025</h5>
                                                </div>
                                            </div>
                                            <div class="row body">
                                                <div class="row">
                                                    <div class="col">
                                                        <h5>Event Name</h5>
                                                        <p>KulturaFest</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col">
                                                        <h5>Location</h5>
                                                        <p>Vigan City, Ilocos Sur</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col">
                                                        <h5>Start Date</h5>
                                                        <P>June 01, 2025</P>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="container-fluid event-request-card">
                                            <div class="row header">
                                                <div class="col-auto">
                                                    <img src="/cca/assets/img/blank-profile.png" alt="">
                                                </div>
                                                <div class="col">
                                                    <h4>Blck Mvmnt</h4>
                                                    <h5>March 05, 2025</h5>
                                                </div>
                                            </div>
                                            <div class="row body">
                                                <div class="row">
                                                    <div class="col">
                                                        <h5>Event Name</h5>
                                                        <p>KulturaFest</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col">
                                                        <h5>Location</h5>
                                                        <p>Vigan City, Ilocos Sur</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col">
                                                        <h5>Start Date</h5>
                                                        <P>June 01, 2025</P>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="container-fluid event-request-card">
                                            <div class="row header">
                                                <div class="col-auto">
                                                    <img src="/cca/assets/img/blank-profile.png" alt="">
                                                </div>
                                                <div class="col">
                                                    <h4>Blck Mvmnt</h4>
                                                    <h5>March 05, 2025</h5>
                                                </div>
                                            </div>
                                            <div class="row body">
                                                <div class="row">
                                                    <div class="col">
                                                        <h5>Event Name</h5>
                                                        <p>KulturaFest</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col">
                                                        <h5>Location</h5>
                                                        <p>Vigan City, Ilocos Sur</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col">
                                                        <h5>Start Date</h5>
                                                        <P>June 01, 2025</P>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="container-fluid event-request-card">
                                            <div class="row header">
                                                <div class="col-auto">
                                                    <img src="/cca/assets/img/blank-profile.png" alt="">
                                                </div>
                                                <div class="col">
                                                    <h4>Blck Mvmnt</h4>
                                                    <h5>March 05, 2025</h5>
                                                </div>
                                            </div>
                                            <div class="row body">
                                                <div class="row">
                                                    <div class="col">
                                                        <h5>Event Name</h5>
                                                        <p>KulturaFest</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col">
                                                        <h5>Location</h5>
                                                        <p>Vigan City, Ilocos Sur</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col">
                                                        <h5>Start Date</h5>
                                                        <P>June 01, 2025</P>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="container-fluid event-request-card">
                                            <div class="row header">
                                                <div class="col-auto">
                                                    <img src="/cca/assets/img/blank-profile.png" alt="">
                                                </div>
                                                <div class="col">
                                                    <h4>Blck Mvmnt</h4>
                                                    <h5>March 05, 2025</h5>
                                                </div>
                                            </div>
                                            <div class="row body">
                                                <div class="row">
                                                    <div class="col">
                                                        <h5>Event Name</h5>
                                                        <p>KulturaFest</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col">
                                                        <h5>Location</h5>
                                                        <p>Vigan City, Ilocos Sur</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col">
                                                        <h5>Start Date</h5>
                                                        <P>June 01, 2025</P>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    </div>
    <?php require_once BASE_PATH . '/assets/components/footer_links.php' ?>

    <script>
        // Retrieve the session role from PHP
        var sessionID =
            "<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'undefined'; ?>";

        var sessionRole =
            "<?php echo isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'undefined'; ?>";

        console.log("User ID: " + sessionID);
        console.log("User Role: " + sessionRole);
    </script>
</body>

</html>