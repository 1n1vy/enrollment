-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 07, 2025 at 12:23 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `enrollment_1`
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `SearchStudent` (IN `p_student_number` VARCHAR(255), IN `p_first_name` VARCHAR(255), IN `p_last_name` VARCHAR(255), IN `p_gender` ENUM('male','female'), IN `p_departments_id` INT, IN `p_course_id` INT, IN `p_major_id` INT, IN `p_classification_code` INT)   BEGIN
    SELECT *
    FROM students
    WHERE
        (p_student_number = '' OR student_number LIKE CONCAT('%', p_student_number, '%')) AND
        (p_first_name = '' OR first_name LIKE CONCAT('%', p_first_name, '%')) AND
        (p_last_name = '' OR last_name LIKE CONCAT('%', p_last_name, '%')) AND
        (p_gender = '' OR gender = p_gender) AND
        (p_departments_id = 0 OR departments_id = p_departments_id) AND
        (p_course_id = 0 OR course_id = p_course_id) AND
        (p_major_id = 0 OR major_id = p_major_id) AND
        (p_classification_code = 0 OR classification_code = p_classification_code);
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
(1, 'BSA', 'Bachelor of Science in Accountancy', 'Focuses on financial reporting, auditing, taxation, and business law for professional accounting careers.', 0, 1),
(2, 'BSAIS', 'Bachelor of Science in Accounting Information System', 'Combines accounting principles with information technology to manage financial data and systems.', 0, 1),
(3, 'BSAgri', 'Bachelor of Science in Agriculture\r\n', 'Studies crop production, animal science, agribusiness, and sustainable farming practices.', 0, 2),
(4, 'BAE', 'Bachelor of Arts in Economics', 'Examines economic theories, policies, and their impact on businesses and society.', 0, 3),
(5, 'BAPolSci', 'Bachelor of Arts in Political Science', 'Explores government systems, political behavior, and public policies.', 0, 3),
(6, 'BSBio', 'Bachelor of Science in Biology', 'Focuses on the study of living organisms, genetics, ecology, and human biology.', 0, 3),
(7, 'BSPsyc', 'Bachelor of Science in Psychology', 'Studies human behavior, mental processes, and psychological theories.', 0, 3),
(8, 'BPA', 'Bachelor of Public Administration', 'Prepares students for leadership roles in government and public service management.', 0, 3),
(9, 'BSBA', 'Bachelor of Science in Business Administration', 'Covers management, finance, marketing, and operations in business organizations.', 0, 4),
(10, 'BSEntrep', 'Bachelor of Science in Entrepreneurship', 'Focuses on business innovation, startup development, and financial management.', 0, 4),
(11, 'BSREM', 'Bachelor of Science in Real Estate Management', 'Studies property valuation, real estate laws, and property management.', 0, 4),
(12, 'BAB', 'Bachelor of Arts in Broadcasting', 'Explores radio, television, and digital media production and journalism.', 0, 5),
(13, 'BAC', 'Bachelor of Arts in Communication', 'Covers media studies, public relations, and corporate communication.', 0, 5),
(14, 'BAJ', 'Bachelor of Arts in Journalism', 'Focuses on news writing, reporting, and investigative journalism.', 0, 5),
(15, 'BLIS', 'Bachelor of Library and Information Science', 'Teaches library management, information organization, and digital archiving.', 0, 6),
(16, 'BSCS', 'Bachelor of Science in Computer Science', 'Studies programming, algorithms, and software development.', 0, 6),
(17, 'BSEMC', 'Bachelor of Science in Entertainment and Multimedia Computing', 'Focuses on game development, animation, and digital media technology.', 0, 6),
(18, 'BSIT', 'Bachelor of Science in Information Technology', 'Covers computer networking, cybersecurity, and software applications.', 0, 6),
(19, 'BSIS', 'Bachelor of Science in Information System', 'Focuses on business technology solutions, database management, and system analysis.', 0, 6),
(20, 'BSCrim', 'Bachelor of Science in Criminology', 'Studies crime detection, law enforcement, and forensic science.', 0, 7),
(21, 'BEED', 'Bachelor of Elementary Education', 'Prepares students to teach in elementary schools with a focus on pedagogy and subject mastery.', 0, 8),
(22, 'BSED', 'Bachelor of Secondary Education', 'Trains students to teach specific subjects at the high school level.', 0, 8),
(23, 'BSArchi', 'Bachelor of Science in Architecture ', 'Covers architectural design, construction, and urban planning.', 0, 9),
(24, 'BSAstro', 'Bachelor of Science in Astronomy', 'Studies celestial objects, space science, and astrophysics.', 0, 9),
(25, 'BSCE', 'Bachelor of Science in Civil Engineering', 'Focuses on infrastructure design, construction, and structural analysis.', 0, 9),
(26, 'BSElectricEngr', 'Bachelor of Science in Electrical Engineering', 'Studies electrical systems, power generation, and circuit design.', 0, 9),
(27, 'BSElectronEngr', 'Bachelor of Science in Electronics Engineering', 'Covers electronic circuits, communication systems, and semiconductor technology.', 0, 9),
(28, 'BSIE', 'Bachelor of Science in Industrial Engineering', 'Focuses on optimizing production processes, systems, and management.', 0, 9),
(29, 'BSME', 'Bachelor of Science in Mechanical Engineering ', 'Studies machine design, thermodynamics, and manufacturing processes.', 0, 9),
(30, 'BSMedTech', 'Bachelor of Science in Medical Technology', 'Trains students in laboratory diagnostics and clinical procedures.', 0, 10),
(31, 'DIM', 'Diploma in Midwifery', 'Prepares students for maternal care, childbirth assistance, and postnatal support.', 0, 11),
(32, 'BMCC', 'Bachelor of Music in Choral Conducting', 'Focuses on leading choirs, vocal techniques, and choral arrangement.', 0, 12),
(33, 'BMME', 'Bachelor of Music in Music Education', 'Prepares students to teach music theory, performance, and composition.', 0, 12);

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
(1, 1, '2023', '2024', 'Curriculum for BSA program starting from 2023.'),
(2, 2, '2024', '2024', 'Curriculum for BSAIS program starting from 2024.'),
(3, 3, '2025', '2026', 'Curriculum for BSAgri program starting from 2025.'),
(6, 5, '2024', '2026', 'enroll most shit updated');

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
(1, 'College of Accountancy', 'COA', 'Studies financial reporting, auditing, taxation, and business ethics to prepare for careers in accounting and finance.', '2025-04-01 03:10:45', '2025-04-01 03:11:20'),
(2, 'College of Agriculture', 'COAg', ' Focuses on crop production, animal husbandry, agribusiness, and sustainable farming practices.', '2025-04-01 03:10:45', '2025-04-01 03:11:34'),
(3, 'College of Arts and Sciences', 'CAS', 'Covers a broad range of disciplines including humanities, social sciences, natural sciences, and mathematics.', '2025-04-01 03:14:23', '2025-04-01 03:14:23'),
(4, 'College of Business Administration', 'CBA', 'Teaches management, marketing, finance, and entrepreneurship to develop leadership and business skills.', '2025-04-01 03:14:23', '2025-04-01 03:14:23'),
(5, 'College of Communication', 'COC', 'Explores media, journalism, public relations, and digital communication strategies.', '2025-04-01 03:15:32', '2025-04-01 03:16:10'),
(6, 'College of Informatics and Computing Studies', 'CICS', 'Focuses on computer science, information systems, and data analytics for technological innovation.', '2025-04-01 03:15:32', '2025-04-01 03:16:20'),
(7, 'College of Criminology', 'COCr', 'Studies crime prevention, law enforcement, forensic science, and the criminal justice system.\r\n\r\n', '2025-04-01 03:18:41', '2025-04-01 03:18:41'),
(8, 'College of Education', 'COE', 'Prepares students for teaching careers through pedagogy, curriculum development, and educational psychology.', '2025-04-01 03:18:41', '2025-04-01 03:18:41'),
(9, 'College of Engineering and Architecture', 'CEA', 'Covers engineering principles, architectural design, and construction technology.', '2025-04-01 03:20:15', '2025-04-01 03:20:15'),
(10, 'College of Medical Technology', 'CMT', 'Focuses on laboratory testing, diagnostics, and clinical procedures for disease detection.', '2025-04-01 03:20:15', '2025-04-01 03:20:15'),
(11, 'College of Midwifery', 'CMW', 'Trains students in maternal care, childbirth assistance, and neonatal health.', '2025-04-01 03:22:30', '2025-04-01 03:22:30'),
(12, 'College of Music', 'COM', 'Studies music theory, performance, composition, and music education.', '2025-04-01 03:22:30', '2025-04-01 03:22:30'),
(13, 'College of Nursing', 'CON', 'Prepares students for patient care, medical procedures, and healthcare management.', '2025-04-01 03:23:35', '2025-04-01 03:23:35'),
(14, 'College of Physical Therapy', 'CPT', 'Focuses on rehabilitation, movement science, and therapy for physical disabilities.', '2025-04-01 03:23:35', '2025-04-01 03:23:35'),
(15, 'College of Respiratory Therapy', 'CRT', 'Specializes in pulmonary care, respiratory treatments, and critical care support.', '2025-04-01 03:24:48', '2025-04-01 03:24:48'),
(16, 'School of International Relations', 'SIR', 'Studies diplomacy, global politics, and international cooperation.', '2025-04-01 03:24:48', '2025-04-01 03:24:48');

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
(3, 'Doctoral', 'Highest level of academic degree.', '2025-01-08 10:57:40', '2025-01-08 10:57:40'),
(4, 'Primary Level', 'Refers to Elementary Education', '2025-04-01 09:46:56', '2025-04-01 09:46:56'),
(5, 'Junior High School', 'Covers lower secondary education', '2025-04-01 09:46:56', '2025-04-01 09:46:56'),
(6, 'Senior High School', 'Covers upper secondary education', '2025-04-01 09:46:56', '2025-04-01 09:46:56');

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
  `department_id` int(11) NOT NULL,
  `role` enum('Professor','Lecturer','Assistant Professor') NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`id`, `username`, `password`, `first_name`, `last_name`, `email`, `phone_number`, `gender`, `date_of_birth`, `department_id`, `role`, `profile_picture`, `created_at`, `updated_at`) VALUES
(1, 'faculty1', '$2y$10$urjXuy9.LfQIj5hkZO5ImOSNEMNYsu4X3y.avk2hy4ZpNwp9I2o2S', 'Jane', 'Smith', 'jane.smith@university.com', '987-654-3210', 'Female', '1980-08-15', 1, 'Professor', '/images/faculty1.jpg', '2025-01-08 10:57:40', '2025-01-08 10:57:40');

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
(1, 9, 1, 'Financial Management', 'Focuses on investment strategies, financial planning, risk management, and corporate finance.', '2025-04-01 05:48:55', '2025-04-01 05:48:55'),
(2, 9, 1, 'Human Resource Development Management', 'Studies recruitment, employee relations, labor laws, and organizational behavior.', '2025-04-01 05:48:55', '2025-04-01 05:48:55'),
(3, 9, 1, 'Legal Management', 'Combines business principles with legal studies, including contracts, corporate law, and regulatory compliance.', '2025-04-01 05:48:55', '2025-04-01 05:48:55'),
(4, 9, 1, 'Marketing Management', 'Covers market research, consumer behavior, branding, and digital marketing strategies.', '2025-04-01 05:48:55', '2025-04-01 05:48:55'),
(5, 17, 1, 'Digital Animation Technology', 'Focuses on 2D/3D animation, visual effects, and character design for digital media.', '2025-04-01 05:48:55', '2025-04-01 05:48:55'),
(6, 17, 1, 'Game Development', 'Studies programming, game design, and interactive media for video game creation.', '2025-04-01 05:48:55', '2025-04-01 05:48:55'),
(7, 21, 1, 'Content Courses', 'Covers core subjects such as mathematics, science, language, and social studies for elementary-level teaching.', '2025-04-01 05:48:55', '2025-04-01 05:48:55'),
(8, 21, 1, 'Preschool Education', 'Focuses on early childhood development, learning strategies, and play-based education.', '2025-04-01 05:48:55', '2025-04-01 05:48:55'),
(9, 21, 1, 'Special Education', 'Prepares teachers to support students with disabilities and special learning needs.', '2025-04-01 05:48:55', '2025-04-01 05:48:55'),
(10, 22, 1, 'Music, Arts and PE', 'Studies music theory, visual arts, physical education, and performing arts for high school teaching.', '2025-04-01 05:48:55', '2025-04-01 05:48:55'),
(11, 22, 1, 'English', 'Focuses on literature, linguistics, and English language teaching methodologies.', '2025-04-01 05:48:55', '2025-04-01 05:48:55'),
(12, 22, 1, 'Filipino', 'Studies Filipino language, literature, and pedagogy for high school instruction.', '2025-04-01 05:48:55', '2025-04-01 05:48:55'),
(13, 22, 1, 'Mathematics', 'Covers algebra, calculus, statistics, and mathematical teaching techniques.', '2025-04-01 05:48:55', '2025-04-01 05:48:55'),
(14, 22, 1, 'Science', 'Focuses on biology, chemistry, physics, and environmental science for secondary education.', '2025-04-01 05:48:55', '2025-04-01 05:48:55'),
(15, 22, 1, 'Social Studies', 'Studies history, geography, political science, and cultural studies for secondary-level teaching.', '2025-04-01 05:48:55', '2025-04-01 05:48:55'),
(16, 22, 1, 'Technology and Livelihood Education', 'Covers vocational skills, entrepreneurship, and technology-based education.', '2025-04-01 05:48:55', '2025-04-01 05:48:55');

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
  `section_id` int(11) DEFAULT NULL,
  `year_level` text DEFAULT NULL,
  `term` text DEFAULT NULL,
  `school_year` text DEFAULT NULL,
  `subject_type` text DEFAULT NULL,
  `subject_component` text DEFAULT NULL,
  `schedule_day` text DEFAULT NULL,
  `schedule_time` text DEFAULT NULL,
  `is_international` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`program_id`, `department_id`, `course_id`, `course_program`, `subject_id`, `major_id`, `curriculum_id`, `section_id`, `year_level`, `term`, `school_year`, `subject_type`, `subject_component`, `schedule_day`, `schedule_time`, `is_international`) VALUES
(6, 1, 1, 1, 7, 0, 1, 2, '2nd', '1st', '2024-2025', 'Reg', 'Lec', 'M,W,F', '07:27 AM - 08:28 AM', 0);

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
  `building_location` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `building_code`, `floor`, `room_number`, `room_capacity`, `no_subject`, `room_conflict`, `room_type`, `status`, `description`, `last_inspection_date`, `created_at`, `updated_at`, `school_year`, `term`, `building`, `building_location`) VALUES
(1, 'MAIN', '1st Floor', '101', 35, 0, 0, 'Laboratory Room', 'AVAILABLE', 'Computer Laboratory', '0000-00-00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2024', '1st-Semester', 'MAIN', 'Main'),
(2, 'PSB', '4th Floor', '420', 30, 0, 0, 'Lecture Room', 'OCCUPIED', 'This room is occupied for College of Medicine Lecture room', '0000-00-00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2025', '2nd-Semester', '', 'PSB'),
(3, 'BLDG A', 'Ground', '248', 40, 0, 0, 'Library', 'OCCUPIED', 'Reading Center', '0000-00-00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2025', '2nd-Semester', '', 'IS Bldg. A'),
(4, 'MAIN', '2nd Floor', '212', 35, 0, 0, 'Lecture Room', 'AVAILABLE', 'This room is occupied for College of Arts and Sciences', '0000-00-00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2025', '2nd-Semester', 'MAIN', 'Main'),
(5, 'PSB', '3rd Floor', '303', 30, 0, 0, 'Laboratory Room', 'OCCUPIED', 'Operating Laboratory', '0000-00-00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2025', '2nd-Semester', '', 'PSB'),
(6, 'SOM', '4th Floor', '411', 40, 0, 0, 'Lecture Room', 'AVAILABLE', 'Lecture Room for Management students', '0000-00-00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2024', '1st-Semester', '', 'SOM'),
(7, 'BLDG B', '2nd Floor', '208', 40, 0, 0, 'Lecture Room', 'OCCUPIED', 'Lecture Room for IS Students', '0000-00-00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2024', '1st-Semester', '', 'IS Bldg. B');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `semester` varchar(255) DEFAULT NULL,
  `school_start_year` year(4) DEFAULT NULL,
  `school_end_year` year(4) DEFAULT NULL,
  `section_name` varchar(255) DEFAULT NULL,
  `section_code` varchar(255) DEFAULT NULL,
  `schedule_days` varchar(255) DEFAULT NULL,
  `max_students` int(11) DEFAULT NULL,
  `start` time DEFAULT NULL,
  `end` time DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT NULL,
  `instructor_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `is_paid` enum('yes','no') NOT NULL DEFAULT 'no',
  `payment_mode` enum('installment','fully-paid','Awaiting-Payment') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `student_number`, `first_name`, `last_name`, `middle_name`, `gender`, `birthdate`, `age`, `religion`, `email`, `phone`, `address`, `departments_id`, `course_id`, `major_id`, `education_level_id`, `year_terms_id`, `classification_code`, `student_status`, `created_at`, `updated_at`, `enrollment_status`, `advised_status`, `status`, `maximum_units`, `school_year`, `term`, `is_paid`, `payment_mode`) VALUES
(1, '20-12345-678', 'John', 'Doe', 'Michael', 'male', '2002-03-31', 22, 1, 'john.doe@email.ph', '123-456-789', '123 Main st.', 1, 1, 3, 1, 16, 4, 'old student', '2025-03-31 23:34:49', '2025-03-31 23:34:49', 'enrolled', 1, 'Active', 12, '2024-2025', '1st sem', 'no', 'fully-paid'),
(2, '18-12345-100', 'Daniel', 'Padilla', 'Cruz', 'male', '2000-04-26', 25, 0, 'daniel.padilla@email.ph', '09171234567', '123 Manila St., QC', 9, 26, NULL, 1, 16, 3, 'old student', '2025-04-01 19:41:36', '2025-04-01 19:41:36', 'pending', 1, 'Active', 18, '2024-2025', '1st sem', 'no', 'Awaiting-Payment'),
(3, '18-23456-101', 'Kathryn', 'Chandria', 'Bernardo', 'female', '2000-03-26', 25, 1, 'kathryn.bernardo@email.ph', '0918-234-5678', '456 Makati Ave., MNL', 1, 1, NULL, 1, 14, 1, 'old student', '2025-04-01 19:41:36', '2025-04-01 19:41:36', 'not enrolled', 0, 'Active', 25, '2024-2025', '1st sem', 'no', 'Awaiting-Payment'),
(4, '19-34567-102', 'Nadine', 'Lustre', 'Alexis', 'female', '2001-10-31', 24, 1, 'nadine.lustre@email.ph', '0917-345-6789', '789 Pasig Blvd., MNL', 6, 18, NULL, 1, 16, 2, 'transferee', '2025-04-01 19:41:36', '2025-04-01 19:41:36', 'enrolled', 1, 'Active', 21, '2024-2025', '2nd sem', 'no', 'installment'),
(5, '19-45678-103', 'James', 'Alexander', 'Reid', 'male', '2001-03-30', 24, 1, 'james.reid@email.ph', '0917-456-7890', '567 BGC St., Taguig', 8, 21, 8, 1, 14, 3, 'new student', '2025-04-01 19:41:36', '2025-04-01 19:41:36', 'enrolled', 1, 'Active', 18, '2024-2025', '2nd sem', 'no', 'Awaiting-Payment'),
(6, '19-56789-104', 'Enrique', 'Marino', 'Gil', 'male', '2001-05-11', 24, 0, 'enrique.gil@email.ph', '0919-567-8901', '654 Cebu St., Cebu', 1, 2, NULL, 1, 15, 4, 'new student', '2025-04-01 19:41:36', '2025-04-01 19:41:36', 'pending', 1, 'Active', 12, '2024-2025', '2nd sem', 'no', 'fully-paid'),
(7, '19-67890-105', 'Liza', 'Hope', 'Soberano', 'female', '2001-01-04', 24, 1, 'liza.soberano@email.ph', '0918-678-9012', '321 Davao St., Davao', 6, 19, NULL, 1, 14, 3, 'old student', '2025-04-01 19:41:36', '2025-04-01 19:41:36', 'pending', 1, 'Active', 18, '2024-2025', '1st sem', 'no', 'Awaiting-Payment'),
(8, '20-78901-106', 'Sarah', 'Geronimo', 'Asher', 'female', '2002-07-25', 0, 1, 'sarah.geronimo@email.ph', '0920-789-0123', '876 Pampanga Rd., Pampanga', 10, 30, NULL, 1, 13, 2, 'transferee', '2025-04-01 19:41:36', '2025-04-01 19:41:36', 'not enrolled\n', 0, 'Active', 21, '2024-2025', '1st sem', 'no', 'Awaiting-Payment'),
(9, '20-89012-107', 'Vice', 'Jose Marie', 'Ganda', 'male', '2002-03-31', 23, 0, 'vice.ganda@email.ph', '0917-890-1234', '234 QC Circle, QC', 9, 28, NULL, NULL, NULL, 3, 'old student', '2025-04-01 19:41:36', '2025-04-01 19:41:36', 'not enrolled\n', 0, 'Active', 18, '2024-2025', '1st sem', 'no', 'Awaiting-Payment'),
(10, '20-90123-108', 'Alden', 'Richard', 'Richards', 'male', '2003-01-02', 22, 1, 'alden.richards@email.ph', '0921-901-2345', '876 Alabang Rd., MNL', 6, 17, 5, 1, 14, 4, 'cross enrollee', '2025-04-01 19:41:36', '2025-04-01 19:41:36', 'not enrolled\n', 0, 'Active', 12, '2024-2025', '1st sem', 'no', 'Awaiting-Payment'),
(11, '21-01234-109', 'Maine', 'Nicomaine', 'Mendoza', 'female', '2003-03-03', 22, 1, 'maine.mendoza@email.ph', '0917-012-3456', '543 Bacolod St., Bacolod', 5, 13, NULL, 1, 16, 4, 'old student', '2025-04-01 19:41:36', '2025-04-01 19:41:36', 'enrolled', 1, 'Active', 12, '2024-2025', '1st sem', 'no', 'Awaiting-Payment'),
(12, '21-12345-110', 'Kim', 'Kimberly', 'Chiu', 'female', '2003-04-19', 22, 1, 'kim.chiu@email.ph', '0923-123-4567', '678 Iloilo Ave., Iloilo', 2, NULL, NULL, NULL, NULL, 3, 'old student', '2025-04-01 19:41:36', '2025-04-01 19:41:36', 'enrolled', 1, 'Inactive', 18, '2024-2025', '1st sem', '', 'Awaiting-Payment'),
(13, '22-23456-111', 'Coco', 'Rodel', 'Martin', 'male', '2004-11-01', 21, 0, 'coco.martin@email.ph', '0924-234-5678', '890 Zambales St., Zambales', 13, NULL, NULL, NULL, NULL, 1, 'new student', '2025-04-01 19:41:36', '2025-04-01 19:41:36', 'not enrolled\n', 0, 'Inactive', 25, '2024-2025', '2nd sem', 'no', 'Awaiting-Payment'),
(14, '23-34567-112', 'Anne', 'Ojales', 'Curtis', 'female', '2002-02-17', 23, 1, 'anne.curtis@email.ph', '09253456789', '123 La Union St., La Union', NULL, NULL, NULL, NULL, NULL, 3, 'transferee', '2025-04-01 20:00:44', '2025-04-01 20:00:44', 'not enrolled\n', 0, 'Inactive', 18, '2023-2024', '2nd sem', '', 'Awaiting-Payment'),
(15, '23-45678-113', 'Jericho', 'Vibar', 'Rosales', 'male', '2004-09-22', 21, 0, 'jericho.rosales@email.ph', '09184567890', '456 CDO St., CDO', NULL, NULL, NULL, NULL, NULL, 2, 'second program', '2025-04-01 20:00:44', '2025-04-01 20:00:44', 'not enrolled\n', 0, 'Inactive', 21, '2023-2024', '2nd sem', '', 'Awaiting-Payment'),
(16, '21-45678-114', 'Angel', 'Colmenares', 'Locsin', 'male', '2003-04-23', 22, 1, 'angel.locsin@email.ph', '09265678901', '567 Davao St., Davao', NULL, NULL, NULL, NULL, NULL, 1, 'new student', '2025-04-01 20:00:44', '2025-04-01 20:00:44', 'not enrolled\n', 0, 'Inactive', 25, '2023-2024', 'Summer', '', 'Awaiting-Payment'),
(17, '20-56789-115', 'Bea', 'Phylbert', 'Alonzo', 'female', '2001-10-17', 24, 0, 'bea.alonzo@email.ph', '09276789012', '789 Tagaytay Rd., Tagaytay', NULL, NULL, NULL, NULL, NULL, 4, 'old student', '2025-04-01 20:00:44', '2025-04-01 20:00:44', 'not enrolled\n', 0, 'Graduate', 12, '2023-2024', 'Summer', '', 'Awaiting-Payment'),
(18, '19-78901-116', 'Dingdong', 'Jose', 'Dantes', 'male', '2002-08-02', 23, 1, 'dingdong.dantes@email.ph', '09287890123', '321 Laguna Blvd., Laguna', NULL, NULL, NULL, NULL, NULL, 3, 'old student', '2025-04-01 20:00:44', '2025-04-01 20:00:44', 'not enrolled\n', 0, 'Inactive', 18, '2023-2024', 'Summer', '', 'Awaiting-Payment'),
(19, '18-89012-117', 'Marian', 'Gracia', 'Rivera', 'female', '2004-08-12', 21, 0, 'marian.rivera@email.ph', '09178901234', '654 Cavite Ave., Cavite', NULL, NULL, NULL, NULL, NULL, 2, 'transferee', '2025-04-01 20:00:44', '2025-04-01 20:00:44', 'not enrolled\n', 0, 'Inactive', 21, '2023-2024', 'Summer', '', 'Awaiting-Payment'),
(20, '24-90123-118', 'Piolo', 'Jose', 'Pascual', 'male', '2000-01-12', 25, 1, 'piolo.pascual@email.ph', '09299012345', '876 Batangas Rd., Batangas', NULL, NULL, NULL, NULL, NULL, 4, 'cross enrollee', '2025-04-01 20:00:44', '2025-04-01 20:00:44', 'not enrolled\n', 0, 'Inactive', 12, '2023-2024', 'Summer', '', 'Awaiting-Payment'),
(21, '25-01234-119', 'Toni', 'Celestine', 'Gonzaga', 'female', '2002-01-20', 23, 1, 'toni.gonzaga@email.ph	', '09180123456', '543 Rizal St., Rizal', NULL, NULL, NULL, NULL, NULL, 1, 'new student', '2025-04-01 20:00:44', '2025-04-01 20:00:44', 'not enrolled\n', 0, 'Inactive', 25, '2023-2024', 'Summer', '', 'Awaiting-Payment');

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
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `subject_code` varchar(50) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `department_id` int(11) NOT NULL,
  `department_code` varchar(10) DEFAULT NULL,
  `created_by` varchar(100) NOT NULL,
  `status` varchar(50) DEFAULT 'active',
  `is_requested_subject` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `lec_units` int(11) NOT NULL,
  `lab_units` int(11) NOT NULL,
  `units` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `subject_code`, `subject_name`, `department_id`, `department_code`, `created_by`, `status`, `is_requested_subject`, `created_at`, `updated_at`, `lec_units`, `lab_units`, `units`) VALUES
(1, 'ccc111-18', 'Introduction to Computing', 6, 'CICS', 'Dr. De Luna', 'active', 0, '2025-04-01 08:27:22', '2025-04-05 07:25:38', 2, 0, 2),
(2, 'ccl111-18', 'Introduction to Computing(Lab)\r\n', 6, 'CICS', 'Dr. De Luna', 'active', 0, '2025-04-01 08:27:22', '2025-04-05 07:26:02', 0, 1, 1),
(3, 'ccc112-18', 'Fundamentals of Programming', 6, 'CICS', 'Prof. Lex', 'active', 0, '2025-04-01 09:17:16', '2025-04-05 07:26:08', 2, 0, 2),
(4, 'ccl112-18', 'Fundamentals of Programming(Lab)', 6, 'CICS', 'Prof. Lex', 'active', 0, '2025-04-01 09:17:16', '2025-04-05 07:30:44', 0, 1, 1),
(5, 'cit113-18', 'Fundamentals of Information Technology', 6, 'CICS', 'Dr. Smith', 'active', 0, '2025-04-01 09:17:16', '2025-04-05 07:32:09', 3, 3, 3),
(6, 'gectcw-18', 'The Contemporary World', 3, 'CAS', 'Prof. Cruz', 'active', 0, '2025-04-01 09:17:16', '2025-04-05 08:01:52', 3, 0, 3),
(7, 'gecmmw-18', 'Math in the Modern World', 1, 'COA', 'Dr. Julie', 'active', 0, '2025-04-01 09:17:16', '2025-04-05 08:01:52', 3, 0, 3),
(8, 'gecsts-18', 'Science, Technology, Society\r\n', 3, 'CAS', 'Prof. John', 'active', 0, '2025-04-01 09:17:16', '2025-04-05 08:01:52', 3, 0, 3),
(9, 'konfil-18', 'Kontekstwalisadong Komunikasyon sa Filipino', 3, 'CAS', 'Prof. Cruz', 'active', 0, '2025-04-01 09:17:16', '2025-04-05 08:01:52', 3, 0, 3),
(10, 'pe 1pf-18', 'Physical Fitness', 8, 'COE', 'Prof. Sauza', 'active', 0, '2025-04-01 09:17:16', '2025-04-05 08:01:52', 2, 0, 2),
(11, 'nstp1-18', 'National Service Training Program 1', 3, 'CAS', 'Prof. Sauza', 'active', 0, '2025-04-01 09:17:16', '2025-04-05 08:01:52', 3, 0, 3),
(12, 'ccc121-18', 'Intermediate Programming\r\n', 6, 'CICS', 'Prof. AJ', 'active', 0, '2025-04-01 09:17:16', '2025-04-05 08:01:52', 2, 0, 2),
(13, 'ccl121-18', 'Intermediate Programming(Lab)\r\n', 6, 'CICS', 'Prof. AJ', 'active', 0, '2025-04-01 09:17:16', '2025-04-05 08:01:52', 0, 1, 1),
(14, 'cit122-18', 'Fundamentals of Network\r\n', 6, 'CICS', 'Prof. Myra', 'active', 0, '2025-04-01 09:17:16', '2025-04-05 08:19:36', 2, 0, 2),
(15, 'itl122-18', 'Fundamentals of Network(Lab)\r\n', 6, 'CICS', 'Prof. Myra', 'active', 0, '2025-04-01 09:17:16', '2025-04-05 08:19:36', 0, 1, 1),
(16, 'cit123-18', 'Discrete Structure\r\n', 6, 'CICS', 'Dr. Hagos', 'active', 0, '2025-04-01 09:17:16', '2025-04-05 08:19:36', 3, 0, 3),
(17, 'gecuts-18', 'Understanding Self\r\n', 3, 'CAS', 'Prof. Cruz', 'active', 0, '2025-04-01 09:17:16', '2025-04-05 08:19:36', 3, 0, 3),
(18, 'gecrph-18', 'Reading in Philippine History\r\n', 3, 'CAS', 'Dr. Smith', 'active', 0, '2025-04-01 09:17:16', '2025-04-05 08:19:36', 3, 0, 0),
(19, 'gecaap-18', 'Art Appreciation\r\n', 3, 'CAS', 'Prof. Cruz', 'active', 0, '2025-04-01 09:17:16', '2025-04-05 08:19:36', 3, 0, 3),
(20, 'fildis-18', 'Filipino sa Iba\'t-ibang Disiplina\r\n', 3, 'CAS', 'Dr. Smith', 'active', 0, '2025-04-01 09:17:16', '2025-04-05 08:19:36', 3, 0, 3),
(21, 'pe 2ra-18', 'Rhythmic Activities\r\n', 8, 'COE', 'Dr. Smith', 'active', 0, '2025-04-01 09:17:16', '2025-04-05 08:19:36', 2, 0, 2),
(22, 'nstp2-18', 'National Service Training Program 2\r\n', 3, 'CAS', 'Dr. Smith', 'active', 0, '2025-04-01 09:17:16', '2025-04-05 08:19:36', 3, 0, 3),
(23, 'ccc211-18', 'Information Management 1\r\n', 6, 'CICS', 'Dr. Smith', 'active', 0, '2025-04-01 09:17:16', '2025-04-05 08:19:36', 2, 0, 2),
(24, 'ccl211-18', 'Information Management 1 (Lab)\r\n', 6, 'CICS', 'Dr. Smith', 'active', 0, '2025-04-01 09:17:16', '2025-04-05 08:19:36', 0, 1, 1),
(25, 'ccc212-18', 'Data Structures and Algorithms\r\n', 6, 'CICS', 'Dr. Smith', 'active', 0, '2025-04-01 09:17:16', '2025-04-05 08:19:36', 2, 0, 2),
(26, 'ccl212-18', 'Data Structures and Algorithms (Lab)', 6, 'CICS', '', 'active', 0, '2025-04-01 09:17:16', '2025-04-05 08:15:02', 0, 1, 1),
(27, 'cit213-18', 'Human Computer Interaction\r\n', 6, 'CICS', '', 'active', 0, '2025-04-01 09:40:50', '2025-04-05 08:15:02', 2, 0, 2),
(28, 'itl213-18', 'Human Computer Interaction (Lab)\r\n', 6, 'CICS', '', 'active', 0, '2025-04-01 09:40:50', '2025-04-05 08:15:02', 1, 0, 1),
(29, 'citel1-18', 'IT Elective 1\r\n', 6, 'CICS', '', 'active', 0, '2025-04-01 09:40:50', '2025-04-05 08:15:02', 2, 0, 2),
(30, 'itlel1-18', 'IT Elective 1(Lab)\r\n', 6, 'CICS', '', 'active', 0, '2025-04-01 09:40:50', '2025-04-05 08:15:02', 0, 1, 1),
(31, 'gecpco-18', 'Purposive Communication\r\n', 3, 'CAS', '', 'active', 0, '2025-04-01 09:40:50', '2025-04-05 08:15:02', 3, 0, 3),
(32, 'geceth-18', 'Ethics\r\n', 3, 'CAS', '', 'active', 0, '2025-04-01 09:40:50', '2025-04-05 08:15:02', 3, 0, 3),
(33, 'soslit-18', 'Sosyedad at Literatura\r\n', 3, 'CAS', '', 'active', 0, '2025-04-01 09:40:50', '2025-04-05 08:15:02', 3, 0, 3),
(34, 'pe 3id-18', 'Individual Sports / Games\r\n', 8, 'COE', '', 'active', 0, '2025-04-01 09:40:50', '2025-04-05 08:15:02', 2, 0, 2),
(35, 'cit221-18', 'Object Oriented Programming\r\n', 6, 'CICS', '', 'active', 0, '2025-04-01 09:40:50', '2025-04-05 08:15:02', 2, 0, 2),
(36, 'itl221-18', 'Object Oriented Programming (Lab)\r\n', 6, 'CICS', '', 'active', 0, '2025-04-01 09:40:50', '2025-04-05 08:15:02', 0, 1, 1),
(37, 'cit222-18', 'Operating Systems\r\n', 6, 'CICS', '', 'active', 0, '2025-04-01 09:40:50', '2025-04-05 08:15:02', 2, 0, 2),
(38, 'itl222-18', 'Operating Systems (Lab)', 6, 'CICS', '', 'active', 0, '2025-04-01 09:40:50', '2025-04-05 08:15:02', 0, 1, 1),
(39, 'cit223-18', 'Information Management 2\r\n', 6, 'CICS', '', 'active', 0, '2025-04-01 09:40:50', '2025-04-05 08:42:10', 2, 0, 2),
(40, 'itl223-18', 'Information Management 2(Lab)\r\n', 6, 'CICS', '', 'active', 0, '2025-04-01 09:40:50', '2025-04-05 08:42:06', 0, 1, 1),
(41, 'cit224-18', 'System Integration and Architecture 1\r\n', 6, 'CICS', '', 'active', 0, '2025-04-01 09:40:50', '2025-04-05 08:41:45', 2, 0, 2),
(42, 'itl224-18', 'System Integration and Architecture 1(Lab)\r\n', 6, 'CICS', '', 'active', 0, '2025-04-01 09:40:50', '2025-04-05 08:41:33', 0, 1, 1),
(43, 'cit225-18', 'Quantitative Methods\r\n', 3, 'CAS', '', 'active', 0, '2025-04-01 09:40:50', '2025-04-05 08:41:21', 0, 3, 3),
(44, 'citel2-18', 'IT Elective 2\r\n', 6, 'CICS', '', 'active', 0, '2025-04-01 09:40:50', '2025-04-05 08:41:09', 2, 0, 2),
(45, 'itlel2-18', 'IT Elective 2 (Lab)\r\n', 6, 'CICS', '', 'active', 0, '2025-04-01 09:40:50', '2025-04-05 08:41:02', 0, 1, 1),
(46, 'geclwr-18', 'The Life and Works of Rizal\r\n', 3, 'CAS', '', 'active', 0, '2025-04-01 09:40:50', '2025-04-05 08:40:55', 3, 0, 3);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sections_instructor` (`instructor_id`),
  ADD KEY `fk_sections_course` (`course_id`);

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
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subject_code` (`subject_code`),
  ADD KEY `department_id` (`department_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
-- AUTO_INCREMENT for table `majors`
--
ALTER TABLE `majors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `program_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

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
  ADD CONSTRAINT `curriculum_years_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`);

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
-- Constraints for table `sections`
--
ALTER TABLE `sections`
  ADD CONSTRAINT `fk_sections_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  ADD CONSTRAINT `fk_sections_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `users` (`id`);

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
  ADD CONSTRAINT `students_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  ADD CONSTRAINT `students_ibfk_3` FOREIGN KEY (`major_id`) REFERENCES `majors` (`id`),
  ADD CONSTRAINT `students_ibfk_4` FOREIGN KEY (`education_level_id`) REFERENCES `education_levels` (`id`),
  ADD CONSTRAINT `students_ibfk_5` FOREIGN KEY (`year_terms_id`) REFERENCES `year_terms` (`id`),
  ADD CONSTRAINT `students_ibfk_6` FOREIGN KEY (`classification_code`) REFERENCES `student_classifications` (`id`);

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`);

--
-- Constraints for table `year_terms`
--
ALTER TABLE `year_terms`
  ADD CONSTRAINT `year_terms_ibfk_1` FOREIGN KEY (`term_id`) REFERENCES `semesters` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
