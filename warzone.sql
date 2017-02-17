-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 15, 2016 at 07:32 PM
-- Server version: 10.1.16-MariaDB
-- PHP Version: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `warzone`
--
CREATE DATABASE IF NOT EXISTS `warzone` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `warzone`;

-- --------------------------------------------------------

--
-- Table structure for table `logged_in_users`
--

DROP TABLE IF EXISTS `logged_in_users`;
CREATE TABLE `logged_in_users` (
  `USER` varchar(255) DEFAULT NULL,
  `INVITESTATUS` varchar(255) DEFAULT 'FALSE',
  `INVITING_PLAYER` varchar(255) DEFAULT NULL,
  `INVITEDPLAYER` varchar(255) DEFAULT NULL,
  `PARTNERED` varchar(255) NOT NULL DEFAULT 'FALSE',
  `OPPONENT` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `registered_users`
--

DROP TABLE IF EXISTS `registered_users`;
CREATE TABLE `registered_users` (
  `UNAME` varchar(20) NOT NULL,
  `PWORD` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `registered_users`
--

INSERT INTO `registered_users` (`UNAME`, `PWORD`) VALUES
('Big Boss', 'EVA'),
('den', 'water'),
('geoff', 'dan'),
('jimminy', 'cricket'),
('ken', 'ken'),
('matt', 'matt'),
('mike', 'seven'),
('nick', 'nick'),
('Ocelot', 'ADAM'),
('omar', 'abid');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `logged_in_users`
--
ALTER TABLE `logged_in_users`
  ADD UNIQUE KEY `USER` (`USER`);

--
-- Indexes for table `registered_users`
--
ALTER TABLE `registered_users`
  ADD PRIMARY KEY (`UNAME`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
