-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : lun. 07 déc. 2020 à 19:13
-- Version du serveur :  10.4.11-MariaDB
-- Version de PHP : 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `blog_dev`
--

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_creation` datetime NOT NULL DEFAULT current_timestamp(),
  `date_maj` datetime NOT NULL DEFAULT current_timestamp(),
  `image_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `articles`
--

INSERT INTO `articles` (`id`, `titre`, `description`, `date_creation`, `date_maj`, `image_name`, `user_id`) VALUES
(20, 'capture d\'écran', 'Une capture d\'écran pour faire un test d\'image.', '2020-11-26 18:45:28', '2020-11-26 18:45:28', 'minion-5fbfe9b8e855a090401314.jpg', 3),
(23, 'test', 'une autre description', '2020-11-26 21:58:06', '2020-11-26 21:58:06', 'coding-computer-science-5fc016de0552c922633836.jpg', 3),
(24, 'test post', 'new test post', '2020-12-07 17:20:47', '2020-12-07 17:20:47', 'footballstat1-5fce565f79bda524183012.png', 4),
(25, 'La tete à nassser', 'nassser le bg', '2020-12-07 17:22:53', '2020-12-07 17:22:53', 'nasserbg-5fce56dd8f1a0524064647.png', 4);

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20201107223545', '2020-11-07 23:48:22', 46),
('DoctrineMigrations\\Version20201107223953', '2020-11-07 23:48:22', 15),
('DoctrineMigrations\\Version20201108155430', '2020-11-08 16:58:29', 25),
('DoctrineMigrations\\Version20201118180836', '2020-11-18 19:10:28', 34),
('DoctrineMigrations\\Version20201125174906', '2020-11-25 18:55:35', 103),
('DoctrineMigrations\\Version20201125175252', '2020-11-25 18:57:51', 60),
('DoctrineMigrations\\Version20201125181356', '2020-11-25 19:17:20', 123),
('DoctrineMigrations\\Version20201125230950', '2020-11-26 00:10:15', 74);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_creation` datetime NOT NULL DEFAULT current_timestamp(),
  `date_maj` datetime NOT NULL DEFAULT current_timestamp(),
  `is_verified` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `roles`, `password`, `date_creation`, `date_maj`, `is_verified`) VALUES
(1, 'abdellah', 'choukri', 'abdellah.choukri@etu.umoddntpellier.fr', '[]', '$argon2id$v=19$m=65536,t=4,p=1$J47bmkxwSyo9B7l5tJkkzg$e/wAi7CcJ1BtZq6bji8htmAMv2EUJHVTpK7ETJisV3o', '2020-11-26 00:10:44', '2020-11-26 00:10:44', 0),
(2, 'abdellah', 'choukri', 'abdellah.choukri@etu.umossddntpellier.fr', '[]', '$argon2id$v=19$m=65536,t=4,p=1$1l8jath0GaSOua9acw//rQ$OTUwyJ18zIU823ASXAoGDX+MZnhOCXtXkOnDVNOzOmQ', '2020-11-26 00:19:21', '2020-11-26 00:19:21', 1),
(3, 'abdellah', 'choukri', 'abdellah.choukri@test.fr', '[ \"ROLE_ADMIN\"]', '$argon2id$v=19$m=65536,t=4,p=1$ptlyhLtftMhmbZ43knvMZQ$WYe1ROyLq+3Pr6JDXgSnK8Tlnn+nz24cFEzpg/jSCwM', '2020-11-26 15:38:45', '2020-11-26 15:38:45', 0),
(4, 'abdellah', 'choukri', 'abdellahchoukri@gmail.com', '[]', '$argon2id$v=19$m=65536,t=4,p=1$vH40W41lp64pbyRgEseVGQ$a/T8TD6ehlX7AMMcJ78N/tJG99S7nxL7+FkxgWcCpkI', '2020-12-07 17:18:58', '2020-12-07 17:18:58', 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_BFDD3168A76ED395` (`user_id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_1483A5E9E7927C74` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `FK_BFDD3168A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
