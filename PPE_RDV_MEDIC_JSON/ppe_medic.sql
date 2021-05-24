-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 24 mai 2021 à 12:50
-- Version du serveur :  8.0.21
-- Version de PHP : 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ppe_medic`
--

-- --------------------------------------------------------

--
-- Structure de la table `authentification`
--

DROP TABLE IF EXISTS `authentification`;
CREATE TABLE IF NOT EXISTS `authentification` (
  `token` varchar(70) NOT NULL,
  `idPatient` int DEFAULT NULL,
  `ipAppareil` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`token`),
  KEY `idPatient` (`idPatient`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `patient`
--

DROP TABLE IF EXISTS `patient`;
CREATE TABLE IF NOT EXISTS `patient` (
  `idPatient` int NOT NULL AUTO_INCREMENT,
  `nomPatient` varchar(50) NOT NULL,
  `prenomPatient` varchar(50) NOT NULL,
  `ruePatient` varchar(100) NOT NULL,
  `cpPatient` varchar(6) NOT NULL,
  `villePatient` varchar(50) NOT NULL,
  `telPatient` varchar(15) NOT NULL,
  `loginPatient` varchar(50) NOT NULL,
  `mdpPatient` varchar(100) NOT NULL,
  PRIMARY KEY (`idPatient`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `patient`
--

INSERT INTO `patient` (`idPatient`, `nomPatient`, `prenomPatient`, `ruePatient`, `cpPatient`, `villePatient`, `telPatient`, `loginPatient`, `mdpPatient`) VALUES
(43, 'Chevalot', 'Lucas', '15 rue du gué', '55000', 'Bar le duc', '0648789090', 'lchevalot', '$2y$10$kTlQBkvEjWEbjPWwJFOdEOb7f470oEArQDFRnu5KojShHCPdk6L9C'),
(45, 'Yilmaz', 'Yusuf', '66 rue du cordon bleu', '55000', 'Bar le duc', '0666666666', 'yyilmaz', '$2y$10$1b/8.vZqIQrPNR3pYX9W4eHlnQ.dr3f796NL.GxgJ58/08nbzOm5C');

-- --------------------------------------------------------

--
-- Structure de la table `rdv`
--

DROP TABLE IF EXISTS `rdv`;
CREATE TABLE IF NOT EXISTS `rdv` (
  `idRdv` int NOT NULL AUTO_INCREMENT,
  `dateHeureRdv` datetime NOT NULL,
  `idPatient` int NOT NULL,
  `idMedecin` varchar(50) NOT NULL,
  PRIMARY KEY (`idRdv`),
  KEY `idPatient` (`idPatient`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `rdv`
--

INSERT INTO `rdv` (`idRdv`, `dateHeureRdv`, `idPatient`, `idMedecin`) VALUES
(38, '2001-04-14 16:30:00', 43, 'd0084bea9049e11e0cb060dcef340c97d8f3e113');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `authentification`
--
ALTER TABLE `authentification`
  ADD CONSTRAINT `authentification_ibfk_1` FOREIGN KEY (`idPatient`) REFERENCES `patient` (`idPatient`);

--
-- Contraintes pour la table `rdv`
--
ALTER TABLE `rdv`
  ADD CONSTRAINT `rdv_ibfk_1` FOREIGN KEY (`idPatient`) REFERENCES `patient` (`idPatient`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
