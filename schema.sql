-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Czas generowania: 24 Maj 2015, 14:18
-- Wersja serwera: 5.6.24
-- Wersja PHP: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Baza danych: `l8-02`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `administrator`
--

CREATE TABLE IF NOT EXISTS `administrator` (
  `ID_Administrator` int(11) NOT NULL,
  `ID_Konto` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `administrator`
--

INSERT INTO `administrator` (`ID_Administrator`, `ID_Konto`) VALUES
(2, 7);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `artykul`
--

CREATE TABLE IF NOT EXISTS `artykul` (
  `ID_Artykul` int(11) NOT NULL,
  `ID_Konferencja` int(11) NOT NULL,
  `ID_Konto` int(11) NOT NULL,
  `Tytul` varchar(30) NOT NULL,
  `Tresc` varchar(70) NOT NULL,
  `Data_Utworzenia` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Opublikowany` tinyint(1) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `artykul`
--

INSERT INTO `artykul` (`ID_Artykul`, `ID_Konferencja`, `ID_Konto`, `Tytul`, `Tresc`, `Data_Utworzenia`, `Opublikowany`) VALUES
(2, 3, 5, 'Testowy art', 'i blab  lacsf ', '2015-05-15 18:05:40', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `artykul_recenzent`
--

CREATE TABLE IF NOT EXISTS `artykul_recenzent` (
  `ID_Artykul` int(11) NOT NULL,
  `ID_Recenzent` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Zrzut danych tabeli `artykul_recenzent`
--

INSERT INTO `artykul_recenzent` (`ID_Artykul`, `ID_Recenzent`) VALUES
(2, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `konferencja`
--

CREATE TABLE IF NOT EXISTS `konferencja` (
  `ID_Konferencja` int(11) NOT NULL,
  `Nazwa` varchar(30) NOT NULL,
  `Data` datetime NOT NULL,
  `Miejsce` varchar(30) NOT NULL,
  `Limit_Miejsc` int(11) NOT NULL,
  `Program` text NOT NULL,
  `Termin_Zgloszen` datetime NOT NULL,
  `Koszt` int(11) DEFAULT '0',
  `ID_Organizator` int(11) NOT NULL,
  `Ilosc_Ocen` int(11) DEFAULT '0',
  `Suma_Ocen` int(11) DEFAULT '0',
  `Data_Utworzenia` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `konferencja`
--

INSERT INTO `konferencja` (`ID_Konferencja`, `Nazwa`, `Data`, `Miejsce`, `Limit_Miejsc`, `Program`, `Termin_Zgloszen`, `Koszt`, `ID_Organizator`, `Ilosc_Ocen`, `Suma_Ocen`, `Data_Utworzenia`) VALUES
(1, 'Pierwsza konferencja', '2015-06-01 10:10:44', 'Rzeszów', 12, '08:30 – 09:30 Rejestracja uczestników. miejsce: Gmach "Starej Biblioteki UW"<br>\r\n09:30 – 09:45 Uroczyste otwarcie konferencji<br>\r\n16:00 – 19:00 Żarełko w Hotelu "Gromada Chłopa"<br>\r\n19:00 – 21:00 Welcome reception. miejsce: "Gromada Chłopa"', '2015-05-31 10:19:38', 0, 1, 0, 0, '2015-05-13 18:19:39');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `konto`
--

CREATE TABLE IF NOT EXISTS `konto` (
  `ID_Konto` int(11) NOT NULL,
  `Login` varchar(70) NOT NULL,
  `Haslo` varchar(32) NOT NULL,
  `Imie` varchar(20) NOT NULL,
  `Nazwisko` varchar(40) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Nr_Telefonu` int(11) DEFAULT NULL,
  `Data_Utworzenia` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `konto`
--

INSERT INTO `konto` (`ID_Konto`, `Login`, `Haslo`, `Imie`, `Nazwisko`, `Email`, `Nr_Telefonu`, `Data_Utworzenia`) VALUES
(2, 'test', '827ccb0eea8a706c4c34a16891f84e7b', 'Jakiś', 'Ktoś', 'jakiś@email.coś', NULL, '2015-05-13 17:33:48'),
(3, 'qwe', '76d80224611fc919a5d54f0ff9fba446', 'qwe', 'qwe', 'qwe', 0, '2015-05-15 11:31:36'),
(4, 'asd', '7815696ecbf1c96e6894b779456d330e', 'asd', 'asd', 'asd', 0, '2015-05-15 13:56:19'),
(5, 'ucz', '827ccb0eea8a706c4c34a16891f84e7b', 'Mano', 'Dano', 'dan@wp.pl', NULL, '2015-05-20 18:58:19'),
(6, 'org', '827ccb0eea8a706c4c34a16891f84e7b', 'Den', 'Men', 'dds@wp.pl', NULL, '2015-05-20 18:58:38'),
(7, 'adm', '827ccb0eea8a706c4c34a16891f84e7b', 'Pilo', 'Asro', 'egdg@wp.pl', NULL, '2015-05-20 18:59:20');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `ocena`
--

CREATE TABLE IF NOT EXISTS `ocena` (
  `ID_Ocena` int(11) NOT NULL,
  `Ocena` int(11) NOT NULL,
  `Opis` text NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `ocena`
--

INSERT INTO `ocena` (`ID_Ocena`, `Ocena`, `Opis`) VALUES
(9, 2, 'recenzja'),
(10, 2, 'recenzja');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `organizator`
--

CREATE TABLE IF NOT EXISTS `organizator` (
  `ID_Organizator` int(11) NOT NULL,
  `ID_Konto` int(11) NOT NULL,
  `ID_Konferencja` int(11) NOT NULL,
  `ID_Recenzent` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `organizator`
--

INSERT INTO `organizator` (`ID_Organizator`, `ID_Konto`, `ID_Konferencja`, `ID_Recenzent`) VALUES
(1, 6, 1, 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `recenzent`
--

CREATE TABLE IF NOT EXISTS `recenzent` (
  `ID_Recenzent` int(11) NOT NULL,
  `ID_Konto` int(11) NOT NULL,
  `ID_Konferencja` int(11) NOT NULL,
  `Oceniono` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `recenzent`
--

INSERT INTO `recenzent` (`ID_Recenzent`, `ID_Konto`, `ID_Konferencja`, `Oceniono`) VALUES
(1, 2, 3, 0),
(2, 3, 3, 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `recenzja`
--

CREATE TABLE IF NOT EXISTS `recenzja` (
  `ID_Recenzja` int(11) NOT NULL,
  `Tresc` text NOT NULL,
  `ID_Recenzent` int(11) NOT NULL,
  `ID_Artykul` int(11) NOT NULL,
  `ID_Ocena` int(11) NOT NULL,
  `Data_Utworzenia` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Opublikowana` tinyint(1) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `recenzja`
--

INSERT INTO `recenzja` (`ID_Recenzja`, `Tresc`, `ID_Recenzent`, `ID_Artykul`, `ID_Ocena`, `Data_Utworzenia`, `Opublikowana`) VALUES
(8, 'siemanko', 1, 2, 9, '2015-05-20 18:26:49', 1),
(9, 'inna recenzja', 2, 2, 10, '2015-05-20 18:26:49', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `tematy`
--

CREATE TABLE IF NOT EXISTS `tematy` (
  `ID_Tematy` int(11) NOT NULL,
  `ID_Konto` int(11) NOT NULL,
  `Opis` varchar(128) NOT NULL,
  `Zaakceptowany` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `tematy`
--

INSERT INTO `tematy` (`ID_Tematy`, `ID_Konto`, `Opis`, `Zaakceptowany`) VALUES
(11, 5, 'przykładowy temat', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `temat_artykulu`
--

CREATE TABLE IF NOT EXISTS `temat_artykulu` (
  `ID_Temat_Artykulu` int(11) NOT NULL,
  `ID_Tematy` int(11) NOT NULL,
  `Temat` varchar(30) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `temat_artykulu`
--

INSERT INTO `temat_artykulu` (`ID_Temat_Artykulu`, `ID_Tematy`, `Temat`) VALUES
(6, 11, 'Hiha hiah'),
(7, 11, 'Blemen'),
(8, 11, 'Coś tam');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `temat_konferencji`
--

CREATE TABLE IF NOT EXISTS `temat_konferencji` (
  `ID_Temat_Konferencji` int(11) NOT NULL,
  `ID_Konferencja` int(11) NOT NULL,
  `ID_Tematy` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uczestnik`
--

CREATE TABLE IF NOT EXISTS `uczestnik` (
  `ID_Uczestnik` int(11) NOT NULL,
  `ID_Konto` int(11) NOT NULL,
  `ID_Konferencja` int(11) NOT NULL,
  `Zaakceptowany` tinyint(1) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `uczestnik`
--

INSERT INTO `uczestnik` (`ID_Uczestnik`, `ID_Konto`, `ID_Konferencja`, `Zaakceptowany`) VALUES
(1, 5, 1, 1);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indexes for table `administrator`
--
ALTER TABLE `administrator`
  ADD PRIMARY KEY (`ID_Administrator`), ADD KEY `Administrator_Konto_FK` (`ID_Konto`);

--
-- Indexes for table `artykul`
--
ALTER TABLE `artykul`
  ADD PRIMARY KEY (`ID_Artykul`), ADD KEY `FK_ASS_6` (`ID_Konferencja`);

--
-- Indexes for table `artykul_recenzent`
--
ALTER TABLE `artykul_recenzent`
  ADD PRIMARY KEY (`ID_Artykul`,`ID_Recenzent`);

--
-- Indexes for table `konferencja`
--
ALTER TABLE `konferencja`
  ADD PRIMARY KEY (`ID_Konferencja`), ADD KEY `Konferencja_Organizator_FKv1` (`ID_Organizator`);

--
-- Indexes for table `konto`
--
ALTER TABLE `konto`
  ADD PRIMARY KEY (`ID_Konto`);

--
-- Indexes for table `ocena`
--
ALTER TABLE `ocena`
  ADD PRIMARY KEY (`ID_Ocena`);

--
-- Indexes for table `organizator`
--
ALTER TABLE `organizator`
  ADD PRIMARY KEY (`ID_Organizator`), ADD KEY `Organizator_Konto_FKv1` (`ID_Konto`);

--
-- Indexes for table `recenzent`
--
ALTER TABLE `recenzent`
  ADD PRIMARY KEY (`ID_Recenzent`), ADD KEY `Recenzent_Konferencja_FKv1` (`ID_Konferencja`), ADD KEY `Recenzent_Konto_FKv1` (`ID_Konto`);

--
-- Indexes for table `recenzja`
--
ALTER TABLE `recenzja`
  ADD PRIMARY KEY (`ID_Recenzja`), ADD KEY `Recenzja_Ocena_FKv1` (`ID_Ocena`), ADD KEY `Recenzja_Recenzent_FKv1` (`ID_Recenzent`), ADD KEY `ID_Artykul_fk` (`ID_Artykul`);

--
-- Indexes for table `tematy`
--
ALTER TABLE `tematy`
  ADD PRIMARY KEY (`ID_Tematy`), ADD KEY `ID_Konto` (`ID_Konto`);

--
-- Indexes for table `temat_artykulu`
--
ALTER TABLE `temat_artykulu`
  ADD PRIMARY KEY (`ID_Temat_Artykulu`), ADD KEY `ID_Tematy` (`ID_Tematy`);

--
-- Indexes for table `temat_konferencji`
--
ALTER TABLE `temat_konferencji`
  ADD PRIMARY KEY (`ID_Temat_Konferencji`), ADD KEY `Temat_konferencji_Konferencja_FKv1` (`ID_Konferencja`), ADD KEY `Temat_konferencji_Tematy_FKv1` (`ID_Tematy`);

--
-- Indexes for table `uczestnik`
--
ALTER TABLE `uczestnik`
  ADD PRIMARY KEY (`ID_Uczestnik`), ADD KEY `Uczestnik_Konferencja_FKv1` (`ID_Konferencja`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `administrator`
--
ALTER TABLE `administrator`
  MODIFY `ID_Administrator` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT dla tabeli `artykul`
--
ALTER TABLE `artykul`
  MODIFY `ID_Artykul` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT dla tabeli `konferencja`
--
ALTER TABLE `konferencja`
  MODIFY `ID_Konferencja` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT dla tabeli `konto`
--
ALTER TABLE `konto`
  MODIFY `ID_Konto` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT dla tabeli `ocena`
--
ALTER TABLE `ocena`
  MODIFY `ID_Ocena` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT dla tabeli `organizator`
--
ALTER TABLE `organizator`
  MODIFY `ID_Organizator` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT dla tabeli `recenzent`
--
ALTER TABLE `recenzent`
  MODIFY `ID_Recenzent` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT dla tabeli `recenzja`
--
ALTER TABLE `recenzja`
  MODIFY `ID_Recenzja` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT dla tabeli `tematy`
--
ALTER TABLE `tematy`
  MODIFY `ID_Tematy` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT dla tabeli `temat_artykulu`
--
ALTER TABLE `temat_artykulu`
  MODIFY `ID_Temat_Artykulu` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT dla tabeli `temat_konferencji`
--
ALTER TABLE `temat_konferencji`
  MODIFY `ID_Temat_Konferencji` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT dla tabeli `uczestnik`
--
ALTER TABLE `uczestnik`
  MODIFY `ID_Uczestnik` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
