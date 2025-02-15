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
                <a href=""><span>Director</span></a>
            </li>
            <li class="active">
                <a href="#">
                    <i class="icon bi bi-grid-1x2-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <button onclick="toggleSubMenu(this)" class="dropdown-btn" id="orgMembers-btn">
                    <i class="icon bi bi-people-fill"></i>
                    <span>Org. Members</span>
                    <i class="icon iconrotate bi bi-chevron-right"></i>
                </button>
                <ul class="sub-menu">
                    <div>
                        <li><a href="#">Blck Mvmnt</a></li>
                        <li><a href="#">Chorale</a></li>
                        <li><a href="#">Dulangsining</a></li>
                        <li><a href="#">Euphoria</a></li>
                        <li><a href="#">Hiraya</a></li>
                        <li><a href="#">Kultura Teknika</a></li>
                        <li><a href="#">Search</a></li>
                    </div>
                </ul>
            </li>
            <li>
                <a href="#">
                    <i class="icon bi bi-calendar-event-fill" id="calendar-btn"></i>
                    <span>Calendar</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="icon bi bi-file-text-fill" id="finance-btn"></i>
                    <span>Finance</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="icon bi bi-calendar-check-fill" id="accomplishments-btn"></i>
                    <span>Accomplishments</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="icon bi bi-person-fill" id="accounts-btn"></i>
                    <span>Accounts</span>
                </a>
            </li>
            <li>
                <a href="#" id="logout">
                    <i class="icon bi bi-box-arrow-left" id="logout"></i>
                    <span>Sign Out</span>
                </a>
            </li>
        </ul>
    </aside>

    <!-- OWN SCRIPT (SIDEBAR)-->
    <script src="/cca/components/sidebar/director/sidebar.js"></script>
</body>

</html>