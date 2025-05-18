<?php require_once '../../../sql/base_path.php'?>

<?php
require_once BASE_PATH . '/assets/sql/session_check.php';
check_role('Director');
?>

<?php
// include_once BASE_PATH . '/assets/sql/temporary_session.php'
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
                <?php include_once BASE_PATH . '/assets/components/sidebar/director/sidebar.php' ?>
            </div>
            <div class="col main-content">
                <div class="row page-header">
                    <div class="col">
                        <h1>Dashboard</h1>
                        <?php include_once BASE_PATH . '/assets/components/topbar/topbar.php'?>
                    </div>
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
                                            <img src="<?php echo $row_active_members['profile'] ?>"
                                                alt="Prfile Image">
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
                            <div class="col events-wrapper">
                                <div class="row events-tabs">
                                    <div class="col">
                                        <ul class="nav nav-tabs" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                                    data-bs-target="#home-tab-pane" type="button" role="tab"
                                                    aria-controls="home-tab-pane" aria-selected="true">Today</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="profile-tab" data-bs-toggle="tab"
                                                    data-bs-target="#profile-tab-pane" type="button" role="tab"
                                                    aria-controls="profile-tab-pane" aria-selected="false">This
                                                    Week</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="contact-tab" data-bs-toggle="tab"
                                                    data-bs-target="#contact-tab-pane" type="button" role="tab"
                                                    aria-controls="contact-tab-pane"
                                                    aria-selected="false">Upcoming</button>
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
                                                                <td>10:00 AM - 4:00 PM</td>
                                                                <td>Pista ng Sining: Local Artists' Fair</td>
                                                                <td>
                                                                    <div class="organization-column">
                                                                        <img src="/cca/assets/img/organization/plmun-dulangsining.jpg"
                                                                            alt="Profile Image">
                                                                        <h4>Dulangsining</h4>
                                                                    </div>
                                                                </td>
                                                                <td>Cultural Center of the Philippines, Pasay City</td>
                                                            </tr>
                                                            <tr>
                                                                <td>1:30 PM - 5:00 PM</td>
                                                                <td>Habi at Kulay: A Textile Weaving Workshop</td>
                                                                <td>
                                                                    <div class="organization-column">
                                                                        <img src="/cca/assets/img/organization/plmun-blckmvmnt.jpg"
                                                                            alt="Profile Image">
                                                                        <h4>Blck Mvmnt</h4>
                                                                    </div>
                                                                </td>
                                                                <td>Museo Kordilyera, Baguio City</td>
                                                            </tr>
                                                            <tr>
                                                                <td>3:00 PM - 6:00 PM</td>
                                                                <td>Sayaw at Tugtugan: A Cultural Dance and Music
                                                                    Showcase</td>
                                                                <td>
                                                                    <div class="organization-column">
                                                                        <img src="/cca/assets/img/organization/plmun-chorale.jpg"
                                                                            alt="Profile Image">
                                                                        <h4>Chorale</h4>
                                                                    </div>
                                                                </td>
                                                                <td>Rizal Park Open-Air Auditorium, Manila</td>
                                                            </tr>
                                                            <tr>
                                                                <td>5:00 PM - 8:00 PM</td>
                                                                <td>Kape at Kuwento: Storytelling Night on Filipino
                                                                    Folklore</td>
                                                                <td>
                                                                    <div class="organization-column">
                                                                        <img src="/cca/assets/img/organization/plmun-kultura_teknika.jpg"
                                                                            alt="Profile Image">
                                                                        <h4>Kultura Teknika</h4>
                                                                    </div>
                                                                </td>
                                                                <td>Kultura Café, Dumaguete City</td>
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
                                                                <td>May 15</td>
                                                                <td>9:00 AM - 5:00 PM</td>
                                                                <td>Kultura at Kalikasan: Eco-Art Fair</td>
                                                                <td>
                                                                    <div class="organization-column">
                                                                        <img src="/cca/assets/img/organization/plmun-chorale.jpg"
                                                                            alt="Profile Image">
                                                                        <h4>Chorale</h4>
                                                                    </div>
                                                                </td>
                                                                <td>Ayala Triangle Gardens, Makati City</td>
                                                            </tr>
                                                            <tr>
                                                                <td>May 16</td>
                                                                <td>2:00 PM - 4:00 PM</td>
                                                                <td>Pambansang Sayaw: Filipino Folk Dance Workshop</td>
                                                                <td>
                                                                    <div class="organization-column">
                                                                        <img src="/cca/assets/img/organization/plmun-fdc.jpg"
                                                                            alt="Profile Image">
                                                                        <h4>FDC</h4>
                                                                    </div>
                                                                </td>
                                                                <td>Iloilo City Cultural Center</td>
                                                            </tr>
                                                            <tr>
                                                                <td>May 17</td>
                                                                <td>6:00 PM - 9:00 PM</td>
                                                                <td>Sine Sining: Indie Film Night</td>
                                                                <td>
                                                                    <div class="organization-column">
                                                                        <img src="/cca/assets/img/organization/plmun-dulangsining.jpg"
                                                                            alt="Profile Image">
                                                                        <h4>Dulangsining</h4>
                                                                    </div>
                                                                </td>
                                                                <td>Abreeza Mall, Davao City</td>
                                                            </tr>
                                                            <tr>
                                                                <td>May 18</td>
                                                                <td>10:00 AM - 3:00 PM</td>
                                                                <td>Pamana: Heritage Food Festival</td>
                                                                <td>
                                                                    <div class="organization-column">
                                                                        <img src="/cca/assets/img/organization/plmun-kultura_teknika.jpg"
                                                                            alt="Profile Image">
                                                                        <h4>Kultura Teknika</h4>
                                                                    </div>
                                                                </td>
                                                                <td>Plaza Independencia, Cebu City</td>
                                                            </tr>
                                                            <tr>
                                                                <td>May 19</td>
                                                                <td>1:00 PM - 5:00 PM</td>
                                                                <td>Likha: Youth Art Exhibit</td>
                                                                <td>
                                                                    <div class="organization-column">
                                                                        <img src="/cca/assets/img/organization/plmun-euphoria.jpg"
                                                                            alt="Profile Image">
                                                                        <h4>Euphoria</h4>
                                                                    </div>
                                                                </td>
                                                                <td>Museo Dabawenyo, Davao City</td>
                                                            </tr>
                                                            <tr>
                                                                <td>May 20</td>
                                                                <td>4:00 PM - 7:00 PM</td>
                                                                <td>Boses ng Bayan: Spoken Word & Music Jam</td>
                                                                <td>
                                                                    <div class="organization-column">
                                                                        <img src="/cca/assets/img/organization/plmun-euphoria.jpg"
                                                                            alt="Profile Image">
                                                                        <h4>Euphoria</h4>
                                                                    </div>
                                                                </td>
                                                                <td>The Grounds, Quezon City</td>
                                                            </tr>
                                                            <tr>
                                                                <td>May 21</td>
                                                                <td>8:00 AM - 12:00 PM</td>
                                                                <td>Kwentong Bayan: Oral History Storytelling</td>
                                                                <td>
                                                                    <div class="organization-column">
                                                                        <img src="/cca/assets/img/organization/plmun-chorale.jpg"
                                                                            alt="Profile Image">
                                                                        <h4>Chorale</h4>
                                                                    </div>
                                                                </td>
                                                                <td>Baguio City Library</td>
                                                            </tr>
                                                            <tr>
                                                                <td>May 21</td>
                                                                <td>3:00 PM - 6:00 PM</td>
                                                                <td>Himig ng Lahi: Traditional Music Ensemble</td>
                                                                <td>
                                                                    <div class="organization-column">
                                                                        <img src="/cca/assets/img/organization/plmun-fdc.jpg"
                                                                            alt="Profile Image">
                                                                        <h4>FDC</h4>
                                                                    </div>
                                                                </td>
                                                                <td>Legazpi City Amphitheater</td>
                                                            </tr>
                                                            <tr>
                                                                <td>May 21</td>
                                                                <td>5:00 PM - 9:00 PM</td>
                                                                <td>Gabi ng Pelikula: Open-Air Filipino Film Screening
                                                                </td>
                                                                <td>
                                                                    <div class="organization-column">
                                                                        <img src="/cca/assets/img/organization/plmun-euphoria.jpg"
                                                                            alt="Profile Image">
                                                                        <h4>Euphoria</h4>
                                                                    </div>
                                                                </td>
                                                                <td>Rizal Park, Manila</td>
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
                                                                <td>May 19-27, 2025</td>
                                                                <td>All Day</td>
                                                                <td>Sarung Banggi Festival</td>
                                                                <td>
                                                                    <div class="organization-column">
                                                                        <img src="/cca/assets/img/organization/plmun-kultura_teknika.jpg"
                                                                            alt="Profile Image">
                                                                        <h4>Kultura Teknika</h4>
                                                                    </div>
                                                                </td>
                                                                <td>Sto. Domingo, Albay</td>
                                                            </tr>
                                                            <tr>
                                                                <td>May 24, 2025</td>
                                                                <td>3:00 PM</td>
                                                                <td>Red Bull Dance Your Style: National Finals</td>
                                                                <td>
                                                                    <div class="organization-column">
                                                                        <img src="/cca/assets/img/organization/plmun-euphoria.jpg"
                                                                            alt="Profile Image">
                                                                        <h4>Euphoria</h4>
                                                                    </div>
                                                                </td>
                                                                <td>Ayala Center Cebu, Cebu City</td>
                                                            </tr>
                                                            <tr>
                                                                <td>May 29, 2025</td>
                                                                <td>10:00 AM - 5:00 PM</td>
                                                                <td>CCP @ the Senate: 21st Century Art Museum Exhibit
                                                                </td>
                                                                <td>
                                                                    <div class="organization-column">
                                                                        <img src="/cca/assets/img/organization/plmun-dulangsining.jpg"
                                                                            alt="Profile Image">
                                                                        <h4>Dulangsining</h4>
                                                                    </div>
                                                                </td>
                                                                <td>Senate of the Philippines, Pasay City</td>
                                                            </tr>
                                                            <tr>
                                                                <td>May 30, 2025</td>
                                                                <td>6:00 PM - 10:00 PM</td>
                                                                <td>ARTOON NETWORK: The Cartoon Art Show</td>
                                                                <td>
                                                                    <div class="organization-column">
                                                                        <img src="/cca/assets/img/organization/plmun-blckmvmnt.jpg"
                                                                            alt="Profile Image">
                                                                        <h4>Blck Mvmnt</h4>
                                                                    </div>
                                                                </td>
                                                                <td>Manila (Venue TBA)</td>
                                                            </tr>
                                                            <tr>
                                                                <td>May 30 - June 1, 2025</td>
                                                                <td>Various Times</td>
                                                                <td>AndFriends Festival</td>
                                                                <td>
                                                                    <div class="organization-column">
                                                                        <img src="/cca/assets/img/organization/plmun-fdc.jpg"
                                                                            alt="Profile Image">
                                                                        <h4>FDC</h4>
                                                                    </div>
                                                                </td>
                                                                <td>Okada Manila, Parañaque City</td>
                                                            </tr>
                                                            <tr>
                                                                <td>May 31 - June 1, 2025</td>
                                                                <td>All Day</td>
                                                                <td>Pagdiriwang Philippine Festival</td>
                                                                <td>
                                                                    <div class="organization-column">
                                                                        <img src="/cca/assets/img/organization/plmun-kultura_teknika.jpg"
                                                                            alt="Profile Image">
                                                                        <h4>Kultura Teknika</h4>
                                                                    </div>
                                                                </td>
                                                                <td>Armory Food & Event Hall, Seattle, WA</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row events-chart">
                                    <div class="col">
                                        <div class="row">
                                            <div class="col container">
                                                <div class="container-fluid">
                                                    <canvas id="chartEventApproval"></canvas>
                                                </div>
                                            </div>
                                            <div class="col container">
                                                <div class="container-fluid">
                                                    <canvas id="chartTotalEventsSubmitted"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row container">
                                            <div class="col">
                                                <div class="container-fluid">
                                                    <canvas id="chartOrganizationEvents"></canvas>
                                                </div>
                                            </div>
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
                                                        href="../calendar/calendar_page.php">Calendar page</a></span> to
                                                take action.
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="row body">
                                        <div class="col">
                                            <div class="container-fluid event-request-card">
                                                <div class="row header">
                                                    <div class="col-auto">
                                                        <img src="/cca/assets/img/organization/plmun-euphoria.jpg"
                                                            alt="Profile Image">
                                                    </div>
                                                    <div class="col">
                                                        <h4>Euphoria</h4>
                                                        <h5>March 05, 2025</h5>
                                                    </div>
                                                </div>
                                                <div class="row body">
                                                    <div class="row">
                                                        <div class="col">
                                                            <h5>Event Name</h5>
                                                            <p>Panagbenga Flower Festival</p>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col">
                                                            <h5>Location</h5>
                                                            <p>Baguio City, Benguet</p>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col">
                                                            <h5>Start Date</h5>
                                                            <P>February 1, 2025</P>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="container-fluid event-request-card">
                                                <div class="row header">
                                                    <div class="col-auto">
                                                        <img src="/cca/assets/img/organization/plmun-fdc.jpg"
                                                            alt="Profile Image">
                                                    </div>
                                                    <div class="col">
                                                        <h4>FDC</h4>
                                                        <h5>March 05, 2025</h5>
                                                    </div>
                                                </div>
                                                <div class="row body">
                                                    <div class="row">
                                                        <div class="col">
                                                            <h5>Event Name</h5>
                                                            <p>Pahiyas Festival</p>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col">
                                                            <h5>Location</h5>
                                                            <p>Lucban, Quezon</p>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col">
                                                            <h5>Start Date</h5>
                                                            <P>May 15, 2025</P>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="container-fluid event-request-card">
                                                <div class="row header">
                                                    <div class="col-auto">
                                                        <img src="/cca/assets/img/organization/plmun-dulangsining.jpg"
                                                            alt="Profile Image">
                                                    </div>
                                                    <div class="col">
                                                        <h4>Dulangsining</h4>
                                                        <h5>March 05, 2025</h5>
                                                    </div>
                                                </div>
                                                <div class="row body">
                                                    <div class="row">
                                                        <div class="col">
                                                            <h5>Event Name</h5>
                                                            <p>MassKara Festival</p>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col">
                                                            <h5>Location</h5>
                                                            <p>Bacolod City, Negros Occidental</p>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col">
                                                            <h5>Start Date</h5>
                                                            <P>October 19, 2025</P>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="container-fluid event-request-card">
                                                <div class="row header">
                                                    <div class="col-auto">
                                                        <img src="/cca/assets/img/organization/plmun-chorale.jpg"
                                                            alt="Profile Image">
                                                    </div>
                                                    <div class="col">
                                                        <h4>Chorale</h4>
                                                        <h5>March 05, 2025</h5>
                                                    </div>
                                                </div>
                                                <div class="row body">
                                                    <div class="row">
                                                        <div class="col">
                                                            <h5>Event Name</h5>
                                                            <p>Ati-Atihan Festival</p>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col">
                                                            <h5>Location</h5>
                                                            <p>Kalibo, Aklan</p>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col">
                                                            <h5>Start Date</h5>
                                                            <P>January 10, 2025</P>
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
                    <section class="container-fluid wrapper d-none">
                        <div class="row header">
                            <div class="col">
                                <h2>Finance</h2>
                            </div>
                        </div>
                        <div class="container-fluid">
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL -->
    <!-- Topbar -->
    <?php include_once BASE_PATH . '/assets/components/topbar/topbar_modal.php'?>

    <!-- VIEW PROFILE MODAL -->
    <?php include_once BASE_PATH . '/assets/components/sidebar/admin_modal.php'?>

    <!-- FOOTER LINKS -->
    <?php require_once BASE_PATH . '/assets/components/footer_links.php' ?>

    <!-- CHART.JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

    <script src="dashboard-page.js"></script>

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