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
            <a href=""><span>Director</span></a>
        </li>
        <li
            class="<?= ($currentPage == 'dashboard-page.php') ? 'active' : '' ?>">
            <a href="/cca/assets/pages/director/dashboard-page.php">
                <i class="icon bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li
            class="<?= ($currentPage == 'members-page.php') ? 'active' : '' ?>">
            <a href="/cca/assets/pages/director/members/members-page.php">
                <i class="icon bi bi-people-fill" id="orgMembersBtn"></i>
                <span>Org. Members</span>
            </a>
        </li>
        <li
            class="<?= ($currentPage == 'calendar-page.php') ? 'active' : '' ?>">
            <a href="/cca/assets//pages/director/calendar/calendar-page.php">
                <i class="icon bi bi-calendar-event-fill" id="calendarBtn"></i>
                <span>Calendar</span>
            </a>
        </li>
        <li
            class="<?= ($currentPage == 'records-page.php') ? 'active' : '' ?>">
            <a href="/cca/assets/pages/public/finance/records-page.php">
                <i class="icon bi bi-file-text-fill" id="financeBtn"></i>
                <span>Finance</span>
            </a>
        </li>
        <li
            class="<?= ($currentPage == 'accomplishments-page.php') ? 'active' : '' ?>">
            <a href="/cca/assets/pages/public/accomplishments/accomplishments-page.php">
                <i class="icon bi bi-calendar-check-fill" id="accomplishmentsBtn"></i>
                <span>Accomplishments</span>
            </a>
        </li>
        <li>
            <a href="#">
                <i class="icon bi bi-person-fill" id="accountsBtn"></i>
                <span>Accounts</span>
            </a>
        </li>
        <li>
            <a href="#" id="logoutBtn">
                <i class="icon bi bi-box-arrow-left" id="logout"></i>
                <span>Sign Out</span>
            </a>
        </li>
    </ul>
</aside>

<!-- OWN SCRIPT (SIDEBAR)-->
<script src="/cca/assets/components/sidebar/director/sidebar.js"></script>