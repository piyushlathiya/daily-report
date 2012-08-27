-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 27, 2012 at 10:44 AM
-- Server version: 5.1.53
-- PHP Version: 5.3.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `c_report`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE IF NOT EXISTS `activity` (
  `entity_id` int(255) NOT NULL AUTO_INCREMENT,
  `user_id` int(255) NOT NULL,
  `date` date NOT NULL,
  `from_time` time NOT NULL,
  `to_time` time NOT NULL,
  `activity_type_id` varchar(255) NOT NULL,
  `activity_desc` text NOT NULL,
  `created_time` datetime NOT NULL,
  `updated_time` datetime NOT NULL,
  PRIMARY KEY (`entity_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `activity`
--

INSERT INTO `activity` (`entity_id`, `user_id`, `date`, `from_time`, `to_time`, `activity_type_id`, `activity_desc`, `created_time`, `updated_time`) VALUES
(3, 1, '2012-08-17', '12:30:00', '18:30:00', '1', '<p>test</p>', '2012-08-17 17:32:42', '2012-08-17 17:32:42'),
(4, 1, '2012-08-17', '20:15:00', '21:15:00', '2', '<p>asdf</p>', '2012-08-17 19:15:52', '2012-08-17 19:15:52'),
(5, 1, '2012-08-20', '14:30:00', '20:30:00', '1,2,3', '<p>sdfd</p>', '2012-08-20 17:29:12', '2012-08-20 17:29:12');

-- --------------------------------------------------------

--
-- Table structure for table `activity_type`
--

CREATE TABLE IF NOT EXISTS `activity_type` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(255) NOT NULL,
  `type_desc` text NOT NULL,
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`type_id`),
  UNIQUE KEY `type_name` (`type_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `activity_type`
--

INSERT INTO `activity_type` (`type_id`, `type_name`, `type_desc`, `created_time`, `updated_time`) VALUES
(1, 'Professional Project', '', '2012-08-08 11:51:15', '2012-08-08 11:51:15'),
(2, 'Company Project', '', '2012-07-05 12:38:11', '0000-00-00 00:00:00'),
(3, 'Learning', '', '2012-07-05 12:38:32', '0000-00-00 00:00:00'),
(4, 'Social', '', '2012-07-05 12:38:32', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `day`
--

CREATE TABLE IF NOT EXISTS `day` (
  `day_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `morning_intime` datetime DEFAULT NULL,
  `morning_outtime` datetime DEFAULT NULL,
  `evening_intime` datetime DEFAULT NULL,
  `evening_outtime` datetime DEFAULT NULL,
  `rate` int(20) NOT NULL,
  `created_time` datetime NOT NULL,
  `updated_time` datetime NOT NULL,
  PRIMARY KEY (`day_id`),
  KEY `employee_id` (`employee_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `day`
--

INSERT INTO `day` (`day_id`, `employee_id`, `date`, `morning_intime`, `morning_outtime`, `evening_intime`, `evening_outtime`, `rate`, `created_time`, `updated_time`) VALUES
(1, 'E-001', '2012-07-30', '2012-07-30 15:45:00', '2012-07-30 17:45:00', '2012-07-30 14:45:00', '2012-07-30 22:45:00', 10, '2012-07-30 15:48:57', '2012-07-30 19:13:28');

-- --------------------------------------------------------

--
-- Table structure for table `project_overview`
--

CREATE TABLE IF NOT EXISTS `project_overview` (
  `entity_id` int(255) NOT NULL AUTO_INCREMENT,
  `project_name` varchar(255) NOT NULL,
  `meta_description` blob NOT NULL,
  `assign_to` varchar(255) NOT NULL,
  `start_at` datetime NOT NULL,
  `end_at` datetime NOT NULL,
  `deadline` datetime NOT NULL,
  `created_time` datetime NOT NULL,
  `updated_time` datetime NOT NULL,
  PRIMARY KEY (`entity_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `project_overview`
--

INSERT INTO `project_overview` (`entity_id`, `project_name`, `meta_description`, `assign_to`, `start_at`, `end_at`, `deadline`, `created_time`, `updated_time`) VALUES
(1, '', 0x0d0a0d0a617364660d0a, '1,2', '2012-08-19 00:00:00', '2012-08-20 00:00:00', '2012-08-21 00:00:00', '2012-08-19 12:09:42', '2012-08-19 12:09:42'),
(2, '', 0x3c703e617364663c2f703e, '1,2', '2012-08-19 00:00:00', '2012-08-20 00:00:00', '2012-08-21 00:00:00', '2012-08-19 12:17:05', '2012-08-19 12:17:05'),
(3, '', 0x3c703e617364663c2f703e, '1,2', '2012-08-19 00:00:00', '2012-08-20 00:00:00', '2012-08-21 00:00:00', '2012-08-19 12:18:55', '2012-08-19 12:18:55');

-- --------------------------------------------------------

--
-- Table structure for table `time_slot`
--

CREATE TABLE IF NOT EXISTS `time_slot` (
  `slot_id` int(11) NOT NULL AUTO_INCREMENT,
  `slot` varchar(255) NOT NULL,
  PRIMARY KEY (`slot_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `time_slot`
--

INSERT INTO `time_slot` (`slot_id`, `slot`) VALUES
(1, '0700-0759 AM'),
(2, '0800-0859 AM'),
(3, '0900-0959 AM'),
(4, '1000-1059 AM'),
(5, '1100-1159 AM'),
(6, '1200-1259 PM'),
(7, '0100-0159 PM'),
(8, '0200-0259 PM'),
(9, '0300-0359 PM'),
(10, '0400-0459 PM'),
(11, '0500-0559 PM'),
(12, '0600-0659 PM'),
(13, '0700-0759 PM'),
(14, '0800-0859 PM'),
(15, '0900-0959 PM');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` varchar(50) NOT NULL,
  `user_fname` varchar(255) NOT NULL,
  `user_mname` varchar(255) NOT NULL,
  `user_lname` varchar(255) NOT NULL,
  `photo_path` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_gender` tinyint(4) NOT NULL COMMENT '0=>male, 1=>female',
  `dob` date NOT NULL,
  `user_role` int(2) NOT NULL DEFAULT '2' COMMENT '1=>admin, 2=>employee',
  `user_pass` varchar(255) NOT NULL,
  `user_phone` varchar(20) NOT NULL,
  `user_country` varchar(100) NOT NULL,
  `user_state` varchar(100) NOT NULL,
  `user_city` varchar(100) NOT NULL,
  `user_address1` varchar(255) NOT NULL,
  `user_address2` varchar(255) NOT NULL,
  `user_postcode` varchar(10) NOT NULL,
  `user_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=>disable, 1=>enabled',
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastlogin_at` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `em_id` (`employee_id`),
  UNIQUE KEY `em_id_2` (`employee_id`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `employee_id`, `user_fname`, `user_mname`, `user_lname`, `photo_path`, `user_email`, `user_gender`, `dob`, `user_role`, `user_pass`, `user_phone`, `user_country`, `user_state`, `user_city`, `user_address1`, `user_address2`, `user_postcode`, `user_status`, `created_time`, `updated_time`, `lastlogin_at`) VALUES
(1, 'E-001', 'Piyush', 'Manjibhai', 'Dankhra', '', 'dankhrapiyush@gmail.com', 0, '1991-06-19', 1, '0192023a7bbd73250516f069df18b500', '9687213123', 'India', 'Gujarat', 'Surat', 'varachha road', 'surat', '395006', 1, '2012-08-20 17:28:59', '2012-07-31 11:49:39', '2012-08-20 17:28:59'),
(2, 'E-002', 'piyush', 'a', 'dankhra', '', 'dankhra@gmail.com', 0, '2012-07-04', 2, '21232f297a57a5a743894a0e4a801fc3', '9687213123', 'India', 'Gujarat', 'surat', '36'' Greenpark', 'surat', '395006', 1, '2012-07-19 18:06:38', '2012-07-18 09:36:06', NULL),
(3, 'E-003', 'a', 'a', 'dankhra', '', 'piyush@gmail.com', 0, '2012-07-04', 2, '0192023a7bbd73250516f069df18b500', '9687213123', 'India', 'Gujarat', 'surat', 'a', '', '395006', 1, '2012-07-19 18:06:59', '2012-07-18 09:41:26', NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity`
--
ALTER TABLE `activity`
  ADD CONSTRAINT `activity_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `day`
--
ALTER TABLE `day`
  ADD CONSTRAINT `day_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `user` (`employee_id`) ON UPDATE CASCADE;
