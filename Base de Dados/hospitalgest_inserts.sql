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

-- A despejar dados para tabela db1241344.componentes_consumiveis: ~30 rows (aproximadamente)
DELETE FROM `componentes_consumiveis`;
INSERT INTO `componentes_consumiveis` (`id`, `equipamento_id`, `tipo`, `nome`, `referencia`, `quantidade`, `estado`, `observacoes`) VALUES
	(1, 1, 'Componente', 'Sensor SpO2', 'DS-100A', 2, 'Ativo', ''),
	(2, 1, 'Componente', 'Cabo ECG 5 derivações', 'ECG-5D', 1, 'Ativo', ''),
	(3, 1, 'Componente', 'Manguito NIBP adulto', 'MAN-ADU', 2, 'Ativo', ''),
	(4, 1, 'Componente', 'Sensor de temperatura', 'TEMP-01', 1, 'Ativo', ''),
	(5, 1, 'Componente', 'Bateria', 'BAT-MP5', 1, 'Ativo', ''),
	(6, 2, 'Componente', 'Filtro bacteriano', 'FIL-BAC', 3, 'Ativo', NULL),
	(7, 2, 'Componente', 'Circuito respiratório adulto', 'CIR-ADU', 2, 'Ativo', NULL),
	(8, 2, 'Componente', 'Bateria de reserva', 'BAT-EV5', 1, 'Ativo', NULL),
	(9, 2, 'Consumível', 'Filtro HME', 'HME-001', 10, 'Ativo', NULL),
	(10, 3, 'Componente', 'Seringa 50ml', 'SER-50', 5, 'Ativo', NULL),
	(11, 3, 'Consumível', 'Prolongamento IV', 'PRO-IV', 10, 'Ativo', NULL),
	(12, 4, 'Componente', 'Pás de desfibrilhação adulto', 'PAS-ADU', 1, 'Ativo', NULL),
	(13, 4, 'Componente', 'Cabo ECG monitorização', 'ECG-MON', 1, 'Ativo', NULL),
	(14, 4, 'Componente', 'Bateria', 'BAT-ZR', 2, 'Ativo', NULL),
	(15, 4, 'Consumível', 'Elétrodos adesivos', 'ELE-ADH', 20, 'Ativo', NULL),
	(16, 6, 'Componente', 'Sonda convex 3.5MHz', 'SON-CON', 1, 'Ativo', NULL),
	(17, 6, 'Componente', 'Sonda linear 7.5MHz', 'SON-LIN', 1, 'Ativo', NULL),
	(18, 6, 'Consumível', 'Gel de ultrassom', 'GEL-US', 5, 'Ativo', NULL),
	(19, 8, 'Componente', 'Cartucho de reagentes', 'CAR-REA', 2, 'Ativo', NULL),
	(20, 8, 'Consumível', 'Capilares de amostra', 'CAP-AMO', 50, 'Ativo', NULL),
	(21, 18, 'Componente', 'Cassete de detetor', 'CAS-DET', 1, 'Ativo', NULL),
	(22, 18, 'Consumível', 'Película radiográfica', 'PEL-RAD', 100, 'Ativo', NULL),
	(23, 21, 'Componente', 'Sensor de temperatura incubadora', 'TEMP-INC', 1, 'Ativo', NULL),
	(24, 21, 'Consumível', 'Filtro de ar HEPA', 'FIL-HEPA', 2, 'Ativo', NULL),
	(25, 24, 'Componente', 'Módulo de reagentes', 'MOD-REA', 1, 'Ativo', NULL),
	(26, 24, 'Consumível', 'Tiras de calibração', 'TIR-CAL', 30, 'Ativo', NULL),
	(27, 32, 'consumivel', 'Gel', 'DS-100B', 2, '', ''),
	(28, 33, 'componente', 'Sensores de fluxo e pressão', 'SF-200A', 2, 'Ativo', ''),
	(29, 33, 'consumivel', 'Filtro bacteriano/viral (HMEF)', 'CRA-22MM', 1, 'Ativo', ''),
	(33, 1, 'Componente', 'SENSOR', '', 1, 'Ativo', '');

-- A despejar dados para tabela db1241344.contratos: ~14 rows (aproximadamente)
DELETE FROM `contratos`;
INSERT INTO `contratos` (`id`, `equipamento_id`, `tipo_contrato`, `periodicidade`, `data_inicio`, `data_fim`, `entidade_responsavel`, `observacoes`) VALUES
	(2, 2, 'Manutenção Preventiva', 'Semestral', '2023-06-20', '2026-06-20', 'Empresa de assistência técnica', NULL),
	(3, 4, 'Manutenção Preventiva', 'Anual', '2023-09-05', '2026-09-05', 'Empresa de assistência técnica', NULL),
	(4, 5, 'Full-Service', 'Trimestral', '2024-02-28', '2027-02-28', 'Empresa de assistência técnica', NULL),
	(5, 6, 'Manutenção Corretiva', 'Anual', '2023-07-14', '2026-07-14', 'Empresa de assistência técnica', NULL),
	(6, 8, 'Full-Service', 'Semestral', '2024-05-18', '2027-05-18', 'Empresa de assistência técnica', NULL),
	(7, 12, 'Manutenção Preventiva', 'Semestral', '2023-03-15', '2026-03-15', 'Empresa de assistência técnica', NULL),
	(8, 14, 'Full-Service', 'Trimestral', '2024-01-20', '2027-01-20', 'Empresa de assistência técnica', NULL),
	(9, 16, 'Outsourcing', 'Anual', '2023-11-05', '2026-11-05', 'Empresa de assistência técnica', NULL),
	(10, 18, 'Full-Service', 'Semestral', '2023-05-30', '2026-05-30', 'Empresa de assistência técnica', NULL),
	(11, 21, 'Manutenção Preventiva', 'Trimestral', '2024-03-22', '2027-03-22', 'Empresa de assistência técnica', NULL),
	(12, 24, 'Full-Service', 'Anual', '2023-04-05', '2026-04-05', 'Empresa de assistência técnica', NULL),
	(13, 32, 'Manutenção Preventiva', 'Trimestral', '2026-06-09', '2028-06-07', 'Empresa de assistência técnica', ''),
	(14, 34, 'Manutenção Preventiva', 'Anual', '2026-06-01', '2027-06-09', 'Empresa de assistência técnica', ''),
	(16, 1, 'Manutenção Preventiva', 'Mensal', '2022-04-23', '2030-04-23', 'Empresa de assistência técnica', NULL);

-- A despejar dados para tabela db1241344.documentacao: ~127 rows (aproximadamente)
DELETE FROM `documentacao`;
INSERT INTO `documentacao` (`id`, `equipamento_id`, `contexto`, `tipo_documento_id`, `nome_documento`, `data_documento`, `data_validade`, `ficheiro`) VALUES
	(2, 1, 'geral', 2, 'Manual de Serviço IntelliVue MP6', '2022-03-15', NULL, 'geral/manual_utilizador.pdf'),
	(3, 1, 'aquisicao', 6, 'Contrato de Aquisição Monitor Philips', '2022-03-10', NULL, 'aquisicao/contrato_aquisicao.pdf'),
	(4, 1, 'aquisicao', 5, 'Fatura Monitor Philips', '2022-03-15', NULL, 'aquisicao/fatura_aquisicao.pdf'),
	(5, 1, 'garantia', 8, 'Certificado de Garantia Philips MP5', '2022-03-10', '2025-03-15', 'garantia/certificado_garantia.pdf'),
	(7, 2, 'geral', 1, 'Manual Utilizador Evita V500', '2021-06-20', NULL, 'geral/manual_utilizador.pdf'),
	(8, 2, 'aquisicao', 5, 'Fatura Ventilador Dräger', '2021-06-20', NULL, 'aquisicao/fatura_aquisicao.pdf'),
	(9, 2, 'garantia', 8, 'Certificado de Garantia Dräger', '2021-06-20', '2024-06-20', 'garantia/certificado_garantia.pdf'),
	(10, 2, 'contrato', 4, 'Contrato Manutenção Dräger 2023', '2023-06-20', '2026-06-20', 'contrato/contrato_manutencao.pdf'),
	(11, 3, 'geral', 1, 'Manual Utilizador Infusomat Space', '2020-01-10', NULL, 'geral/manual_utilizador.pdf'),
	(12, 3, 'aquisicao', 5, 'Fatura Bomba Infusão B.Braun', '2020-01-10', NULL, 'aquisicao/fatura_aquisicao.pdf'),
	(13, 3, 'garantia', 8, 'Certificado de Garantia B.Braun', '2020-01-10', '2023-01-10', 'garantia/certificado_garantia.pdf'),
	(14, 4, 'geral', 1, 'Manual Utilizador R Series', '2021-09-05', NULL, 'geral/manual_utilizador.pdf'),
	(15, 4, 'aquisicao', 5, 'Fatura Desfibrilhador Zoll', '2021-09-05', NULL, 'aquisicao/fatura_aquisicao.pdf'),
	(16, 4, 'garantia', 8, 'Certificado de Garantia Zoll', '2021-09-05', '2024-09-05', 'garantia/certificado_garantia.pdf'),
	(17, 4, 'contrato', 4, 'Contrato Manutenção TecAssist 2023', '2023-09-05', '2026-09-05', 'contrato/contrato_manutencao.pdf'),
	(18, 5, 'geral', 1, 'Manual Utilizador MAC 5500 HD', '2023-02-28', NULL, 'geral/manual_utilizador.pdf'),
	(19, 5, 'geral', 3, 'Certificado de Calibração MAC 5500', '2024-01-15', '2025-01-15', 'geral/manual_utilizador.pdf'),
	(20, 5, 'garantia', 8, 'Certificado de Garantia GE', '2023-02-28', '2026-02-28', 'garantia/certificado_garantia.pdf'),
	(21, 6, 'geral', 1, 'Manual Utilizador HS70A', '2022-07-14', NULL, 'geral/manual_utilizador.pdf'),
	(22, 6, 'garantia', 8, 'Certificado de Garantia Samsung', '2022-07-14', '2025-07-14', 'garantia/certificado_garantia.pdf'),
	(23, 8, 'geral', 1, 'Manual Utilizador ABL90 FLEX', '2023-05-18', NULL, 'geral/manual_utilizador.pdf'),
	(24, 8, 'garantia', 8, 'Certificado de Garantia Radiometer', '2023-05-18', '2026-05-18', 'garantia/certificado_garantia.pdf'),
	(25, 18, 'geral', 1, 'Manual Utilizador Multix Impact', '2022-05-30', NULL, 'geral/manual_utilizador.pdf'),
	(26, 18, 'geral', 7, 'Declaração de Conformidade Siemens', '2022-05-30', NULL, 'geral/manual_utilizador.pdf'),
	(27, 18, 'garantia', 8, 'Certificado de Garantia Siemens', '2022-05-30', '2025-05-30', 'garantia/certificado_garantia.pdf'),
	(28, 18, 'contrato', 4, 'Contrato Manutenção Siemens 2023', '2023-05-30', '2026-05-30', 'contrato/contrato_manutencao.pdf'),
	(29, 21, 'geral', 1, 'Manual Utilizador Caleo', '2023-03-22', NULL, 'geral/manual_utilizador.pdf'),
	(30, 21, 'garantia', 8, 'Certificado de Garantia Dräger Caleo', '2023-03-22', '2026-03-22', 'garantia/certificado_garantia.pdf'),
	(31, 24, 'geral', 1, 'Manual Utilizador Cobas c 311', '2022-04-05', NULL, 'geral/manual_utilizador.pdf'),
	(32, 24, 'garantia', 8, 'Certificado de Garantia Roche', '2022-04-05', '2025-04-05', 'garantia/certificado_garantia.pdf'),
	(33, 32, 'geral', 1, 'Manual do utilizador', '2026-06-01', NULL, 'geral/manual_utilizador.pdf'),
	(34, 33, 'geral', 1, 'Ficha Técnica', '2026-06-10', NULL, 'geral/manual_utilizador.pdf'),
	(37, 34, 'garantia', 1, 'Certificado de Garantia Monitor', '2026-06-01', NULL, 'garantia/certificado_garantia.pdf'),
	(38, 34, 'contrato', 1, 'Contrato de Manutenção 2026', '2026-06-01', NULL, 'contrato/contrato_manutencao.pdf'),
	(39, 34, 'geral', 1, 'Certificado de Calibração', '2026-06-01', '2027-06-02', 'geral/manual_utilizador.pdf'),
	(43, 7, 'garantia', 8, 'Certificado de Garantia EQ-2025-007', '2019-11-30', '2022-11-30', 'garantia/certificado_garantia.pdf'),
	(44, 9, 'garantia', 8, 'Certificado de Garantia EQ-2025-009', '2021-04-12', '2024-04-12', 'garantia/certificado_garantia.pdf'),
	(45, 10, 'garantia', 8, 'Certificado de Garantia EQ-2025-010', '2020-08-22', '2023-08-22', 'garantia/certificado_garantia.pdf'),
	(46, 11, 'garantia', 8, 'Certificado de Garantia EQ-2025-011', '2022-01-10', '2025-01-10', 'garantia/certificado_garantia.pdf'),
	(47, 12, 'garantia', 8, 'Certificado de Garantia EQ-2025-012', '2021-03-15', '2024-03-15', 'garantia/certificado_garantia.pdf'),
	(48, 13, 'garantia', 8, 'Certificado de Garantia EQ-2025-013', '2020-06-01', '2023-06-01', 'garantia/certificado_garantia.pdf'),
	(49, 14, 'garantia', 8, 'Certificado de Garantia EQ-2025-014', '2023-01-20', '2026-01-20', 'garantia/certificado_garantia.pdf'),
	(50, 15, 'garantia', 8, 'Certificado de Garantia EQ-2025-015', '2022-09-10', '2025-09-10', 'garantia/certificado_garantia.pdf'),
	(51, 16, 'garantia', 8, 'Certificado de Garantia EQ-2025-016', '2021-11-05', '2024-11-05', 'garantia/certificado_garantia.pdf'),
	(52, 17, 'garantia', 8, 'Certificado de Garantia EQ-2025-017', '2020-04-18', '2023-04-18', 'garantia/certificado_garantia.pdf'),
	(53, 19, 'garantia', 8, 'Certificado de Garantia EQ-2025-019', '2021-08-14', '2024-08-14', 'garantia/certificado_garantia.pdf'),
	(54, 20, 'garantia', 8, 'Certificado de Garantia EQ-2025-020', '2020-12-01', '2023-12-01', 'garantia/certificado_garantia.pdf'),
	(55, 22, 'garantia', 8, 'Certificado de Garantia EQ-2025-022', '2022-11-17', '2025-11-17', 'garantia/certificado_garantia.pdf'),
	(56, 23, 'garantia', 8, 'Certificado de Garantia EQ-2025-023', '2021-07-09', '2024-07-09', 'garantia/certificado_garantia.pdf'),
	(57, 25, 'garantia', 8, 'Certificado de Garantia EQ-2025-025', '2020-09-28', '2023-09-28', 'garantia/certificado_garantia.pdf'),
	(58, 26, 'garantia', 8, 'Certificado de Garantia EQ-2025-026', '2021-02-14', '2024-02-14', 'garantia/certificado_garantia.pdf'),
	(59, 27, 'garantia', 8, 'Certificado de Garantia EQ-2025-027', '2022-06-20', '2025-06-20', 'garantia/certificado_garantia.pdf'),
	(60, 28, 'garantia', 8, 'Certificado de Garantia EQ-2025-028', '2023-08-11', '2026-08-11', 'garantia/certificado_garantia.pdf'),
	(61, 29, 'garantia', 8, 'Certificado de Garantia EQ-2025-029', '2022-10-03', '2025-10-03', 'garantia/certificado_garantia.pdf'),
	(62, 30, 'garantia', 8, 'Certificado de Garantia EQ-2025-030', '2021-05-25', '2024-05-25', 'garantia/certificado_garantia.pdf'),
	(63, 31, 'garantia', 8, 'Certificado de Garantia EQ-2025-031', '2026-06-02', NULL, 'garantia/certificado_garantia.pdf'),
	(64, 32, 'garantia', 8, 'Certificado de Garantia EQ-2025-32', '2026-06-11', '2029-06-11', 'garantia/certificado_garantia.pdf'),
	(65, 33, 'garantia', 8, 'Certificado de Garantia EQ-2025-033', '2024-06-05', '2027-06-05', 'garantia/certificado_garantia.pdf'),
	(74, 2, 'aquisicao', 6, 'Contrato de Aquisição EQ-2025-002', '2021-06-20', NULL, 'aquisicao/contrato_aquisicao.pdf'),
	(75, 3, 'aquisicao', 6, 'Contrato de Aquisição EQ-2025-003', '2020-01-10', NULL, 'aquisicao/contrato_aquisicao.pdf'),
	(76, 4, 'aquisicao', 6, 'Contrato de Aquisição EQ-2025-004', '2021-09-05', NULL, 'aquisicao/contrato_aquisicao.pdf'),
	(77, 5, 'aquisicao', 6, 'Contrato de Aquisição EQ-2025-005', '2023-02-28', NULL, 'aquisicao/contrato_aquisicao.pdf'),
	(78, 6, 'aquisicao', 6, 'Contrato de Aquisição EQ-2025-006', '2022-07-14', NULL, 'aquisicao/contrato_aquisicao.pdf'),
	(79, 7, 'aquisicao', 6, 'Contrato de Aquisição EQ-2025-007', '2019-11-30', NULL, 'aquisicao/contrato_aquisicao.pdf'),
	(80, 8, 'aquisicao', 6, 'Contrato de Aquisição EQ-2025-008', '2023-05-18', NULL, 'aquisicao/contrato_aquisicao.pdf'),
	(81, 9, 'aquisicao', 6, 'Contrato de Aquisição EQ-2025-009', '2021-04-12', NULL, 'aquisicao/contrato_aquisicao.pdf'),
	(82, 10, 'aquisicao', 6, 'Contrato de Aquisição EQ-2025-010', '2020-08-22', NULL, 'aquisicao/contrato_aquisicao.pdf'),
	(83, 11, 'aquisicao', 6, 'Contrato de Aquisição EQ-2025-011', '2022-01-10', NULL, 'aquisicao/contrato_aquisicao.pdf'),
	(84, 12, 'aquisicao', 6, 'Contrato de Aquisição EQ-2025-012', '2021-03-15', NULL, 'aquisicao/contrato_aquisicao.pdf'),
	(85, 13, 'aquisicao', 6, 'Contrato de Aquisição EQ-2025-013', '2020-06-01', NULL, 'aquisicao/contrato_aquisicao.pdf'),
	(86, 14, 'aquisicao', 6, 'Contrato de Aquisição EQ-2025-014', '2023-01-20', NULL, 'aquisicao/contrato_aquisicao.pdf'),
	(87, 15, 'aquisicao', 6, 'Contrato de Aquisição EQ-2025-015', '2022-09-10', NULL, 'aquisicao/contrato_aquisicao.pdf'),
	(88, 16, 'aquisicao', 6, 'Contrato de Aquisição EQ-2025-016', '2021-11-05', NULL, 'aquisicao/contrato_aquisicao.pdf'),
	(89, 17, 'aquisicao', 6, 'Contrato de Aquisição EQ-2025-017', '2020-04-18', NULL, 'aquisicao/contrato_aquisicao.pdf'),
	(90, 18, 'aquisicao', 6, 'Contrato de Aquisição EQ-2025-018', '2022-05-30', NULL, 'aquisicao/contrato_aquisicao.pdf'),
	(91, 19, 'aquisicao', 6, 'Contrato de Aquisição EQ-2025-019', '2021-08-14', NULL, 'aquisicao/contrato_aquisicao.pdf'),
	(92, 20, 'aquisicao', 6, 'Contrato de Aquisição EQ-2025-020', '2020-12-01', NULL, 'aquisicao/contrato_aquisicao.pdf'),
	(93, 21, 'aquisicao', 6, 'Contrato de Aquisição EQ-2025-021', '2023-03-22', NULL, 'aquisicao/contrato_aquisicao.pdf'),
	(94, 22, 'aquisicao', 6, 'Contrato de Aquisição EQ-2025-022', '2022-11-17', NULL, 'aquisicao/contrato_aquisicao.pdf'),
	(95, 23, 'aquisicao', 6, 'Contrato de Aquisição EQ-2025-023', '2021-07-09', NULL, 'aquisicao/contrato_aquisicao.pdf'),
	(96, 24, 'aquisicao', 6, 'Contrato de Aquisição EQ-2025-024', '2022-04-05', NULL, 'aquisicao/contrato_aquisicao.pdf'),
	(97, 25, 'aquisicao', 6, 'Contrato de Aquisição EQ-2025-025', '2020-09-28', NULL, 'aquisicao/contrato_aquisicao.pdf'),
	(98, 26, 'aquisicao', 6, 'Contrato de Aquisição EQ-2025-026', '2021-02-14', NULL, 'aquisicao/contrato_aquisicao.pdf'),
	(99, 27, 'aquisicao', 6, 'Contrato de Aquisição EQ-2025-027', '2022-06-20', NULL, 'aquisicao/contrato_aquisicao.pdf'),
	(100, 28, 'aquisicao', 6, 'Contrato de Aquisição EQ-2025-028', '2023-08-11', NULL, 'aquisicao/contrato_aquisicao.pdf'),
	(101, 29, 'aquisicao', 6, 'Contrato de Aquisição EQ-2025-029', '2022-10-03', NULL, 'aquisicao/contrato_aquisicao.pdf'),
	(102, 30, 'aquisicao', 6, 'Contrato de Aquisição EQ-2025-030', '2021-05-25', NULL, 'aquisicao/contrato_aquisicao.pdf'),
	(103, 31, 'aquisicao', 6, 'Contrato de Aquisição EQ-2025-031', NULL, NULL, 'aquisicao/contrato_aquisicao.pdf'),
	(104, 32, 'aquisicao', 6, 'Contrato de Aquisição EQ-2025-32', '2026-06-11', NULL, 'aquisicao/contrato_aquisicao.pdf'),
	(105, 33, 'aquisicao', 6, 'Contrato de Aquisição EQ-2025-033', '2024-06-05', NULL, 'aquisicao/contrato_aquisicao.pdf'),
	(106, 34, 'aquisicao', 6, 'Contrato de Aquisição EQ-2025-034', '2026-06-02', NULL, 'aquisicao/contrato_aquisicao.pdf'),
	(137, 5, 'aquisicao', 5, 'Fatura EQ-2025-005', '2023-02-28', NULL, 'aquisicao/fatura_aquisicao.pdf'),
	(138, 6, 'aquisicao', 5, 'Fatura EQ-2025-006', '2022-07-14', NULL, 'aquisicao/fatura_aquisicao.pdf'),
	(139, 7, 'aquisicao', 5, 'Fatura EQ-2025-007', '2019-11-30', NULL, 'aquisicao/fatura_aquisicao.pdf'),
	(140, 8, 'aquisicao', 5, 'Fatura EQ-2025-008', '2023-05-18', NULL, 'aquisicao/fatura_aquisicao.pdf'),
	(141, 10, 'aquisicao', 5, 'Fatura EQ-2025-010', '2020-08-22', NULL, 'aquisicao/fatura_aquisicao.pdf'),
	(142, 11, 'aquisicao', 5, 'Fatura EQ-2025-011', '2022-01-10', NULL, 'aquisicao/fatura_aquisicao.pdf'),
	(143, 12, 'aquisicao', 5, 'Fatura EQ-2025-012', '2021-03-15', NULL, 'aquisicao/fatura_aquisicao.pdf'),
	(144, 13, 'aquisicao', 5, 'Fatura EQ-2025-013', '2020-06-01', NULL, 'aquisicao/fatura_aquisicao.pdf'),
	(145, 14, 'aquisicao', 5, 'Fatura EQ-2025-014', '2023-01-20', NULL, 'aquisicao/fatura_aquisicao.pdf'),
	(146, 15, 'aquisicao', 5, 'Fatura EQ-2025-015', '2022-09-10', NULL, 'aquisicao/fatura_aquisicao.pdf'),
	(147, 16, 'aquisicao', 5, 'Fatura EQ-2025-016', '2021-11-05', NULL, 'aquisicao/fatura_aquisicao.pdf'),
	(148, 17, 'aquisicao', 5, 'Fatura EQ-2025-017', '2020-04-18', NULL, 'aquisicao/fatura_aquisicao.pdf'),
	(149, 18, 'aquisicao', 5, 'Fatura EQ-2025-018', '2022-05-30', NULL, 'aquisicao/fatura_aquisicao.pdf'),
	(150, 19, 'aquisicao', 5, 'Fatura EQ-2025-019', '2021-08-14', NULL, 'aquisicao/fatura_aquisicao.pdf'),
	(151, 20, 'aquisicao', 5, 'Fatura EQ-2025-020', '2020-12-01', NULL, 'aquisicao/fatura_aquisicao.pdf'),
	(152, 21, 'aquisicao', 5, 'Fatura EQ-2025-021', '2023-03-22', NULL, 'aquisicao/fatura_aquisicao.pdf'),
	(153, 22, 'aquisicao', 5, 'Fatura EQ-2025-022', '2022-11-17', NULL, 'aquisicao/fatura_aquisicao.pdf'),
	(154, 24, 'aquisicao', 5, 'Fatura EQ-2025-024', '2022-04-05', NULL, 'aquisicao/fatura_aquisicao.pdf'),
	(155, 25, 'aquisicao', 5, 'Fatura EQ-2025-025', '2020-09-28', NULL, 'aquisicao/fatura_aquisicao.pdf'),
	(156, 26, 'aquisicao', 5, 'Fatura EQ-2025-026', '2021-02-14', NULL, 'aquisicao/fatura_aquisicao.pdf'),
	(157, 27, 'aquisicao', 5, 'Fatura EQ-2025-027', '2022-06-20', NULL, 'aquisicao/fatura_aquisicao.pdf'),
	(158, 28, 'aquisicao', 5, 'Fatura EQ-2025-028', '2023-08-11', NULL, 'aquisicao/fatura_aquisicao.pdf'),
	(159, 29, 'aquisicao', 5, 'Fatura EQ-2025-029', '2022-10-03', NULL, 'aquisicao/fatura_aquisicao.pdf'),
	(160, 31, 'aquisicao', 5, 'Fatura EQ-2025-031', NULL, NULL, 'aquisicao/fatura_aquisicao.pdf'),
	(161, 32, 'aquisicao', 5, 'Fatura EQ-2025-32', '2026-06-11', NULL, 'aquisicao/fatura_aquisicao.pdf'),
	(162, 34, 'aquisicao', 5, 'Fatura EQ-2025-034', '2026-06-02', NULL, 'aquisicao/fatura_aquisicao.pdf'),
	(168, 5, 'contrato', 4, 'Contrato de Manutenção EQ-2025-005', '2024-02-28', '2027-02-28', 'contrato/contrato_manutencao.pdf'),
	(169, 6, 'contrato', 4, 'Contrato de Manutenção EQ-2025-006', '2023-07-14', '2026-07-14', 'contrato/contrato_manutencao.pdf'),
	(170, 8, 'contrato', 4, 'Contrato de Manutenção EQ-2025-008', '2024-05-18', '2027-05-18', 'contrato/contrato_manutencao.pdf'),
	(171, 12, 'contrato', 4, 'Contrato de Manutenção EQ-2025-012', '2023-03-15', '2026-03-15', 'contrato/contrato_manutencao.pdf'),
	(172, 14, 'contrato', 4, 'Contrato de Manutenção EQ-2025-014', '2024-01-20', '2027-01-20', 'contrato/contrato_manutencao.pdf'),
	(173, 16, 'contrato', 4, 'Contrato de Manutenção EQ-2025-016', '2023-11-05', '2026-11-05', 'contrato/contrato_manutencao.pdf'),
	(174, 21, 'contrato', 4, 'Contrato de Manutenção EQ-2025-021', '2024-03-22', '2027-03-22', 'contrato/contrato_manutencao.pdf'),
	(175, 24, 'contrato', 4, 'Contrato de Manutenção EQ-2025-024', '2023-04-05', '2026-04-05', 'contrato/contrato_manutencao.pdf'),
	(176, 32, 'contrato', 4, 'Contrato de Manutenção EQ-2025-32', '2026-06-09', '2028-06-07', 'contrato/contrato_manutencao.pdf'),
	(183, 1, 'contrato', 4, 'Contrato de Manutenção 2022', '2022-04-05', NULL, 'contrato/contrato_manutencao.pdf');

-- A despejar dados para tabela db1241344.equipamento_fornecedor: ~39 rows (aproximadamente)
DELETE FROM `equipamento_fornecedor`;
INSERT INTO `equipamento_fornecedor` (`id`, `equipamento_id`, `fornecedor_id`, `tipo_relacao`, `pessoa_contacto`, `telefone_contacto`, `morada_associada`, `observacoes`) VALUES
	(2, 1, 3, 'Assistência Técnica', 'Tiago Marques', '934567890', 'Rua do Comércio, Porto', 'Responsável pelas revisões anuais'),
	(3, 2, 2, 'Fabricante', 'Maria Costa', '923456789', 'Av. da Liberdade, Lisboa', NULL),
	(4, 2, 3, 'Assistência Técnica', 'Rui Mendes', '934567890', 'Rua do Comércio, Porto', NULL),
	(5, 3, 4, 'Fabricante', 'Sofia Lopes', '945678901', 'Rua Quinta do Paizinho, Lisboa', NULL),
	(6, 4, 1, 'Distribuidor / Comercial', 'João Silva', '912345678', 'Armazém Norte, Braga', NULL),
	(7, 4, 3, 'Assistência Técnica', 'Rui Mendes', '934567890', 'Rua do Comércio, Porto', NULL),
	(8, 5, 7, 'Fabricante', 'Inês Rodrigues', '962345678', 'Rua Tomás da Fonseca, Lisboa', NULL),
	(9, 6, 1, 'Distribuidor / Comercial', 'João Silva', '912345678', 'Armazém Norte, Braga', NULL),
	(10, 6, 9, 'Assistência Técnica', 'Carla Santos', '964567890', 'Rua da Restauração, Porto', NULL),
	(11, 7, 3, 'Assistência Técnica', 'Rui Mendes', '934567890', 'Rua do Comércio, Porto', NULL),
	(12, 8, 1, 'Distribuidor / Comercial', 'João Silva', '912345678', 'Armazém Norte, Braga', NULL),
	(13, 9, 1, 'Distribuidor / Comercial', 'João Silva', '912345678', 'Armazém Norte, Braga', NULL),
	(14, 12, 12, 'Assistência Técnica', 'Bruno Pinto', '967890123', 'Rua do Pinhal, Coimbra', NULL),
	(15, 14, 9, 'Assistência Técnica', 'Carla Santos', '964567890', 'Rua da Restauração, Porto', NULL),
	(16, 16, 3, 'Assistência Técnica', 'Rui Mendes', '934567890', 'Rua do Comércio, Porto', NULL),
	(17, 18, 8, 'Fabricante', 'Pedro Almeida', '961234567', 'Av. José Malhoa, Lisboa', NULL),
	(18, 18, 9, 'Assistência Técnica', 'Carla Santos', '964567890', 'Rua da Restauração, Porto', NULL),
	(19, 21, 2, 'Fabricante', 'Maria Costa', '923456789', 'Av. da Liberdade, Lisboa', NULL),
	(20, 24, 11, 'Fabricante', 'Margarida Fonseca', '966789012', 'Estrada Nacional 249, Amadora', NULL),
	(21, 28, 10, 'Consumíveis / Acessórios', 'André Oliveira', '965678901', 'Zona Industrial de Aveiro', NULL),
	(22, 30, 5, 'Distribuidor / Comercial', 'Carlos Neves', '956789012', 'Zona Industrial, Coimbra', NULL),
	(23, 32, 1, 'Fabricante', 'João Pereira', '987654367', 'A', ''),
	(24, 33, 2, 'Distribuidor / Comercial', 'Maria Gomes', '934561229', 'Rua dos Hospitais, Lisboa', ''),
	(25, 33, 3, 'Consumíveis / Acessórios', 'Filipe Silva', '913456443', 'Rua das Flores, Porto', ''),
	(26, 34, 11, 'Distribuidor / Comercial', 'Maria Gomes', '934567893', 'Rua da Sá', ''),
	(28, 11, 12, 'Fabricante', 'Bruno Pinto', '967890123', 'Rua do Pinhal, 34, Coimbra', 'Associação gerada automaticamente para demonstração'),
	(29, 15, 3, 'Fabricante', 'Rui Mendes', '934567890', 'Rua do Comércio, 12, Porto', 'Associação gerada automaticamente para demonstração'),
	(30, 26, 1, 'Fabricante', 'Afonso Coutinho', '912345678', 'Rua das Indústrias, 45, Braga', 'Associação gerada automaticamente para demonstração'),
	(31, 31, 8, 'Fabricante', 'Pedro Almeida', '961234567', 'Av. José Malhoa, 16, Lisboa', 'Associação gerada automaticamente para demonstração'),
	(32, 29, 4, 'Fabricante', 'Sofia Lopes', '945678901', 'Rua Quinta do Paizinho, Lisboa', 'Associação gerada automaticamente para demonstração'),
	(33, 13, 1, 'Fabricante', 'Afonso Coutinho', '912345678', 'Rua das Indústrias, 45, Braga', 'Associação gerada automaticamente para demonstração'),
	(34, 17, 5, 'Fabricante', 'Carlos Neves', '956789012', 'Zona Industrial, Lote 8, Coimbra', 'Associação gerada automaticamente para demonstração'),
	(35, 22, 10, 'Fabricante', 'André Oliveira', '965678901', 'Zona Industrial de Aveiro, Lote 22', 'Associação gerada automaticamente para demonstração'),
	(36, 23, 11, 'Fabricante', 'Margarida Fonseca', '966789012', 'Estrada Nacional 249, Amadora', 'Associação gerada automaticamente para demonstração'),
	(37, 10, 11, 'Fabricante', 'Margarida Fonseca', '966789012', 'Estrada Nacional 249, Amadora', 'Associação gerada automaticamente para demonstração'),
	(38, 27, 2, 'Fabricante', 'Maria Costa', '923456789', 'Av. da Liberdade, 200, Lisboa', 'Associação gerada automaticamente para demonstração'),
	(39, 19, 7, 'Fabricante', 'Inês Rodrigues', '962345678', 'Rua Tomás da Fonseca, 55, Lisboa', 'Associação gerada automaticamente para demonstração'),
	(40, 20, 8, 'Fabricante', 'Tiago Marques', '963456789', 'Lagoas Park, Edifício 3, Oeiras', 'Associação gerada automaticamente para demonstração'),
	(41, 25, 13, 'Fabricante', 'Afonso Queirós', '945362904', 'Rua da Agra, Porto', 'Associação gerada automaticamente para demonstração');

-- A despejar dados para tabela db1241344.equipamentos: ~34 rows (aproximadamente)
DELETE FROM `equipamentos`;
INSERT INTO `equipamentos` (`id`, `codigo`, `designacao`, `marca`, `modelo`, `fabricante`, `numero_serie`, `categoria`, `estado`, `criticidade`, `localizacao_id`, `data_aquisicao`, `ano_fabrico`, `custo`, `tipo_entrada`, `observacoes`, `equipamento_ativo`) VALUES
	(1, 'EQ001', 'Monitor Multiparamétrico', 'Philips', 'IntelliVue MP5', 'Philips Healthcare', 'MP5-2022-45873', 'Monitorização', 'Em manutenção', 'Alta', 2, '2022-03-16', 2022, 12500.00, 'compra', '', 0),
	(2, 'EQ002', 'Ventilador Pulmonar', 'Dräger', 'Evita V500', 'Dräger Medical', 'EV500-2021-9934', 'Suporte de vida', 'Ativo', 'Suporte de vida', 3, '2021-06-20', 2021, 35000.00, 'compra', NULL, 1),
	(3, 'EQ003', 'Bomba de Infusão', 'B. Braun', 'Infusomat Space', 'B. Braun Melsungen', 'INF-2020-88321', 'Terapia', 'Ativo', 'Média', 8, '2020-01-10', 2020, 3200.00, 'compra', NULL, 1),
	(4, 'EQ004', 'Desfibrilhador', 'Zoll', 'R Series', 'Zoll Medical', 'ZR-2021-7712', 'Suporte de vida', 'Ativo', 'Alta', 1, '2021-09-05', 2021, 18000.00, 'compra', NULL, 1),
	(5, 'EQ005', 'Eletrocardiógrafo', 'GE Healthcare', 'MAC 5500 HD', 'GE Healthcare', 'MAC-2023-33210', 'Diagnóstico', 'Em calibração', 'Alta', 7, '2023-02-28', 2023, 9800.00, 'compra', NULL, 1),
	(6, 'EQ006', 'Ecógrafo', 'Samsung', 'HS70A', 'Samsung Medison', 'HS70-2022-11456', 'Diagnóstico', 'Ativo', 'Alta', 9, '2022-07-14', 2022, 45000.00, 'compra', NULL, 1),
	(7, 'EQ007', 'Autoclave', 'Tuttnauer', '3870EA', 'Tuttnauer Europe', 'TUT-2019-55432', 'Esterilização', 'Em manutenção', 'Média', 6, '2019-11-30', 2019, 7500.00, 'compra', NULL, 1),
	(8, 'EQ008', 'Analisador de Gases', 'Radiometer', 'ABL90 FLEX', 'Radiometer Medical', 'ABL-2023-77654', 'Laboratório', 'Ativo', 'Alta', 10, '2023-05-18', 2023, 28000.00, 'compra', NULL, 1),
	(9, 'EQ009', 'Monitor de Sinais Vitais', 'Mindray', 'MEC-15', 'Mindray Medical', 'MEC-2021-44321', 'Monitorização', 'Inativo', 'Média', 4, '2021-04-12', 2021, 6500.00, 'doacao', 'Recebido por doação da Fundação Saúde Portugal', 1),
	(10, 'EQ010', 'Cama Articulada Elétrica', 'Stryker', 'InTouch', 'Stryker Medical', 'STR-2020-99123', 'Reabilitação', 'Ativo', 'Baixa', 8, '2020-08-22', 2020, 4200.00, 'compra', NULL, 1),
	(11, 'EQ011', 'Oxímetro de Pulso', 'Nonin', '9590', 'Nonin Medical', 'NON-2022-12345', 'Monitorização', 'Ativo', 'Média', 1, '2022-01-10', 2022, 850.00, 'compra', NULL, 1),
	(12, 'EQ012', 'Ventilador de Transporte', 'Hamilton', 'T1', 'Hamilton Medical', 'HAM-2021-54321', 'Suporte de vida', 'Ativo', 'Suporte de vida', 2, '2021-03-15', 2021, 22000.00, 'compra', NULL, 1),
	(13, 'EQ013', 'Bomba de Seringa', 'B. Braun', 'Perfusor Space', 'B. Braun Melsungen', 'PER-2020-11111', 'Terapia', 'Ativo', 'Média', 4, '2020-06-01', 2020, 2800.00, 'compra', NULL, 1),
	(14, 'EQ014', 'Monitor de Pressão Invasiva', 'Edwards', 'ClearSight', 'Edwards Lifesciences', 'EDW-2023-22222', 'Monitorização', 'Ativo', 'Alta', 3, '2023-01-20', 2023, 15000.00, 'compra', NULL, 1),
	(15, 'EQ015', 'Desfibrilhador Automático', 'Philips', 'HeartStart FRx', 'Philips Healthcare', 'PHI-2022-33333', 'Suporte de vida', 'Ativo', 'Suporte de vida', 1, '2022-09-10', 2022, 2500.00, 'compra', NULL, 1),
	(16, 'EQ016', 'Bisturi Elétrico', 'Erbe', 'VIO 300 D', 'Erbe Elektromedizin', 'ERB-2021-44444', 'Terapia', 'Ativo', 'Alta', 6, '2021-11-05', 2021, 19000.00, 'compra', NULL, 1),
	(17, 'EQ017', 'Aspirador Cirúrgico', 'Medela', 'Dominant 50', 'Medela AG', 'MED-2020-55555', 'Terapia', 'Em manutenção', 'Média', 6, '2020-04-18', 2020, 3100.00, 'compra', NULL, 1),
	(18, 'EQ018', 'Aparelho de Raio-X', 'Siemens', 'Multix Impact', 'Siemens Healthineers', 'SIE-2022-66666', 'Diagnóstico', 'Ativo', 'Alta', 9, '2022-05-30', 2022, 85000.00, 'compra', NULL, 1),
	(19, 'EQ019', 'Centrifugadora', 'Eppendorf', '5810R', 'Eppendorf AG', 'EPP-2021-77777', 'Laboratório', 'Ativo', 'Média', 10, '2021-08-14', 2021, 6200.00, 'compra', NULL, 1),
	(20, 'EQ020', 'Microscópio Clínico', 'Olympus', 'CX43', 'Olympus Corporation', 'OLY-2020-88888', 'Laboratório', 'Ativo', 'Média', 10, '2020-12-01', 2020, 4800.00, 'compra', NULL, 1),
	(21, 'EQ021', 'Incubadora Neonatal', 'Dräger', 'Caleo', 'Dräger Medical', 'DRA-2023-99999', 'Suporte de vida', 'Ativo', 'Suporte de vida', 6, '2023-03-22', 2023, 32000.00, 'compra', NULL, 1),
	(22, 'EQ022', 'Monitor Fetal', 'GE Healthcare', 'Corometrics 250cx', 'GE Healthcare', 'GEF-2022-10101', 'Monitorização', 'Ativo', 'Alta', 6, '2022-11-17', 2022, 11000.00, 'compra', NULL, 1),
	(23, 'EQ023', 'Cardiotocógrafo', 'Philips', 'Avalon FM40', 'Philips Healthcare', 'AVA-2021-20202', 'Monitorização', 'Inativo', 'Alta', 6, '2021-07-09', 2021, 9500.00, 'emprestimo', 'Equipamento em regime de empréstimo', 1),
	(24, 'EQ024', 'Analisador Bioquímico', 'Roche', 'Cobas c 311', 'Roche Diagnostics', 'ROC-2022-30303', 'Laboratório', 'Ativo', 'Alta', 10, '2022-04-05', 2022, 42000.00, 'compra', NULL, 1),
	(25, 'EQ025', 'Equipamento de Fisioterapia', 'Enraf-Nonius', 'Sonopuls 492', 'Enraf-Nonius', 'ENR-2020-40404', 'Reabilitação', 'Ativo', 'Baixa', 10, '2020-09-28', 2020, 2200.00, 'compra', NULL, 1),
	(26, 'EQ026', 'Carro de Emergência', 'Stryker', 'Power-PRO XT', 'Stryker Medical', 'STR-2021-50505', 'Suporte de vida', 'Ativo', 'Alta', 1, '2021-02-14', 2021, 8500.00, 'compra', NULL, 1),
	(27, 'EQ027', 'Nebulizador', 'PARI', 'Boy SX', 'PARI GmbH', 'PAR-2022-60606', 'Terapia', 'Ativo', 'Baixa', 8, '2022-06-20', 2022, 420.00, 'compra', NULL, 1),
	(28, 'EQ028', 'Tensiômetro Digital', 'Omron', 'HBP-1300', 'Omron Healthcare', 'OMR-2023-70707', 'Diagnóstico', 'Ativo', 'Baixa', 7, '2023-08-11', 2023, 380.00, 'compra', NULL, 1),
	(29, 'EQ029', 'Termómetro Infravermelho', 'Braun', 'ThermoScan 7', 'Braun GmbH', 'BRA-2022-80808', 'Diagnóstico', 'Em quarentena', 'Baixa', 2, '2022-10-03', 2022, 95.00, 'compra', 'Em avaliação técnica', 1),
	(30, 'EQ030', 'Cadeira de Rodas Elétrica', 'Permobil', 'M3 Corpus', 'Permobil AB', 'PER-2021-90909', 'Reabilitação', 'Ativo', 'Baixa', 10, '2021-05-25', 2021, 5800.00, 'aluguer', 'Equipamento em regime de aluguer', 1),
	(31, 'EQ031', 'Monitor Multiparamétrico', 'Philips', 'Intellivue Mp5', 'Philips Healthcare', 'mp5-2022-45873', 'Monitorização', 'Ativo', 'Baixa', 1, NULL, 2022, NULL, 'compra', '', 1),
	(32, 'EQ032', 'Monitor Multiparamétrico', 'Philips', 'Intellivue Mp5', 'Philips Healthcare', 'mp5-2022-45873', 'Monitorização', 'Ativo', 'Baixa', 1, '2026-06-11', 2022, 1000.00, 'compra', '', 1),
	(33, 'EQ033', 'Ventilador Pulmonar', 'Dräger', 'Evita V300', 'Dräger Medical', 'EV300-2023-00123', 'Suporte de vida', 'Ativo', 'Suporte de vida', 2, '2024-06-05', 2023, 5000.00, 'aluguer', '', 1),
	(34, 'EQ034', 'Monitor Multiparamétrico', 'Philips', 'Intellivue Mp5', 'Philips Healthcare', 'mp5-2022-5432', 'Monitorização', 'Em manutenção', 'Média', 10, '2026-06-02', 2024, 6000.00, 'compra', '', 1);

-- A despejar dados para tabela db1241344.fornecedores: ~13 rows (aproximadamente)
DELETE FROM `fornecedores`;
INSERT INTO `fornecedores` (`id`, `codigo`, `nome`, `nif`, `telefone`, `email`, `morada`, `website`, `pessoa_contacto`, `telefone_contacto`, `tipo_fornecedor`, `observacoes`, `fornecedor_ativo`) VALUES
	(1, 'F001', 'MedTech Solutions', '123456789', '253000001', 'geral@medtech.pt', 'Rua das Indústrias, 45, Braga', 'www.medtech.pt', 'Afonso Coutinho', '912345678', 'Fabricante', NULL, 1),
	(2, 'F002', 'Dräger Portugal', '987654321', '214000002', 'info@draeger.pt', 'Av. da Liberdade, 200, Lisboa', 'www.draeger.pt', 'Maria Costa', '923456789', 'Distribuidor / Comercial', NULL, 1),
	(3, 'F003', 'TecAssist', '456789123', '229000003', 'suporte@tecassist.pt', 'Rua do Comércio, 12, Porto', 'www.tecassist.pt', 'Rui Mendes', '934567890', 'Assistência Técnica', 'Empresa de manutenção preventiva e corretiva', 1),
	(4, 'F004', 'B. Braun Portugal', '321654987', '212000004', 'contacto@bbraun.pt', 'Rua Quinta do Paizinho, Lisboa', 'www.bbraun.pt', 'Sofia Lopes', '945678901', 'Fabricante', NULL, 1),
	(5, 'F005', 'MedSupply', '654321789', '225000005', 'vendas@medsupply.pt', 'Zona Industrial, Lote 8, Coimbra', 'www.medsupply.pt', 'Carlos Neves', '956789012', 'Consumíveis / Acessórios', 'Fornecedor de consumíveis e acessórios médicos', 1),
	(6, 'F006', 'Siemens Healthineers Portugal', '234567891', '213000006', 'info@siemens-healthineers.pt', 'Av. José Malhoa, 16, Lisboa', 'www.siemens-healthineers.pt', 'Pedro Almeida', '961234567', 'Fabricante', NULL, 0),
	(7, 'F007', 'GE Healthcare Portugal', '345678912', '216000007', 'geral@gehealthcare.pt', 'Rua Tomás da Fonseca, 55, Lisboa', 'www.gehealthcare.pt', 'Inês Rodrigues', '962345678', 'Distribuidor / Comercial', NULL, 1),
	(8, 'F008', 'Philips Healthcare Portugal', '456789123', '219000008', 'philips@healthcare.pt', 'Lagoas Park, Edifício 3, Oeiras', 'www.philips.pt', 'Tiago Marques', '963456789', 'Fabricante', NULL, 1),
	(9, 'F009', 'MedEquip Serviços', '567891234', '222000009', 'servicos@medequip.pt', 'Rua da Restauração, 78, Porto', 'www.medequip.pt', 'Carla Santos', '964567890', 'Assistência Técnica', 'Especializada em manutenção de equipamentos de imagiologia', 1),
	(10, 'F010', 'BioMed Consumíveis', '678912345', '234000010', 'vendas@biomed.pt', 'Zona Industrial de Aveiro, Lote 22', 'www.biomed.pt', 'André Oliveira', '965678901', 'Consumíveis / Acessórios', NULL, 1),
	(11, 'F011', 'Roche Diagnósticos', '789123456', '214500011', 'diagnosticos@roche.pt', 'Estrada Nacional 249, Amadora', 'www.roche.pt', 'Margarida Fonseca', '966789012', 'Fabricante', NULL, 1),
	(12, 'F012', 'TechMed Assistência', '891234567', '228000012', 'assistencia@techmed.pt', 'Rua do Pinhal, 34, Coimbra', 'www.techmed.pt', 'Bruno Pinto', '967890123', 'Assistência Técnica', 'Cobertura nacional de assistência técnica', 1),
	(13, 'F013', 'Medin Portugal', '123456789', '234554565', 'email@45', 'Rua da Agra, Porto', NULL, 'Afonso Queirós', '945362904', 'Fabricante', NULL, 1);

-- A despejar dados para tabela db1241344.garantias: ~34 rows (aproximadamente)
DELETE FROM `garantias`;
INSERT INTO `garantias` (`id`, `equipamento_id`, `data_inicio`, `data_fim`, `entidade_responsavel`, `observacoes`) VALUES
	(1, 1, '2022-03-15', '2025-03-13', 'Fabricante', NULL),
	(2, 2, '2021-06-20', '2024-06-20', 'Fabricante', NULL),
	(3, 3, '2020-01-10', '2023-01-10', 'Fabricante', NULL),
	(4, 4, '2021-09-05', '2024-09-05', 'Fabricante', NULL),
	(5, 5, '2023-02-28', '2026-02-28', 'Fabricante', NULL),
	(6, 6, '2022-07-14', '2025-07-14', 'Fabricante', NULL),
	(7, 7, '2019-11-30', '2022-11-30', 'Fabricante', 'Garantia já expirada'),
	(8, 8, '2023-05-18', '2026-05-18', 'Fabricante', NULL),
	(9, 9, '2021-04-12', '2024-04-12', 'Fabricante', NULL),
	(10, 10, '2020-08-22', '2023-08-22', 'Fabricante', 'Garantia já expirada'),
	(11, 11, '2022-01-10', '2025-01-10', 'Fabricante', NULL),
	(12, 12, '2021-03-15', '2024-03-15', 'Fabricante', NULL),
	(13, 13, '2020-06-01', '2023-06-01', 'Fabricante', 'Garantia já expirada'),
	(14, 14, '2023-01-20', '2026-01-20', 'Fabricante', NULL),
	(15, 15, '2022-09-10', '2025-09-10', 'Fabricante', NULL),
	(16, 16, '2021-11-05', '2024-11-05', 'Fabricante', NULL),
	(17, 17, '2020-04-18', '2023-04-18', 'Fabricante', 'Garantia já expirada'),
	(18, 18, '2022-05-30', '2025-05-30', 'Fabricante', NULL),
	(19, 19, '2021-08-14', '2024-08-14', 'Fabricante', NULL),
	(20, 20, '2020-12-01', '2023-12-01', 'Fabricante', 'Garantia já expirada'),
	(21, 21, '2023-03-22', '2026-03-22', 'Fabricante', NULL),
	(22, 22, '2022-11-17', '2025-11-17', 'Fabricante', NULL),
	(23, 23, '2021-07-09', '2024-07-09', 'Fabricante', NULL),
	(24, 24, '2022-04-05', '2025-04-05', 'Fabricante', NULL),
	(25, 25, '2020-09-28', '2023-09-28', 'Fabricante', 'Garantia já expirada'),
	(26, 26, '2021-02-14', '2024-02-14', 'Fabricante', NULL),
	(27, 27, '2022-06-20', '2025-06-20', 'Fabricante', NULL),
	(28, 28, '2023-08-11', '2026-08-11', 'Fabricante', NULL),
	(29, 29, '2022-10-03', '2025-10-03', 'Fabricante', NULL),
	(30, 30, '2021-05-25', '2024-05-25', 'Fabricante', NULL),
	(31, 32, '2026-06-04', '2031-06-18', 'Fabricante', ''),
	(32, 33, '2026-06-09', '2028-06-01', 'Distribuidor Autorizado', ''),
	(33, 34, '2026-06-02', '2027-06-10', 'Fabricante', ''),
	(35, 31, '2026-06-03', '2027-06-09', 'Fabricante', NULL);

-- A despejar dados para tabela db1241344.gestao_area_publica: ~17 rows (aproximadamente)
DELETE FROM `gestao_area_publica`;
INSERT INTO `gestao_area_publica` (`id`, `secao`, `titulo`, `texto`, `imagem`) VALUES
	(1, 'hero', 'HospitalGest - Gestão de Inventário Hospitalar!', 'Solução completa para gerir o inventário de equipamentos médicos da sua instituição de saúde.', 'hero.jpg'),
	(4, 'contactos', 'Contacte-nos', 'Estamos disponíveis para o ajudar. Entre em contacto connosco através do formulário ou pelos nossos contactos diretos.', NULL),
	(5, 'rodape_morada', 'Morada', 'Rua da Saúde Digital, 42, 4000-123 Porto, Portugal', NULL),
	(6, 'rodape_telefone', 'Telefone', '+351 220 000 002', NULL),
	(7, 'rodape_email', 'Email', 'geral@hospitalgest.pt', NULL),
	(8, 'sobre_nos_intro', 'Texto Introdutório', 'A HospitalGest desenvolve soluções digitais para apoiar a gestão do inventário hospitalar, substituindo processos dispersos por uma plataforma centralizada, intuitiva e eficiente.', NULL),
	(9, 'sobre_nos_problema', 'O Problema', 'Hospitais usam Excel, documentos soltos e registos manuais.', NULL),
	(10, 'sobre_nos_solucao', 'A Nossa Solução', 'Aplicação web para organizar equipamentos, fornecedores e localizações, com rastreabilidade total do ciclo de vida de cada equipamento.', NULL),
	(11, 'sobre_nos_oferecemos', 'O Que Oferecemos', 'Inventário estruturado, rastreabilidade completa, gestão documental, fornecedores, localizações e contratos, dashboard com indicadores e gráficos.', NULL),
	(12, 'sobre_nos_objetivo', 'Objetivo', 'Criar uma plataforma que ajude os hospitais a organizar melhor os seus equipamentos, documentação e fornecedores.', NULL),
	(13, 'servico_equipamentos', 'Gestão de Equipamentos', 'Registo, edição, consulta e desativação controlada de equipamentos médicos, com histórico sempre preservado.', NULL),
	(14, 'servico_localizacoes', 'Localizações', 'Organização dos equipamentos por edifício, piso, serviço e sala.', NULL),
	(15, 'servico_fornecedores', 'Fornecedores', 'Associação de fabricantes, distribuidores e empresas de assistência técnica.', NULL),
	(16, 'servico_documentacao', 'Documentação', 'Gestão de manuais, certificados, contratos e relatórios técnicos.', NULL),
	(17, 'servico_garantias', 'Garantias e Contratos', 'Consulta rápida de datas, validade e entidades responsáveis.', NULL),
	(18, 'servico_dashboard', 'Dashboard', 'Indicadores e gráficos em tempo real sobre o estado, criticidade e distribuição dos equipamentos.', NULL),
	(19, 'rodape_horario', 'Todos os dias', '07:00h - 22:00h', NULL);

-- A despejar dados para tabela db1241344.localizacoes: ~11 rows (aproximadamente)
DELETE FROM `localizacoes`;
INSERT INTO `localizacoes` (`id`, `codigo`, `servico_id`, `edificio`, `piso`, `sala`, `observacoes`, `localizacao_ativa`) VALUES
	(1, 'LOC001', 1, 'Edifício A', 'Piso 0', 'Urgência Geral', NULL, 1),
	(2, 'LOC002', 1, 'Edifício A', 'Piso 0', 'Triagem', NULL, 1),
	(3, 'LOC003', 2, 'Edifício B', 'Piso 2', 'UCI - Sala 1', NULL, 1),
	(4, 'LOC004', 2, 'Edifício B', 'Piso 2', 'UCI - Sala 2', NULL, 1),
	(5, 'LOC005', 3, 'Edifício C', 'Piso 1', 'Bloco 1', NULL, 1),
	(6, 'LOC006', 3, 'Edifício C', 'Piso 1', 'Bloco 2', NULL, 1),
	(7, 'LOC007', 4, 'Edifício A', 'Piso 3', 'Cardiologia - Gabinete 1', NULL, 1),
	(8, 'LOC008', 5, 'Edifício A', 'Piso 1', 'Medicina Interna - Sala 4', NULL, 1),
	(9, 'LOC009', 8, 'Edifício D', 'Piso 0', 'Radiologia - Sala Principal', NULL, 1),
	(10, 'LOC010', 9, 'Edifício D', 'Piso 1', 'Laboratório Central', NULL, 1),
	(11, 'LOC011', 3, 'Edifício A', 'Piso 0', 'Sala 12', 'Localizacao 11', 1);

-- A despejar dados para tabela db1241344.mensagens_publico: ~6 rows (aproximadamente)
DELETE FROM `mensagens_publico`;
INSERT INTO `mensagens_publico` (`id`, `nome`, `email`, `assunto`, `mensagem`, `data_envio`, `lida`) VALUES
	(1, 'João Pereira', 'joao.pereira@gmail.com', 'Pedido de informação', 'Bom dia, gostaria de saber mais sobre os vossos serviços de gestão de inventário hospitalar.', '2025-03-10 09:15:00', 1),
	(2, 'Maria Santos', 'maria.santos@hospital-braga.pt', 'Orçamento', 'Olá, somos um hospital de média dimensão e gostaríamos de receber um orçamento para implementação do sistema.', '2025-04-22 14:30:00', 1),
	(3, 'Carlos Oliveira', 'carlos.oliveira@clinica-porto.pt', 'Demonstração do sistema', 'Seria possível agendar uma demonstração do sistema para a nossa equipa técnica?', '2025-05-05 11:00:00', 1),
	(4, 'Ana Rodrigues', 'ana.rodrigues@gmail.com', 'Dúvida técnica', 'Boa tarde, o vosso sistema suporta integração com sistemas de manutenção já existentes?', '2025-05-18 16:45:00', 1),
	(5, 'Pedro Ferreira', 'pedro.ferreira@centrosaudelisboa.pt', 'Parceria', 'Estamos interessados em estabelecer uma parceria para implementação do sistema em vários centros de saúde.', '2026-01-08 10:20:00', 0),
	(6, 'Maria Costa', 'mariacosta14@gmail.com', 'Equipamentos', 'Como funcionam os vossos serviços?', '2026-06-20 19:53:03', 0);

-- A despejar dados para tabela db1241344.servicos: ~10 rows (aproximadamente)
DELETE FROM `servicos`;
INSERT INTO `servicos` (`id`, `nome`) VALUES
	(1, 'Urgência'),
	(2, 'Unidade de Cuidados Intensivos'),
	(3, 'Bloco Operatório'),
	(4, 'Cardiologia'),
	(5, 'Medicina Interna'),
	(6, 'Pediatria'),
	(7, 'Ortopedia'),
	(8, 'Radiologia'),
	(9, 'Laboratório'),
	(10, 'Fisioterapia');

-- A despejar dados para tabela db1241344.tipos_documento: ~10 rows (aproximadamente)
DELETE FROM `tipos_documento`;
INSERT INTO `tipos_documento` (`id`, `nome`) VALUES
	(1, 'Manual de Utilizador'),
	(2, 'Manual de Serviço'),
	(3, 'Certificado de Calibração'),
	(4, 'Contrato de Manutenção'),
	(5, 'Fatura de Aquisição'),
	(6, 'Contrato de Aquisição'),
	(7, 'Declaração de Conformidade'),
	(8, 'Certificado de Garantia'),
	(9, 'Relatório Técnico'),
	(10, 'Outro');

-- A despejar dados para tabela db1241344.utilizadores: ~3 rows (aproximadamente)
DELETE FROM `utilizadores`;
INSERT INTO `utilizadores` (`id`, `nome`, `email`, `password`, `perfil`) VALUES
	(1, 'Matilde Queirós', 'admin@hospitalgest.pt', '$2y$10$etKqZvlcKbb7.VD1kY7kROXQZKxxOsKZ5HvAsDRK1Vk1nieW9o7GO', 'administrador'),
	(3, 'Ricardo Tavares', 'tecnico@hospitalgest.pt', '$2y$10$WOE1lgomBLI4jNds6Ufum.dnjjbXKNcnF3LZ2lR19hk1Vw2hN7bUe', 'tecnico'),
	(4, 'Ana Pereira', 'enfermeiro@hospitalgest.pt', '$2y$10$l2MZy3yhDKgoWlXEV582YOG7mP7PEBlR7swICadcnJEK6ikQwE5L2', 'profissional_saude');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
