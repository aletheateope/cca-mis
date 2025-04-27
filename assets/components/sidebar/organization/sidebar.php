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
            class="<?= ($currentPage == 'members-page.php' || $currentPage == 'member-page.php') ? 'active' : '' ?>">
            <a href="/cca/assets/pages/organization/members/members-page.php">
                <i class="icon bi bi-people-fill" id="membersBtn"></i>
                <span>Members</span>
            </a>
        </li>
        <li
            class="<?= ($currentPage == 'calendar-page.php') ? 'active' : '' ?>">
            <a href="/cca/assets/pages/organization/calendar/calendar-page.php">
                <i class="icon bi bi-calendar-event-fill"></i>
                <span>Calendar</span>
            </a>
        </li>
        <li>
            <button onclick="toggleSubMenu(this)"
                class="dropdown-btn <?= ($currentPage == 'my-records-page.php' || $currentPage == 'add-record-page.php' || $currentPage == 'records-page.php') ? 'rotate' : '' ?>"
                id="financeBtn">
                <i class="icon bi bi-file-text-fill"></i>
                <span>Finance</span>
                <i class="icon iconrotate bi bi-chevron-right"></i>
            </button>
            <ul
                class="sub-menu <?= ($currentPage == 'my-records-page.php' || $currentPage == 'add-record-page.php' || $currentPage == 'records-page.php') ? 'show' : '' ?>">
                <div>
                    <li
                        class="<?= ($currentPage == 'records-page.php') ? 'active' : '' ?>">
                        <a href="/cca/assets/pages/public/finance/records-page.php">View Records</a>
                    </li>
                    <li
                        class="<?= ($currentPage == 'my-records-page.php' || $currentPage == 'add-record-page.php') ? 'active' : '' ?>">
                        <a href="/cca/assets/pages/organization/finance/my-records-page.php">My Records</a>
                    </li>
                </div>
            </ul>
        </li>
        <li>
            <button onclick="toggleSubMenu(this)"
                class="dropdown-btn <?= ($currentPage == 'my-accomplishments-page.php'|| $currentPage == 'add-activity-page.php' || $currentPage == 'accomplishments-page.php') ? 'rotate' : '' ?>"
                id="accomplishmentsBtn">
                <i class="icon bi bi-calendar-check-fill"></i>
                <span>Accomplisments</span>
                <i class="icon iconrotate bi bi-chevron-right"></i>
            </button>
            <ul
                class="sub-menu <?= ($currentPage == 'my-accomplishments-page.php'|| $currentPage == 'add-activity-page.php'|| $currentPage == 'accomplishments-page.php') ? 'show' : '' ?>">
                <div>
                    <li
                        class="<?= ($currentPage == 'accomplishments-page.php') ? 'active' : '' ?>">
                        <a href="/cca/assets/pages/public/accomplishments/accomplishments-page.php">View Activities</a>
                    </li>
                    <li
                        class="<?= ($currentPage == 'my-accomplishments-page.php'|| $currentPage == 'add-activity-page.php') ? 'active' : '' ?>">
                        <a href="/cca/assets/pages/organization/accomplishments/my-accomplishments-page.php">My
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
<script src="/cca/assets/components/sidebar/organization/sidebar.js"></script>