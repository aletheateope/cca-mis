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
            <img class="profile-icon" src="/cca/assets/img/CCA/cca.png" alt="">
            <button class="no-style-btn profile-btn" data-bs-toggle="modal" data-bs-target="#viewProfileModal">
                <span>VPSLD</span>
            </button>
        </li>
        <li
            class="<?= ($currentPage == 'dashboard_page.php') ? 'active' : ''?>">
            <a href="/cca/assets/pages/vpsld/dashboard/dashboard_page.php">
                <i class="icon bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li
            class="<?= ($currentPage == 'members_page.php' || $currentPage == 'member_page.php') ? 'active' : ''?>">
            <a href="/cca/assets/pages/admin/members/members_page.php">
                <i class="icon bi bi-people-fill" id="orgMembersBtn"></i>
                <span>Org. Members</span>
            </a>
        </li>
        <li
            class="<?= ($currentPage == 'calendar_page.php') ? 'active' : ''?>">
            <a href="/cca/assets/pages/vpsld/calendar/calendar_page.php">
                <i class="icon bi bi-calendar-event-fill" id="calendarBtn"></i>
                <span>Calendar</span>
            </a>
        </li>
        <li
            class="<?= ($currentPage == 'records_page.php') ? 'active' : ''?>">
            <a href="/cca/assets/pages/public/finance/records_page.php">
                <i class="icon bi bi-file-text-fill" id="financeBtn"></i>
                <span>Finance</span>
            </a>
        </li>
        <li
            class="<?= ($currentPage == 'accomplishments_page.php') ? 'active' : ''?>">
            <a href="/cca/assets/pages/public/accomplishments/accomplishments_page.php">
                <i class="icon bi bi-calendar-check-fill" id="accomplishmentsBtn"></i>
                <span>Accomplishments</span>
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
<script src="/cca/assets/components/sidebar/vpsld/sidebar.js"></script>