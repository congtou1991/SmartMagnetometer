-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2016-10-12 13:01:09
-- 服务器版本： 5.5.39
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `anfang`
--

-- --------------------------------------------------------

--
-- 表的结构 `af_alertlog`
--

CREATE TABLE IF NOT EXISTS `af_alertlog` (
`id` int(10) unsigned NOT NULL,
  `rs_id` tinyint(3) NOT NULL,
  `rs_state` tinyint(1) NOT NULL,
  `homemember` char(10) NOT NULL,
  `time` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `af_config`
--

CREATE TABLE IF NOT EXISTS `af_config` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(30) NOT NULL,
  `value` varchar(30) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `af_config`
--

INSERT INTO `af_config` (`id`, `name`, `value`) VALUES
(1, 'alert_state', 'off'),
(2, 'sms_on', '1');

-- --------------------------------------------------------

--
-- 表的结构 `af_handshake`
--

CREATE TABLE IF NOT EXISTS `af_handshake` (
`id` int(10) unsigned NOT NULL,
  `health` tinyint(1) DEFAULT '0',
  `time` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `af_log`
--

CREATE TABLE IF NOT EXISTS `af_log` (
`id` tinyint(3) unsigned NOT NULL,
  `name` char(15) DEFAULT NULL,
  `wechatid` varchar(100) NOT NULL,
  `operation` tinyint(1) NOT NULL,
  `time` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `af_smslog`
--

CREATE TABLE IF NOT EXISTS `af_smslog` (
`id` int(10) unsigned NOT NULL,
  `name` char(100) DEFAULT NULL,
  `phone` char(100) NOT NULL,
  `success` tinyint(1) NOT NULL,
  `err_code` int(15) NOT NULL DEFAULT '0',
  `time` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `af_user`
--

CREATE TABLE IF NOT EXISTS `af_user` (
`id` tinyint(3) unsigned NOT NULL,
  `name` char(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` char(11) DEFAULT NULL,
  `wechatid` varchar(100) NOT NULL,
  `inhome` tinyint(1) NOT NULL DEFAULT '1',
  `checkid` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `af_alertlog`
--
ALTER TABLE `af_alertlog`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `af_config`
--
ALTER TABLE `af_config`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `af_handshake`
--
ALTER TABLE `af_handshake`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `af_log`
--
ALTER TABLE `af_log`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `af_smslog`
--
ALTER TABLE `af_smslog`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `af_user`
--
ALTER TABLE `af_user`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `af_alertlog`
--
ALTER TABLE `af_alertlog`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `af_config`
--
ALTER TABLE `af_config`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `af_handshake`
--
ALTER TABLE `af_handshake`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `af_log`
--
ALTER TABLE `af_log`
MODIFY `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `af_smslog`
--
ALTER TABLE `af_smslog`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `af_user`
--
ALTER TABLE `af_user`
MODIFY `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
