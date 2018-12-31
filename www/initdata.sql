-- phpMyAdmin SQL Dump
-- version 3.4.7.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2013 年 04 月 15 日 15:08
-- 服务器版本: 5.1.63
-- PHP 版本: 5.2.17p1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `demo_zhongchou`
--

-- --------------------------------------------------------

--
-- 表的结构 `access_logs`
--

DROP TABLE IF EXISTS `access_logs`;
CREATE TABLE IF NOT EXISTS `access_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `ip_address` varchar(200) NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- 转存表中的数据 `access_logs`
--

INSERT INTO `access_logs` (`id`, `user_id`, `ip_address`, `datetime`) VALUES
(1, 688969, '127.0.0.1', '2013-04-12 13:57:42'),
(2, 688969, '127.0.0.1', '2013-04-12 14:31:44'),
(3, 688969, '127.0.0.1', '2013-04-13 10:55:16'),
(4, 688969, '127.0.0.1', '2013-04-14 06:34:29'),
(5, 688969, '127.0.0.1', '2013-04-15 18:25:03'),
(6, 688969, '127.0.0.1', '2013-04-15 22:31:58'),
(7, 688969, '127.0.0.1', '2013-04-15 22:48:33'),
(8, 688969, '127.0.0.1', '2013-04-15 22:48:35'),
(9, 688969, '127.0.0.1', '2013-04-15 22:52:52'),
(10, 688969, '127.0.0.1', '2013-04-15 22:53:12'),
(11, 688969, '127.0.0.1', '2013-04-15 22:58:34'),
(12, 688969, '127.0.0.1', '2013-04-15 23:02:55');

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `categories`
--

INSERT INTO `categories` (`id`, `name`, `status`) VALUES
(1, '互联网项目', '1');

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
('WWW', 'http://demo.codec2i.net/'),
('SITE_NAME', 'Codec2i众筹'),
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
('SITE_CREDIT', '161100'),
('REQUIRE_GOAL', 'YES'),
('THEME_NAME', 'mds_light');

-- --------------------------------------------------------

--
-- 表的结构 `credit_bank_logs`
--

DROP TABLE IF EXISTS `credit_bank_logs`;
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

DROP TABLE IF EXISTS `credit_history`;
CREATE TABLE IF NOT EXISTS `credit_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `credits` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `datetime` datetime NOT NULL,
  `status` enum('c','d') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `credit_packages`
--

DROP TABLE IF EXISTS `credit_packages`;
CREATE TABLE IF NOT EXISTS `credit_packages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `qty` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `credit_packages`
--

INSERT INTO `credit_packages` (`id`, `name`, `status`, `qty`) VALUES
(1, '测试', '1', 100);

-- --------------------------------------------------------

--
-- 表的结构 `investments`
--

DROP TABLE IF EXISTS `investments`;
CREATE TABLE IF NOT EXISTS `investments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `creator_id` bigint(20) unsigned NOT NULL,
  `created` datetime NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` varchar(250) NOT NULL,
  `investment_wanted` bigint(20) unsigned NOT NULL,
  `amount_invested` bigint(20) unsigned NOT NULL,
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `investments`
--

INSERT INTO `investments` (`id`, `creator_id`, `created`, `name`, `description`, `investment_wanted`, `amount_invested`, `invested`, `expires`, `main_picture`, `status`, `thumbnail`, `main_description`, `category_id`, `investment_message`, `investor_count`, `featured`, `project_closed_message`, `top_theme`, `theme`, `custom_theme`, `ctheme`, `video`) VALUES
(1, 688969, '2013-04-15 22:33:04', '预言星国内首家互动竞猜平台', '此系统是国内首创（国外不敢说，国内绝对是首家），市面同类产品NULL没有，何为竞争力，创新产品0竞争，这就是最大的竞争力。  打造平台概念，打破现有竞猜网站、博彩网站，由网站运营方坐庄发起竞猜的模式，圆每个人心中的小庄家梦。  以运营平台为概念，以用户自主发起竞猜、参与竞猜并且获得盈利的同类产品国内尚未出现。将如何坐庄赚钱，如何拉人参与竞猜的问题转嫁于用户。您只需守株待兔，您会发现用户的力量是伟大的。', 10000, 0, '0', '2013-05-15 22:33:04', '', '1', '竞猜内页 - 首页 - 第2版 - 通版.png', '系统特点\n\n1.任何注册用户都可以在预言星自主发起竞猜（即用户自主坐庄），用户既为参与者同时也可成为庄家。\n\n2.多元化竞猜，预言星竞猜平台可发起体育（足球、篮球…）、财经（股指）、彩票、生活娱乐、等多种竞猜题目。打破传统竞猜平台单一分类的瓶颈，可吸纳更多的人参与竞猜。\n\n    主流竞猜主题，快速发起。例如：\n\n        足球\n        股指\n        彩票 等... （可在系统后台进行添加及定义）\n\n    主流竞猜主题，系统自动列出相关玩法、规则及赔率（玩法抽离，可以动态安装而不需要修改任何代码）。用户只需要简单的勾选及设定即可完成。\n\n    例如：足球竞猜主题，用户发布流程\n\n        选择联赛（系统自动列出可竞猜的比赛，供用户选择，场次可在后台手工添加或者读取数据源）\n        选择比赛场次 （系统会根据开球时间，给出建议竞猜时间范围）\n        选择下注类型 （金币、积分、吃喝玩乐）\n        选择玩法 （猜胜负平、猜总进球数、猜比分、等...，系统支持后台添加玩法并安装。）\n        设定赔率 （固定赔率、浮动赔率 用户作为庄家可自主设定浮动赔率百分比）\n        选择竞猜范围（公开您的竞猜让任何人都可以参与，或者限定好友参与）\n\n    完成以上步骤，系统会根据用户的下注类型、玩法、赔率等...参数，自动托管用户作为庄家的金币或者积分。(托管金币或者积分用于，结果判定。)\n\n    OK完成发布！通过新浪微博或者腾讯QQ，去邀请好友来参与您的竞猜吧。\n\n3.主流竞猜主题系统自动判定结果。平台通过网络公信数据源进行数据采集（或者后台人工判定），并配合人工审核，双重认证判定竞猜结果。并且根据判定结果，进行赌注分配。（用户作为庄家，赢则获得全部收益，输则从托管配额中扣除相应部分，分配给竞猜参与者，系统同时可设定是否抽取庄家利润。）\n\n4.用户自定义竞猜主题，电视选秀节目、生活中的小话题等...，为主题发布竞猜。并且以请吃饭、请唱歌为赌注类型，进行趣味竞猜。在网络进行互动的同时兼备线下交流娱乐。\n\n5.多种竞猜玩法\n\n        金币竞猜（通过充值获得，可直接兑换商品）\n        积分竞猜（通过完成网站任务获得，可参与网站抽奖兑换商品）\n        吃喝玩乐（庄家与参与者线下解决，请吃饭、请唱歌、请喝酒…庄家可自定义赌注类型）\n\n运营收入\n\n打破传统坐庄赚钱的概念，预言星的主要收入来源于用户坐庄的盈利。\n\n金币竞猜需要人民币充值，系统金币由系统平台发行，系统默认是与用户坐庄的盈利金币3/7分成（系统可以设定可高可低，根据用户粘稠度设定），即用户坐庄盈利的金币数的30%由预言星系统扣除，这30%即为运营者的盈利。\n\n其中浮动赔率庄家稳赚不赔，系统默认浮动赔率计算公式为：\n\n    浮动赔率 =（总投注额-A.选项投注的总额）× 90% ÷ A.选项投注总额。\n\n其中90%可以更改为80%、70%、60%（暴利），百分比越小，赔率越小，庄家盈利越大，系统分成越大；可根据自身营运情况调节。（赔率保留小数点后两位，不四舍五入。损失掉的金币系统扣除）从传统的坐庄盈利转化为经营平台，通过庄家赚钱。\n\n做为即能控制庄家又能查看参与者的系统运营平台，想必还有更多盈利方式等待你去挖掘，这里就不再表述了，请依法使用本系统营运。 Enjoy!\n\n目前缺点\n\n优点有很多，缺点同样有。为了不坑，详细列出。\n\n1.系统目前版本，尚不能实现根据竞猜分类，直接读取公信数据源。以实现竞猜主题的自动获取，不能自动获取就不能实现对竞猜结果的自动判定。\n\n    导致的缺点：需要专人在后台手工录入系统竞猜主题，或者通过整理成XML、excel文件导入。但是必须人工进行结果判定。想运营好，几乎每天都要有专人职守。\n\n    解决办法：这个问题主要是因为数据源的获取渠道，如果说您肯花每月5000大洋进行数据租用，那么此问题就迎刃而解了。\n\n    例如：体育类几乎所有的数据源都出自于此OPTA（大名鼎鼎）http://www.optasports.com/，不要说什么国内新浪、腾讯啥也有。他们也是在租用OPTA的数据源，通过国内代理商搜达付费租用。\n\n    花大价钱进行数据租用，肯定不靠谱。我的解决办法，将所有主流竞猜主题的获取源，采取数据采集的办法。集中到一个平台，所有使用预言星竞猜平台的用户都可以通过此平台来读取数据源。（当然我要做这项工作的前提是，平台要卖到10份以上，这项工作很麻烦，不会在近期更新中考虑）\n\n2.经过反复推敲，积分为赌注形式有些累赘，多此一举。但此版本并不能控制积分模式的开启和关闭。我为何会说积分有些累赘您需要，运营一段时间才会发现。我粗略说下，积分在前期培养用户时有用，用户可以不进行充值，就可以熟悉网站。但是对于一个带点博彩色彩、以快速盈利为目的的平台来讲，在中期积分会成为很多漏洞的入口。\n\n    解决办法：在1.1版本加入对积分模式的开启与关闭。\n\n3.一个相对弱智的商城，进行金币及积分的兑换及抽奖。\n\n    主要考虑到写个商城很复杂，不如去对接现在较成熟的商城系统，再进行二次开发。\n\n    解决办法：对接市面主流商城系统。\n\n4.在1.0版本，用户提示不足，前端相对粗糙，主要原因在于竞猜程序复杂，并且要求严谨，所以大量的时间都花费在了反复测试与推敲的过程。由于之前放话过于理想，导致一再跳票。所以就在前端及用户体验方面就放低了标准。\n\n    解决办法：有强力的前端及设计能力，如果给点资金动力。解决不是问题 ：）\n\n吐槽\n\n本人爱小赌，以足球为乐。受各大博彩网站，腾讯体育竞猜、搜狐微竞猜启发，萌生预言星互动竞猜平台概念，但又由于资金有限，无团队，所以自己无法运营。但我相信此平台绝对有很大前景（看趋势、看发展、就看各大门户的一些beta产品，与竞猜相关的都在出）。\n\n个人完美主义者，从12年11月开始开发，历经5个月的时间。将其基本完成，问题肯定是有，但是希望大家支持我持续改进。本人不承诺会更新此系统多长时间，多快速度，说这些不靠谱的没啥用。如果说我从中无利可途，自然时间长了就会放弃，毕竟要张嘴吃饭。如果卖的不错，自然快速更新将此系统放在首要位置。（鉴于系统尚有不足，我起码会更新此系统到年底哈）\n\n本人混过大场子，走过小作坊，自己创过业。对WEB运营有些想法，可以与我交流。针对本系统，能盈利才是硬道理。', 1, '感谢您抽出宝贵的时间看一下我们的项目...', 0, '0', '感谢你支持这个项目....', '0', 'main.jpg', '0', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `investments_faq`
--

DROP TABLE IF EXISTS `investments_faq`;
CREATE TABLE IF NOT EXISTS `investments_faq` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `investment_id` bigint(20) unsigned NOT NULL,
  `datetime` datetime NOT NULL,
  `title` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `investments_faq`
--

INSERT INTO `investments_faq` (`id`, `investment_id`, `datetime`, `title`, `message`, `status`) VALUES
(1, 1, '2013-04-15 22:44:57', '您有什么问题？', '没有问题', '1');

-- --------------------------------------------------------

--
-- 表的结构 `investments_made`
--

DROP TABLE IF EXISTS `investments_made`;
CREATE TABLE IF NOT EXISTS `investments_made` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `investment_id` bigint(20) unsigned NOT NULL,
  `amount` bigint(20) unsigned NOT NULL,
  `status` enum('0','1','2') NOT NULL DEFAULT '0',
  `date_invested` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `investments_messages`
--

DROP TABLE IF EXISTS `investments_messages`;
CREATE TABLE IF NOT EXISTS `investments_messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `investment_id` bigint(20) unsigned NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `sent` datetime NOT NULL,
  `type` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `investments_messages`
--

INSERT INTO `investments_messages` (`id`, `user_id`, `investment_id`, `status`, `message`, `sent`, `type`) VALUES
(1, 688969, 1, '0', '测试一下', '2013-04-15 22:39:59', '0'),
(2, 688969, 1, '0', '再次测试', '2013-04-15 22:40:16', '0');

-- --------------------------------------------------------

--
-- 表的结构 `investments_updates`
--

DROP TABLE IF EXISTS `investments_updates`;
CREATE TABLE IF NOT EXISTS `investments_updates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `investment_id` bigint(20) unsigned NOT NULL,
  `datetime` datetime NOT NULL,
  `title` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `investments_updates`
--

INSERT INTO `investments_updates` (`id`, `investment_id`, `datetime`, `title`, `message`, `status`) VALUES
(1, 1, '2013-04-15 22:44:40', '更新图片', '测试一下', '1');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `payout_requests`
--

DROP TABLE IF EXISTS `payout_requests`;
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

DROP TABLE IF EXISTS `personal_messages`;
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

DROP TABLE IF EXISTS `pm_data`;
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

DROP TABLE IF EXISTS `profile`;
CREATE TABLE IF NOT EXISTS `profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `profile_msg` varchar(255) NOT NULL,
  `about_me` text NOT NULL,
  `profile_picture` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

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
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `profile_id` bigint(20) unsigned NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `sent` datetime NOT NULL,
  `type` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
  `credit` bigint(20) unsigned NOT NULL,
  `banked_credit` bigint(20) unsigned NOT NULL,
  `investments_made` bigint(20) unsigned NOT NULL,
  `amount_invested` bigint(20) unsigned NOT NULL,
  `oauth_provider` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `oauth_uid` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `users`
--

INSERT INTO `users` (`id`, `user_id`, `first_name`, `last_name`, `gender`, `username`, `password`, `email`, `activated`, `suspended`, `date_created`, `last_login`, `signup_ip`, `last_ip`, `country`, `staff`, `whitelist`, `ip_whitelist`, `credit`, `banked_credit`, `investments_made`, `amount_invested`, `oauth_provider`, `oauth_uid`) VALUES
(1, 688969, 'Admin', 'Account', 'Male', 'admin', 'Sh1mgk0Gf2szw', 'admin@example.com', '1', '0', '0000-00-00 00:00:00', '2013-04-15 23:02:55', 'SERVER', '127.0.0.1', 'United Kingdom', '1', '0', '', 0, 0, 0, 0, '0', ''),
(3, 115315, '', '小三哥', 'Male', 'rj6967', 'ShLgQTCpLWVnQ', 'tinitry.rj6967@gmail.com', '', '0', '2013-04-15 18:24:47', '0000-00-00 00:00:00', '127.0.0.1', '127.0.0.1', '', '0', '0', '', 0, 0, 0, 0, '0', '');

-- --------------------------------------------------------

--
-- 表的结构 `user_activity`
--

DROP TABLE IF EXISTS `user_activity`;
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

DROP TABLE IF EXISTS `user_notifications`;
CREATE TABLE IF NOT EXISTS `user_notifications` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `note` text NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
