-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 16/04/2026 às 12:19
-- Versão do servidor: 9.1.0
-- Versão do PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `tcc1`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

DROP TABLE IF EXISTS `clientes`;
CREATE TABLE IF NOT EXISTS `clientes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `endereco` text,
  `criado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `clientes`
--

INSERT INTO `clientes` (`id`, `nome`, `email`, `telefone`, `endereco`, `criado_em`) VALUES
(1, 'Dimitri Teste', NULL, NULL, 'Rua Exemplo, 123 - Centro', '2025-11-29 17:57:05'),
(2, 'Angelita Teste', NULL, NULL, 'Av. Principal, 456', '2025-11-29 17:57:05');

-- --------------------------------------------------------

--
-- Estrutura para tabela `entregas`
--

DROP TABLE IF EXISTS `entregas`;
CREATE TABLE IF NOT EXISTS `entregas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_pedido` int DEFAULT NULL,
  `metodo_envio` enum('retirada_loja','entrega') NOT NULL,
  `status_envio` varchar(20) DEFAULT 'aguardando',
  `endereco_entrega` text,
  `frete` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `fk_entregas_pedidos` (`id_pedido`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `entregas`
--

INSERT INTO `entregas` (`id`, `id_pedido`, `metodo_envio`, `status_envio`, `endereco_entrega`, `frete`) VALUES
(8, 9, 'entrega', 'aguardando', NULL, 0.00),
(10, 10, 'entrega', 'aguardando', NULL, 0.00),
(12, 11, 'entrega', 'aguardando', NULL, 0.00),
(13, 11, 'entrega', 'aguardando', NULL, 0.00),
(14, 12, 'entrega', 'aguardando', NULL, 0.00),
(15, 12, 'entrega', 'aguardando', NULL, 0.00),
(16, 13, 'entrega', 'aguardando', NULL, 0.00),
(17, 13, 'entrega', 'aguardando', NULL, 0.00),
(18, 14, 'entrega', 'aguardando', NULL, 0.00),
(19, 14, 'entrega', 'aguardando', NULL, 0.00),
(20, 15, '', 'aguardando', NULL, 0.00),
(21, 15, '', 'aguardando', NULL, 0.00),
(22, 16, 'entrega', 'aguardando', NULL, 0.00),
(23, 16, 'entrega', 'aguardando', NULL, 0.00),
(24, 17, 'entrega', 'aguardando', NULL, 0.00),
(25, 17, 'entrega', 'aguardando', NULL, 0.00),
(26, 18, 'entrega', 'aguardando', NULL, 0.00),
(27, 18, 'entrega', 'aguardando', NULL, 0.00),
(28, 19, 'entrega', 'aguardando', NULL, 0.00),
(29, 19, 'entrega', 'aguardando', NULL, 0.00),
(30, 20, 'entrega', 'aguardando', NULL, 0.00),
(31, 20, 'entrega', 'aguardando', NULL, 0.00),
(32, 21, 'entrega', 'aguardando', NULL, 0.00),
(33, 21, 'entrega', 'aguardando', NULL, 0.00),
(34, 22, 'entrega', 'aguardando', NULL, 0.00),
(35, 22, 'entrega', 'aguardando', NULL, 0.00),
(36, 23, 'entrega', 'aguardando', NULL, 0.00),
(37, 23, 'entrega', 'aguardando', NULL, 0.00),
(38, 24, 'entrega', 'aguardando', NULL, 0.00),
(39, 24, 'entrega', 'aguardando', NULL, 0.00),
(40, 25, 'entrega', 'aguardando', NULL, 0.00),
(41, 25, 'entrega', 'aguardando', NULL, 0.00),
(42, 26, 'entrega', 'aguardando', NULL, 0.00),
(43, 26, 'entrega', 'aguardando', NULL, 0.00),
(44, 27, 'entrega', 'aguardando', NULL, 3.00),
(45, 28, 'entrega', 'aguardando', NULL, 3.00),
(46, 29, 'entrega', 'aguardando', NULL, 3.00),
(47, 30, 'entrega', 'aguardando', NULL, 3.00),
(48, 31, 'entrega', 'aguardando', NULL, 3.00),
(49, 32, 'entrega', 'aguardando', NULL, 3.00),
(50, 33, 'entrega', 'aguardando', NULL, 3.00),
(51, 34, 'entrega', 'aguardando', NULL, 3.00),
(52, 35, '', 'aguardando', NULL, 0.00),
(53, 36, '', 'aguardando', NULL, 0.00),
(54, 37, '', 'aguardando', NULL, 0.00),
(55, 38, '', 'aguardando', NULL, 0.00),
(56, 39, '', 'aguardando', NULL, 0.00),
(57, 40, '', 'aguardando', NULL, 0.00),
(58, 41, '', 'aguardando', NULL, 0.00),
(59, 42, '', 'aguardando', NULL, 0.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens_pedido`
--

DROP TABLE IF EXISTS `itens_pedido`;
CREATE TABLE IF NOT EXISTS `itens_pedido` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_pedido` int DEFAULT NULL,
  `id_produto` int DEFAULT NULL,
  `quantidade` int NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_pedido` (`id_pedido`),
  KEY `id_produto` (`id_produto`)
) ENGINE=InnoDB AUTO_INCREMENT=121 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `itens_pedido`
--

INSERT INTO `itens_pedido` (`id`, `id_pedido`, `id_produto`, `quantidade`, `preco_unitario`) VALUES
(1, 1, 1, 1, 4.75),
(2, 1, 1, 1, 4.75),
(3, 2, 1, 1, 19.00),
(4, 2, 2, 1, 17.00),
(5, 2, 3, 1, 6.00),
(6, 2, 4, 1, 4.00),
(7, 3, 1, 1, 19.00),
(8, 3, 2, 1, 17.00),
(9, 3, 3, 1, 6.00),
(10, 3, 4, 1, 4.00),
(11, 4, 1, 1, 19.00),
(12, 4, 2, 1, 17.00),
(13, 4, 3, 1, 6.00),
(14, 4, 4, 1, 4.00),
(15, 5, 1, 1, 19.00),
(16, 5, 2, 1, 17.00),
(17, 5, 3, 1, 6.00),
(18, 5, 4, 1, 4.00),
(19, 6, 1, 1, 19.00),
(20, 6, 2, 1, 17.00),
(21, 6, 3, 1, 6.00),
(22, 6, 4, 1, 4.00),
(23, 7, 1, 1, 19.00),
(24, 7, 2, 1, 17.00),
(25, 7, 3, 1, 6.00),
(26, 7, 4, 1, 4.00),
(27, 8, 1, 1, 19.00),
(28, 8, 2, 1, 17.00),
(29, 8, 3, 1, 6.00),
(30, 8, 4, 1, 4.00),
(31, 9, 1, 1, 19.00),
(32, 9, 2, 1, 17.00),
(33, 9, 3, 1, 6.00),
(34, 9, 4, 1, 4.00),
(35, 10, 1, 1, 19.00),
(36, 10, 2, 1, 17.00),
(37, 10, 3, 1, 6.00),
(38, 10, 4, 2, 4.00),
(39, 11, 1, 1, 19.00),
(40, 11, 2, 1, 17.00),
(41, 11, 3, 1, 6.00),
(42, 11, 4, 2, 4.00),
(43, 12, 1, 1, 19.00),
(44, 12, 2, 1, 17.00),
(45, 12, 3, 1, 6.00),
(46, 12, 4, 1, 4.00),
(47, 13, 2, 1, 17.00),
(48, 13, 4, 1, 4.00),
(49, 14, 1, 1, 19.00),
(50, 14, 2, 1, 17.00),
(51, 14, 3, 1, 6.00),
(52, 14, 4, 1, 4.00),
(53, 15, 1, 1, 19.00),
(54, 15, 2, 1, 17.00),
(55, 15, 3, 1, 6.00),
(56, 15, 4, 1, 4.00),
(57, 16, 1, 1, 19.00),
(58, 16, 2, 1, 17.00),
(59, 16, 3, 1, 6.00),
(60, 16, 4, 1, 4.00),
(61, 17, 1, 1, 19.00),
(62, 17, 2, 1, 17.00),
(63, 17, 3, 1, 6.00),
(64, 17, 4, 1, 4.00),
(65, 18, 1, 1, 19.00),
(66, 18, 2, 1, 17.00),
(67, 18, 3, 1, 6.00),
(68, 18, 4, 1, 4.00),
(69, 19, 1, 1, 19.00),
(70, 19, 2, 1, 17.00),
(71, 19, 3, 1, 6.00),
(72, 19, 4, 3, 4.00),
(73, 20, 1, 1, 19.00),
(74, 20, 2, 1, 17.00),
(75, 20, 3, 1, 6.00),
(76, 20, 4, 1, 4.00),
(77, 21, 1, 1, 19.00),
(78, 21, 2, 1, 17.00),
(79, 21, 3, 1, 6.00),
(80, 21, 4, 1, 4.00),
(81, 22, 1, 1, 19.00),
(82, 22, 2, 1, 17.00),
(83, 22, 3, 1, 6.00),
(84, 22, 4, 1, 4.00),
(85, 23, 1, 1, 19.00),
(86, 23, 2, 1, 17.00),
(87, 23, 3, 1, 6.00),
(88, 23, 4, 1, 4.00),
(89, 24, 1, 1, 19.00),
(90, 24, 2, 1, 17.00),
(91, 24, 3, 1, 6.00),
(92, 24, 4, 1, 4.00),
(93, 25, 1, 1, 19.00),
(94, 25, 2, 1, 17.00),
(95, 25, 3, 1, 6.00),
(96, 25, 4, 1, 4.00),
(97, 26, 1, 1, 19.00),
(98, 26, 2, 2, 17.00),
(99, 26, 3, 1, 6.00),
(100, 26, 4, 1, 4.00),
(101, 27, 4, 1, 4.00),
(102, 28, 1, 1, 19.00),
(103, 28, 2, 1, 17.00),
(104, 28, 4, 1, 4.00),
(105, 29, 2, 1, 17.00),
(106, 29, 4, 1, 4.00),
(107, 30, 2, 1, 17.00),
(108, 30, 4, 1, 4.00),
(109, 31, 4, 1, 4.00),
(110, 32, 2, 1, 17.00),
(111, 33, 4, 2, 4.00),
(112, 34, 2, 1, 17.00),
(113, 35, 1, 1, 19.00),
(114, 36, 4, 1, 4.00),
(115, 37, 4, 1, 4.00),
(116, 38, 2, 1, 17.00),
(117, 39, 4, 1, 4.00),
(118, 40, 1, 1, 19.00),
(119, 41, 1, 1, 19.00),
(120, 42, 2, 1, 17.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pagamentos`
--

DROP TABLE IF EXISTS `pagamentos`;
CREATE TABLE IF NOT EXISTS `pagamentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_pedido` int DEFAULT NULL,
  `metodo_pagamento` enum('cartao','boleto','pix','dinheiro') NOT NULL,
  `status_pagamento` varchar(20) DEFAULT 'pendente',
  `valor` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_pedido` (`id_pedido`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `pagamentos`
--

INSERT INTO `pagamentos` (`id`, `id_pedido`, `metodo_pagamento`, `status_pagamento`, `valor`) VALUES
(1, 1, 'pix', 'pendente', 9.50),
(2, 2, 'pix', 'pendente', 49.00),
(3, 3, 'pix', 'pendente', 49.00),
(4, 27, 'pix', 'pendente', 7.00),
(5, 28, 'dinheiro', 'pendente', 43.00),
(6, 29, 'pix', 'pendente', 24.00),
(7, 30, 'pix', 'pendente', 24.00),
(8, 31, 'pix', 'pendente', 7.00),
(9, 32, 'pix', 'pendente', 20.00),
(10, 33, 'pix', 'pendente', 11.00),
(11, 34, 'pix', 'pendente', 20.00),
(12, 35, 'pix', 'pendente', 19.00),
(13, 36, 'pix', 'pendente', 4.00),
(14, 37, 'pix', 'pendente', 4.00),
(15, 38, 'pix', 'pendente', 17.00),
(16, 39, 'pix', 'pendente', 4.00),
(17, 40, 'pix', 'pendente', 19.00),
(18, 41, 'pix', 'pendente', 19.00),
(19, 42, 'pix', 'pendente', 17.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
CREATE TABLE IF NOT EXISTS `pedidos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_cliente` int DEFAULT NULL,
  `data_pedido` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `valor_total` decimal(10,2) NOT NULL,
  `status` varchar(20) DEFAULT 'pendente',
  `usuario_id` int DEFAULT NULL,
  `observacoes` text,
  PRIMARY KEY (`id`),
  KEY `id_cliente` (`id_cliente`),
  KEY `fk_pedidos_usuarios` (`usuario_id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `pedidos`
--

INSERT INTO `pedidos` (`id`, `id_cliente`, `data_pedido`, `valor_total`, `status`, `usuario_id`, `observacoes`) VALUES
(1, 1, '2025-11-29 17:58:55', 9.50, 'entregue', NULL, NULL),
(2, 1, '2025-11-29 20:42:20', 49.00, 'entregue', NULL, NULL),
(3, 1, '2025-11-29 20:42:29', 49.00, 'entregue', NULL, NULL),
(4, 1, '2025-11-29 20:43:51', 49.00, 'entregue', NULL, NULL),
(5, 1, '2025-11-29 20:44:00', 46.00, 'entregue', NULL, NULL),
(6, 1, '2025-11-29 21:10:29', 46.00, 'entregue', NULL, NULL),
(7, 1, '2025-11-29 21:11:11', 46.00, 'entregue', NULL, NULL),
(8, 1, '2025-11-29 21:12:32', 49.00, 'entregue', NULL, NULL),
(9, 1, '2025-11-29 21:14:11', 49.00, 'entregue', NULL, NULL),
(10, 1, '2025-11-29 21:14:17', 53.00, 'entregue', NULL, NULL),
(11, 1, '2025-11-29 21:33:48', 53.00, 'entregue', NULL, NULL),
(12, 1, '2025-11-29 21:37:52', 49.00, 'entregue', NULL, NULL),
(13, 1, '2025-11-29 22:13:21', 24.00, 'entregue', NULL, NULL),
(14, 1, '2025-11-29 22:13:33', 49.00, 'entregue', NULL, NULL),
(15, 1, '2025-11-29 23:32:46', 46.00, 'entregue', NULL, NULL),
(16, 1, '2025-11-29 23:48:42', 49.00, 'entregue', NULL, NULL),
(17, 1, '2025-11-30 00:35:04', 49.00, 'entregue', NULL, NULL),
(18, 2, '2025-11-30 00:36:59', 49.00, 'entregue', NULL, NULL),
(19, 1, '2025-11-30 13:24:52', 57.00, 'entregue', NULL, NULL),
(20, 1, '2025-11-30 13:26:08', 49.00, 'entregue', NULL, NULL),
(21, 1, '2025-11-30 13:26:13', 49.00, 'entregue', NULL, NULL),
(22, 1, '2025-11-30 13:30:24', 49.00, 'entregue', NULL, NULL),
(23, 1, '2025-11-30 13:33:50', 49.00, 'entregue', NULL, NULL),
(24, 1, '2025-11-30 17:26:16', 49.00, 'entregue', NULL, NULL),
(25, 2, '2025-12-09 19:19:12', 49.00, 'entregue', NULL, NULL),
(26, 1, '2025-12-11 16:38:54', 66.00, 'entregue', NULL, NULL),
(27, 1, '2025-12-12 13:53:51', 7.00, 'entregue', NULL, NULL),
(28, 1, '2025-12-12 14:32:48', 43.00, 'entregue', 3, NULL),
(29, 1, '2025-12-12 14:33:21', 24.00, 'entregue', 3, NULL),
(30, 1, '2025-12-12 15:06:08', 24.00, 'entregue', 3, NULL),
(31, 1, '2025-12-12 15:07:26', 7.00, 'entregue', 3, NULL),
(32, 1, '2025-12-12 17:19:36', 20.00, 'entregue', 1, NULL),
(33, 1, '2025-12-13 12:32:55', 11.00, 'entregue', 1, NULL),
(34, 1, '2025-12-13 12:43:09', 20.00, 'entregue', 1, NULL),
(35, 1, '2025-12-13 12:43:38', 19.00, 'entregue', 1, NULL),
(36, 1, '2025-12-13 12:45:20', 4.00, 'entregue', 3, NULL),
(37, 1, '2025-12-13 13:24:32', 4.00, 'entregue', 1, 'sssssssssssssssssssss'),
(38, 1, '2025-12-13 13:48:42', 17.00, 'pendente', 1, ''),
(39, NULL, '2026-04-16 11:47:42', 4.00, 'pendente', 7, ''),
(40, NULL, '2026-04-16 11:47:56', 19.00, 'pendente', 7, ''),
(41, NULL, '2026-04-16 12:09:40', 19.00, 'pendente', 7, ''),
(42, NULL, '2026-04-16 12:10:41', 17.00, 'pendente', 7, '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

DROP TABLE IF EXISTS `produtos`;
CREATE TABLE IF NOT EXISTS `produtos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `descricao` text,
  `preco` decimal(10,2) NOT NULL,
  `estoque` int DEFAULT '0',
  `criado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `descricao`, `preco`, `estoque`, `criado_em`) VALUES
(1, 'Marmita grande', 'Marmita grande serve até duas pessoas.', 19.00, 50, '2025-11-29 18:41:48'),
(2, 'Marmita pequena', 'Marmita pequena serve uma pessoa.', 17.00, 50, '2025-11-29 18:41:48'),
(3, 'Coca-Cola 600ml gelada', 'Coca-Cola 600ml estupidamente no ponto. Acompanha canudo', 6.00, 100, '2025-11-29 18:41:48'),
(4, 'Coca-Cola de lata', 'Coca-Cola 350ml estupidamente no ponto. Acompanha canudo', 4.00, 100, '2025-11-29 18:41:48');

-- --------------------------------------------------------

--
-- Estrutura para tabela `recuperacao_codigo`
--

DROP TABLE IF EXISTS `recuperacao_codigo`;
CREATE TABLE IF NOT EXISTS `recuperacao_codigo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `codigo` varchar(100) NOT NULL,
  `expiracao` datetime NOT NULL,
  `expira` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_recuperacao_usuario` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `recuperacao_codigo`
--

INSERT INTO `recuperacao_codigo` (`id`, `email`, `codigo`, `expiracao`, `expira`) VALUES
(1, 'Darksity09@gmail.com', '077473', '0000-00-00 00:00:00', '2026-01-06 00:13:56'),
(2, 'dimitri.2023317573@aluno.iffar.edu.br', '240788', '0000-00-00 00:00:00', '2026-03-27 17:50:21'),
(3, 'dimitri.2023317573@aluno.iffar.edu.br', '963702', '0000-00-00 00:00:00', '2026-03-27 18:00:01'),
(4, 'dimitri.2023317573@aluno.iffar.edu.br', '921946', '0000-00-00 00:00:00', '2026-03-27 18:00:47');

-- --------------------------------------------------------

--
-- Estrutura para tabela `statusloja`
--

DROP TABLE IF EXISTS `statusloja`;
CREATE TABLE IF NOT EXISTS `statusloja` (
  `id` int NOT NULL AUTO_INCREMENT,
  `aberto` tinyint(1) NOT NULL DEFAULT '0',
  `cardapio_do_dia` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `statusloja`
--

INSERT INTO `statusloja` (`id`, `aberto`, `cardapio_do_dia`) VALUES
(1, 1, '<p>Arroz, feijão, espaguete, pure de batata e frango a milanesa</p>');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha_hash` varchar(255) NOT NULL,
  `tipo` enum('admin','cliente') NOT NULL DEFAULT 'cliente',
  `token_recuperacao` varchar(255) DEFAULT NULL,
  `token_expira_em` datetime DEFAULT NULL,
  `cpf` varchar(14) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha_hash`, `tipo`, `token_recuperacao`, `token_expira_em`, `cpf`, `telefone`, `endereco`, `data_nascimento`) VALUES
(1, 'Dimitri', 'Darksity09@gmail.com', '$2y$10$Wequv8dYkkaSn4Xe0YO3u.b08ThEpV10mijp02Xr8A2Slbz4wVpWG', 'admin', NULL, NULL, '', '', '', '0000-00-00'),
(2, 'Dimitri', 'dimitri.2023317573@aluno.iffar.edu.br', '$2y$10$SOXI5oNrSdNSFNZsXa2mruEKOytn6COTYcMIHhxDi4zWHzOYhddQW', 'cliente', 'becc064d120806ffa563ff37ddd5c77b99e0582a6f3615c1e5f17b211e39dec1', '2025-12-13 16:03:09', NULL, NULL, NULL, NULL),
(3, 'Gabriela Tavares', 'tavareti@gmail.com', '$2y$10$akZwQIUfsUYX7j1KxD.yVONtvfAA3Xn65hHBYVn6bxm/xooK47LVS', 'cliente', 'a9cca2ed75af2e78751acf2de9bc14699395af8b6fe3ed74727f6264bc130d8b', '2025-12-12 16:20:29', '05793542027', '55998733456', '', '2008-02-11'),
(4, 'Dimitri', 'dimitri55@gmail.com', '$2y$10$Ykv.qsijC.Qj5H9vIgbuZed3mZXexKNjj4BrCTafvt1qofihzMwZW', 'cliente', NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'Dimitri Mod', 'dimitri.mod@gmail.com', '$2y$10$2kdYvlnxDwYtLqHrbPG0iegXggttSXcbmveC9omV.k2P.blV2rUlW', 'cliente', NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'Admin 1', 'admin123@gmail.com', '$2y$10$iG8/mnRaw0HGdD3MWbuk3e6DyolR8wEf0pO4sQ/ZSTv1RFFg7A75u', 'admin', NULL, NULL, NULL, NULL, NULL, NULL),
(7, 'Cliente 1', 'cliente123@gmail.com', '$2y$10$s8FJvoiFku8ZPO0MMbaYke/DZS13KYYcqDTl4JwhgdXU6UsxetFFa', 'cliente', NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `entregas`
--
ALTER TABLE `entregas`
  ADD CONSTRAINT `fk_entregas_pedidos` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id`);

--
-- Restrições para tabelas `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD CONSTRAINT `fk_itens_pedido_pedidos` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `fk_itens_pedido_produtos` FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`id`);

--
-- Restrições para tabelas `pagamentos`
--
ALTER TABLE `pagamentos`
  ADD CONSTRAINT `fk_pagamentos_pedidos` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id`);

--
-- Restrições para tabelas `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `fk_pedidos_clientes` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `fk_pedidos_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `recuperacao_codigo`
--
ALTER TABLE `recuperacao_codigo`
  ADD CONSTRAINT `fk_recuperacao_usuario` FOREIGN KEY (`email`) REFERENCES `usuarios` (`email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
