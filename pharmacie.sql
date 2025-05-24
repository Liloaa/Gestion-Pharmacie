-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 26 mars 2025 à 21:10
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `pharmacie`
--

-- --------------------------------------------------------

--
-- Structure de la table `achat`
--

CREATE TABLE `achat` (
  `numAchat` varchar(10) NOT NULL,
  `nomClient` varchar(100) DEFAULT NULL,
  `dateAchat` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `achat`
--

INSERT INTO `achat` (`numAchat`, `nomClient`, `dateAchat`) VALUES
('A00001', 'Rasoa Zara', '2024-05-10');

-- --------------------------------------------------------

--
-- Structure de la table `achat_details`
--

CREATE TABLE `achat_details` (
  `numAchat` varchar(10) NOT NULL,
  `numMedoc` varchar(10) NOT NULL,
  `nbr` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `achat_details`
--

INSERT INTO `achat_details` (`numAchat`, `numMedoc`, `nbr`) VALUES
('A00001', 'M00001', 3),
('A00001', 'M00002', 2),
('A00001', 'M00004', 5);

-- --------------------------------------------------------

--
-- Structure de la table `entree`
--

CREATE TABLE `entree` (
  `numEntree` varchar(10) NOT NULL,
  `numMedoc` varchar(10) DEFAULT NULL,
  `stockEntree` int(11) DEFAULT NULL,
  `dateEntree` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `entree`
--

INSERT INTO `entree` (`numEntree`, `numMedoc`, `stockEntree`, `dateEntree`) VALUES
('E00001', 'M00001', 30, '2024-01-11'),
('E00002', 'M00002', 10, '2024-03-07'),
('E00003', 'M00003', 10, '2024-04-09'),
('E00004', 'M00003', 10, '2024-06-12'),
('E00005', 'M00004', 50, '2024-08-02'),
('E00006', 'M00005', 40, '2024-10-15'),
('E00007', 'M00006', 15, '2024-11-11'),
('E00008', 'M00007', 20, '2024-12-17'),
('E00009', 'M00008', 45, '2025-01-04'),
('E00010', 'M00009', 50, '2025-02-25');

-- --------------------------------------------------------

--
-- Structure de la table `medicament`
--

CREATE TABLE `medicament` (
  `numMedoc` varchar(10) NOT NULL,
  `Design` varchar(100) DEFAULT NULL,
  `prix_unitaire` int(11) DEFAULT NULL,
  `stock` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `medicament`
--

INSERT INTO `medicament` (`numMedoc`, `Design`, `prix_unitaire`, `stock`) VALUES
('M00001', 'Paracétamol 500mg cp', 1000, 27),
('M00002', 'Vahona au Miel', 20000, 8),
('M00003', 'Amoxicilline 500mg cp', 1500, 20),
('M00004', 'Vitamine C cp', 3000, 45),
('M00005', 'Tétracycline 500mg cp', 1000, 40),
('M00006', 'Pinkoo sp', 15000, 15),
('M00007', 'Albendazole 10mL sp', 4000, 20),
('M00008', 'Seringue 5cc', 400, 45),
('M00009', 'Fervex Adulte Framboise', 2500, 50);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `achat`
--
ALTER TABLE `achat`
  ADD PRIMARY KEY (`numAchat`);

--
-- Index pour la table `achat_details`
--
ALTER TABLE `achat_details`
  ADD PRIMARY KEY (`numAchat`,`numMedoc`),
  ADD KEY `numMedoc` (`numMedoc`);

--
-- Index pour la table `entree`
--
ALTER TABLE `entree`
  ADD PRIMARY KEY (`numEntree`),
  ADD KEY `numMedoc` (`numMedoc`);

--
-- Index pour la table `medicament`
--
ALTER TABLE `medicament`
  ADD PRIMARY KEY (`numMedoc`);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `achat_details`
--
ALTER TABLE `achat_details`
  ADD CONSTRAINT `achat_details_ibfk_1` FOREIGN KEY (`numAchat`) REFERENCES `achat` (`numAchat`),
  ADD CONSTRAINT `achat_details_ibfk_2` FOREIGN KEY (`numMedoc`) REFERENCES `medicament` (`numMedoc`);

--
-- Contraintes pour la table `entree`
--
ALTER TABLE `entree`
  ADD CONSTRAINT `entree_ibfk_1` FOREIGN KEY (`numMedoc`) REFERENCES `medicament` (`numMedoc`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
