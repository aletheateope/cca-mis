<?php require_once '../../sql/session_check.php' ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar</title>

    <!-- OWN STYLES (SIDEBAR) -->
    <link rel="stylesheet" href="/cca/css/sidebar.css">
</head>

<body>
    <aside class="sidebar" id="sidebar">
        <ul>
            <li>
                <span class="logo">CCA</span>
                <button onclick="toggleSidebar()" class="collapse-btn" id="collapse-btn">
                    <i class="icon bi bi-chevron-double-left"></i>
                </button>
            </li>
            <li>
                <i class="profile-image"></i>
                <a
                    href=""><span><?php echo isset($_SESSION['organization_name']) ? $_SESSION['organization_name'] : 'Organization'; ?></span></a>
            </li>
            <li class="active">
                <a href="#">
                    <i class="icon bi bi-grid-1x2-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="icon bi bi-people-fill" id="members-btn"></i>
                    <span>Members</span>
                </a>
            </li>
            <li>
                <button onclick="toggleSubMenu(this)" class="dropdown-btn" id="calendar-btn">
                    <i class="icon bi bi-calendar-event-fill"></i>
                    <span>Calendar</span>
                    <i class="icon iconrotate bi bi-chevron-right"></i>
                </button>
                <ul class="sub-menu">
                    <div>
                        <li><a href="#">View Schedules</a></li>
                        <li><a href="#">Notification</a></li>
                    </div>
                </ul>
            </li>
            <li>
                <button onclick="toggleSubMenu(this)" class="dropdown-btn" id="finance-btn">
                    <i class="icon bi bi-file-text-fill"></i>
                    <span>Finance</span>
                    <i class="icon iconrotate bi bi-chevron-right"></i>
                </button>
                <ul class="sub-menu">
                    <div>
                        <li><a href="#">View Records</a></li>
                        <li><a href="#">My Records</a></li>
                    </div>
                </ul>
            </li>
            <li>
                <button onclick="toggleSubMenu(this)" class="dropdown-btn" id="accomplishments-btn">
                    <i class="icon bi bi-calendar-check-fill"></i>
                    <span>Accomplisments</span>
                    <i class="icon iconrotate bi bi-chevron-right"></i>
                </button>
                <ul class="sub-menu">
                    <div>
                        <li><a href="#">View Accomplishments</a></li>
                        <li><a href="#">My Accomplishments</a></li>
                    </div>
                </ul>
            </li>
            <li>
                <a href="#" id="logout">
                    <i class="icon bi bi-box-arrow-left"></i>
                    <span>Sign Out</span>
                </a>
            </li>
        </ul>
    </aside>
    <!-- OWN SCRIPT (SIDEBAR) -->
    <script src="/cca/components/sidebar/organization/sidebar.js"></script>
</body>

</html>