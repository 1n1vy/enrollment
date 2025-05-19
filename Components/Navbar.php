<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment System</title>
    <!-- Load Google Fonts Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Load Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.1/css/boxicons.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="../styles.css"> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar">
        <!-- Hamburger Button Icon on the left side -->
        <div class="hamburger-icon p-4 cursor-pointer" id="hamburgerIcon">
            <i class="bx bx-menu"></i> <!-- Hamburger icon -->
        </div>
        <div class="logo_item">
            <img src="http://localhost/capst/images/Logo.png" alt=""> ENROLLMENT SYSTEM - SCHOOL SYSTEM
        </div>


    </nav>

    <!-- Sidebar Header Container (Sticky Header) -->
    <div class="sidebar-header-container" id="sidebarHeaderContainer">
        <div class="sidebar-header">

            <!-- Collapse Toggle -->
            <div class="sidebar-collapse-toggle" id="collapseToggle">
                <i class="bx bx-arrow-from-right" id="collapseIcon"></i>
            </div>
        </div>
    </div>
    <div class="sidebar" id="sidebar">
        <!-- Sidebar header (search bar and collapse button) -->


        <!-- Sidebar navigation -->
        <ul class="nav_list">
            <!-- 1. Dashboard -->
            <li>
                <a href="http://localhost/capst/welcome.php" class="no-chevron" title="Dashboard"><i class="bx bx-home"></i><span>Dashboard</span></a>
            </li>

            <!-- 2. Rooms Monitoring -->
            <li>
                <a href="#" class="dropdown-toggle" title="Room Monitoring">
                    <i class="bx bx-building-house"></i><span>Room Monitoring</span>
                    <i class="bx bx-chevron-right dropdown-icon"></i>
                </a>
                <ul class="submenu">
                    <li><a href="http://localhost/capst/RoomMonitoring/RoomDirectory/main.php">Room Directory and Creator</a></li>
                    <!-- <li><a href="http://localhost/capst/RoomMonitoring/RoomScheduleManager/search.php">Room Schedule Manager</a></li> -->
                </ul>
            </li>

            <!-- 3. Class Program -->
            <li>
                <a href="#" class="dropdown-toggle" title="Class Program">
                    <i class="bx bx-calendar-event"></i><span>Class Program</span>
                    <i class="bx bx-chevron-right dropdown-icon"></i>
                </a>
                <ul class="submenu">
                    <li><a href="http://localhost/capst/ClassProgram/NewClassPrograms/NewClassPrograms.php">New Class Programs</a></li>
                    <li>
                        <a href="#" class="dropdown-toggle">
                            <span>Print Class Program</span>
                            <i class="bx bx-chevron-right dropdown-icon"></i>
                        </a>
                        <ul class="submenu">
                            <li><a href="http://localhost/capst/ClassProgram/ClassProgramsClassIndex/ClassProgramSearch.php">Print Class</a></li>
                            <li><a href="http://localhost/capst/ClassProgram/ClassProgramsClassIndex/ClassProgramPlotting.php">Print Plotted Class Program</a></li>
                            <li><a href="http://localhost/capst/ClassProgram/ClassProgramsClassIndex/ClassTime.php">Print Class Time</a></li>
                        </ul>
                    </li>
                    <li><a href="http://localhost/capst/ClassProgram/ClassProgramPerSubject.php">Class Program Per Subject</a></li>
                    <li><a href="http://localhost/capst/ClassProgram/SubjectsOfferingsPerCollege-Dept.php">Subject Offering Per College/Department</a></li>
                    <li><a href="http://localhost/capst/ClassProgram/Class ProgramWithoutRoomAssignment.php">Class Program without Room Assign</a></li>
                    <li><a href='http://localhost/capst/ClassProgram/ManageBlockSection.php'>Manage Block Section</a></li>
                </ul>
            </li>

            <!-- 4. Advising and Scheduling -->
            <li>
                <a href="#" class="dropdown-toggle" title="Advising and Scheduling">
                    <i class="bx bx-calendar-check"></i><span>Advising and Scheduling</span>
                    <i class="bx bx-chevron-right dropdown-icon"></i>
                </a>
                <ul class="submenu">
                    <li><a href="http://localhost/capst/Advising/Main.php">Advise a Student</a></li>
                    <li><a href="http://localhost/capst/Advising/Enrolled.php">Enrolled Students</a></li>
                    <li><a href="http://localhost/capst/Advising/Confirmation.php">Payment Confirmation</a></li>
                    <li><a href="http://localhost/capst/Advising/Student.php">Searh Student</a></li>
                    <li><a href="http://localhost/capst/Advising/Subject.php">Search Subject</a></li>
                </ul>
            </li>

            <!-- 5. Faculty -->
            <li>
                <a href="#" class="dropdown-toggle" title="Faculty">
                    <i class="bx bx-user"></i><span>Faculty</span>
                    <i class="bx bx-chevron-right dropdown-icon"></i>
                </a>
                <ul class="submenu">
                    <li><a href="http://localhost/capst/Faculty/FacultyCanTeach1/FacMain.php">Courses Faculty can Teach</a></li>
                    <li><a href="http://localhost/capst/Faculty/Loading-Scheduling2/FacLoad.php">Loading/Scheduling</a></li>
                    <li><a href="http://localhost/capst/Faculty/Substitution3/FacSubs.php">Substitution</a></li>
                    <!-- <li><a href="http://localhost/capst/Faculty/View-PrintPlottedLoad(4)/FactPlot.php">View/Print Plotted Load</a></li> -->
                    <li><a href="http://localhost/capst/Faculty/SummaryFacultyLoad5/FacSum.php">Summary Faculty load</a></li>
                    <li><a href="http://localhost/capst/Faculty/Reports6/MainReports.php">Reports</a></li>
                </ul>
            </li>

            <!-- 6. Reports -->
            <li>
                <a href="#" class="dropdown-toggle" title="Reports">
                    <i class="bx bx-file-blank"></i><span>Reports</span>
                    <i class="bx bx-chevron-right dropdown-icon"></i>
                </a>
                <ul class="submenu">
                    <li><a href="http://localhost/capst/Reports/1StudentStatusMasterlist/student_stats_masterlist.php">Student Status Masterlist</a></li>
                    <li><a href="http://localhost/capst/Reports/2ClassMasterlist/class_masterlist.php">Class Masterlist</a></li>
                    <li><a href="http://localhost/capst/Reports/3StudentSearch/student_search.php">Student Search</a></li>
                    <li><a href="http://localhost/capst/Reports/4EnrollmentAdd-DroppedSummary/dropped_summary.php">Enrollment Add/Dropped/Advised Summary</a></li>
                    <li><a href="http://localhost/capst/Reports/5Previous School/studFr_PrevSchool.php">Summary of Students from Previous School </a></li>
                </ul>
            </li>

            <!-- 9. Statistics -->
            <li>
                <a href="#" class="dropdown-toggle" title="Statistics">
                    <i class="bx bx-line-chart"></i><span>Statistics</span>
                    <i class="bx bx-chevron-right dropdown-icon"></i>
                </a>
                <ul class="submenu">
                    <li><a href="http://localhost/capst/Statistics/EnrolleesandEnrollmentComparison/Main1.php">Enrollees and Enrollment Comparison</a></li>
                    <li><a href="http://localhost/capst/Statistics/Subjects/main2.php">Courses</a></li>
                    <li><a href="http://localhost/capst/Statistics/SubjectSchedule/Main3.php">Courses Schedule</a></li>
                    <li><a href="http://localhost/capst/Statistics/Rooms/Main4.php">Rooms</a></li>
                    <li><a href="http://localhost/capst/Statistics/Religion/main5.php">Religion</a></li>
                </ul>
            </li>
        </ul>

        <div class="sticky-footer-container" id="stickyFooterContainer">
            <!-- Profile Section -->
            <div class="profile-section">
                <img src="http://localhost/capst/images/profile.jpg" alt="Profile Picture" class="profile-img">
                <div class="profile-info" id="proff">
                    <!-- <strong id="mulberry">Jane Doe</strong> -->
                    <!-- <strong id="mulberry"></strong> -->
                    <strong id="mulberry"><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
                    <small>Faculty Staff</small>
                </div>
                <!-- Notification Button -->
                <i class="bx bx-bell notification-icon"></i>
            </div>

            <!-- Logout Section -->
            <div class="logout-section" id="out-mo">
                <a href="http://localhost/capst/includes/logout.php" id="logoutButton">
                    <i class="bx bx-log-out"></i> <span>Log Out</span>
                </a>
            </div>
        </div>
    </div>

    <div class="sticky-footer-container-mobile">
        <!-- Profile Section -->
        <div class="profile-section">
            <img src="http://localhost/capst/images/profile.jpg" alt="Profile Picture" class="profile-img">
            <div class="profile-info" id="proff">
                <!-- <strong>J</strong> -->
                <small>Faculty Staff</small>
            </div>
            <!-- Notification Button -->
            <i class="bx bx-bell notification-icon"></i>
        </div>

        <!-- Logout Section -->
        <div class="logout-section" id="">
            <button id="logoutButton">
                <a href="http://localhost/capst/includes/logout.php">
                    <i class="bx bx-log-out"></i> <span>Log Out</span>
                </a>
            </button>

        </div>

    </div>

    <!-- Dark Overlay (appears when mobile nav is open) -->
    <div class="overlay" id="overlay"></div>

    <script src="app.js"></script>

    <script>
        // document.getElementById('logoutButton').addEventListener('click', () => {
        //     // Redirect to the logout script
        //     window.location.href = 'http://localhost/capst/auth/logout.php';
        // });

        document.getElementById('logoutButton').addEventListener('click', () => {
            if (confirm('Are you sure you want to log out?')) {
                window.location.href = 'http://localhost/capst/includes/logout.php';
            }
        });
    </script>
    </div>
</body>

</html>


<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Poppins", sans-serif;
    }

    body {
        margin: 0;
        background-color: var(--background-color);
    }

    :root {
        --white-color: #fff;
        --blue-color: #174069;
        --hover-color: #20568B;
        --background-color: white;
        --grey-color: #707070;
        --grey-color-light: #aaa;
        --font-size-main-menu: 14px;
        /* Primary Menu */
        --font-size-submenu: 14px;
        /* Secondary Menu */
        --font-size-tertiary-menu: 14px;
        /* Tertiary Menu */
    }

    /* Navbar */
    .navbar {
        position: fixed;
        top: 0;
        width: 100%;
        left: 0;
        background-image: url('http://localhost/capst/images/cover.png');
        background-position: -80px -80px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px 30px;
        z-index: 1000;
        box-shadow: 0 0 2px #ccc;
    }

    /* Hamburger Icon Styling */
    .hamburger-icon {
        padding: 4px;
        /* 4px padding */
        display: none;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 24px;
        color: #fff;
        /* Make sure it's visible on the navbar */
    }

    .hamburger-icon:hover {
        background-color: #f0f0f0;
        border-radius: 4px;
        color: var(--blue-color);
    }

    .logo_item {
        display: flex;
        align-items: center;
        column-gap: 10px;
        font-size: 22px;
        font-weight: 600;
        color: #fff;
    }

    .navbar img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 50%;
    }

    .navbar_content {
        display: flex;
        align-items: center;
        column-gap: 25px;
    }

    .navbar_content i {
        cursor: pointer;
        font-size: 20px;
        color: #fff;
    }

    /* Sidebar Header Container */
    .sidebar-header-container {
        position: fixed;
        top: 80px;
        left: 0;
        width: 270px;
        background-color: var(--white-color);
        box-shadow: 0 0 1px var(--grey-color-light);
        z-index: 1001;
        transition: all 0.3s ease;
    }

    .sidebar-header-container.collapsed {
        width: 80px;
    }

    /* Sidebar header (collapse button only) */
    .sidebar-header {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
        height: 60px;
        position: relative;
        border-bottom: 1px solid #ddd;
    }

    .sidebar-collapse-toggle {
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        margin-left: auto;
        margin-right: auto;
        position: relative;
    }

    /* Collapse toggle when sidebar is collapsed */
    .sidebar-header-container.collapsed .sidebar-collapse-toggle {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }

    /* Sidebar */
    .sidebar {
        position: fixed;
        top: 140px;
        left: 0;
        width: 270px;
        height: calc(100vh - 200px);
        /* Adjust depending on the height of the sticky footer */
        background-color: var(--white-color);
        box-shadow: 0 0 1px var(--grey-color-light);
        padding: 15px;
        padding-bottom: 100px;
        /* Add space for the sticky footer */
        display: flex;
        flex-direction: column;
        overflow-y: auto;
        transition: all 0.3s ease;
        z-index: 1001;
    }

    .sidebar.collapsed {
        width: 80px;
    }

    .sticky-footer-container.collapsed {
        width: 80px;
    }

    .sidebar::-webkit-scrollbar {
        display: none;
    }

    /* Sidebar Links */
    .nav_list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    /* Main Menu Links */
    .nav_list li {
        margin-bottom: 15px;
    }

    /* Main Menu Items (Primary) */
    .nav_list a {
        color: black;
        text-decoration: none;
        font-size: var(--font-size-main-menu);
        font-weight: 500;
        display: flex;
        justify-content: flex-start;
        align-items: center;
        padding: 10px 15px;
        border-radius: 5px;
        transition: background-color 0.3s ease, color 0.3s ease;
        position: relative;
    }

    .nav_list a span {
        margin-left: 10px;
        flex: 1;
        transition: opacity 0.3s ease;
    }

    .nav_list a i:first-child {
        font-size: 22px;
        margin-right: 10px;
    }

    .nav_list a:hover {
        background-color: #174069;
        color: white;
    }

    /* Submenus */
    .submenu {
        list-style: none;
        padding-left: 40px;
        display: none;
    }

    /* Submenu Items (Secondary) */
    .submenu li a {
        font-size: var(--font-size-submenu);
        padding: 8px 15px;
        color: black;
    }

    .submenu li a:hover {
        background-color: #174069;
        color: white;
    }

    .profile-section {
        display: flex;
        align-items: center;
        padding-bottom: 10px;
        border-bottom: 1px solid #ddd;
    }

    .profile-img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 15px;
    }

    .profile-info strong {
        display: block;
        font-size: 14px;
        color: #174069;
    }

    .profile-info small {
        font-size: 12px;
        color: #aaa;
    }

    .logout-section {
        display: flex;
        align-items: center;
        padding-top: 20px;
    }

    .logout-section i {
        font-size: 24px;
        margin-right: 10px;
        color: #20568B;
    }

    .logout-section span {
        font-size: 16px;
        color: #174069;
    }

    .sidebar-header img {
        width: 80px;
        margin-bottom: 10px;
    }

    .sidebar-header h2 {
        font-size: 16px;
        color: #174069;
    }

    .sidebar-header h3 {
        font-size: 14px;
        color: #20568B;
    }

    /* Tertiary Menu (Nested Submenu) */
    .submenu .submenu li a {
        font-size: var(--font-size-tertiary-menu);
        padding: 7px 15px;
        color: black;
    }

    /* Tertiary Menu Hover */
    .submenu .submenu li a:hover {
        background-color: #174069;
        color: white;
    }

    .submenu.open {
        display: block;
    }

    .rotate-down {
        transform: rotate(90deg);
    }

    .nav_list a:hover i:first-child {
        color: white;
    }

    /* Sidebar text visibility during collapse */
    .sidebar.collapsed .nav_list a span {
        opacity: 0;
        pointer-events: none;
    }

    .sidebar.collapsed .submenu {
        display: none;
    }

    /* Tooltip */
    .sidebar.collapsed .nav_list a::after {
        content: attr(data-title);
        position: fixed;
        left: 90px;
        background-color: rgba(0, 0, 0, 0.75);
        color: white;
        padding: 5px 10px;
        font-size: 12px;
        border-radius: 4px;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease, left 0.3s ease;
        z-index: 1002;
        transform: translateY(-10px);
        transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
        visibility: hidden;
    }

    /* Show tooltip on hover with delay */
    .sidebar.collapsed .nav_list a:hover::after {
        opacity: 1;
        transform: translateY(0);
        visibility: visible;
        transition-delay: 0.2s;
    }

    /* Tooltip arrow */
    .sidebar.collapsed .nav_list a::before {
        content: '';
        position: fixed;
        left: 80px;
        border-width: 5px;
        border-style: solid;
        border-color: transparent rgba(0, 0, 0, 0.75) transparent transparent;
        opacity: 0;
        transition: opacity 0.3s ease;
        transform: translateY(-10px);
        transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
        visibility: hidden;
    }

    /* Show tooltip arrow on hover with delay */
    .sidebar.collapsed .nav_list a:hover::before {
        opacity: 1;
        transform: translateY(0);
        visibility: visible;
        transition-delay: 0.2s;
    }

    /* Mobile Navigation Menu */
    .mobile-nav {
        position: fixed;
        top: 0;
        left: -100%;
        width: 280px;
        height: 100vh;
        background-color: #fff;
        padding: 20px;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        z-index: 1003;
        /* Ensure mobile nav is above the overlay */
        transition: left 0.3s ease;
        overflow-y: auto;
    }

    .mobile-nav.open {
        left: 0;
    }

    /* Dark overlay */
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1002;
        display: none;
    }

    .overlay.show {
        display: block;

    }

    /* Close Button */
    .close-btn {
        font-size: 24px;
        cursor: pointer;
        padding: 10px;
        display: block;
        margin-bottom: 10px;
        color: var(--blue-color);
    }

    /* Main Content */
    .main-content {
        margin-top: 80px;
        padding: 20px;
        transition: margin-left 0.3s ease;
        margin-left: 270px;
    }

    .main-content.shifted-collapsed {
        margin-left: 80px;
    }

    .notification-icon {
        font-size: 24px;
        color: #174069;
        margin-left: auto;
        cursor: pointer;
    }

    .notification-icon:hover {
        color: #20568B;
    }

    /* Sticky Footer and Profile Section */
    .sticky-footer-container {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 270px;
        /* Sidebar width */
        background-color: var(--white-color);
        padding: 15px;
        box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
        z-index: 1001;
        transition: all 0.3s ease;
        /* Add smooth transition for collapse */
    }

    /* Sticky Footer - Adjusted for Collapsed State */
    .sticky-footer-container.collapsed .profile-info strong,
    .sticky-footer-container.collapsed .profile-info small,
    .sticky-footer-container.collapsed .logout-section span {
        display: none;
        /* Hide text when sidebar is collapsed */
    }

    /* Ensure the profile image and bell icon remain visible when collapsed */
    .sticky-footer-container.collapsed .profile-img,
    .sticky-footer-container.collapsed .notification-icon,
    .sticky-footer-container.collapsed .logout-section i {
        display: block;
        /* Icons remain visible */
    }

    /* Align profile and bell icons at the center when sidebar is collapsed */
    .sticky-footer-container.collapsed .profile-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        /* Center align horizontally */
        justify-content: center;
        /* Center align vertically */
        text-align: center;
        /* Center-align content */
        height: 100%;
        /* Ensure full height is available for centering */
    }

    .sticky-footer-container.collapsed .profile-img {
        margin-bottom: 10px;
        margin-right: 0px;
        /* Space between profile image and bell icon */
        display: block;
        text-align: center;
    }

    .sticky-footer-container.collapsed .notification-icon {
        margin-top: 0;
        margin-left: 0px;
        font-size: 24px;
        /* Adjust size of bell icon if needed */
        display: block;
        text-align: center;
    }

    /* Center the logout icon */
    .sticky-footer-container.collapsed .logout-section {
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        height: 80px;
        /* Ensuring enough space */
    }

    /* Optional: Add space to the top of the logout icon */
    .sticky-footer-container.collapsed .logout-section i {
        margin-top: 20px;
        display: block;
        text-align: center;
    }


    /* Media query for smaller screens */
    @media (max-width: 932px) {

        .sidebar,
        .sticky-footer-container,
        .sidebar-header-container {
            display: none;
        }

        /* Show hamburger icon on small screens */
        .hamburger-icon {
            display: flex;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1002;
        }


        /* Main content takes up full width */
        .main-content {
            margin-left: 0;
        }

        /* Dark overlay for mobile nav */
        .overlay.show {
            display: block;
        }
    }
</style>