-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 16, 2025 at 06:59 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

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
('INNOV', 'Innovation Center', 'A hub for research and innovation in various fields.'),
('TECH', 'Technology Building', 'The main building for all technology and engineering courses.');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course_code` varchar(50) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_international` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_code`, `course_name`, `description`, `is_international`) VALUES
(1, 'BSIT', 'Bachelor of Science in Information Technology', 'A program designed to equip students with IT skills.', 0),
(2, 'BSME', 'Bachelor of Science in Mechanical Engineering', 'A program focused on the fundamentals of mechanical engineering.', 0),
(3, 'MSDS', 'Master of Science in Data Science', 'Graduate program focused on advanced data analysis and machine learning techniques.', 1);

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
(1, 1, '2023', '2024', 'Curriculum for BSIT program starting from 2023.'),
(2, 2, '2024', NULL, 'Curriculum for BSME program starting from 2024.'),
(3, 3, '2025', '2026', 'Curriculum for MSDS program starting from 2025.');

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
(1, 'Computer Science', 'CS', 'Department responsible for the IT and computer science programs.', '2025-01-08 10:57:40', '2025-01-08 10:57:40'),
(2, 'Mechanical Engineering', 'ME', 'Department focusing on mechanical engineering courses.', '2025-01-08 10:57:40', '2025-01-08 10:57:40');

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
-- Table structure for table `majors`
--

CREATE TABLE `majors` (
  `id` int(11) NOT NULL,
  `education_level_id` int(11) NOT NULL,
  `major_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `majors`
--

INSERT INTO `majors` (`id`, `education_level_id`, `major_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'Information Technology', 'Focuses on computer systems, software, and technology infrastructure.', '2025-01-08 10:57:40', '2025-01-08 10:57:40'),
(2, 1, 'Mechanical Engineering', 'Prepares students for careers in mechanical design and manufacturing.', '2025-01-08 10:57:40', '2025-01-08 10:57:40'),
(3, 2, 'Data Science', 'Graduate program focused on data analysis, statistics, and machine learning.', '2025-01-08 10:57:40', '2025-01-08 10:57:40');

-- --------------------------------------------------------

--
-- Table structure for table `program`
--

CREATE TABLE `program` (
  `id` int(11) NOT NULL,
  `is_international` tinyint(1) DEFAULT 0,
  `level_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `curriculum_year_start` year(4) NOT NULL,
  `year_term_id` int(11) NOT NULL,
  `major_id` int(11) DEFAULT NULL,
  `subject_id` int(11) NOT NULL,
  `classification_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `is_requested_subject` tinyint(1) DEFAULT 0,
  `created_by` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `year` varchar(20) NOT NULL DEFAULT '1st',
  `term` varchar(20) NOT NULL DEFAULT '1st',
  `program_sy_start` year(4) NOT NULL DEFAULT 2024,
  `program_sy_end` year(4) NOT NULL DEFAULT 2025,
  `program_sem` varchar(20) NOT NULL DEFAULT '1',
  `program_schedule` varchar(120) NOT NULL DEFAULT 'Mon, Tues, SAT',
  `schedule_start_time` time NOT NULL DEFAULT '08:00:00',
  `schedule_end_time` time NOT NULL DEFAULT '09:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `program`
--

INSERT INTO `program` (`id`, `is_international`, `level_id`, `course_id`, `curriculum_year_start`, `year_term_id`, `major_id`, `subject_id`, `classification_id`, `room_id`, `is_requested_subject`, `created_by`, `created_at`, `updated_at`, `year`, `term`, `program_sy_start`, `program_sy_end`, `program_sem`, `program_schedule`, `schedule_start_time`, `schedule_end_time`) VALUES
(1, 0, 1, 1, '2023', 1, 1, 1, 1, 1, 0, 'Dr. Smith', '2025-01-08 10:57:40', '2025-01-11 10:31:38', 'A', 'A', '2024', '2025', '1', 'Mon, Wed, Fri', '08:00:00', '09:00:00'),
(2, 0, 2, 2, '2024', 2, 2, 2, 2, 2, 0, 'Prof. Johnson', '2025-01-08 10:57:40', '2025-01-11 10:31:38', 'A', 'A', '2024', '2025', '1', 'Tues, Thurs, Sat', '10:00:00', '11:30:00');

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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `building_code`, `floor`, `room_number`, `room_capacity`, `no_subject`, `room_conflict`, `room_type`, `status`, `description`, `last_inspection_date`, `created_at`, `updated_at`) VALUES
(1, 'TECH', '1st Floor', '101', 30, 0, 0, 'Classroom', 'AVAILABLE', 'A spacious classroom for lectures.', '2024-12-01', '2025-01-08 10:57:40', '2025-01-11 08:01:35'),
(2, 'TECH', '2nd Floor', '202', 50, 0, 0, 'Lecture Hall', 'AVAILABLE', 'Large hall for lectures and events.', '2024-12-05', '2025-01-08 10:57:40', '2025-01-11 08:01:43'),
(3, 'INNOV', 'Ground Floor', 'G01', 15, 0, 0, 'Lab', 'AVAILABLE', 'A laboratory for engineering experiments.', '2024-11-25', '2025-01-08 10:57:40', '2025-01-11 08:01:50'),
(4, 'TECH', '2nd Floor', '205', 10, 1, 0, 'Lecture Hall', 'RESERVED', 'Test Building Maintenace Page', '2024-12-26', '2025-01-08 18:00:13', '2025-01-11 08:02:33'),
(5, 'TECH', '2nd Floor', '204', 5, 0, 1, 'Lab', 'OCCUPIED', 'Test Room Lab Close', '2024-11-29', '2025-01-08 18:26:12', '2025-01-11 12:33:27');

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
  `student_number` varchar(50) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `classification_code` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `student_number`, `first_name`, `last_name`, `middle_name`, `gender`, `birth_date`, `email`, `phone_number`, `address`, `classification_code`, `created_at`, `updated_at`) VALUES
(1, 'S12345', 'John', 'Doe', 'Michael', 'Male', '2002-04-15', 'john.doe@example.com', '123-456-7890', '123 Main St', 'FRESH', '2025-01-08 10:57:40', '2025-01-08 10:57:40'),
(2, 'S12346', 'Jane', 'Smith', 'Elizabeth', 'Female', '2001-08-22', 'jane.smith@example.com', '987-654-3210', '456 Oak Ave', 'SOPH', '2025-01-08 10:57:40', '2025-01-08 10:57:40');

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
(3, 'JUNIOR', 'Junior', 'Students who are in their third year of study.', 1, '2025-01-08 10:57:40', '2025-01-08 10:57:40');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `subject_code` varchar(50) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `department_id` int(11) NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `status` varchar(50) DEFAULT 'active',
  `description` text DEFAULT NULL,
  `is_requested_subject` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `units` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `subject_code`, `subject_name`, `department_id`, `created_by`, `status`, `description`, `is_requested_subject`, `created_at`, `updated_at`, `units`) VALUES
(1, 'CS101', 'Introduction to Programming', 1, 'Dr. Smith', 'Active', 'Basics of programming in Python.', 0, '2025-01-08 10:57:40', '2025-01-08 10:57:40', 1),
(2, 'ME101', 'Engineering Mechanics', 2, 'Prof. Johnson', 'Active', 'Introduction to the principles of mechanics.', 0, '2025-01-08 10:57:40', '2025-01-08 10:57:40', 1);

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
  `year` int(11) NOT NULL,
  `term` int(11) NOT NULL,
  `description` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `year_terms`
--

INSERT INTO `year_terms` (`id`, `year`, `term`, `description`, `created_at`, `updated_at`) VALUES
(1, 2023, 1, 'First term of the 2023 academic year', '2025-01-08 10:57:40', '2025-01-08 10:57:40'),
(2, 2023, 2, 'Second term of the 2023 academic year', '2025-01-08 10:57:40', '2025-01-08 10:57:40'),
(3, 2024, 1, 'First term of the 2024 academic year', '2025-01-08 10:57:40', '2025-01-08 10:57:40');

--
-- Indexes for dumped tables
--

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
  ADD UNIQUE KEY `course_code` (`course_code`);

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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `department_code` (`department_code`);

--
-- Indexes for table `education_levels`
--
ALTER TABLE `education_levels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `level_name` (`level_name`);

--
-- Indexes for table `majors`
--
ALTER TABLE `majors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `education_level_id` (`education_level_id`);

--
-- Indexes for table `program`
--
ALTER TABLE `program`
  ADD PRIMARY KEY (`id`),
  ADD KEY `level_id` (`level_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `year_term_id` (`year_term_id`),
  ADD KEY `major_id` (`major_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `classification_id` (`classification_id`),
  ADD KEY `room_id` (`room_id`);

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
  ADD UNIQUE KEY `student_number` (`student_number`),
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
  ADD UNIQUE KEY `year` (`year`,`term`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `curriculum_years`
--
ALTER TABLE `curriculum_years`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `education_levels`
--
ALTER TABLE `education_levels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `majors`
--
ALTER TABLE `majors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `program`
--
ALTER TABLE `program`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `specific_roles`
--
ALTER TABLE `specific_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `student_classifications`
--
ALTER TABLE `student_classifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `year_terms`
--
ALTER TABLE `year_terms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `curriculum_years`
--
ALTER TABLE `curriculum_years`
  ADD CONSTRAINT `curriculum_years_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`);

--
-- Constraints for table `majors`
--
ALTER TABLE `majors`
  ADD CONSTRAINT `majors_ibfk_1` FOREIGN KEY (`education_level_id`) REFERENCES `education_levels` (`id`);

--
-- Constraints for table `program`
--
ALTER TABLE `program`
  ADD CONSTRAINT `program_ibfk_1` FOREIGN KEY (`level_id`) REFERENCES `education_levels` (`id`),
  ADD CONSTRAINT `program_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  ADD CONSTRAINT `program_ibfk_3` FOREIGN KEY (`year_term_id`) REFERENCES `year_terms` (`id`),
  ADD CONSTRAINT `program_ibfk_4` FOREIGN KEY (`major_id`) REFERENCES `majors` (`id`),
  ADD CONSTRAINT `program_ibfk_5` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`),
  ADD CONSTRAINT `program_ibfk_6` FOREIGN KEY (`classification_id`) REFERENCES `student_classifications` (`id`),
  ADD CONSTRAINT `program_ibfk_7` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);

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
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`classification_code`) REFERENCES `student_classifications` (`classification_code`);

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`specific_role_id`) REFERENCES `specific_roles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_ibfk_3` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
