-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 04, 2025 at 11:26 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `newspocket`
--

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `id` int UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `photo_profile` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `auth_token` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`id`, `nama`, `email`, `photo_profile`, `password`, `auth_token`, `created_at`) VALUES
(1, 'Kvien', 'kvien@gmail.com', NULL, 'password', NULL, '2025-12-02 22:05:13'),
(2, 'dasdsa', 'sdad@gmail.com', NULL, '$2y$10$KsM35OCQ6.iEWSpJ.5ddZ.EqYW7MKfsVcGjwbSnQQD1M7WJsdyJ..', NULL, '2025-12-02 22:20:32'),
(3, 'kevin', 'kevin@gmail.com', '1764704167_f2ac9f2a5e9bdfed866a.jpg', '$2y$10$9ZzeUiHwNXTAtn0tMPzGd.ByXIXnU69eGWOD0ytcCi3CHL8sMv3cS', '8ad50c9aeda5d211e8fc06f43fd1a8cd', '2025-12-02 22:20:50'),
(4, 'dasda', 'dasds@gmail.com', NULL, '$2y$10$B7NjtnEOEUOoFQLN9uvIfO/2ZJVc94zFQu28XXCipDC6.1rS2MBDG', NULL, '2025-12-02 23:02:57'),
(5, 'dasda', 'kasep@gmail.com', NULL, '$2y$10$i5aY4QjhxoOZqRn0L/9mVeJaWKoYvBfDbihO1YA71akq38IHsJptK', NULL, '2025-12-02 23:05:46'),
(6, 'Naufal', 'naufal@gmail.com', '1764702346_bf279bc48cc62299b44b.jpg', '$2y$10$NCcRqEnrKLhg1OMs9oGbSO4O79sUKUcOMAHfLNh3qw/5Z3WvLqZva', 'c903f59df0700862f5afa73f6b0bbd32', '2025-12-03 01:32:18');

-- --------------------------------------------------------

--
-- Table structure for table `news_bookmarks`
--

CREATE TABLE `news_bookmarks` (
  `id` int UNSIGNED NOT NULL,
  `member_id` int UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `source_name` varchar(100) DEFAULT NULL,
  `url_image` text,
  `article_url` text NOT NULL,
  `personal_notes` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `news_bookmarks`
--

INSERT INTO `news_bookmarks` (`id`, `member_id`, `title`, `source_name`, `url_image`, `article_url`, `personal_notes`, `created_at`) VALUES
(1, 3, 'BNN dan Interpol Tangkap Buron Sabu Rp 5 T Dewi Astutik', 'detikNews', 'https://awsimages.detik.net.id/api/wm/2025/06/01/dewi-astutik-dok-ist-1748749574643_169.png?wid=54&w=1200&v=1&t=jpeg', 'https://news.detik.com/berita/d-8238731/bnn-dan-interpol-tangkap-buron-sabu-rp-5-t-dewi-astutik', 'Keren', '2025-12-02 22:28:16'),
(5, 3, 'MA-RI Tetapkan 19 Satuan Kerja Menuju WBK Tahun 2025, Siapa Saja Mereka?', 'Dandapala Digital', 'https://dandapala.com/assets/back/article/img/compress/ma-ri-tetapkan-19-satuan-kerja-menuju-wbk-tahun-2025-siapa-saja-mereka-thumbnail.jpeg', 'https://dandapala.com/article/detail/ma-ri-tetapkan-19-satuan-kerja-menuju-wbk-tahun-2025-siapa-saja-mereka', 'Waduh', '2025-12-03 01:15:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `news_bookmarks`
--
ALTER TABLE `news_bookmarks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_member` (`member_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `news_bookmarks`
--
ALTER TABLE `news_bookmarks`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `news_bookmarks`
--
ALTER TABLE `news_bookmarks`
  ADD CONSTRAINT `fk_member` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
