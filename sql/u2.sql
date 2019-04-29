-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2019 年 4 月 24 日 03:51
-- サーバのバージョン： 5.7.25
-- PHP Version: 7.2.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u2`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `other`
--

CREATE TABLE `other` (
  `data_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `repair` enum('yes','no') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `other`
--

INSERT INTO `other` (`data_time`, `repair`) VALUES
('1970-01-01 00:00:00', 'no');

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

CREATE TABLE `users` (
  `id` int(5) NOT NULL,
  `uid` int(5) NOT NULL,
  `username` char(25) NOT NULL,
  `master` int(5) NOT NULL,
  `alive` enum('yes','no') NOT NULL DEFAULT 'yes',
  `privacy` enum('normal','strong') NOT NULL DEFAULT 'normal'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `other`
--
ALTER TABLE `other`
  ADD PRIMARY KEY (`repair`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
