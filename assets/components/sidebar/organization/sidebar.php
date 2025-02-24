<!-- Get the current page name -->
<?php $currentPage = basename($_SERVER['PHP_SELF']);?>

<!-- OWN STYLES (SIDEBAR) -->
<link rel="stylesheet" href="/cca/assets/css/sidebar.css">

<aside class="sidebar" id="sidebar">
    <ul>
        <li>
            <span class="logo">CCA</span>
            <button onclick="toggleSidebar()" class="collapse-btn" id="collapse-btn">
                <i class="icon bi bi-chevron-double-left"></i>
            </button>
        </li>
        <li>
            <i class="profile-icon"></i>
            <a href="">
                <span>
                    <?php echo isset($_SESSION['organization_name']) ? $_SESSION['organization_name'] : 'Organization'; ?>
                </span>
            </a>
        </li>
        <li
            class="<?= ($currentPage == 'dashboard-page.php') ? 'active' : '' ?>">
            <a href="/cca/assets/pages/organization/dashboard-page.php">
                <i class="icon bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li
            class="<?= ($currentPage == 'members-page.php') ? 'active' : '' ?>">
            <a href="/cca/assets/pages/organization/members/members-page.php">
                <i class="icon bi bi-people-fill" id="membersBtn"></i>
                <span>Members</span>
            </a>
        </li>
        <li>
            <button onclick="toggleSubMenu(this)" class="dropdown-btn" id="calendarBtn">
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
            <button onclick="toggleSubMenu(this)" class="dropdown-btn" id="financeBtn">
                <i class="icon bi bi-file-text-fill"></i>
                <span>Finance</span>
                <i class="icon iconrotate bi bi-chevron-right"></i>
            </button>
            <ul class="sub-menu">
                <div>
                    <li> <a href="#">View Records</a></li>
                    <li
                        class="<?= ($currentPage == 'finance-page.php') ? 'active' : '' ?>">
                        <a href="/cca/assets/pages/organization/finance/finance-page.php">My Records</a>
                    </li>
                </div>
            </ul>
        </li>
        <li>
            <button onclick="toggleSubMenu(this)" class="dropdown-btn" id="accomplishmentsBtn">
                <i class="icon bi bi-calendar-check-fill"></i>
                <span>Accomplisments</span>
                <i class="icon iconrotate bi bi-chevron-right"></i>
            </button>
            <ul class="sub-menu">
                <div>
                    <li><a href="#">View Accomplishments</a></li>
                    <li
                        class="<?= ($currentPage == 'accomplishments-page.php'|| $currentPage == 'add-activity-page.php') ? 'active' : '' ?>">
                        <a href="/cca/assets/pages/organization/accomplishments/accomplishments-page.php">My
                            Accomplishments</a>
                    </li>
                </div>
            </ul>
        </li>
        <li>
            <a href="#" id="logoutBtn">
                <i class="icon bi bi-box-arrow-left"></i>
                <span>Sign Out</span>
            </a>
        </li>
    </ul>
</aside>
<!-- OWN SCRIPT (SIDEBAR) -->
<script src="/cca/assets/components/sidebar/organization/sidebar.js"></script>