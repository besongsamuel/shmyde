-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 14, 2016 at 01:23 AM
-- Server version: 10.1.9-MariaDB
-- PHP Version: 5.5.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shmyde`
--

-- --------------------------------------------------------

--
-- Table structure for table `auth_sessions`
--

CREATE TABLE `auth_sessions` (
  `id` varchar(40) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `login_time` datetime DEFAULT NULL,
  `modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` varchar(60) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `auth_sessions`
--

INSERT INTO `auth_sessions` (`id`, `user_id`, `login_time`, `modified_at`, `ip_address`, `user_agent`) VALUES
('2435ae8cd905e3f625699639148b9c24ec088938', 3576232344, '2016-01-07 17:17:31', '2016-01-07 16:17:31', '::1', 'Chrome 46.0.2490.80 on Mac OS X'),
('abb6def416e145c8bf31d3fe2c1c35d457d9de5a', 3576232344, '2016-01-07 17:18:12', '2016-01-07 16:18:13', '::1', 'Chrome 46.0.2490.80 on Mac OS X'),
('1794fdcd6adbe560044bb404a52b27338d9f7371', 3576232344, '2016-01-07 17:18:57', '2016-01-07 16:18:57', '::1', 'Chrome 46.0.2490.80 on Mac OS X'),
('52cb23eba031fe426896dfb4be723aac96b1be32', 3576232344, '2016-01-07 17:37:47', '2016-01-07 16:37:47', '::1', 'Chrome 46.0.2490.80 on Mac OS X');

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `data` blob NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `denied_access`
--

CREATE TABLE `denied_access` (
  `ai` int(10) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `time` datetime NOT NULL,
  `reason_code` tinyint(2) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ips_on_hold`
--

CREATE TABLE `ips_on_hold` (
  `ai` int(10) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `time` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `login_errors`
--

CREATE TABLE `login_errors` (
  `ai` int(10) UNSIGNED NOT NULL,
  `username_or_email` varchar(255) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `time` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `shmyde_design_main_menu`
--

CREATE TABLE `shmyde_design_main_menu` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `shmyde_design_main_menu`
--

INSERT INTO `shmyde_design_main_menu` (`id`, `name`) VALUES
(1, 'FABRIC'),
(2, 'STYLE'),
(3, 'COLOR CONTRAST'),
(4, 'MEASURMENTS');

-- --------------------------------------------------------

--
-- Table structure for table `shmyde_design_option`
--

CREATE TABLE `shmyde_design_option` (
  `id` int(11) NOT NULL,
  `shmyde_design_sub_menu_id` int(11) DEFAULT NULL COMMENT 'This represents the specific design option to which this value is linked',
  `type` int(11) DEFAULT NULL COMMENT 'This represents an int value that determines what type of value this is. e.g. visual, checkbox etc. ',
  `name` varchar(45) DEFAULT NULL COMMENT 'This represents an optional name associated with this value. ',
  `price` decimal(10,0) DEFAULT '0' COMMENT 'This is an additional price required to add this attribute to a design. ',
  `description` longtext COMMENT 'A short description of the attribute to be displayed on the application. ',
  `is_default` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `shmyde_design_option`
--

INSERT INTO `shmyde_design_option` (`id`, `shmyde_design_sub_menu_id`, `type`, `name`, `price`, `description`, `is_default`) VALUES
(1, 1, 0, 'LongSleeve', '0', 'This is the long sleeve of the selected shirt. ', 0),
(2, 1, 0, 'ShortSleeve', '0', 'This represents a short sleeve option. ', 0),
(3, 2, 0, 'Coller01', '0', 'Simple Collar', 0),
(4, 2, 0, 'Coller02', '0', 'Complex Collar', 0),
(6, 2, 0, 'shiny_collar', '0', 'no description', 1),
(7, 3, 1, 'vertical_colors', '0', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `shmyde_design_sub_menu`
--

CREATE TABLE `shmyde_design_sub_menu` (
  `id` int(11) NOT NULL,
  `shmyde_design_main_menu_id` int(11) DEFAULT NULL,
  `shmyde_product_id` int(11) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `type` int(11) DEFAULT NULL COMMENT 'This represents the type of this specific option. Generally there will be two types. 0 for visual type with images and 1 for measurements. '
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `shmyde_design_sub_menu`
--

INSERT INTO `shmyde_design_sub_menu` (`id`, `shmyde_design_main_menu_id`, `shmyde_product_id`, `name`, `type`) VALUES
(1, 2, 1, 'Sleeve', 0),
(2, 2, 1, 'Collar', 0),
(3, 1, 1, 'Pattern', 0),
(4, 1, 1, 'Solid', 0);

-- --------------------------------------------------------

--
-- Table structure for table `shmyde_images`
--

CREATE TABLE `shmyde_images` (
  `id` int(11) NOT NULL,
  `name` longtext,
  `caption` text NOT NULL,
  `shmyde_design_options_id` int(11) DEFAULT NULL,
  `z_index` int(11) DEFAULT NULL,
  `values` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `shmyde_images`
--

INSERT INTO `shmyde_images` (`id`, `name`, `caption`, `shmyde_design_options_id`, `z_index`, `values`) VALUES
(1, 'LongSleeve_1_2_1_option_image.png', '', 1, 4, NULL),
(2, 'ShortSleeve_1_2_1_option_image.png', '', 2, 4, NULL),
(3, 'Coller01_1_2_2_option_image.png', '', 3, 2, NULL),
(4, 'Coller02_1_2_2_option_image.png', '', 4, 4, NULL),
(5, 'style_1_2_2_option_image.png', 'style_1_2_2_caption_image.png', 6, 0, NULL),
(6, 'fabric_1_1_3_option_image.png', 'fabric_1_1_3_caption_image.png', 7, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `shmyde_measurment`
--

CREATE TABLE `shmyde_measurment` (
  `id` int(11) NOT NULL,
  `shmyde_product_id` int(11) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `shmyde_product`
--

CREATE TABLE `shmyde_product` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL COMMENT 'The name of the product. e.g. shirt, suit etc',
  `url_name` varchar(32) NOT NULL,
  `target` int(11) DEFAULT NULL COMMENT 'This represents the target of this product. ',
  `base_price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `shmyde_product`
--

INSERT INTO `shmyde_product` (`id`, `name`, `url_name`, `target`, `base_price`) VALUES
(1, 'Shirts', 'shirt', 0, 10000);

-- --------------------------------------------------------

--
-- Table structure for table `shmyde_product_image`
--

CREATE TABLE `shmyde_product_image` (
  `id` int(11) NOT NULL,
  `shmyde_product_id` int(11) DEFAULT NULL,
  `name` longtext,
  `view_type` int(11) NOT NULL COMMENT '0 for back and 1 for front',
  `z_index` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `shmyde_product_image`
--

INSERT INTO `shmyde_product_image` (`id`, `shmyde_product_id`, `name`, `view_type`, `z_index`) VALUES
(1, 1, 'Shirts_front_view.png', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `shmyde_template_measurment`
--

CREATE TABLE `shmyde_template_measurment` (
  `id` int(11) NOT NULL,
  `shmyde_template_id` int(11) DEFAULT NULL,
  `shmyde_measurment_id` int(11) DEFAULT NULL,
  `value` decimal(10,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `shmyde_user`
--

CREATE TABLE `shmyde_user` (
  `id` int(11) NOT NULL,
  `email` varchar(45) DEFAULT NULL,
  `firstname` varchar(45) DEFAULT NULL,
  `lastname` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `shyme_template`
--

CREATE TABLE `shyme_template` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `shmyde_user_id` int(11) DEFAULT NULL,
  `shmyde_product_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `username_or_email_on_hold`
--

CREATE TABLE `username_or_email_on_hold` (
  `ai` int(10) UNSIGNED NOT NULL,
  `username_or_email` varchar(255) NOT NULL,
  `time` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `username` varchar(12) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `passwd` varchar(60) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `auth_level` tinyint(2) UNSIGNED NOT NULL,
  `banned` enum('0','1') NOT NULL DEFAULT '0',
  `passwd_recovery_code` varchar(60) DEFAULT NULL,
  `passwd_recovery_date` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `passwd`, `last_login`, `auth_level`, `banned`, `passwd_recovery_code`, `passwd_recovery_date`, `created_at`, `modified_at`) VALUES
(3576232344, 'skunkbot', 'skunkbot@example.com', '$2y$11$zm0MweRiDhcFWYsRcbgzg.jfdtRdHzqlBvyDw1xHCquVzh6TgYf1W', '2016-01-07 17:38:56', 1, '0', NULL, NULL, '2016-01-07 17:10:04', '2016-01-07 16:38:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auth_sessions`
--
ALTER TABLE `auth_sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ci_sessions_timestamp` (`timestamp`);

--
-- Indexes for table `denied_access`
--
ALTER TABLE `denied_access`
  ADD PRIMARY KEY (`ai`);

--
-- Indexes for table `ips_on_hold`
--
ALTER TABLE `ips_on_hold`
  ADD PRIMARY KEY (`ai`);

--
-- Indexes for table `login_errors`
--
ALTER TABLE `login_errors`
  ADD PRIMARY KEY (`ai`);

--
-- Indexes for table `shmyde_design_main_menu`
--
ALTER TABLE `shmyde_design_main_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shmyde_design_option`
--
ALTER TABLE `shmyde_design_option`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shmyde_design_option_specifics_fk_idx` (`shmyde_design_sub_menu_id`);

--
-- Indexes for table `shmyde_design_sub_menu`
--
ALTER TABLE `shmyde_design_sub_menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shmyde_design_option_fk_idx` (`shmyde_design_main_menu_id`),
  ADD KEY `shmyde_product_id_idx` (`shmyde_product_id`);

--
-- Indexes for table `shmyde_images`
--
ALTER TABLE `shmyde_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shmyde_design_options_fk_idx` (`shmyde_design_options_id`);

--
-- Indexes for table `shmyde_measurment`
--
ALTER TABLE `shmyde_measurment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shmyde_product_fk_idx` (`shmyde_product_id`);

--
-- Indexes for table `shmyde_product`
--
ALTER TABLE `shmyde_product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shmyde_product_image`
--
ALTER TABLE `shmyde_product_image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shmyde_product_fk_idx` (`shmyde_product_id`);

--
-- Indexes for table `shmyde_template_measurment`
--
ALTER TABLE `shmyde_template_measurment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shmyde_template_fk_idx` (`shmyde_template_id`),
  ADD KEY `shmyde_measurment_fk_idx` (`shmyde_measurment_id`);

--
-- Indexes for table `shmyde_user`
--
ALTER TABLE `shmyde_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shyme_template`
--
ALTER TABLE `shyme_template`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shmyde_measurment_product_fk_idx` (`shmyde_product_id`);

--
-- Indexes for table `username_or_email_on_hold`
--
ALTER TABLE `username_or_email_on_hold`
  ADD PRIMARY KEY (`ai`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `denied_access`
--
ALTER TABLE `denied_access`
  MODIFY `ai` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ips_on_hold`
--
ALTER TABLE `ips_on_hold`
  MODIFY `ai` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `login_errors`
--
ALTER TABLE `login_errors`
  MODIFY `ai` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `shmyde_design_main_menu`
--
ALTER TABLE `shmyde_design_main_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `shmyde_design_sub_menu`
--
ALTER TABLE `shmyde_design_sub_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `shmyde_images`
--
ALTER TABLE `shmyde_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `shmyde_product_image`
--
ALTER TABLE `shmyde_product_image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `shmyde_user`
--
ALTER TABLE `shmyde_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `username_or_email_on_hold`
--
ALTER TABLE `username_or_email_on_hold`
  MODIFY `ai` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `shmyde_design_option`
--
ALTER TABLE `shmyde_design_option`
  ADD CONSTRAINT `shmyde_design_option_specifics_fk` FOREIGN KEY (`shmyde_design_sub_menu_id`) REFERENCES `shmyde_design_sub_menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shmyde_design_sub_menu`
--
ALTER TABLE `shmyde_design_sub_menu`
  ADD CONSTRAINT `shmyde_design_option_fk` FOREIGN KEY (`shmyde_design_main_menu_id`) REFERENCES `shmyde_design_main_menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `shmyde_product_id` FOREIGN KEY (`shmyde_product_id`) REFERENCES `shmyde_product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shmyde_images`
--
ALTER TABLE `shmyde_images`
  ADD CONSTRAINT `shmyde_design_options_fk` FOREIGN KEY (`shmyde_design_options_id`) REFERENCES `shmyde_design_option` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shmyde_measurment`
--
ALTER TABLE `shmyde_measurment`
  ADD CONSTRAINT `shmyde_product_fk` FOREIGN KEY (`shmyde_product_id`) REFERENCES `shmyde_product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shmyde_product_image`
--
ALTER TABLE `shmyde_product_image`
  ADD CONSTRAINT `shmyde_product_image_fk` FOREIGN KEY (`shmyde_product_id`) REFERENCES `shmyde_product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shmyde_template_measurment`
--
ALTER TABLE `shmyde_template_measurment`
  ADD CONSTRAINT `shmyde_measurment_fk` FOREIGN KEY (`shmyde_measurment_id`) REFERENCES `shmyde_measurment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `shmyde_template_fk` FOREIGN KEY (`shmyde_template_id`) REFERENCES `shyme_template` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shyme_template`
--
ALTER TABLE `shyme_template`
  ADD CONSTRAINT `shmyde_measurment_product_fk` FOREIGN KEY (`shmyde_product_id`) REFERENCES `shmyde_product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
