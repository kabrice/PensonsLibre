-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Dim 16 Octobre 2016 à 16:15
-- Version du serveur :  10.1.13-MariaDB
-- Version de PHP :  5.5.37

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE DATABASE  IF NOT EXISTS `biblioshare`;
USE `biblioshare`;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `biblioshare`
--

-- --------------------------------------------------------

--
-- Structure de la table `appartenir`
--

CREATE TABLE `appartenir` (
  `num_auteur` int(4) NOT NULL,
  `num_livre` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `auteur`
--

CREATE TABLE `auteur` (
  `num_auteur` int(4) NOT NULL,
  `nom_auteur` varchar(32) NOT NULL,
  `prenom_auteur` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `collection`
--

CREATE TABLE `collection` (
  `num_collection` int(4) NOT NULL,
  `libelle_collection` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `num_categorie` int(4) NOT NULL,
  `libelle_categorie` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `detenir`
--

CREATE TABLE `detenir` (
  `num_utilisateur` int(4) NOT NULL,
  `num_livre` int(4) NOT NULL,
  `emprunt` tinyint(1) DEFAULT NULL,
  `confie` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `edition`
--

CREATE TABLE `edition` (
  `num_editeur` int(4) NOT NULL,
  `libelle_editeur` varchar(32) NOT NULL,
  `date_edition` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `livre`
--

CREATE TABLE `livre` (
  `num_livre` int(4) NOT NULL,
  `num_editeur` int(4) NOT NULL,
  `num_collection` int(4) NOT NULL,
  `num_categorie` int(4) NOT NULL,
  `description` varchar(255) NOT NULL,
  `date_sortie` date NOT NULL,
  `libelle_livre` varchar(255) NOT NULL,
  `image` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `posseder`
--

CREATE TABLE `posseder` (
  `num_utilisateur` int(4) NOT NULL,
  `num_utilisateur_1` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `num_utilisateur` int(4) NOT NULL,
  `nom` varchar(32) NOT NULL,
  `prenom` varchar(32) NOT NULL,
  `e_mail` varchar(32) NOT NULL,
  `motdepasse` varchar(32) NOT NULL,
  `date_inscription` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `utilisateur`
--

INSERT INTO `utilisateur` (`num_utilisateur`, `nom`, `prenom`, `e_mail`, `motdepasse`, `date_inscription`) VALUES
(1, 'tongle', 'michael', 'michael@3il.fr', '314toto33', '2016-10-15'),
(2, 'kamdem', 'edgar', 'edgar@3il.fr', 'titi77854tt', '2016-10-15'),
(3, 'kamdem', 'roger', 'roger@3il.fr', '66roger123', '2016-10-15'),
(4, 'teka', 'diego', 'diego@3il.fr', 'ff123hh78', '2016-10-15');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `appartenir`
--
ALTER TABLE `appartenir`
  ADD PRIMARY KEY (`num_auteur`,`num_livre`),
  ADD KEY `fk_appartenir_livre` (`num_livre`);

--
-- Index pour la table `auteur`
--
ALTER TABLE `auteur`
  ADD PRIMARY KEY (`num_auteur`);

--
-- Index pour la table `collection`
--
ALTER TABLE `collection`
  ADD PRIMARY KEY (`num_collection`);

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`num_categorie`);

--
-- Index pour la table `detenir`
--
ALTER TABLE `detenir`
  ADD PRIMARY KEY (`num_utilisateur`,`num_livre`),
  ADD KEY `fk_detenir_livre` (`num_livre`);

--
-- Index pour la table `edition`
--
ALTER TABLE `edition`
  ADD PRIMARY KEY (`num_editeur`);

--
-- Index pour la table `livre`
--
ALTER TABLE `livre`
  ADD PRIMARY KEY (`num_livre`),
  ADD KEY `fk_livre_edition` (`num_editeur`),
  ADD KEY `fk_livre_collection` (`num_collection`),
  ADD KEY `fk_livre_categorie` (`num_categorie`);

--
-- Index pour la table `posseder`
--
ALTER TABLE `posseder`
  ADD PRIMARY KEY (`num_utilisateur`,`num_utilisateur_1`),
  ADD KEY `fk_posseder_utilisateur1` (`num_utilisateur_1`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`num_utilisateur`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `auteur`
--
ALTER TABLE `auteur`
  MODIFY `num_auteur` int(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `collection`
--
ALTER TABLE `collection`
  MODIFY `num_collection` int(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `num_categorie` int(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `edition`
--
ALTER TABLE `edition`
  MODIFY `num_editeur` int(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `livre`
--
ALTER TABLE `livre`
  MODIFY `num_livre` int(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `num_utilisateur` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `appartenir`
--
ALTER TABLE `appartenir`
  ADD CONSTRAINT `fk_appartenir_auteur` FOREIGN KEY (`num_auteur`) REFERENCES `auteur` (`num_auteur`),
  ADD CONSTRAINT `fk_appartenir_livre` FOREIGN KEY (`num_livre`) REFERENCES `livre` (`num_livre`);

--
-- Contraintes pour la table `detenir`
--
ALTER TABLE `detenir`
  ADD CONSTRAINT `fk_detenir_livre` FOREIGN KEY (`num_livre`) REFERENCES `livre` (`num_livre`),
  ADD CONSTRAINT `fk_detenir_utilisateur` FOREIGN KEY (`num_utilisateur`) REFERENCES `utilisateur` (`num_utilisateur`);

--
-- Contraintes pour la table `livre`
--
ALTER TABLE `livre`
  ADD CONSTRAINT `fk_livre_collection` FOREIGN KEY (`num_collection`) REFERENCES `collection` (`num_collection`),
  ADD CONSTRAINT `fk_livre_categorie` FOREIGN KEY (`num_categorie`) REFERENCES `categorie` (`num_categorie`),
  ADD CONSTRAINT `fk_livre_edition` FOREIGN KEY (`num_editeur`) REFERENCES `edition` (`num_editeur`);

--
-- Contraintes pour la table `posseder`
--
ALTER TABLE `posseder`
  ADD CONSTRAINT `fk_posseder_utilisateur` FOREIGN KEY (`num_utilisateur`) REFERENCES `utilisateur` (`num_utilisateur`),
  ADD CONSTRAINT `fk_posseder_utilisateur1` FOREIGN KEY (`num_utilisateur_1`) REFERENCES `utilisateur` (`num_utilisateur`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
