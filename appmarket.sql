-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 
-- 伺服器版本： 10.1.40-MariaDB
-- PHP 版本： 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `appmarket`
--

-- --------------------------------------------------------

--
-- 資料表結構 `appmarket_item`
--

CREATE TABLE `appmarket_item` (
  `id` int(255) NOT NULL,
  `access_token` varchar(255) COLLATE utf8_bin NOT NULL,
  `item_id` varchar(255) COLLATE utf8_bin NOT NULL,
  `item_name` varchar(15) COLLATE utf8_bin NOT NULL,
  `item_image` longtext COLLATE utf8_bin NOT NULL,
  `item_description` longtext COLLATE utf8_bin NOT NULL,
  `item_price` int(255) NOT NULL,
  `item_unit` int(255) NOT NULL,
  `item_enable` varchar(5) COLLATE utf8_bin NOT NULL,
  `created_time` varchar(20) COLLATE utf8_bin NOT NULL,
  `updated_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 資料表結構 `appmarket_log`
--

CREATE TABLE `appmarket_log` (
  `id` int(255) NOT NULL,
  `access_token` varchar(255) COLLATE utf8_bin NOT NULL,
  `activity_content` varchar(255) COLLATE utf8_bin NOT NULL,
  `store_device` varchar(255) COLLATE utf8_bin NOT NULL,
  `ip` varchar(15) COLLATE utf8_bin NOT NULL,
  `created_time` varchar(20) COLLATE utf8_bin NOT NULL,
  `updated_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 資料表結構 `appmarket_member`
--

CREATE TABLE `appmarket_member` (
  `id` int(255) NOT NULL,
  `access_token` varchar(255) COLLATE utf8_bin NOT NULL,
  `store_name` varchar(15) COLLATE utf8_bin NOT NULL,
  `store_password` varchar(255) COLLATE utf8_bin NOT NULL,
  `store_email` varchar(255) COLLATE utf8_bin NOT NULL,
  `store_code` varchar(10) COLLATE utf8_bin NOT NULL,
  `store_image` longtext COLLATE utf8_bin NOT NULL,
  `administrator` varchar(5) COLLATE utf8_bin NOT NULL,
  `enable` varchar(5) COLLATE utf8_bin NOT NULL,
  `created_time` varchar(20) COLLATE utf8_bin NOT NULL,
  `updated_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- 傾印資料表的資料 `appmarket_member`
--

INSERT INTO `appmarket_member` (`id`, `access_token`, `store_name`, `store_password`, `store_email`, `store_code`, `store_image`, `administrator`, `enable`, `created_time`, `updated_time`) VALUES
(1, '9db925ff421b16842f933c7f421e8f75', '管理員', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', 'admin@admin.com', 'admin', 'data:image/jpeg;base64,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', 'true', 'true', '2020-03-01 12:27:14', '2020-03-23 15:09:13');

-- --------------------------------------------------------

--
-- 資料表結構 `appmarket_order`
--

CREATE TABLE `appmarket_order` (
  `id` int(255) NOT NULL,
  `access_token` varchar(255) COLLATE utf8_bin NOT NULL,
  `order_token` varchar(255) COLLATE utf8_bin NOT NULL,
  `order_password` varchar(5) COLLATE utf8_bin NOT NULL,
  `order_content` longtext COLLATE utf8_bin NOT NULL,
  `order_price` int(255) NOT NULL,
  `order_verification` varchar(5) COLLATE utf8_bin NOT NULL,
  `order_status` varchar(5) COLLATE utf8_bin NOT NULL,
  `order_ip` varchar(15) COLLATE utf8_bin NOT NULL,
  `order_device` varchar(255) COLLATE utf8_bin NOT NULL,
  `enable` varchar(5) COLLATE utf8_bin NOT NULL,
  `created_time` varchar(20) COLLATE utf8_bin NOT NULL,
  `updated_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 資料表結構 `appmarket_token`
--

CREATE TABLE `appmarket_token` (
  `id` int(255) NOT NULL,
  `token` varchar(255) COLLATE utf8_bin NOT NULL,
  `service_name` varchar(255) COLLATE utf8_bin NOT NULL,
  `created_time` varchar(20) COLLATE utf8_bin NOT NULL,
  `updated_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `appmarket_item`
--
ALTER TABLE `appmarket_item`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `appmarket_log`
--
ALTER TABLE `appmarket_log`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `appmarket_member`
--
ALTER TABLE `appmarket_member`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `appmarket_order`
--
ALTER TABLE `appmarket_order`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `appmarket_token`
--
ALTER TABLE `appmarket_token`
  ADD PRIMARY KEY (`id`);

--
-- 在傾印的資料表使用自動增長(AUTO_INCREMENT)
--

--
-- 使用資料表自動增長(AUTO_INCREMENT) `appmarket_item`
--
ALTER TABLE `appmarket_item`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動增長(AUTO_INCREMENT) `appmarket_log`
--
ALTER TABLE `appmarket_log`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動增長(AUTO_INCREMENT) `appmarket_member`
--
ALTER TABLE `appmarket_member`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用資料表自動增長(AUTO_INCREMENT) `appmarket_order`
--
ALTER TABLE `appmarket_order`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動增長(AUTO_INCREMENT) `appmarket_token`
--
ALTER TABLE `appmarket_token`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
