-- Base de données : `FLOPPY`
--

-- --------------------------------------------------------

--
-- Structure de la table `User`


CREATE TABLE `User` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(250)  NOT NULL UNIQUE,
  `password` varchar(250)  NOT NULL,
  `date_create` date NOT NULL DEFAULT curdate(),
  PRIMARY KEY (`id`),
);


