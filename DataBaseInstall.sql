-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 02, 2024 at 10:17 AM
-- Server version: 8.0.36
-- PHP Version: 8.1.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `StudyProject`
--

-- --------------------------------------------------------

--
-- Table structure for table `buttons`
--

CREATE TABLE `buttons` (
  `id` int NOT NULL,
  `siteId` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `title` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `neededGetParameters` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `neededPostParameters` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `openSeperateWindow` int NOT NULL,
  `targetSiteId` int NOT NULL,
  `icon` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `permissions` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `buttons`
--

INSERT INTO `buttons` (`id`, `siteId`, `title`, `neededGetParameters`, `neededPostParameters`, `openSeperateWindow`, `targetSiteId`, `icon`, `permissions`) VALUES
(1, ',1,', 'Competitions', '', '', 0, 2, 'trophy', ',2,'),
(2, ',6,', 'Supervise Discipline', ',disId,', '', 1, 9, 'play', ',4,'),
(3, ',11,', 'Add Member', ',compId,,disId,', '', 1, 10, 'user-plus', ',5,'),
(4, ',1,,4,,6,', 'Manage Members', ',compId,,disId,', '', 1, 11, 'users', ',6,'),
(5, ',6,', 'Judge', ',disId,', '', 1, 12, 'gavel', ',7,'),
(6, ',1,', 'Login for Attendees', '', '', 0, 13, 'right-to-bracket', ',16,'),
(7, ',1,', 'Logout', '', '', 0, 13, 'right-from-bracket', ',17,'),
(8, ',1,,4,,6,', 'Notes', ',compId,,disId,', '', 1, 14, 'note-sticky', ',17,'),
(9, ',6,', 'Criteria Cheatsheet', '', '', 1, 15, 'banana', ',1,');

-- --------------------------------------------------------

--
-- Table structure for table `competitions`
--

CREATE TABLE `competitions` (
  `id` int NOT NULL,
  `title` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `associatedMembers` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `environmentId` int NOT NULL,
  `hide` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `competitions`
--

INSERT INTO `competitions` (`id`, `title`, `associatedMembers`, `date`, `environmentId`, `hide`) VALUES
(1, 'FTA WC Qualifier Airtime 2022', ',37,,38,,39,,40,,41,,42,,43,,44,,45,,39,,39,,39,,39,,39,,39,,39,,39,,41,,43,,40,,38,,44,,45,,44,,43,,45,', '2022-07-16 13:44:08', 1, 1),
(3, 'FTA WC Qualifier Rebel Park 2022', ',51,,52,,53,,54,,56,,57,,58,,60,,61,,62,,63,,49,,50,,58,,61,,52,,50,,54,,56,,60,,53,,52,,52,,52,,52,,52,,58,,61,,60,,50,', '2022-07-16 13:42:41', 1, 1),
(4, 'FTA WC Qualifier ARL Park 2022', ',64,,65,,66,,67,,68,,69,,70,,71,,72,,74,,75,,77,,78,,79,,80,,81,,82,,83,,65,,81,,78,,64,,74,,75,,66,,79,,82,,75,,75,,75,,75,,75,,65,,74,,64,', '2022-07-16 13:38:57', 1, 1),
(5, 'FTA WC Qualifier Tempest Academy 2022', ',33,', '2022-08-04 09:51:18', 1, 1),
(6, 'FTA World Championships 2022', ',33,,35,,84,,73,,85,,76,,86,,87,,88,,89,,90,,55,,59,,91,,92,,36,,93,,94,,95,,96,,97,,98,,99,,46,,47,,48,,34,,47,,98,,99,,100,,101,,102,,103,,104,,105,,106,,107,,108,,109,,110,,111,,112,,113,,114,,115,,116,,117,,118,,119,,120,,121,,122,,123,,124,,125,,126,,127,,128,,129,,130,,131,,132,,133,,129,,129,,129,,129,,129,,129,,129,,129,,129,,129,,129,,117,,119,,120,,121,,122,,123,,124,,125,,127,,128,,133,,133,,133,,133,,133,,133,,119,,120,,121,,122,,127,,134,,134,,135,,136,,137,,138,,139,,140,,141,,134,', '2023-04-30 13:36:20', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `disciplines`
--

CREATE TABLE `disciplines` (
  `id` int NOT NULL,
  `title` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `compId` int NOT NULL,
  `associatedMembers` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `startListSort` varchar(300) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `rounds` int NOT NULL,
  `criteria` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `criteriaRuleset` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `disciplines`
--

INSERT INTO `disciplines` (`id`, `title`, `compId`, `associatedMembers`, `startListSort`, `rounds`, `criteria`, `criteriaRuleset`) VALUES
(1, 'Round 1', 1, ',37,,38,,39,,40,,41,,42,,43,,44,,45,', 'random', 1, 'creativity~0-10-0.1,difficulty~0-10-0.1,execution~0-10-0.1', 'sum(creativity)+sum(difficulty)+sum(execution)'),
(4, 'Round 2', 1, ',41,,43,,40,,38,,44,,45,', 'scoreOfId:1:ASC', 1, 'creativity~0-10-0.1,difficulty~0-10-0.1,execution~0-10-0.1', 'sum(creativity)+sum(difficulty)+sum(execution)'),
(5, 'Round 3', 1, ',44,,45,', 'scoreOfId:1:ASC', 1, 'creativity~0-10-0.1,difficulty~0-10-0.1,execution~0-10-0.1', 'sum(creativity)+sum(difficulty)+sum(execution)'),
(6, 'Round 1', 3, ',51,,52,,53,,54,,56,,57,,58,,60,,61,,62,,63,,49,,50,', 'random', 1, 'creativity~0-10-0.1,difficulty~0-10-0.1,execution~0-10-0.1', 'sum(creativity)+sum(difficulty)+sum(execution)'),
(7, 'Round 2', 3, ',58,,61,,52,,50,,54,,56,,60,,53,', 'scoreOfId:1:ASC', 1, 'creativity~0-10-0.1,difficulty~0-10-0.1,execution~0-10-0.1', 'sum(creativity)+sum(difficulty)+sum(execution)'),
(8, 'Round 3', 3, ',52,,52,,52,,52,,52,,58,,61,,60,,50,', 'scoreOfId:7:ASC', 1, 'creativity~0-10-0.1,difficulty~0-10-0.1,execution~0-10-0.1', 'sum(creativity)+sum(difficulty)+sum(execution)'),
(9, 'Round 1', 4, ',64,,65,,66,,67,,68,,69,,70,,71,,72,,74,,75,,77,,78,,79,,80,,81,,82,,83,', 'random', 1, 'creativity~0-10-0.1,difficulty~0-10-0.1,execution~0-10-0.1', 'sum(creativity)+sum(difficulty)+sum(execution)'),
(10, 'Round 2', 4, ',65,,81,,78,,64,,74,,75,,66,,79,,82,', 'scoreOfId:9:ASC', 1, 'creativity~0-10-0.1,difficulty~0-10-0.1,execution~0-10-0.1', 'sum(creativity)+sum(difficulty)+sum(execution)'),
(11, 'Round 3', 4, ',75,,75,,75,,75,,75,,65,,74,,64,', 'scoreOfId:10:ASC', 1, 'creativity~0-10-0.1,difficulty~0-10-0.1,execution~0-10-0.1', 'sum(creativity)+sum(difficulty)+sum(execution)'),
(12, 'Round 1', 5, '', 'random', 1, 'creativity~0-10-0.1,difficulty~0-10-0.1,execution~0-10-0.1', 'sum(creativity)+sum(difficulty)+sum(execution)'),
(13, 'Round 2', 5, '', 'scoreOfId:9:ASC', 1, 'creativity~0-10-0.1,difficulty~0-10-0.1,execution~0-10-0.1', 'sum(creativity)+sum(difficulty)+sum(execution)'),
(14, 'Round 3', 5, '', 'scoreOfId:10:ASC', 1, 'creativity~0-10-0.1,difficulty~0-10-0.1,execution~0-10-0.1', 'sum(creativity)+sum(difficulty)+sum(execution)'),
(15, 'Round 1', 6, ',35,,84,,73,,85,,76,,86,,87,,88,,89,,90,,55,,59,,91,,92,,36,,93,,94,,95,,96,,97,,98,,99,,46,,47,,48,,34,,47,,98,,99,,100,,101,,102,,103,,104,,105,,106,,107,,108,,109,,110,,111,,112,,113,,114,,115,,116,,117,,118,,119,,120,,121,,122,,123,,124,,125,,126,,127,,128,,129,,130,,131,,132,,133,,134,,134,,135,,136,,137,,138,,139,,140,,141,', 'random', 1, 'creativity~0-10-0.1,difficulty~0-10-0.1,execution~0-10-0.1', 'sum(creativity)+sum(difficulty)+sum(execution)'),
(16, 'Round 2', 6, ',98,,99,,46,,47,,48,,34,,47,,98,,99,,100,,101,,102,,103,,104,,105,,106,,107,,108,,109,,110,,111,,112,,113,,114,,115,,116,,129,,129,,129,,129,,129,,129,,129,,129,,129,,129,,129,,117,,119,,120,,121,,122,,123,,124,,125,,127,,128,,133,,134,,135,,136,,137,,140,,130,', 'scoreOfId:9:ASC', 1, 'creativity~0-10-0.1,difficulty~0-10-0.1,execution~0-10-0.1', 'sum(creativity)+sum(difficulty)+sum(execution)'),
(17, 'Round 3', 6, ',98,,99,,46,,47,,48,,34,,47,,98,,99,,100,,101,,102,,103,,104,,105,,106,,107,,108,,109,,110,,111,,112,,113,,114,,115,,116,,133,,133,,133,,133,,133,,119,,120,,121,,122,,127,', 'scoreOfId:10:ASC', 1, 'creativity~0-10-0.1,difficulty~0-10-0.1,execution~0-10-0.1', 'sum(creativity)+sum(difficulty)+sum(execution)');

-- --------------------------------------------------------

--
-- Table structure for table `executediscipline`
--

CREATE TABLE `executediscipline` (
  `id` int NOT NULL,
  `disciplineId` int NOT NULL,
  `currentAthlete` int NOT NULL,
  `currentRound` int NOT NULL,
  `state` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `executediscipline`
--

INSERT INTO `executediscipline` (`id`, `disciplineId`, `currentAthlete`, `currentRound`, `state`) VALUES
(8, 1, 39, 1, 0),
(9, 4, 36, 1, 0),
(10, 5, 36, 1, 0),
(11, 6, 54, 1, 1),
(12, 7, 59, 1, 0),
(13, 8, 50, 1, 0),
(14, 9, 69, 1, 1),
(15, 10, 76, 1, 0),
(16, 11, 74, 1, 0),
(17, 15, 136, 1, 0),
(18, 16, 130, 1, 0),
(19, 17, 122, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `judging`
--

CREATE TABLE `judging` (
  `id` int NOT NULL,
  `userId` int NOT NULL,
  `disciplineId` int NOT NULL,
  `criteria` varchar(400) NOT NULL,
  `range` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `judging`
--

INSERT INTO `judging` (`id`, `userId`, `disciplineId`, `criteria`, `range`) VALUES
(22, 15, 1, 'creativity', '0-10-0.1'),
(23, 15, 4, 'creativity', '0-10-0.1'),
(30, 15, 5, 'creativity', '0-10-0.1'),
(73, 47, 15, 'execution', '0-10-0.1'),
(74, 47, 16, 'execution', '0-10-0.1'),
(75, 47, 17, 'execution', '0-10-0.1'),
(79, 100, 15, 'difficulty', '0-10-0.1'),
(80, 100, 16, 'difficulty', '0-10-0.1'),
(81, 100, 17, 'difficulty', '0-10-0.1'),
(82, 101, 15, 'creativity', '0-10-0.1'),
(83, 101, 16, 'creativity', '0-10-0.1'),
(84, 101, 17, 'creativity', '0-10-0.1');

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int NOT NULL,
  `userId` int NOT NULL,
  `noteLocation` varchar(200) NOT NULL,
  `noteValue` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`id`, `userId`, `noteLocation`, `noteValue`) VALUES
(7, 46, '/modules/start.php?id=14&disId=1&', 'Round 1:\nJan: \n- '),
(8, 48, '/modules/start.php?id=14&disId=6&', '<span class=\'highlightedElement\'> <span class=\'highlightedElement\'><span class=\'highlightedElement\'><span class=\'highlightedElement\'> jonis cocoa kola in Jocke kokakola in dubb Coady x dbbcoady Ozzy </span> </span>\n'),
(9, 48, '/modules/start.php?id=14&disId=7&', '<span class=\'highlightedElement\'> knut 8.5</span>\n9.3\n8'),
(10, 34, '/modules/start.php?id=14&disId=15&', 'Test\n1\nQ'),
(11, 100, '/modules/start.php?id=14&compId=6&', 'Difficulty:\n\nDouble Flip | 0.1 - 1\nTriple Flip | 0.3 - 1\nQuad Flip | 0.5 - 1\nTriple twist | 0.1 - 1\nQuad twist | 0.3 - 1\nQuint Twist | 0.5 - 1\nBoth Directions in 1 skill | 0.5 - 1\n2 Landing Positions | 0.1 - 1\nFinal Skill to feet | 0.5 - 1\nBoth directions separate skills | 0.5 - 1\n\n\n');

-- --------------------------------------------------------

--
-- Table structure for table `permissionpresets`
--

CREATE TABLE `permissionpresets` (
  `id` int NOT NULL,
  `title` varchar(200) NOT NULL,
  `value` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `permissionpresets`
--

INSERT INTO `permissionpresets` (`id`, `title`, `value`) VALUES
(1, 'Judge', ',1,,2,,3,,7,,17,'),
(2, 'Competition Supervisor', ',1,,2,,3,,4,,17,'),
(3, 'Event Manager', ',1,,2,,3,,4,,6,,8,,17,,14,'),
(4, 'Event Admin', ',1,,2,,3,,4,,5,,6,,8,,12,,17,,14,,15,'),
(5, 'Athlete', ',1,,2,,3,,9,,17,'),
(6, 'Streamer', ',1,,2,,3,,18,');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int NOT NULL,
  `title` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `title`) VALUES
(1, 'View Home'),
(2, 'View Competitions'),
(3, 'View Disciplines'),
(4, 'Supervise Discipline'),
(5, 'Add Members'),
(6, 'Manage Members'),
(7, 'Judge Discipline'),
(8, 'View Members'),
(9, 'Can Compete'),
(11, 'SU'),
(12, 'Delete Members'),
(14, 'Move Members'),
(15, 'Update Critical Member Info'),
(16, 'Logged Out'),
(17, 'Logged In'),
(18, 'Is Streamer');

-- --------------------------------------------------------

--
-- Table structure for table `scores`
--

CREATE TABLE `scores` (
  `id` int NOT NULL,
  `judgeId` int NOT NULL,
  `competitorId` int NOT NULL,
  `disciplineId` int NOT NULL,
  `round` int NOT NULL,
  `criteria` varchar(400) NOT NULL,
  `value` float NOT NULL,
  `unixTimestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `scores`
--

INSERT INTO `scores` (`id`, `judgeId`, `competitorId`, `disciplineId`, `round`, `criteria`, `value`, `unixTimestamp`) VALUES
(30, 15, 45, 1, 1, 'creativity', 6.1, '2022-05-01 09:08:08'),
(31, 47, 45, 1, 1, 'execution', 6, '2022-05-01 09:08:32'),
(32, 46, 45, 1, 1, 'difficulty', 7.6, '2022-05-01 09:08:35'),
(33, 46, 38, 1, 1, 'difficulty', 6.7, '2022-05-01 09:09:27'),
(34, 47, 38, 1, 1, 'execution', 7, '2022-05-01 09:09:36'),
(35, 15, 38, 1, 1, 'creativity', 6.9, '2022-05-01 09:09:49'),
(36, 46, 37, 1, 1, 'difficulty', 6, '2022-05-01 09:10:27'),
(37, 15, 37, 1, 1, 'creativity', 5.3, '2022-05-01 09:10:41'),
(38, 47, 37, 1, 1, 'execution', 6.4, '2022-05-01 09:10:42'),
(39, 46, 43, 1, 1, 'difficulty', 7, '2022-05-01 09:11:43'),
(40, 47, 43, 1, 1, 'execution', 7.7, '2022-05-01 09:11:48'),
(41, 15, 43, 1, 1, 'creativity', 8, '2022-05-01 09:12:16'),
(42, 46, 42, 1, 1, 'difficulty', 5, '2022-05-01 09:12:41'),
(43, 47, 42, 1, 1, 'execution', 5, '2022-05-01 09:12:43'),
(45, 15, 42, 1, 1, 'creativity', 5.9, '2022-05-01 09:13:28'),
(47, 46, 44, 1, 1, 'difficulty', 7.6, '2022-05-01 09:14:41'),
(48, 47, 44, 1, 1, 'execution', 6.3, '2022-05-01 09:14:43'),
(49, 15, 44, 1, 1, 'creativity', 6.2, '2022-05-01 09:14:46'),
(50, 46, 35, 1, 1, 'difficulty', 8.9, '2022-05-01 09:15:52'),
(51, 47, 35, 1, 1, 'execution', 8, '2022-05-01 09:16:10'),
(52, 15, 35, 1, 1, 'creativity', 7.8, '2022-05-01 09:16:31'),
(54, 47, 41, 1, 1, 'execution', 6.9, '2022-05-01 09:17:13'),
(55, 15, 41, 1, 1, 'creativity', 8.6, '2022-05-01 09:17:15'),
(56, 46, 41, 1, 1, 'difficulty', 7.7, '2022-05-01 09:17:20'),
(57, 46, 36, 1, 1, 'difficulty', 8.4, '2022-05-01 09:18:30'),
(59, 47, 36, 1, 1, 'execution', 8.6, '2022-05-01 09:18:38'),
(60, 15, 36, 1, 1, 'creativity', 8.1, '2022-05-01 09:18:45'),
(62, 46, 40, 1, 1, 'difficulty', 6.5, '2022-05-01 09:19:58'),
(64, 47, 40, 1, 1, 'execution', 6.6, '2022-05-01 09:20:21'),
(65, 15, 40, 1, 1, 'creativity', 8, '2022-05-01 09:20:33'),
(66, 15, 39, 1, 1, 'creativity', 0, '2022-05-01 09:24:42'),
(67, 46, 45, 4, 1, 'difficulty', 7, '2022-05-01 09:46:16'),
(68, 15, 45, 4, 1, 'creativity', 8.5, '2022-05-01 09:46:25'),
(69, 47, 45, 4, 1, 'execution', 7, '2022-05-01 09:46:44'),
(70, 46, 44, 4, 1, 'difficulty', 8.6, '2022-05-01 09:47:55'),
(71, 47, 44, 4, 1, 'execution', 8.3, '2022-05-01 09:48:07'),
(72, 15, 44, 4, 1, 'creativity', 8.2, '2022-05-01 09:48:13'),
(73, 46, 38, 4, 1, 'difficulty', 6.7, '2022-05-01 09:50:31'),
(74, 47, 38, 4, 1, 'execution', 6.7, '2022-05-01 09:50:33'),
(75, 15, 38, 4, 1, 'creativity', 6.9, '2022-05-01 09:50:35'),
(76, 46, 40, 4, 1, 'difficulty', 6, '2022-05-01 09:52:02'),
(77, 47, 40, 4, 1, 'execution', 5.2, '2022-05-01 09:52:13'),
(78, 15, 40, 4, 1, 'creativity', 5.9, '2022-05-01 09:52:18'),
(79, 46, 43, 4, 1, 'difficulty', 7.7, '2022-05-01 09:55:06'),
(80, 47, 43, 4, 1, 'execution', 8.1, '2022-05-01 09:55:08'),
(81, 15, 43, 4, 1, 'creativity', 9.1, '2022-05-01 09:55:13'),
(82, 46, 41, 4, 1, 'difficulty', 5, '2022-05-01 09:57:38'),
(83, 47, 41, 4, 1, 'execution', 6, '2022-05-01 09:58:04'),
(84, 15, 41, 4, 1, 'creativity', 8.6, '2022-05-01 09:58:12'),
(86, 15, 35, 4, 1, 'creativity', 7.3, '2022-05-01 10:04:11'),
(87, 46, 35, 4, 1, 'difficulty', 9, '2022-05-01 10:04:15'),
(88, 47, 35, 4, 1, 'execution', 8.9, '2022-05-01 10:04:25'),
(89, 15, 36, 4, 1, 'creativity', 8.8, '2022-05-01 10:07:05'),
(90, 47, 36, 4, 1, 'execution', 8.1, '2022-05-01 10:07:32'),
(91, 46, 36, 4, 1, 'difficulty', 8.8, '2022-05-01 10:07:33'),
(92, 46, 45, 5, 1, 'difficulty', 8.5, '2022-05-01 10:26:08'),
(93, 47, 45, 5, 1, 'execution', 8.3, '2022-05-01 10:26:49'),
(94, 15, 45, 5, 1, 'creativity', 9.3, '2022-05-01 10:27:00'),
(95, 46, 44, 5, 1, 'difficulty', 7.5, '2022-05-01 10:28:36'),
(97, 15, 44, 5, 1, 'creativity', 5.5, '2022-05-01 10:28:45'),
(98, 47, 44, 5, 1, 'execution', 6.5, '2022-05-01 10:29:11'),
(99, 15, 35, 5, 1, 'creativity', 8, '2022-05-01 10:32:48'),
(100, 46, 35, 5, 1, 'difficulty', 9.1, '2022-05-01 10:33:06'),
(101, 47, 35, 5, 1, 'execution', 7.7, '2022-05-01 10:33:31'),
(103, 15, 36, 5, 1, 'creativity', 8, '2022-05-01 10:40:44'),
(104, 46, 36, 5, 1, 'difficulty', 8.6, '2022-05-01 10:40:46'),
(105, 47, 36, 5, 1, 'execution', 8.1, '2022-05-01 10:41:05'),
(106, 46, 59, 6, 1, 'difficulty', 7, '2022-05-28 12:06:38'),
(107, 47, 59, 6, 1, 'execution', 7, '2022-05-28 12:06:42'),
(108, 48, 59, 6, 1, 'creativity', 7.1, '2022-05-28 12:06:47'),
(109, 46, 53, 6, 1, 'difficulty', 7.2, '2022-05-28 12:09:19'),
(110, 47, 53, 6, 1, 'execution', 7.4, '2022-05-28 12:09:21'),
(111, 48, 53, 6, 1, 'creativity', 6.8, '2022-05-28 12:09:25'),
(112, 48, 52, 6, 1, 'creativity', 7.9, '2022-05-28 12:14:15'),
(113, 47, 52, 6, 1, 'execution', 8, '2022-05-28 12:14:16'),
(114, 46, 52, 6, 1, 'difficulty', 7.8, '2022-05-28 12:14:17'),
(115, 46, 51, 6, 1, 'difficulty', 7.1, '2022-05-28 12:17:01'),
(116, 47, 51, 6, 1, 'execution', 5, '2022-05-28 12:17:42'),
(117, 48, 51, 6, 1, 'creativity', 4, '2022-05-28 12:18:06'),
(118, 46, 60, 6, 1, 'difficulty', 7.5, '2022-05-28 12:20:12'),
(119, 47, 60, 6, 1, 'execution', 7.9, '2022-05-28 12:20:16'),
(120, 48, 60, 6, 1, 'creativity', 7, '2022-05-28 12:20:18'),
(121, 47, 57, 6, 1, 'execution', 0, '2022-05-28 12:21:16'),
(122, 46, 57, 6, 1, 'difficulty', 0, '2022-05-28 12:21:18'),
(123, 48, 57, 6, 1, 'creativity', 0, '2022-05-28 12:21:21'),
(124, 48, 56, 6, 1, 'creativity', 7.5, '2022-05-28 12:23:19'),
(125, 46, 56, 6, 1, 'difficulty', 7.6, '2022-05-28 12:23:20'),
(126, 47, 56, 6, 1, 'execution', 7.4, '2022-05-28 12:23:24'),
(127, 46, 58, 6, 1, 'difficulty', 8.8, '2022-05-28 12:25:13'),
(128, 48, 58, 6, 1, 'creativity', 8.7, '2022-05-28 12:25:15'),
(129, 47, 58, 6, 1, 'execution', 8.8, '2022-05-28 12:25:19'),
(130, 46, 49, 6, 1, 'difficulty', 6.4, '2022-05-28 12:28:48'),
(131, 48, 49, 6, 1, 'creativity', 5, '2022-05-28 12:29:00'),
(132, 47, 49, 6, 1, 'execution', 5.5, '2022-05-28 12:29:01'),
(133, 46, 62, 6, 1, 'difficulty', 6.5, '2022-05-28 12:30:53'),
(134, 47, 62, 6, 1, 'execution', 7, '2022-05-28 12:31:08'),
(135, 48, 62, 6, 1, 'creativity', 6, '2022-05-28 12:31:10'),
(136, 46, 55, 6, 1, 'difficulty', 9.5, '2022-05-28 12:32:49'),
(137, 47, 55, 6, 1, 'execution', 9.5, '2022-05-28 12:32:51'),
(138, 48, 55, 6, 1, 'creativity', 9, '2022-05-28 12:32:52'),
(139, 46, 61, 6, 1, 'difficulty', 8, '2022-05-28 12:34:40'),
(140, 47, 61, 6, 1, 'execution', 8.5, '2022-05-28 12:34:46'),
(141, 48, 61, 6, 1, 'creativity', 7.5, '2022-05-28 12:34:48'),
(142, 46, 50, 6, 1, 'difficulty', 7.9, '2022-05-28 12:37:27'),
(143, 47, 50, 6, 1, 'execution', 7.4, '2022-05-28 12:37:33'),
(144, 48, 50, 6, 1, 'creativity', 8.2, '2022-05-28 12:37:33'),
(145, 46, 54, 6, 1, 'difficulty', 7.2, '2022-05-28 12:39:23'),
(146, 47, 54, 6, 1, 'execution', 8.1, '2022-05-28 12:39:34'),
(147, 48, 54, 6, 1, 'creativity', 7.5, '2022-05-28 12:39:37'),
(148, 46, 55, 7, 1, 'difficulty', 7.8, '2022-05-28 12:56:21'),
(149, 48, 55, 7, 1, 'creativity', 8.5, '2022-05-28 12:56:22'),
(150, 47, 55, 7, 1, 'execution', 7.6, '2022-05-28 12:56:23'),
(151, 46, 58, 7, 1, 'difficulty', 9.4, '2022-05-28 12:57:59'),
(152, 47, 58, 7, 1, 'execution', 9.3, '2022-05-28 12:58:00'),
(153, 48, 58, 7, 1, 'creativity', 9.3, '2022-05-28 12:58:02'),
(154, 46, 61, 7, 1, 'difficulty', 8.7, '2022-05-28 12:59:31'),
(155, 47, 61, 7, 1, 'execution', 9.1, '2022-05-28 12:59:35'),
(156, 48, 61, 7, 1, 'creativity', 8.2, '2022-05-28 12:59:36'),
(157, 46, 52, 7, 1, 'difficulty', 6.7, '2022-05-28 13:02:43'),
(158, 48, 52, 7, 1, 'creativity', 7.5, '2022-05-28 13:03:00'),
(159, 47, 52, 7, 1, 'execution', 7.3, '2022-05-28 13:03:11'),
(160, 46, 50, 7, 1, 'difficulty', 8.1, '2022-05-28 13:04:50'),
(161, 48, 50, 7, 1, 'creativity', 8, '2022-05-28 13:04:54'),
(162, 47, 50, 7, 1, 'execution', 8, '2022-05-28 13:05:01'),
(163, 46, 54, 7, 1, 'difficulty', 7.9, '2022-05-28 13:07:44'),
(164, 47, 54, 7, 1, 'execution', 7.5, '2022-05-28 13:08:22'),
(165, 48, 54, 7, 1, 'creativity', 7.5, '2022-05-28 13:08:25'),
(166, 48, 56, 7, 1, 'creativity', 8.4, '2022-05-28 13:10:47'),
(167, 47, 56, 7, 1, 'execution', 7.8, '2022-05-28 13:11:02'),
(168, 46, 56, 7, 1, 'difficulty', 7.9, '2022-05-28 13:11:13'),
(169, 46, 60, 7, 1, 'difficulty', 8.3, '2022-05-28 13:12:13'),
(170, 47, 60, 7, 1, 'execution', 8.3, '2022-05-28 13:12:16'),
(171, 48, 60, 7, 1, 'creativity', 8.9, '2022-05-28 13:12:17'),
(174, 46, 53, 7, 1, 'difficulty', 7.6, '2022-05-28 13:14:41'),
(175, 47, 53, 7, 1, 'execution', 8.2, '2022-05-28 13:15:23'),
(176, 48, 53, 7, 1, 'creativity', 8.2, '2022-05-28 13:15:26'),
(177, 47, 59, 7, 1, 'execution', 9.3, '2022-05-28 13:20:37'),
(178, 46, 59, 7, 1, 'difficulty', 8.6, '2022-05-28 13:20:50'),
(179, 48, 59, 7, 1, 'creativity', 8.4, '2022-05-28 13:21:27'),
(180, 46, 58, 8, 1, 'difficulty', 7.6, '2022-05-28 13:33:58'),
(181, 47, 58, 8, 1, 'execution', 7.5, '2022-05-28 13:34:01'),
(182, 48, 58, 8, 1, 'creativity', 8, '2022-05-28 13:34:05'),
(183, 46, 61, 8, 1, 'difficulty', 7.9, '2022-05-28 13:37:29'),
(184, 47, 61, 8, 1, 'execution', 8.2, '2022-05-28 13:37:35'),
(185, 48, 61, 8, 1, 'creativity', 8.5, '2022-05-28 13:37:40'),
(186, 46, 60, 8, 1, 'difficulty', 8.4, '2022-05-28 13:39:26'),
(187, 47, 60, 8, 1, 'execution', 8.6, '2022-05-28 13:39:44'),
(188, 48, 60, 8, 1, 'creativity', 8, '2022-05-28 13:39:47'),
(189, 47, 59, 8, 1, 'execution', 9.3, '2022-05-28 13:42:39'),
(190, 46, 59, 8, 1, 'difficulty', 9.3, '2022-05-28 13:42:41'),
(191, 48, 59, 8, 1, 'creativity', 8.7, '2022-05-28 13:42:43'),
(192, 46, 50, 8, 1, 'difficulty', 7.6, '2022-05-28 13:45:19'),
(193, 47, 50, 8, 1, 'execution', 8.9, '2022-05-28 13:45:57'),
(194, 48, 50, 8, 1, 'creativity', 9.4, '2022-05-28 13:46:18'),
(201, 46, 74, 9, 1, 'difficulty', 7.9, '2022-06-04 14:27:30'),
(202, 48, 74, 9, 1, 'creativity', 7.5, '2022-06-04 14:27:55'),
(203, 47, 74, 9, 1, 'execution', 7, '2022-06-04 14:32:12'),
(204, 46, 68, 9, 1, 'difficulty', 6.9, '2022-06-04 14:32:27'),
(205, 47, 68, 9, 1, 'execution', 6.8, '2022-06-04 14:32:32'),
(206, 48, 68, 9, 1, 'creativity', 7.2, '2022-06-04 14:33:45'),
(207, 46, 83, 9, 1, 'difficulty', 6.2, '2022-06-04 14:34:12'),
(208, 47, 83, 9, 1, 'execution', 5.5, '2022-06-04 14:34:13'),
(209, 48, 83, 9, 1, 'creativity', 8.2, '2022-06-04 14:34:23'),
(210, 47, 67, 9, 1, 'execution', 5.4, '2022-06-04 14:35:20'),
(211, 46, 67, 9, 1, 'difficulty', 5.9, '2022-06-04 14:35:20'),
(212, 48, 67, 9, 1, 'creativity', 6, '2022-06-04 14:35:31'),
(213, 46, 66, 9, 1, 'difficulty', 6.8, '2022-06-04 14:36:21'),
(214, 48, 66, 9, 1, 'creativity', 9.3, '2022-06-04 14:36:23'),
(215, 47, 66, 9, 1, 'execution', 6.2, '2022-06-04 14:36:29'),
(216, 47, 75, 9, 1, 'execution', 5.7, '2022-06-04 14:38:19'),
(217, 48, 75, 9, 1, 'creativity', 8.8, '2022-06-04 14:38:31'),
(218, 46, 75, 9, 1, 'difficulty', 7.8, '2022-06-04 14:38:38'),
(219, 46, 72, 9, 1, 'difficulty', 5.9, '2022-06-04 14:41:34'),
(220, 47, 72, 9, 1, 'execution', 5.3, '2022-06-04 14:41:41'),
(221, 48, 72, 9, 1, 'creativity', 7.3, '2022-06-04 14:41:49'),
(222, 46, 78, 9, 1, 'difficulty', 7.5, '2022-06-04 14:42:43'),
(223, 47, 78, 9, 1, 'execution', 7.2, '2022-06-04 14:43:00'),
(224, 48, 78, 9, 1, 'creativity', 8.4, '2022-06-04 14:43:06'),
(226, 47, 71, 9, 1, 'execution', 5, '2022-06-04 14:44:02'),
(227, 46, 71, 9, 1, 'difficulty', 5.4, '2022-06-04 14:44:09'),
(228, 48, 71, 9, 1, 'creativity', 8, '2022-06-04 14:44:14'),
(229, 47, 73, 9, 1, 'execution', 7.4, '2022-06-04 14:45:45'),
(231, 46, 73, 9, 1, 'difficulty', 8.4, '2022-06-04 14:45:53'),
(232, 48, 73, 9, 1, 'creativity', 9.4, '2022-06-04 14:45:54'),
(233, 47, 64, 9, 1, 'execution', 4.6, '2022-06-04 14:48:59'),
(234, 48, 64, 9, 1, 'creativity', 9.2, '2022-06-04 14:49:02'),
(235, 46, 64, 9, 1, 'difficulty', 8.7, '2022-06-04 14:49:40'),
(236, 46, 79, 9, 1, 'difficulty', 6.8, '2022-06-04 14:51:02'),
(237, 47, 79, 9, 1, 'execution', 7.3, '2022-06-04 14:51:25'),
(238, 48, 79, 9, 1, 'creativity', 8, '2022-06-04 14:51:28'),
(239, 47, 81, 9, 1, 'execution', 6.9, '2022-06-04 14:54:16'),
(240, 46, 81, 9, 1, 'difficulty', 8.3, '2022-06-04 14:54:43'),
(241, 48, 81, 9, 1, 'creativity', 8.6, '2022-06-04 14:54:45'),
(242, 47, 77, 9, 1, 'execution', 0, '2022-06-04 14:55:09'),
(243, 48, 77, 9, 1, 'creativity', 0, '2022-06-04 14:55:10'),
(244, 46, 77, 9, 1, 'difficulty', 0, '2022-06-04 14:55:10'),
(245, 46, 80, 9, 1, 'difficulty', 5, '2022-06-04 14:57:17'),
(246, 48, 80, 9, 1, 'creativity', 7.2, '2022-06-04 14:57:35'),
(247, 47, 80, 9, 1, 'execution', 4.6, '2022-06-04 14:57:36'),
(248, 48, 70, 9, 1, 'creativity', 0, '2022-06-04 14:57:50'),
(249, 46, 70, 9, 1, 'difficulty', 0, '2022-06-04 14:57:57'),
(250, 47, 70, 9, 1, 'execution', 0, '2022-06-04 14:58:05'),
(251, 46, 82, 9, 1, 'difficulty', 6.2, '2022-06-04 15:00:31'),
(252, 48, 82, 9, 1, 'creativity', 8.2, '2022-06-04 15:00:41'),
(253, 47, 82, 9, 1, 'execution', 7.1, '2022-06-04 15:00:54'),
(254, 46, 76, 9, 1, 'difficulty', 9.6, '2022-06-04 15:02:20'),
(255, 47, 76, 9, 1, 'execution', 7.8, '2022-06-04 15:02:36'),
(256, 48, 76, 9, 1, 'creativity', 9.6, '2022-06-04 15:02:53'),
(257, 47, 65, 9, 1, 'execution', 7.1, '2022-06-04 15:05:11'),
(258, 48, 65, 9, 1, 'creativity', 9.2, '2022-06-04 15:05:32'),
(259, 46, 65, 9, 1, 'difficulty', 8.7, '2022-06-04 15:05:40'),
(261, 46, 69, 9, 1, 'difficulty', 6.5, '2022-06-04 15:07:14'),
(262, 47, 69, 9, 1, 'execution', 6.9, '2022-06-04 15:07:18'),
(263, 48, 69, 9, 1, 'creativity', 8, '2022-06-04 15:07:44'),
(264, 47, 82, 10, 1, 'execution', 8.2, '2022-06-04 15:27:07'),
(265, 48, 82, 10, 1, 'creativity', 8.4, '2022-06-04 15:27:44'),
(266, 46, 82, 10, 1, 'difficulty', 6.9, '2022-06-04 15:30:53'),
(267, 47, 79, 10, 1, 'execution', 7.5, '2022-06-04 15:31:23'),
(268, 46, 79, 10, 1, 'difficulty', 7.2, '2022-06-04 15:31:28'),
(269, 48, 79, 10, 1, 'creativity', 8.6, '2022-06-04 15:31:34'),
(270, 48, 66, 10, 1, 'creativity', 8.4, '2022-06-04 15:31:56'),
(271, 46, 66, 10, 1, 'difficulty', 7.7, '2022-06-04 15:32:16'),
(272, 47, 66, 10, 1, 'execution', 7.7, '2022-06-04 15:32:33'),
(274, 46, 75, 10, 1, 'difficulty', 6.5, '2022-06-04 15:34:02'),
(275, 48, 75, 10, 1, 'creativity', 7, '2022-06-04 15:34:07'),
(276, 47, 75, 10, 1, 'execution', 6.9, '2022-06-04 15:34:19'),
(277, 47, 74, 10, 1, 'execution', 8.8, '2022-06-04 15:35:43'),
(278, 48, 74, 10, 1, 'creativity', 9.2, '2022-06-04 15:36:05'),
(279, 46, 74, 10, 1, 'difficulty', 8.7, '2022-06-04 15:36:22'),
(281, 46, 64, 10, 1, 'difficulty', 9, '2022-06-04 15:41:12'),
(282, 47, 64, 10, 1, 'execution', 7.9, '2022-06-04 15:41:31'),
(283, 48, 64, 10, 1, 'creativity', 9.4, '2022-06-04 15:41:42'),
(286, 46, 78, 10, 1, 'difficulty', 8.1, '2022-06-04 15:44:01'),
(287, 48, 78, 10, 1, 'creativity', 8.5, '2022-06-04 15:44:01'),
(288, 47, 78, 10, 1, 'execution', 7.8, '2022-06-04 15:44:14'),
(289, 46, 81, 10, 1, 'difficulty', 8.4, '2022-06-04 15:46:41'),
(290, 48, 81, 10, 1, 'creativity', 8.8, '2022-06-04 15:46:42'),
(291, 47, 81, 10, 1, 'execution', 6.8, '2022-06-04 15:46:44'),
(292, 47, 65, 10, 1, 'execution', 9.1, '2022-06-04 15:48:19'),
(293, 46, 65, 10, 1, 'difficulty', 8.5, '2022-06-04 15:48:30'),
(294, 48, 65, 10, 1, 'creativity', 9.2, '2022-06-04 15:48:36'),
(295, 47, 73, 10, 1, 'execution', 9, '2022-06-04 15:50:02'),
(296, 48, 73, 10, 1, 'creativity', 9.3, '2022-06-04 15:50:34'),
(297, 46, 73, 10, 1, 'difficulty', 9, '2022-06-04 15:50:41'),
(298, 46, 76, 10, 1, 'difficulty', 9.4, '2022-06-04 15:54:13'),
(299, 47, 76, 10, 1, 'execution', 9, '2022-06-04 15:54:22'),
(300, 48, 76, 10, 1, 'creativity', 9.4, '2022-06-04 15:54:47'),
(301, 47, 76, 11, 1, 'execution', 9.1, '2022-06-04 16:03:12'),
(302, 48, 76, 11, 1, 'creativity', 9.5, '2022-06-04 16:04:20'),
(303, 46, 76, 11, 1, 'difficulty', 9.7, '2022-06-04 16:04:24'),
(304, 47, 73, 11, 1, 'execution', 9.3, '2022-06-04 16:05:48'),
(305, 48, 73, 11, 1, 'creativity', 9.3, '2022-06-04 16:06:03'),
(306, 46, 73, 11, 1, 'difficulty', 9.7, '2022-06-04 16:06:09'),
(307, 46, 65, 11, 1, 'difficulty', 9, '2022-06-04 16:10:34'),
(309, 47, 65, 11, 1, 'execution', 9.1, '2022-06-04 16:11:22'),
(310, 48, 65, 11, 1, 'creativity', 9.1, '2022-06-04 16:11:29'),
(312, 46, 64, 11, 1, 'difficulty', 8.6, '2022-06-04 16:14:12'),
(313, 47, 64, 11, 1, 'execution', 8.7, '2022-06-04 16:14:12'),
(314, 48, 64, 11, 1, 'creativity', 8.9, '2022-06-04 16:14:16'),
(315, 47, 74, 11, 1, 'execution', 9.5, '2022-06-04 16:16:56'),
(316, 48, 74, 11, 1, 'creativity', 9.2, '2022-06-04 16:17:17'),
(317, 46, 74, 11, 1, 'difficulty', 8.9, '2022-06-04 16:17:18'),
(318, 47, 73, 15, 1, 'execution', 6.3, '2023-04-26 15:41:35'),
(320, 100, 73, 15, 1, 'difficulty', 7.8, '2023-04-26 15:59:47'),
(321, 101, 97, 15, 1, 'creativity', 5, '2023-04-26 16:06:58'),
(323, 101, 73, 15, 1, 'creativity', 3.8, '2023-04-26 16:09:14'),
(336, 101, 117, 15, 1, 'creativity', 3, '2023-04-29 16:13:23'),
(338, 47, 117, 15, 1, 'execution', 5, '2023-04-29 16:14:00'),
(343, 101, 119, 15, 1, 'creativity', 1, '2023-04-29 16:20:10'),
(345, 47, 119, 15, 1, 'execution', 5.8, '2023-04-29 16:20:44'),
(346, 101, 120, 15, 1, 'creativity', 0.6, '2023-04-29 16:24:17'),
(347, 47, 120, 15, 1, 'execution', 6, '2023-04-29 16:24:50'),
(350, 101, 121, 15, 1, 'creativity', 4.1, '2023-04-29 16:28:38'),
(352, 47, 121, 15, 1, 'execution', 5.9, '2023-04-29 16:29:15'),
(353, 101, 122, 15, 1, 'creativity', 2.9, '2023-04-29 16:32:26'),
(355, 47, 122, 15, 1, 'execution', 6.3, '2023-04-29 16:32:49'),
(357, 101, 123, 15, 1, 'creativity', 4.4, '2023-04-29 16:35:38'),
(358, 47, 123, 15, 1, 'execution', 5.9, '2023-04-29 16:36:42'),
(359, 101, 124, 15, 1, 'creativity', 2.5, '2023-04-29 16:39:25'),
(361, 47, 124, 15, 1, 'execution', 5.5, '2023-04-29 16:40:09'),
(362, 101, 125, 15, 1, 'creativity', 0.6, '2023-04-29 16:43:35'),
(364, 47, 125, 15, 1, 'execution', 6, '2023-04-29 16:45:26'),
(365, 101, 126, 15, 1, 'creativity', 1.9, '2023-04-29 16:48:28'),
(367, 47, 126, 15, 1, 'execution', 5, '2023-04-29 16:48:41'),
(368, 101, 127, 15, 1, 'creativity', 2.4, '2023-04-29 16:51:22'),
(370, 47, 127, 15, 1, 'execution', 6.2, '2023-04-29 16:52:10'),
(375, 101, 128, 15, 1, 'creativity', 1.2, '2023-04-29 16:58:08'),
(378, 101, 129, 15, 1, 'creativity', 0.2, '2023-04-29 17:01:06'),
(380, 47, 129, 15, 1, 'execution', 3.5, '2023-04-29 17:01:54'),
(385, 101, 132, 15, 1, 'creativity', 1.4, '2023-04-29 17:08:41'),
(386, 47, 132, 15, 1, 'execution', 4.3, '2023-04-29 17:09:28'),
(389, 101, 133, 15, 1, 'creativity', 1.8, '2023-04-29 17:14:31'),
(390, 47, 133, 15, 1, 'execution', 5.9, '2023-04-29 17:14:54'),
(391, 100, 127, 15, 1, 'difficulty', 3.8, '2023-04-29 17:18:06'),
(392, 100, 121, 15, 1, 'difficulty', 3.4, '2023-04-29 17:19:17'),
(393, 100, 120, 15, 1, 'difficulty', 4.4, '2023-04-29 17:20:52'),
(394, 100, 119, 15, 1, 'difficulty', 4, '2023-04-29 17:21:09'),
(395, 100, 128, 15, 1, 'difficulty', 3.6, '2023-04-29 17:21:25'),
(396, 100, 125, 15, 1, 'difficulty', 3.2, '2023-04-29 17:21:39'),
(398, 100, 124, 15, 1, 'difficulty', 4.4, '2023-04-29 17:22:09'),
(399, 100, 126, 15, 1, 'difficulty', 2.4, '2023-04-29 17:22:28'),
(400, 100, 117, 15, 1, 'difficulty', 5, '2023-04-29 17:23:10'),
(401, 100, 132, 15, 1, 'difficulty', 1.6, '2023-04-29 17:23:19'),
(403, 100, 133, 15, 1, 'difficulty', 3.8, '2023-04-29 17:23:48'),
(404, 100, 123, 15, 1, 'difficulty', 3.4, '2023-04-29 17:24:02'),
(405, 100, 129, 15, 1, 'difficulty', 1.2, '2023-04-29 17:24:17'),
(407, 100, 122, 15, 1, 'difficulty', 4.8, '2023-04-29 17:24:47'),
(411, 47, 117, 16, 1, 'execution', 4.9, '2023-04-29 17:53:23'),
(412, 100, 117, 16, 1, 'difficulty', 2.4, '2023-04-29 17:53:46'),
(413, 101, 119, 16, 1, 'creativity', 1.9, '2023-04-29 17:56:25'),
(414, 47, 119, 16, 1, 'execution', 6, '2023-04-29 17:56:43'),
(415, 100, 119, 16, 1, 'difficulty', 4.2, '2023-04-29 17:57:01'),
(416, 101, 120, 16, 1, 'creativity', 2.2, '2023-04-29 18:00:44'),
(417, 100, 120, 16, 1, 'difficulty', 4.8, '2023-04-29 18:00:46'),
(418, 47, 120, 16, 1, 'execution', 7.4, '2023-04-29 18:00:55'),
(419, 101, 121, 16, 1, 'creativity', 2.9, '2023-04-29 18:04:39'),
(420, 100, 121, 16, 1, 'difficulty', 3.6, '2023-04-29 18:04:53'),
(421, 47, 121, 16, 1, 'execution', 5.8, '2023-04-29 18:05:06'),
(423, 101, 122, 16, 1, 'creativity', 2.5, '2023-04-29 18:09:07'),
(424, 47, 122, 16, 1, 'execution', 6.5, '2023-04-29 18:09:52'),
(425, 100, 122, 16, 1, 'difficulty', 4.8, '2023-04-29 18:10:00'),
(426, 101, 123, 16, 1, 'creativity', 1.7, '2023-04-29 18:12:28'),
(427, 100, 123, 16, 1, 'difficulty', 2.6, '2023-04-29 18:13:00'),
(428, 47, 123, 16, 1, 'execution', 4.3, '2023-04-29 18:13:21'),
(429, 101, 124, 16, 1, 'creativity', 1.9, '2023-04-29 18:15:18'),
(430, 100, 124, 16, 1, 'difficulty', 3.6, '2023-04-29 18:15:35'),
(431, 47, 124, 16, 1, 'execution', 5.6, '2023-04-29 18:16:37'),
(432, 101, 125, 16, 1, 'creativity', 1.1, '2023-04-29 18:19:21'),
(433, 100, 125, 16, 1, 'difficulty', 3.6, '2023-04-29 18:20:14'),
(434, 47, 125, 16, 1, 'execution', 5.9, '2023-04-29 18:20:22'),
(435, 101, 127, 16, 1, 'creativity', 1.3, '2023-04-29 18:23:01'),
(436, 100, 127, 16, 1, 'difficulty', 3.8, '2023-04-29 18:23:30'),
(438, 101, 128, 16, 1, 'creativity', 1.9, '2023-04-29 18:26:26'),
(439, 100, 128, 16, 1, 'difficulty', 3, '2023-04-29 18:26:46'),
(440, 47, 128, 16, 1, 'execution', 4.7, '2023-04-29 18:27:08'),
(441, 47, 127, 16, 1, 'execution', 6.2, '2023-04-29 18:31:00'),
(442, 101, 117, 16, 1, 'creativity', 2.5, '2023-04-29 18:31:47'),
(443, 101, 119, 17, 1, 'creativity', 1.9, '2023-04-29 18:56:55'),
(444, 47, 119, 17, 1, 'execution', 5, '2023-04-29 18:57:18'),
(447, 101, 120, 17, 1, 'creativity', 3, '2023-04-29 19:00:02'),
(448, 47, 120, 17, 1, 'execution', 7.1, '2023-04-29 19:01:06'),
(449, 100, 120, 17, 1, 'difficulty', 4, '2023-04-29 19:01:30'),
(450, 100, 121, 17, 1, 'difficulty', 3.2, '2023-04-29 19:03:39'),
(451, 101, 121, 17, 1, 'creativity', 2.1, '2023-04-29 19:03:48'),
(452, 47, 121, 17, 1, 'execution', 5.5, '2023-04-29 19:05:21'),
(453, 100, 119, 17, 1, 'difficulty', 3, '2023-04-29 19:05:53'),
(454, 101, 122, 17, 1, 'creativity', 2.5, '2023-04-29 19:07:57'),
(456, 47, 122, 17, 1, 'execution', 7.1, '2023-04-29 19:08:10'),
(457, 101, 127, 17, 1, 'creativity', 1.9, '2023-04-29 19:10:45'),
(458, 100, 127, 17, 1, 'difficulty', 3.2, '2023-04-29 19:10:55'),
(459, 47, 127, 17, 1, 'execution', 5.2, '2023-04-29 19:12:29'),
(460, 100, 122, 17, 1, 'difficulty', 4.6, '2023-04-29 19:34:56'),
(461, 47, 128, 15, 1, 'execution', 5, '2023-04-29 19:36:18'),
(462, 101, 135, 15, 1, 'creativity', 2.8, '2023-04-30 12:41:34'),
(463, 47, 135, 15, 1, 'execution', 5.5, '2023-04-30 12:41:46'),
(466, 101, 136, 15, 1, 'creativity', 2.6, '2023-04-30 12:45:02'),
(467, 47, 136, 15, 1, 'execution', 6.6, '2023-04-30 12:45:27'),
(470, 47, 131, 15, 1, 'execution', 5.2, '2023-04-30 12:49:40'),
(471, 101, 131, 15, 1, 'creativity', 2.9, '2023-04-30 12:49:44'),
(473, 101, 137, 15, 1, 'creativity', 1.2, '2023-04-30 12:53:49'),
(474, 47, 137, 15, 1, 'execution', 6.3, '2023-04-30 12:54:04'),
(477, 101, 130, 15, 1, 'creativity', 1.4, '2023-04-30 12:58:11'),
(478, 47, 130, 15, 1, 'execution', 6.5, '2023-04-30 12:58:15'),
(480, 47, 139, 15, 1, 'execution', 5.3, '2023-04-30 13:01:03'),
(482, 101, 139, 15, 1, 'creativity', 2.5, '2023-04-30 13:01:53'),
(484, 101, 118, 15, 1, 'creativity', 2.1, '2023-04-30 13:06:33'),
(485, 47, 118, 15, 1, 'execution', 5.4, '2023-04-30 13:06:55'),
(487, 101, 140, 15, 1, 'creativity', 2.3, '2023-04-30 13:10:18'),
(488, 47, 140, 15, 1, 'execution', 6.3, '2023-04-30 13:10:28'),
(490, 101, 134, 15, 1, 'creativity', 6, '2023-04-30 13:15:48'),
(491, 47, 134, 15, 1, 'execution', 6.9, '2023-04-30 13:16:08'),
(493, 101, 141, 15, 1, 'creativity', 2.1, '2023-04-30 13:19:33'),
(494, 47, 141, 15, 1, 'execution', 5.8, '2023-04-30 13:20:35'),
(495, 100, 141, 15, 1, 'difficulty', 3.6, '2023-04-30 13:22:44'),
(496, 100, 135, 15, 1, 'difficulty', 3.9, '2023-04-30 13:23:09'),
(497, 100, 134, 15, 1, 'difficulty', 8.7, '2023-04-30 13:23:49'),
(498, 100, 131, 15, 1, 'difficulty', 2.7, '2023-04-30 13:24:37'),
(499, 100, 139, 15, 1, 'difficulty', 3, '2023-04-30 13:24:50'),
(500, 100, 140, 15, 1, 'difficulty', 6.6, '2023-04-30 13:25:25'),
(501, 100, 118, 15, 1, 'difficulty', 3.9, '2023-04-30 13:26:44'),
(502, 100, 137, 15, 1, 'difficulty', 5.4, '2023-04-30 13:27:36'),
(503, 100, 130, 15, 1, 'difficulty', 3.9, '2023-04-30 13:28:08'),
(504, 100, 136, 15, 1, 'difficulty', 5.7, '2023-04-30 13:32:50'),
(505, 47, 135, 16, 1, 'execution', 6.1, '2023-04-30 14:12:36'),
(506, 101, 135, 16, 1, 'creativity', 2.3, '2023-04-30 14:12:44'),
(507, 100, 135, 16, 1, 'difficulty', 5.4, '2023-04-30 14:13:01'),
(508, 100, 137, 16, 1, 'difficulty', 5.1, '2023-04-30 14:15:56'),
(509, 47, 137, 16, 1, 'execution', 5.7, '2023-04-30 14:16:19'),
(510, 101, 137, 16, 1, 'creativity', 2.2, '2023-04-30 14:17:08'),
(511, 100, 130, 16, 1, 'difficulty', 4.2, '2023-04-30 14:20:53'),
(513, 101, 130, 16, 1, 'creativity', 1.8, '2023-04-30 14:21:27'),
(514, 100, 140, 16, 1, 'difficulty', 5.1, '2023-04-30 14:24:09'),
(515, 101, 140, 16, 1, 'creativity', 3.6, '2023-04-30 14:24:26'),
(516, 47, 140, 16, 1, 'execution', 6.1, '2023-04-30 14:24:38'),
(517, 100, 134, 16, 1, 'difficulty', 9.3, '2023-04-30 14:30:15'),
(518, 47, 134, 16, 1, 'execution', 7.2, '2023-04-30 14:30:18'),
(519, 101, 134, 16, 1, 'creativity', 4.3, '2023-04-30 14:30:36'),
(520, 47, 130, 16, 1, 'execution', 5.8, '2023-04-30 14:31:08');

-- --------------------------------------------------------

--
-- Table structure for table `sites`
--

CREATE TABLE `sites` (
  `id` int NOT NULL,
  `title` varchar(500) NOT NULL,
  `permissions` varchar(500) NOT NULL,
  `modules` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sites`
--

INSERT INTO `sites` (`id`, `title`, `permissions`, `modules`) VALUES
(1, 'Home', ',1,', ''),
(2, 'View Competitions', ',2,', ',ViewCompetitions,'),
(4, 'View Competition', ',2,', ',ViewCompetition,'),
(5, 'View Disciplines', ',3,', ',ViewDisciplines,'),
(6, 'View Discipline', ',3,', ',ViewDiscipline,'),
(7, 'View Startlist', ',3,', ',ViewStartList,'),
(8, 'View Scoreboard', ',3,', ',ViewScoreboard,'),
(9, 'Supervise Discipline', ',4,', ',SuperviseDiscipline,'),
(10, 'Add Member', ',5,', ',AddMember,'),
(11, 'Manage Members', ',6,', ',ManageMembers,'),
(12, 'Judge Discipline', ',7,', ',JudgeDiscipline,'),
(13, 'Login', ',16,,17,', ',login,'),
(14, 'Notes', ',17,', ',makeNotes,'),
(15, 'Cheatsheet', ',1,', ',cheatsheet,');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `environmentId` int NOT NULL,
  `id` int NOT NULL,
  `firstName` varchar(300) NOT NULL,
  `lastName` varchar(300) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `identifier` varchar(500) NOT NULL,
  `password` varchar(500) NOT NULL,
  `permissions` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`environmentId`, `id`, `firstName`, `lastName`, `identifier`, `password`, `permissions`) VALUES
(1, 15, 'Felix', 'Hirt', 'felixhirt', '7e331abb5498b2ae8e9725924f5a4e58', ',5,,12,,7,,17,,6,,14,,11,,4,,15,,2,,3,,1,,8,'),
(1, 24, '', 'Event Manager1', 'EV1', 'b2c07736f033ebbc0b4b79ba53bc2f0f', ',17,,6,,4,,2,,3,,1,,8,'),
(1, 26, '', 'Greg', 'GROE', 'cc1ae498f2e155bdd5c91f4c7c8e005d', ',5,,12,,17,,6,,14,,4,,15,,2,,3,,1,,8,'),
(1, 33, '', 'Streamer', 'STREAMER', '7544247353a067a7e1f0f62eb97f55d9', ',18,,17,,2,,3,,1,'),
(1, 34, '', 'Trish', 'TRISH', '72810f8f1fb1fb1b1709b5612b6bc0df', ',5,,12,,17,,6,,14,,4,,15,,2,,3,,1,,8,'),
(1, 47, 'Judge', 'Execution', 'Judge2', 'd9f5e2c326a2a62cb38184f5be86cc41', ',7,,17,,2,,3,,1,'),
(1, 49, '', '@jakobliabo', 'jakobliabo', '3d10e73da021e4daf3641f3e891fa0af', ',9,,17,,2,,3,,1,'),
(1, 50, '', '@filip_cederholm', 'filip_cederholm', 'd98c5fff7988001dccf8a8f5daecdb7b', ',9,,17,,2,,3,,1,'),
(1, 51, '', '@emil.havstein', '@emil.havstein', '0880e28a80d4d9501bbe9c959ce5d4f7', ',9,,17,,2,,3,,1,'),
(1, 52, '', '@ozzyhuff.pk', '@ozzyhuff.pk', 'af66e652131ac34b1c3cfb1e1a951642', ',9,,17,,2,,3,,1,'),
(1, 53, '', '@joakimflips', '@joakimflips', '2ab36726e1f21124e32a6783f950da91', ',9,,17,,2,,3,,1,'),
(1, 54, '', '@flips_by_tim', '@flips_by_tim', '7baa120022bac66da7f3fecd5410a467', ',9,,17,,2,,3,,1,'),
(1, 56, '', '@sebastian.fuglestad_', '@sebastian.fuglestad_', 'd85ebaadd29a9dd442cc6950cae8f9bc', ',9,,17,,2,,3,,1,'),
(1, 57, '', '@andreas.vince', '@andreas.vince', '8419301fb874edc811958a5eb69f55db', ',9,,17,,2,,3,,1,'),
(1, 58, '', '@melvinthoren', '@melvinthoren', '16b608ccd1be78ba5ae1908da3143e97', ',9,,17,,2,,3,,1,'),
(1, 60, '', '@thor_flipz', '@thor_flipz', 'c561db48bab0eea79eb5a36d7a864f8f', ',9,,17,,2,,3,,1,'),
(1, 61, '', '@delenwalles', '@delenwalles', 'e814182f666882216eaca484e1345b63', ',9,,17,,2,,3,,1,'),
(1, 62, '', '@noahtufte', '@noahtufte', '090ff83d2682e91fa3cded4190d03eab', ',9,,17,,2,,3,,1,'),
(1, 63, '', '@thomaskvale', '@thomaskvale', 'c61178fb9d6cc7034e9853b7c5b403be', ',9,,17,,2,,3,,1,'),
(1, 64, 'Danèle', 'Kammacher', 'Kammacher1', '47b516756d260de2afcace97ade8936f', ',9,,17,,2,,3,,1,'),
(1, 65, '', '@ndflips', '@ndflips', 'a9219959028af441f9d2a2469e4c03fc', ',9,,17,,2,,3,,1,'),
(1, 66, '', '@brin._.flipz', '@brin._.flipz', '93176ab63c004b348eb2bf0ef2ca086c', ',9,,17,,2,,3,,1,'),
(1, 67, '', '@willi_122', '@willi_122', '011cc9253101976a2b52d4c4adb39dd3', ',9,,17,,2,,3,,1,'),
(1, 68, '', '@vinzenz.b._', '@vinzenz.b._', '11e24be938389a81719ffea2f98ee75d', ',9,,17,,2,,3,,1,'),
(1, 69, '', '@larry_flipz', '@larry_flipz', 'b0fdab5edd937d9004590e568b6a7034', ',9,,17,,2,,3,,1,'),
(1, 70, '', '@jz.flipz', '@jz.flipz', '17201a1114a02c02d438f8bdca14c0eb', ',9,,17,,2,,3,,1,'),
(1, 71, '', '@timy_flipz', '@timy_flipz', 'e0c400455878e837777ffbee95f36209', ',9,,17,,2,,3,,1,'),
(1, 72, '', '@flipz_by_alexander', '@flipz_by_alexander', '37c91aa599e81fca0c2c47937dbd8d40', ',9,,17,,2,,3,,1,'),
(1, 74, '', '@cle.sends', '@cle.sends', '2d451df28b0472a1dd2d30b028ce061d', ',9,,17,,2,,3,,1,'),
(1, 75, '', '@david22_flipz', '@david22_flipz', '3fa04682649af17793a158e73b9062f5', ',9,,17,,2,,3,,1,'),
(1, 77, '', '@theo.moessinger', '@theo.moessinger', 'a43de3a1c1b68bad08a5f18443582b16', ',9,,17,,2,,3,,1,'),
(1, 78, '', '@jonas._.flips', '@jonas._.flips', '8e4c9581b671d48dfc228eaad2c34597', ',9,,17,,2,,3,,1,'),
(1, 79, '', '@dominikflips', '@dominikflips', 'f6ef074f5cb13464eb68a07b70888618', ',9,,17,,2,,3,,1,'),
(1, 80, '', '@magdalenaluethi', '@magdalenaluethi', '826197c851a836c8b93d1243f34f656c', ',9,,17,,2,,3,,1,'),
(1, 81, '', '@em_tilg', '@em_tilg', '2f2a875416b56d6f69675f0b7c08e683', ',9,,17,,2,,3,,1,'),
(1, 82, '', '@dxvid.flips', '@dxvid.flips', 'f50769853c2cd884e8bc0ca5e96b85e0', ',9,,17,,2,,3,,1,'),
(1, 83, '', '@flipz_by_noah', '@flipz_by_noah', '33de7f3e432656871af638e699dd24fb', ',9,,17,,2,,3,,1,'),
(1, 100, 'Judge 1', 'Difficulty', 'Judge1', '3ac5888551e425393f977662e9a00202', ',7,,17,,2,,3,,1,'),
(1, 101, 'Judge 3', 'Creativity', 'Judge3', '7f196eefd0d931285e50c255ef9ad840', ',7,,17,,2,,3,,1,'),
(1, 118, 'Levin', 'Simon', 'Levin Simon', '5e543256c480ac577d30f76f9120eb74', ',9,,17,,2,,3,,1,'),
(1, 130, 'Timo', 'Willi', 'Timo Willi', '5e543256c480ac577d30f76f9120eb74', ',9,,17,,2,,3,,1,'),
(1, 131, 'Manuel ', 'Lutz', 'Manuel Lutz', '3ac5888551e425393f977662e9a00202', ',9,,17,,2,,3,,1,'),
(1, 134, 'Robin', 'Steiner', 'Robin Steiner', '2aab5f483d0c458e72444e387e1aaeb7', ',9,,17,,2,,3,,1,'),
(1, 135, 'Vinzenz', 'Berchtold', 'Vinzenz Berchtold', '2aab5f483d0c458e72444e387e1aaeb7', ',9,,17,,2,,3,,1,'),
(1, 136, 'Luan', 'Stauffer', 'Luan Stauffer', '5e543256c480ac577d30f76f9120eb74', ',9,,17,,2,,3,,1,'),
(1, 137, 'Manuel ', 'Klarer', 'Manuel Klarer', '5e543256c480ac577d30f76f9120eb74', ',9,,17,,2,,3,,1,'),
(1, 138, 'Dominik', 'Burge', 'Dominik Burge', '5e543256c480ac577d30f76f9120eb74', ',9,,17,,2,,3,,1,'),
(1, 139, 'Lars', 'Hochuli', 'Lars Hochuli', '5e543256c480ac577d30f76f9120eb74', ',9,,17,,2,,3,,1,'),
(1, 140, 'Juri', 'Ifflaender', 'Juri Ifflaender', '5e543256c480ac577d30f76f9120eb74', ',9,,17,,2,,3,,1,'),
(1, 141, 'Timon', 'Hensch', 'Timon Hensch', '5e543256c480ac577d30f76f9120eb74', ',9,');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buttons`
--
ALTER TABLE `buttons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `competitions`
--
ALTER TABLE `competitions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `disciplines`
--
ALTER TABLE `disciplines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `executediscipline`
--
ALTER TABLE `executediscipline`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `judging`
--
ALTER TABLE `judging`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissionpresets`
--
ALTER TABLE `permissionpresets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scores`
--
ALTER TABLE `scores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sites`
--
ALTER TABLE `sites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buttons`
--
ALTER TABLE `buttons`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `competitions`
--
ALTER TABLE `competitions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `disciplines`
--
ALTER TABLE `disciplines`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `executediscipline`
--
ALTER TABLE `executediscipline`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `judging`
--
ALTER TABLE `judging`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `permissionpresets`
--
ALTER TABLE `permissionpresets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `scores`
--
ALTER TABLE `scores`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=521;

--
-- AUTO_INCREMENT for table `sites`
--
ALTER TABLE `sites`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
