-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 10 mai 2025 à 18:31
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `virtual_trader`
--

-- --------------------------------------------------------

--
-- Structure de la table `action`
--

CREATE TABLE `action` (
  `id` int(11) NOT NULL,
  `code` varchar(10) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `prix_actuel` decimal(10,2) NOT NULL,
  `evolution_precedente` decimal(5,2) DEFAULT 0.00,
  `date_dividende` date DEFAULT NULL,
  `montant_dividende` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `action`
--

INSERT INTO `action` (`id`, `code`, `nom`, `description`, `prix_actuel`, `evolution_precedente`, `date_dividende`, `montant_dividende`) VALUES
(1, 'AAPL', 'Apple ', 'Entreprise technologie', 100.00, 0.00, NULL, 0.00),
(2, 'GOOG', 'Google ', 'Entreprise tech', 100.00, 0.00, NULL, 0.00),
(3, 'TSLA', 'Tesla', 'Constructeur automobile', 100.00, 0.00, NULL, 0.00),
(4, 'LVMH', 'LVMH', 'Groupe de luxe français spécialisé dans la mode, les vins et spiritueux.', 100.00, 0.00, NULL, 0.00),
(5, 'AIR', 'Airbus', 'Leader européen de l’aéronautique, de l’espace et de la défense.', 100.00, 0.00, NULL, 0.00),
(6, 'MC', 'L’Oréal', 'Groupe mondial de cosmétique et de soins de beauté.', 100.00, 0.00, NULL, 0.00),
(7, 'DEC', 'Decathlon', 'Entreprise spécialisée dans la conception et la distribution d’articles de sport.', 100.00, 0.00, NULL, 0.00),
(8, 'AMZN', 'Amazon', 'Leader mondial du e-commerce et des services cloud.', 100.00, 0.00, NULL, 0.00),
(9, 'PYPL', 'PayPal ', 'Société spécialisée dans les paiements électroniques en ligne.', 100.00, 0.00, NULL, 0.00),
(10, 'NVDA', 'NVIDIA ', 'Leader mondial des processeurs graphiques et de l’IA.', 100.00, 0.00, NULL, 0.00),
(11, 'ALO', 'Alstom', 'Acteur majeur du transport ferroviaire (TGV, tramways, etc.).', 100.00, 0.00, NULL, 0.00),
(12, 'HO', 'Thales ', 'Groupe français d’électronique spécialisé en défense et sécurité.', 100.00, 0.00, NULL, 0.00),
(13, 'BNP', 'BNP Paribas', 'Grande banque française présent à l internationale.', 100.00, 0.00, NULL, 0.00),
(14, 'STLA', 'Stellantis', 'Groupe automobile issu de la fusion PSA-Fiat Chrysler.', 100.00, 0.00, NULL, 0.00);

-- --------------------------------------------------------

--
-- Structure de la table `dividende_recu`
--

CREATE TABLE `dividende_recu` (
  `utilisateur_id` int(11) NOT NULL,
  `montant` decimal(10,2) DEFAULT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `historique_classement_joueur`
--

CREATE TABLE `historique_classement_joueur` (
  `id_historique` int(11) NOT NULL,
  `id_joueur` int(11) NOT NULL,
  `date_snapshot` date NOT NULL,
  `rang` int(11) DEFAULT NULL,
  `valeur_portefeuille` decimal(15,2) DEFAULT NULL,
  `performance_mensuelle` decimal(10,2) DEFAULT NULL COMMENT 'Performance par rapport au mois précédent en %'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `historique_portefeuille`
--

CREATE TABLE `historique_portefeuille` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `valeur` decimal(10,2) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `historique_prix`
--

CREATE TABLE `historique_prix` (
  `id` int(11) NOT NULL,
  `action_id` int(11) NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `possession_action`
--

CREATE TABLE `possession_action` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `action_id` int(11) NOT NULL,
  `quantite` int(11) NOT NULL DEFAULT 0,
  `prix_moyen_achat` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `prets`
--

CREATE TABLE `prets` (
  `id_pret` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `montant_emprunte` decimal(15,2) NOT NULL,
  `taux_interet_annuel` decimal(5,2) NOT NULL COMMENT 'Taux annuel en pourcentage, ex: 5.00 pour 5%',
  `date_emprunt` datetime NOT NULL DEFAULT current_timestamp(),
  `date_echeance` datetime NOT NULL,
  `montant_rembourse` decimal(15,2) DEFAULT 0.00,
  `statut_pret` enum('ACTIF','REMBOURSE','DEFAUT') NOT NULL DEFAULT 'ACTIF'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `suivi`
--

CREATE TABLE `suivi` (
  `id` int(11) NOT NULL,
  `suiveur_id` int(11) NOT NULL,
  `suivi_id` int(11) NOT NULL,
  `date_suivi` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `suivi_joueur`
--

CREATE TABLE `suivi_joueur` (
  `id_suivi_joueur` int(11) NOT NULL,
  `id_suiveur` int(11) NOT NULL,
  `id_suivi` int(11) NOT NULL,
  `date_debut_suivi` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `transaction`
--

CREATE TABLE `transaction` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `action_id` int(11) NOT NULL,
  `type` enum('achat','vente') NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL,
  `montant_total` decimal(10,2) NOT NULL,
  `date_transaction` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `date_inscription` date NOT NULL,
  `liquidites` decimal(10,2) DEFAULT 10000.00,
  `derniere_connexion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `action`
--
ALTER TABLE `action`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Index pour la table `dividende_recu`
--
ALTER TABLE `dividende_recu`
  ADD PRIMARY KEY (`utilisateur_id`,`date`);

--
-- Index pour la table `historique_classement_joueur`
--
ALTER TABLE `historique_classement_joueur`
  ADD PRIMARY KEY (`id_historique`),
  ADD UNIQUE KEY `unique_snapshot_joueur` (`id_joueur`,`date_snapshot`);

--
-- Index pour la table `historique_portefeuille`
--
ALTER TABLE `historique_portefeuille`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `utilisateur_id` (`utilisateur_id`,`date`);

--
-- Index pour la table `historique_prix`
--
ALTER TABLE `historique_prix`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `action_id` (`action_id`,`date`);

--
-- Index pour la table `possession_action`
--
ALTER TABLE `possession_action`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `utilisateur_id` (`utilisateur_id`,`action_id`),
  ADD KEY `action_id` (`action_id`);

--
-- Index pour la table `prets`
--
ALTER TABLE `prets`
  ADD PRIMARY KEY (`id_pret`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `suivi`
--
ALTER TABLE `suivi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `suiveur_id` (`suiveur_id`,`suivi_id`),
  ADD KEY `suivi_id` (`suivi_id`);

--
-- Index pour la table `suivi_joueur`
--
ALTER TABLE `suivi_joueur`
  ADD PRIMARY KEY (`id_suivi_joueur`),
  ADD UNIQUE KEY `unique_suivi` (`id_suiveur`,`id_suivi`),
  ADD KEY `id_suivi` (`id_suivi`);

--
-- Index pour la table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`),
  ADD KEY `action_id` (`action_id`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `action`
--
ALTER TABLE `action`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `historique_classement_joueur`
--
ALTER TABLE `historique_classement_joueur`
  MODIFY `id_historique` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `historique_portefeuille`
--
ALTER TABLE `historique_portefeuille`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1168;

--
-- AUTO_INCREMENT pour la table `historique_prix`
--
ALTER TABLE `historique_prix`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1606;

--
-- AUTO_INCREMENT pour la table `possession_action`
--
ALTER TABLE `possession_action`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT pour la table `prets`
--
ALTER TABLE `prets`
  MODIFY `id_pret` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `suivi`
--
ALTER TABLE `suivi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `suivi_joueur`
--
ALTER TABLE `suivi_joueur`
  MODIFY `id_suivi_joueur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `dividende_recu`
--
ALTER TABLE `dividende_recu`
  ADD CONSTRAINT `dividende_recu_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`);

--
-- Contraintes pour la table `historique_classement_joueur`
--
ALTER TABLE `historique_classement_joueur`
  ADD CONSTRAINT `historique_classement_joueur_ibfk_1` FOREIGN KEY (`id_joueur`) REFERENCES `utilisateur` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `historique_portefeuille`
--
ALTER TABLE `historique_portefeuille`
  ADD CONSTRAINT `historique_portefeuille_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `historique_prix`
--
ALTER TABLE `historique_prix`
  ADD CONSTRAINT `historique_prix_ibfk_1` FOREIGN KEY (`action_id`) REFERENCES `action` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `possession_action`
--
ALTER TABLE `possession_action`
  ADD CONSTRAINT `possession_action_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `possession_action_ibfk_2` FOREIGN KEY (`action_id`) REFERENCES `action` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `prets`
--
ALTER TABLE `prets`
  ADD CONSTRAINT `prets_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `suivi`
--
ALTER TABLE `suivi`
  ADD CONSTRAINT `suivi_ibfk_1` FOREIGN KEY (`suiveur_id`) REFERENCES `utilisateur` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `suivi_ibfk_2` FOREIGN KEY (`suivi_id`) REFERENCES `utilisateur` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `suivi_joueur`
--
ALTER TABLE `suivi_joueur`
  ADD CONSTRAINT `suivi_joueur_ibfk_1` FOREIGN KEY (`id_suiveur`) REFERENCES `utilisateur` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `suivi_joueur_ibfk_2` FOREIGN KEY (`id_suivi`) REFERENCES `utilisateur` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `transaction_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaction_ibfk_2` FOREIGN KEY (`action_id`) REFERENCES `action` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
