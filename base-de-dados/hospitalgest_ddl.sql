-- --------------------------------------------------------
-- Anfitrião:                    vsgate-s1.dei.isep.ipp.pt
-- Versão do servidor:           8.0.45 - MySQL Community Server - GPL
-- SO do servidor:               Linux
-- HeidiSQL Versão:              12.17.0.7270
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- A despejar estrutura para tabela db1241344.componentes_consumiveis
CREATE TABLE IF NOT EXISTS `componentes_consumiveis` (
  `id` int NOT NULL AUTO_INCREMENT,
  `equipamento_id` int NOT NULL,
  `tipo` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `referencia` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `quantidade` int DEFAULT NULL,
  `estado` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
  `observacoes` text COLLATE utf8mb4_bin,
  PRIMARY KEY (`id`),
  KEY `equipamento_id` (`equipamento_id`),
  CONSTRAINT `componentes_consumiveis_ibfk_1` FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Exportação de dados não seleccionada.

-- A despejar estrutura para tabela db1241344.contratos
CREATE TABLE IF NOT EXISTS `contratos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `equipamento_id` int NOT NULL,
  `tipo_contrato` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `periodicidade` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date NOT NULL,
  `entidade_responsavel` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `observacoes` text COLLATE utf8mb4_bin,
  PRIMARY KEY (`id`),
  KEY `equipamento_id` (`equipamento_id`),
  CONSTRAINT `contratos_ibfk_1` FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Exportação de dados não seleccionada.

-- A despejar estrutura para tabela db1241344.documentacao
CREATE TABLE IF NOT EXISTS `documentacao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `equipamento_id` int NOT NULL,
  `contexto` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `tipo_documento_id` int NOT NULL,
  `nome_documento` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `data_documento` date DEFAULT NULL,
  `data_validade` date DEFAULT NULL,
  `ficheiro` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `equipamento_id` (`equipamento_id`),
  KEY `tipo_documento_id` (`tipo_documento_id`),
  CONSTRAINT `documentacao_ibfk_1` FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`),
  CONSTRAINT `documentacao_ibfk_2` FOREIGN KEY (`tipo_documento_id`) REFERENCES `tipos_documento` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=184 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Exportação de dados não seleccionada.

-- A despejar estrutura para tabela db1241344.equipamento_fornecedor
CREATE TABLE IF NOT EXISTS `equipamento_fornecedor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `equipamento_id` int NOT NULL,
  `fornecedor_id` int NOT NULL,
  `tipo_relacao` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `pessoa_contacto` varchar(150) COLLATE utf8mb4_bin DEFAULT NULL,
  `telefone_contacto` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
  `morada_associada` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `observacoes` text COLLATE utf8mb4_bin,
  PRIMARY KEY (`id`),
  KEY `equipamento_id` (`equipamento_id`),
  KEY `fornecedor_id` (`fornecedor_id`),
  CONSTRAINT `equipamento_fornecedor_ibfk_1` FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`),
  CONSTRAINT `equipamento_fornecedor_ibfk_2` FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedores` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Exportação de dados não seleccionada.

-- A despejar estrutura para tabela db1241344.equipamentos
CREATE TABLE IF NOT EXISTS `equipamentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `designacao` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `marca` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
  `modelo` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
  `fabricante` varchar(150) COLLATE utf8mb4_bin DEFAULT NULL,
  `numero_serie` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
  `categoria` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `estado` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `criticidade` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `localizacao_id` int NOT NULL,
  `data_aquisicao` date DEFAULT NULL,
  `ano_fabrico` int DEFAULT NULL,
  `custo` decimal(10,2) DEFAULT NULL,
  `tipo_entrada` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
  `observacoes` text COLLATE utf8mb4_bin,
  `equipamento_ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `localizacao_id` (`localizacao_id`),
  CONSTRAINT `equipamentos_ibfk_1` FOREIGN KEY (`localizacao_id`) REFERENCES `localizacoes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Exportação de dados não seleccionada.

-- A despejar estrutura para tabela db1241344.fornecedores
CREATE TABLE IF NOT EXISTS `fornecedores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `nif` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `telefone` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_bin NOT NULL,
  `morada` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `website` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `pessoa_contacto` varchar(150) COLLATE utf8mb4_bin DEFAULT NULL,
  `telefone_contacto` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
  `tipo_fornecedor` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `observacoes` text COLLATE utf8mb4_bin,
  `fornecedor_ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Exportação de dados não seleccionada.

-- A despejar estrutura para tabela db1241344.garantias
CREATE TABLE IF NOT EXISTS `garantias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `equipamento_id` int NOT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date NOT NULL,
  `entidade_responsavel` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `observacoes` text COLLATE utf8mb4_bin,
  PRIMARY KEY (`id`),
  KEY `equipamento_id` (`equipamento_id`),
  CONSTRAINT `garantias_ibfk_1` FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Exportação de dados não seleccionada.

-- A despejar estrutura para tabela db1241344.gestao_area_publica
CREATE TABLE IF NOT EXISTS `gestao_area_publica` (
  `id` int NOT NULL AUTO_INCREMENT,
  `secao` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `titulo` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `texto` text COLLATE utf8mb4_bin,
  `imagem` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Exportação de dados não seleccionada.

-- A despejar estrutura para tabela db1241344.localizacoes
CREATE TABLE IF NOT EXISTS `localizacoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `servico_id` int NOT NULL,
  `edificio` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `piso` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `sala` varchar(150) COLLATE utf8mb4_bin NOT NULL,
  `observacoes` text COLLATE utf8mb4_bin,
  `localizacao_ativa` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  KEY `servico_id` (`servico_id`),
  CONSTRAINT `localizacoes_ibfk_1` FOREIGN KEY (`servico_id`) REFERENCES `servicos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Exportação de dados não seleccionada.

-- A despejar estrutura para tabela db1241344.mensagens_publico
CREATE TABLE IF NOT EXISTS `mensagens_publico` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) COLLATE utf8mb4_bin DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_bin NOT NULL,
  `assunto` varchar(200) COLLATE utf8mb4_bin DEFAULT NULL,
  `mensagem` text COLLATE utf8mb4_bin NOT NULL,
  `data_envio` datetime NOT NULL,
  `lida` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Exportação de dados não seleccionada.

-- A despejar estrutura para tabela db1241344.servicos
CREATE TABLE IF NOT EXISTS `servicos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Exportação de dados não seleccionada.

-- A despejar estrutura para tabela db1241344.tipos_documento
CREATE TABLE IF NOT EXISTS `tipos_documento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Exportação de dados não seleccionada.

-- A despejar estrutura para tabela db1241344.utilizadores
CREATE TABLE IF NOT EXISTS `utilizadores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) COLLATE utf8mb4_bin NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_bin NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `perfil` enum('administrador','tecnico','profissional_saude') COLLATE utf8mb4_bin NOT NULL DEFAULT 'profissional_saude',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Exportação de dados não seleccionada.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
