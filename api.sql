-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 03, 2017 at 08:40 PM
-- Server version: 5.6.16-1~exp1
-- PHP Version: 7.0.19-1+deb.sury.org~xenial+2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `api`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `id` int(11) NOT NULL,
  `address` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`id`, `address`) VALUES
(2, 'bbbb'),
(3, 'kkkk'),
(4, 'zzz'),
(5, 'addrf'),
(6, 'jjj'),
(7, 'cvcxv'),
(8, 'addr'),
(9, 'addrtgtg'),
(10, 'adxcvcdr'),
(11, 'addrsdf'),
(12, 'addrfgh'),
(13, 'asdsaaddr'),
(14, 'addrdfg'),
(16, 'addrfbfb');

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE `country` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `code` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `country`
--

INSERT INTO `country` (`id`, `name`, `code`) VALUES
(1, 'Ukraine', 'uk');

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

CREATE TABLE `image` (
  `id` int(11) NOT NULL,
  `path` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `image`
--

INSERT INTO `image` (`id`, `path`) VALUES
(2, 'http://api/upload/8b4bc838914edc9a20092f8d5f7da9db.jpeg'),
(3, 'http://api/upload/df082aa0e09cab77ab16cd1502914a5c.jpeg'),
(4, 'http://api/upload/5a2ea1b71dd22ba7c4e8890095c978cf.jpeg'),
(5, 'http://api/upload/895cd2f6bb0cec4260db826a0a4be4bb.jpeg'),
(6, 'http://api/upload/7c5d55b8e25fb3cf7ac6c23d852096fa.jpeg'),
(7, 'http://api/upload/2bf1dced2d1eefcbca7815afd7548206.jpeg'),
(8, 'http://api/upload/4e65037b82839bff5c819f81f702defe.jpeg'),
(9, 'http://api/upload/0627b7917a6a7ade382e6c1db8f5753f.jpeg'),
(10, 'http://api/upload/9a26c49bba88e12176cf2e7307586589.jpeg'),
(11, 'http://api/upload/1c0f08867c8a666650e694ec6c97daf9.jpeg'),
(12, 'http://api/upload/8132aded3442be102cc35ddcdf260ab8.jpeg'),
(13, 'http://api/upload/b7f654873d7bc853725135248c06a80c.jpeg'),
(14, 'http://api/upload/fb0242f07ac96a2d2a593dca6228e65b.png'),
(16, 'http://api/upload/8a7c0b5b3870882099e4857bb0540366.png');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `user_role` enum('ROLE_USER','ROLE_ADMIN','ROLE_MANAGER') COLLATE utf8_unicode_ci DEFAULT NULL,
  `role_label` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `user_role`, `role_label`) VALUES
(1, 'ROLE_USER', 'role');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `address_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `image_id` int(11) DEFAULT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(64) NOT NULL,
  `email` varchar(60) NOT NULL,
  `is_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `address_id`, `country_id`, `image_id`, `username`, `password`, `email`, `is_active`) VALUES
(3, 2, 1, 2, 'bbbb', '$2y$12$gzU7L9S0Z3TprKf9DuY5hulwjhsP3r3Rpjp4PEt.vcf5Y0u.uDk3u', 'bbbb@ram.ru', 1),
(4, 3, 1, 3, 'kkk', '$2y$12$De/Qtr0.g1QMDSh2eU3MZeAgFSUGJish5RpFt71mS0FTLnnrFIYYC', 'kkk@ram.ru', 1),
(5, 4, 1, 4, 'zzzz', '$2y$12$2G8d0t1sOdm/o0EM6q6/neIxDmxFWD8YOat8FJre8xrr6QrCuRrYe', 'zzzz@ram.ru', 1),
(6, 5, 1, 5, 'Bobf', '$2y$12$P0e7kZtc2d9loey3l0Wbae.DQzNCXizAgpVOhlO89tN/OBywylhUG', 'bogf@ram.ru', 1),
(7, 6, 1, 6, 'jj', '$2y$12$ewrw.4MRwdjVxvW8s8T4buAtgfaCna9XE/jIFYIlIsIywXhyciXxm', 'bojjjg@ram.ru', 1),
(8, 7, 1, 7, 'xcvcv', '$2y$12$0..IFp9i46dKKQoubw3GRejW8zHbsbyME1jxWd99/YSyzMppYPZee', 'boxcvcxg@ram.ru', 1),
(9, 8, 1, 8, 'Bob', '$2y$12$ng5VHAdCJzsK..rMDqrv5ONRsmEerHjRbh1LVpbC6NOXX1JQ1AG0O', 'bosdfg@ram.ru', 1),
(10, 9, 1, 9, 'Bobtgt', '$2y$12$huc42jnUd1l.k0Y6RVjb..E24WxUyx6BHhuKqxqrLR2rQwQkkG7ji', 'boggtgtg@ram.ru', 1),
(11, 10, 1, 10, 'Bovcxvb', '$2y$12$2244uIvWAjsSIqF8D14unuCM/X8rQ/bOymIPNVGnH0apBm.ibSd7i', 'bog@cxvvram.ru', 1),
(12, 11, 1, 11, 'Bobdf', '$2y$12$37uM5wrQ5bejLd9F4xQXx.bjKAu3hNT3v2.j4R5n9Qu/YMj4sZdX6', 'bosdfdsfg@ram.ru', 1),
(13, 12, 1, 12, 'Bobgfh', '$2y$12$oGAo.iINTVGakghP0fUk9u1849NhlS5S6fOTMpHwD6vr1w0MmkcJe', 'boggfh@ram.ru', 1),
(14, 13, 1, 13, 'Bobadas', '$2y$12$VrF7yMUCDk3dVD4pHD.TWu4Kd75mhg2EdxscZzr3OAFjKSCZ1vle6', 'boasdg@ram.ru', 1),
(15, 14, 1, 14, 'Bobfdg', '$2y$12$qP8dv64DsKcjdN5YP/VwxOVwKyCD0fpbuqPYMObsaz4M.eKUjXBny', 'bodfgg@ram.ru', 1),
(17, 16, 1, 16, 'Bobbfbf', '$2y$12$vXMpJFUA/SgwrTFVuNae5eFfpxnEikcYEmqyRRQF9CelAODw/KCaG', 'bfbfbog@ram.ru', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_group`
--

CREATE TABLE `user_group` (
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_group`
--

INSERT INTO `user_group` (`user_id`, `group_id`) VALUES
(3, 2),
(4, 3),
(5, 4),
(6, 5),
(7, 6),
(8, 7),
(9, 8),
(10, 9),
(11, 10),
(12, 11),
(13, 12),
(14, 13),
(15, 14),
(17, 16);

-- --------------------------------------------------------

--
-- Table structure for table `user_permission`
--

CREATE TABLE `user_permission` (
  `id` int(11) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_permission`
--

INSERT INTO `user_permission` (`id`, `role_id`, `name`) VALUES
(1, 1, 'asd'),
(2, 1, 'bbbb'),
(3, 1, 'kkk'),
(4, 1, 'zzzz'),
(5, 1, 'Bobf'),
(6, 1, 'jj'),
(7, 1, 'xcvcv'),
(8, 1, 'Bob'),
(9, 1, 'Bobtgt'),
(10, 1, 'Bovcxvb'),
(11, 1, 'Bobdf'),
(12, 1, 'Bobgfh'),
(13, 1, 'Bobadas'),
(14, 1, 'Bobfdg'),
(16, 1, 'Bobbfbf');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8D93D649F5B7AF75` (`address_id`),
  ADD KEY `IDX_8D93D649F92F3E70` (`country_id`),
  ADD KEY `IDX_8D93D6493DA5256D` (`image_id`);

--
-- Indexes for table `user_group`
--
ALTER TABLE `user_group`
  ADD PRIMARY KEY (`user_id`,`group_id`),
  ADD KEY `IDX_8F02BF9DA76ED395` (`user_id`),
  ADD KEY `IDX_8F02BF9DFE54D947` (`group_id`);

--
-- Indexes for table `user_permission`
--
ALTER TABLE `user_permission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_472E5446D60322AC` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `country`
--
ALTER TABLE `country`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `image`
--
ALTER TABLE `image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `user_permission`
--
ALTER TABLE `user_permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D6493DA5256D` FOREIGN KEY (`image_id`) REFERENCES `image` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_8D93D649F5B7AF75` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_8D93D649F92F3E70` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`);

--
-- Constraints for table `user_group`
--
ALTER TABLE `user_group`
  ADD CONSTRAINT `FK_8F02BF9DA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_8F02BF9DFE54D947` FOREIGN KEY (`group_id`) REFERENCES `user_permission` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_permission`
--
ALTER TABLE `user_permission`
  ADD CONSTRAINT `FK_472E5446D60322AC` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
