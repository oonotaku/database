-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: mysql3104.db.sakura.ne.jp
-- 生成日時: 2025 年 1 月 10 日 15:11
-- サーバのバージョン： 8.0.40
-- PHP のバージョン: 8.2.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `takkun-da_registration`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `registrations`
--

CREATE TABLE `registrations` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `company` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `memo` text,
  `photo_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- テーブルのデータのダンプ `registrations`
--

INSERT INTO `registrations` (`id`, `name`, `email`, `password`, `company`, `position`, `memo`, `photo_path`, `created_at`) VALUES
(6, 'アントニオ猪木', 'inoki@anton', '$2y$10$YhnL6FYjRksO35MpHJ13EejHSvQzMOSFLHqmXXqADF2o.trhgf8Sy', '新日本プロレス', '創業者', 'test', './uploaded_photos/311f51b75df890292d1c2e346d221817.jpg', '2025-01-10 05:25:02'),
(7, 'ジャイアント馬場', 'baba@baba', '$2y$10$rotSveUDpwxkeZL.xkf3K.VEsjMwE6/6YFSARIRKRNEoEtY15kW5.', '全日本プロレス', '社長', '', './uploaded_photos/d3b8afd236b6b8e4aadb6e4a74d34b0e.jpg', '2025-01-10 05:46:51'),
(8, 'test', 'test@test', '$2y$10$KibpztQrTeiHIjpxkP579OA14v9wSOGE28iWDHIbxJzdAxjrNBSHS', 'test', 'test', 'test', './uploaded_photos/cc840119f4cc4f4a3030b62e7f868e65.png', '2025-01-10 05:51:27'),
(9, '大野拓', 'taku_oono@node-bee.com', '$2y$10$i7aJbyc3AQ028dhmFgE/hOEV2fbVvEvSR5UzTqRBtytjOriTXY3wO', 'gsacademy', '学生', 'test', './uploaded_photos/cf67bf064233929f2d8343ba8be6eab9.png', '2025-01-10 06:04:49');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `registrations`
--
ALTER TABLE `registrations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
