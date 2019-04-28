-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2019-04-18 05:34:05
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `democyc`
--

-- --------------------------------------------------------

--
-- 表的结构 `access_logs`
--

CREATE TABLE IF NOT EXISTS `access_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `ip_address` varchar(200) NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33 ;

-- --------------------------------------------------------

--
-- 表的结构 `activation_links`
--

CREATE TABLE IF NOT EXISTS `activation_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` text NOT NULL,
  `hash` varchar(255) NOT NULL,
  `done` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- 表的结构 `core_settings`
--

CREATE TABLE IF NOT EXISTS `core_settings` (
  `name` varchar(100) NOT NULL,
  `data` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `credit_bank_logs`
--

CREATE TABLE IF NOT EXISTS `credit_bank_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `created` datetime NOT NULL,
  `amount` bigint(20) unsigned NOT NULL,
  `type` enum('0','1') NOT NULL DEFAULT '0',
  `status` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `credit_history`
--

CREATE TABLE IF NOT EXISTS `credit_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `credits` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `datetime` datetime NOT NULL,
  `status` enum('c','d') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- 表的结构 `credit_packages`
--

CREATE TABLE IF NOT EXISTS `credit_packages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `qty` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `investments`
--

CREATE TABLE IF NOT EXISTS `investments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `creator_id` bigint(20) unsigned NOT NULL,
  `created` datetime NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` varchar(250) NOT NULL,
  `investment_wanted` bigint(20) unsigned NOT NULL,
  `amount_invested` bigint(20) unsigned NOT NULL,
  `contract_address` varchar(256) CHARACTER SET utf32 NOT NULL,
  `invested` enum('0','1') NOT NULL DEFAULT '0',
  `expires` datetime NOT NULL,
  `main_picture` varchar(255) NOT NULL,
  `status` enum('0','1','2') NOT NULL DEFAULT '0',
  `thumbnail` varchar(255) NOT NULL,
  `main_description` text NOT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  `investment_message` varchar(200) NOT NULL,
  `investor_count` bigint(20) unsigned NOT NULL,
  `featured` enum('0','1') NOT NULL DEFAULT '0',
  `project_closed_message` varchar(200) NOT NULL,
  `top_theme` enum('0','1') NOT NULL DEFAULT '0',
  `theme` varchar(255) NOT NULL,
  `custom_theme` enum('0','1') NOT NULL DEFAULT '0',
  `ctheme` varchar(255) NOT NULL,
  `video` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- 表的结构 `investments_faq`
--

CREATE TABLE IF NOT EXISTS `investments_faq` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `investment_id` bigint(20) unsigned NOT NULL,
  `datetime` datetime NOT NULL,
  `title` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `investments_made`
--

CREATE TABLE IF NOT EXISTS `investments_made` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `investment_id` bigint(20) unsigned NOT NULL,
  `amount` bigint(20) unsigned NOT NULL,
  `status` enum('0','1','2') NOT NULL DEFAULT '0',
  `date_invested` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- 表的结构 `investments_messages`
--

CREATE TABLE IF NOT EXISTS `investments_messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `investment_id` bigint(20) unsigned NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `sent` datetime NOT NULL,
  `type` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- 表的结构 `investments_updates`
--

CREATE TABLE IF NOT EXISTS `investments_updates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `investment_id` bigint(20) unsigned NOT NULL,
  `datetime` datetime NOT NULL,
  `title` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- 表的结构 `password_links`
--

CREATE TABLE IF NOT EXISTS `password_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` text NOT NULL,
  `hash` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `payout_requests`
--

CREATE TABLE IF NOT EXISTS `payout_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `amount` bigint(20) unsigned NOT NULL,
  `options` text NOT NULL,
  `status` enum('0','1','2') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `personal_messages`
--

CREATE TABLE IF NOT EXISTS `personal_messages` (
  `id` bigint(20) unsigned NOT NULL,
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

CREATE TABLE IF NOT EXISTS `pm_data` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pm_id` bigint(20) unsigned NOT NULL,
  `sender` bigint(20) unsigned NOT NULL,
  `message` text NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `profile`
--

CREATE TABLE IF NOT EXISTS `profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `profile_msg` varchar(255) NOT NULL,
  `about_me` text NOT NULL,
  `profile_picture` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- 表的结构 `profile_messages`
--

CREATE TABLE IF NOT EXISTS `profile_messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `profile_id` bigint(20) unsigned NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `sent` datetime NOT NULL,
  `type` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `staff_notes`
--

CREATE TABLE IF NOT EXISTS `staff_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `users`
--

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
  `credit` bigint(20) unsigned NOT NULL,
  `banked_credit` bigint(20) unsigned NOT NULL,
  `investments_made` bigint(20) unsigned NOT NULL,
  `amount_invested` bigint(20) unsigned NOT NULL,
  `oauth_provider` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `oauth_uid` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- 表的结构 `user_activity`
--

CREATE TABLE IF NOT EXISTS `user_activity` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `datetime` datetime NOT NULL,
  `task` varchar(255) NOT NULL,
  `type` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `user_notifications`
--

CREATE TABLE IF NOT EXISTS `user_notifications` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `note` text NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `categories`
--

INSERT INTO `categories` (`id`, `name`, `status`) VALUES
(1, '互联网项目', '1');

--
-- 转存表中的数据 `core_settings`
--

INSERT INTO `core_settings` (`name`, `data`) VALUES
('WWW', ''),
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
('SITE_CREDIT', '24600'),
('REQUIRE_GOAL', 'YES'),
('THEME_NAME', 'mds_light');


--
-- 转存表中的数据 `users`
--

INSERT INTO `users` (`id`, `user_id`, `first_name`, `last_name`, `gender`, `username`, `password`, `email`, `activated`, `suspended`, `date_created`, `last_login`, `signup_ip`, `last_ip`, `country`, `staff`, `whitelist`, `ip_whitelist`, `credit`, `banked_credit`, `investments_made`, `amount_invested`, `oauth_provider`, `oauth_uid`) VALUES
(1, 688969, 'Admin', 'Account', 'Male', 'admin', 'Sh1mgk0Gf2szw', 'admin@example.com', '1', '0', '2018-12-27 18:34:07', '2019-01-15 15:05:10', 'SERVER', '127.0.0.1', 'United Kingdom', '1', '0', '', 0, 0, 0, 0, '0', ''),
(2, 924233, '', 'caoyucong', 'Male', 'caoyucong', 'Shd0gG4G1Ks2U', '1769614410@qq.com', '', '0', '2018-12-27 18:34:07', '0000-00-00 00:00:00', '::1', '::1', '', '0', '0', '', 0, 0, 0, 0, '0', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

--
-- 转存表中的数据 `profile`
--

INSERT INTO `profile` (`id`, `user_id`, `profile_msg`, `about_me`, `profile_picture`) VALUES
(1, 688969, '=在测试', '一个众筹系统', 'male.jpg'),
(2, 356213, '', '', 'male.jpg'),
(3, 115315, '', '', 'male.jpg');



--
-- 转存表中的数据 `profile_messages`
--

INSERT INTO `profile_messages` (`id`, `user_id`, `profile_id`, `status`, `message`, `sent`, `type`) VALUES
(1, 688969, 688969, '0', '%u6B63%u5728%u6D4B%u8BD5\n', '2013-04-15 22:41:57', '0');
