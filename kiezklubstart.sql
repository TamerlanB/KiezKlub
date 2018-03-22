-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 15. April 2011 um 12:42
-- Server Version: 5.1.49
-- PHP-Version: 5.3.3-1ubuntu9.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `bierboerse`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `baustein`
--

CREATE TABLE IF NOT EXISTS `baustein` (
  `idbaustein` int(11) NOT NULL AUTO_INCREMENT,
  `menu_idmenu` int(11) NOT NULL DEFAULT '0',
  `name` varchar(45) DEFAULT NULL,
  `zeile` int(2) NOT NULL DEFAULT '0',
  `spalte` tinyint(2) NOT NULL DEFAULT '0',
  `a_datum` datetime DEFAULT NULL,
  `e_datum` datetime DEFAULT NULL,
  `l_datum` datetime DEFAULT NULL,
  PRIMARY KEY (`idbaustein`,`menu_idmenu`),
  KEY `fk_baustein_menu` (`menu_idmenu`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=360 ;

--
-- Daten für Tabelle `baustein`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bbaustein`
--

CREATE TABLE IF NOT EXISTS `bbaustein` (
  `idbbaustein` int(11) NOT NULL AUTO_INCREMENT,
  `reihenfolge_idreihenfolge` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `bild` varchar(45) DEFAULT NULL,
  `groesse` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idbbaustein`,`reihenfolge_idreihenfolge`),
  KEY `fk_bbaustein_reihenfolge1` (`reihenfolge_idreihenfolge`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=165 ;

--
-- Daten für Tabelle `bbaustein`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `gbaustein`
--

CREATE TABLE IF NOT EXISTS `gbaustein` (
  `idgbaustein` int(11) NOT NULL AUTO_INCREMENT,
  `reihenfolge_idreihenfolge` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `text` text,
  `autorisiert` datetime NOT NULL,
  `abuse` int(1) NOT NULL DEFAULT '0',
  `ip` varchar(45) DEFAULT NULL,
  `anlagedatum` datetime DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idgbaustein`,`reihenfolge_idreihenfolge`),
  KEY `fk_gbaustein_reihenfolge1` (`reihenfolge_idreihenfolge`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Daten für Tabelle `gbaustein`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `link`
--

CREATE TABLE IF NOT EXISTS `link` (
  `idlink` int(11) NOT NULL AUTO_INCREMENT,
  `reihenfolge_idreihenfolge` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `link` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`idlink`,`reihenfolge_idreihenfolge`),
  KEY `fk_link_reihenfolge1` (`reihenfolge_idreihenfolge`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

--
-- Daten für Tabelle `link`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `idmenu` int(11) NOT NULL AUTO_INCREMENT,
  `menu` varchar(45) NOT NULL,
  `hmstellung` tinyint(4) NOT NULL DEFAULT '0',
  `untermenu` varchar(45) NOT NULL,
  `stellung` int(4) NOT NULL DEFAULT '0',
  `ueberschrift` varchar(45) DEFAULT NULL,
  `seite` varchar(45) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idmenu`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=48 ;

--
-- Daten für Tabelle `menu`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `reihenfolge`
--

CREATE TABLE IF NOT EXISTS `reihenfolge` (
  `idreihenfolge` int(11) NOT NULL AUTO_INCREMENT,
  `baustein_idbaustein` int(11) NOT NULL,
  `reihenfolge` int(3) NOT NULL DEFAULT '0',
  `textgroesse` int(3) NOT NULL DEFAULT '3',
  `fett` tinyint(1) NOT NULL DEFAULT '0',
  `kursiv` tinyint(1) NOT NULL DEFAULT '0',
  `zentriert` int(1) NOT NULL DEFAULT '0',
  `bprozent` int(3) NOT NULL DEFAULT '100',
  `aart` int(1) NOT NULL DEFAULT '0',
  `zeilenumbruch` int(1) NOT NULL DEFAULT '0',
  `vorschau` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idreihenfolge`,`baustein_idbaustein`),
  KEY `fk_reihenfolge_baustein1` (`baustein_idbaustein`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=360 ;

--
-- Daten für Tabelle `reihenfolge`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tbaustein`
--

CREATE TABLE IF NOT EXISTS `tbaustein` (
  `idtbaustein` int(11) NOT NULL AUTO_INCREMENT,
  `reihenfolge_idreihenfolge` varchar(45) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `text` text,
  PRIMARY KEY (`idtbaustein`,`reihenfolge_idreihenfolge`),
  KEY `fk_tbaustein_reihenfolge1` (`reihenfolge_idreihenfolge`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=147 ;

--
-- Daten für Tabelle `tbaustein`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `iduser` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(45) DEFAULT NULL,
  `passwort` varchar(45) DEFAULT NULL,
  `lastlogin` datetime DEFAULT NULL,
  `website` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`iduser`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`iduser`, `login`, `passwort`, `lastlogin`, `website`) VALUES
(1, 'administrator', 'vergessen', '2011-04-15 12:41:00', 'localhost/bierboerse/');
