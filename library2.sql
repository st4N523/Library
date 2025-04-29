-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 03, 2024 at 07:49 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library2`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `phone_number` varchar(20) DEFAULT NULL,
  `admin_type` varchar(50) DEFAULT 'normal_admin',
  `AdminStatus` enum('Active','Block','Closed') NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `email`, `first_name`, `last_name`, `password`, `created_at`, `phone_number`, `admin_type`, `AdminStatus`) VALUES
(1, 'e@gmail.com', 'a', 'a', 'Eric134679.', '2024-06-03 22:28:49', '1212121212', 'normal_admin', 'Active'),
(2, 'ericching342@gmail.com', 'Eric', 'Ching Khai Jie', 'eric134679E.', '2024-06-04 02:46:48', '0183862559', 'normal_admin', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `publisher` varchar(255) NOT NULL,
  `publication_year` int(4) NOT NULL,
  `isbn` varchar(255) NOT NULL,
  `available` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `title`, `author`, `publisher`, `publication_year`, `isbn`, `available`) VALUES
(1, 'ER+ric', '1', '1', 1990, '1', 1),
(2, 'Eric', 'Eric', '1990', 2025, '12', 1),
(3, 'The Great ', 'F. Scott Fitzgerald', 'Scribner', 1925, '9780743273565', 1),
(4, 'To Kill a Mockingbird', 'Harper Lee', 'J.B. Lippincott & Co.', 1960, '9780061120084', 1),
(5, '1984', 'George Orwell', 'Secker & Warburg', 1949, '9780451524935', 1),
(6, 'The Catcher in the Rye', 'J.D. Salinger', 'Little, Brown and Company', 1951, '9780316769488', 1),
(7, 'Harry Potter and the Philosopher\'s Stone', 'J.K. Rowling', 'Bloomsbury Publishing', 1997, '9781408855652', 1),
(8, 'Pride and Prejudice', 'Jane Austen', 'T. Egerton, Whitehall', 1813, '9780141439518', 1),
(9, 'The Lord of the Rings', 'J.R.R. Tolkien', 'George Allen & Unwin', 1954, '9780261103252', 1),
(10, 'The Hobbit', 'J.R.R. Tolkien', 'George Allen & Unwin', 1937, '9780547928227', 1),
(11, 'Moby-Dick', 'Herman Melville', 'Richard Bentley', 1851, '9780142437247', 1),
(12, 'War and Peace', 'Leo Tolstoy', 'The Russian Messenger', 1869, '9780140447934', 1);

-- --------------------------------------------------------

--
-- Table structure for table `borrow`
--

CREATE TABLE `borrow` (
  `borrow_id` int(11) NOT NULL,
  `borrower_name` varchar(255) NOT NULL,
  `borrower_nric` int(12) NOT NULL,
  `borrow_date` date NOT NULL,
  `return_date` date NOT NULL,
  `book_id` int(11) DEFAULT NULL,
  `returned` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrow`
--

INSERT INTO `borrow` (`borrow_id`, `borrower_name`, `borrower_nric`, `borrow_date`, `return_date`, `book_id`, `returned`) VALUES
(1, 'John Doe', 2147483647, '2024-06-01', '2024-06-15', 1, 0),
(2, 'Alice Johnson', 2147483647, '2024-06-10', '2024-06-25', 2, 0),
(3, 'Bob Brown', 2147483647, '2024-06-12', '2024-06-28', 3, 0),
(4, 'Charlie Davis', 2147483647, '2024-06-15', '2024-07-01', 4, 0),
(5, 'David Green', 2147483647, '2024-06-18', '2024-06-22', 5, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`);

--
-- Indexes for table `borrow`
--
ALTER TABLE `borrow`
  ADD PRIMARY KEY (`borrow_id`),
  ADD KEY `book_id` (`book_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `borrow`
--
ALTER TABLE `borrow`
  MODIFY `borrow_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `borrow`
--
ALTER TABLE `borrow`
  ADD CONSTRAINT `borrow_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
