-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 14, 2026 at 11:58 PM
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
-- Database: `cavoskill`
--

-- --------------------------------------------------------

--
-- Table structure for table `distributer`
--

CREATE TABLE `distributer` (
  `id_distributer` varchar(50) NOT NULL,
  `naziv_distributera` varchar(50) NOT NULL,
  `tip` enum('velika kompanija','pravno lice','obrazovna institucija','preduzetnik','srednje preduzece','malo preduzece') NOT NULL,
  `email_distributera` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `distributer`
--

INSERT INTO `distributer` (`id_distributer`, `naziv_distributera`, `tip`, `email_distributera`) VALUES
('D1', 'SkillForge Academy', 'velika kompanija', 'kontakt@skillforge.com'),
('D2', 'Business Pro Institute', 'velika kompanija', 'office@businesspro.rs'),
('D3', 'Digital Leaders Hub', 'velika kompanija', 'info@digitalleaders.com'),
('D4', 'Future Managers', 'velika kompanija', 'contact@futuremanagers.rs'),
('D5', 'Elite Coaching Group', 'velika kompanija', 'elite@coaching.com'),
('D6', 'Project Masters', 'velika kompanija', 'pm@projectmasters.com'),
('D7', 'SoftSkills Lab', 'velika kompanija', 'hello@softskillslab.com');

-- --------------------------------------------------------

--
-- Table structure for table `korisnik`
--

CREATE TABLE `korisnik` (
  `id_korisnik` varchar(50) NOT NULL,
  `ime_i_prezime` varchar(50) NOT NULL,
  `email_korisnika` varchar(50) NOT NULL,
  `lozinka_korisnika` varchar(50) NOT NULL,
  `datum_rodjenja` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kupovina`
--

CREATE TABLE `kupovina` (
  `sifra_kupovine` varchar(50) NOT NULL,
  `id_korisnik` varchar(50) NOT NULL,
  `datum_kupovine` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kupovina_kursa`
--

CREATE TABLE `kupovina_kursa` (
  `sifra_kupovine` varchar(50) NOT NULL,
  `sifra_kursa` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kurs`
--

CREATE TABLE `kurs` (
  `sifra_kursa` varchar(50) NOT NULL,
  `naziv_kursa` varchar(50) NOT NULL,
  `kategorija` enum('upravljanje projektima','menadzment i liderstvo','komunikacione veštine','liderstvo','licni razvoj','strateško planiranje','coaching i mentoring') NOT NULL,
  `nivo` enum('osnovni','srednji','napredni','') NOT NULL,
  `cena` int(11) NOT NULL,
  `slika_kursa` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kurs`
--

INSERT INTO `kurs` (`sifra_kursa`, `naziv_kursa`, `kategorija`, `nivo`, `cena`, `slika_kursa`) VALUES
('KS01', 'Upravljanje projektima', 'upravljanje projektima', 'srednji', 125, 'S01.jpg'),
('KS02', 'Digitalni marketing', 'komunikacione veštine', 'osnovni', 100, 'S02.jpg'),
('KS03', 'Liderstvo', 'liderstvo', 'napredni', 130, 'S03.jpg'),
('KS04', 'Finansijska pismenost', 'menadzment i liderstvo', 'srednji', 80, 'S04.jpg'),
('KS05', 'Excel za biznis', 'licni razvoj', 'osnovni', 110, 'S05.jpg'),
('KS06', 'Pregovaracke vestine', 'komunikacione veštine', 'srednji', 70, 'S06.jpg'),
('KS07', 'Strategijski menadzment', 'strateško planiranje', 'napredni', 145, 'S07.jpg'),
('KS08', 'Osnove preduzetnistva', 'coaching i mentoring', 'srednji', 200, 'S08.jpg'),
('KS09', 'Upravljanje vremenom', 'licni razvoj', 'srednji', 160, 'S09.jpg'),
('KS10', 'Analiza podataka za menadzere', 'menadzment i liderstvo', 'napredni', 155, 'S10.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `kurs_distributer`
--

CREATE TABLE `kurs_distributer` (
  `sifra_kursa` varchar(50) NOT NULL,
  `id_distributer` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kurs_distributer`
--

INSERT INTO `kurs_distributer` (`sifra_kursa`, `id_distributer`) VALUES
('KS01', 'D3'),
('KS02', 'D1'),
('KS03', 'D6'),
('KS04', 'D2'),
('KS05', 'D5'),
('KS06', 'D1'),
('KS07', 'D7'),
('KS08', 'D4'),
('KS09', 'D2'),
('KS10', 'D3');

-- --------------------------------------------------------

--
-- Table structure for table `sertifikat`
--

CREATE TABLE `sertifikat` (
  `sifra_sertifikata` varchar(50) NOT NULL,
  `sifra_kursa` varchar(50) NOT NULL,
  `dokument` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sertifikat`
--

INSERT INTO `sertifikat` (`sifra_sertifikata`, `sifra_kursa`, `dokument`) VALUES
('SERT01', 'KS01', 'KS01.pdf'),
('SERT02', 'KS02', 'KS02.pdf'),
('SERT03', 'KS03', 'KS03.pdf'),
('SERT04', 'KS04', 'KS04.pdf'),
('SERT05', 'KS05', 'KS05.pdf'),
('SERT06', 'KS06', 'KS06.pdf'),
('SERT07', 'KS07', 'KS07.pdf'),
('SERT08', 'KS08', 'KS08.pdf'),
('SERT09', 'KS09', 'KS09.pdf'),
('SERT10', 'KS10', 'KS10.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `sertifikat_korisnik`
--

CREATE TABLE `sertifikat_korisnik` (
  `sifra_sertifikata` varchar(50) NOT NULL,
  `id_korisnik` varchar(50) NOT NULL,
  `datum_izdavanja` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `zavrsen_kurs`
--

CREATE TABLE `zavrsen_kurs` (
  `id_korisnik` varchar(50) NOT NULL,
  `sifra_kursa` varchar(50) NOT NULL,
  `datum_zavrsetka` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `distributer`
--
ALTER TABLE `distributer`
  ADD PRIMARY KEY (`id_distributer`);

--
-- Indexes for table `korisnik`
--
ALTER TABLE `korisnik`
  ADD PRIMARY KEY (`id_korisnik`);

--
-- Indexes for table `kupovina`
--
ALTER TABLE `kupovina`
  ADD PRIMARY KEY (`sifra_kupovine`),
  ADD KEY `id_korisnik` (`id_korisnik`);

--
-- Indexes for table `kupovina_kursa`
--
ALTER TABLE `kupovina_kursa`
  ADD KEY `sifra_kupovine` (`sifra_kupovine`),
  ADD KEY `sifra_kursa` (`sifra_kursa`);

--
-- Indexes for table `kurs`
--
ALTER TABLE `kurs`
  ADD PRIMARY KEY (`sifra_kursa`);

--
-- Indexes for table `kurs_distributer`
--
ALTER TABLE `kurs_distributer`
  ADD KEY `sifra_kursa` (`sifra_kursa`),
  ADD KEY `id_distributera` (`id_distributer`);

--
-- Indexes for table `sertifikat`
--
ALTER TABLE `sertifikat`
  ADD PRIMARY KEY (`sifra_sertifikata`),
  ADD KEY `sifra_kursa` (`sifra_kursa`);

--
-- Indexes for table `sertifikat_korisnik`
--
ALTER TABLE `sertifikat_korisnik`
  ADD KEY `sifra_sertifikata` (`sifra_sertifikata`),
  ADD KEY `id_korisnik` (`id_korisnik`);

--
-- Indexes for table `zavrsen_kurs`
--
ALTER TABLE `zavrsen_kurs`
  ADD KEY `id_korisnik` (`id_korisnik`),
  ADD KEY `sifra_kursa` (`sifra_kursa`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
