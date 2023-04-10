-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 11, 2023 at 12:06 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project_news`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `topic_name` varchar(250) NOT NULL,
  `descripion` text NOT NULL,
  `image_banner` varchar(250) NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `view` varchar(50) NOT NULL,
  `keyword_seo` varchar(500) NOT NULL,
  `descripion_seo` varchar(500) NOT NULL,
  `url_articles_seo` varchar(350) NOT NULL,
  `update_at` datetime NOT NULL,
  `update_by` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `category_id`, `user_id`, `topic_name`, `descripion`, `image_banner`, `create_at`, `view`, `keyword_seo`, `descripion_seo`, `url_articles_seo`, `update_at`, `update_by`, `status`) VALUES
(88, 5, 37, 'asdasd', '<p>sadasdasdasdasafasf</p>\r\n', '1980284707.jpg', '2023-04-06 20:24:48', '0', 'afsafa', 'fasfasfasf', 'asdasd', '0000-00-00 00:00:00', 0, 1),
(89, 4, 37, 'asdasd', '<p>asdasdasd</p>\r\n', '1395515081.jpg', '2023-04-06 20:25:42', '0', 'asdasd', 'asdasdas', 'asdasd', '2006-04-23 22:25:42', 40, 0),
(90, 5, 40, 'tsesafad', '<p>asdasdsadasd</p>\r\n', '610137150.jpg', '2023-04-06 20:27:07', '0', 'asdasd', 'asdasda', 'tsesafad', '0000-00-00 00:00:00', 0, 1),
(92, 1, 37, 'asdsad', '<p>&nbsp;</p>\r\n\r\n<p>&lt;video src=&#39;uploads/videos/keshi-limbo-visualizer-easysave.net.mp4&#39; class=&quot;img-fluid&quot; controls width=&#39;320px&#39; height=&#39;320px&#39;&gt;&lt;/video&gt;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>asdasdas</p>\r\n', '1444426664.jpg', '2023-04-10 21:32:26', '0', 'werwe', 'rwerwer', 'asdsad', '2010-04-23 23:32:26', 37, 1),
(93, 7, 38, 'gshsghs', '<p>hsghsghsdgh</p>\r\n', '1593248501.png', '2023-04-10 22:03:58', '0', 'sghsh', 'sdghshs', 'gshsghs', '0000-00-00 00:00:00', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(350) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'คาสิโน'),
(2, 'หวย'),
(3, 'สล็อต'),
(4, 'กีฬา'),
(5, 'ทางเข้า'),
(6, 'สมัคร'),
(7, 'ลิงค์รับทรัพย์');

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `avthur_name` varchar(250) NOT NULL,
  `rating_score` varchar(250) NOT NULL,
  `title` varchar(250) NOT NULL,
  `comment` varchar(450) NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `image_articles`
--

CREATE TABLE `image_articles` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `podcasts`
--

CREATE TABLE `podcasts` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `podcasts`
--

INSERT INTO `podcasts` (`id`, `name`, `location`) VALUES
(1, 'Justine-Wizkid.mp3', 'uploads/podcasts/Justine-Wizkid.mp3'),
(2, 'Justine-Wizkid.mp3', 'uploads/podcasts/Justine-Wizkid.mp3');

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `keyword` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`id`, `name`, `keyword`) VALUES
(1, 'Admin', 'A'),
(2, 'Webmaster', 'B');

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE `tag` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `tag_url` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tag`
--

INSERT INTO `tag` (`id`, `name`, `tag_url`, `user_id`, `create_at`) VALUES
(13, 'tag 1', 'tag-1', 0, '2022-02-17 02:38:02'),
(14, 'tag 2', 'tag-2', 0, '2022-02-17 02:37:55'),
(15, 'tag 3', 'tag-3', 36, '2022-02-17 02:37:14'),
(16, 'tag 4', 'tag-4', 36, '2022-02-17 02:34:15'),
(17, 'tag 5', 'tag-5', 0, '2022-04-08 11:08:12'),
(18, 'asd', 'asd', 37, '2023-04-06 15:23:11');

-- --------------------------------------------------------

--
-- Table structure for table `tag_log`
--

CREATE TABLE `tag_log` (
  `id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  `articles_id` int(11) NOT NULL,
  `create_by` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tag_log`
--

INSERT INTO `tag_log` (`id`, `tag_id`, `articles_id`, `create_by`) VALUES
(250, 15, 80, 0),
(185, 16, 82, 0),
(244, 16, 71, 0),
(239, 15, 73, 0),
(204, 15, 74, 0),
(203, 14, 74, 0),
(241, 15, 79, 0),
(240, 14, 79, 0),
(248, 14, 78, 0),
(247, 17, 78, 0),
(249, 14, 80, 0),
(193, 15, 77, 0),
(192, 14, 77, 0),
(255, 13, 75, 0),
(254, 15, 75, 0),
(238, 13, 73, 0),
(237, 14, 72, 0),
(236, 15, 72, 0),
(243, 13, 71, 0),
(242, 14, 71, 0),
(184, 15, 82, 0),
(246, 17, 83, 0),
(245, 14, 83, 0),
(257, 14, 84, 0),
(256, 13, 84, 0),
(253, 15, 85, 0),
(252, 14, 85, 0),
(251, 16, 85, 0),
(265, 14, 15, 0),
(266, 16, 8, 0),
(267, 15, 14, 0),
(268, 16, 87, 40),
(269, 15, 88, 37),
(271, 17, 89, 0),
(272, 15, 89, 0),
(273, 16, 90, 40),
(274, 17, 90, 40),
(275, 16, 93, 38);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `role_id`, `firstname`, `lastname`, `username`, `password`, `image_path`) VALUES
(37, 1, 'admin', 'admin', 'admin', '6c31fc0f69bbf07cba275ff861d99123', '1217215459.jpg'),
(38, 2, 'mis', 'ter', 'mister', '6ad14ba9986e3615423dfca256d04e3f', '946555253.jpg'),
(39, 2, 'test1', 'test2', 'test3', 'cc03e747a6afbbcbf8be7668acfebee5', '289512030.jpg'),
(40, 2, 'test1', 'test11', 'test11', 'cc03e747a6afbbcbf8be7668acfebee5', '2144860921.png');

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `videos`
--

INSERT INTO `videos` (`id`, `name`, `location`) VALUES
(7, 'keshi-limbo-visualizer-easysave.net.mp4', 'uploads/videos/keshi-limbo-visualizer-easysave.net.mp4');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `image_articles`
--
ALTER TABLE `image_articles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `podcasts`
--
ALTER TABLE `podcasts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tag_log`
--
ALTER TABLE `tag_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `image_articles`
--
ALTER TABLE `image_articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `podcasts`
--
ALTER TABLE `podcasts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `role_user`
--
ALTER TABLE `role_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tag`
--
ALTER TABLE `tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tag_log`
--
ALTER TABLE `tag_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=276;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
