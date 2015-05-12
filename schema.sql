-- phpMyAdmin SQL Dump
-- version 4.0.5
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1:3306

-- Generation Time: May 11, 2015 at 08:59 PM
-- Server version: 5.5.33
-- PHP Version: 5.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `l8-02`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrator`
--

CREATE TABLE `administrator` (
  `ID_Uzytkownika` int(11) NOT NULL,
  `Konto_Id_konta` int(11) NOT NULL,
  KEY `Administrator_Konto_FK` (`Konto_Id_konta`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `artykul`
--

CREATE TABLE `artykul` (
  `ID_Artykul` int(11) NOT NULL,
  `ID_Konferencji` int(11) NOT NULL,
  `ID_Uzytkownika` int(11) NOT NULL,
  `Tytul` varchar(30) NOT NULL,
  `Tresc` varchar(70) NOT NULL,
  `Konferencja_ID_Konferencji` int(11) NOT NULL,
  `Data_Utworzenia` date DEFAULT NULL,
  PRIMARY KEY (`ID_Artykul`),
  KEY `FK_ASS_6` (`Konferencja_ID_Konferencji`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `konferencja`
--

CREATE TABLE `konferencja` (
  `ID_Konferencji` int(11) NOT NULL,
  `Nazwa` varchar(30) NOT NULL,
  `Data` date NOT NULL,
  `Miejsce` varchar(30) NOT NULL,
  `Limit_Miejsc` int(11) NOT NULL,
  `Program` varchar(70) NOT NULL,
  `Termin_Zgloszen` date NOT NULL,
  `Koszt` int(11) NOT NULL,
  `Organizator_ID_Organizator` int(11) NOT NULL,
  `Ilosc_Ocen` int(11) DEFAULT NULL,
  `Suma_Ocen` int(11) DEFAULT NULL,
  `Data_Utworzenia` date DEFAULT NULL,
  PRIMARY KEY (`ID_Konferencji`),
  KEY `Konferencja_Organizator_FKv1` (`Organizator_ID_Organizator`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `konto`
--

CREATE TABLE `konto` (
  `Id_konta` int(11) NOT NULL,
  `Login` varchar(70) NOT NULL,
  `Hasło` varchar(30) NOT NULL,
  `Imie` varchar(20) NOT NULL,
  `Nazwisko` varchar(40) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Nr_Telefonu` int(11) DEFAULT NULL,
  `Data_Utworzenia` date DEFAULT NULL,
  PRIMARY KEY (`Id_konta`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ocena`
--

CREATE TABLE `ocena` (
  `ID_Oceny` int(11) NOT NULL,
  `Ocena` int(11) DEFAULT NULL,
  `Opis` varchar(70) DEFAULT NULL,
  PRIMARY KEY (`ID_Oceny`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `organizator`
--

CREATE TABLE `organizator` (
  `ID_Organizator` int(11) NOT NULL,
  `ID_Uzytkownika` int(11) NOT NULL,
  `ID_Konferencji` int(11) NOT NULL,
  `ID_Recenzent` int(11) NOT NULL,
  `Konto_Id_konta` int(11) NOT NULL,
  PRIMARY KEY (`ID_Organizator`),
  KEY `Organizator_Konto_FKv1` (`Konto_Id_konta`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `recenzent`
--

CREATE TABLE `recenzent` (
  `ID_Recenzent` int(11) NOT NULL,
  `ID_Recenzja` int(11) NOT NULL,
  `ID_Uzytkownika` int(11) NOT NULL,
  `Konto_Id_konta` int(11) NOT NULL,
  `Ocena_ID_Oceny` int(11) NOT NULL,
  `ID_Artykulu` int(11) DEFAULT NULL,
  `Artykul_ID_Artykul` int(11) NOT NULL,
  `ID_Konferencja` int(11) DEFAULT NULL,
  `Konferencja_ID_Konferencji` int(11) NOT NULL,
  PRIMARY KEY (`ID_Recenzent`),
  UNIQUE KEY `Recenzent__IDX` (`Ocena_ID_Oceny`),
  KEY `Recenzent_Artykul_FKv1` (`Artykul_ID_Artykul`),
  KEY `Recenzent_Konferencja_FKv1` (`Konferencja_ID_Konferencji`),
  KEY `Recenzent_Konto_FKv1` (`Konto_Id_konta`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `recenzja`
--

CREATE TABLE `recenzja` (
  `ID_Recenzja` int(11) NOT NULL,
  `Tresc` varchar(70) NOT NULL,
  `Recenzent_ID_Recenzent` int(11) NOT NULL,
  `Ocena_ID_Oceny` int(11) NOT NULL,
  `ID_Artykul` int(11) DEFAULT NULL,
  `Artykul_ID_Artykul` int(11) NOT NULL,
  `Data_Utworzenia` date DEFAULT NULL,
  `Opublikowana` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID_Recenzja`),
  KEY `Recenzja_Artykul_FKv1` (`Artykul_ID_Artykul`),
  KEY `Recenzja_Ocena_FKv1` (`Ocena_ID_Oceny`),
  KEY `Recenzja_Recenzent_FKv1` (`Recenzent_ID_Recenzent`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tematy`
--

CREATE TABLE `tematy` (
  `Id_tematu` int(11) NOT NULL,
  `Opis` varchar(70) DEFAULT NULL,
  `Artykul_ID_Artykul` int(11) NOT NULL,
  PRIMARY KEY (`Id_tematu`),
  KEY `Tematy_Artykul_FKv1` (`Artykul_ID_Artykul`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `temat_konferencji`
--

CREATE TABLE `temat_konferencji` (
  `Id_tematu_konferencji` int(11) NOT NULL,
  `Konferencja_ID_Konferencji` int(11) NOT NULL,
  `Tematy_Id_tematu` int(11) NOT NULL,
  PRIMARY KEY (`Id_tematu_konferencji`),
  KEY `Temat_konferencji_Konferencja_FKv1` (`Konferencja_ID_Konferencji`),
  KEY `Temat_konferencji_Tematy_FKv1` (`Tematy_Id_tematu`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `uczestnik`
--

CREATE TABLE `uczestnik` (
  `ID_Uzytkownika` int(11) NOT NULL,
  `Konto_Id_konta` int(11) NOT NULL,
  `Konferencja_ID_Konferencji` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID_Uzytkownika`),
  KEY `Uczestnik_Konferencja_FKv1` (`Konferencja_ID_Konferencji`),
  KEY `Uczestnik_Konto_FKv1` (`Konto_Id_konta`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
