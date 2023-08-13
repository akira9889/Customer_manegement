-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: db
-- 生成日時: 2023 年 8 月 11 日 05:28
-- サーバのバージョン： 8.0.34
-- PHP のバージョン: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `customer_manegement`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `companies`
--

CREATE TABLE `companies` (
  `id` int NOT NULL,
  `name` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(20) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `companies`
--

INSERT INTO `companies` (`id`, `name`, `password`) VALUES
(72, 'sawascle', 'password'),
(73, '石井運送', 'password');

-- --------------------------------------------------------

--
-- テーブルの構造 `customers`
--

CREATE TABLE `customers` (
  `id` int NOT NULL,
  `shop_id` int NOT NULL,
  `last_name` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `first_name` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `last_kana` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `first_kana` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `tel` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `birthday` date NOT NULL,
  `gender` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `information` longtext COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `prefectures`
--

CREATE TABLE `prefectures` (
  `id` tinyint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `name_kana` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `prefectures`
--

INSERT INTO `prefectures` (`id`, `name`, `name_kana`) VALUES
(1, '北海道', 'ホッカイドウ'),
(2, '青森県', 'アオモリケン'),
(3, '岩手県', 'イワテケン'),
(4, '宮城県', 'ミヤギケン'),
(5, '秋田県', 'アキタケン'),
(6, '山形県', 'ヤマガタケン'),
(7, '福島県', 'フクシマケン'),
(8, '茨城県', 'イバラキケン'),
(9, '栃木県', 'トチギケン'),
(10, '群馬県', 'グンマケン'),
(11, '埼玉県', 'サイタマケン'),
(12, '千葉県', 'チバケン'),
(13, '東京都', 'トウキョウト'),
(14, '神奈川県', 'カナガワケン'),
(15, '新潟県', 'ニイガタケン'),
(16, '富山県', 'トヤマケン'),
(17, '石川県', 'イシカワケン'),
(18, '福井県', 'フクイケン'),
(19, '山梨県', 'ヤマナシケン'),
(20, '長野県', 'ナガノケン'),
(21, '岐阜県', 'ギフケン'),
(22, '静岡県', 'シズオカケン'),
(23, '愛知県', 'アイチケン'),
(24, '三重県', 'ミエケン'),
(25, '滋賀県', 'シガケン'),
(26, '京都府', 'キョウトフ'),
(27, '大阪府', 'オオサカフ'),
(28, '兵庫県', 'ヒョウゴケン'),
(29, '奈良県', 'ナラケン'),
(30, '和歌山県', 'ワカヤマケン'),
(31, '鳥取県', 'トットリケン'),
(32, '島根県', 'シマネケン'),
(33, '岡山県', 'オカヤマケン'),
(34, '広島県', 'ヒロシマケン'),
(35, '山口県', 'ヤマグチケン'),
(36, '徳島県', 'トクシマケン'),
(37, '香川県', 'カガワケン'),
(38, '愛媛県', 'エヒメケン'),
(39, '高知県', 'コウチケン'),
(40, '福岡県', 'フクオカケン'),
(41, '佐賀県', 'サガケン'),
(42, '長崎県', 'ナガサキケン'),
(43, '熊本県', 'クマモトケン'),
(44, '大分県', 'オオイタケン'),
(45, '宮崎県', 'ミヤザキケン'),
(46, '鹿児島県', 'カゴシマケン'),
(47, '沖縄県', 'オキナワケン');

-- --------------------------------------------------------

--
-- テーブルの構造 `shops`
--

CREATE TABLE `shops` (
  `id` int NOT NULL,
  `company_id` int NOT NULL,
  `prefecture_id` int NOT NULL,
  `area` varchar(10) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `shops`
--

INSERT INTO `shops` (`id`, `company_id`, `prefecture_id`, `area`) VALUES
(72, 72, 12, '市川'),
(73, 72, 12, '塩浜'),
(74, 73, 4, '仙台');

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `shop_id` int NOT NULL,
  `name` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `admin_state` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`id`, `shop_id`, `name`, `password`, `admin_state`) VALUES
(16, 72, '岩澤明', 'password', 2),
(20, 72, '岩澤公人', 'password', 2),
(21, 72, '岩澤元', 'password', 2);

-- --------------------------------------------------------

--
-- テーブルの構造 `visit_histories`
--

CREATE TABLE `visit_histories` (
  `id` int NOT NULL,
  `shop_id` int NOT NULL,
  `user_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `date` date NOT NULL,
  `price` int NOT NULL,
  `memo` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `visit_histories`
--

INSERT INTO `visit_histories` (`id`, `shop_id`, `user_id`, `customer_id`, `date`, `price`, `memo`) VALUES
(28, 72, 0, 16853, '2023-02-24', 15000, '足の怪我をしました'),
(29, 72, 0, 16854, '2023-02-27', 90000, '');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `prefectures`
--
ALTER TABLE `prefectures`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `shops`
--
ALTER TABLE `shops`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `visit_histories`
--
ALTER TABLE `visit_histories`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- テーブルの AUTO_INCREMENT `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `shops`
--
ALTER TABLE `shops`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- テーブルの AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- テーブルの AUTO_INCREMENT `visit_histories`
--
ALTER TABLE `visit_histories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
