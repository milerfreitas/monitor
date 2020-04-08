-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 07/04/2020 às 23:16
-- Versão do servidor: 5.7.29-0ubuntu0.18.04.1
-- Versão do PHP: 7.2.24-0ubuntu0.18.04.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `monitor`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `server`
--

CREATE TABLE `server` (
  `id` int(10) NOT NULL,
  `server` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `server`
--

INSERT INTO `server` (`id`, `server`) VALUES
(1, 'localhost');

-- --------------------------------------------------------

--
-- Estrutura para tabela `server_data`
--

CREATE TABLE `server_data` (
  `id` int(10) NOT NULL,
  `server_id` int(10) NOT NULL,
  `service` varchar(40) NOT NULL,
  `port` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `server_data`
--

INSERT INTO `server_data` (`id`, `server_id`, `service`, `port`) VALUES
(1, 1, 'Apache', 80),
(2, 1, 'MySQL', 3306),
(3, 1, 'FTP', 21),
(4, 1, 'DNS', 53);

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `server`
--
ALTER TABLE `server`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `server_data`
--
ALTER TABLE `server_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_servers` (`server_id`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `server`
--
ALTER TABLE `server`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de tabela `server_data`
--
ALTER TABLE `server_data`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- Restrições para dumps de tabelas
--

--
-- Restrições para tabelas `server_data`
--
ALTER TABLE `server_data`
  ADD CONSTRAINT `fk_servers` FOREIGN KEY (`server_id`) REFERENCES `server` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
