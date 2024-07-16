-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 14, 2024 at 01:36 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 7.4.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quiz_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `aid` int(11) NOT NULL,
  `answer` varchar(255) NOT NULL,
  `ans_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`aid`, `answer`, `ans_id`) VALUES
(41, 'Def', 1),
(42, 'function', 1),
(43, ' func', 1),
(44, 'None of these', 1),
(45, '( )', 2),
(46, '[ ]', 2),
(47, '{ }', 2),
(48, 'None of these', 2),
(49, '.py', 3),
(50, '.pl', 3),
(51, '.ty', 3),
(52, 'None of these', 3),
(53, 'Java', 4),
(54, 'C#', 4),
(55, 'C', 4),
(56, 'Perl', 4),
(57, 'string_123', 5),
(58, '_hello', 5),
(59, '12_hello', 5),
(60, 'None of these', 5),
(61, 'False', 6),
(62, 'True', 6),
(63, 'Depends on program', 6),
(64, 'Depends on computer', 6),
(69, 'system.getversion', 7),
(70, 'sys.version(1)', 7),
(71, 'system.get', 7),
(72, 'None of the above', 7),
(73, 'dvd()', 8),
(74, 'ntr()', 8),
(75, 'sqrt()', 8),
(76, 'print()', 8),
(77, 'functionable', 9),
(78, 'mutable', 9),
(79, 'immutable', 9),
(80, 'None of these', 9),
(81, 'define function(x)', 10),
(82, 'function x()', 10),
(83, 'def x():', 10),
(84, 'None of these', 10);

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `email` varchar(20) NOT NULL,
  `qid` int(3) NOT NULL,
  `answer` varchar(20) NOT NULL,
  `mark` int(1) NOT NULL,
  `total` int(3) DEFAULT NULL,
  `grade` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `history`
--

INSERT INTO `history` (`email`, `qid`, `answer`, `mark`, `total`, `grade`) VALUES
('ramjoker1208@gmail.c', 1, 'Sherlock Holmes', 1, NULL, NULL),
('ramjoker1208@gmail.c', 2, 'Finland', 1, NULL, NULL),
('ramjoker1208@gmail.c', 1, 'Sherlock Holmes', 1, NULL, NULL),
('ramjoker1208@gmail.c', 2, 'Finland', 1, NULL, NULL),
('ramjoker1208@gmail.c', 3, 'Mercury', 0, NULL, NULL),
('ramjoker1208@gmail.c', 4, 'Delta', 1, NULL, NULL),
('ramjoker1208@gmail.c', 1, 'Sherlock Holmes', 1, NULL, NULL),
('ramjoker1208@gmail.c', 2, 'Finland', 1, NULL, NULL),
('ramjoker1208@gmail.c', 3, 'Mercury', 0, NULL, NULL),
('ramjoker1208@gmail.c', 4, 'Delta', 1, NULL, NULL),
('ramjoker1208@gmail.c', 1, 'Sherlock Holmes', 1, NULL, NULL),
('ramjoker1208@gmail.c', 2, 'Finland', 1, NULL, NULL),
('ramjoker1208@gmail.c', 3, 'Mercury', 0, NULL, NULL),
('ramjoker1208@gmail.c', 4, 'Delta', 1, NULL, NULL),
('ramjoker1208@gmail.c', 1, 'Iron Man', 0, NULL, NULL),
('ramjoker1208@gmail.c', 2, 'Finland', 1, NULL, NULL),
('ramjoker1208@gmail.c', 3, 'Mercury', 0, NULL, NULL),
('ramjoker1208@gmail.c', 4, 'Gamma', 0, NULL, NULL),
('ramjoker1208@gmail.c', 1, 'Iron Man', 0, NULL, NULL),
('ramjoker1208@gmail.c', 2, 'Finland', 1, NULL, NULL),
('ramjoker1208@gmail.c', 3, 'Mercury', 0, NULL, NULL),
('ramjoker1208@gmail.c', 4, 'Gamma', 0, NULL, NULL),
('ramjoker1208@gmail.c', 1, 'Iron Man', 0, NULL, NULL),
('ramjoker1208@gmail.c', 2, 'Finland', 1, NULL, NULL),
('ramjoker1208@gmail.c', 3, 'Mercury', 0, NULL, NULL),
('ramjoker1208@gmail.c', 4, 'Gamma', 0, NULL, NULL),
('ramjoker1208@gmail.c', 1, 'Iron Man', 0, NULL, NULL),
('ramjoker1208@gmail.c', 2, 'Finland', 1, NULL, NULL),
('ramjoker1208@gmail.c', 3, 'Mercury', 0, NULL, NULL),
('ramjoker1208@gmail.c', 4, 'Gamma', 0, NULL, NULL),
('ramjoker1208@gmail.c', 1, 'Iron Man', 0, NULL, NULL),
('ramjoker1208@gmail.c', 2, 'Finland', 1, NULL, NULL),
('ramjoker1208@gmail.c', 3, 'Mercury', 0, NULL, NULL),
('ramjoker1208@gmail.c', 4, 'Gamma', 0, NULL, NULL),
('ramjoker1208@gmail.c', 1, 'Iron Man', 0, NULL, NULL),
('ramjoker1208@gmail.c', 2, 'Finland', 1, NULL, NULL),
('ramjoker1208@gmail.c', 3, 'Mercury', 0, NULL, NULL),
('ramjoker1208@gmail.c', 4, 'Gamma', 0, NULL, NULL),
('ramjoker1208@gmail.c', 1, 'Iron Man', 0, NULL, NULL),
('ramjoker1208@gmail.c', 2, 'Finland', 1, NULL, NULL),
('ramjoker1208@gmail.c', 3, 'Mercury', 0, NULL, NULL),
('ramjoker1208@gmail.c', 4, 'Gamma', 0, NULL, NULL),
('ramjoker1208@gmail.c', 1, 'Iron Man', 0, NULL, NULL),
('ramjoker1208@gmail.c', 2, 'Finland', 1, NULL, NULL),
('ramjoker1208@gmail.c', 3, 'Mercury', 0, NULL, NULL),
('ramjoker1208@gmail.c', 4, 'Delta', 1, NULL, NULL),
('kumar@gmail.com', 1, ' func', 0, NULL, NULL),
('kumar@gmail.com', 2, '( )', 0, NULL, NULL),
('kumar@gmail.com', 3, '.py', 0, NULL, NULL),
('kumar@gmail.com', 4, 'C', 0, NULL, NULL),
('kumar@gmail.com', 5, '12_hello', 0, NULL, NULL),
('kumar@gmail.com', 6, 'False', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `login_sessions`
--

CREATE TABLE `login_sessions` (
  `ic_number` varchar(20) NOT NULL,
  `batch_code` varchar(20) NOT NULL,
  `session_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `qid` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `ans_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`qid`, `question`, `ans_id`) VALUES
(1, 'Which of the following brackets are used in python to create a list?', 46),
(2, 'Which of the following is the correct extension of a python file?', 49),
(3, 'In which language python is written?', 55),
(4, 'Which of the following is invalid variable?', 59),
(5, ' Is python identifiers case sensitive?', 62),
(6, 'Which of the following is not the part of python programming?', 65),
(7, 'Which function is used to get the version of python?', 70),
(8, 'Which of the following is the built in function in python?', 76),
(9, ' List in Python is ................in nature.', 78),
(10, 'What is the correct syntax for defining a function in Python?', 83);

-- --------------------------------------------------------

--
-- Table structure for table `quiz_status`
--

CREATE TABLE `quiz_status` (
  `quiz_id` int(5) NOT NULL,
  `ic_number` varchar(10) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `quiz_status`
--

INSERT INTO `quiz_status` (`quiz_id`, `ic_number`, `date`) VALUES
(3, 'G6357282P', '2024-05-19 15:37:06');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_topics`
--

CREATE TABLE `quiz_topics` (
  `quiz_id` int(11) NOT NULL,
  `quiz_topic` text NOT NULL,
  `batch_code` varchar(20) NOT NULL,
  `quiz_link` varchar(1000) NOT NULL,
  `num_questions` int(11) NOT NULL,
  `total_marks` int(11) NOT NULL,
  `quiz_date` date NOT NULL,
  `total_time` time DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `quiz_time` time DEFAULT NULL,
  `set` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quiz_topics`
--

INSERT INTO `quiz_topics` (`quiz_id`, `quiz_topic`, `batch_code`, `quiz_link`, `num_questions`, `total_marks`, `quiz_date`, `total_time`, `created_at`, `quiz_time`, `set`) VALUES
(3, 'Forklift', '19674', 'https://forms.office.com/Pages/ShareFormPage.aspx?id=DQSIkWdsW0yxEjajBLZtrQAAAAAAAAAAAAO__R2GXyJUMFhVTTdPUTcwNUMzVUI4NVJVQjhWWEIzTy4u&sharetoken=lsThMloso19ARJtwM0JE,https://forms.office.com/Pages/ShareFormPage.aspx?id=DQSIkWdsW0yxEjajBLZtrQAAAAAAAAAAAAO__R2GXyJUQlFaRFBaOU0yQzdMMFFRNERJWkRRMEU4Qy4u&sharetoken=hjHJ6iSlE3Mgxb3wOQ8b', 30, 30, '2024-07-13', '00:30:00', '2024-03-31 13:31:34', '10:00:35', 1),
(1, 'Scissor Lift', '19674', 'https://forms.office.com/Pages/ShareFormPage.aspx?id=DQSIkWdsW0yxEjajBLZtrQAAAAAAAAAAAAO__R2GXyJUQk0xNUNDVEY4Nzk3RllWMktZWDhCN0I3RC4u&sharetoken=S6uJbvJEvw7KC2qkqzAB,https://forms.office.com/Pages/ShareFormPage.aspx?id=DQSIkWdsW0yxEjajBLZtrQAAAAAAAAAAAAO__R2GXyJURENESlc2TVFPSDBYRUQ1WU5WRDA2T01RVS4u&sharetoken=S6uJbvJEvw7KC2qkqzAB', 7, 7, '2024-07-13', '00:07:00', '2024-03-31 13:31:34', '10:00:35', 0),
(4, 'Boom Lift', '19674', 'https://forms.office.com/Pages/ShareFormPage.aspx?id=DQSIkWdsW0yxEjajBLZtrQAAAAAAAAAAAAO__R2GXyJUQ081RFQ2UVMyNUU4SzEzMkRaWFVLUU05TC4u&sharetoken=S6uJbvJEvw7KC2qkqzAB,https://forms.office.com/Pages/ShareFormPage.aspx?id=DQSIkWdsW0yxEjajBLZtrQAAAAAAAAAAAAO__R2GXyJUM1FBS0hJUkNSSjhJUUdOTks2VU02UFRUVS4u&sharetoken=S6uJbvJEvw7KC2qkqzAB', 7, 7, '2024-07-13', '00:07:00', '2024-03-31 13:31:34', '10:00:35', 0);

-- --------------------------------------------------------

--
-- Table structure for table `score`
--

CREATE TABLE `score` (
  `email` varchar(50) NOT NULL,
  `total` int(10) NOT NULL,
  `grade` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `score`
--

INSERT INTO `score` (`email`, `total`, `grade`) VALUES
('kumar@gmail.com', 0, 'fail'),
('ramjoker1208@gmail.com', 2, 'fail');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `t_id` int(11) NOT NULL,
  `t_name` varchar(20) NOT NULL,
  `t_email` varchar(30) NOT NULL,
  `t_mobile` bigint(10) NOT NULL,
  `t_pass` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`t_id`, `t_name`, `t_email`, `t_mobile`, `t_pass`) VALUES
(2, 'ramkumar', 'ramjoker1208@gmail.com', 9003943238, 'ramkumar');

-- --------------------------------------------------------

--
-- Table structure for table `timer`
--

CREATE TABLE `timer` (
  `duration` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `timer`
--

INSERT INTO `timer` (`duration`) VALUES
('00:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` tinyint(3) UNSIGNED NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_dob` date NOT NULL,
  `user_pass` varchar(30) NOT NULL,
  `ic_number` varchar(20) NOT NULL,
  `batch_code` varchar(20) NOT NULL,
  `login` int(11) DEFAULT 0,
  `status` varchar(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `mobile`, `user_email`, `user_dob`, `user_pass`, `ic_number`, `batch_code`, `login`, `status`) VALUES
(1, 'ramkumar', '9003943238', 'ramjoker1208@gmail.com', '2002-08-12', 'ramkumar', '', '', 0, '0'),
(5, 'kumar', '9003943238', 'kumar@gmail.com', '2002-08-12', 'ramkumar', 'G6357282P', '19674', 0, '0'),
(6, 'ram', '9003943238', 'ram@gmail.com', '2002-08-12', 'ramkumar', 'G7938085M', '19674', 1, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`aid`);

--
-- Indexes for table `login_sessions`
--
ALTER TABLE `login_sessions`
  ADD PRIMARY KEY (`ic_number`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`qid`);

--
-- Indexes for table `quiz_topics`
--
ALTER TABLE `quiz_topics`
  ADD PRIMARY KEY (`quiz_id`);

--
-- Indexes for table `score`
--
ALTER TABLE `score`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`t_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `aid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `qid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `quiz_topics`
--
ALTER TABLE `quiz_topics`
  MODIFY `quiz_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `t_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
