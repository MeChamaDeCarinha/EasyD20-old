SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "-3:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `atributo` (
  `id` int(11) NOT NULL,
  `cod_ficha` int(11) DEFAULT NULL,
  `nome` varchar(32) NOT NULL,
  `valor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `aventura` (
  `id` int(11) NOT NULL,
  `nome` varchar(32) NOT NULL,
  `livro` varchar(32) DEFAULT NULL,
  `descricao` varchar(96) NOT NULL,
  `imagem` varchar(100) NOT NULL,
  `cod` varchar(6) NOT NULL,
  `publica` tinyint(1) NOT NULL DEFAULT 1,
  `editar` tinyint(1) NOT NULL DEFAULT 0,
  `ficha_mestre` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `aventura_usuario` (
  `id` int(11) NOT NULL,
  `cod_usuario` int(11) NOT NULL,
  `cod_aventura` int(11) NOT NULL,
  `mestre` tinyint(1) NOT NULL DEFAULT 0,
  `banido` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `ficha` (
  `id` int(11) NOT NULL,
  `cod_usuario` int(11) NOT NULL,
  `cod_aventura` int(11) NOT NULL,
  `nome` varchar(32) DEFAULT NULL,
  `dinheiro` decimal(10,2) NOT NULL DEFAULT 0.00,
  `idade` int(3) DEFAULT 0,
  `altura` decimal(3,2) DEFAULT 0.00,
  `peso` int(3) DEFAULT 0,
  `raca` varchar(32) DEFAULT NULL,
  `classe` varchar(32) DEFAULT NULL,
  `nivel` int(4) DEFAULT 0,
  `pontos_exp` int(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `habilidade` (
  `id` int(11) NOT NULL,
  `cod_ficha` int(11) DEFAULT NULL,
  `nome` varchar(64) DEFAULT NULL,
  `forca` int(4) DEFAULT NULL,
  `nivel` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `item` (
  `id` int(11) NOT NULL,
  `cod_ficha` int(11) NOT NULL,
  `nome` varchar(64) NOT NULL,
  `img_path` varchar(100) NOT NULL,
  `quantidade` int(11) NOT NULL DEFAULT 1,
  `preco` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `mapa` (
  `id` int(11) NOT NULL,
  `cod_aventura` int(11) NOT NULL,
  `nome` varchar(32) NOT NULL,
  `descricao` varchar(96) DEFAULT NULL,
  `img_path` varchar(100) NOT NULL,
  `hidden` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nome` varchar(20) NOT NULL,
  `email` varchar(48) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `img_path` varchar(100) DEFAULT 'Default.png',
  `verificado` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


ALTER TABLE `atributo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cod_ficha` (`cod_ficha`);

ALTER TABLE `aventura`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `aventura_usuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cod_usuario` (`cod_usuario`),
  ADD KEY `cod_aventura` (`cod_aventura`);

ALTER TABLE `ficha`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cod_usuario` (`cod_usuario`),
  ADD KEY `cod_aventura` (`cod_aventura`);

ALTER TABLE `habilidade`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cod_ficha` (`cod_ficha`);

ALTER TABLE `item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cod_ficha` (`cod_ficha`);

ALTER TABLE `mapa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cod_aventura` (`cod_aventura`);

ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `atributo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `aventura`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `aventura_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `ficha`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `habilidade`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `mapa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `atributo`
  ADD CONSTRAINT `atributo_ibfk_1` FOREIGN KEY (`cod_ficha`) REFERENCES `ficha` (`id`);

ALTER TABLE `aventura_usuario`
  ADD CONSTRAINT `aventura_usuario_ibfk_1` FOREIGN KEY (`cod_usuario`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `aventura_usuario_ibfk_2` FOREIGN KEY (`cod_aventura`) REFERENCES `aventura` (`id`);

ALTER TABLE `ficha`
  ADD CONSTRAINT `ficha_ibfk_1` FOREIGN KEY (`cod_usuario`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `ficha_ibfk_2` FOREIGN KEY (`cod_aventura`) REFERENCES `aventura` (`id`);

ALTER TABLE `habilidade`
  ADD CONSTRAINT `habilidade_ibfk_1` FOREIGN KEY (`cod_ficha`) REFERENCES `ficha` (`id`);

ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`cod_ficha`) REFERENCES `ficha` (`id`);

ALTER TABLE `mapa`
  ADD CONSTRAINT `mapa_ibfk_1` FOREIGN KEY (`cod_aventura`) REFERENCES `aventura` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
