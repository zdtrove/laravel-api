-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 28, 2019 at 10:59 AM
-- Server version: 5.7.21
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `wantedly`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `uuid` varchar(45) DEFAULT NULL,
  `password` varchar(45) DEFAULT NULL,
  `image` varchar(150) DEFAULT NULL,
  `is_manager` int(11) NOT NULL DEFAULT '0',
  `is_master` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `updated_by` varchar(45) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `uuid`, `password`, `image`, `is_manager`, `is_master`, `status`, `created_at`, `updated_at`, `created_by`, `updated_by`, `deleted_at`) VALUES
(1, 'Administrator', 'admin@icd-vn.com', NULL, '$2y$10$DeXgD5kvEd6xPftXVK1URu4bQMerlBGBYyIOB9', NULL, 0, 0, 1, '2019-11-12 17:55:03', '2019-11-12 17:55:03', NULL, 'system', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin_roles`
--

CREATE TABLE `admin_roles` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `role` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `awards`
--

CREATE TABLE `awards` (
  `id` int(11) NOT NULL,
  `received_in` year(4) DEFAULT NULL,
  `title` varchar(45) DEFAULT NULL,
  `profile_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `id` int(11) NOT NULL,
  `title` varchar(45) DEFAULT NULL,
  `issued_in` date DEFAULT NULL,
  `profile_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `clubs_volunteerings`
--

CREATE TABLE `clubs_volunteerings` (
  `id` int(11) NOT NULL,
  `title` varchar(45) DEFAULT NULL,
  `link` varchar(100) DEFAULT NULL,
  `term_from` date DEFAULT NULL,
  `term_to` date DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `profile_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `title` varchar(45) DEFAULT NULL,
  `count` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `title` varchar(45) DEFAULT NULL,
  `link` varchar(100) DEFAULT NULL,
  `term_from` date DEFAULT NULL,
  `term_to` date DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `profile_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `educations`
--

CREATE TABLE `educations` (
  `id` int(11) NOT NULL,
  `degree_major` varchar(45) DEFAULT NULL,
  `graduation` date DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `profile_id` int(11) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `experiences`
--

CREATE TABLE `experiences` (
  `id` int(11) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `from` datetime DEFAULT NULL,
  `to` datetime DEFAULT NULL,
  `is_working` int(11) DEFAULT NULL,
  `intership` int(11) DEFAULT NULL,
  `profile_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `friendships`
--

CREATE TABLE `friendships` (
  `requester_id` int(11) NOT NULL,
  `addressee_id` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL COMMENT '0: pedding; 1: Accepted; 2: Declined; 3: Blocked',
  `specifier_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `group` varchar(45) DEFAULT NULL,
  `count` int(11) DEFAULT NULL,
  `description` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int(11) NOT NULL,
  `lang_code` char(20) NOT NULL,
  `level` int(11) NOT NULL COMMENT '1: conversational; 2: professional; 3: native',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_10_29_154208_create_admin_roles_table', 0),
(2, '2019_10_29_154208_create_admins_table', 0),
(3, '2019_10_29_154208_create_awards_table', 0),
(4, '2019_10_29_154208_create_certificates_table', 0),
(5, '2019_10_29_154208_create_clubs_volunteerings_table', 0),
(6, '2019_10_29_154208_create_companies_table', 0),
(7, '2019_10_29_154208_create_courses_table', 0),
(8, '2019_10_29_154208_create_educations_table', 0),
(9, '2019_10_29_154208_create_experiences_table', 0),
(10, '2019_10_29_154208_create_friendships_table', 0),
(11, '2019_10_29_154208_create_groups_table', 0),
(12, '2019_10_29_154208_create_languages_table', 0),
(13, '2019_10_29_154208_create_occupations_table', 0),
(14, '2019_10_29_154208_create_portfolios_table', 0),
(15, '2019_10_29_154208_create_profile_groups_table', 0),
(16, '2019_10_29_154208_create_profile_languages_table', 0),
(17, '2019_10_29_154208_create_profile_skills_table', 0),
(18, '2019_10_29_154208_create_profiles_table', 0),
(19, '2019_10_29_154208_create_projects_table', 0),
(20, '2019_10_29_154208_create_publications_table', 0),
(21, '2019_10_29_154208_create_schools_table', 0),
(22, '2019_10_29_154208_create_skills_table', 0),
(23, '2019_10_29_154208_create_visibilities_table', 0),
(24, '2019_10_29_154209_add_foreign_keys_to_admin_roles_table', 0),
(25, '2019_10_29_154209_add_foreign_keys_to_awards_table', 0),
(26, '2019_10_29_154209_add_foreign_keys_to_certificates_table', 0),
(27, '2019_10_29_154209_add_foreign_keys_to_clubs_volunteerings_table', 0),
(28, '2019_10_29_154209_add_foreign_keys_to_courses_table', 0),
(29, '2019_10_29_154209_add_foreign_keys_to_educations_table', 0),
(30, '2019_10_29_154209_add_foreign_keys_to_experiences_table', 0),
(31, '2019_10_29_154209_add_foreign_keys_to_friendships_table', 0),
(32, '2019_10_29_154209_add_foreign_keys_to_portfolios_table', 0),
(33, '2019_10_29_154209_add_foreign_keys_to_profile_groups_table', 0),
(34, '2019_10_29_154209_add_foreign_keys_to_profile_languages_table', 0),
(35, '2019_10_29_154209_add_foreign_keys_to_profile_skills_table', 0),
(36, '2019_10_29_154209_add_foreign_keys_to_profiles_table', 0),
(37, '2019_10_29_154209_add_foreign_keys_to_projects_table', 0),
(38, '2019_10_29_154209_add_foreign_keys_to_publications_table', 0),
(39, '2019_10_29_154209_add_foreign_keys_to_visibilities_table', 0);

-- --------------------------------------------------------

--
-- Table structure for table `occupations`
--

CREATE TABLE `occupations` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `count` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `portfolios`
--

CREATE TABLE `portfolios` (
  `id` int(11) NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  `youtube_link` varchar(100) DEFAULT NULL,
  `title` varchar(45) NOT NULL,
  `link` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `made_in` date DEFAULT NULL,
  `profile_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portfolios`
--

INSERT INTO `portfolios` (`id`, `image`, `youtube_link`, `title`, `link`, `description`, `made_in`, `profile_id`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, 'title', 'http://link.local', NULL, NULL, 1, '2019-11-12 17:55:03', '2019-11-12 17:55:03'),
(2, NULL, NULL, 'title', 'http://link.local', NULL, NULL, 1, '2019-11-12 17:55:03', '2019-11-12 17:55:03'),
(3, NULL, NULL, 'title', 'http://link.local', NULL, NULL, 1, '2019-11-12 17:55:03', '2019-11-12 17:55:03'),
(4, NULL, NULL, 'title', 'http://link.local', NULL, NULL, 1, '2019-11-12 17:55:03', '2019-11-12 17:55:03'),
(5, NULL, NULL, 'title', 'http://link.local', NULL, NULL, 1, '2019-11-12 17:55:03', '2019-11-12 17:55:03'),
(6, NULL, NULL, 'title', 'http://link.local', NULL, NULL, 1, '2019-11-12 17:55:03', '2019-11-12 17:55:03'),
(7, NULL, NULL, 'title', 'http://link.local', NULL, NULL, 1, '2019-11-12 17:55:03', '2019-11-12 17:55:03'),
(8, NULL, NULL, 'title', 'http://link.local', NULL, NULL, 1, '2019-11-12 17:55:03', '2019-11-12 17:55:03'),
(9, NULL, NULL, 'title', 'http://link.local', NULL, NULL, 1, '2019-11-12 17:55:03', '2019-11-12 17:55:03');

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` int(11) NOT NULL,
  `full_name` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `password` varchar(140) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `sex` int(11) DEFAULT NULL,
  `birthday` datetime DEFAULT NULL,
  `address` varchar(128) DEFAULT NULL,
  `avata` varchar(45) DEFAULT NULL,
  `cover` varchar(45) DEFAULT NULL,
  `facebook` varchar(45) DEFAULT NULL,
  `google` varchar(45) DEFAULT NULL,
  `twitter` varchar(45) DEFAULT NULL,
  `uuid` varchar(150) DEFAULT NULL,
  `link` varchar(45) DEFAULT NULL,
  `note` varchar(45) DEFAULT NULL,
  `introduction` varchar(255) DEFAULT NULL,
  `ambition` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `token_confirm` varchar(50) NOT NULL,
  `occupation_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `updated_by` varchar(45) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `full_name`, `email`, `password`, `phone`, `sex`, `birthday`, `address`, `avata`, `cover`, `facebook`, `google`, `twitter`, `uuid`, `link`, `note`, `introduction`, `ambition`, `status`, `token_confirm`, `occupation_id`, `created_at`, `updated_at`, `created_by`, `updated_by`, `deleted_at`) VALUES
(1, 'Profile test', 'tuanlh@icd-vn.com', '$2y$10$6fNrw51/4q2iqZWi92O3JO0BwgMJ8JmLX1/GN.v05O5a2je0eZzcm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'instroduction', 'ambition', 1, '', NULL, '2019-11-12 17:55:03', '2019-11-12 17:55:03', NULL, 'system', NULL),
(2, NULL, 'tuanlh1@icd-vn.com', '$2y$10$ly.taKJccdVf6Us49n.efumbkb2OHMTIsGSenW85EeSJn.imCqtpC', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '', NULL, '2019-11-26 15:37:10', '2019-11-27 15:25:48', 'system', 'system', NULL),
(3, NULL, 'antnh@icd-vn.com', '$2y$10$/0tlzG.47yQZ2BXo/GNrgOl/Ug7YwUPgAio1hDD1unOUuJgEluQW2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '4Bf3AIzw10bBKUMjaGKk', NULL, '2019-11-26 15:56:50', '2019-11-26 15:56:50', 'system', 'system', NULL),
(4, NULL, 'antnh2@icd-vn.com', '$2y$10$I2QjJKuCOzV4I01ySOuknOov2MxwySgiQLoYIQK0UuWCaF6RurHu2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'Y1W6TmEpcPnLTm4hAglE', NULL, '2019-11-27 15:10:40', '2019-11-27 15:10:40', 'system', 'system', NULL),
(5, NULL, 'user01@gmail.com', '$2y$10$gYUXXiw/Qx/otHY.7o.Q.OXNKxnduEHRPrOnlKU4FUSt8QO8P86jy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'Tm9SJCHmJRm734mwhykq', NULL, '2019-11-27 15:17:46', '2019-11-27 15:17:46', 'system', 'system', NULL),
(6, NULL, 'antnh3@icd-vn.com', '$2y$10$SyAzbOQElnC.0tGdEjyTGuQPpNYDObkeY9DQKdokImLXR16Rf3Nfm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'zfY0FaHi76QDpnLz0gTr', NULL, '2019-11-27 15:40:52', '2019-11-27 15:40:52', 'system', 'system', NULL),
(7, NULL, 'user02@gmail.com', '$2y$10$g/Z08g0F5Ga3U4EoOHK74OrNv3Npy2YU4yJUO/ZzxMeD6pEvmTKfe', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'IBhHo11bOsrUL2nS1qPm', NULL, '2019-11-27 15:42:21', '2019-11-27 15:42:21', 'system', 'system', NULL),
(8, NULL, 'user05@gmail.com', '$2y$10$qaF0ZG8AcOg08sI4ZszxqOGIEA.ct4SnB0MpueOBpg6h5kGUY/JaS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'b9sQYtt9R6JyTmbfC5oH', NULL, '2019-11-27 16:08:25', '2019-11-27 16:08:25', 'system', 'system', NULL),
(9, NULL, 'user06@gmail.com', '$2y$10$oEpRNRiBLnCh8bomrXM/u.mhhVObj4uReuwC1MMu.2tM0wZtl1xVS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'i99zpHi3xZNz7jbRuHHB', NULL, '2019-11-27 16:10:14', '2019-11-27 16:10:14', 'system', 'system', NULL),
(10, NULL, 'user07@gmail.com', '$2y$10$sETJF1iApEk8kxh1EOVwzeFFwEPQKD8TGZY65SvhPStjKqza5VIA2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'JWolxnJYiKO7h0wR9i8M', NULL, '2019-11-27 16:17:25', '2019-11-27 16:17:25', 'system', 'system', NULL),
(11, NULL, 'user08@gmail.com', '$2y$10$9TwAD5RLzQlFBSunBaKtY.cLUidygdBy9SKBRWJiqH/uVHvvWG32u', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'SW7PrMhMY33FroaS5AOL', NULL, '2019-11-27 16:18:08', '2019-11-27 16:18:08', 'system', 'system', NULL),
(13, NULL, 'nnt11111991nhmt@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2328839450561446', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'LDFP95MEoKC0pGNYK4Uh', NULL, '2019-11-27 18:10:10', '2019-11-27 18:10:10', 'system', 'system', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `profile_groups`
--

CREATE TABLE `profile_groups` (
  `id` int(11) NOT NULL,
  `group_id` int(11) DEFAULT NULL COMMENT 'only_me, friend, recruite, public	',
  `profile_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `profile_languages`
--

CREATE TABLE `profile_languages` (
  `profile_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `profile_skills`
--

CREATE TABLE `profile_skills` (
  `id` int(11) NOT NULL,
  `profile_id` int(11) DEFAULT NULL,
  `skill_id` int(11) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `title` varchar(45) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `term_from` datetime DEFAULT NULL,
  `term_to` datetime DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `project_member` int(11) DEFAULT NULL,
  `profile_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `publications`
--

CREATE TABLE `publications` (
  `id` int(11) NOT NULL,
  `written_in` date DEFAULT NULL,
  `title` varchar(45) DEFAULT NULL,
  `link` varchar(100) DEFAULT NULL,
  `profile_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `schools`
--

CREATE TABLE `schools` (
  `id` int(11) NOT NULL,
  `title` varchar(45) DEFAULT NULL,
  `count` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` int(11) NOT NULL,
  `title` varchar(45) DEFAULT NULL,
  `count` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `visibilities`
--

CREATE TABLE `visibilities` (
  `id` int(11) NOT NULL,
  `profile_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `object_id` int(11) DEFAULT NULL,
  `object_type` char(45) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `visibilities`
--

INSERT INTO `visibilities` (`id`, `profile_id`, `group_id`, `object_id`, `object_type`, `created_at`, `updated_at`) VALUES
(1, 1, 0, 1, 'skill', NULL, NULL),
(2, 1, 0, 2, 'skill', NULL, NULL),
(3, 1, 0, 3, 'skill', NULL, NULL),
(4, 1, 0, 4, 'skill', NULL, NULL),
(5, 1, 0, 5, 'skill', NULL, NULL),
(6, 1, 0, 6, 'skill', NULL, NULL),
(7, 1, 0, 7, 'skill', NULL, NULL),
(8, 1, 0, 8, 'skill', NULL, NULL),
(9, 1, 0, 9, 'skill', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_roles`
--
ALTER TABLE `admin_roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_admin_roles_admins1_idx` (`admin_id`);

--
-- Indexes for table `awards`
--
ALTER TABLE `awards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_awards_profiles1_idx` (`profile_id`);

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_certificates_profiles1_idx` (`profile_id`);

--
-- Indexes for table `clubs_volunteerings`
--
ALTER TABLE `clubs_volunteerings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_clubs_volunteerings_profiles1_idx` (`profile_id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_courses_profiles1_idx` (`profile_id`);

--
-- Indexes for table `educations`
--
ALTER TABLE `educations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_educations_profiles1_idx` (`profile_id`),
  ADD KEY `fk_educations_schools1_idx` (`school_id`);

--
-- Indexes for table `experiences`
--
ALTER TABLE `experiences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_experiences_companies1_idx` (`company_id`),
  ADD KEY `fk_experiences_profiles1_idx` (`profile_id`);

--
-- Indexes for table `friendships`
--
ALTER TABLE `friendships`
  ADD KEY `fk_friendships_profiles1_idx` (`requester_id`),
  ADD KEY `fk_friendships_profiles2_idx` (`addressee_id`),
  ADD KEY `fk_friendships_profiles3_idx` (`specifier_id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `occupations`
--
ALTER TABLE `occupations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `portfolios`
--
ALTER TABLE `portfolios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_portfolios_profiles1_idx` (`profile_id`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_profiles_occupations1_idx` (`occupation_id`);

--
-- Indexes for table `profile_groups`
--
ALTER TABLE `profile_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_profile_groups_groups1_idx` (`group_id`),
  ADD KEY `fk_profile_groups_profiles1_idx` (`profile_id`);

--
-- Indexes for table `profile_languages`
--
ALTER TABLE `profile_languages`
  ADD PRIMARY KEY (`profile_id`,`language_id`),
  ADD KEY `fk_profile_languages_languages1_idx` (`language_id`);

--
-- Indexes for table `profile_skills`
--
ALTER TABLE `profile_skills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_profile_skills_profiles1_idx` (`profile_id`),
  ADD KEY `fk_profile_skills_skills1_idx` (`skill_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_projects_profiles1_idx` (`profile_id`);

--
-- Indexes for table `publications`
--
ALTER TABLE `publications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_publications_profiles1_idx` (`profile_id`);

--
-- Indexes for table `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visibilities`
--
ALTER TABLE `visibilities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_visibilities_profiles1_idx` (`profile_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_roles`
--
ALTER TABLE `admin_roles`
  ADD CONSTRAINT `fk_admin_roles_admins1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `awards`
--
ALTER TABLE `awards`
  ADD CONSTRAINT `fk_awards_profiles1` FOREIGN KEY (`profile_id`) REFERENCES `profiles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `certificates`
--
ALTER TABLE `certificates`
  ADD CONSTRAINT `fk_certificates_profiles1` FOREIGN KEY (`profile_id`) REFERENCES `profiles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `clubs_volunteerings`
--
ALTER TABLE `clubs_volunteerings`
  ADD CONSTRAINT `fk_clubs_volunteerings_profiles1` FOREIGN KEY (`profile_id`) REFERENCES `profiles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `fk_courses_profiles1` FOREIGN KEY (`profile_id`) REFERENCES `profiles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `educations`
--
ALTER TABLE `educations`
  ADD CONSTRAINT `fk_educations_profiles1` FOREIGN KEY (`profile_id`) REFERENCES `profiles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_educations_schools1` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `experiences`
--
ALTER TABLE `experiences`
  ADD CONSTRAINT `fk_experiences_companies1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_experiences_profiles1` FOREIGN KEY (`profile_id`) REFERENCES `profiles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `friendships`
--
ALTER TABLE `friendships`
  ADD CONSTRAINT `fk_friendships_profiles1` FOREIGN KEY (`requester_id`) REFERENCES `profiles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_friendships_profiles2` FOREIGN KEY (`addressee_id`) REFERENCES `profiles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_friendships_profiles3` FOREIGN KEY (`specifier_id`) REFERENCES `profiles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `portfolios`
--
ALTER TABLE `portfolios`
  ADD CONSTRAINT `fk_portfolios_profiles1` FOREIGN KEY (`profile_id`) REFERENCES `profiles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `fk_profiles_occupations1` FOREIGN KEY (`occupation_id`) REFERENCES `occupations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `profile_groups`
--
ALTER TABLE `profile_groups`
  ADD CONSTRAINT `fk_profile_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_profile_groups_profiles1` FOREIGN KEY (`profile_id`) REFERENCES `profiles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `profile_languages`
--
ALTER TABLE `profile_languages`
  ADD CONSTRAINT `fk_profile_languages_languages1` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_profile_languages_profiles1` FOREIGN KEY (`profile_id`) REFERENCES `profiles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `profile_skills`
--
ALTER TABLE `profile_skills`
  ADD CONSTRAINT `fk_profile_skills_profiles1` FOREIGN KEY (`profile_id`) REFERENCES `profiles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_profile_skills_skills1` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `fk_projects_profiles1` FOREIGN KEY (`profile_id`) REFERENCES `profiles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `publications`
--
ALTER TABLE `publications`
  ADD CONSTRAINT `fk_publications_profiles1` FOREIGN KEY (`profile_id`) REFERENCES `profiles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `visibilities`
--
ALTER TABLE `visibilities`
  ADD CONSTRAINT `fk_visibilities_profiles1` FOREIGN KEY (`profile_id`) REFERENCES `profiles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
