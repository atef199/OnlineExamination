-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 31, 2022 at 04:01 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `final`
--

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`course_id`, `course_name`) VALUES
(1, 'Machine Learning'),
(2, 'Computer Vision');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `dep_id` int(11) NOT NULL,
  `dep_name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`dep_id`, `dep_name`) VALUES
(1, 'AI');

-- --------------------------------------------------------

--
-- Table structure for table `department_course`
--

CREATE TABLE `department_course` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `dep_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `department_course`
--

INSERT INTO `department_course` (`id`, `course_id`, `dep_id`) VALUES
(1, 1, 1),
(2, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `exam`
--

CREATE TABLE `exam` (
  `exam_id` int(11) NOT NULL,
  `lecturer_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `exam_name` varchar(200) NOT NULL,
  `questions_num` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `exam_type` enum('Random','Sort') NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `token` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `exam`
--

INSERT INTO `exam` (`exam_id`, `lecturer_id`, `course_id`, `exam_name`, `questions_num`, `time`, `exam_type`, `start_date`, `end_date`, `token`) VALUES
(1, 1, 1, 'ML Midterm', 3, 10, 'Random', '2022-05-31 10:24:46', '2022-05-31 10:41:51', 'PCJYZ');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Administrator'),
(2, 'Lecturer', 'For adding and checking Questions. And also conducting examinations'),
(3, 'Student', 'Exam Participants');

-- --------------------------------------------------------

--
-- Table structure for table `lecturer`
--

CREATE TABLE `lecturer` (
  `lecturer_id` int(11) NOT NULL,
  `nip` char(12) NOT NULL,
  `lecturer_name` varchar(50) NOT NULL,
  `email` varchar(254) NOT NULL,
  `course_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lecturer`
--

INSERT INTO `lecturer` (`lecturer_id`, `nip`, `lecturer_name`, `email`, `course_id`) VALUES
(1, '76512309', 'Andrew Ng', 'andrew@mail.com', 1),
(2, '87317212', 'Mohamed ', 'mohamed_cv@mail.com', 2);

--
-- Triggers `lecturer`
--
DELIMITER $$
CREATE TRIGGER `edit_user_dosen` BEFORE UPDATE ON `lecturer` FOR EACH ROW UPDATE `users` SET `email` = NEW.email, `username` = NEW.nip WHERE `users`.`username` = OLD.nip
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `hapus_user_dosen` BEFORE DELETE ON `lecturer` FOR EACH ROW DELETE FROM `users` WHERE `users`.`username` = OLD.nip
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `level`
--

CREATE TABLE `level` (
  `level_id` int(11) NOT NULL,
  `level_name` varchar(30) NOT NULL,
  `dep_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `level`
--

INSERT INTO `level` (`level_id`, `level_name`, `dep_id`) VALUES
(10, 'One_AI', 1),
(11, 'Two_AI', 1);

-- --------------------------------------------------------

--
-- Table structure for table `level_lecturer`
--

CREATE TABLE `level_lecturer` (
  `id` int(11) NOT NULL,
  `level_id` int(11) NOT NULL,
  `lecturer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `level_lecturer`
--

INSERT INTO `level_lecturer` (`id`, `level_id`, `lecturer_id`) VALUES
(1, 10, 1),
(3, 11, 2);

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `std_exam`
--

CREATE TABLE `std_exam` (
  `id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `std_id` int(11) NOT NULL,
  `q_list` longtext NOT NULL,
  `answers` longtext NOT NULL,
  `correct_ans_num` int(11) NOT NULL,
  `mark` decimal(10,2) NOT NULL,
  `max_mark` decimal(10,2) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `status` enum('Y','N') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `std_exam`
--

INSERT INTO `std_exam` (`id`, `exam_id`, `std_id`, `q_list`, `answers`, `correct_ans_num`, `mark`, `max_mark`, `start_date`, `end_date`, `status`) VALUES
(1, 1, 1, '3,1,4', '3:D:N,1:C:N,4:B:N', 2, '66.00', '100.00', '2022-05-31 10:25:30', '2022-05-31 10:35:30', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `std_id` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `nim` char(20) NOT NULL,
  `email` varchar(254) NOT NULL,
  `jenis_kelamin` enum('M','F') NOT NULL,
  `level_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`std_id`, `nama`, `nim`, `email`, `jenis_kelamin`, `level_id`) VALUES
(1, 'Atef Marzouk', '989348912', 'atef@mail.com', 'M', 10),
(2, 'Mohamed Ghanem', '98934898', 'mohamed@mail.com', 'M', 11);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_question`
--

CREATE TABLE `tbl_question` (
  `q_id` int(11) NOT NULL,
  `lecturer_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  `file` varchar(255) NOT NULL,
  `file_type` varchar(50) NOT NULL,
  `question` longtext NOT NULL,
  `ans_a` longtext NOT NULL,
  `ans_b` longtext NOT NULL,
  `ans_c` longtext NOT NULL,
  `ans_d` longtext NOT NULL,
  `ans_e` longtext NOT NULL,
  `file_a` varchar(255) NOT NULL,
  `file_b` varchar(255) NOT NULL,
  `file_c` varchar(255) NOT NULL,
  `file_d` varchar(255) NOT NULL,
  `file_e` varchar(255) NOT NULL,
  `right_ans` varchar(5) NOT NULL,
  `created_on` int(11) NOT NULL,
  `updated_on` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_question`
--

INSERT INTO `tbl_question` (`q_id`, `lecturer_id`, `course_id`, `weight`, `file`, `file_type`, `question`, `ans_a`, `ans_b`, `ans_c`, `ans_d`, `ans_e`, `file_a`, `file_b`, `file_c`, `file_d`, `file_e`, `right_ans`, `created_on`, `updated_on`) VALUES
(1, 1, 1, 1, '', '', '<p><span xss=removed>What is the application of machine learning methods to a large database called?</span><br></p>', '<p><span xss=removed>Big data computing </span><br></p>', '<p><span xss=removed>Internet of things</span><br></p>', '<p><span xss=removed> Data mining</span><br></p>', '<p><span xss=removed>Deep Learning</span></p>', '<p><span xss=removed>Unsupervised Learning</span></p>', '', '', '', '', '', 'C', 1653984423, 1653984423),
(2, 1, 1, 1, '', '', '<span xss=removed>What is machine learning?</span>', '<p><span xss=removed>The selective acquisition of knowledge through the use of computer programs</span></p>', '<p><span xss=removed>﻿</span><span xss=removed> The autonomous acquisition of knowledge through the use of computer programs</span></p>', '<p><span xss=removed>The autonomous acquisition of knowledge through the use of manual programs</span></p>', '<p><span xss=removed>The selective acquisition of knowledge through the use of manual programs</span><br></p>', '<p><span xss=removed>None</span></p>', '', '', '', '', '', 'B', 1653984792, 1653984792),
(3, 1, 1, 1, '', '', '<font face=\"Source Sans Pro, sans-serif\" color=\"#ff0000\"><span xss=\"removed\" xss=removed><b>.......... is a widely used and effective machine learning algorithm based on the idea of bagging.</b></span></font><br>', '<p><span xss=\"removed\"><font color=\"#ff0000\">Regression</font></span></p>', '<p><span xss=\"removed\"><font color=\"#ff0000\">Classification</font></span></p>', '<p><span xss=\"removed\"><font color=\"#ff0000\">Decision Tree</font></span></p>', '<p><span xss=\"removed\"><font color=\"#ff0000\">Random Forest</font></span></p>', '<p><span xss=\"removed\"><font color=\"#ff0000\">CNN</font></span></p>', '', '', '', '', '', 'D', 1653985051, 1653988546),
(5, 1, 1, 1, '96fca7b284e51e7a10b152b5425e413b.png', 'image/png', '<p><br></p>', '<p>ddflsgklkl</p>', '<p>ksdkggjksjkjk</p>', '<p>ksddkdgkjsgjkjksgd</p>', '<p>skdskjgsgjkgsdkjk</p>', '<p>kfwskjsfkkklj</p>', '', '', '', '', '', 'B', 1653987165, 1653987165);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(254) DEFAULT NULL,
  `activation_selector` varchar(255) DEFAULT NULL,
  `activation_code` varchar(255) DEFAULT NULL,
  `forgotten_password_selector` varchar(255) DEFAULT NULL,
  `forgotten_password_code` varchar(255) DEFAULT NULL,
  `forgotten_password_time` int(11) UNSIGNED DEFAULT NULL,
  `remember_selector` varchar(255) DEFAULT NULL,
  `remember_code` varchar(255) DEFAULT NULL,
  `created_on` int(11) UNSIGNED NOT NULL,
  `last_login` int(11) UNSIGNED DEFAULT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `email`, `activation_selector`, `activation_code`, `forgotten_password_selector`, `forgotten_password_code`, `forgotten_password_time`, `remember_selector`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`) VALUES
(1, '127.0.0.1', 'Administrator', '$2y$12$JjM5xBghu01DOMBL4./8M.V54I2CIuLNqQ1dHTRPbnHCprQRa3FKq', 'admin@mail.com', NULL, '', NULL, NULL, NULL, NULL, NULL, 1268889823, 1654003786, 1, 'Atef', 'Marzouk', 'ADMIN', '0'),
(45, '::1', '76512309', '$2y$10$kuE/COqUZS.NMJpLQJZLIOlk1R6VtD3ebE8ngrZuO9tlMvr.nDCqa', 'andrew@mail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1653932433, 1654002044, 1, 'Andrew', 'Ng', NULL, NULL),
(46, '::1', '989348912', '$2y$10$OxxWYpTuJTA/xH7MDAcDTODAf8J/I3bt8ksfy1su01qCSix3Y1zTq', 'atef@mail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1653932606, 1653985510, 1, 'Atef', 'Marzouk', NULL, NULL),
(47, '::1', '98934898', '$2y$10$2udokFBgGU/03J2uJs7vUOECtihlxb4KZEfZkBkbufB.AtAy2vCmC', 'mohamed@mail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1653932778, NULL, 1, 'Mohamed', 'Ghanem', NULL, NULL),
(48, '::1', '87317212', '$2y$10$m1gy/fw/EGqefiHJma4WaeJ/aBz0ror/wNowRpi4sAsCXHIh2sh/C', 'mohamed_cv@mail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1653933036, NULL, 1, 'Mohamed', 'ali', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users_groups`
--

CREATE TABLE `users_groups` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `group_id` mediumint(8) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_groups`
--

INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES
(3, 1, 1),
(47, 45, 2),
(48, 46, 3),
(49, 47, 3),
(50, 48, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`course_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`dep_id`);

--
-- Indexes for table `department_course`
--
ALTER TABLE `department_course`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jurusan_id` (`dep_id`),
  ADD KEY `matkul_id` (`course_id`);

--
-- Indexes for table `exam`
--
ALTER TABLE `exam`
  ADD PRIMARY KEY (`exam_id`),
  ADD KEY `matkul_id` (`course_id`),
  ADD KEY `dosen_id` (`lecturer_id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lecturer`
--
ALTER TABLE `lecturer`
  ADD PRIMARY KEY (`lecturer_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `nip` (`nip`),
  ADD KEY `matkul_id` (`course_id`);

--
-- Indexes for table `level`
--
ALTER TABLE `level`
  ADD PRIMARY KEY (`level_id`),
  ADD KEY `jurusan_id` (`dep_id`);

--
-- Indexes for table `level_lecturer`
--
ALTER TABLE `level_lecturer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kelas_id` (`level_id`),
  ADD KEY `dosen_id` (`lecturer_id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `std_exam`
--
ALTER TABLE `std_exam`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ujian_id` (`exam_id`),
  ADD KEY `mahasiswa_id` (`std_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`std_id`),
  ADD UNIQUE KEY `nim` (`nim`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `kelas_id` (`level_id`);

--
-- Indexes for table `tbl_question`
--
ALTER TABLE `tbl_question`
  ADD PRIMARY KEY (`q_id`),
  ADD KEY `matkul_id` (`course_id`),
  ADD KEY `dosen_id` (`lecturer_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_activation_selector` (`activation_selector`),
  ADD UNIQUE KEY `uc_forgotten_password_selector` (`forgotten_password_selector`),
  ADD UNIQUE KEY `uc_remember_selector` (`remember_selector`),
  ADD UNIQUE KEY `uc_email` (`email`) USING BTREE;

--
-- Indexes for table `users_groups`
--
ALTER TABLE `users_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
  ADD KEY `fk_users_groups_users1_idx` (`user_id`),
  ADD KEY `fk_users_groups_groups1_idx` (`group_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `dep_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `department_course`
--
ALTER TABLE `department_course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `exam`
--
ALTER TABLE `exam`
  MODIFY `exam_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `lecturer`
--
ALTER TABLE `lecturer`
  MODIFY `lecturer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `level`
--
ALTER TABLE `level`
  MODIFY `level_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `level_lecturer`
--
ALTER TABLE `level_lecturer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `std_exam`
--
ALTER TABLE `std_exam`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `std_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_question`
--
ALTER TABLE `tbl_question`
  MODIFY `q_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `users_groups`
--
ALTER TABLE `users_groups`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `department_course`
--
ALTER TABLE `department_course`
  ADD CONSTRAINT `department_course_ibfk_1` FOREIGN KEY (`dep_id`) REFERENCES `department` (`dep_id`),
  ADD CONSTRAINT `department_course_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`);

--
-- Constraints for table `exam`
--
ALTER TABLE `exam`
  ADD CONSTRAINT `exam_ibfk_1` FOREIGN KEY (`lecturer_id`) REFERENCES `lecturer` (`lecturer_id`),
  ADD CONSTRAINT `exam_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`);

--
-- Constraints for table `lecturer`
--
ALTER TABLE `lecturer`
  ADD CONSTRAINT `lecturer_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`);

--
-- Constraints for table `level_lecturer`
--
ALTER TABLE `level_lecturer`
  ADD CONSTRAINT `level_lecturer_ibfk_1` FOREIGN KEY (`lecturer_id`) REFERENCES `lecturer` (`lecturer_id`),
  ADD CONSTRAINT `level_lecturer_ibfk_2` FOREIGN KEY (`level_id`) REFERENCES `level` (`level_id`);

--
-- Constraints for table `std_exam`
--
ALTER TABLE `std_exam`
  ADD CONSTRAINT `std_exam_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `exam` (`exam_id`),
  ADD CONSTRAINT `std_exam_ibfk_2` FOREIGN KEY (`std_id`) REFERENCES `student` (`std_id`);

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_2` FOREIGN KEY (`level_id`) REFERENCES `level` (`level_id`);

--
-- Constraints for table `tbl_question`
--
ALTER TABLE `tbl_question`
  ADD CONSTRAINT `tbl_question_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`),
  ADD CONSTRAINT `tbl_question_ibfk_2` FOREIGN KEY (`lecturer_id`) REFERENCES `lecturer` (`lecturer_id`);

--
-- Constraints for table `users_groups`
--
ALTER TABLE `users_groups`
  ADD CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
