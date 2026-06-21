CREATE TABLE `equipamentos` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `codigo` varchar(50) NOT NULL,
  `designacao` varchar(255) NOT NULL,
  `marca` varchar(100),
  `modelo` varchar(100),
  `fabricante` varchar(150),
  `numero_serie` varchar(100),
  `categoria` varchar(50) NOT NULL,
  `estado` varchar(20) NOT NULL,
  `criticidade` varchar(20) NOT NULL,
  `localizacao_id` int NOT NULL,
  `data_aquisicao` date,
  `ano_fabrico` int,
  `custo` decimal(10,2),
  `tipo_entrada` varchar(50),
  `observacoes` text
);

CREATE TABLE `servicos` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nome` varchar(150) UNIQUE NOT NULL
);

CREATE TABLE `localizacoes` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `codigo` varchar(50) UNIQUE NOT NULL,
  `servico_id` int NOT NULL,
  `edificio` varchar(100) NOT NULL,
  `piso` varchar(50) NOT NULL,
  `sala` varchar(150) NOT NULL
);

CREATE TABLE `fornecedores` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `codigo` varchar(50) UNIQUE NOT NULL,
  `nome` varchar(255) NOT NULL,
  `nif` varchar(20) NOT NULL,
  `telefone` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `morada` varchar(255) NOT NULL,
  `website` varchar(255),
  `pessoa_contacto` varchar(150),
  `telefone_contacto` varchar(50),
  `tipo_fornecedor` varchar(100) NOT NULL,
  `observacoes` text
);

CREATE TABLE `tipos_documento` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nome` varchar(100) UNIQUE NOT NULL
);

CREATE TABLE `documentacao` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `equipamento_id` int NOT NULL,
  `contexto` varchar(50) NOT NULL,
  `tipo_documento_id` int NOT NULL,
  `nome_documento` varchar(255) NOT NULL,
  `data_documento` date,
  `data_validade` date,
  `ficheiro` varchar(255) NOT NULL
);

CREATE TABLE `garantias` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `equipamento_id` int NOT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date NOT NULL,
  `entidade_responsavel` varchar(255) NOT NULL,
  `observacoes` text
);

CREATE TABLE `contratos` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `equipamento_id` int NOT NULL,
  `tipo_contrato` varchar(100) NOT NULL,
  `periodicidade` varchar(100) NOT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date NOT NULL,
  `entidade_responsavel` varchar(255) NOT NULL,
  `observacoes` text
);

CREATE TABLE `equipamento_fornecedor` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `equipamento_id` int NOT NULL,
  `fornecedor_id` int NOT NULL,
  `tipo_relacao` varchar(100) NOT NULL,
  `pessoa_contacto` varchar(150),
  `telefone_contacto` varchar(50),
  `morada_associada` varchar(255),
  `observacoes` text
);

CREATE TABLE `mensagens_publico` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nome` varchar(150),
  `email` varchar(150) NOT NULL,
  `assunto` varchar(200),
  `mensagem` text NOT NULL,
  `data_envio` datetime NOT NULL,
  `lida` boolean DEFAULT false
);

CREATE TABLE `gestao_area_publica` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `secao` varchar(100) NOT NULL,
  `titulo` varchar(255),
  `texto` text,
  `imagem` varchar(255)
);

CREATE TABLE `utilizadores` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nome` varchar(150) NOT NULL,
  `email` varchar(150) UNIQUE NOT NULL,
  `password` varchar(255) NOT NULL,
  `perfil` enum('administrador','tecnico','profissional_saude') NOT NULL DEFAULT 'profissional_saude'
);

CREATE TABLE `componentes_consumiveis` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `equipamento_id` int NOT NULL,
  `tipo` varchar(20) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `referencia` varchar(255),
  `quantidade` int,
  `estado` varchar(50),
  `observacoes` text
);

ALTER TABLE `equipamentos` ADD FOREIGN KEY (`localizacao_id`) REFERENCES `localizacoes` (`id`);

ALTER TABLE `documentacao` ADD FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`);

ALTER TABLE `garantias` ADD FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`);

ALTER TABLE `contratos` ADD FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`);

ALTER TABLE `componentes_consumiveis` ADD FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`);

ALTER TABLE `equipamento_fornecedor` ADD FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`);

ALTER TABLE `equipamento_fornecedor` ADD FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedores` (`id`);

ALTER TABLE `localizacoes` ADD FOREIGN KEY (`servico_id`) REFERENCES `servicos` (`id`);

ALTER TABLE `documentacao` ADD FOREIGN KEY (`tipo_documento_id`) REFERENCES `tipos_documento` (`id`);
