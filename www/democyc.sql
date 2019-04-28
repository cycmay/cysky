-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1:3306
-- 生成日期： 2019-04-28 13:31:06
-- 服务器版本： 5.7.24
-- PHP 版本： 7.2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `democyc`
--

-- --------------------------------------------------------

--
-- 表的结构 `access_logs`
--

DROP TABLE IF EXISTS `access_logs`;
CREATE TABLE IF NOT EXISTS `access_logs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `ip_address` varchar(200) NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `access_logs`
--

INSERT INTO `access_logs` (`id`, `user_id`, `ip_address`, `datetime`) VALUES
(33, 688969, '::1', '2019-04-18 12:07:34'),
(34, 688969, '::1', '2019-04-18 20:52:26'),
(35, 688969, '::1', '2019-04-19 10:48:50'),
(36, 688969, '::1', '2019-04-19 16:48:33'),
(37, 924233, '::1', '2019-04-19 17:05:18'),
(38, 688969, '::1', '2019-04-19 17:24:10'),
(39, 924233, '::1', '2019-04-19 17:24:53'),
(40, 688969, '::1', '2019-04-21 16:57:46'),
(41, 688969, '::1', '2019-04-21 17:16:28'),
(42, 688969, '::1', '2019-04-21 19:54:44'),
(43, 688969, '::1', '2019-04-22 13:49:00'),
(44, 688969, '::1', '2019-04-22 16:02:43'),
(45, 688969, '::1', '2019-04-22 18:19:57'),
(46, 688969, '::1', '2019-04-22 19:12:16'),
(47, 688969, '::1', '2019-04-23 11:27:25'),
(48, 688969, '::1', '2019-04-23 19:05:38'),
(49, 688969, '::1', '2019-04-23 23:16:24'),
(50, 688969, '::1', '2019-04-24 13:04:07'),
(51, 688969, '::1', '2019-04-24 16:24:37'),
(52, 688969, '::1', '2019-04-24 16:29:04'),
(53, 688969, '::1', '2019-04-24 16:58:29'),
(54, 688969, '::1', '2019-04-24 17:05:52'),
(55, 688969, '::1', '2019-04-24 17:12:15'),
(56, 688969, '::1', '2019-04-24 17:12:42'),
(57, 688969, '::1', '2019-04-24 17:14:37'),
(58, 688969, '::1', '2019-04-24 20:11:01'),
(59, 688969, '::1', '2019-04-24 20:46:47'),
(60, 688969, '::1', '2019-04-24 20:52:35'),
(61, 688969, '::1', '2019-04-25 14:39:19'),
(62, 688969, '::1', '2019-04-26 13:46:32'),
(63, 688969, '::1', '2019-04-27 13:08:29'),
(64, 688969, '::1', '2019-04-27 13:20:25'),
(65, 688969, '::1', '2019-04-27 13:20:42'),
(66, 688969, '::1', '2019-04-28 15:20:34'),
(67, 688969, '::1', '2019-04-28 16:28:37'),
(68, 924233, '::1', '2019-04-28 17:54:20'),
(69, 688969, '::1', '2019-04-28 18:01:19'),
(70, 924233, '::1', '2019-04-28 18:05:10'),
(71, 924233, '::1', '2019-04-28 18:11:49'),
(72, 688969, '::1', '2019-04-28 18:12:03');

-- --------------------------------------------------------

--
-- 表的结构 `activation_links`
--

DROP TABLE IF EXISTS `activation_links`;
CREATE TABLE IF NOT EXISTS `activation_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` text NOT NULL,
  `hash` varchar(255) NOT NULL,
  `done` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `categories`
--

INSERT INTO `categories` (`id`, `name`, `status`) VALUES
(1, '互联网项目', '1'),
(2, 'Mr.bicycle', '0');

-- --------------------------------------------------------

--
-- 表的结构 `core_settings`
--

DROP TABLE IF EXISTS `core_settings`;
CREATE TABLE IF NOT EXISTS `core_settings` (
  `name` varchar(100) NOT NULL,
  `data` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `core_settings`
--

INSERT INTO `core_settings` (`name`, `data`) VALUES
('WWW', '/'),
('SITE_NAME', 'Bicycle众筹'),
('SITE_DESC', '站点描述一般不超过200个字符'),
('SITE_KEYW', '站点关键词一般不超过100个字符'),
('ADMINDIR', 'admin/'),
('SITE_EMAIL', 'sv@codec2i.net'),
('VERIFY_EMAIL', '是'),
('MAINTENANCE_MODE', '是'),
('DEMO_MODE', 'OFF'),
('DATABASE_SALT', 'ShEyeki2UhRWDJBUKjf0KvvTY'),
('CURRENCY_CODE', 'RMB'),
('CURRENCYSYMBOL', '&#65509;'),
('PAYPAL_SANDBOX', 'YES'),
('PAYPAL_EMAIL', 'paypal@example.com'),
('CREDIT_PRICE', '1'),
('NEW_PROJECT_VERIFY', '是'),
('DEFAULT_THEME', 'main.jpg'),
('PAGINATION_PER_PAGE', '20'),
('TIMEZONE', 'Asia/Hong_Kong'),
('OAUTH', 'OFF'),
('CUT_PERCENTAGE', '5'),
('TAKE_CUT', 'YES'),
('SITE_CREDIT', '65850'),
('REQUIRE_GOAL', 'YES'),
('THEME_NAME', 'antangle');

-- --------------------------------------------------------

--
-- 表的结构 `credit_bank_logs`
--

DROP TABLE IF EXISTS `credit_bank_logs`;
CREATE TABLE IF NOT EXISTS `credit_bank_logs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created` datetime NOT NULL,
  `amount` bigint(20) UNSIGNED NOT NULL,
  `type` enum('0','1') NOT NULL DEFAULT '0',
  `status` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `credit_history`
--

DROP TABLE IF EXISTS `credit_history`;
CREATE TABLE IF NOT EXISTS `credit_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `credits` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `datetime` datetime NOT NULL,
  `status` enum('c','d') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `credit_history`
--

INSERT INTO `credit_history` (`id`, `user_id`, `credits`, `description`, `datetime`, `status`) VALUES
(1, 924233, 10, 'Credit added for investment: 12', '2019-04-19 17:12:15', 'c'),
(2, 924233, 10, 'Investment made to: 12', '2019-04-19 17:12:15', 'd');

-- --------------------------------------------------------

--
-- 表的结构 `credit_packages`
--

DROP TABLE IF EXISTS `credit_packages`;
CREATE TABLE IF NOT EXISTS `credit_packages` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `qty` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `investments`
--

DROP TABLE IF EXISTS `investments`;
CREATE TABLE IF NOT EXISTS `investments` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `creator_id` bigint(20) UNSIGNED NOT NULL,
  `created` datetime NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` varchar(250) NOT NULL,
  `investment_wanted` bigint(20) UNSIGNED NOT NULL,
  `amount_invested` bigint(20) UNSIGNED NOT NULL,
  `contract_address` varchar(256) CHARACTER SET utf32 NOT NULL,
  `invested` enum('0','1') NOT NULL DEFAULT '0',
  `expires` datetime NOT NULL,
  `main_picture` varchar(255) NOT NULL,
  `status` enum('0','1','2') NOT NULL DEFAULT '0',
  `thumbnail` varchar(255) NOT NULL,
  `main_description` text NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `investment_message` varchar(200) NOT NULL,
  `investor_count` bigint(20) UNSIGNED NOT NULL,
  `featured` enum('0','1') NOT NULL DEFAULT '0',
  `project_closed_message` varchar(200) NOT NULL,
  `top_theme` enum('0','1') NOT NULL DEFAULT '0',
  `theme` varchar(255) NOT NULL,
  `custom_theme` enum('0','1') NOT NULL DEFAULT '0',
  `ctheme` varchar(255) NOT NULL,
  `video` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `investments`
--

INSERT INTO `investments` (`id`, `creator_id`, `created`, `name`, `description`, `investment_wanted`, `amount_invested`, `contract_address`, `invested`, `expires`, `main_picture`, `status`, `thumbnail`, `main_description`, `category_id`, `investment_message`, `investor_count`, `featured`, `project_closed_message`, `top_theme`, `theme`, `custom_theme`, `ctheme`, `video`) VALUES
(2, 688969, '2019-04-19 13:17:11', '尤家网络《超级星球》', '《超级星球》是一款我们自研的女性向休闲社交手游，它以著名IP形象潘斯特为核心创造游戏。在这个游戏中，我们将休闲和社交相结合，提供玩家一个全新的玩法', 1000, 0, '0x000000000000000000000', '0', '2019-05-19 13:17:11', '', '1', 'dog.jpg', '1', 1, '感谢您抽出宝贵的时间看一下我们的项目...', 0, '0', '感谢你支持这个项目....', '0', 'main.jpg', '0', '', ''),
(3, 688969, '2019-04-19 16:48:49', '12', '易电力是基于技术创新与模式创新的专注电力工程产业B2B服务企业，易电力致力于应用新技术、新模式积累行业信用大数据，打造行业大数据‘企查查’，塑造电力行业信用体系‘芝麻信用’，让在易电力的服务平台上可以回到生意本质，基于信任、信用快乐简单的完成每一单生意，让资金在实体产业链闭环完整流通，解决融资难、数据分散、信用不透明等行业问题。', 1000, 10, '0xd6852bbd8be64f6e16301436b9fe98da29f4e261', '0', '2019-05-19 16:48:49', '', '1', '1008034.jpg', '111', 1, '感谢您抽出宝贵的时间看一下我们的项目...', 0, '0', '感谢你支持这个项目....', '0', 'main.jpg', '0', '', ''),
(4, 688969, '2019-04-24 20:52:22', '234324', '1', 1, 0, '0x000000000000000000000', '0', '2019-05-24 20:52:22', '', '0', '', '1', 1, '感谢您抽出宝贵的时间看一下我们的项目...', 0, '0', '感谢你支持这个项目....', '0', 'main.jpg', '0', '', ''),
(5, 688969, '2019-04-24 20:53:09', '1', '1', 1, 0, '0x000000000000000000000', '0', '2019-05-24 20:53:09', '', '0', '1008034.jpg', '1', 1, '感谢您抽出宝贵的时间看一下我们的项目...', 0, '0', '感谢你支持这个项目....', '0', 'main.jpg', '0', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `investments_faq`
--

DROP TABLE IF EXISTS `investments_faq`;
CREATE TABLE IF NOT EXISTS `investments_faq` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `investment_id` bigint(20) UNSIGNED NOT NULL,
  `datetime` datetime NOT NULL,
  `title` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `investments_made`
--

DROP TABLE IF EXISTS `investments_made`;
CREATE TABLE IF NOT EXISTS `investments_made` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `investment_id` bigint(20) UNSIGNED NOT NULL,
  `amount` bigint(20) UNSIGNED NOT NULL,
  `status` enum('0','1','2') NOT NULL DEFAULT '0',
  `date_invested` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `investments_made`
--

INSERT INTO `investments_made` (`id`, `user_id`, `investment_id`, `amount`, `status`, `date_invested`) VALUES
(1, 924233, 3, 10, '0', '2019-04-19 17:12:15');

-- --------------------------------------------------------

--
-- 表的结构 `investments_messages`
--

DROP TABLE IF EXISTS `investments_messages`;
CREATE TABLE IF NOT EXISTS `investments_messages` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `investment_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `sent` datetime NOT NULL,
  `type` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `investments_messages`
--

INSERT INTO `investments_messages` (`id`, `user_id`, `investment_id`, `status`, `message`, `sent`, `type`) VALUES
(1, 688969, 2, '0', 'test sets他', '2019-04-27 17:01:49', '0'),
(2, 688969, 2, '0', '21321321323213123', '2019-04-27 17:45:44', '0'),
(3, 688969, 2, '0', '我去恶趣味我去', '2019-04-27 17:51:45', '0'),
(4, 688969, 3, '0', '哦啦啦啦', '2019-04-27 21:29:53', '0');

-- --------------------------------------------------------

--
-- 表的结构 `investments_updates`
--

DROP TABLE IF EXISTS `investments_updates`;
CREATE TABLE IF NOT EXISTS `investments_updates` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `investment_id` bigint(20) UNSIGNED NOT NULL,
  `datetime` datetime NOT NULL,
  `title` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `investments_updates`
--

INSERT INTO `investments_updates` (`id`, `investment_id`, `datetime`, `title`, `message`, `status`) VALUES
(1, 3, '2019-04-27 13:54:24', '1', '1', '1'),
(3, 3, '2019-04-27 14:58:34', '这是一个标题', '这是一个内容。', '1'),
(5, 2, '2019-04-27 17:55:39', 'initfirst', 'test', '1');

-- --------------------------------------------------------

--
-- 表的结构 `password_links`
--

DROP TABLE IF EXISTS `password_links`;
CREATE TABLE IF NOT EXISTS `password_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` text NOT NULL,
  `hash` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `password_links`
--

INSERT INTO `password_links` (`id`, `email`, `hash`) VALUES
(1, '1769614470@qq.com', 'zCv0yfAq');

-- --------------------------------------------------------

--
-- 表的结构 `payout_requests`
--

DROP TABLE IF EXISTS `payout_requests`;
CREATE TABLE IF NOT EXISTS `payout_requests` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `amount` bigint(20) UNSIGNED NOT NULL,
  `options` text NOT NULL,
  `status` enum('0','1','2') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `personal_messages`
--

DROP TABLE IF EXISTS `personal_messages`;
CREATE TABLE IF NOT EXISTS `personal_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `recipient` varchar(50) NOT NULL,
  `sender` varchar(50) NOT NULL,
  `subject` varchar(150) NOT NULL,
  `status` enum('s','r') NOT NULL DEFAULT 's',
  `datetime_sent` datetime NOT NULL,
  `sender_deleted` enum('0','1') NOT NULL DEFAULT '0',
  `recipient_deleted` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pm_data`
--

DROP TABLE IF EXISTS `pm_data`;
CREATE TABLE IF NOT EXISTS `pm_data` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pm_id` bigint(20) UNSIGNED NOT NULL,
  `sender` bigint(20) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `profile`
--

DROP TABLE IF EXISTS `profile`;
CREATE TABLE IF NOT EXISTS `profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `profile_msg` varchar(255) NOT NULL,
  `about_me` text NOT NULL,
  `profile_picture` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `profile`
--

INSERT INTO `profile` (`id`, `user_id`, `profile_msg`, `about_me`, `profile_picture`) VALUES
(1, 688969, '=在测试', '一个众筹系统', 'male.jpg'),
(2, 356213, '', '', 'male.jpg'),
(3, 115315, '', '', 'male.jpg');

-- --------------------------------------------------------

--
-- 表的结构 `profile_messages`
--

DROP TABLE IF EXISTS `profile_messages`;
CREATE TABLE IF NOT EXISTS `profile_messages` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `profile_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `sent` datetime NOT NULL,
  `type` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `profile_messages`
--

INSERT INTO `profile_messages` (`id`, `user_id`, `profile_id`, `status`, `message`, `sent`, `type`) VALUES
(1, 688969, 688969, '0', '%u6B63%u5728%u6D4B%u8BD5\n', '2013-04-15 22:41:57', '0');

-- --------------------------------------------------------

--
-- 表的结构 `staff_notes`
--

DROP TABLE IF EXISTS `staff_notes`;
CREATE TABLE IF NOT EXISTS `staff_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(11) NOT NULL,
  `first_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `gender` enum('Male','Female') COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `activated` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `suspended` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `date_created` datetime NOT NULL,
  `last_login` datetime NOT NULL,
  `signup_ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `staff` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `whitelist` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `ip_whitelist` text COLLATE utf8_unicode_ci NOT NULL,
  `credit` bigint(20) UNSIGNED NOT NULL,
  `banked_credit` bigint(20) UNSIGNED NOT NULL,
  `investments_made` bigint(20) UNSIGNED NOT NULL,
  `amount_invested` bigint(20) UNSIGNED NOT NULL,
  `oauth_provider` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `oauth_uid` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `users`
--

INSERT INTO `users` (`id`, `user_id`, `first_name`, `last_name`, `gender`, `username`, `password`, `email`, `activated`, `suspended`, `date_created`, `last_login`, `signup_ip`, `last_ip`, `country`, `staff`, `whitelist`, `ip_whitelist`, `credit`, `banked_credit`, `investments_made`, `amount_invested`, `oauth_provider`, `oauth_uid`) VALUES
(1, 688969, 'Admin', 'Account', 'Male', 'admin', 'Sh1mgk0Gf2szw', 'admin@example.com', '1', '0', '2018-12-27 18:34:07', '2019-04-28 18:12:03', 'SERVER', '::1', 'United Kingdom', '1', '0', '', 0, 0, 0, 0, '0', ''),
(2, 924233, '无名氏', 'caoyucong', 'Male', 'caoyucong', 'Shd0gG4G1Ks2U', '1769614410@qq.com', '1', '0', '2018-12-27 18:34:07', '2019-04-28 18:11:49', '::1', '::1', 'China', '0', '0', '', 10, 0, 1, 10, '0', '');

-- --------------------------------------------------------

--
-- 表的结构 `user_activity`
--

DROP TABLE IF EXISTS `user_activity`;
CREATE TABLE IF NOT EXISTS `user_activity` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `datetime` datetime NOT NULL,
  `task` varchar(255) NOT NULL,
  `type` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `user_notifications`
--

DROP TABLE IF EXISTS `user_notifications`;
CREATE TABLE IF NOT EXISTS `user_notifications` (
  `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `note` text NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
