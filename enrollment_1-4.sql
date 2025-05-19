-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 02, 2025 at 05:34 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `enrollment_1-4`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetStudentCount` (IN `gender_filter` VARCHAR(10), IN `department_id` INT, IN `course_id` INT, IN `year_level_id` INT)   BEGIN
    DECLARE column_list TEXT;
    DECLARE total_columns TEXT;
    DECLARE sql_query TEXT;
    
    SELECT GROUP_CONCAT(DISTINCT 
        CASE 
            WHEN gender_filter = 'male' THEN 
                CONCAT('SUM(CASE WHEN s.education_level_id = ', e.id, ' AND s.gender = "male" THEN 1 ELSE 0 END) AS ', e.level_name, '_Male')
            WHEN gender_filter = 'female' THEN 
                CONCAT('SUM(CASE WHEN s.education_level_id = ', e.id, ' AND s.gender = "female" THEN 1 ELSE 0 END) AS ', e.level_name, '_Female')
            ELSE 
                CONCAT(
                    'SUM(CASE WHEN s.education_level_id = ', e.id, ' AND s.gender = "male" THEN 1 ELSE 0 END) AS ', e.level_name, '_Male, ',
                    'SUM(CASE WHEN s.education_level_id = ', e.id, ' AND s.gender = "female" THEN 1 ELSE 0 END) AS ', e.level_name, '_Female'
                )
        END
        ORDER BY e.id SEPARATOR ', '
    ) INTO column_list
    FROM education_levels e
    WHERE year_level_id = 0 OR e.id = year_level_id;

    SELECT GROUP_CONCAT(DISTINCT 
        CASE 
            WHEN gender_filter = 'male' THEN 
                CONCAT('SUM(CASE WHEN s.education_level_id = ', e.id, ' AND s.gender = "male" THEN 1 ELSE 0 END)')
            WHEN gender_filter = 'female' THEN 
                CONCAT('SUM(CASE WHEN s.education_level_id = ', e.id, ' AND s.gender = "female" THEN 1 ELSE 0 END)')
            ELSE 
                CONCAT(
                    'SUM(CASE WHEN s.education_level_id = ', e.id, ' AND s.gender = "male" THEN 1 ELSE 0 END) + ',
                    'SUM(CASE WHEN s.education_level_id = ', e.id, ' AND s.gender = "female" THEN 1 ELSE 0 END)'
                )
        END
        ORDER BY e.id SEPARATOR ' + '
    ) INTO total_columns
    FROM education_levels e
    WHERE year_level_id = 0 OR e.id = year_level_id;

    SET @sql_query = CONCAT(
        'SELECT d.department_name AS department, c.course_name AS course, m.major_name AS major, ',
        column_list, ', (', total_columns, ') AS Total
        FROM departments d
        LEFT JOIN courses c ON d.id = c.department_id
        LEFT JOIN majors m ON c.id = m.course_id
        LEFT JOIN students s ON m.id = s.major_id
        LEFT JOIN education_levels e ON s.education_level_id = e.id
        WHERE (', department_id, ' = 0 OR d.id = ', department_id, ')  -- Department filter
        AND (', course_id, ' = 0 OR c.id = ', course_id, ')  -- Course filter
        AND (', year_level_id, ' = 0 OR s.education_level_id = ', year_level_id, ')  -- Education level filter
        GROUP BY d.department_name, c.course_name, m.major_name
        ORDER BY d.department_name, c.course_name, m.major_name;'
    );

    -- Execute the query
    PREPARE stmt FROM @sql_query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Super Admin','Admin') NOT NULL DEFAULT 'Admin',
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `role`, `profile_picture`, `created_at`, `updated_at`) VALUES
(1, 'admin1', '$2y$10$urjXuy9.LfQIj5hkZO5ImOSNEMNYsu4X3y.avk2hy4ZpNwp9I2o2S', 'Super Admin', '/images/admin1.jpg', '2025-01-08 10:57:40', '2025-01-08 10:57:40');

-- --------------------------------------------------------

--
-- Table structure for table `buildings`
--

CREATE TABLE `buildings` (
  `code` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buildings`
--

INSERT INTO `buildings` (`code`, `name`, `description`) VALUES
('BLDG A', 'IS Building A', 'Building for the Integrated School'),
('BLDG B', 'IS Building B', 'Building for the Integrated School'),
('MAIN', 'Main Building', 'Front Building of the School'),
('PSB', 'Professionals School Building', 'Building for Students of Medicine'),
('SOM', 'School of Management', 'Building for students of Management');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course_code` varchar(50) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_international` tinyint(1) DEFAULT 0,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_code`, `course_name`, `description`, `is_international`, `department_id`) VALUES
(15, 'BLIS', 'Bachelor of Library and Information Science', 'Teaches library management, information organization, and digital archiving.', 0, 6),
(16, 'BSCS', 'Bachelor of Science in Computer Science', 'Studies programming, algorithms, and software development.', 0, 6),
(17, 'BSEMC', 'Bachelor of Science in Entertainment and Multimedia Computing', 'Focuses on game development, animation, and digital media technology.', 0, 6),
(18, 'BSIT', 'Bachelor of Science in Information Technology', 'Covers computer networking, cybersecurity, and software applications.', 0, 6),
(19, 'BSIS', 'Bachelor of Science in Information System', 'Focuses on business technology solutions, database management, and system analysis.', 0, 6);

-- --------------------------------------------------------

--
-- Table structure for table `curriculum_years`
--

CREATE TABLE `curriculum_years` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `curriculum_year_start` year(4) NOT NULL,
  `curriculum_year_end` year(4) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `curriculum_years`
--

INSERT INTO `curriculum_years` (`id`, `course_id`, `curriculum_year_start`, `curriculum_year_end`, `description`) VALUES
(9, 15, '2015', '2018', 'test_curriculum_year');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `department_name` varchar(255) NOT NULL,
  `department_code` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `department_name`, `department_code`, `description`, `created_at`, `updated_at`) VALUES
(6, 'College of Informatics and Computing Studies', 'CICS', 'Focuses on computer science, information systems, and data analytics for technological innovation.', '2025-04-01 03:15:32', '2025-04-01 03:16:20');

-- --------------------------------------------------------

--
-- Table structure for table `education_levels`
--

CREATE TABLE `education_levels` (
  `id` int(11) NOT NULL,
  `level_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `education_levels`
--

INSERT INTO `education_levels` (`id`, `level_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Baccalaureate', 'Undergraduate degree programs.', '2025-01-08 10:57:40', '2025-01-08 10:57:40'),
(2, 'Master\'s', 'Graduate degree programs for advanced education.', '2025-01-08 10:57:40', '2025-01-08 10:57:40'),
(3, 'Doctoral', 'Highest level of academic degree.', '2025-01-08 10:57:40', '2025-01-08 10:57:40');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `max_load` double(11,1) NOT NULL,
  `department_id` int(11) NOT NULL,
  `role` enum('Professor','Lecturer','Assistant Professor') NOT NULL,
  `emp_type` varchar(100) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`id`, `username`, `password`, `first_name`, `last_name`, `email`, `phone_number`, `gender`, `date_of_birth`, `max_load`, `department_id`, `role`, `emp_type`, `profile_picture`, `created_at`, `updated_at`) VALUES
(1, 'faculty1', '$2y$10$urjXuy9.LfQIj5hkZO5ImOSNEMNYsu4X3y.avk2hy4ZpNwp9I2o2S', 'Jane', 'Smith', 'jane.smith@university.com', '987-654-3210', 'Female', '1980-08-15', 24.0, 6, 'Professor', 'Full-Time', '/images/faculty1.jpg', '2025-01-08 10:57:40', '2025-05-01 20:05:37'),
(2, 'faculty2', '$2y$10$urjXuy9.LfQIj5hkZO5ImOSNEMNYsu4X3y.avk2hy4ZpNwp9I2o2S', 'John', 'Smith', 'john.smith@university.com', '987-654-3210', 'Male', '1980-08-15', 24.0, 6, 'Assistant Professor', 'Part-Time', '/images/faculty1.jpg', '2025-01-08 10:57:40', '2025-05-01 20:08:39');

-- --------------------------------------------------------

--
-- Table structure for table `faculty_load`
--

CREATE TABLE `faculty_load` (
  `id` int(11) NOT NULL,
  `college_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `professor_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `units` varchar(10) NOT NULL,
  `day` varchar(20) NOT NULL,
  `class_time_from` varchar(10) NOT NULL,
  `class_time_to` varchar(10) NOT NULL,
  `school_year_from` varchar(11) NOT NULL,
  `school_year_to` varchar(11) NOT NULL,
  `year_level` varchar(20) NOT NULL,
  `term` varchar(20) NOT NULL,
  `room` int(11) NOT NULL,
  `section` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty_load`
--

INSERT INTO `faculty_load` (`id`, `college_id`, `course_id`, `professor_id`, `subject_id`, `units`, `day`, `class_time_from`, `class_time_to`, `school_year_from`, `school_year_to`, `year_level`, `term`, `room`, `section`, `created_at`) VALUES
(7, 6, 18, 1, 23, '2', 'T,TH', '02:00 PM', '04:00 PM', '2025', '2026', '1', '1st', 420, 'BSIS1-A', '2025-04-30 06:06:47');

-- --------------------------------------------------------

--
-- Table structure for table `majors`
--

CREATE TABLE `majors` (
  `id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `education_level_id` int(11) NOT NULL,
  `major_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `majors`
--

INSERT INTO `majors` (`id`, `course_id`, `education_level_id`, `major_name`, `description`, `created_at`, `updated_at`) VALUES
(5, 17, 1, 'Digital Animation Technology', 'Focuses on 2D/3D animation, visual effects, and character design for digital media.', '2025-04-01 05:48:55', '2025-04-01 05:48:55'),
(6, 17, 1, 'Game Development', 'Studies programming, game design, and interactive media for video game creation.', '2025-04-01 05:48:55', '2025-04-01 05:48:55');

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `program_id` int(11) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `course_program` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `major_id` int(11) DEFAULT NULL,
  `curriculum_id` int(11) DEFAULT NULL,
  `section` varchar(100) DEFAULT NULL,
  `year_level` text DEFAULT NULL,
  `term` text DEFAULT NULL,
  `school_year` text DEFAULT NULL,
  `subject_type` text DEFAULT NULL,
  `subject_component` text DEFAULT NULL,
  `schedule_day` text DEFAULT NULL,
  `schedule_time` text DEFAULT NULL,
  `is_international` int(11) DEFAULT NULL,
  `room_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`program_id`, `department_id`, `course_id`, `course_program`, `subject_id`, `major_id`, `curriculum_id`, `section`, `year_level`, `term`, `school_year`, `subject_type`, `subject_component`, `schedule_day`, `schedule_time`, `is_international`, `room_id`) VALUES
(36, 6, 17, 1, 24, 1, 9, 'BSEMC2-1', '3', '1st', '2025-2026', 'REG', 'LAB', 'M,W,F', '07:00 AM - 09:00 AM', 0, 16),
(44, 6, 19, 1, 23, 0, 9, 'BSIS1-A', '1', '1st', '2025-2026', 'REG', 'LEC', 'T,TH', '02:00 PM - 04:00 PM', 0, 2),
(45, 6, 19, 1, 5, 0, 9, 'BSIT1-B', '1', '1st', '2025-2026', 'REG', 'LEC', 'T,TH', '02:00 PM - 04:00 PM', 0, 4);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`, `description`) VALUES
(1, 'Admin', 'System administrators with full access'),
(2, 'Faculty', 'Faculty members such as professors and lecturers'),
(3, 'Registrar', 'Manages student records and enrollment'),
(4, 'Building Manager', 'Handles building and infrastructure information');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `building_code` varchar(50) NOT NULL,
  `floor` varchar(50) NOT NULL,
  `room_number` varchar(50) NOT NULL,
  `room_capacity` int(11) NOT NULL,
  `no_subject` tinyint(1) DEFAULT 0,
  `room_conflict` tinyint(1) DEFAULT 0,
  `room_type` varchar(100) NOT NULL,
  `status` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `last_inspection_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `school_year` varchar(9) DEFAULT NULL,
  `term` enum('1st-Semester','2nd-Semester','Summer') DEFAULT NULL,
  `building` enum('Elm','MAIN','ColMain','ProfBldg') DEFAULT NULL,
  `building_location` varchar(100) NOT NULL,
  `department_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `building_code`, `floor`, `room_number`, `room_capacity`, `no_subject`, `room_conflict`, `room_type`, `status`, `description`, `last_inspection_date`, `created_at`, `updated_at`, `school_year`, `term`, `building`, `building_location`, `department_id`) VALUES
(2, 'PSB', '4th Floor', '420', 30, 0, 0, 'LEC', 'OCCUPIED', 'This room is occupied for College of Medicine Lecture room', '0000-00-00', '0000-00-00 00:00:00', '2025-04-29 16:03:52', '2025', '2nd-Semester', '', 'PSB', 6),
(3, 'BLDG A', 'Ground', '248', 40, 0, 0, 'Library', 'OCCUPIED', 'Reading Center', '0000-00-00', '0000-00-00 00:00:00', '2025-04-29 16:03:55', '2025', '2nd-Semester', '', 'IS Bldg. A', 6),
(4, 'MAIN', '2nd Floor', '212', 35, 0, 0, 'LEC', 'AVAILABLE', 'This room is occupied for College of Arts and Sciences', '0000-00-00', '0000-00-00 00:00:00', '2025-04-29 16:03:58', '2025', '2nd-Semester', 'MAIN', 'Main', 6),
(7, 'BLDG B', '2nd Floor', '208', 40, 0, 0, 'LEC', 'OCCUPIED', 'Lecture Room for IS Students', '0000-00-00', '0000-00-00 00:00:00', '2025-04-29 16:04:01', '2024', '1st-Semester', '', 'IS Bldg. B', 6),
(16, 'MAIN', '2nd Floor', '212', 35, 0, 0, 'LAB', 'AVAILABLE', 'This room is occupied for College of Arts and Sciences', '0000-00-00', '0000-00-00 00:00:00', '2025-04-29 16:03:58', '2025', '2nd-Semester', 'MAIN', 'Main', 6);

-- --------------------------------------------------------

--
-- Table structure for table `room_reservations`
--

CREATE TABLE `room_reservations` (
  `id` int(11) NOT NULL,
  `school_year_start` year(4) NOT NULL,
  `school_year_end` year(4) NOT NULL,
  `term` enum('1st-Semester','2nd-Semester','Summer') NOT NULL,
  `activity_start` date NOT NULL,
  `activity_end` date NOT NULL,
  `room_number` varchar(10) NOT NULL,
  `reservation_start` time NOT NULL,
  `reservation_end` time NOT NULL,
  `notes` text DEFAULT NULL,
  `reserved_by` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_reservations`
--

INSERT INTO `room_reservations` (`id`, `school_year_start`, `school_year_end`, `term`, `activity_start`, `activity_end`, `room_number`, `reservation_start`, `reservation_end`, `notes`, `reserved_by`) VALUES
(1, '2024', '2025', '2nd-Semester', '2025-04-07', '2025-04-07', '415', '10:00:00', '13:00:00', 'Capstone Defense Room', 'CICS Dean'),
(6, '2024', '2025', '2nd-Semester', '2025-04-04', '2025-04-04', '512', '14:00:00', '17:00:00', 'Opening Ceremony of Tagis Lakas', 'President');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `section_id` int(11) NOT NULL,
  `section_name` varchar(50) NOT NULL,
  `course_id` int(11) NOT NULL,
  `year_level` enum('1st','2nd','3rd','4th','5th') NOT NULL,
  `academic_year` varchar(9) NOT NULL,
  `semester` enum('1st','2nd','Summer') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`section_id`, `section_name`, `course_id`, `year_level`, `academic_year`, `semester`) VALUES
(1, '1BSAccountancy-1', 1, '1st', '2024-2025', '1st'),
(2, '1BSAccountancy-2', 1, '2nd', '2024-2025', '1st'),
(3, '1BSAIS-1', 2, '1st', '2024-2025', '1st'),
(4, '1BSAIS-2', 2, '2nd', '2024-2025', '1st'),
(5, '1BSAgriculture-1', 3, '1st', '2024-2025', '1st'),
(6, '1BSAgriculture-2', 3, '2nd', '2024-2025', '1st'),
(7, '1BAEconomics-1', 4, '1st', '2024-2025', '1st'),
(8, '1BAEconomics-2', 4, '2nd', '2024-2025', '1st'),
(9, '1BAPoliticalScience-1', 5, '1st', '2024-2025', '1st'),
(10, '1BAPoliticalScience-2', 5, '2nd', '2024-2025', '1st'),
(11, '1BSBiology-1', 6, '1st', '2024-2025', '1st'),
(12, '1BSBiology-2', 6, '2nd', '2024-2025', '1st'),
(13, '1BSPsychology-1', 7, '1st', '2024-2025', '1st'),
(14, '1BSPsychology-2', 7, '2nd', '2024-2025', '1st'),
(15, '1BSPA-1', 8, '1st', '2024-2025', '1st'),
(16, '1BSPA-2', 8, '2nd', '2024-2025', '1st'),
(17, '1BSBA-1', 9, '1st', '2024-2025', '1st'),
(18, '1BSBA-2', 9, '2nd', '2024-2025', '1st'),
(19, '1BSEntrepreneurship-1', 10, '1st', '2024-2025', '1st'),
(20, '1BSEntrepreneurship-2', 10, '2nd', '2024-2025', '1st'),
(21, '1BSREM-1', 11, '1st', '2024-2025', '1st'),
(22, '1BSREM-2', 11, '2nd', '2024-2025', '1st'),
(23, '1BABroadcasting-1', 12, '1st', '2024-2025', '1st'),
(24, '1BABroadcasting-2', 12, '2nd', '2024-2025', '1st'),
(25, '1BACommunication-1', 13, '1st', '2024-2025', '1st'),
(26, '1BACommunication-2', 13, '2nd', '2024-2025', '1st'),
(27, '1BAJournalism-1', 14, '1st', '2024-2025', '1st'),
(28, '1BAJournalism-2', 14, '2nd', '2024-2025', '1st'),
(29, '1BALIS-1', 15, '1st', '2024-2025', '1st'),
(30, '1BALIS-2', 15, '2nd', '2024-2025', '1st'),
(31, '1BSCS-1', 16, '1st', '2024-2025', '1st'),
(32, '1BSCS-2', 16, '2nd', '2024-2025', '1st'),
(33, '1BSEntertainment-1', 17, '1st', '2024-2025', '1st'),
(34, '1BSEntertainment-2', 17, '2nd', '2024-2025', '1st'),
(35, '1BSIT-1', 18, '1st', '2024-2025', '1st'),
(36, '1BSIT-2', 18, '2nd', '2024-2025', '1st'),
(37, '1BSIS-1', 19, '1st', '2024-2025', '1st'),
(38, '1BSIS-2', 19, '2nd', '2024-2025', '1st'),
(39, '1BSCriminology-1', 20, '1st', '2024-2025', '1st'),
(40, '1BSCriminology-2', 20, '2nd', '2024-2025', '1st'),
(41, '1BSEd-1', 21, '1st', '2024-2025', '1st'),
(42, '1BSEd-2', 21, '2nd', '2024-2025', '1st'),
(43, '1BSSEd-1', 22, '1st', '2024-2025', '1st'),
(44, '1BSSEd-2', 22, '2nd', '2024-2025', '1st'),
(45, '1BSArch-1', 23, '1st', '2024-2025', '1st'),
(46, '1BSArch-2', 23, '2nd', '2024-2025', '1st'),
(47, '1BSAstronomy-1', 24, '1st', '2024-2025', '1st'),
(48, '1BSAstronomy-2', 24, '2nd', '2024-2025', '1st'),
(49, '1BSCivilEngineering-1', 25, '1st', '2024-2025', '1st'),
(50, '1BSCivilEngineering-2', 25, '2nd', '2024-2025', '1st');

-- --------------------------------------------------------

--
-- Table structure for table `semesters`
--

CREATE TABLE `semesters` (
  `id` tinyint(4) NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `semesters`
--

INSERT INTO `semesters` (`id`, `name`) VALUES
(1, '1st Semester'),
(2, '2nd Semester'),
(3, 'Summer');

-- --------------------------------------------------------

--
-- Table structure for table `specific_roles`
--

CREATE TABLE `specific_roles` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `specific_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `specific_roles`
--

INSERT INTO `specific_roles` (`id`, `role_id`, `specific_name`, `description`) VALUES
(1, 1, 'Super Admin', 'Has full control over the system'),
(2, 1, 'Admin', 'Manages general administrative tasks'),
(3, 2, 'Professor', 'Teaching staff with research responsibilities'),
(4, 2, 'Lecturer', 'Teaching staff focused on instruction'),
(5, 3, 'Registrar', 'Responsible for managing student records'),
(6, 4, 'Building Manager', 'Manages building and room information');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `student_number` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `suffix` varchar(20) NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `birthdate` date NOT NULL,
  `age` int(50) NOT NULL,
  `religion` tinyint(1) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `departments_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `major_id` int(11) DEFAULT NULL,
  `education_level_id` int(11) DEFAULT NULL,
  `year_terms_id` int(11) DEFAULT NULL,
  `classification_code` int(10) DEFAULT NULL,
  `student_status` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `enrollment_status` varchar(50) DEFAULT NULL,
  `advised_status` tinyint(1) DEFAULT 0,
  `status` varchar(255) DEFAULT NULL,
  `maximum_units` int(11) DEFAULT NULL,
  `school_year` varchar(50) DEFAULT NULL,
  `term` varchar(25) DEFAULT NULL,
  `is_paid` tinyint(1) DEFAULT 0,
  `payment_mode` enum('installment','fully-paid') DEFAULT NULL,
  `year_level` varchar(55) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `student_number`, `first_name`, `last_name`, `middle_name`, `suffix`, `gender`, `birthdate`, `age`, `religion`, `email`, `phone`, `address`, `departments_id`, `course_id`, `major_id`, `education_level_id`, `year_terms_id`, `classification_code`, `student_status`, `created_at`, `updated_at`, `enrollment_status`, `advised_status`, `status`, `maximum_units`, `school_year`, `term`, `is_paid`, `payment_mode`, `year_level`) VALUES
(4, '19-34567-102', 'Nadine', 'Lustre', 'Alexis', '', 'female', '2001-10-31', 24, 1, 'nadine.lustre@email.ph', '0917-345-6789', '789 Pasig Blvd., MNL', 6, 18, NULL, 1, 16, 2, 'transferee', '2025-04-01 19:41:36', '2025-04-01 19:41:36', 'enrolled', 1, 'Active', 21, '2024-2025', '1st', 0, NULL, '2nd'),
(7, '19-67890-105', 'Liza', 'Hope', 'Soberano', '', 'female', '2001-01-04', 24, 1, 'liza.soberano@email.ph', '0918-678-9012', '321 Davao St., Davao', 6, 19, NULL, 1, 14, 3, 'old student', '2025-04-01 19:41:36', '2025-04-01 19:41:36', 'pending', 1, 'Active', 18, '2025-2026', '1st', 0, NULL, '1st'),
(10, '20-90123-108', 'Alden', 'Richard', 'Richards', '', 'male', '2003-01-02', 22, 1, 'alden.richards@email.ph', '0921-901-2345', '876 Alabang Rd., MNL', 6, 17, 5, 1, 14, 4, 'cross enrollee', '2025-04-01 19:41:36', '2025-04-01 19:41:36', 'not enrolled\n', 0, 'Active', 12, '2024-2025', '1st', 0, NULL, '1st'),
(12, '21-12345-110', 'Kim', 'Kimberly', 'Chiu', '', 'female', '2003-04-19', 22, 1, 'kim.chiu@email.ph', '0923-123-4567', '678 Iloilo Ave., Iloilo', NULL, NULL, NULL, NULL, NULL, 3, 'old student', '2025-04-01 19:41:36', '2025-04-01 19:41:36', 'enrolled', 1, 'Active', 18, '2024-2025', '1st', 0, NULL, '3rd'),
(13, '22-23456-111', 'Coco', 'Rodel', 'Martin', '', 'male', '2004-11-01', 21, 0, 'coco.martin@email.ph', '0924-234-5678', '890 Zambales St., Zambales', NULL, NULL, NULL, NULL, NULL, 1, 'new student', '2025-04-01 19:41:36', '2025-04-01 19:41:36', 'not enrolled\n', 0, 'Active', 25, '2024-2025', '2nd', 0, NULL, '1st'),
(14, '23-34567-112', 'Anne', 'Ojales', 'Curtis', '', 'female', '2002-02-17', 23, 1, 'anne.curtis@email.ph', '09253456789', '123 La Union St., La Union', NULL, NULL, NULL, NULL, NULL, 3, 'transferee', '2025-04-01 20:00:44', '2025-04-01 20:00:44', 'not enrolled\n', 0, 'Active', 18, '2023-2024', '2nd', 0, NULL, '2nd'),
(15, '23-45678-113', 'Jericho', 'Vibar', 'Rosales', '', 'male', '2004-09-22', 21, 0, 'jericho.rosales@email.ph', '09184567890', '456 CDO St., CDO', NULL, NULL, NULL, NULL, NULL, 2, 'second program', '2025-04-01 20:00:44', '2025-04-01 20:00:44', 'not enrolled\n', 0, 'Inactive', 21, '2023-2024', '2nd', 0, NULL, '3rd'),
(16, '21-45678-114', 'Angel', 'Colmenares', 'Locsin', '', 'male', '2003-04-23', 22, 1, 'angel.locsin@email.ph', '09265678901', '567 Davao St., Davao', NULL, NULL, NULL, NULL, NULL, 1, 'new student', '2025-04-01 20:00:44', '2025-04-01 20:00:44', 'not enrolled\n', 0, 'Inactive', 25, '2023-2024', 'Summer', 0, NULL, '4th'),
(17, '20-56789-115', 'Bea', 'Phylbert', 'Alonzo', '', 'female', '2001-10-17', 24, 0, 'bea.alonzo@email.ph', '09276789012', '789 Tagaytay Rd., Tagaytay', NULL, NULL, NULL, NULL, NULL, 4, 'old student', '2025-04-01 20:00:44', '2025-04-01 20:00:44', 'not enrolled\n', 0, 'Graduate', 12, '2023-2024', 'Summer', 0, NULL, '2nd'),
(18, '19-78901-116', 'Dingdong', 'Jose', 'Dantes', '', 'male', '2002-08-02', 23, 1, 'dingdong.dantes@email.ph', '09287890123', '321 Laguna Blvd., Laguna', NULL, NULL, NULL, NULL, NULL, 3, 'old student', '2025-04-01 20:00:44', '2025-04-01 20:00:44', 'not enrolled\n', 0, 'Inactive', 18, '2023-2024', 'Summer', 0, NULL, '4th'),
(19, '18-89012-117', 'Marian', 'Gracia', 'Rivera', '', 'female', '2004-08-12', 21, 0, 'marian.rivera@email.ph', '09178901234', '654 Cavite Ave., Cavite', NULL, NULL, NULL, NULL, NULL, 2, 'transferee', '2025-04-01 20:00:44', '2025-04-01 20:00:44', 'not enrolled\n', 0, 'Inactive', 21, '2023-2024', 'Summer', 0, NULL, '3rd'),
(20, '24-90123-118', 'Piolo', 'Jose', 'Pascual', '', 'male', '2000-01-12', 25, 1, 'piolo.pascual@email.ph', '09299012345', '876 Batangas Rd., Batangas', NULL, NULL, NULL, NULL, NULL, 4, 'cross enrollee', '2025-04-01 20:00:44', '2025-04-01 20:00:44', 'not enrolled\n', 0, 'Inactive', 12, '2023-2024', 'Summer', 0, NULL, '4th'),
(21, '25-01234-119', 'Toni', 'Celestine', 'Gonzaga', '', 'female', '2002-01-20', 23, 1, 'toni.gonzaga@email.ph	', '09180123456', '543 Rizal St., Rizal', NULL, NULL, NULL, NULL, NULL, 1, 'new student', '2025-04-01 20:00:44', '2025-04-01 20:00:44', 'not enrolled\n', 0, 'Inactive', 25, '2023-2024', 'Summer', 0, NULL, '1st');

-- --------------------------------------------------------

--
-- Table structure for table `student_classifications`
--

CREATE TABLE `student_classifications` (
  `id` int(11) NOT NULL,
  `classification_code` varchar(10) NOT NULL,
  `classification_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_classifications`
--

INSERT INTO `student_classifications` (`id`, `classification_code`, `classification_name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'FRESH', 'Freshman', 'Students who are in their first year of study.', 1, '2025-01-08 10:57:40', '2025-01-08 10:57:40'),
(2, 'SOPH', 'Sophomore', 'Students who are in their second year of study.', 1, '2025-01-08 10:57:40', '2025-01-08 10:57:40'),
(3, 'JUNIOR', 'Junior', 'Students who are in their third year of study.', 1, '2025-01-08 10:57:40', '2025-01-08 10:57:40'),
(4, 'SENIOR', 'Senior', 'Students who are in their fourth year of study.	', 1, '2025-03-31 00:52:30', '2025-03-31 00:53:00');

-- --------------------------------------------------------

--
-- Table structure for table `student_course`
--

CREATE TABLE `student_course` (
  `id` int(11) NOT NULL,
  `student_id` varchar(255) DEFAULT NULL,
  `program_id` int(11) DEFAULT NULL,
  `enrollment_date` date DEFAULT NULL,
  `school_year` varchar(10) DEFAULT NULL,
  `term` varchar(20) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_course`
--

INSERT INTO `student_course` (`id`, `student_id`, `program_id`, `enrollment_date`, `school_year`, `term`, `status`) VALUES
(20, '4', 36, '2025-04-07', '2024-2025', '1', 'Enrolled'),
(21, '7', 44, '2025-04-30', '2025-2026', '1', 'Enrolled');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `subject_code` varchar(50) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `department_id` int(11) NOT NULL,
  `department_code` varchar(10) DEFAULT NULL,
  `course_id` int(11) NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `status` varchar(50) DEFAULT 'active',
  `is_requested_subject` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `lec_units` int(11) NOT NULL,
  `lab_units` int(11) NOT NULL,
  `units` double(11,2) NOT NULL DEFAULT 1.00,
  `year_level` int(11) NOT NULL,
  `term` varchar(55) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `subject_code`, `subject_name`, `department_id`, `department_code`, `course_id`, `created_by`, `status`, `is_requested_subject`, `created_at`, `updated_at`, `lec_units`, `lab_units`, `units`, `year_level`, `term`) VALUES
(1, 'ccc111-18', 'Introduction to Computing', 6, 'CICS', 18, 'Dr. De Luna', 'active', 0, '2025-04-01 08:27:22', '2025-05-02 03:18:05', 2, 0, 2.00, 2, '2nd'),
(2, 'ccl111-18', 'Introduction to Computing(Lab)\r\n', 6, 'CICS', 18, 'Dr. De Luna', 'active', 0, '2025-04-01 08:27:22', '2025-05-02 03:18:07', 0, 1, 1.00, 3, '1st'),
(3, 'ccc112-18', 'Fundamentals of Programming', 6, 'CICS', 18, 'Prof. Lex', 'active', 0, '2025-04-01 09:17:16', '2025-05-02 03:18:09', 2, 0, 2.00, 3, 'Summer'),
(4, 'ccl112-18', 'Fundamentals of Programming(Lab)', 6, 'CICS', 18, 'Prof. Lex', 'active', 0, '2025-04-01 09:17:16', '2025-05-02 03:18:11', 0, 1, 1.00, 4, '1st'),
(5, 'cit113-18', 'Fundamentals of Information Technology', 6, 'CICS', 18, 'Dr. Smith', 'active', 0, '2025-04-01 09:17:16', '2025-05-02 03:18:13', 3, 0, 3.00, 1, '1st'),
(12, 'ccc121-18', 'Intermediate Programming\r\n', 6, 'CICS', 18, 'Prof. AJ', 'active', 0, '2025-04-01 09:17:16', '2025-05-02 03:18:15', 2, 0, 2.00, 4, '1st'),
(13, 'ccl121-18', 'Intermediate Programming(Lab)\r\n', 6, 'CICS', 19, 'Prof. AJ', 'active', 0, '2025-04-01 09:17:16', '2025-05-02 03:19:02', 0, 1, 1.00, 1, 'Summer'),
(14, 'cit122-18', 'Fundamentals of Network\r\n', 6, 'CICS', 19, 'Prof. Myra', 'active', 0, '2025-04-01 09:17:16', '2025-05-02 03:19:02', 2, 0, 2.00, 1, '1st'),
(15, 'itl122-18', 'Fundamentals of Network(Lab)\r\n', 6, 'CICS', 19, 'Prof. Myra', 'active', 0, '2025-04-01 09:17:16', '2025-05-02 03:19:02', 0, 1, 1.00, 1, 'Summer'),
(16, 'cit123-18', 'Discrete Structure\r\n', 6, 'CICS', 19, 'Dr. Hagos', 'active', 0, '2025-04-01 09:17:16', '2025-05-02 03:19:02', 3, 0, 3.00, 2, '2nd'),
(23, 'ccc211-18', 'Information Management 1\r\n', 6, 'CICS', 19, 'Dr. Smith', 'active', 0, '2025-04-01 09:17:16', '2025-05-02 03:19:02', 2, 0, 2.00, 1, '1st'),
(24, 'ccl211-18', 'Information Management 1 (Lab)\r\n', 6, 'CICS', 19, 'Dr. Smith', 'active', 0, '2025-04-01 09:17:16', '2025-05-02 03:19:02', 0, 1, 1.00, 2, '1st'),
(25, 'ccc212-18', 'Data Structures and Algorithms\r\n', 6, 'CICS', 19, 'Dr. Smith', 'active', 0, '2025-04-01 09:17:16', '2025-05-02 03:19:02', 2, 0, 2.00, 2, 'Summer'),
(26, 'ccl212-18', 'Data Structures and Algorithms (Lab)', 6, 'CICS', 19, '', 'active', 0, '2025-04-01 09:17:16', '2025-05-02 03:19:02', 0, 1, 1.00, 3, '2nd'),
(27, 'cit213-18', 'Human Computer Interaction\r\n', 6, 'CICS', 19, '', 'active', 0, '2025-04-01 09:40:50', '2025-05-02 03:19:02', 2, 0, 2.00, 3, 'Summer'),
(28, 'itl213-18', 'Human Computer Interaction (Lab)\r\n', 6, 'CICS', 19, '', 'active', 0, '2025-04-01 09:40:50', '2025-05-02 03:19:02', 1, 0, 1.00, 2, '1st'),
(29, 'citel1-18', 'IT Elective 1\r\n', 6, 'CICS', 19, '', 'active', 0, '2025-04-01 09:40:50', '2025-05-02 03:19:02', 2, 0, 2.00, 1, 'Summer'),
(30, 'itlel1-18', 'IT Elective 1(Lab)\r\n', 6, 'CICS', 19, '', 'active', 0, '2025-04-01 09:40:50', '2025-05-02 03:19:02', 0, 1, 1.00, 3, 'Summer'),
(35, 'cit221-18', 'Object Oriented Programming\r\n', 6, 'CICS', 19, '', 'active', 0, '2025-04-01 09:40:50', '2025-05-02 03:19:02', 2, 0, 2.00, 4, '1st'),
(36, 'itl221-18', 'Object Oriented Programming (Lab)\r\n', 6, 'CICS', 19, '', 'active', 0, '2025-04-01 09:40:50', '2025-05-02 03:19:02', 0, 1, 1.00, 3, '2nd'),
(37, 'cit222-18', 'Operating Systems\r\n', 6, 'CICS', 19, '', 'active', 0, '2025-04-01 09:40:50', '2025-05-02 03:19:02', 2, 0, 2.00, 1, '2nd'),
(38, 'itl222-18', 'Operating Systems (Lab)', 6, 'CICS', 19, '', 'active', 0, '2025-04-01 09:40:50', '2025-05-02 03:19:02', 0, 1, 1.00, 4, 'Summer'),
(39, 'cit223-18', 'Information Management 2\r\n', 6, 'CICS', 19, '', 'active', 0, '2025-04-01 09:40:50', '2025-05-02 03:19:02', 2, 0, 2.00, 2, 'Summer'),
(40, 'itl223-18', 'Information Management 2(Lab)\r\n', 6, 'CICS', 19, '', 'active', 0, '2025-04-01 09:40:50', '2025-05-02 03:19:02', 0, 1, 1.00, 1, '1st'),
(41, 'cit224-18', 'System Integration and Architecture 1\r\n', 6, 'CICS', 19, '', 'active', 0, '2025-04-01 09:40:50', '2025-05-02 03:19:02', 2, 0, 2.00, 3, '1st'),
(42, 'itl224-18', 'System Integration and Architecture 1(Lab)\r\n', 6, 'CICS', 19, '', 'active', 0, '2025-04-01 09:40:50', '2025-05-02 03:19:02', 0, 1, 1.00, 2, '2nd'),
(44, 'citel2-18', 'IT Elective 2\r\n', 6, 'CICS', 19, '', 'active', 0, '2025-04-01 09:40:50', '2025-05-02 03:19:02', 2, 0, 2.00, 2, '1st'),
(45, 'itlel2-18', 'IT Elective 2 (Lab)\r\n', 6, 'CICS', 19, '', 'active', 0, '2025-04-01 09:40:50', '2025-05-02 03:19:02', 0, 1, 1.00, 4, '1st');

-- --------------------------------------------------------

--
-- Table structure for table `substitution_schedule`
--

CREATE TABLE `substitution_schedule` (
  `id` int(11) NOT NULL,
  `original_professor_id` int(11) NOT NULL,
  `substitute_professor_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `section` varchar(50) NOT NULL,
  `schedule_date` varchar(20) NOT NULL,
  `time_from` varchar(10) NOT NULL,
  `time_to` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `substitution_schedule`
--

INSERT INTO `substitution_schedule` (`id`, `original_professor_id`, `substitute_professor_id`, `subject_id`, `section`, `schedule_date`, `time_from`, `time_to`, `created_at`) VALUES
(3, 1, 2, 23, 'BSIS1-A', '2025-04-25', '02:00 PM', '04:00 PM', '2025-04-30 07:14:03');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `role_id` int(11) NOT NULL,
  `specific_role_id` int(11) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `first_name`, `last_name`, `email`, `phone_number`, `gender`, `date_of_birth`, `department_id`, `role_id`, `specific_role_id`, `profile_picture`, `created_at`, `updated_at`) VALUES
(1, 'admin_user', '$2y$10$urjXuy9.LfQIj5hkZO5ImOSNEMNYsu4X3y.avk2hy4ZpNwp9I2o2S', 'John', 'Doe', 'admin@example.com', '1234567890', 'Male', '1990-01-01', NULL, 1, 1, NULL, '2025-01-14 14:38:28', '2025-01-14 15:09:25'),
(2, 'faculty_user', '$2y$10$urjXuy9.LfQIj5hkZO5ImOSNEMNYsu4X3y.avk2hy4ZpNwp9I2o2S', 'Alice', 'Smith', 'faculty@example.com', '0987654321', 'Female', '1985-05-15', 1, 2, 3, NULL, '2025-01-14 14:38:28', '2025-01-14 15:09:29'),
(3, 'registrar_user', '$2y$10$urjXuy9.LfQIj5hkZO5ImOSNEMNYsu4X3y.avk2hy4ZpNwp9I2o2S', 'Bob', 'Brown', 'registrar@example.com', '1122334455', 'Male', '1980-03-10', NULL, 3, 5, NULL, '2025-01-14 14:38:28', '2025-01-14 15:09:32'),
(4, 'building_manager', '$2y$10$urjXuy9.LfQIj5hkZO5ImOSNEMNYsu4X3y.avk2hy4ZpNwp9I2o2S', 'Clara', 'Johnson', 'building_manager@example.com', '2233445566', 'Female', '1995-07-20', NULL, 4, 6, NULL, '2025-01-14 14:38:28', '2025-01-14 15:09:34');

-- --------------------------------------------------------

--
-- Table structure for table `year_terms`
--

CREATE TABLE `year_terms` (
  `id` int(11) NOT NULL,
  `year` year(4) NOT NULL,
  `term_id` tinyint(4) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `subject_id` int(11) DEFAULT `term_id`
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `year_terms`
--

INSERT INTO `year_terms` (`id`, `year`, `term_id`, `created_at`, `subject_id`) VALUES
(1, '2018', 1, '2025-04-01 18:17:51', 1),
(2, '2018', 2, '2025-04-01 18:17:51', 2),
(3, '2019', 1, '2025-04-01 18:19:28', 1),
(4, '2019', 2, '2025-04-01 18:19:28', 2),
(5, '2020', 1, '2025-04-01 18:20:57', 1),
(6, '2020', 2, '2025-04-01 18:20:57', 2),
(7, '2021', 1, '2025-04-01 18:24:32', 1),
(8, '2021', 2, '2025-04-01 18:24:32', 2),
(9, '2022', 1, '2025-04-01 18:24:32', 1),
(10, '2022', 2, '2025-04-01 18:24:32', 2),
(11, '2023', 1, '2025-04-01 18:26:30', 1),
(12, '2023', 2, '2025-04-01 18:26:30', 2),
(13, '2024', 1, '2025-04-01 18:26:30', 1),
(14, '2024', 2, '2025-04-01 18:26:30', 2),
(15, '2025', 1, '2025-04-01 18:26:30', 1),
(16, '2025', 2, '2025-04-01 18:26:30', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `buildings`
--
ALTER TABLE `buildings`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `course_code` (`course_code`),
  ADD KEY `fk_department` (`department_id`);

--
-- Indexes for table `curriculum_years`
--
ALTER TABLE `curriculum_years`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `education_levels`
--
ALTER TABLE `education_levels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `level_name` (`level_name`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `faculty_load`
--
ALTER TABLE `faculty_load`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `majors`
--
ALTER TABLE `majors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `education_level_id` (`education_level_id`),
  ADD KEY `fk_majors_courses` (`course_id`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`program_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_room_number_type` (`room_number`,`room_type`),
  ADD KEY `building_code` (`building_code`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`section_id`),
  ADD UNIQUE KEY `section_name` (`section_name`),
  ADD KEY `fk_section_course` (`course_id`);

--
-- Indexes for table `semesters`
--
ALTER TABLE `semesters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `specific_roles`
--
ALTER TABLE `specific_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `specific_name` (`specific_name`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_unique` (`email`),
  ADD KEY `fk_department_to_student` (`departments_id`),
  ADD KEY `fk_course_to_student` (`course_id`),
  ADD KEY `fk_major_to_student` (`major_id`),
  ADD KEY `fk_education_level_to_student` (`education_level_id`),
  ADD KEY `fk_terms_level_to_student` (`year_terms_id`),
  ADD KEY `classification_code` (`classification_code`);

--
-- Indexes for table `student_classifications`
--
ALTER TABLE `student_classifications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `classification_code` (`classification_code`);

--
-- Indexes for table `student_course`
--
ALTER TABLE `student_course`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `program_id` (`program_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subject_code` (`subject_code`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `substitution_schedule`
--
ALTER TABLE `substitution_schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `specific_role_id` (`specific_role_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `year_terms`
--
ALTER TABLE `year_terms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_major_to_year_terms` (`subject_id`),
  ADD KEY `fk_semester_to_year_terms` (`term_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `curriculum_years`
--
ALTER TABLE `curriculum_years`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `education_levels`
--
ALTER TABLE `education_levels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `faculty_load`
--
ALTER TABLE `faculty_load`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `majors`
--
ALTER TABLE `majors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `program_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `section_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `semesters`
--
ALTER TABLE `semesters`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `specific_roles`
--
ALTER TABLE `specific_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `student_classifications`
--
ALTER TABLE `student_classifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `student_course`
--
ALTER TABLE `student_course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `substitution_schedule`
--
ALTER TABLE `substitution_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `year_terms`
--
ALTER TABLE `year_terms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `fk_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `curriculum_years`
--
ALTER TABLE `curriculum_years`
  ADD CONSTRAINT `curriculum_years_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `majors`
--
ALTER TABLE `majors`
  ADD CONSTRAINT `fk_majors_courses` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `majors_ibfk_1` FOREIGN KEY (`education_level_id`) REFERENCES `education_levels` (`id`);

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`building_code`) REFERENCES `buildings` (`code`);

--
-- Constraints for table `specific_roles`
--
ALTER TABLE `specific_roles`
  ADD CONSTRAINT `specific_roles_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`departments_id`) REFERENCES `departments` (`id`),
  ADD CONSTRAINT `students_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `students_ibfk_3` FOREIGN KEY (`major_id`) REFERENCES `majors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `students_ibfk_4` FOREIGN KEY (`education_level_id`) REFERENCES `education_levels` (`id`),
  ADD CONSTRAINT `students_ibfk_5` FOREIGN KEY (`year_terms_id`) REFERENCES `year_terms` (`id`),
  ADD CONSTRAINT `students_ibfk_6` FOREIGN KEY (`classification_code`) REFERENCES `student_classifications` (`id`);

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `year_terms`
--
ALTER TABLE `year_terms`
  ADD CONSTRAINT `year_terms_ibfk_1` FOREIGN KEY (`term_id`) REFERENCES `semesters` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
