<?php include_once BASE_PATH . "/assets/sql/org_profile.php"?>

<!-- Get the current page name -->
<?php $currentPage = basename($_SERVER['PHP_SELF']);?>

<!-- OWN STYLES (SIDEBAR) -->
<link rel="stylesheet" href="/cca/assets/components/sidebar/sidebar.css">

<aside class="sidebar" id="sidebar">
    <ul>
        <li>
            <span class="logo">CCA</span>
            <button onclick="toggleSidebar()" class="collapse-btn" id="collapse-btn">
                <i class="icon bi bi-chevron-double-left"></i>
            </button>
        </li>
        <li>
            <button class="no-style-btn profile-btn" data-bs-toggle="modal" data-bs-target="#viewProfileModal">
                <img class="profile-icon"
                    src="<?php echo $imagePath; ?>"
                    alt="Profile Icon">
                <span>
                    <?php echo isset($_SESSION['organization_name']) ? $_SESSION['organization_name'] : 'Organization'; ?>
                </span>
            </button>
        </li>
        <li
            class="<?= ($currentPage == 'dashboard_page.php') ? 'active' : '' ?>">
            <a href="/cca/assets/pages/organization/dashboard/dashboard_page.php">
                <i class="icon bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li
            class="<?= ($currentPage == 'members_page.php' || $currentPage == 'member_page.php') ? 'active' : '' ?>">
            <a href="/cca/assets/pages/organization/members/members_page.php">
                <i class="icon bi bi-people-fill" id="membersBtn"></i>
                <span>Members</span>
            </a>
        </li>
        <li
            class="<?= ($currentPage == 'calendar_page.php') ? 'active' : '' ?>">
            <a href="/cca/assets/pages/organization/calendar/calendar_page.php">
                <i class="icon bi bi-calendar-event-fill"></i>
                <span>Calendar</span>
            </a>
        </li>
        <li>
            <button onclick="toggleSubMenu(this)"
                class="dropdown-btn <?= ($currentPage == 'my_records_page.php' || $currentPage == 'add_record_page.php' || $currentPage == 'records_page.php') ? 'rotate' : '' ?>"
                id="financeBtn">
                <i class="icon bi bi-file-text-fill"></i>
                <span>Finance</span>
                <i class="icon iconrotate bi bi-chevron-right"></i>
            </button>
            <ul
                class="sub-menu <?= ($currentPage == 'my_records_page.php' || $currentPage == 'add_record_page.php' || $currentPage == 'records_page.php') ? 'show' : '' ?>">
                <div>
                    <li
                        class="<?= ($currentPage == 'records_page.php') ? 'active' : '' ?>">
                        <a href="/cca/assets/pages/public/finance/records_page.php">View Records</a>
                    </li>
                    <li
                        class="<?= ($currentPage == 'my_records_page.php' || $currentPage == 'add_record_page.php') ? 'active' : '' ?>">
                        <a href="/cca/assets/pages/organization/finance/my_records_page.php">My Records</a>
                    </li>
                </div>
            </ul>
        </li>
        <li>
            <button onclick="toggleSubMenu(this)"
                class="dropdown-btn <?= ($currentPage == 'my_accomplishments_page.php'|| $currentPage == 'add_activity_page.php' || $currentPage == 'accomplishments_page.php') ? 'rotate' : '' ?>"
                id="accomplishmentsBtn">
                <i class="icon bi bi-calendar-check-fill"></i>
                <span>Accomplisments</span>
                <i class="icon iconrotate bi bi-chevron-right"></i>
            </button>
            <ul
                class="sub-menu <?= ($currentPage == 'my_accomplishments_page.php'|| $currentPage == 'add_activity_page.php'|| $currentPage == 'accomplishments_page.php') ? 'show' : '' ?>">
                <div>
                    <li
                        class="<?= ($currentPage == 'accomplishments_page.php') ? 'active' : '' ?>">
                        <a href="/cca/assets/pages/public/accomplishments/accomplishments_page.php">View Activities</a>
                    </li>
                    <li
                        class="<?= ($currentPage == 'my_accomplishments_page.php'|| $currentPage == 'add_activity_page.php') ? 'active' : '' ?>">
                        <a href="/cca/assets/pages/organization/accomplishments/my_accomplishments_page.php">My
                            Activities</a>
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
<script src="/cca/assets/components/sidebar/sidebar.js"></script>
<script src="/cca/assets/components/sidebar/organization/orgSidebar.js"></script>